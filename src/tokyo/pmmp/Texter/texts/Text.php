<?php

/**
 * // English
 *
 * Texter, the display FloatingTextPerticle plugin for PocketMine-MP
 * Copyright (c) 2018 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * This software is distributed under "MIT license".
 * You should have received a copy of the MIT license
 * along with this program.  If not, see
 * < https://opensource.org/licenses/mit-license >.
 *
 * ---------------------------------------------------------------------
 * // 日本語
 *
 * TexterはPocketMine-MP向けのFloatingTextPerticleを表示するプラグインです。
 * Copyright (c) 2018 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * このソフトウェアは"MITライセンス"下で配布されています。
 * あなたはこのプログラムと共にMITライセンスのコピーを受け取ったはずです。
 * 受け取っていない場合、下記のURLからご覧ください。
 * < https://opensource.org/licenses/mit-license >
 */

namespace tokyo\pmmp\Texter\texts;

// Pocketmine
use pocketmine\{
  entity\Entity,
  level\Level,
  level\Position,
  item\Item,
  network\mcpe\protocol\DataPacket,
  network\mcpe\protocol\AddPlayerPacket,
  network\mcpe\protocol\SetEntityDataPacket,
  network\mcpe\protocol\MovePlayerPacket,
  network\mcpe\protocol\RemoveEntityPacket,
  utils\TextFormat as TF,
  utils\UUID,
  Player
};

/**
 * TextAbstractClass
 */
abstract class Text {

  /** @link $this->sendTo****() */
  public const SEND_TYPE_ADD = 0;
  public const SEND_TYPE_EDIT = 1;
  public const SEND_TYPE_MOVE = 2;
  public const SEND_TYPE_REMOVE = 3;
  /** @link $this->getType() */
  public const TEXT_TYPE_TEXT = 0;
  public const TEXT_TYPE_FT = 1;
  public const TEXT_TYPE_CRFT = 2;

  /** @var ?Position */
  protected $pos = null;
  /** @var string */
  protected $title = "";
  /** @var string */
  protected $text = "";
  /** @var UUID */
  protected $uuid = null;
  /** @var int */
  protected $eid = 0;
  /** @var bool */
  protected $invisible = false;
  /** @var int */
  protected $type = self::TEXT_TYPE_TEXT;

  /**
   * @param Position $pos
   * @param string   $title
   * @param string   $text = ""
   * @param UUID     $uuid = null
   * @param int      $eid = 0
   */
  public function __construct(Position $pos, string $title, string $text = "", UUID $uuid = null, int $eid = 0) {
    $this->pos = $pos !== null? $pos : new Position();
    $this->title = $title;
    $this->text = $text;
    $this->uuid = $uuid === null? UUID::fromRandom() : $uuid;
    $this->eid = $eid !== 0? $eid : Entity::$entityCount++;
  }

  /**
   * @return Position
   */
  public function getAsPosition(): Position {
    return $this->pos;
  }

  /**
   * @param  Position $pos
   * @return Text
   */
  public function setCoordByPosition(Position $pos): Text {
    $this->pos = $pos;
    return $this;
  }

  /**
   * @return Vector3
   */
  public function getAsVector3(): Vector3 {
    return $this->pos->asVector3();
  }

  /**
   * @param Vector3 $vec3
   * @return Text
   */
  public function setCoordByVector3(Vector3 $vec3): Text {
    $this->pos = Position::fromObject($vec3, $this->pos->getLevel());
    return $this;
  }

  /**
   * @return string $this->title
   */
  public function getTitle(): string {
    return $this->title;
  }

  /**
   * @param string $title
   * @return Text
   */
  public function setTitle(string $title): Text {
    $this->title = $title;
    return $this;
  }

  /**
   * @return string $this->text
   */
  public function getText(): string {
    return $this->text;
  }

  /**
   * @param string $text
   * @return Text
   */
  public function setText(string $text): Text {
    $this->text = $text;
    return $this;
  }

  /**
   * @return number $this->x
   */
  public function getX() {
    return $this->pos->x;
  }

  /**
   * @param  number $x
   * @return Text
   */
  public function setX($x): Text {
    $this->pos->x = is_numeric($x)? $x : $this->pos->x;
    return $this;
  }

  /**
   * @return number $this->y
   */
  public function getY() {
    return $this->pos->y;
  }

  /**
   * @param  number $y
   * @return Text
   */
  public function setY($y): Text {
    $this->pos->y = is_numeric($y)? $y : $this->pos->y;
    return $this;
  }

  /**
   * @return number $this->pos->z
   */
  public function getZ() {
    return $this->pos->z;
  }

  /**
   * @param number $z
   * @return Text
   */
  public function setZ(number $z): Text {
    $this->pos->z = is_numeric($z)? $z : $this->pos->z;
    return $this;
  }

  /**
   * @return Level $this->level
   */
  public function getLevel(): Level {
    return $this->pos->level;
  }

  /**
   * @param Level $level
   * @return Text
   */
  public function setLevel(Level $level): Text {
    $this->pos->level = $level;
    return $this;
  }

  /**
   * @return string UUID
   */
  public function getUUID(): UUID {
    return $this->uuid;
  }

  /**
   * @param UUID $uuid
   * @return Text
   */
  public function setUUID(UUID $uuid): Text {
    $this->uuid = $uuid;
    return $this;
  }

  /**
   * @return int $this->eid
   */
  public function getEid(): int {
    return $this->eid;
  }

  /**
   * @param int $eid
   * @return Text
   */
  public function setEid(int $eid): Text {
    $this->eid = $eid;
    return $this;
  }

  /**
   * @return bool $this->invisible
   */
  public function isInvisible(): bool {
    return $this->invisible;
  }

