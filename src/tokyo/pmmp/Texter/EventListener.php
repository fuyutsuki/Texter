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
  Player,
  event\Listener,
  event\entity\EntityLevelChangeEvent,
  event\player\PlayerJoinEvent
};

// Texter
use tokyo\pmmp\Texter\{
  Core,
  TexterApi,
  scheduler\AddTextsTask
};

/**
 * EventListener
 */
class EventListener implements Listener {

  /** @var ?Core */
  private $core = null;
  /** @var ?TexterApi */
  private $api = null;

  public function __construct(Core $core) {
    $this->core = $core;
    $this->api = $core->getApi();
  }

  public function onJoin(PlayerJoinEvent $event) {
    $player = $event->getPlayer();
    $level = $player->getLevel();
    $task = new AddTextsTask($this->core, $player, $level);
    $this->core->getServer()->getScheduler()->scheduleRepeatingTask($task, 1);
  }

  public function onLevelChange(EntityLevelChangeEvent $event) {
    $entity = $event->getEntity();
    if ($entity instanceof Player) {
      $removeTask = new RemoveTextsTask($this->core, $entity, true);
      $this->core->getServer()->getScheduler()->scheduleDelayedRepeatingTask($task, 10, 1);
    }
  }
}
