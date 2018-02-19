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

// pocketmine
use pocketmine\{
  Player,
  event\Listener,
  event\entity\EntityLevelChangeEvent,
  event\player\PlayerJoinEvent
};

// texter
use tokyo\pmmp\Texter\{
  task\SendTextsTask,
  text\Text
};

/**
 * EventListenerClass
 */
class EventListener implements Listener {

  /** @var ?Core */
  private $core = null;

  public function __construct(Core $core) {
    $this->core = $core;
  }

  public function onJoin(PlayerJoinEvent $event) {
    $player = $event->getPlayer();
    $level = $player->getLevel();
    $task = new SendTextsTask($this->core, $level, $player);
    $this->core->getServer()->getScheduler()->scheduleRepeatingTask($task, 1);
  }

  public function onLevelChange(EntityLevelChangeEvent $event) {
    $entity = $event->getEntity();
    if ($entity instanceof Player) {
      $origin = $event->getOrigin();
      $target = $event->getTarget();
      $task1 = new SendTextsTask($this->core, $origin, $player, Text::SEND_TYPE_REMOVE);
      $this->core->getServer()->getScheduler()->scheduleRepeatingTask($task1, 1);
      $task2 = new SendTextsTask($this->core, $target, $player);
      $this->core->getServer()->getScheduler()->scheduleRepeatingTask($task2, 1);
    }
  }
}
