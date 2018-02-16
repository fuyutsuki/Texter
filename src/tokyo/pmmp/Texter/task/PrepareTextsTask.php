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
  scheduler\PluginTask
};
// texter
use tokyo\pmmp\Texter\{
  Core,
  manager\CrftsDataManager,
  manager\FtsDataManager
};

/**
 * PrepareTextsTask
 */
class PrepareTextsTask extends PluginTask {

  /** @var array */
  private $crfts = [];
  /** @var array */
  private $fts = [];

  public function __construct(Core $core) {
    parent::__construct($core);
    $this->core = $core;
    $this->crfts = $core->getCrftsDataManager()->getData();
    $this->fts = $core->getFtsDataManager()->getData();
  }

  public function onRun(int $tick) {
    if (!empty($this->crfts)) {
      // TODO: テキスト生成&api側登録(登録は自動....かな)
    }
    if (!empty($this->fts)) {

    }
  }
}
