<?php
namespace Texter\text;

// Pocketmine
use pocketmine\{
  level\Level,
};

/**
 * TextAbstractClass
 */
abstract class Text {

  // TODO: メソッドチェーン及び動作回り

  /** @link $this->sendTo****() */
  public const SEND_TYPE_ADD = 0;
  public const SEND_TYPE_REMOVE = 1;

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

  /**
   * @param string  $title
   * @param string  $text = ""
   * @param number  $x = null
   * @param number  $y = null
   * @param number  $z = null
   * @param int     $id = 0
   * @param Level   $level = null
   */
  public function __construct(string $title, string $text = "", number $x = null, number $y = null, number $z = null, int $id = 0, Level $level = null) {
    $this->title = $title;
    $this->text = $text;
    $this->x = $x !== null? $x : 0;
    $this->y = $y !== null? $y : 0;
    $this->z = $z !== null? $z : 0;
    $this->level = $level;
  }

  /**
   * @return string $this->title
   */
  public function getTitle(): string {
    return $this->title;
  }

  /**
   * @param string $title
   * @return void
   */
  public function setTitle(string $title): void {
    $this->title = $title;
  }

  /**
   * @return string $this->text
   */
  public function getText(): string {
    return $this->text;
  }

  /**
   * @param string $text
   * @return void
   */
  public function setText(string $text): void {
    $this->text = $text;
  }

  /**
   * @return number $this->x
   */
  public function getX(): number {
    return $this->x;
  }

  /**
   * @param number $x
   * @return void
   */
  public function setX(number $x): void {
    $this->x = $x;
  }

  /**
   * @return number $this->y
   */
  public function getY(): number {
    return $this->y;
  }

  /**
   * @param number $y
   * @return void
   */
  public function setY(number $y): void {
    $this->y = $y;
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
}
