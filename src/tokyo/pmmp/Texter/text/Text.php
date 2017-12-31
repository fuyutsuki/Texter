<?php
namespace tokyo\pmmp\Texter\text;

// Pocketmine
use pocketmine\{
  entity\Entity,
  level\Level,
  item\Item,
  math\Vector3,
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

  /** @var Level */
  protected $level;
  /** @var string */
  protected $title = "";
  /** @var string */
  protected $text = "";
  /** @var ?Vector3 */
  protected $pos = null;
  /** @var string */
  protected $uuid = "";
  /** @var int */
  protected $eid = 0;
  /** @var bool */
  protected $invisible = false;
  /** @var int */
  protected $type = self::TEXT_TYPE_TEXT;

  /**
   * @param Level   $level
   * @param string  $title
   * @param string  $text = ""
   * @param Vector3 $vec3
   * @param int     $id = 0
   */
  public function __construct(Level $level, string $title, string $text = "", Vector3 $pos = null, int $eid = 0) {
    $this->level = $level;
    $this->title = $title;
    $this->text = $text;
    $this->pos = $pos !== null? $pos : new Vector3();
    $this->uuid = UUID::fromRandom();
    $this->eid = $eid !== 0? $eid : Entity::$entityCount++;
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
  public function getZ(): number {
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
    return $this->level;
  }

  /**
   * @param Level $level
   * @return Text
   */
  public function setLevel(Level $level): Text {
    $this->level = $level;
    return $this;
  }

  /**
   * @return string UUID
   */
  public function getUUID(): string {
    return $this->uuid;
  }

  /**
   * @param string $uuid
   * @return Text
   */
  public function setUUID(string $uuid): Text {
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
          Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $this->title . TF::RESET . TF::WHITE . ($this->text !== "")? "\n" . $this->text : ""],
          Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 0]
        ];
      return $pk;

      case self::SEND_TYPE_EDIT:
        $pk = new SetEntityDataPacket();
        $pk->entityUniqueId = $this->eid;
        $flags = 0;
        $flags |= 1 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG;
        $flags |= 1 << Entity::DATA_FLAG_ALWAYS_SHOW_NAMETAG;
        $flags |= 1 << Entity::DATA_FLAG_IMMOBILE;
        if ($this->invisible) $flags |= 1 << Entity::DATA_FLAG_INVISIBLE;
        $pk->metadata = [
          Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
          Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $this->title . TF::RESET . TF::WHITE . ($this->text !== "")? "\n" . $this->text : ""],
          Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 0]
        ];
      return $pk;

      case self::SEND_TYPE_MOVE:
        $pk = new MovePlayerPacket();
        $pk->entityUniqueId = $this->eid;
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
   * @param  Vector3 $vec3
   * @return void
   */
  public function moveFromPlayer(Player $player, Vector3 $vec3): void {
    $this->x = $vec3->x;
    $this->y = $vec3->y;
    $this->z = $vec3->z;
    $this->sendToPlayer(self::SEND_TYPE_MOVE, $player);
  }

  /**
   * Move the FloatingText that a particular player can see
   * @link $this->sendToLevel()
   * @param  Vector3 $vec3
   * @return void
   */
  public function moveFromLevel(Vector3 $vec3): void {
    $this->x = $vec3->x;
    $this->y = $vec3->y;
    $this->z = $vec3->z;
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
