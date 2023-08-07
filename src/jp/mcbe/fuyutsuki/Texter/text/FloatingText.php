<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\text;

use http\Exception\InvalidArgumentException;
use JsonException;
use pocketmine\entity\Entity;
use pocketmine\entity\Skin;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\AddPlayerPacket;
use pocketmine\network\mcpe\protocol\ClientboundPacket;
use pocketmine\network\mcpe\protocol\MoveActorAbsolutePacket;
use pocketmine\network\mcpe\protocol\PlayerListPacket;
use pocketmine\network\mcpe\protocol\RemoveActorPacket;
use pocketmine\network\mcpe\protocol\SetActorDataPacket;
use pocketmine\network\mcpe\protocol\types\AbilitiesData;
use pocketmine\network\mcpe\protocol\types\command\CommandPermissions;
use pocketmine\network\mcpe\protocol\types\DeviceOS;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataFlags;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataProperties;
use pocketmine\network\mcpe\protocol\types\entity\FloatMetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\LongMetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\PropertySyncData;
use pocketmine\network\mcpe\protocol\types\entity\StringMetadataProperty;
use pocketmine\network\mcpe\protocol\types\GameMode;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStack;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;
use pocketmine\network\mcpe\protocol\types\PlayerListEntry;
use pocketmine\network\mcpe\protocol\types\PlayerPermissions;
use pocketmine\network\mcpe\protocol\UpdateAbilitiesPacket;
use pocketmine\player\Player;
use pocketmine\world\World;
use Ramsey\Uuid\Uuid;

class FloatingText implements Sendable {

	private string $text;
	private int $actorRuntimeId;

	public function __construct(
		private Vector3 $position,
		string $text,
		private FloatingTextCluster $parent,
		int $actorRuntimeId = 0
	) {
		$this->setText($text);
		$this->setParent($parent);
		$this->setActorRuntimeId($actorRuntimeId);
	}

	public function position(): Vector3 {
		return $this->position;
	}

	public function setPosition(Vector3 $position) {
		$this->position = $position;
	}

	public function text(): string {
		return str_replace("\n", "#", $this->text);
	}

	public function setText(string $text) {
		$this->text = str_replace("#", "\n", $text);
	}

	public function actorRuntimeId(): int {
		return $this->actorRuntimeId;
	}

	public function setActorRuntimeId(int $actorRuntimeId) {
		$this->actorRuntimeId = $actorRuntimeId === 0 ? Entity::nextRuntimeId() : $actorRuntimeId;
	}

	public function parent(): FloatingTextCluster {
		return $this->parent;
	}

	public function setParent(FloatingTextCluster $parent) {
		$this->parent = $parent;
	}

	/**
	 * @param SendType $type
	 * @return ClientboundPacket[]
	 * @throws JsonException
	 */
	public function asPackets(SendType $type): array {
		switch ($type) {
			# HACK: BlameMojuncrosoft
			case SendType::ADD:
				$uuid = Uuid::uuid4();

				$apk = PlayerListPacket::add([
					PlayerListEntry::createAdditionEntry(
						$uuid,
						$this->actorRuntimeId,
						"",
						TypeConverter::getInstance()->getSkinAdapter()->toSkinData(new Skin(
							"Standard_Custom",
							str_repeat("\x00", 8192),
							"",
							"geometry.humanoid.custom"
						))
					)
				]);

				$pk = AddPlayerPacket::create(
					$uuid,
					$this->text,
					$this->actorRuntimeId,
					"",
					$this->position,
					null,
					0.0,
					0.0,
					0.0,
					ItemStackWrapper::legacy(ItemStack::null()),
					GameMode::ADVENTURE,
					[
						EntityMetadataProperties::FLAGS => LongMetadataProperty::buildFromFlags([
							EntityMetadataFlags::IMMOBILE => true,
						]),
						EntityMetadataProperties::SCALE => new FloatMetadataProperty(0.0),
					],
					new PropertySyncData([], []),
					UpdateAbilitiesPacket::create(new AbilitiesData(CommandPermissions::NORMAL, PlayerPermissions::VISITOR, $this->actorRuntimeId, [])),
					[],
					"",
					DeviceOS::UNKNOWN
				);

				$rpk = PlayerListPacket::remove([
					PlayerListEntry::createRemovalEntry($uuid),
				]);
				$pks = [$apk, $pk, $rpk];
				break;

			case SendType::EDIT:
				$pk = SetActorDataPacket::create(
					$this->actorRuntimeId,
					[
						EntityMetadataProperties::NAMETAG => new StringMetadataProperty($this->text),
					],
					new PropertySyncData([], []),
					0
				);
				$pks = [$pk];
				break;

			case SendType::MOVE:
				$pk = MoveActorAbsolutePacket::create(
					$this->actorRuntimeId,
					$this->position->up()->add(0, 0.6, 0),
					0.0,
					0.0,
					0.0,
					MoveActorAbsolutePacket::FLAG_TELEPORT
				);
				$pks = [$pk];
				break;

			case SendType::REMOVE:
				$pk = RemoveActorPacket::create($this->actorRuntimeId);
				$pks = [$pk];
				break;

			default:
				throw new InvalidArgumentException("The SendType must be an enum value SendType::ADD, SendType::EDIT, SendType::MOVE or SendType::REMOVE");
		}
		return $pks;
	}

	public function sendToPlayer(Player $player, SendType $type): void {
		$pks = $this->asPackets($type);
		foreach ($pks as $pk) {
			$player->getNetworkSession()->sendDataPacket($pk);
		}
	}

	public function sendToPlayers(array $players, SendType $type): void {
		foreach ($players as $player) {
			$this->sendToPlayer($player, $type);
		}
	}

	public function sendToWorld(World $world, SendType $type): void {
		$this->sendToPlayers($world->getPlayers(), $type);
	}

}