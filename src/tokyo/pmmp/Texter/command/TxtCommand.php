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
use tokyo\pmmp\Texter\command\sub\TxtAdd;
use tokyo\pmmp\Texter\command\sub\TxtEdit;
use tokyo\pmmp\Texter\command\sub\TxtList;
use tokyo\pmmp\Texter\command\sub\TxtMove;
use tokyo\pmmp\Texter\command\sub\TxtRemove;
use tokyo\pmmp\Texter\Core;
use tokyo\pmmp\Texter\data\ConfigData;
use tokyo\pmmp\Texter\i18n\Lang;

/**
 * Class TxtCommand
 * @package tokyo\pmmp\Texter\command
 */
class TxtCommand extends Command {

  public function __construct() {
    $this->setPermission("texter.command.txt");
    $cl = Lang::fromConsole();
    $description = $cl->translateString("command.txt.description");
    $usage = $cl->translateString("command.txt.usage");
    parent::__construct("txt", $description, $usage);
  }

  public function execute(CommandSender $sender, string $commandLabel, array $args) {
    if (Core::get()->isDisabled() || !$this->testPermission($sender)) return false;
    if ($sender instanceof Player) {
      $cd = ConfigData::make();
      $lang = Lang::fromLocale($sender->getLocale());
      if ($cd->checkWorldLimit($sender->getLevel()->getName())) {
        if (isset($args[0])) {
          switch ($args[0]) {
            case "add":
            case "a":
              new TxtAdd($sender);
              break;

            case "edit":
            case "e":
              new TxtEdit($sender);
              break;

            case "move":
            case "m":
              new TxtMove($sender);
              break;

            case "remove":
            case "r":
              new TxtRemove($sender);
              break;

            case "list":
            case "l":
              new TxtList($sender);
              break;

            default:
              $message = $lang->translateString("command.txt.usage");
              $sender->sendMessage(Core::PREFIX . $message);
              break;
          }
        }else {
          $message = $lang->translateString("command.txt.usage");
          $sender->sendMessage(Core::PREFIX . $message);
        }
      }else {
        $message = $lang->translateString("error.config.limit.world", [
          $sender->getLevel()->getName()
        ]);
        $sender->sendMessage(TextFormat::RED . Core::PREFIX . $message);
      }
    }else {
      $info = Lang::fromConsole()->translateString("error.console");
      Core::get()->getLogger()->info(TextFormat::RED.$info);
    }
    return true;
  }
}