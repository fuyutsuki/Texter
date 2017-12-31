<?php
namespace Texter\text;

// Pocketmine
use pocketmine\{
  level\Level,
  Player
};

/**
 * TextAbstractClass
 */
abstract class Text {

  /** @link $this->sendTo****() */
  public const SEND_TYPE_ADD = 0;
  public const SEND_TYPE_REMOVE = 1;
  /** @link $this->getType() */
  public const TEXT_TYPE_TEXT = 0;
  public const TEXT_TYPE_FT = 1;
  public const TEXT_TYPE_CRFT = 2;

  /** @var string **/
  private $title = "";
  /** @var string **/
  private $text = "";
  /** @var number **/
  private $x = 0;
  /** @var number **/
  private $y = 0;
  /** @var number **/
  private $z = 0;
  /** @var ?Level */
  private $level = null;
  /** @var int */
  private $id = 0;
  /** @var bool */
  private $invisible = false;
  /** @var int */
  private $type = 0;

  /**
   * @param Level   $level
   * @param string  $title
   * @param string  $text = ""
   * @param number  $x = null
   * @param number  $y = null
   * @param number  $z = null
   * @param int     $id = 0
   */
  public function __construct(Level $level, string $title, string $text = "", number $x = null, number $y = null, number $z = null, int $id = 0) {
    $this->level = $level;
    $this->title = $title;
    $this->text = $text;
    $this->x = $x !== null? $x : 0;
    $this->y = $y !== null? $y : 0;
    $this->z = $z !== null? $z : 0;
    $this->id = $id !== 0? $id : 0;
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
  public function getX(): number {
    return $this->x;
  }

  /**
   * @param number $x
   * @return Text
   */
  public function setX(number $x): Text {
    $this->x = $x;
    return $this;
  }

  /**
   * @return number $this->y
   */
  public function getY(): number {
    return $this->y;
  }

  /**
   * @param number $y
   * @return Text
   */
  public function setY(number $y): Text {
    $this->y = $y;
    return $this;
  }

  /**
   * @return number $this->z
   */
  public function getZ(): number {
    return $this->z;
  }

  /**
   * @param number $z
   * @return Text
   */
  public function setZ(number $z): Text {
    $this->z = $z;
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
   * @return int $this->id
   */
  public function getId(): int {
    return $this->id;
  }

  /**
   * @param int $id
   * @return Text
   */
  public function setId(int $id): Text {
    $this->id = $id;
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
    $this->type;
  }

  public function sendToPlayer(int $sendType, Player $player): void {

  }

  public function sendToLevel(int $sendType, Level $level = null): void {

  }

  public function move(Vector3 $vec3): bool {

  }

  /**
   * @link $this->sendToLevel(self::SEND_TYPE_REMOVE)
   */
  public function remove(): void {
    $this->sendToLevel(self::SEND_TYPE_REMOVE);
  }

  /**
   * @link $this->sendToLevel(self::SEND_TYPE_REMOVE)
   */
  public function clear(): void {
    $this->sendToLevel(self::SEND_TYPE_REMOVE);
  }
}
