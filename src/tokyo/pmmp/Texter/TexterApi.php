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

namespace tokyo\pmmp\Texter;

// pocketmine
use pocketmine\{
  Player,
  level\Level,
  utils\TextFormat as TF
};

// texter
use tokyo\pmmp\Texter\{
  Core,
  manager\ConfigDataManager,
  manager\FtsDataManager,
  text\Text,
  text\CantRemoveFloatingText as CRFT,
  text\FloatingText as FT
};

/**
 * TexterApi
 */
class TexterApi {

  /** @var ?TexterApi */
  private static $instance = null;
  /** @var ?Core */
  private $core = null;
  /** @var array */
  private $crfts = [];
  /** @var array */
  private $fts = [];

  public function __construct(Core $core) {
    self::$instance = $this;
    $this->core = $core;
    $this->lang = $core->getLang();
  }

  /**
   * @return TexterApi
   */
  public static function get(): TexterApi {
    return self::$instance;
  }

  /**
   * @description
   * Register text in the Texter plugin and enable management using TexterApi
   * If you do not do this registration,
   * you can operate with the function of class tokyo\pmmp\Texter\text\Text
   * @param Text $text
   * @return void
   */
  public function registerText(Text $text) {
    switch (true) {
      case $text instanceof CRFT:
        $this->crfts[$text->getPosition()->getLevel()->getName()][$text->getName()] = $text;
      break;

      case $text instanceof FT:
        $level = $text->getPosition()->getLevel();
        $this->fts[$level->getName()][$text->getName()] = $text;
        $this->core->getFtsDataManager()->saveTextByLevel($level, $text);
      break;
    }
  }

  /**
   * @description
   * Get all CRFTs
   * @return array
   */
  public function getCrfts(): array {
    return $this->crfts;
  }

  /**
   * @description
   * Get all CRFTs at the specified level
   * @param  Level $level
   * @return array
   */
  public function getCrftsByLevel(Level $level): array {
    $levelName = $level->getName();
    if (array_key_exists($levelName, $this->crfts)) {
      return $this->crfts[$levelName];
    }else {
      return [];
    }
  }

  /**
   * @description
   * Get all CRFTs in the specified level name
   * @param  string $levelName
   * @return array
   */
  public function getCrftsByLevelName(string $levelName): array {
    $level = $this->core->getServer()->getLevelByName($levelName);
    if ($level !== null) {
      return $this->getCrftsByLevel($level);
    }else {
      return [];
    }
  }

  /**
   * @description
   * Gets CRFT with text name within the specified level
   * @param  Level  $level
   * @param  string $name
   * @return ?CRFT
   */
  public function getCrftByLevel(Level $level, string $name) {
    $crfts = $this->getCrftsByLevel($level);
    if (!empty($crfts)) {
      if (array_key_exists($name, $crfts)) {
        return $crfts[$name];
      }
    }
    return null;
  }

  /**
   * @description
   * Gets CRFT with text name within the specified level name
   * @param  string $levelName
   * @param  string $name
   * @return ?CRFT
   */
  public function getCrftByLevelName(string $levelName, string $name) {
    $crfts = $this->getCrftsByLevelName($levelName);
    if (!empty($crfts)) {
      if (array_key_exists($name, $crfts)) {
        return $crfts[$name];
      }
    }
    return null;
  }

  /**
   * @description
   * Get CRFT with eid of text within the specified level
   * @param  Level  $level
   * @param  int    $eid
   * @return ?CRFT
   */
  public function getCrftByLevelEid(Level $level, int $eid) {
    $crfts = $this->getCrftsByLevel($level);
    if (!empty($crfts)) {
      $search = null;
      foreach ($crfts as $name => $crft) {
        if ($crft->getEid() === $eid) {
          $search = $crft;
        }
      }
      return $search;
    }
    return null;
  }

  /**
   * @description
   * Get CRFT with eid of text within the specified level name
   * @param  string $levelName
   * @param  int    $eid
   * @return ?CRFT
   */
  public function getCrftByLevelNameEid(string $levelName, int $eid) {
    $crfts = $this->getCrftsByLevelName($levelName);
    if (!empty($crfts)) {
      $search = null;
      foreach ($crfts as $name => $crft) {
        if ($crft->getEid() === $eid) {
          $search = $crft;
        }
      }
      return $search;
    }
    return null;
  }

  /**
   * @description
   * Get all FTs
   * @return array
   */
  public function getFts(): array {
    return $this->fts;
  }

  /**
   * @description
   * Get all FTs at the specified level
   * @param  Level $level
   * @return array
   */
  public function getFtsByLevel(Level $level): array {
    $levelName = $level->getName();
    if (array_key_exists($levelName, $this->fts)) {
      return $this->fts[$levelName];
    }else {
      return [];
    }
  }

  /**
   * @description
   * Get all FTs in the specified level name
   * @param  string $levelName
   * @return array
   */
  public function getFtsByLevelName(string $levelName): array {
    $level = $this->core->getServer()->getLevelByName($levelName);
    if ($level !== null) {
      return $this->getFtsByLevel($level);
    }else {
      return [];
    }
  }

