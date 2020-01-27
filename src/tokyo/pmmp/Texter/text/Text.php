<?php

/**
 * // English
 *
 * Texter, the display FloatingTextPerticle plugin for PocketMine-MP
 * Copyright (c) 2019-2020 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * This software is distributed under "NCSA license".
 * You should have received a copy of the NCSA license
 * along with this program.  If not, see
 * < https://opensource.org/licenses/NCSA >.
 *
 * ---------------------------------------------------------------------
 * // 日本語
 *
 * TexterはPocketMine-MP向けのFloatingTextPerticleを表示するプラグインです
 * Copyright (c) 2019-2020 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * このソフトウェアは"NCSAライセンス"下で配布されています。
 * あなたはこのプログラムと共にNCSAライセンスのコピーを受け取ったはずです。
 * 受け取っていない場合、下記のURLからご覧ください。
 * < https://opensource.org/licenses/NCSA >
 */

declare(strict_types = 1);

namespace tokyo\pmmp\Texter\text;

use pocketmine\level\Level;
use pocketmine\Player;

/**
 * Interface Text
 * @package tokyo\pmmp\Texter\text
 */
interface Text {

  public const SEND_TYPE_ADD = 0;
  public const SEND_TYPE_EDIT = 1;
  public const SEND_TYPE_MOVE = 2;
  public const SEND_TYPE_REMOVE = 3;

  /**
   * @param Player $player
   * @param int $type
   * @return mixed
   */
  public function sendToPlayer(Player $player, int $type = Text::SEND_TYPE_ADD);

  /**
   * @param Player[] $players
   * @param int $type
   * @return mixed
   */
  public function sendToPlayers(array $players, int $type = Text::SEND_TYPE_ADD);

  /**
   * @param Level $level
   * @param int $type
   * @return mixed
   */
  public function sendToLevel(Level $level, int $type = Text::SEND_TYPE_ADD);

  /**
   * @return array[string key] = value
   */
  public function format(): array;

  public function __toString(): string;

}