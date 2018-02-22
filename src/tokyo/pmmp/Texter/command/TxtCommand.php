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

  private const COMMAND = "txt";
  private const PERMISSION = "texter.command.txt";

  private const ADD_KEY_FTNAME = 0;
  private const ADD_KEY_TITLE = 2;
  private const ADD_KEY_TEXT = 3;

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
              $labelTips = $this->lang->translateString("command.txt.usage.indent");
              $inputFtName = $this->lang->translateString("form.add.input.ftname");
              $inputTitle = $this->lang->translateString("form.add.input.title");
              $inputText = $this->lang->translateString("form.add.input.text");
              $custom->setTitle("[Texter] /txt add")
              ->addElement(new Input($inputFtName, $inputFtName))
              ->addElement(new Label($labelTips))
              ->addElement(new Input($inputTitle, $inputTitle))
              ->addElement(new Input($inputText, $inputText))
              ->sendToPlayer($sender);
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

  public function addCommand(Player $player, $response): void {
    if (!FormApi::formCancelled($response)) {
      $exists = $this->texterApi->getFtByLevel($player->getLevel(), $response[self::ADD_KEY_FTNAME]);
      if ($exists === null) {
        $ft = new FT(
          $response[self::ADD_KEY_FTNAME],
          $player->getPosition(),
          $response[self::ADD_KEY_TITLE],
          $response[self::ADD_KEY_TEXT],
          $player->getName()
        );
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
      var_dump($response);
    }
  }

  public function moveCommand(Player $player, $response): void {
    if (!FormApi::formCancelled($response)) {
      var_dump($response);
    }
  }

  public function removeCommand(Player $player, $response): void {
    if (!FormApi::formCancelled($response)) {
      var_dump($response);
    }
  }
}
