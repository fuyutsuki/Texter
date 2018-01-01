<?php
namespace tokyo\pmmp\Texter\text;

// Texter
use tokyo\pmmp\Texter\{
  text\Text
};

/**
 * FloatingTextClass
 */
class FloatingText extends Text {

  /** @var int */
  protected $type = self::TEXT_TYPE_FT;
  /** @var string */
  protected $owner = "";

  /**
   * @override
   * @param Position $pos
   * @param string   $title
   * @param string   $text = ""
   * @param string   $owner = "unknown"
   * @param int      $id = 0
   */
  public function __construct(Position $pos, string $title, string $text = "", string $owner = "unknown", int $eid = 0) {
    $this->pos = $pos !== null? $pos : new Position();
    $this->title = $title;
    $this->text = $text;
    $this->owner = $owner;
    $this->uuid = UUID::fromRandom();
    $this->eid = $eid !== 0? $eid : Entity::$entityCount++;
  }

  /**
   * @return string
   */
  public function getOwner(): string {
    return $this->owner;
  }

  /**
   * @param string $owner
   * @return void
   */
  public function setOwner(string $owner): void {
    $this->owner = $owner;
  }
}
