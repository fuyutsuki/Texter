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
  Player,
  level\Level,
  level\Position,
  scheduler\PluginTask,
  utils\TextFormat as TF
};

// Texter
use tokyo\pmmp\Texter\{
  Core,
  scheduler\AddTextsTask,
  texts\CantRemoveFloatingText as CRFT,
  texts\FloatingText as FT,
  texts\Text
};

/**
 * @link EventListener
 */
class RemoveTextsTask extends PluginTask {

  /** @var ?Core */
  private $core = null;
  /** @var ?Player */
  private $player = null;
  /** @var ?Level */
  private $level = null;
  /** @var bool */
  private $afterAdd = false;
  /** @var CantRemoveFloatingText[] */
  private $crfts = [];
  /** @var int */
  private $keyCrfts = 0;
  /** @var FloatingText[] */
  private $fts = [];
  /** @var int */
  private $keyFts = 0;

  public function __construct(Core $core, Player $player, bool $afterAdd = false) {
    parent::__construct($core);
    $this->core = $core;
    $this->player = $player;
    $this->level = $player->getLevel();
    $this->afterAdd = $afterAdd;
    $api = $this->core->getApi();
    $this->crfts = array_values($api->getCrftsByLevel($this->level));
    $this->fts   = array_values($api->getFtsByLevel($this->level));
  }

  public function onRun(int $tick) {
    if (array_key_exists($this->keyCrfts, $this->crfts)) {
      $this->crfts[$this->keyCrfts]->sendToPlayer(Text::SEND_TYPE_REMOVE, $this->player);
      ++$this->keyCrfts;
    }elseif (array_key_exists($this->keyFts, $this->fts)) {
      $this->fts[$this->keyFts]->sendToPlayer(Text::SEND_TYPE_REMOVE, $this->player);
      ++$this->keyFts;
    }else {
      $this->onComplete();
    }
  }

  private function onComplete(): void {
    if ($this->afterAdd) {
      $task = new AddTextsTask($this->core, $this->player, $this->level);
      $this->core->getServer()->getScheduler()->scheduleRepeatingTask($task, 1);
    }
    $this->core->getServer()->getScheduler()->cancelTask($this->getTaskId());
  }
}
