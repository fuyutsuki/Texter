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
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use tokyo\pmmp\libform\element\Input;
use tokyo\pmmp\libform\element\Label;
use tokyo\pmmp\libform\FormApi;
use tokyo\pmmp\Texter\command\response\AddResponse;
use tokyo\pmmp\Texter\Core;
use tokyo\pmmp\Texter\data\ConfigData;
use tokyo\pmmp\Texter\i18n\Lang;
use tokyo\pmmp\Texter\text\FloatingText;
use tokyo\pmmp\Texter\TexterApi;

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
      if ($cd->checkWorldLimit($sender->getLevel()->getName())) {
        if (isset($args[0])) {
          switch ($args[0]) {
            case "add":
            case "a":
              $this->add($sender);
              break;

            case "edit":
            case "e":
              $this->edit($sender);
              break;

            case "move":
            case "m":
              $this->move($sender);
              break;

            case "remove":
            case "r":
              $this->remove($sender);
              break;

            case "list":
            case "l":
              $this->list($sender);
              break;

            default:
              $message = Lang::fromLocale($sender->getLocale())->translateString("command.txt.usage");
              break;
          }
        }else {
          $message = Lang::fromLocale($sender->getLocale())->translateString("command.txt.usage");
        }
      }else {
        $message = Lang::fromLocale($sender->getLocale())->translateString("error.config.limit.world", [
          $sender->getLevel()->getName()
        ]);
      }
    }else {
      $info = Lang::fromConsole()->translateString("error.console");
      Core::get()->getLogger()->info(TextFormat::RED.$info);
    }
    if (isset($message)) {
      $sender->sendMessage(Core::get()->getDescription()->getPrefix()." {$message}");
    }
    return true;
  }

  private function add(CommandSender $sender): void {
    /** @var Player $sender */
    $lang = Lang::fromLocale($sender->getLocale());
    $ftName = $lang->translateString("form.ftname.unique");
    $title = $lang->translateString("form.title");
    $text = $lang->translateString("form.text");

    $custom = FormApi::makeCustomForm(function (Player $player, ?array $response) {
      if (!FormApi::formCancelled($response)) {
        $level = $player->getLevel();
        if (!empty($response[AddResponse::NAME])) {
          $exists = TexterApi::getFtByLevel($level, $response[AddResponse::NAME]);
          if ($exists === null) {
            $title = $player->isOp() ? $response[AddResponse::TITLE] : TextFormat::clean($response[AddResponse::TITLE]);
            $text = $player->isOp() ? $response[AddResponse::TEXT] : TextFormat::clean($response[AddResponse::TEXT]);
            $ft = new FloatingText($response[AddResponse::NAME], Position::fromObject($player->add(0, 1, 0), $level), $title, $text, $player->getName());
            $cd = ConfigData::make();
            if ($cd->checkCharLimit($ft->getTitle().$ft->getText())) {
              if ($cd->checkFeedLimit($ft->getTitle().$ft->getText())) {
                $ft->sendToLevel($level);
                TexterApi::registerText($ft);
                $message = Lang::fromLocale($player->getLocale())->translateString("command.txt.add.success", [
                  TextFormat::clean($response[AddResponse::NAME])
                ]);
              }else {
                $message = Lang::fromLocale($player->getLocale())->translateString("error.config.limit.feed", [
                  $cd->getFeedLimit()
                ]);
              }
            }else {
              $message = Lang::fromLocale($player->getLocale())->translateString("error.config.limit.char", [
                $cd->getCharLimit()
              ]);
            }
          }else {
            $message = Lang::fromLocale($player->getLocale())->translateString("error.ftname.exists", [
              $response[AddResponse::NAME]
            ]);
          }
        }else {
          $message = Lang::fromLocale($player->getLocale())->translateString("error.ftname.not.specified");
        }
        if (isset($message))
          $player->sendMessage(Core::get()->getDescription()->getPrefix() . " {$message}");
      }
    });

    $custom->setTitle(Core::get()->getDescription()->getPrefix()." /txt add")
      ->addElement(new Label($lang->translateString("form.add.description")))
      ->addElement(new Input($ftName, $ftName))
      ->addElement(new Label($lang->translateString("command.txt.usage.indent")))
      ->addElement(new Input($title, $title))
      ->addElement(new Input($text, $text))
      ->sendToPlayer($sender);
  }

  private function edit(CommandSender $sender, string $default = ""): void {

  }

  private function move(CommandSender $sender, string $default = ""): void {

  }

  private function remove(CommandSender $sender, string $default = ""): void {

  }

  private function list(CommandSender $sender): void {

  }
}