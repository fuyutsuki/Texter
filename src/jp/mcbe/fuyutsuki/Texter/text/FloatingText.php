<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\text;

use aieuo\mineflow\variable\DefaultVariables;
use http\Exception\InvalidArgumentException;
use jp\mcbe\fuyutsuki\Texter\mineflow\variable\FloatingTextObjectVariable;
use jp\mcbe\fuyutsuki\Texter\util\dependencies\Mineflow;
use JsonException;
use pocketmine\entity\Entity;
use pocketmine\entity\Skin;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\convert\SkinAdapterSingleton;
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
use pocketmine\network\mcpe\protocol\types\inventory\ItemStack;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;
use pocketmine\network\mcpe\protocol\types\PlayerListEntry;
use pocketmine\network\mcpe\protocol\types\PlayerPermissions;
use pocketmine\network\mcpe\protocol\UpdateAbilitiesPacket;
use pocketmine\player\GameMode;
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

	public function replaceVariables(Player $player): string {
		$text = $this->text;
		if (Mineflow::isAvailable()) {
			$helper = Mineflow::variableHelper();
			if ($helper->containsVariable($text)) {
				$variables = DefaultVariables::getPlayerVariables($player, "player")
					+ [FloatingTextObjectVariable::DEFAULT_NAME => new FloatingTextObjectVariable($this->parent)];
				$text = $helper->replaceVariables($text, $variables);
			}
		}
		return $text;
	}

	/**
	 * @param Player $player
	 * @param SendType $type
	 * @return ClientboundPacket[]
	 * @throws JsonException
	 */
	public function asPackets(Player $player, SendType $type): array {
		switch ($type) {
			# HACK: BlameMojuncrosoft
			case SendType::ADD():
				$uuid = Uuid::uuid4();

				$apk = PlayerListPacket::add([
					PlayerListEntry::createAdditionEntry(
						$uuid,
						$this->actorRuntimeId,
						"",
						SkinAdapterSingleton::get()->toSkinData(new Skin(
							"Standard_Custom",
							str_repeat("\x00", 8192),
							"",
							"geometry.humanoid.custom"
						))
					)
				]);

				$pk = AddPlayerPacket::create(
					$uuid,
					$this->replaceVariables($player),
					$this->actorRuntimeId,
					"",
					$this->position,
					null,
					0.0,
					0.0,
					0.0,
					ItemStackWrapper::legacy(ItemStack::null()),
					GameMode::ADVENTURE()->id(),
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

			case SendType::EDIT():
				$pk = SetActorDataPacket::create(
					$this->actorRuntimeId,
					[
						EntityMetadataProperties::NAMETAG => new StringMetadataProperty($this->replaceVariables($player)),
					],
					new PropertySyncData([], []),
					0
				);
				$pks = [$pk];
				break;

			case SendType::MOVE():
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

			case SendType::REMOVE():
				$pk = RemoveActorPacket::create($this->actorRuntimeId);
				$pks = [$pk];
				break;

			default:
				throw new InvalidArgumentException("The SendType must be an enum value SendType::ADD, SendType::EDIT, SendType::MOVE or SendType::REMOVE");
		}
		return $pks;
	}

	public function sendToPlayer(Player $player, SendType $type): void {
		$pks = $this->asPackets($player, $type);
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