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
};

/**
 * TexterApi
 */
class TexterApi {

  public const FILE_CRFTS      = "crfts.json";
  public const FILE_FTS        = "fts.json";

  private const JSON_OPTIONS = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

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
    $this->core = $core;
    $this->initFiles();
  }

  /**
   * init
   */
  private function initFiles(): void {
    $this->core->saveResource(self::FILE_FTS);
    $this->core->saveResource(self::FILE_CRFTS);
    $this->crftsFile = new Config($this->core->dir.self::FILE_CRFTS, Config::JSON);
    $this->crftsFile->enableJsonOption(self::JSON_OPTIONS);
    $this->crfts = $this->crftsFile->getAll();
    $this->ftsFile = new Config($this->core->dir.self::FILE_FTS, Config::JSON);
    $this->ftsFile->enableJsonOption(self::JSON_OPTIONS);
    $this->fts = $this->ftsFile->getAll();
  }
}
