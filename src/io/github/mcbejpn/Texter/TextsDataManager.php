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

namespace io\github\mcbejpn\Texter;

// pocketmine
use pocketmine\{
  utils\Config
};

// texter
use io\github\mcbejpn\Texter\{
  Core
};

/**
 * TextsDataManagerClass
 */
class TextsDataManager {

  private const FILE_CONFIG = "config.yml";
  private const FILE_CONFIG_VER = 23;
  private const FILE_CRFTS = "crfts.json";
  private const FILE_FTS = "fts.json";

  private const JSON_OPTIONS = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

  /** @var ?Core */
  private $core = null;

  public function __construct(Core $core) {
    $this->core = $core;
    $this->init();
  }

  private function init(): void {
    $this->core->saveResource(self::FILE_CONFIG);
    $this->core->saveResource(self::FILE_CRFTS);
    $this->core->saveResource(self::FILE_FTS);
    $this->config = new Config($this->core->dir.self::FILE_CONFIG, Config::YAML);
    $this->crftsFile = new Config($this->core->dir.self::FILE_CRFTS, Config::JSON);
    $this->crftsFile->enableJsonOption(self::JSON_OPTIONS);
    $this->ftsFile = new Config($this->core->dir.self::FILE_FTS, Config::JSON);
    $this->ftsFile->enableJsonOption(self::JSON_OPTIONS);
  }

  /**
   * Return config version
   * @return int
   */
  public function getConfigVersion(): int {
    return (int)$this->config->get("configVersion");
  }

  /**
   * Returns the three letter language code
   * @return string
   */
  public function getLangCode(): string {
    return (string)$this->config->get("language");
  }

  /**
   * Return timezone code
   * @return string
   */
  public function getTimezone(): string {
    $timezone = $this->config->get("timezone");
    if (!$timezone) {
      return "UTC";
    }else {
      return $timezone;
    }
  }

  public function 
}
