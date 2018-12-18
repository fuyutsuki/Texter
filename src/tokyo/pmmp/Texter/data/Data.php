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
 * TexterはPocketMine-MP向けのFloatingTextPerticleを表示するプラグインです
 * Copyright (c) 2018 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * このソフトウェアは"MITライセンス"下で配布されています。
 * あなたはこのプログラムと共にMITライセンスのコピーを受け取ったはずです。
 * 受け取っていない場合、下記のURLからご覧ください。
 * < https://opensource.org/licenses/mit-license >
 */

declare(strict_types = 1);

namespace tokyo\pmmp\Texter\data;

/**
 * Interface Data
 * @package tokyo\pmmp\Texter\data
 */
interface Data {

  /** @var string */
  public const KEY_NAME = "NAME";
  public const KEY_LEVEL = "LEVEL";
  public const KEY_X = "Xvec";
  public const KEY_Y = "Yvec";
  public const KEY_Z = "Zvec";
  public const KEY_TITLE = "TITLE";
  public const KEY_TEXT = "TEXT";

  /** @var int */
  public const JSON_OPTIONS = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

  /**
   * @return Data
   */
  public static function make();
}