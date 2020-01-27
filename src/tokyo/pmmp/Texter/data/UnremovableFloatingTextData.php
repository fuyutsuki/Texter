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

namespace tokyo\pmmp\Texter\data;

use pocketmine\plugin\Plugin;
use pocketmine\utils\Config;

/**
 * Class UnremovableFloatingTextData
 * @package tokyo\pmmp\Texter\data
 */
class UnremovableFloatingTextData extends Config implements Data {

  /** @var UnremovableFloatingTextData */
  private static $instance;

  public function __construct(Plugin $plugin, string $path, string $file) {
    $plugin->saveResource($file);
    parent::__construct($path.$file, Config::JSON);
    $this->enableJsonOption(Data::JSON_OPTIONS);
    self::$instance = $this;
  }

  public function getData(): array {
    $data = [];
    $ufts = $this->getAll();
    foreach ($ufts as $levelName => $texts) {
      foreach ($texts as $textName => $val) {
        $data[] = [
          Data::KEY_NAME => (string) $textName,
          Data::KEY_LEVEL => (string) $levelName,
          Data::KEY_X => (float) $val[Data::KEY_X],
          Data::KEY_Y => (float) $val[Data::KEY_Y],
          Data::KEY_Z => (float) $val[Data::KEY_Z],
          Data::KEY_TITLE => (string) $val[Data::KEY_TITLE],
          Data::KEY_TEXT => (string) $val[Data::KEY_TEXT]
        ];
      }
    }
    return $data;
  }

  /**
   * @return UnremovableFloatingTextData
   */
  public static function make(): UnremovableFloatingTextData {
    return self::$instance;
  }
}