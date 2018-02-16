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

namespace tokyo\pmmp\Texter\text;

// pocketmine
use pocketmine\{
  Player,
  Server,
  entity\Entity,
  level\Position,
  network\mcpe\protocol\AddPlayerPacket,
  network\mcpe\protocol\DataPacket,
  network\mcpe\protocol\MoveEntityPacket,
  network\mcpe\protocol\RemoveEntityPacket,
  network\mcpe\protocol\SetEntityDataPacket,
  utils\TextFormat as TF,
  utils\UUID
};

// texter
use tokyo\pmmp\Texter\{
  Core,
  TexterApi
};

/**
 * AbstractTextClass
 */
abstract class Text {

  /** @var int $this->sendTo****() */
  public const SEND_TYPE_ADD = 0;
  public const SEND_TYPE_MODIFY = 1;
  public const SEND_TYPE_MOVE = 2;
  public const SEND_TYPE_REMOVE = 3;

  /** @var string */
  protected $name = "";
  /** @var ?Position */
  protected $pos = null;
  /** @var string */
  protected $title = "";
  /** @var string */
  protected $text = "";
  /** @var bool */
  protected $isInvisible = false;
  /** @var @internal int */
  protected $eid = 0;

  /**
   * @param string   $textName
   * @param Position $pos
   * @param string   $title
   * @param string   $text
   * @param integer  $eid
   */
  public function __construct(string $textName, Position $pos, string $title = "", string $text = "", int $eid = 0) {
    $this->name = $textName;
    $this->pos = $pos;
    $this->title = $title;
    $this->text = $text;
    $this->eid = $eid !== 0 ? $eid : Entity::$entityCount++;
  }

  public function getName(): string {
    return $this->name;
  }

  public function setName(string $name): Text {
    $this->name = $name;
    return $this;
  }

  public function getPosition(): Position {
    return $this->pos;
  }

  public function setPosition(Position $pos): Text {
    $this->pos = $pos;
    return $this;
  }

  public function getTitle(): string {
    return $this->title;
  }

  public function setTitle(string $title): Text {
    $this->title = $title;
    return $this;
  }

  public function getText(): string {
    return $this->text;
  }

  public function setText(string $text): Text {
    $this->text = $text;
    return $this;
  }

  public function isInvisible(): bool {
    return $this->isInvisible;
  }

  public function setInvisible(bool $value): Text {
    $this->isInvisible = $value;
    return $this;
  }

  public function getEid(): int {
    return $this->eid;
  }

  public function setEid(int $eid): Text {
    $this->eid = $eid;
    return $this;
  }

  public function getAsPacket(int $type): DataPacket {
    switch ($type) {
      case self::SEND_TYPE_ADD:
        $pk = new AddPlayerPacket;
        $pk->uuid = UUID::fromRandom();
        $pk->username = "text";
        $pk->entityUniqueId = $this->eid;
        $pk->entityRuntimeId = $this->eid;// ...huh?
        $pk->position = $this->pos;
        $pk->item = Item::get(Item::AIR);
        $flags = 0;
        $flags |= 1 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG;
        $flags |= 1 << Entity::DATA_FLAG_ALWAYS_SHOW_NAMETAG;
        $flags |= 1 << Entity::DATA_FLAG_IMMOBILE;
        if ($this->isInvisible) {
          $flags |= 1 << Entity::DATA_FLAG_INVISIBLE;
        }
        $pk->metadata = [
          Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
          Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $this->title . TF::RESET . TF::WHITE . ($this->text !== "" ? "\n" . $this->text : "")],
          Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 0]
        ];
      break;

      case self::SEND_TYPE_MODIFY:
        $pk = new SetEntityDataPacket;
        $pk->entityRuntimeId = $this->eid;
        $pk->metadata = [
          Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
          Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $this->title . TF::RESET . TF::WHITE . ($this->text !== "" ? "\n" . $this->text : "")],
          Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 0]
        ];
      break;

      case self::SEND_TYPE_MOVE:
        $pk = new MoveEntityPacket;
        $pk->entityRuntimeId = $this->eid;
        $pk->position = $this->pos;
      break;

      case self::SEND_TYPE_REMOVE:
        $pk = new RemoveEntityPacket;
        $pk->entityUniqueId = $this->eid;
      break;

      default:// for developper
        throw new \InvalidArgumentException("The type must be an integer value between 0 and 3");
      break;
    }
    return $pk;
  }

  public function sendToPlayer(Player $player, int $type = self::SEND_TYPE_ADD): Text {
    $pk = $this->getAsPacket($type);
    $player->dataPacket($pk);
    return $this;
  }

  public function sendToLevel(Level $level, int $type = self::SEND_TYPE_ADD): Text {
    $pk = $this->getAsPacket($type);
    $players = $level->getPlayers();
    foreach ($players as $key => $value) {
      # code...TODO
    }
  }
}