  /**
   * @description
   * Gets FT with text name within the specified level
   * @param  Level  $level
   * @param  string $name
   * @return ?FT
   */
  public function getFtByLevel(Level $level, string $name) {
    $fts = $this->getFtsByLevel($level);
    if (!empty($fts)) {
      if (array_key_exists($name, $fts)) {
        return $fts[$name];
      }
    }
    return null;
  }

  /**
   * @description
   * Gets FT with text name within the specified level name
   * @param  string $levelName
   * @param  string $name
   * @return ?FT
   */
  public function getFtByLevelName(string $levelName, string $name) {
    $fts = $this->getFtsByLevelName($levelName);
    if (!empty($fts)) {
      if (array_key_exists($name, $fts)) {
        return $fts[$name];
      }
    }
    return null;
  }

  /**
   * @description
   * Get FT with eid of text within the specified level
   * @param  Level  $level
   * @param  int    $eid
   * @return ?FT
   */
  public function getFtByLevelEid(Level $level, int $eid) {
    $fts = $this->getFtsByLevel($level);
    if (!empty($fts)) {
      $search = null;
      foreach ($fts as $name => $ft) {
        if ($ft->getEid() === $eid) {
          $search = $ft;
        }
      }
      return $search;
    }
    return null;
  }

  /**
   * @description
   * Get FT with eid of text within the specified level name
   * @param  string $levelName
   * @param  int    $eid
   * @return ?FT
   */
  public function getFtByLevelNameEid(string $levelName, int $eid) {
    $fts = $this->getFtsByLevelName($levelName);
    if (!empty($fts)) {
      $search = null;
      foreach ($fts as $name => $ft) {
        if ($ft->getEid() === $eid) {
          $search = $ft;
        }
      }
      return $search;
    }
    return null;
  }

  /**
   * @description
   * Delete FTs within the specified level
   * @param  Level $level
   * @return bool
   */
  public function removeFtsByLevel(Level $level): bool {
    $fts = $this->getFtsByLevel($level);
    if (!empty($fts)) {
      foreach ($fts as $ft) {
        $ft->sendToLevel($level, Text::SEND_TYPE_REMOVE);
      }
      $this->core->getFtsDataManager()->removeTextsByLevel($level);
      unset($this->fts[$level->getName()]);
      return true;
    }
    return false;
  }

  /**
   * @description
   * Delete FTs within the specified level name
   * @param  string $levelName
   * @return bool
   */
  public function removeFtsByLevelName(string $levelName): bool {
    $level = $this->core->getServer()->getLevelByName($levelName);
    if ($level !== null) {
      return $this->removeFtsByLevel($level);
    }
    return false;
  }

  /**
   * @description
   * Delete FT within the specified level
   * @param  Level $level
   * @return bool
   */
  public function removeFtByLevel(Level $level, string $name): bool {
    $fts = $this->getFtsByLevel($level);
    if (!empty($fts)) {
      if (array_key_exists($name, $fts)) {
        $ft = $fts[$name];
        $ft->sendToLevel($level, Text::SEND_TYPE_REMOVE);
        $this->core->getFtsDataManager()->removeTextByLevel($level, $ft);
        unset($this->fts[$level->getName()][$name]);
        return true;
      }
    }
    return false;
  }

  /**
   * @description
   * Delete FT within the specified level name
   * @param  string $levelName
   * @return bool
   */
  public function removeFtByLevelName(string $levelName, string $name): bool {
    $level = $this->core->getServer()->getLevelByName($levelName);
    if ($level !== null) {
      return $this->removeFtByLevel($level, $name);
    }
    return false;
  }

  /**
   * @description
   * Check if text can be edited
   * @param  Player $player
   * @param  ?FT    $ft = null
   * @return bool
   */
  public static function canEdit(Player $player, FT $ft = null): bool {
    $cdm = ConfigDataManager::get();
    $lang = self::$instance->core->getLang();
    $level = $player->getLevel();
    $levelName = $level->getName();
    if (!$player->isOp()) {
      if (!array_key_exists($levelName, $cdm->getWorldLimit())) {
        if ($ft !== null) {
          if ($ft->getOwner() === strtolower($player->getName())) {
            $str = $ft->getTitle().$ft->getText();
            if (mb_strlen($str) <= $cdm->getCharLimit()) {
              if (mb_substr_count($str, "#") <= $cdm->getFeedLimit()) {
                return true;
              }else {
                $message = $lang->translateString("error.config.limit.feed", [
                  $cdm->getFeedLimit()
                ]);
                $player->sendMessage(TF::RED.Core::PREFIX.$message);
              }
            }else {
              $message = $lang->translateString("error.config.limit.char", [
                $cdm->getCharLimit()
              ]);
              $player->sendMessage(TF::RED.Core::PREFIX.$message);
            }
          }else {
            $message = $lang->translateString("error.permission");
            $player->sendMessage(TF::RED.Core::PREFIX.$message);
          }
        }else {
          return true;
        }
      }else {
        $message = $lang->translateString("error.config.limit.world", [
          $levelName
        ]);
        $player->sendMessage(TF::RED.Core::PREFIX.$message);
      }
    }else {
      return true;
    }
    return false;
  }
}
