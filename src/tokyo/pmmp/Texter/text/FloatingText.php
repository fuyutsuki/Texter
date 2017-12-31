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
