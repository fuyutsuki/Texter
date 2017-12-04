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
use pocketmine\item\Item;
use pocketmine\utils\{
  TextFormat as TF,
  UUID};

# Texter
use Texter\TexterApi;
use Texter\text\Text;
use Texter\language\Lang;

/**
 * FloatingText
 */
class FloatingText extends Text{

  /** @var string $owner */
  public $owner = null;

  /**
   * コンストラクタ
   * @Override
   * @param Level  $level
   * @param number $x = 0
   * @param number $y = 0
   * @param number $z = 0
   * @param string $title = ""
   * @param string $text  = ""
   * @param string $owner = ""
   */
  public function __construct(Level $level, $x = 0, $y = 0, $z = 0, string $title = "", string $text = "", string $owner = ""){
    $this->level = $level;
    $this->x = $x;
    $this->y = $y;
    $this->z = $z;
    $this->title = $title;
    $this->text  = $text;
    $this->owner = strtolower($owner);
    $this->eid = Entity::$entityCount++;
    $this->api = TexterApi::getInstance();
    if ($this->api->saveFt($this, true)) {
      $this->sendToLevel(self::SEND_TYPE_ADD);
    }else {
      $this->failed = true;
    }
  }

  /**
   * X座標を変更します
   * @Override
   * @param  number $x
   * @return Text
   */
  public function setX($x): Text{
    if (is_numeric($x)) {
      $tmpX = $this->x;
      $this->x = $x;
      if ($this->api->saveFt($this)) {
        $this->sendToLevel(self::SEND_TYPE_ADD);
        return $this;
      }else {
        $this->x = $tmpX;
      }
    }
    return $this;
  }

  /**
   * Y座標を変更します
   * @Override
   * @param  number $y
   * @return Text
   */
  public function setY($y): Text{
    if (is_numeric($y)) {
      $tmpY = $this->y;
      $this->y = $y;
      if ($this->api->saveFt($this)) {
        $this->sendToLevel(self::SEND_TYPE_ADD);
        return $this;
      }else {
        $this->y = $tmpY;
      }
    }
    return $this;
  }

  /**
   * Z座標を変更します
   * @Override
   * @param  number $z
   * @return Text
   */
  public function setZ($z): Text{
    if (is_numeric($z)) {
      $tmpZ = $this->z;
      $this->z = $z;
      if ($this->api->saveFt($this)) {
        $this->sendToLevel(self::SEND_TYPE_ADD);
        return $this;
      }else {
        $this->z = $tmpZ;
      }
    }
    return $this;
  }

  /**
   * Levelを変更します
   * @Override
   * @param  Level $level
   * @return Text
   */
  public function setLevel(Level $level): Text{
    $this->sendToLevel(self::SEND_TYPE_REMOVE);
    $tmpLev = $this->level;
    $this->level = $level;
    if ($this->api->saveFt($this)) {
      $this->sendToLevel(self::SEND_TYPE_ADD);
      return $this;
    }else {
      $this->level = $tmpLev;
      $this->sendToLevel(self::SEND_TYPE_ADD);
      return $this;
    }
  }

  /**
   * Levelを変更します
   * @Override
   * @param  string $levelName
   * @return Text
   */
  public function setLevelByName(string $levelName): Text{
    $level = Server::getInstance()->getLevelByName($levelName);
    if ($level !== null) {
      $this->sendToLevel(self::SEND_TYPE_REMOVE);
      $tmpLev = $this->level;
      $this->level = $level;
      if ($this->api->saveFt($this)) {
        $this->sendToLevel(self::SEND_TYPE_ADD);
        return $this;
      }else {
        $this->level = $tmpLev;
        $this->sendToLevel(self::SEND_TYPE_ADD);
      }
    }
    return $this;
  }

  /**
  * 座標を変更します
  * @Override
  * @param  Vector3 $pos
  * @return Text
  */
  public function setCoord(Vector3 $pos): Text{
    $tmpX = $this->x;
    $tmpY = $this->y;
    $tmpZ = $this->z;
    $this->x = $pos->x;
    $this->y = $pos->y;
    $this->z = $pos->z;
    if ($this->api->saveFt($this)) {
      $this->sendToLevel(self::SEND_TYPE_ADD);
      return $this;
    }else {
      $this->x = $tmpX;
      $this->y = $tmpY;
      $this->z = $tmpZ;
      return $this;
    }
  }

  /**
   * タイトルを変更します, # で改行です.
   * @Override
   * @param  string $title
   * @return Text
   */
  public function setTitle(string $title): Text{
    $this->title = str_replace("#", "\n", $title);
    $this->api->saveFt($this);
    $this->sendToLevel(self::SEND_TYPE_ADD);
    return $this;
  }

  /**
   * テキストを変更します, # で改行です
   * @Override
   * @param  string $text
   * @return Text
   */
  public function setText(string $text): Text{
    $this->text = str_replace("#", "\n", $text);
    $this->api->saveFt($this);
    $this->sendToLevel(self::SEND_TYPE_ADD);
    return $this;
  }

  /**
   * プレイヤーに送信します
   * @Override
   * @param  Player $player
   * @param  int    $type
   * @return bool
   */
  public function sendToPlayer(Player $player, int $type): bool{
    switch ($type) {
      case self::SEND_TYPE_ADD:
        $pk = $this->getAsAddPacket();
        if ($this->canEditFt($player)) {
          $pk->metadata[4][1] = TF::GRAY . "[" . $this->eid . "] " . TF::WHITE . $pk->metadata[4][1];
        }
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
   * テキストを更新します
   * @Override
   * @param  int  $type
   * @return bool true
   */
  public function sendToLevel(int $type): bool{
    switch ($type) {
      case self::SEND_TYPE_ADD:
        $pk = $this->getAsAddPacket();
        $players = $this->level->getPlayers();
        foreach ($players as $player) {
          if ($this->canEditFt($player)) {
            $pk->metadata[4][1] = TF::GRAY . "[" . $this->eid . "] " . TF::WHITE . $pk->metadata[4][1];
          }
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
   * 所有者を取得します
   * @return string $this->owner
   */
  public function getOwner(): string{
    return $this->owner;
  }

  /**
   * 所有者を変更します
   * @param  string $owner
   * @return Text
   */
  public function setOwner(string $owner): Text{
    $this->owner = strtolower($owner);
    $this->api->saveFt($this);
    $this->sendToLevel(self::SEND_TYPE_ADD);
    return $this;
  }

  /**
   * テキストを操作できるか確認します
   * @param  Player $player
   * @return bool
   */
  public function canEditFt(Player $player): bool{
    $name = strtolower($player->getName());
    if ($player->isOp() || $this->owner === $name) {
      return true;
    }else {
      return false;
    }
  }
}
