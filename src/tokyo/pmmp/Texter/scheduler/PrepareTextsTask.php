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

namespace tokyo\pmmp\Texter\scheduler;

// Pocetmine
use pocketmine\{
  level\Position,
  scheduler\PluginTask,
  utils\TextFormat as TF,
  utils\UUID
};

// Texter
use tokyo\pmmp\Texter\{
  Core,
  texts\CantRemoveFloatingText as CRFT,
  texts\FloatingText as FT
};

/**
 * @link prepareTexts()
 */
class PrepareTextsTask extends PluginTask {

  /** @var ?Core */
  private $core = null;
  /** @var mixed[] */
  private $crfts = [];
  /** @var mixed[] */
  private $fts = [];
  /** @var int */
  private $keyCrfts = 0;
  /** @var int */
  private $keyFts = 0;
  /** @var CantRemoveFloatingText[] */
  private $processedCrfts = [];
  /** @var FloatingText[] */
  private $processedFts = [];

  public function __construct(Core $core, array $crfts, array $fts) {
    parent::__construct($core);
    $this->core = $core;
    $this->crfts = array_values($crfts);
    $this->fts = array_values($fts);
  }

  public function onRun(int $tick) {
    if (array_key_exists($this->keyCrfts, $this->crfts)) {
      $tmpCrft = $this->crfts[$this->keyCrfts];
      $level = $this->core->getServer()->getLevelByName($tmpCrft["WORLD"]);
      if ($level !== null) {
        $pos = new Position($tmpCrft["Xvec"], $tmpCrft["Yvec"], $tmpCrft["Zvec"], $level);
      }else {
        $level = $this->core->getServer()->getDefaultLevel();
        $pos = new Position($tmpCrft["Xvec"], $tmpCrft["Yvec"], $tmpCrft["Zvec"], $level);
      }
      $title = $tmpCrft["TITLE"];
      $text = $tmpCrft["TEXT"];
      $crft = new CRFT($pos, $title, $text);
      $this->processedCrfts[] = $crft;
      ++$this->keyCrfts;
    }elseif (array_key_exists($this->keyFts, $this->fts)) {
      $tmpFt = $this->fts[$this->keyFts];
      $level = $this->core->getServer()->getLevelByName($tmpFt["WORLD"]);
      if ($level !== null) {
        $pos = new Position($tmpFt["Xvec"], $tmpFt["Yvec"], $tmpFt["Zvec"], $level);
      }else {
        $level = $this->core->getServer()->getDefaultLevel();
        $pos = new Position($tmpFt["Xvec"], $tmpFt["Yvec"], $tmpFt["Zvec"], $level);
      }
      $title = $tmpFt["TITLE"];
      $text = $tmpFt["TEXT"];
      $owner = $tmpFt["OWNER"];
      if (array_key_exists("UUID", $tmpFt)) {
        $uuid = UUID::fromString($tmpFt["UUID"]);
        $ft = new FT($pos, $title, $text, $owner, $uuid);
      }else {
        $ft = new FT($pos, $title, $text, $owner);
      }
      $this->processedFts[] = $ft;
      ++$this->keyFts;
    }else {
      $this->onComplete();
    }
  }

  private function onComplete(): void {
    $message = $this->core->getLang()->translateString("on.enable.prepared", [
      count($this->processedCrfts),
      count($this->processedFts)
    ]);
    $this->core->getLogger()->info(TF::GREEN.$message);
    $api = $this->core->getApi();
    if (!empty($this->processedCrfts)) {
      foreach ($this->processedCrfts as $crft) {
        $api->registerText($crft);
      }
    }
    if (!empty($this->processedFts)) {
      foreach ($this->processedFts as $ft) {
        $api->registerText($ft);
      }
    }
    $api->saveFts();
    $this->core->getServer()->getScheduler()->cancelTask($this->getTaskId());
  }
}
