<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\text;

use pocketmine\block\VanillaBlocks;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\RemoveActorPacket;
use pocketmine\network\mcpe\protocol\SetActorDataPacket;
use pocketmine\network\mcpe\protocol\types\entity\ByteMetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataFlags;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataProperties;
use pocketmine\network\mcpe\protocol\types\entity\FloatMetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\IntMetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\LongMetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\PropertySyncData;
use pocketmine\network\mcpe\protocol\types\entity\StringMetadataProperty;
use pocketmine\player\Player;
use pocketmine\world\World;

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

	public function asPackets(SendType $type): array {
		return match ($type) {
			SendType::ADD => [
				AddActorPacket::create(
					$this->actorRuntimeId,
					$this->actorRuntimeId,
					EntityIds::FALLING_BLOCK,
					$this->position,
					motion: null,
					pitch: 0.0,
					yaw: 0.0,
					headYaw: 0.0,
					bodyYaw: 0.0,
					attributes: [],
					metadata: [
						EntityMetadataProperties::ALWAYS_SHOW_NAMETAG => new ByteMetadataProperty(1),
						EntityMetadataProperties::BOUNDING_BOX_HEIGHT => new FloatMetadataProperty(0.0),
						EntityMetadataProperties::BOUNDING_BOX_WIDTH => new FloatMetadataProperty(0.0),
						EntityMetadataProperties::FLAGS => LongMetadataProperty::buildFromFlags([
							EntityMetadataFlags::IMMOBILE => true,
						]),
						EntityMetadataProperties::NAMETAG => new StringMetadataProperty($this->text),
						EntityMetadataProperties::SCALE => new FloatMetadataProperty(0.0),
						EntityMetadataProperties::VARIANT => new IntMetadataProperty(TypeConverter::getInstance()->getBlockTranslator()->internalIdToNetworkId(VanillaBlocks::AIR()->getStateId()))
					],
					syncedProperties: new PropertySyncData([], []),
					links: []
				),
			],
			SendType::EDIT => [
				SetActorDataPacket::create(
					$this->actorRuntimeId,
					metadata: [
						EntityMetadataProperties::NAMETAG => new StringMetadataProperty($this->text),
					],
					syncedProperties: new PropertySyncData([], []),
					tick: 0
				),
			],
			SendType::MOVE => [
				// MoveActorAbsolutePacket::create(
				// 	$this->actorRuntimeId,
				// 	$this->position,
				// 	pitch: 0.0,
				// 	yaw: 0.0,
				// 	headYaw: 0.0,
				// 	flags: MoveActorAbsolutePacket::FLAG_TELEPORT
				// ),
				RemoveActorPacket::create(
					$this->actorRuntimeId
				),
				AddActorPacket::create(
					$this->actorRuntimeId,
					$this->actorRuntimeId,
					EntityIds::FALLING_BLOCK,
					$this->position,
					motion: null,
					pitch: 0.0,
					yaw: 0.0,
					headYaw: 0.0,
					bodyYaw: 0.0,
					attributes: [],
					metadata: [
						EntityMetadataProperties::ALWAYS_SHOW_NAMETAG => new ByteMetadataProperty(1),
						EntityMetadataProperties::BOUNDING_BOX_HEIGHT => new FloatMetadataProperty(0.0),
						EntityMetadataProperties::BOUNDING_BOX_WIDTH => new FloatMetadataProperty(0.0),
						EntityMetadataProperties::FLAGS => LongMetadataProperty::buildFromFlags([
							EntityMetadataFlags::IMMOBILE => true,
						]),
						EntityMetadataProperties::NAMETAG => new StringMetadataProperty($this->text),
						EntityMetadataProperties::SCALE => new FloatMetadataProperty(0.0),
						EntityMetadataProperties::VARIANT => new IntMetadataProperty(TypeConverter::getInstance()->getBlockTranslator()->internalIdToNetworkId(VanillaBlocks::AIR()->getStateId()))
					],
					syncedProperties: new PropertySyncData([], []),
					links: []
				),
			],
			SendType::REMOVE => [
				RemoveActorPacket::create(
					$this->actorRuntimeId
				),
			],
		};
	}

	public function sendToPlayer(Player $player, SendType $type): void {
		$packets = $this->asPackets($type);
		foreach ($packets as $packet) {
			$player->getNetworkSession()->sendDataPacket($packet);
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