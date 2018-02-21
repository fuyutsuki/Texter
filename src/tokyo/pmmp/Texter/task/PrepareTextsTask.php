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
  level\Position,
  scheduler\PluginTask,
  utils\TextFormat as TF
};
// texter
use tokyo\pmmp\Texter\{
  Core,
  manager\Manager,
  text\CantRemoveFloatingText as CRFT,
  text\FloatingText as FT
};

/**
 * PrepareTextsTask
 */
class PrepareTextsTask extends PluginTask {

  /** @var array */
  private $crfts = [];
  /** @var array */
  private $fts = [];
  /** @var int */
  private $crftsKey = 0;
  /** @var int */
  private $crftsKeyMax = 0;
  /** @var int */
  private $ftsKey = 0;
  /** @var int */
  private $ftsKeyMax = 0;

  public function __construct(Core $core) {
    parent::__construct($core);
    $this->crfts = $core->getCrftsDataManager()->getData();
    $this->crftsKeyMax = count($this->crfts);
    $this->fts = $core->getFtsDataManager()->getData();
    $this->ftsKeyMax = count($this->fts);
  }

  public function onRun(int $tick) {
    if ($this->crftsKey >= $this->crftsKeyMax) {
      if ($this->ftsKey >= $this->ftsKeyMax) {
        $this->onSuccess();
      }else {
        $data = $this->fts[$this->ftsKey];
        $textName = $data[Manager::DATA_NAME];
        $level = $this->getOwner()->getServer()->getLevelByName($data[Manager::DATA_LEVEL]);
        if ($level !== null) {
          $x = (float)$data[Manager::DATA_X_VEC];
          $y = (float)$data[Manager::DATA_Y_VEC];
          $z = (float)$data[Manager::DATA_Z_VEC];
          $pos = new Position($x, $y, $z, $level);
          $title = $data[Manager::DATA_TITLE];
          $text = $data[Manager::DATA_TEXT];
          $owner = $data[Manager::DATA_OWNER];
          $ft = new FT($textName, $pos, $title, $text, $owner);
          $this->getOwner()->getTexterApi()->registerText($ft);
          ++$this->ftsKey;
        }
      }
    }else {
      $data = $this->crfts[$this->crftsKey];
      $textName = $data[Manager::DATA_NAME];
      $level = $this->getOwner()->getServer()->getLevelByName($data[Manager::DATA_LEVEL]);
      if ($level !== null) {
        $x = (float)$data[Manager::DATA_X_VEC];
        $y = (float)$data[Manager::DATA_Y_VEC];
        $z = (float)$data[Manager::DATA_Z_VEC];
        $pos = new Position($x, $y, $z, $level);
        $title = $data[Manager::DATA_TITLE];
        $text = $data[Manager::DATA_TEXT];
        $crft = new CRFT($textName, $pos, $title, $text);
        $this->getOwner()->getTexterApi()->registerText($crft);
        ++$this->crftsKey;
      }
    }
  }

  private function onSuccess(): void {
    $lang = $this->getOwner()->getLang();
    $message = $lang->translateString("on.enable.prepared", [
      $this->crftsKeyMax,
      $this->ftsKeyMax
    ]);
    $this->getOwner()->getLogger()->info(TF::GREEN . $message);
    $this->getOwner()->getServer()->getScheduler()->cancelTask($this->getTaskId());
  }
}
