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

namespace tokyo\pmmp\Texter;

// pocketmine

// texter
use tokyo\pmmp\Texter\{
  text\Text,
  text\CantRemoveFloatingText as CRFT,
  text\FloatingText as FT
};

/**
 * TexterApi
 */
class TexterApi {

  /** @var ?TexterApi */
  private static $instance = null;
  /** @var ?Core */
  private $core = null;
  /** @var array */
  private $crfts = [];
  /** @var array */
  private $fts = [];

  public function __construct(Core $core) {
    self::$instance = $this;
    $this->core = $core;
  }

  public static function get(): TexterApi {
    return self::$instance;
  }

  public function registerText(Text $text): void {
    switch (true) {
      case $text instanceof CRFT:
        $this->crfts[$text->getPosition()->getLevel()->getName()][$text->getName()] = $text;
      break;

      case $text instanceof FT:
        $this->fts[$text->getPosition()->getLevel()->getName()][$text->getName()] = $text;
      break;
    }
  }
}
