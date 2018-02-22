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
  level\Position,
  utils\TextFormat as TF
};

// texter
use tokyo\pmmp\Texter\{
  Core,
  text\Text,
  text\FloatingText as FT
};

// mcbeformapi
use tokyo\pmmp\MCBEFormAPI\{
  FormApi,
  element\Button,
  element\Dropdown,
  element\Input,
  element\Label,
  element\Slider,
  element\StepSlider,
  element\Toggle
};

/**
 * TxtCommandClass
 */
class TxtCommand extends Command {

  /** @var string */
  private const COMMAND = "txt";
  private const PERMISSION = "texter.command.txt";

  /** @var int */
  private const ADD_KEY_FTNAME = 1;
  private const ADD_KEY_TITLE = 3;
  private const ADD_KEY_TEXT = 4;
  private const EDIT_KEY_FTNAME = 1;
  private const EDIT_KEY_TYPE = 2;
  private const EDIT_TITLE = 0;
  private const EDIT_TEXT = 1;
  private const EDIT_KEY_CONTENT = 4;
  private const MOVE_KEY_FTNAME = 1;
  private const REMOVE_KEY_FTNAME = 1;

  /** @var ?Core */
  private $core = null;
  /** @var ?BaseLang */
  private $lang = null;

  public function __construct(Core $core) {
    $this->core = $core;
    $this->texterApi = $core->getTexterApi();
    $this->lang = $core->getLang();
    $this->cdm = $core->getConfigDataManager();
    //
    $description = $this->lang->translateString("command.txt.description");
    $usage = $this->lang->translateString("command.txt.usage.inline");
    parent::__construct(self::COMMAND, $description, $usage);
    $this->setPermission(self::PERMISSION);
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
              $custom = $this->core->getFormApi()->makeCustomForm([$this, "addCommand"]);
              $description = $this->lang->translateString("form.add.description");
              $tips = $this->lang->translateString("command.txt.usage.indent");
              $ftName = $this->lang->translateString("form.ftname.unique");
              $title = $this->lang->translateString("form.title");
              $text = $this->lang->translateString("form.text");
              $custom->setTitle(Core::PREFIX."/txt a(dd)")
              ->addElement(new Label($description))
              ->addElement(new Input($ftName, $ftName))
              ->addElement(new Label($tips))
              ->addElement(new Input($title, $title))
              ->addElement(new Input($text, $text))
              ->sendToPlayer($sender);
            break;

            case 'edit':
            case 'e':
              $custom = $this->core->getFormApi()->makeCustomForm([$this, "editCommand"]);
              $description = $this->lang->translateString("form.edit.description");
              $ftName = $this->lang->translateString("form.ftname");
              $type = $this->lang->translateString("form.edit.type");
              $title = $this->lang->translateString("form.title");
              $text = $this->lang->translateString("form.text");
              $tips = $this->lang->translateString("command.txt.usage.indent");
              $content = $this->lang->translateString("form.edit.content");
              $custom->setTitle(Core::PREFIX."/txt e(dit)")
              ->addElement(new Label($description))
              ->addElement(new Input($ftName, $ftName))
              ->addElement(new StepSlider($type, [$title, $text]))
              ->addElement(new Label($tips))
              ->addElement(new Input($content, $content))
              ->sendToPlayer($sender);
            break;

            case 'move':
            case 'm':
              $custom = $this->core->getFormApi()->makeCustomForm([$this, "moveCommand"]);
              $description = $this->lang->translateString("form.move.description");
              $ftName = $this->lang->translateString("form.ftname");
              $custom->setTitle(Core::PREFIX."/txt m(ove)")
              ->addElement(new Label($description))
              ->addElement(new Input($ftName, $ftName))
              ->sendToPlayer($sender);
            break;

            case 'remove':
            case 'r':
              $custom = $this->core->getFormApi()->makeCustomForm([$this, "removeCommand"]);
              $description = $this->lang->translateString("form.remove.description");
              $ftName = $this->lang->translateString("form.ftname");
              $custom->setTitle(Core::PREFIX."/txt r(emove)")
              ->addElement(new Label($description))
              ->addElement(new Input($ftName, $ftName))
              ->sendToPlayer($sender);
            break;

            case 'list':
            case 'l':

            break;

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

  public function addCommand(Player $player, $response): void {
    if (!FormApi::formCancelled($response)) {
      $level = $player->getLevel();
      $exists = $this->texterApi->getFtByLevel($level, $response[self::ADD_KEY_FTNAME]);
      if ($exists === null) {
        $ft = new FT(
          $response[self::ADD_KEY_FTNAME],
          Position::fromObject($player->add(0, 1, 0), $level),
          $response[self::ADD_KEY_TITLE],
          $response[self::ADD_KEY_TEXT],
          $player->getName()
        );
        $ft->sendToLevel($level);
        $this->texterApi->registerText($ft);
        $message = $this->lang->translateString("command.txt.add.success", [
          TF::clean($response[self::ADD_KEY_FTNAME])
        ]);
        $player->sendMessage(TF::GREEN.Core::PREFIX.$message);
      }else {
        $message = $this->lang->translateString("error.ftname.exists", [
          TF::clean($response[self::ADD_KEY_FTNAME])
        ]);
        $player->sendMessage(TF::RED.Core::PREFIX.$message);
      }
    }
  }

  public function editCommand(Player $player, $response): void {
    if (!FormApi::formCancelled($response)) {
      $level = $player->getLevel();
      $ft = $this->texterApi->getFtByLevel($level, $response[self::EDIT_KEY_FTNAME]);
      if ($ft !== null) {
        switch ($response[self::EDIT_KEY_TYPE]) {
          case self::EDIT_TITLE:
            $ft->setTitle($response[self::EDIT_KEY_CONTENT])
            ->sendToLevel($level, Text::SEND_TYPE_EDIT);
            $this->core->getFtsDataManager()->saveTextByLevel($level, $ft);
            $message = $this->lang->translateString("command.txt.edit.success", [
              TF::clean($response[self::EDIT_KEY_FTNAME]),
              $this->lang->translateString("form.title")
            ]);
          break;

          case self::EDIT_TEXT:
            $ft->setText($response[self::EDIT_KEY_CONTENT])
            ->sendToLevel($level, Text::SEND_TYPE_EDIT);
            $this->core->getFtsDataManager()->saveTextByLevel($level, $ft);
            $message = $this->lang->translateString("command.txt.edit.success", [
              TF::clean($response[self::EDIT_KEY_FTNAME]),
              $this->lang->translateString("form.text")
            ]);
          break;
        }
        $player->sendMessage(TF::GREEN.Core::PREFIX.$message);
      }else {
        $message = $this->lang->translateString("error.ftname.not.exists");
        $player->sendMessage(TF::RED.Core::PREFIX.$message);
      }
    }
  }

  public function moveCommand(Player $player, $response): void {
    if (!FormApi::formCancelled($response)) {
      $level = $player->getLevel();
      $ft = $this->texterApi->getFtByLevel($level, $response[self::MOVE_KEY_FTNAME]);
      if ($ft !== null) {
        $ft->setPosition(Position::fromObject($player->add(0, 2, 0), $level))
        ->sendToLevel($level, Text::SEND_TYPE_MOVE);
        $this->core->getFtsDataManager()->saveTextByLevel($level, $ft);
        $message = $this->lang->translateString("command.txt.move.success", [
          TF::clean($response[self::MOVE_KEY_FTNAME]),
          $this->lang->translateString("form.move.here")
        ]);
        $player->sendMessage(TF::GREEN.Core::PREFIX.$message);
      }else {
        $message = $this->lang->translateString("error.ftname.not.exists");
        $player->sendMessage(TF::RED.Core::PREFIX.$message);
      }
    }
  }

  public function removeCommand(Player $player, $response): void {
    if (!FormApi::formCancelled($response)) {
      $level = $player->getLevel();
      $ft = $this->texterApi->getFtByLevel($level, $response[self::REMOVE_KEY_FTNAME]);
      if ($ft !== null) {
        $ft->sendToLevel($level, Text::SEND_TYPE_REMOVE);
        $this->core->getFtsDataManager()->removeTextByLevel($level, $ft);
        $message = $this->lang->translateString("command.txt.remove.success", [
          TF::clean($response[self::REMOVE_KEY_FTNAME])
        ]);
        $player->sendMessage(TF::GREEN.Core::PREFIX.$message);
      }else {
        $message = $this->lang->translateString("error.ftname.not.exists");
        $player->sendMessage(TF::RED.Core::PREFIX.$message);
      }
    }
  }
}
