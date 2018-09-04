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

namespace tokyo\pmmp\Texter;

use pocketmine\plugin\PluginBase;
use tokyo\pmmp\Texter\i18n\Lang;

class Core extends PluginBase {

  /** @var Core */
  private static $core;

  public function onLoad(): void {
    self::$core = $this;
    $this
      ->checkOldDirectives() // check old config file
      ->loadResources()
      ->initLanguage();
  }

  public function onEnable(): void {
    // TODO
  }

  private function checkOldDirectives(): self {
    $dir = $this->getDataFolder();
    return $this;
  }

  private function loadResources(): self {
    return $this;
  }

  private function initLanguage(): self {
    new Lang($this);
    return $this;
  }

  /**
   * @return Core
   */
  public static function get(): Core {
    return self::$core;
  }
}