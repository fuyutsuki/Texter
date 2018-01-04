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

// Pocketmine
use pocketmine\{
  level\Level,
  utils\Config,
  utils\UUID
};

// Texter
use tokyo\pmmp\Texter\{
  Core,
  scheduler\PrepareTextsTask,
  texts\CantRemoveFloatingText as CRFT,
  texts\FloatingText as FT,
  texts\Text
};

/**
 * TexterApi
 */
class TexterApi {

  public const FILE_CRFTS      = "crfts.json";
  public const FILE_FTS        = "fts.json";

  private const JSON_OPTIONS = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

  /** @var ?TexterApi */
  private static $instance = null;
  /** @var ?Core */
  private $core = null;
  /** @var ?Config */
  private $crftsFile = null;
  /** @var CantRemoveFloatingText[] */
  private $crfts = [];
  /** @var ?Config */
  private $ftsFile = null;
  /** @var FloatingText[] */
  private $fts = [];

  public function __construct(Core $core) {
    self::$instance = $this;
    $this->core = $core;
    $this->core->saveResource(self::FILE_FTS);
    $this->core->saveResource(self::FILE_CRFTS);
    $this->crftsFile = new Config($this->core->dir.self::FILE_CRFTS, Config::JSON);
    $this->crftsFile->enableJsonOption(self::JSON_OPTIONS);
    $this->ftsFile = new Config($this->core->dir.self::FILE_FTS, Config::JSON);
    $this->ftsFile->enableJsonOption(self::JSON_OPTIONS);
  }

  /**
   * @link __construct() prepareTexts
   * @return void
   */
  public function prepareTexts(): void {
    $task = new PrepareTextsTask($this->core, $this->crftsFile->getAll(), $this->ftsFile->getAll());
    var_dump($this->ftsFile);
    $this->core->getServer()->getScheduler()->scheduleRepeatingTask($task, 1);
  }

  /**
   * @return TexterApi
   */
  public static function getInstance(): ?TexterApi {
    return self::$instance;
  }

  /**
   * @return array
   */
  public function getCrfts(): array {
    return $this->crfts;
  }

  /**
   * @param  Level $level
   * @return array
   */
  public function getCrftsByLevel(Level $level): array {
    $levelName = strtolower($level->getName());
    if (array_key_exists($levelName, $this->crfts)) {
      return $this->crfts[$levelName];
    }else {
      return [];
    }
  }

  /**
   * @param  string $levelName
   * @return array
   */
  public function getCrftsByLevelName(string $levelName): array {
    $levelName = strtolower($levelName);
    if (array_key_exists($levelName, $this->crfts)) {
      return $this->crfts[$levelName];
    }else {
      return [];
    }
  }

  /**
   * @param  Level $level
   * @param  int   $eid
   * @return ?CantRemoveFloatingText
   */
  public function getCrftByLevel(Level $level, int $eid): ?CRFT {
    $crfts = $this->getCrftsByLevel($level);
    if (array_key_exists($eid, $crfts)) {
      return $crfts[$eid];
    }else {
      return null;
    }
  }

  /**
   * @param  string $levelName
   * @param  int    $eid
   * @return ?CantRemoveFloatingText
   */
  public function getCrftByLevelName(string $levelName, int $eid): ?CRFT {
    $crfts = $this->getCrftsByLevelName($levelName);
    if (array_key_exists($eid, $crfts)) {
      return $crfts[$eid];
    }else {
      return null;
    }
  }

  /**
   * @return int
   */
  public function getCrftsCount(): int {
    $levels = count($this->crfts);
    $crfts = count($this->crfts, COUNT_RECURSIVE);
    return $crfts - $worlds;
  }

  /**
   * @return array
   */
  public function getFts(): array {
    return $this->fts;
  }

  /**
   * @param  Level $level
   * @return array
   */
  public function getFtsByLevel(Level $level): array {
    $levelName = strtolower($level->getName());
    if (array_key_exists($levelName, $this->fts)) {
      return $this->fts[$levelName];
    }else {
      return [];
    }
  }

  /**
   * @param  string
   * @return array
   */
  public function getFtsByLevelName(string $levelName): array {
    $levelName = strtolower($levelName);
    if (array_key_exists($levelName, $this->crfts)) {
      return $this->crfts[$levelName];
    }else {
      return [];
    }
  }

  /**
   * @param  Level $level
   * @param  int   $eid
   * @return ?FloatingText
   */
  public function getFtByLevel(Level $level, int $eid): ?FT {
    $fts = $this->getFtsByLevel($level);
    if (array_key_exists($eid, $fts)) {
      return $fts[$eid];
    }else {
      return null;
    }
  }

  /**
   * @param  string $levelName
   * @param  int    $eid
   * @return FloatingText
   */
  public function getFtByLevelName(string $levelName, int $eid): ?FT {
    $fts = $this->getFtsByLevelName($levelName);
    if (array_key_exists($eid, $fts)) {
      return $fts[$eid];
    }else {
      return null;
    }
  }

  /**
   * @return int
   */
  public function getFtsCount(): int {
    $levels = count($this->fts);
    $fts = count($this->fts, COUNT_RECURSIVE);
    return $fts - $levels;
  }

  /**
   * Register the FloatingText in TexterApi.
   * @param  Text $text
   * @return void
   */
  public function registerText(Text $text): void {
    switch ($text->getType()) {
      case Text::TEXT_TYPE_FT:
        $this->fts[$text->getLevel()->getName()][$text->getEid()] = $text;
      break;

      case Text::TEXT_TYPE_CRFT:
        $this->crfts[$text->getLevel()->getName()][$text->getEid()] = $text;
      break;
    }
  }

  /**
   * Unregister the FloatingText registered in TexterApi.
   * @param  Text $text
   * @return bool
   */
  public function unregisterText(Text $text): bool {
    $levelName = $text->getLevel()->getName();
    $eid = $text->getEid();
    if (array_key_exists($eid, $this->fts[$levelName])) {
      $ft = $this->getFt($levelName, $eid);
      $ft->removeFromLevel();
      unset($this->fts[$levelName][$eid]);
    }elseif (array_key_exists($eid, $this->crfts[$levelName])) {
      $crft = $this->getCrft($levelName, $eid);
      $crft->removeFromLevel();
      unset($this->crfts[$levelName][$eid]);
    }else {
      return false;
    }
    return true;
  }

  /**
   * Save FloatingTexts in Config file.
   * @return bool
   */
  public function saveFts(): bool {
    $fts = [];
    if (!empty($this->fts)) {
      foreach ($this->fts as $levelName => $ftsOnLevel) {
        foreach ($ftsOnLevel as $eid => $ft) {
          $fts[$ft->getUUID()->__toString()] = [
            "WORLD" => $ft->getLevel()->getName(),
            "Xvec"  => $ft->getX(),
            "Yvec"  => $ft->getY(),
            "Zvec"  => $ft->getZ(),
            "TITLE" => $ft->getTitle(),
            "TEXT"  => $ft->getText(),
            "OWNER" => $ft->getOwner(),
            "UUID"  => $ft->getUUID()->__toString()
          ];
        }
      }
      $this->ftsFile->setAll($fts);
      $this->ftsFile->save(true);// HACK: Async
      return true;
    }else {
      return false;
    }
  }
}
