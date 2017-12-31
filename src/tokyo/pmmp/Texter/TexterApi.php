<?php

/**
 * ## English
 *
 * Texter, the display FloatingTextPerticle plugin for PocketMine-MP
 * Copyright (c) 2017 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * This software is distributed under "MIT license".
 * You should have received a copy of the MIT license
 * along with this program.  If not, see
 * < https://opensource.org/licenses/mit-license >.
 *
 * ---------------------------------------------------------------------
 * ## 日本語
 *
 * TexterはPocketMine-MP向けのFloatingTextPerticleを表示するプラグインです。
 * Copyright (c) 2017 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * このソフトウェアは"MITライセンス"下で配布されています。
 * あなたはこのプログラムと共にMITライセンスのコピーを受け取ったはずです。
 * 受け取っていない場合、下記のURLからご覧ください。
 * < https://opensource.org/licenses/mit-license >
 */

namespace tokyo\pmmp\Texter;

// Pocketmine
use pocketmine\{
  utils\Config,
};

// Texter
use tokyo\pmmp\Texter\{
  Core,
  scheduler\PrepareTextsTask,
  text\CantRemoveFloatingText as CRFT,
  text\FloatingText as FT,
  text\Text
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
  public $crfts = [];
  /** @var ?Config */
  private $ftsFile = null;
  /** @var FloatingText[] */
  public $fts = [];

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
    return $this->crfts[strtolower($level->getName())];
  }

  /**
   * @param  string $levelName
   * @return array
   */
  public function getCrftsByLevelName(string $levelName): array {
    return $this->crfts[strtolower($levelName)];
  }

  /**
   * @param  Level  $level
   * @param  string $uuid
   * @return CantRemoveFloatingText
   */
  public function getCrftByLevel(Level $level, string $uuid): CRFT {
    return $this->crfts[strtolower($level->getName())][$uuid];
  }

  /**
   * @param  string $levelName
   * @param  string $uuid
   * @return CantRemoveFloatingText
   */
  public function getCrftByLevelName(string $levelName, string $uuid): CRFT {
    return $this->crfts[strtolower($levelName)][$uuid];
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
    return $this->fts[strtolower($level->getName())];
  }

  /**
   * @param  string
   * @return array
   */
  public function getFtsByLevelName(string $levelName): array {
    return $this->fts[strtolower($levelName)];
  }

  /**
   * @param  Level  $level
   * @param  string $uuid
   * @return FloatingText
   */
  public function getFtByLevel(Level $level, string $uuid): FT {
    return $this->fts[strtolower($level->getName())][$uuid];
  }

  /**
   * @param  string $levelName
   * @param  string $uuid
   * @return FloatingText
   */
  public function getFtByLevelName(string $levelName, string $uuid): FT {
    return $this->fts[strtolower($levelName)][$uuid];
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
   * @param  array $texts
   * @return void
   */
  public function registerTexts(array $texts): void {
    foreach ($texts as $text) {
      switch ($text->getType()) {
        case Text::TEXT_TYPE_FT:
          $this->fts[$text->getLevel()->getName()][$text->getUUID()] = $text;
        break;

        case Text::TEXT_TYPE_CRFT:
          $this->crfts[$text->getLevel()->getName()][$text->getUUID()] = $text;
        break;
      }
    }
  }

  /**
   * Register the FloatingText in TexterApi.
   * @param  Text $text
   * @return void
   */
  public function registerText(Text $text): void {
    switch ($text->getType()) {
      case Text::TEXT_TYPE_FT:
        $this->fts[$text->getLevel()->getName()][$text->getUUID()] = $text;
      break;

      case Text::TEXT_TYPE_CRFT:
        $this->crfts[$text->getLevel()->getName()][$text->getUUID()] = $text;
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
    $uuid = $text->getUUID();
    if (array_key_exists($uuid, $this->fts)) {
      $ft = $this->getFt($levelName, $uuid);
      $ft->removeFromLevel();
      unset($this->fts[$levelName][$uuid]);
    }elseif (array_key_exists($uuid, $this->crfts[$levelName])) {
      $crft = $this->getCrft($levelName, $uuid);
      $crft->removeFromLevel();
      unset($this->crfts[$levelName][$uuid]);
    }else {
      return false;
    }
    return true;
  }

  /**
   * Save crfts in Config file
   * @return
   */
  public function saveCrfts(): void {
    //$this->core->crftsFile
  }

  public function saveFts() {

  }
}
