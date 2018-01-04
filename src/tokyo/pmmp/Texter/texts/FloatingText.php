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
  level\Position,
  utils\UUID
};

// Texter
use tokyo\pmmp\Texter\{
  texts\Text
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
   * @param UUID     $uuid = null
   * @param int      $id = 0
   */
  public function __construct(Position $pos, string $title, string $text = "", string $owner = "unknown", UUID $uuid = null, int $eid = 0) {
    $this->pos = $pos !== null? $pos : new Position();
    $this->title = $title;
    $this->text = $text;
    $this->owner = strtolower($owner);
    $this->uuid = $uuid === null? UUID::fromRandom() : $uuid;
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
    $this->owner = strtolower($owner);
  }
}
