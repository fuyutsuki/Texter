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
  manager\Manager,
  text\CantRemoveFloatingText as CRFT
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
    $data = [];
    $crfts = $this->config->getAll();
    foreach ($crfts as $levelName => $texts) {
      foreach ($texts as $textName => $val) {
        $data[] = [
          Manager::DATA_NAME => $textName,
          Manager::DATA_LEVEL => $levelName,
          Manager::DATA_X_VEC => $val["Xvec"],
          Manager::DATA_Y_VEC => $val["Yvec"],
          Manager::DATA_Z_VEC => $val["Zvec"],
          Manager::DATA_TITLE => $val["TITLE"],
          Manager::DATA_TEXT => $val["TEXT"]
        ];
      }
    }
    return $data;
  }

  public function saveTextByLevel(Level $level, CRFT $crft): bool {
    $levelName = $level->getName();
    $pos = $crft->getPosition();
    $data = [];
    $data[$crft->getName()] = [
      Manager::KEY_X_VEC => $pos->x,
      Manager::KEY_Y_VEC => $pos->y,
      Manager::KEY_Z_VEC => $pos->z,
      Manager::KEY_TITLE => $crft->getTitle(),
      Manager::KEY_TEXT => $crft->getText(),
      Manager::KEY_OWNER => $crft->getOwner()
    ];
    $this->config->set($levelName, $data);
    $this->config->save(true);
    return true;
  }

  public function saveTextByLevelName(string $levelName, CRFT $crft): bool {
    $level = $this->core->getServer()->getLevelByName($levelName);
    if ($level !== null) {
      return $this->saveTextByLevel($level, $crft);
    }
    return false;
  }

  public function removeTextByLevel(Level $level, CRFT $crft): bool {
    $levelName = $level->getName();
    $name = $crft->getName();
    if ($this->config->exists($levelName)) {
      $texts = $this->config->get($levelName);
      if (array_key_exists($name, $texts)) {
        unset($texts[$name]);
        $this->config->set($levelName, $texts);
        $this->config->save();
        return true;
      }
    }
    return false;
  }

  public function removeTextByLevelName(string $levelName, CRFT $crft): bool {
    $level = $this->core->getServer()->getLevelByName($levelName);
    if ($level !== null) {
      return $this->removeTextByLevel($level, $crft);
    }
    return false;
  }

  protected function registerInstance(): void {
    self::$instance = $this;
  }

  public static function get(): self {
    return self::$instance;
  }
}
