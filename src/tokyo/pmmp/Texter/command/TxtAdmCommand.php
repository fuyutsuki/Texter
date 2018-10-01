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
 * TexterはPocketMine-MP向けのFloatingTextPerticleを表示するプラグインです
 * Copyright (c) 2018 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * このソフトウェアは"MITライセンス"下で配布されています。
 * あなたはこのプログラムと共にMITライセンスのコピーを受け取ったはずです。
 * 受け取っていない場合、下記のURLからご覧ください。
 * < https://opensource.org/licenses/mit-license >
 */

declare(strict_types = 1);

namespace tokyo\pmmp\Texter\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use tokyo\pmmp\Texter\Core;
use tokyo\pmmp\Texter\i18n\Lang;
use tokyo\pmmp\Texter\task\SafetyTimerTask;
use tokyo\pmmp\Texter\TexterApi;

class TxtAdmCommand extends Command {

  /** @var bool */
  private $safety = true;

  public function __construct() {
    $this->setPermission("texter.command.txtadm");
    $cl = Lang::fromConsole();
    $description = $cl->translateString("command.txtadm.description");
    $usage = $cl->translateString("command.txtadm.usage");
    parent::__construct("txtadm", $description, $usage);
  }

  public function execute(CommandSender $sender, string $commandLabel, array $args) {
    if (Core::get()->isDisabled() || !$this->testPermission($sender)) return false;
    if ($sender instanceof Player) {
      $message = Lang::fromLocale($sender->getLocale())->translateString("error.player");
      $sender->sendMessage(TextFormat::RED . Core::PREFIX . $message);
    }else {
      $lang = Lang::fromConsole();
      if (isset($args[0])) {
        $logger = Core::get()->getLogger();
        switch ($args[0]) {
          case "allremove":
          case "ar":
            if ($this->safety) {
              // TODO: confirmを打たせる
              $message = $lang->translateString();
              $logger->warning($message);
            }else {
              $fts = TexterApi::getFts();
              foreach ($fts as $levelName => $levelFts) {
                TexterApi::removeFtsByLevelName($levelName);
              }
              $message = $lang->translateString("command.txtadm.ar.success");
            }
            break;

          case "userremove":
          case "ur":
            break;

          case "levelremove":
          case "lr":
            break;

          case "confirm":
            // TODO: セーフティを解除した旨のメッセージ
            Core::get()->getScheduler()->scheduleDelayedTask(new SafetyTimerTask($this), 20);
            $this->safety = false;
            break;

          case "info":
          case "i":
            break;

          default:
            $message = $lang->translateString("command.txtadm.usage.console");
            Core::get()->getLogger()->info($message);
            break;
        }
      }else {
        $message = $lang->translateString("command.txtadm.usage.console");
        Core::get()->getLogger()->info($message);
      }
    }
  }

  public function reLockSafety(): void {
    $this->safety = true;
  }
}