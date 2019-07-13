<?php

/**
 * // English
 *
 * Texter, the display FloatingTextPerticle plugin for PocketMine-MP
 * Copyright (c) 2019 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * This software is distributed under "NCSA license".
 * You should have received a copy of the MIT license
 * along with this program.  If not, see
 * < https://opensource.org/licenses/NCSA >.
 *
 * ---------------------------------------------------------------------
 * // 日本語
 *
 * TexterはPocketMine-MP向けのFloatingTextPerticleを表示するプラグインです
 * Copyright (c) 2019 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * このソフトウェアは"MITライセンス"下で配布されています。
 * あなたはこのプログラムと共にNCSAライセンスのコピーを受け取ったはずです。
 * 受け取っていない場合、下記のURLからご覧ください。
 * < https://opensource.org/licenses/NCSA >
 */

declare(strict_types = 1);

namespace tokyo\pmmp\Texter\text;

use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\AddPlayerPacket;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\MoveActorAbsolutePacket;
use pocketmine\network\mcpe\protocol\RemoveActorPacket;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\utils\UUID;
use tokyo\pmmp\Texter\data\Data;
use tokyo\pmmp\Texter\data\FloatingTextData;

/**
 * Class FloatingText
 * @package tokyo\pmmp\Texter\text
 */
class FloatingText extends Position implements Text {

  public const CHECK_CHAR = 0;
  public const CHECK_FEED = 1;

  /** @var string */
  protected $name;
  /** @var string */
  protected $title;
  /** @var string */
  protected $text;
  /** @var string */
  protected $owner;
  /** @var int */
  protected $eid;

  /** @var bool */
  protected $isInvisible = false;

  public function __construct(string $name, Position $pos, string $title = "", string $text = "", string $owner = "unknown", int $eid = 0) {
    $this
      ->setName($name)
      ->setPosition($pos)
      ->setTitle($title)
      ->setText($text)
      ->setOwner($owner)
      ->setEid($eid);
  }

  public function getName(): string {
    return $this->name;
  }

  public function setName(string $name): FloatingText {
    $this->name = $name;
    return $this;
  }

  public function getPosition(): Position {
    return $this->asPosition();
  }

  public function setPosition(Position $pos): FloatingText {
    parent::__construct($pos->x, $pos->y, $pos->z, $pos->level);
    return $this;
  }

  public function getTitle(): string {
    return str_replace("\n", "#", $this->title);
  }

  public function setTitle(string $title): FloatingText {
    $this->title = str_replace("#", "\n", $title);
    return $this;
  }

  public function getText(): string {
    return str_replace("\n", "#", $this->text);
  }

  public function setText(string $text): FloatingText {
    $this->text = str_replace("#", "\n", $text);
    return $this;
  }

  public function getIndentedTexts(bool $owned): string {
    $texts = "{$this->title}".TextFormat::RESET.TextFormat::WHITE."\n{$this->text}";
    return $texts . ($owned ? "\n".TextFormat::GRAY."[{$this->name}]" : "");
  }

  public function getTextsForCheck(int $mode = self::CHECK_CHAR): string {
    switch ($mode) {
      case self::CHECK_CHAR:
        $str = str_replace("\n", "", TextFormat::clean($this->title.$this->text));
        break;
      case self::CHECK_FEED:
        $str = TextFormat::clean($this->title.$this->text);
        break;
      default:
        throw new \InvalidArgumentException("The value of mode must be 0(FloatingText::CHECK_CHAR) to 1(FloatingText::CHECK_FEED)");
    }
    return $str;
  }

  public function isOwner(Player $player): bool {
    return $player->isOp() || strtolower($player->getName()) === $this->owner;
  }

  public function getOwner(): string {
    return $this->owner;
  }

  public function setOwner(string $owner): FloatingText {
    $this->owner = strtolower($owner);
    return $this;
  }

  public function getEid(): int {
    return $this->eid;
  }

  public function setEid(int $eid): FloatingText {
    $this->eid = $eid === 0 ? Entity::$entityCount++ : $eid;
    return $this;
  }

  public function isInvisible(): bool {
    return $this->isInvisible;
  }

  public function setInvisible(bool $value): FloatingText {
    $this->isInvisible = $value;
    return $this;
  }

  public function asPacket(int $type = Text::SEND_TYPE_ADD, bool $owned = false): DataPacket {
    switch ($type) {
      case Text::SEND_TYPE_ADD:
      case Text::SEND_TYPE_EDIT:
        $pk = new AddPlayerPacket;
        $pk->username = $this->getIndentedTexts($owned);
        $pk->uuid = UUID::fromRandom();
        $pk->entityRuntimeId = $this->eid;
        $pk->entityUniqueId = $this->eid;
        $pk->position = $this;
        $pk->item = Item::get(Item::AIR);
        $flags = 1 << Entity::DATA_FLAG_IMMOBILE;
        if ($this->isInvisible) {
          $flags |= 1 << Entity::DATA_FLAG_INVISIBLE;
        }
        $pk->metadata = [
          Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
          Entity::DATA_SCALE => [Entity::DATA_TYPE_FLOAT, 0]
        ];
        break;

      case Text::SEND_TYPE_MOVE:
        $pk = new MoveActorAbsolutePacket();
        $pk->entityRuntimeId = $this->eid;
        $pk->position = $this;
        $pk->xRot = 0;
        $pk->yRot = 0;
        $pk->zRot = 0;
        break;

      case Text::SEND_TYPE_REMOVE:
        $pk = new RemoveActorPacket();
        $pk->entityUniqueId = $this->eid;
        break;

      // for developers
      default:
        throw new \InvalidArgumentException("The type must be an integer value between 0 to 3");
        break;
    }
    return $pk;
  }

  public function sendToPlayer(Player $player, int $type = Text::SEND_TYPE_ADD): FloatingText {
    $pk = $this->asPacket($type, $this->isOwner($player));
    $player->dataPacket($pk);
    return $this;
  }

  public function sendToPlayers(array $players, int $type = Text::SEND_TYPE_ADD): FloatingText {
    foreach ($players as $player) {
      $this->sendToPlayer($player, $type);
    }
    return $this;
  }

  public function sendToLevel(Level $level, int $type = Text::SEND_TYPE_ADD): FloatingText {
    $this->sendToPlayers($level->getPlayers(), $type);
    return $this;
  }

  public function format(): array {
    return [
      Data::KEY_X => sprintf('%0.1f', $this->x),
      Data::KEY_Y => sprintf('%0.1f', $this->y),
      Data::KEY_Z => sprintf('%0.1f', $this->z),
      Data::KEY_TITLE => $this->title,
      Data::KEY_TEXT => $this->text,
      FloatingTextData::KEY_OWNER => $this->owner
    ];
  }

  public function __toString(): string {
    $p = $this->getPosition();
    return "FloatingText(name=\"{$this->name}\", pos=\"x:{$p->x};y:{$p->y};z:{$p->z};level:{$p->getLevel()->getFolderName()}\", title=\"{$this->title}\", text=\"{$this->text}\", owner=\"{$this->owner}\", eid=\"{$this->eid}\")";
  }
}