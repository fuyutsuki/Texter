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
  Player,
  level\Level,
  scheduler\Task
};

// texter
use tokyo\pmmp\Texter\{
  Core,
  TexterApi,
  text\Text
};

/**
 * SendTextsTask
 */
class SendTextsTask extends Task {

  /** @var Core */
  private $core;
  /** @var CRFT[] */
  private $crfts;
  /** @var int */
  private $crftsKey = 0;
  /** @var int */
  private $crftsKeyMax;
  /** @var FT[] */
  private $fts;
  /** @var int */
  private $ftsKey = 0;
  /** @var int */
  private $ftsKeyMax;
  /** @var Player */
  private $player;
  /** @var int */
  private $type = Text::SEND_TYPE_ADD;

  public function __construct(Core $core, Level $level, Player $player, int $type = Text::SEND_TYPE_ADD) {
    $this->core = $core;
    $this->crfts = array_values(TexterApi::getCrftsByLevel($level));
    $this->crftsKeyMax = count($this->crfts);
    $this->fts = array_values(TexterApi::getFtsByLevel($level));
    $this->ftsKeyMax = count($this->fts);
    $this->player = $player;
    $this->type = $type;
  }

  public function onRun(int $tick) {
    if ($this->crftsKey === $this->crftsKeyMax) {
      if ($this->ftsKey === $this->ftsKeyMax) {
        $this->onSuccess();
      }else {
        $ft = $this->fts[$this->ftsKey];
        $ft->sendToPlayer($this->player, $this->type);
        ++$this->ftsKey;
      }
    }else {
      $crft = $this->crfts[$this->crftsKey];
      $crft->sendToPlayer($this->player, $this->type);
      ++$this->crftsKey;
    }
  }

  private function onSuccess(): void {
    $this->core->getScheduler()->cancelTask($this->getTaskId());
  }
}
