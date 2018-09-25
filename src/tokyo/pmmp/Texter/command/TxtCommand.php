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
use tokyo\pmmp\libform\element\Button;
use tokyo\pmmp\libform\element\Input;
use tokyo\pmmp\libform\element\Label;
use tokyo\pmmp\libform\FormApi;
use tokyo\pmmp\Texter\command\response\AddResponse;
use tokyo\pmmp\Texter\command\response\ListResponse;
use tokyo\pmmp\Texter\Core;
use tokyo\pmmp\Texter\data\ConfigData;
use tokyo\pmmp\Texter\i18n\Lang;
use tokyo\pmmp\Texter\i18n\Language;
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
      $lang = Lang::fromLocale($sender->getLocale());
      if ($cd->checkWorldLimit($sender->getLevel()->getName())) {
        if (isset($args[0])) {
          switch ($args[0]) {
            case "add":
            case "a":
              $this->add($lang, $sender);
              break;

            case "edit":
            case "e":
              $this->edit($lang, $sender);
              break;

            case "move":
            case "m":
              $this->move($lang, $sender);
              break;

            case "remove":
            case "r":
              $this->remove($lang, $sender);
              break;

            case "list":
            case "l":
              $this->list($lang, $sender);
              break;

            default:
              $message = $lang->translateString("command.txt.usage");
              break;
          }
        }else {
          $message = $lang->translateString("command.txt.usage");
        }
      }else {
        $message = $lang->translateString("error.config.limit.world", [
          $sender->getLevel()->getName()
        ]);
      }
    }else {
      $info = Lang::fromConsole()->translateString("error.console");
      Core::get()->getLogger()->info(TextFormat::RED.$info);
    }
    if (isset($message))
      $sender->sendMessage(Core::PREFIX . $message);
    return true;
  }

  private function add(Language $lang, Player $player): void {
    $ftName = $lang->translateString("form.ftname.unique");
    $title = $lang->translateString("form.title");
    $text = $lang->translateString("form.text");

    $custom = FormApi::makeCustomForm(function (Player $player, ?array $response) use ($lang) {
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
                $message = $lang->translateString("command.txt.add.success", [
                  TextFormat::clean($response[AddResponse::NAME])
                ]);
              }else {
                $message = $lang->translateString("error.config.limit.feed", [
                  $cd->getFeedLimit()
                ]);
              }
            }else {
              $message = $lang->translateString("error.config.limit.char", [
                $cd->getCharLimit()
              ]);
            }
          }else {
            $message = $lang->translateString("error.ftname.exists", [
              $response[AddResponse::NAME]
            ]);
          }
        }else {
          $message = $lang->translateString("error.ftname.not.specified");
        }
        if (isset($message))
          $player->sendMessage(Core::PREFIX . $message);
      }
    });

    $custom->setTitle(Core::PREFIX . "/txt add")
      ->addElement(new Label($lang->translateString("form.add.description")))
      ->addElement(new Input($ftName, $ftName))
      ->addElement(new Label($lang->translateString("command.txt.usage.indent")))
      ->addElement(new Input($title, $title))
      ->addElement(new Input($text, $text))
      ->sendToPlayer($player);
  }

  private function edit(Language $lang, Player $player, string $default = ""): void {
    $description = $lang->translateString("form.edit.description");
    $ftName = $lang->translateString("form.ftname");
    $type = $lang->translateString("form.edit.type");
    $title = $lang->translateString("form.title");
    $text = $lang->translateString("form.text");
    $tips = $lang->translateString("command.txt.usage.indent");
    $content = $lang->translateString("form.edit.content");

    $custom = FormApi::makeCustomForm(function (Player $player, ?array $response) {

    });
  }

  private function move(Language $lang, Player $player, string $default = ""): void {
    echo "test_move".PHP_EOL;
  }

  private function remove(Language $lang, Player $player, string $default = ""): void {
    echo "test_remove".PHP_EOL;
  }

  private function list(Language $lang, Player $sender): void {
    $description = $lang->translateString("form.list.description.1");

    $fts = TexterApi::getFtsByLevel($sender->getLevel());
    $search = [];
    foreach ($fts as $name => $ft) {
      /** @var FloatingText $ft */
      if ($sender->distance($ft) <= 10 && $ft->isOwner($sender)) {
        $search[] = $ft;
      }
    }

    $list1 = FormApi::makeListForm(function (Player $player, ?int $key) use ($lang, $search) {
      if (!FormApi::formCancelled($key)) {
        $target = $search[$key];
        $description = $lang->translateString("form.list.description.2", [
          $target->getName()
        ]);
        $list2 = FormApi::makeListForm(function (Player $player, ?int $key) use ($target) {
          if (!FormApi::formCancelled($key)) {
            switch ($key) {
              case ListResponse::EDIT:
                $func = "edit";
                break;
              case ListResponse::MOVE:
                $func = "move";
                break;
              case ListResponse::REMOVE:
                $func = "remove";
                break;
            }
            $this->$func($player, $target->getName());
          }
        });

        $list2->setTitle(Core::PREFIX . "/txt list")
          ->setContent($description)
          ->addButton(new Button("edit"))
          ->addButton(new Button("move"))
          ->addButton(new Button("remove"))
          ->sendToPlayer($player);
      }
    });

    $list1->setTitle(Core::PREFIX . "/txt list")
      ->setContent($description);
    foreach ($search as $ft) $list1->addButton(new Button($ft->getName()));
    $list1->sendToPlayer($sender);
  }
}