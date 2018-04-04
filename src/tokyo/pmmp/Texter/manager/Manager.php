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

namespace tokyo\pmmp\Texter\manager;

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

  /** @var string */
  public const KEY_X_VEC = "Xvec";
  public const KEY_Y_VEC = "Yvec";
  public const KEY_Z_VEC = "Zvec";
  public const KEY_TITLE = "TITLE";
  public const KEY_TEXT = "TEXT";
  public const KEY_OWNER = "OWNER";

  /** @var int */
  public const DATA_NAME = 0;
  public const DATA_LEVEL = 1;
  public const DATA_X_VEC = 2;
  public const DATA_Y_VEC = 3;
  public const DATA_Z_VEC = 4;
  public const DATA_TITLE = 5;
  public const DATA_TEXT = 6;
  public const DATA_OWNER = 7;

  /** @var int */
  private const JSON_OPTIONS = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

  /** @var ?self */
  protected static $instance = null;
  /** @var ?Core */
  protected $core = null;
  /** @var string */
  protected $dir = "";
  /** @var string */
  protected $configName = "config.yml";
  /** @var int */
  protected $configType = Config::YAML;
  /** @var ?Config */
  protected $config = null;

  public function __construct(Core $core) {
    $this->core = $core;
    $this->init();
    $this->registerInstance();
  }

  /**
   * @return void
   */
  private function init(): void {
    $this->core->saveResource($this->configName);
    $this->config = new Config($this->core->dir.$this->configName, $this->configType);
    if ($this->configType === Config::JSON) {
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

  public function isOldFormat(): bool {
    $data = $this->config->getAll();
    if (!empty($data)) {
      $depth = $this->array_depth($data);
      if ($depth !== 0 && $depth <= 2) {
        // oldFormt
        return true;
      }
    }
    return false;
  }

  private function array_depth($array, int $depth = 0): int {
    if(!is_array($array)){
      return $depth;
    } else {
      ++$depth;
      $tmp = [];
      foreach($array as $value){
        $tmp[] = $this->array_depth($value, $depth);
      }
      if (empty($tmp)) {
        return 0;
      }else {
        return max($tmp);
      }
    }
  }

  /**
   * @return Config
   */
  public function getConfig(): Config {
    return $this->config;
  }

  /**
   * @internal
   * @return void
   */
  abstract protected function registerInstance(): void;

  /**
   * @return self Manager
   */
  abstract public static function get();
}
