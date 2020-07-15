<?php

/**
 * // English
 *
 * Texter, the display FloatingTextPerticle plugin for PocketMine-MP
 * Copyright (c) 2019-2020 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * This software is distributed under "NCSA license".
 * You should have received a copy of the NCSA license
 * along with this program.  If not, see
 * < https://opensource.org/licenses/NCSA >.
 *
 * ---------------------------------------------------------------------
 * // 日本語
 *
 * TexterはPocketMine-MP向けのFloatingTextPerticleを表示するプラグインです
 * Copyright (c) 2019-2020 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * このソフトウェアは"NCSAライセンス"下で配布されています。
 * あなたはこのプログラムと共にNCSAライセンスのコピーを受け取ったはずです。
 * 受け取っていない場合、下記のURLからご覧ください。
 * < https://opensource.org/licenses/NCSA >
 */

declare(strict_types = 1);

namespace tokyo\pmmp\Texter\task;

use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use tokyo\pmmp\Texter\Core;
use tokyo\pmmp\Texter\text\Text;
use tokyo\pmmp\Texter\TexterApi;

/**
 * Class SendTextsTask
 * @package tokyo\pmmp\Texter\task
 */
class SendTextsTask extends Task {

  private Player $target;
  private int $type;

  private array $ufts;
  private int $uftsKey = 0;
  private int $uftsKeyMax;

  private array $fts;
  private int $ftsKey = 0;
  private int $ftsKeyMax;

  public function __construct(Player $target, Level $sendTo, int $type = Text::SEND_TYPE_ADD) {
    $this->target = $target;
    $this->type = $type;
    $this->ufts = array_values(TexterApi::getUftsByLevel($sendTo));
    $this->uftsKeyMax = count($this->ufts);
    $this->fts = array_values(TexterApi::getFtsByLevel($sendTo));
    $this->ftsKeyMax = count($this->fts);
  }

  public function onRun(int $currentTick) {
    if ($this->uftsKey === $this->uftsKeyMax) {
      if ($this->ftsKey === $this->ftsKeyMax) {
        $this->onSuccess();
      }else {
        $ft = $this->fts[$this->ftsKey];
        $ft->sendToPlayer($this->target, $this->type);
        ++$this->ftsKey;
      }
    }else {
      $uft = $this->ufts[$this->uftsKey];
      $uft->sendToPlayer($this->target, $this->type);
      ++$this->uftsKey;
    }
  }

  private function onSuccess(): void {
    Core::get()->getScheduler()->cancelTask($this->getTaskId());
  }
}