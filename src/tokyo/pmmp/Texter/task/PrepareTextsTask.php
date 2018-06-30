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

namespace tokyo\pmmp\Texter\task;

// pocketmine
use pocketmine\{
  level\Position, scheduler\Task, Server, utils\TextFormat as TF
};

// texter
use tokyo\pmmp\Texter\{
  Core,
  data\CrftsData,
  data\Data,
  data\FtsData,
  i18n\Lang,
  text\CantRemoveFloatingText as CRFT,
  text\FloatingText as FT,
  TexterApi
};

/**
 * PrepareTextsTask
 */
class PrepareTextsTask extends Task {

  /** @var Core */
  private $core;
  /** @var Server */
  private $server;
  /** @var array */
  private $crfts;
  /** @var array */
  private $fts;
  /** @var int */
  private $crftsCount = 0;
  /** @var int */
  private $crftsMax;
  /** @var int */
  private $ftsCount = 0;
  /** @var int */
  private $ftsMax;

  public function __construct(Core $core) {
    $this->core = $core;
    $this->server = $core->getServer();
    $this->crfts = CrftsData::get()->getData();
    $this->crftsMax = count($this->crfts);
    $this->fts = FtsData::get()->getData();
    $this->ftsMax = count($this->fts);
  }

  public function onRun(int $tick) {
    if ($this->crftsCount === $this->crftsMax) {
      if ($this->ftsCount === $this->ftsMax) {
        $this->onSuccess();
      }else {
        $data = $this->fts[$this->ftsCount];
        $textName = $data[Data::DATA_NAME];
        $loaded = $this->server->isLevelLoaded($data[Data::DATA_LEVEL]);
        $successed = true;
        if (!$loaded) $successed = $this->server->loadLevel($data[Data::DATA_LEVEL]);
        if ($successed) {
          $level = $this->server->getLevelByName($data[Data::DATA_LEVEL]);
          if ($level !== null) {
            $x = (float)$data[Data::DATA_X_VEC];
            $y = (float)$data[Data::DATA_Y_VEC];
            $z = (float)$data[Data::DATA_Z_VEC];
            $pos = new Position($x, $y, $z, $level);
            $title = $data[Data::DATA_TITLE];
            $text = $data[Data::DATA_TEXT];
            $owner = $data[Data::DATA_OWNER];
            $ft = new FT($textName, $pos, $title, $text, $owner);
            TexterApi::registerText($ft);
          }
        }
        ++$this->ftsCount;
      }
    }else {
      $data = $this->crfts[$this->crftsCount];
      $textName = $data[Data::DATA_NAME];
      $loaded = $this->server->isLevelLoaded($data[Data::DATA_LEVEL]);
      $successed = true;
      if (!$loaded) $successed = $this->server->loadLevel($data[Data::DATA_LEVEL]);
      if ($successed) {
        $level = $this->server->getLevelByName($data[Data::DATA_LEVEL]);
        if ($level !== null) {
          $x = (float)$data[Data::DATA_X_VEC];
          $y = (float)$data[Data::DATA_Y_VEC];
          $z = (float)$data[Data::DATA_Z_VEC];
          $pos = new Position($x, $y, $z, $level);
          $title = $data[Data::DATA_TITLE];
          $text = $data[Data::DATA_TEXT];
          $crft = new CRFT($textName, $pos, $title, $text);
          TexterApi::registerText($crft);
        }
      }
      ++$this->crftsCount;
    }
  }

  private function onSuccess(): void {
    $lang = Lang::detectLangByStr();
    $message = $lang->translateString("on.enable.prepared", [
      count(TexterApi::getCrfts(), COUNT_RECURSIVE) - count(TexterApi::getCrfts()),
      count(TexterApi::getFts(), COUNT_RECURSIVE) - count(TexterApi::getFts())
    ]);
    $this->core->getLogger()->info(TF::GREEN . $message);
    $this->core->getScheduler()->cancelTask($this->getTaskId());
  }
}
