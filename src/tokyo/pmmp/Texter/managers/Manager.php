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

namespace tokyo\pmmp\Texter\managers;

// pocketmine
use pocketmine\{
  utils\Config
};

// texter
use tokyo\pmmp\Texter\{
  Core
};

/**
 * AbstractManagerClass
 */
abstract class Manager {

  private const FILE_CONFIG = "config.yml";
  private const FILE_TYPE = Config::YAML;
  private const JSON_OPTIONS = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

  /** @var ?self */
  private static $instance = null;
  /** @var ?Core */
  private $core = null;
  /** @var string */
  private $dir = "";
  /** @var ?Config */
  private $config = null;

  public function __construct(Core $core) {
    $this->core = $core;
    $this->init();
  }

  /**
   * @return void
   */
  private function init(): void {
    $this->core->saveResource(self::FILE_CONFIG);
    $this->config = new Config($this->core->dir.self::FILE_CONFIG, self::FILE_TYPE);
    if (self::FILE_TYPE === Config::JSON) {
      $this->config->enableJsonOption(self::JSON_OPTIONS);
    }
  }

  /**
   * @param  string $key
   * @return string
   */
  public function getString(string $key): string {
    return (string)$this->config->get($key);
  }

  /**
   * @param  string $key
   * @return int
   */
  public function getInt(string $key): int {
    return (int)$this->config->get($key);
  }

  /**
   * @param  string $key
   * @return array
   */
  public function getArray(string $key): array {
    return (array)$this->config->get($key);
  }

  /**
   * @param  string $key
   * @return bool
   */
  public function getBool(string $key): bool {
    return (bool)$this->config->get($key);
  }

  /**
   * @return self ConfigDataManager
   */
  public static function get(): self {
    return self::$instance;
  }
}