  /**
   * @param  bool $invisible
   * @return Text
   */
  public function setInvisible(bool $invisible): Text {
    $this->invisible = true;
    return $this;
  }

  /**
   * @return int $this->type
   */
  public function getType(): int {
    return $this->type;
  }

  /**
   * Send to the player at the level.
   * @param int    $sendType
   * @param Player $player
   * @return void
   */
  public function sendToPlayer(int $sendType, Player $player): void {
    switch ($sendType) {
      case self::SEND_TYPE_ADD:
        $pk = $this->getAsPacket(self::SEND_TYPE_ADD);
      break;

      case self::SEND_TYPE_EDIT:
        $pk = $this->getAsPacket(self::SEND_TYPE_EDIT);
      break;

      case self::SEND_TYPE_MOVE:
        $pk = $this->getAsPacket(self::SEND_TYPE_MOVE);
      break;

      case self::SEND_TYPE_REMOVE:
        $pk = $this->getAsPacket(self::SEND_TYPE_REMOVE);
      break;
    }
    $player->dataPacket($pk);
  }

  /**
   * Send to all players at the level.
   * @param int    $sendType
   * @param Level  $level = null
   * @return void
   */
  public function sendToLevel(int $sendType, Level $level = null): void {
    $level = $level !== null? $level : $this->level;
    $players = $level->getPlayers();
    switch ($sendType) {
      case self::SEND_TYPE_ADD:
        $pk = $this->getAsPacket(self::SEND_TYPE_ADD);
      break;

      case self::SEND_TYPE_EDIT:
        $pk = $this->getAsPacket(self::SEND_TYPE_EDIT);
      break;

      case self::SEND_TYPE_MOVE:
        $pk = $this->getAsPacket(self::SEND_TYPE_MOVE);
      break;

      case self::SEND_TYPE_REMOVE:
        $pk = $this->getAsPacket(self::SEND_TYPE_REMOVE);
      break;
    }
    foreach ($players as $player) {
      $player->dataPacket($pk);
    }
  }

  /**
   * It gets it as a packet.
   * @param  int        $sendType
   * @return DataPacket
   */
  public function getAsPacket(int $sendType): DataPacket {
    switch ($sendType) {
      case self::SEND_TYPE_ADD:
        $pk = new AddPlayerPacket();
        $pk->entityUniqueId = $this->eid;
        $pk->entityRuntimeId = $this->eid;
        $pk->uuid = $this->uuid;
        $pk->username = "text";
        $pk->position = $this->pos;
        $pk->item = Item::get(Item::AIR);
        $flags = 0;
        $flags |= 1 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG;
        $flags |= 1 << Entity::DATA_FLAG_ALWAYS_SHOW_NAMETAG;
        $flags |= 1 << Entity::DATA_FLAG_IMMOBILE;
        if ($this->invisible) $flags |= 1 << Entity::DATA_FLAG_INVISIBLE;
        $pk->metadata = [
          Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
          Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $this->title . TF::RESET . TF::WHITE . ($this->text !== ""? "\n" . $this->text : "")],
          Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 0]
        ];
      return $pk;

      case self::SEND_TYPE_EDIT:
        $pk = new SetEntityDataPacket();
        $pk->entityRuntimeId = $this->eid;
        $flags = 0;
        $flags |= 1 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG;
        $flags |= 1 << Entity::DATA_FLAG_ALWAYS_SHOW_NAMETAG;
        $flags |= 1 << Entity::DATA_FLAG_IMMOBILE;
        if ($this->invisible) $flags |= 1 << Entity::DATA_FLAG_INVISIBLE;
        $pk->metadata = [
          Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
          Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $this->title . TF::RESET . TF::WHITE . ($this->text !== ""? "\n" . $this->text : "")],
          Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 0]
        ];
      return $pk;

      case self::SEND_TYPE_MOVE:
        $pk = new MovePlayerPacket();
        $pk->entityRuntimeId = $this->eid;
        $pk->position = $this->pos;
      return $pk;

      case self::SEND_TYPE_REMOVE:
        $pk = new RemoveEntityPacket();
        $pk->entityUniqueId = $this->eid;
      return $pk;
    }
  }

  /**
   * Move the FloatingText that a particular player can see
   * @link $this->sendToPlayer()
   * @param  Player  $player
   * @param  Position $pos = null
   * @return void
   */
  public function moveFromPlayer(Player $player, Position $pos = null): void {
    $this->pos = $pos !== null? $pos : $this->pos;
    $this->sendToPlayer(self::SEND_TYPE_MOVE, $player);
  }

  /**
   * Move the FloatingText that a particular player can see
   * @link $this->sendToLevel()
   * @param  Position $pos = null
   * @return void
   */
  public function moveFromLevel(Position $pos = null): void {
    $this->pos = $pos !== null? $pos : $this->pos;
    $this->sendToLevel(self::SEND_TYPE_MOVE);
  }

  /**
   * @link $this->sendToPlayer()
   */
  public function removeFromPlayer(Player $player): void {
    $this->sendToPlayer(self::SEND_TYPE_REMOVE, $player);
  }

  /**
   * @link $this->sendToLevel()
   */
  public function removeFromLevel(): void {
    $this->sendToLevel(self::SEND_TYPE_REMOVE);
  }

  /**
   * @link $this->sendToPlayer()
   */
  public function clearFromPlayer(Player $player): void {
    $this->sendToPlayer(self::SEND_TYPE_REMOVE, $player);
  }

  /**
   * @link $this->sendToLevel()
   */
  public function clearFromLevel(): void {
    $this->sendToLevel(self::SEND_TYPE_REMOVE);
  }
}
