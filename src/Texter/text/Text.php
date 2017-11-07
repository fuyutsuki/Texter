<?php
namespace Texter\text;

# Pocketmine
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\entity\Entity;
use pocketmine\level\{
  Level,
  Position};
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\{
  AddPlayerPacket,
  RemoveEntityPacket};
use pocketmine\item\Item;
use pocketmine\utils\{
  TextFormat as TF,
  UUID};

# Texter
use Texter\TexterApi;
use Texter\language\Lang;

/**
 * Text
 */
abstract class Text{

  /** @link $this->sendTo***() */
  const SEND_TYPE_ADD = 0;
  const SEND_TYPE_REMOVE = 1;

  /** @var TexterApi */
  protected $api = null;
  /** @var float $x */
  public $x = 0.0;
  /** @var float $y */
  public $y = 0.0;
  /** @var float $z */
  public $z = 0.0;
  /** @var Level $level */
  public $level = null;
  /** @var string $title */
  public $title = "";
  /** @var string $text */
  public $text = "";
  /** @var bool $invisible */
  public $invisible = false;
  /** @var int $eid */
  public $eid = 0;
  /** @var bool $failed */
  public $failed = false;

  /**
   * コンストラクタ
   * @param Level     $level
   * @param int|float $x = 0
   * @param int|float $y = 0
   * @param int|float $z = 0
   * @param Vector3   $pos
   * @param string    $title = ""
   * @param string    $text = ""
   */
  public function __construct(Level $level, $x = 0, $y = 0, $z = 0, string $title = "", string $text = ""){
    $this->level = $level;
    $this->x = $x;
    $this->y = $y;
    $this->z = $z;
    $this->title = $title;
    $this->text = $text;
    $this->eid = Entity::$entityCount++;
    $this->api = TexterApi::getInstance();
    if ($this->api->saveCrft($this, true)) {
      $this->sendToLevel(self::SEND_TYPE_ADD);
    }else {
      $this->failed = true;
    }
  }

  /**
   * X座標を取得します
   * @return int|float $this->x
   */
  public function getX(){
    return $this->x;
  }

  /**
   * X座標を変更します
   * @param  int|float $x
   * @return bool
   */
  public function setX($x): bool{
    if (is_numeric($x)) {
      $tmpX = $this->x;
      $this->x = $x;
      if ($this->api->saveCrft($this)) {
        $this->sendToLevel(self::SEND_TYPE_ADD);
        return true;
      }else {
        $this->x = $tmpX;
      }
    }
    return false;
  }

  /**
   * Y座標を取得します
   * @return int|float $this->y
   */
  public function getY(){
    return $this->y;
  }

  /**
   * Y座標を変更します
   * @param  int|float $y
   * @return bool
   */
  public function setY($y): bool{
    if (is_numeric($y)) {
      $tmpY = $this->y;
      $this->y = $y;
      if ($this->api->saveCrft($this)) {
        $this->sendToLevel(self::SEND_TYPE_ADD);
        return true;
      }else {
        $this->y = $tmpY;
      }
    }
    return false;
  }

  /**
   * Z座標を取得します
   * @return int|float $this->z
   */
  public function getZ(){
    return $this->z;
  }

  /**
   * Z座標を変更します
   * @param  int|float $z
   * @return bool
   */
  public function setZ($z): bool{
    if (is_numeric($z)) {
      $tmpZ = $this->z;
      $this->z = $z;
      if ($this->api->saveCrft($this)) {
        $this->sendToLevel(self::SEND_TYPE_ADD);
        return true;
      }else {
        $this->z = $tmpZ;
      }
    }
    return false;
  }

  /**
   * Levelを取得します
   * @return Level $this->level
   */
  public function getLevel(): Level{
    return $this->level;
  }

  /**
   * Levelを変更します
   * @param  Level $level
   * @return bool
   */
  public function setLevel(Level $level): bool{
    $this->sendToLevel(self::SEND_TYPE_REMOVE);
    $tmpLev = $this->level;
    $this->level = $level;
    if ($this->api->saveCrft($this)) {
      $this->sendToLevel(self::SEND_TYPE_ADD);
      return true;
    }else {
      $this->level = $tmpLev;
      $this->sendToLevel(self::SEND_TYPE_ADD);
      return false;
    }
  }

  /**
   * Levelを変更します
   * @param  string $levelName
   * @return bool
   */
  public function setLevelByName(string $levelName): bool{
    $level = Server::getInstance()->getLevelByName($levelName);
    if ($level !== null) {
      $this->sendToLevel(self::SEND_TYPE_REMOVE);
      $tmpLev = $this->level;
      $this->level = $level;
      if ($this->api->saveCrft($this)) {
        $this->sendToLevel(self::SEND_TYPE_ADD);
        return true;
      }else {
        $this->level = $tmpLev;
        $this->sendToLevel(self::SEND_TYPE_ADD);
      }
    }
    return false;
  }

  /**
   * 座標をVector3オブジェクトとして取得します
   * @return Vector3 $this->pos
   */
  public function getAsVector3(){
    return new Vector3($this->x, $this->y, $this->z);
  }

  /**
   * 座標をPositionオブジェクトとして取得します
   * @return Position
   */
  public function getAsPosition(){
    return new Position($this->x, $this->y, $this->z, $this->level);
  }

  /**
  * 座標を変更します
  * @param  Vector3 $pos
  * @return bool    true
  */
  public function setCoord(Vector3 $pos): bool{
    $tmpX = $this->x;
    $tmpY = $this->y;
    $tmpZ = $this->z;
    $this->x = $pos->x;
    $this->y = $pos->y;
    $this->z = $pos->z;
    if ($this->api->saveCrft($this)) {
      $this->sendToLevel(self::SEND_TYPE_ADD);
      return true;
    }else {
      $this->x = $tmpX;
      $this->y = $tmpY;
      $this->z = $tmpZ;
      return false;
    }
  }

