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
  entity\Entity,
  level\Level,
  level\Position
};

// texter
use tokyo\pmmp\Texter\{
  manager\Manager
};

/**
 * FloatingTextClass
 */
class FloatingText extends Text {

  /** @var string */
  protected $owner = "unknown";

  /**
   * @param string   $textName
   * @param Position $pos
   * @param string   $title
   * @param string   $text
   * @param string   $owner
   * @param integer  $eid
   */
  public function __construct(string $textName, Position $pos, string $title = "", string $text = "", string $owner = "", int $eid = 0) {
    parent::__construct($textName, $pos, $title, $text, $eid);
    $this->owner = strtolower($owner);
  }

  /**
   * @return string
   */
  public function getOwner(): string {
    return $this->owner;
  }

  /**
   * @param  string       $owner
   * @return FloatingText
   */
  public function setOwner(string $owner): FloatingText {
    $this->owner = $owner;
    return $this;
  }

  /**
   * @param  Player $player
   * @param  int    $type
   * @return Text
   */
  public function sendToPlayer(Player $player, int $type = Text::SEND_TYPE_ADD): Text {
    if ($this->owner === strtolower($player->getName()) || $player->isOp()) {
      $pk = $this->asPacket($type, true);
    }else {
      $pk = $this->asPacket($type);
    }
    $player->dataPacket($pk);
    return $this;
  }

  /**
   * @param  Level  $level
   * @param  int    $type
   * @return Text
   */
  public function sendToLevel(Level $level, int $type = Text::SEND_TYPE_ADD): Text {
    $players = $level->getPlayers();
    foreach ($players as $player) {
      $this->sendToPlayer($player, $type);
    }
    return $this;
  }

  /**
   * @internal
   * @return array
   */
  public function format(): array {
    $data = [
      Manager::KEY_X_VEC => sprintf('%0.1f', $this->pos->x),
      Manager::KEY_Y_VEC => sprintf('%0.1f', $this->pos->y),
      Manager::KEY_Z_VEC => sprintf('%0.1f', $this->pos->z),
      Manager::KEY_TITLE => $this->title,
      Manager::KEY_TEXT => $this->text,
      Manager::KEY_OWNER => $this->owner
    ];
    return $data;
  }
}
