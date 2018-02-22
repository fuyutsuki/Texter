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
  level\Level,
  utils\Config
};

// texter
use tokyo\pmmp\Texter\{
  manager\Manager,
  text\FloatingText as FT
};

/**
 * FtsDataManager
 */
class FtsDataManager extends Manager {

  /** @var ?self */
  protected static $instance = null;
  /** @var string */
  protected $configName = "fts.json";
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
          Manager::DATA_TEXT => $val["TEXT"],
          Manager::DATA_OWNER => $val["OWNER"]
        ];
      }
    }
    return $data;
  }

  public function saveTextByLevel(Level $level, FT $ft): bool {
    $levelName = $level->getName();
    if ($this->config->exists($levelName)) {
      $texts = $this->getArray($levelName);
      $texts[$ft->getName()] = $ft->format();
      $this->config->set($levelName, $texts);
    }else {
      $this->config->set($levelName, [$ft->getName() => $ft->format()]);
    }
    $this->config->save(true);
    return true;
  }

  public function saveTextByLevelName(string $levelName, FT $ft): bool {
    $level = $this->core->getServer()->getLevelByName($levelName);
    if ($level !== null) {
      return $this->saveTextByLevel($level, $ft);
    }
    return false;
  }

  public function removeTextsByLevel(Level $level): bool {
    $levelName = $level->getName();
    if ($this->config->exists($levelName)) {
      $this->config->remove($levelName);
      $this->config->save(true);
      return true;
    }
    return false;
  }

  public function removeTextsByLevelName(string $levelName): bool {
    $level = $this->core->getServer()->getLevelByName($levelName);
    if ($level !== null) {
      return $this->removeTextsByLevel($level);
    }
    return false;
  }

  public function removeTextByLevel(Level $level, FT $ft): bool {
    $levelName = $level->getName();
    $name = $ft->getName();
    if ($this->config->exists($levelName)) {
      $texts = $this->getArray($levelName);
      if (array_key_exists($name, $texts)) {
        unset($texts[$name]);
        $this->config->set($levelName, $texts);
        $this->config->save();
        return true;
      }
    }
    return false;
  }

  public function removeTextByLevelName(string $levelName, FT $ft): bool {
    $level = $this->core->getServer()->getLevelByName($levelName);
    if ($level !== null) {
      return $this->removeTextByLevel($level, $ft);
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
