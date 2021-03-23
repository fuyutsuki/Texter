<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\text;

use aieuo\mineflow\variable\DefaultVariables;
use http\Exception\InvalidArgumentException;
use jp\mcbe\fuyutsuki\Texter\mineflow\variable\FloatingTextObjectVariable;
use jp\mcbe\fuyutsuki\Texter\util\dependencies\Mineflow;
use pocketmine\entity\Entity;
use pocketmine\entity\Skin;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\AddPlayerPacket;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\PlayerListPacket;
use pocketmine\network\mcpe\protocol\RemoveActorPacket;
use pocketmine\network\mcpe\protocol\SetActorDataPacket;
use pocketmine\network\mcpe\protocol\types\PlayerListEntry;
use pocketmine\network\mcpe\protocol\types\SkinAdapterSingleton;
use pocketmine\Player;
use pocketmine\utils\UUID;

/**
 * Class FloatingText
 * @package jp\mcbe\fuyutsuki\Texter\text
 */
class FloatingText implements Sendable {

	/** @var Vector3 */
	private $position;
	/** @var string */
	private $text;
	/** @var FloatingTextCluster */
	private $parent;
	/** @var int */
	private $entityRuntimeId;

	public function __construct(
		Vector3 $position,
		string $text,
		FloatingTextCluster $parent,
		int $entityRuntimeId = 0
	) {
		$this->setPosition($position);
		$this->setText($text);
		$this->setParent($parent);
		$this->setEntityRuntimeId($entityRuntimeId);
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

	public function entityRuntimeId(): int {
		return $this->entityRuntimeId;
	}

	public function setEntityRuntimeId(int $entityRuntimeId) {
		$this->entityRuntimeId = $entityRuntimeId === 0 ? Entity::$entityCount++ : $entityRuntimeId;
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
	 * @return DataPacket[]
	 */
	public function asPackets(Player $player, SendType $type): array {
		switch ($type->value()) {
			# BLAME "MOJUNCROSOFT" on 1.13
			case SendType::ADD:
			case SendType::MOVE:
				$uuid = UUID::fromRandom();

				$apk = new PlayerListPacket;
				$apk->type = PlayerListPacket::TYPE_ADD;
				$apk->entries = [PlayerListEntry::createAdditionEntry(
					$uuid,
					$this->entityRuntimeId,
					"",
					SkinAdapterSingleton::get()->toSkinData(new Skin(
						"Standard_Custom",
						str_repeat("\x00", 8192),
						"",
						"geometry.humanoid.custom"
					))
				)];

				$pk = new AddPlayerPacket;
				$pk->username = $this->replaceVariables($player);
				$pk->uuid = $uuid;
				$pk->entityRuntimeId = $this->entityRuntimeId;
				$pk->entityUniqueId = $this->entityRuntimeId;
				$pk->position = $this->position;
				$pk->item = Item::get(ItemIds::AIR);
				$pk->metadata = [
					Entity::DATA_FLAGS => [
						Entity::DATA_TYPE_LONG, 1 << Entity::DATA_FLAG_IMMOBILE
					],
					Entity::DATA_SCALE => [
						Entity::DATA_TYPE_FLOAT, 0
					]
				];

				$rpk = new PlayerListPacket;
				$rpk->type = PlayerListPacket::TYPE_REMOVE;
				$rpk->entries = [PlayerListEntry::createRemovalEntry($uuid)];
				$pks = [$apk, $pk, $rpk];
				break;

			case SendType::EDIT:
				$pk = new SetActorDataPacket;
				$pk->entityRuntimeId = $this->entityRuntimeId;
				$pk->metadata = [
					Entity::DATA_NAMETAG => [
						Entity::DATA_NAMETAG, $this->replaceVariables($player)
					]
				];
				$pks = [$pk];
				break;

			/*
			# BLAME "MOJUNCROSOFT"
			case SendType::MOVE:
				$pk = new MoveActorAbsolutePacket;
				$pk->entityRuntimeId = $this->entityRuntimeId;
				$pk->flags = MoveActorAbsolutePacket::FLAG_TELEPORT;
				$pk->position = $this->position;
				$pk->xRot = 0;
				$pk->yRot = 0;
				$pk->zRot = 0;
				$pks = [$pk];
				break;
			 */

			case SendType::REMOVE:
				$pk = new RemoveActorPacket;
				$pk->entityUniqueId = $this->entityRuntimeId;
				$pks = [$pk];
				break;

			default:
				throw new InvalidArgumentException("The SendType must be an integer value between 0 to 3");
		}
		return $pks;
	}

	public function sendToPlayer(Player $player, SendType $type) {
		$pks = $this->asPackets($player, $type);
		foreach ($pks as $pk) {
			$player->dataPacket($pk);
		}
	}

	public function sendToPlayers(array $players, SendType $type) {
		foreach ($players as $player) {
			$this->sendToPlayer($player, $type);
		}
	}

	public function sendToLevel(Level $level, SendType $type) {
		$this->sendToPlayers($level->getPlayers(), $type);
	}

}