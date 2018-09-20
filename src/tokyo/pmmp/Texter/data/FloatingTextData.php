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

use pocketmine\level\Level;
use pocketmine\plugin\Plugin;
use pocketmine\utils\Config;
use tokyo\pmmp\Texter\text\FloatingText;

/**
 * Class FloatingTextData
 * @package tokyo\pmmp\Texter\data
 */
class FloatingTextData extends Config implements Data {

  /** @var string */
  public const KEY_OWNER = "OWNER";

  /** @var FloatingTextData */
  private static $instance;

  public function __construct(Plugin $plugin, string $path, string $file) {
    $plugin->saveResource($file);
    parent::__construct($path.$file, Config::JSON);
    $this->enableJsonOption(Data::JSON_OPTIONS);
    self::$instance = $this;
  }

  public function saveFtChange(FloatingText $ft): bool {
    $this->setNested("{$ft->level->getFolderName()}.{$ft->getName()}", $ft->format());
    $this->save();
    return true;
  }

  public function removeFtsByLevel(Level $level): bool {
    return $this->removeFtsByLevelName($level->getFolderName());
  }

  public function removeFtsByLevelName(string $levelName): bool {
    if ($bool = $this->exists($levelName))
      $this->remove($levelName);
      $this->save();
    return $bool;
  }

  public function removeFtByLevel(Level $level, string $name): void {
    $this->removeFtByLevelName($level->getFolderName(), $name);
  }

  public function removeFtByLevelName(string $levelName, string $name): void {
    if ($bool = $this->exists($levelName)) {
      $this->removeNested("{$levelName}.{$name}");
      $this->save();
    }
  }

  /**
   * @return FloatingTextData
   */
  public static function make(): FloatingTextData {
    return self::$instance;
  }
}