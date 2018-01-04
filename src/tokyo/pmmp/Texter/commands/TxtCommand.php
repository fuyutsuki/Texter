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
  command\Command,
  command\CommandSender,
  lang\BaseLang,
  level\Position,
  utils\TextFormat as TF
};

// Texter
use tokyo\pmmp\Texter\{
  Core,
  text\Text,
  text\CantRemoveFloatingText as CRFT,
  text\FloatingText as FT
};

/**
 * TxtCommand
 */
class TxtCommand extends Command {

  private const NAME = "txt";
  private const DESCRIPTION = "command.description.txt";
  private const USAGE = "/txt <add | remove | update | move | help>";
  private const PERMISSION = "texter.command.txt";

  private const COMMAND_USAGE = "command.txt.usage";
  private const COMMAND_USAGE_ADD = "command.txt.usage.add";
  private const COMMAND_USAGE_REMOVE = "command.txt.usage.remove";
  private const COMMAND_USAGE_MOVE = "command.txt.usage.move";
  private const COMMAND_USAGE_UPDATE = "command.txt.usage.update";
  private const COMMAND_USAGE_INDENT = "command.txt.usage.indent";
  private const COMMAND_LIMIT_CHAR = "command.txt.limit.char";
  private const COMMAND_LIMIT_FEED = "command.txt.limit.feed";
  private const COMMAND_LIMIT_WORLD = "command.txt.limit.world";
  private const ERROR_CONSOLE = "error.console";

  /** @var ?Core */
  private $core = null;
  /** @var ?TexterApi */
  private $api = null;
  /** @var ?BaseLang */
  private $lang = null;

  public function __construct(Core $core, BaseLang $lang) {
    parent::__construct(self::NAME, $lang->translateString(self::DESCRIPTION), self::USAGE);
    $this->core = $core;
    $this->api = $core->getApi();
    $this->lang = $lang;
    $this->setPermission(self::PERMISSION);
  }

  public function execute(CommandSender $sender, string $label, array $args) {
    if (!$this->core->isEnabled()) return false;
    if (!$this->testPermission($sender)) return false;
    if (!$sender instanceof Player) {// Console
      $message = $this->lang->translateString(self::ERROR_CONSOLE);
      $sender->sendMessage(TF::RED.Core::PREFIX.$message);
    }else {
      $level = $sender->getLevel();
      $levelName = $level->getName();
      if (!array_key_exists($levelName, $this->core->getWorldLimit())) {
        $message = $this->lang->translateString(self::COMMAND_LIMIT_WORLD, [
          $levelName
        ]);
        $sender->sendMessage(TF::RED.Core::PREFIX.$message);
      }else {
        if (!empty($args[0])) {
          switch (strtolower($args[0])) {// subCommands
            case 'add':
            case 'a':
              if (!empty($args[1])) {// Title
                $title = str_replace("#", "\n", $args[1]);
                if (!empty($args[2])) {
                  $text = str_replace("#", "\n", implode(" ", array_slice($args, 2)));
                }else {
                  $text = "";
                }
                $this->add($sender, $title, $text);
              }else {
                $message = $this->lang->translateString(self::COMMAND_USAGE_ADD);
                $sender->sendMessage(TF::BLUE.Core::PREFIX.$message);
              }
            break;

            case 'remove':
            case 'r':
              $this->remove($sender);
            break;

            case 'move':
            case 'm':
              $this->move($sender);
            break;

            case 'update':
            case 'u':
              $this->update($sender);
            break;

            case 'help':
            case 'h':
            case '?':
            default:
              $this->help($sender);
            break;
          }
        }
      }
    }
  }

  private function add(Player $player, string $title, string $text): void {
    $ft = new FT($player, $title, $text, $player->getName());
    $ft->sendToLevel(Text::SEND_TYPE_ADD);
    $this->api->registerText($ft);
  }

  private function remove(Player $player, int $eid) {
    
  }

  private function move(Player $player) {

  }

  private function update(Player $player) {

  }

  private function help(Player $player) {

  }
}