  /**
   * タイトルを取得します
   * @return string $this->title
   */
  public function getTitle(): string{
    return $this->title;
  }

  /**
   * タイトルを変更します, # で改行です.
   * @param  string $title
   * @return bool   true
   */
  public function setTitle(string $title): bool{
    $this->title = str_replace("#", "\n", $title);
    $this->api->saveCrft($this);
    $this->sendToLevel(self::SEND_TYPE_ADD);
    return true;
  }

  /**
   * テキストを取得します
   * @return string $this->text
   */
  public function getText(): string{
    return $this->text;
  }

  /**
   * テキストを変更します, # で改行です
   * @param  string $text
   * @return bool   true
   */
  public function setText(string $text): bool{
    $this->text = str_replace("#", "\n", $text);
    $this->api->saveCrft($this);
    $this->sendToLevel(self::SEND_TYPE_ADD);
    return true;
  }

  /**
  * 不可視かどうか取得します
  * @return bool
  */
  public function isInvisible(): bool{
    return $this->invisible;
  }

  /**
   * 不可視かどうか変更します
   * @param  bool $bool
   * @return bool true
   */
  public function setInvisible(bool $bool): bool{
    $this->invisible = $bool;
    $this->sendToLevel(self::SEND_TYPE_ADD);
    return true;
  }

  /**
   * エンティティIDを取得します
   * @return int $this->eid
   */
  public function getEntityId(): int{
    return $this->eid;
  }

  /**
   * エンティティIDを変更します
   * @param  int  $eid
   * @return bool true
   */
  public function setEntityId(int $eid): bool{
    $this->sendToLevel(self::SEND_TYPE_REMOVE);
    $this->eid = $eid;
    $this->sendToLevel(self::SEND_TYPE_ADD);
    return true;
  }

  /**
   * AddPlayerPacketとして取得します
   * @return AddPlayerPacket $pk
   */
  public function getAsAddPacket(): AddPlayerPacket{
    $pk = new AddPlayerPacket();
    $pk->uuid = UUID::fromRandom();
    $pk->username = "text";
    $pk->eid = $this->eid;// for GenisysPro
    $pk->entityUniqueId = $this->eid;
    $pk->entityRuntimeId = $this->eid;// ...huh?
    $x = (float)sprintf('%0.1f', $this->x);
    $y = (float)sprintf('%0.1f', $this->y);
    $z = (float)sprintf('%0.1f', $this->z);
    $pk->x = $x;
    $pk->y = $y;
    $pk->z = $z;
    $pk->position = new Vector3($x, $y, $z);// for 1.2~
    $pk->motion = new Vector3();
    $pk->item = Item::get(Item::AIR);
    $flags = 0;
    $flags |= 1 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG;
    $flags |= 1 << Entity::DATA_FLAG_ALWAYS_SHOW_NAMETAG;
    $flags |= 1 << Entity::DATA_FLAG_IMMOBILE;
    if ($this->invisible) {
      $flags |= 1 << Entity::DATA_FLAG_INVISIBLE;
    }
    $pk->metadata = [
      Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
      Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $this->title . TF::RESET . TF::WHITE . ($this->text !== "" ? "\n" . $this->text : "")],
      Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 0]
    ];
    return $pk;
  }

  /**
   * RemoveEntityPacketとして取得します
   * @return RemoveEntityPacket $pk
   */
  public function getAsRemovePacket(): RemoveEntityPacket{
    $pk = new RemoveEntityPacket();
    $pk->eid = $this->eid;
    $pk->entityUniqueId = $this->eid;
    return $pk;
  }

  /**
   * プレイヤーに送信します
   * @param  Player $player
   * @param  int    $type
   * @return bool
   */
  public function sendToPlayer(Player $player, int $type): bool{
    switch ($type) {
      case self::SEND_TYPE_ADD:
        $pk = $this->getAsAddPacket();
        $player->dataPacket($pk);
      break;

      case self::SEND_TYPE_REMOVE:
        $pk = $this->getAsRemovePacket();
        $player->dataPacket($pk);
      break;

      default:
        return false;
      break;
    }
    return true;
  }

  /**
   * ワールドに送信します
   * @param  int  $type
   * @return bool true
   */
  public function sendToLevel(int $type): bool{
    switch ($type) {
      case self::SEND_TYPE_ADD:
        $pk = $this->getAsAddPacket();
        $players = $this->level->getPlayers();
        foreach ($players as $player) {
          $player->dataPacket($pk);
        }
      break;

      case self::SEND_TYPE_REMOVE:
        $this->api->removeText($this);
        $pk = $this->getAsRemovePacket();
        $players = $this->level->getPlayers();
        foreach ($players as $player) {
          $player->dataPacket($pk);
        }
      break;

      default:
        return false;
      break;
    }
    return true;
  }

  /**
   * @link $this->sendToLevel(self::SEND_TYPE_REMOVE);
   */
  public function remove(){
    $this->sendToLevel(self::SEND_TYPE_REMOVE);
  }

  /**
   * 処理に失敗したかを取得します
   * @return bool
   */
  public function getFailed(): bool{
    return $this->failed;
  }

  /**
   * 処理に失敗したかどうかを変更します
   * @param  bool $value
   * @return bool true
   */
  public function setFailed(bool $value): bool{
    $this->failed = $value;
    return true;
  }
}
