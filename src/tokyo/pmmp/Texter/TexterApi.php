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

// texter
use tokyo\pmmp\Texter\{
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
  public function registerText(Text $text): void {
    switch (true) {
      case $text instanceof CRFT:
        $this->crfts[$text->getPosition()->getLevel()->getName()][$text->getName()] = $text;
      break;

      case $text instanceof FT:
        $this->fts[$text->getPosition()->getLevel()->getName()][$text->getName()] = $text;
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
  public function getCrftByLevel(Level $level, string $name): ?CRFT {
    $crfts = $this->getCrftsByLevel($level);
    if (!empty($crfts)) {
      $lowerName = strtolower($name);
      if (array_key_exists($lowerName, $crfts)) {
        return $crfts[$lowerName];
      }else {
        return null;
      }
    }
  }

  /**
   * @description
   * Gets CRFT with text name within the specified level name
   * @param  string $levelName
   * @param  string $name
   * @return ?CRFT
   */
  public function getCrftByLevelName(string $levelName, string $name): ?CRFT {
    $crfts = $this->getCrftsByLevelName($levelName);
    if (!empty($crfts)) {
      $lowerName = strtolower($name);
      if (array_key_exists($lowerName, $crfts)) {
        return $crfts[$lowerName];
      }else {
        return null;
      }
    }
  }

  /**
   * @description
   * Get CRFT with eid of text within the specified level
   * @param  Level  $level
   * @param  int    $eid
   * @return ?CRFT
   */
  public function getCrftByLevelEid(Level $level, int $eid): ?CRFT {
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
  }

  /**
   * @description
   * Get CRFT with eid of text within the specified level name
   * @param  string $levelName
   * @param  int    $eid
   * @return ?CRFT
   */
  public function getCrftByLevelNameEid(string $levelName, int $eid): ?CRFT {
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
  public function getFtByLevel(Level $level, string $name): ?FT {
    $fts = $this->getFtsByLevel($level);
    if (!empty($fts)) {
      $lowerName = strtolower($name);
      if (array_key_exists($lowerName, $fts)) {
        return $fts[$lowerName];
      }else {
        return null;
      }
    }
  }

  /**
   * @description
   * Gets FT with text name within the specified level name
   * @param  string $levelName
   * @param  string $name
   * @return ?FT
   */
  public function getFtByLevelName(string $levelName, string $name): ?FT {
    $fts = $this->getFtsByLevelName($levelName);
    if (!empty($fts)) {
      $lowerName = strtolower($name);
      if (array_key_exists($lowerName, $fts)) {
        return $fts[$lowerName];
      }else {
        return null;
      }
    }
  }

  /**
   * @description
   * Get FT with eid of text within the specified level
   * @param  Level  $level
   * @param  int    $eid
   * @return ?FT
   */
  public function getFtByLevelEid(Level $level, int $eid): ?FT {
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
  }

  /**
   * @description
   * Get FT with eid of text within the specified level name
   * @param  string $levelName
   * @param  int    $eid
   * @return ?FT
   */
  public function getFtByLevelNameEid(string $levelName, int $eid): ?FT {
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
  }
}
