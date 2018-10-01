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

use pocketmine\plugin\Plugin;
use pocketmine\utils\Config;
use tokyo\pmmp\Texter\Core;

/**
 * Class ConfigData
 * @package tokyo\pmmp\Texter\data
 */
class ConfigData extends Config implements Data {

  /** @var ConfigData */
  private static $instance;

  public function __construct(Plugin $plugin, string $path, string $file) {
    $plugin->saveResource($file, Core::isUpdater());
    parent::__construct($path.$file, Config::YAML);
    self::$instance = $this;
  }

  /**
   * @return string
   */
  public function getLocale(): string {
    return (string) $this->get("locale", "en_US");// Improvement required for pmmp
  }

  /**
   * @return bool
   */
  public function checkUpdate(): bool {
    return (bool) $this->get("check.update", true);
  }

  /**
   * @return bool
   */
  public function canUseCommands(): bool {
    return (bool) $this->get("can.use.commands", true);
  }

  /**
   * @param string $text
   * @return bool
   */
  public function checkCharLimit(string $text): bool {
    $limit = $this->getCharLimit();
    if ($limit === -1)
      return true;
    $length = mb_strlen($text);
    return $limit >= $length;
  }

  /**
   * @return int
   */
  public function getCharLimit(): int {
    return (int) $this->get("char", -1);
  }

  /**
   * @param string $text
   * @return bool
   */
  public function checkFeedLimit(string $text): bool {
    $limit = $this->getFeedLimit();
    if ($limit === -1)
      return true;
    $feed = mb_substr_count($text, "#");
    return $limit >= $feed;
  }

  /**
   * @return int
   */
  public function getFeedLimit(): int {
    return (int) $this->get("feed", -1);
  }

  /**
   * @param string $levelName
   * @return bool
   */
  public function checkWorldLimit(string $levelName): bool {
    if ($this->exists("world")) {
      $limited = $this->get("world", []);
      if (is_array($limited)) {
        $flip = array_flip($limited);
        return !isset($flip[$levelName]);
      }
      if (is_string($limited)) {
        return $limited !== $levelName;
      }
    }
    return true;// isn't limited
  }

  /**
   * @return ConfigData
   */
  public static function make(): ConfigData {
    return self::$instance;
  }
}