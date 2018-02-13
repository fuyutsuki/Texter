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

/**
 * CrftsDataManager
 */
class CrftsDataManager extends Manager {

  /** @var ?self */
  protected static $instance = null;
  /** @var string */
  protected $configName = "crfts.json";
  /** @var int */
  protected $configType = Config::JSON;

  public function getData(): array {
    return $this->config->getAll();
  }

  public function getDataByLevel(Level $level): array {

  }

  public function getDataByLevelName(string $levelName): array {

  }

  public function isDataExists(Level $level, string $textName): bool {

  }

  public function isDataExistsByLevelName(string $levelName, string $textName): bool {

  }

  public function saveTexts(array $crfts): void {
    // TODO: config形式に戻す
    $this->config->setAll($crfts);
  }

  protected function registerInstance(): void {
    self::$instance = $this;
  }

  public static function get(): self {
    return self::$instance;
  }
}
