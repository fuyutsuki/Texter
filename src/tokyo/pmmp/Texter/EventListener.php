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

namespace tokyo\pmmp\Texter;

use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\Player;
use tokyo\pmmp\Texter\i18n\Lang;
use tokyo\pmmp\Texter\task\SendTextsTask;
use tokyo\pmmp\Texter\text\Text;

class EventListener implements Listener {

  public function onJoin(PlayerJoinEvent $ev): void {
    $p = $ev->getPlayer();
    $l = $p->getLevel();
    $add = new SendTextsTask($p, $l);
    Core::get()->getScheduler()->scheduleDelayedRepeatingTask($add, 20, 1);
  }

  public function onLevelChange(EntityLevelChangeEvent $ev): void {
    $ent = $ev->getEntity();
    if ($ent instanceof Player) {
      $from = $ev->getOrigin();
      $to = $ev->getTarget();
      $core = Core::get();
      $remove = new SendTextsTask($ent, $from, Text::SEND_TYPE_REMOVE);
      $core->getScheduler()->scheduleDelayedRepeatingTask($remove, 20, 1);
      $add = new SendTextsTask($ent, $to);
      $core->getScheduler()->scheduleDelayedRepeatingTask($add, 20, 1);
    }
  }

  public function onSendPacket(DataPacketSendEvent $ev): void {
    $pk = $ev->getPacket();
    if ($pk->pid() === ProtocolInfo::AVAILABLE_COMMANDS_PACKET) {
      /** @var AvailableCommandsPacket $pk */
      if (isset($pk->commandData["txt"])) {
        $p = $ev->getPlayer();
        $txt = $pk->commandData["txt"];
        $txt->commandDescription = Lang::fromLocale($p->getLocale())->translateString("command.txt.description");
      }
    }
  }
}