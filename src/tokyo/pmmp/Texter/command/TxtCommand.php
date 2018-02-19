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

namespace tokyo\pmmp\Texter\command;

// pocketmine
use pocketmine\{
  Player,
  command\Command,
  command\CommandSender,
  utils\TextFormat as TF
};

// texter

/**
 * TxtCommandClass
 */
class TxtCommand extends Command {

  private const COMMAND = "txt";
  private const PERMISSION = "texter.command.txt";

  /** @var ?Core */
  private $core = null;
  /** @var ?BaseLang */
  private $lang = null;

  public function __construct(Core $core) {
    $this->core = $core;
    $this->lang = $core->getLang();
    $this->cdm = $core->getConfigDataManager();
    //
    $description = $this->lang->translateString("command.txt.description");
    $usage = $this->lang->translateString("command.txt.usage.inline");
    parent::__construct(self::COMMAND, $description, $usage);
    $this->setPermission(self::PERMISSION);
    $this->initHelp();
  }

  public function execute(CommandSender $sender, string $label, array $args) {
    if (!$this->core->isEnabled()) return false;
    if (!$this->testPermission($sender)) return false;
    if ($sender instanceof Player) {
      $level = $sender->getLevel();
      $levelName = $level->getName();
      if (!array_key_exists($levelName, $this->cdm->getWorldLimit())) {
        if (isset($args[0])) {
          switch (strtolower($args[0])) {
            case 'add':
            case 'a':
              # code...
            break;

            case 'edit':
            case 'e':

            case 'move':
            case 'm':

            case 'remove':
            case 'r':

            default:
              //help
            break;
          }
        }else {
          //help
        }
      }else {
        //worldlimit
      }
    }else {
      //console
    }
  }

  private function initHelp(): void {
    $this->help  = $this->lang->transrateString("command.txt.usage")."\n";
    $this->help .= $this->lang->transrateString("command.txt.usage.add")."\n";
    $this->help .= $this->lang->transrateString("command.txt.usage.remove")."\n";
    $this->help .= $this->lang->transrateString("command.txt.usage.update")."\n";
    $this->help .= $this->lang->transrateString("command.txt.usage.indent");
  }
}
