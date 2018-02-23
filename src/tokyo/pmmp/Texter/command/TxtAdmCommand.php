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
  utils\TextFormat as TF,
  utils\Utils
};

// texter
use tokyo\pmmp\Texter\{
  Core,
  TexterApi,
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
 * TxtAdmCommandClass
 */
class TxtAdmCommand extends Command {

  /** @var string */
  private const COMMAND = "txtadm";
  private const PERMISSION = "texter.command.txtadm";

  /** @var int */
  private const SESSION_ID = 0;
  private const SESSION_DATA = 1;
  private const SESSION_AR = 0;
  private const SESSION_UR = 1;
  private const SESSION_LR = 2;

  /** @var ?Core */
  private $core = null;
  /** @var ?TexterApi */
  private $texterApi = null;
  /** @var ?BaseLang */
  private $lang = null;
  /** @var array */
  private $session = [];

  public function __construct(Core $core) {
    $this->core = $core;
    $this->texterApi = $core->getTexterApi();
    $this->lang = $core->getLang();
    //
    $description = $this->lang->translateString("command.txtadm.description");
    $usage = $this->lang->translateString("command.txtadm.usage");
    parent::__construct(self::COMMAND, $description, $usage);
    $this->setPermission(self::PERMISSION);
  }

  public function execute(CommandSender $sender, string $label, array $args) {
    if (!$this->core->isEnabled()) return false;
    if (!$this->testPermission($sender)) return false;
    if ($sender instanceof Player) {
      $message = $this->lang->translateString("error.player");
      $sender->sendMessage(TF::RED.Core::PREFIX.$message);
      //TODO: ver2.3.1 $this->sendList($sender);
    }else {
      if (isset($args[0])) {
        $logger = $this->core->getLogger();
        $console = Utils::getMachineUniqueId()->toString();
        switch (strtolower($args[0])) {
          case 'allremove':
          case 'ar':
            if (array_key_exists($console, $this->session)) {
              if ($this->session[$console][self::SESSION_ID] === self::SESSION_AR) {
                $fts = $this->texterApi->getFts();
                foreach ($fts as $levelName => $levFts) {
                  $this->texterApi->removeFtsByLevelName($levelName);
                }
                $message = $this->lang->translateString("command.txtadm.ar.success");
                $logger->info(TF::GREEN.$message);
                unset($this->session[$console]);
              }
            }
            $message = $this->lang->translateString("command.txtadm.ar.warning.console");
            $logger->warning($message);
            $this->session[$console][self::SESSION_ID] = self::SESSION_AR;
          break;

          case 'userremove':
          case 'ur':
            if (array_key_exists($console, $this->session)) {
              if ($this->session[$console][self::SESSION_ID] === self::SESSION_UR) {
                $specified = $this->session[$console][self::SESSION_DATA];
                $fts = $this->texterApi->getFts();
                foreach ($fts as $levelName => $levFts) {
                  foreach ($levFts as $name => $ft) {
                    if ($specified === $ft->getOwner()) {
                      $this->texterApi->removeFtByLevelName($levelName, $name);
                    }
                  }
                }
                $message = $this->lang->translateString("command.txtadm.ur.success", [
                  $this->session[$console][self::SESSION_DATA]
                ]);
                $logger->info(TF::GREEN.$message);
                unset($this->session[$console]);
                break;
              }
            }
            if (isset($args[1])) {
              $message = $this->lang->translateString("command.txtadm.ur.warning.console", [
                $args[1]
              ]);
              $logger->warning($message);
              $this->session[$console] = [
                self::SESSION_ID => self::SESSION_UR,
                self::SESSION_DATA => strtolower($args[1])
              ];
            }else {
              $message = $this->lang->translateString("command.txtadm.ur.usage");
              $logger->info($message);
            }
          break;

          case 'levelremove':
          case 'lr':
            if (array_key_exists($console, $this->session)) {
              if ($this->session[$console][self::SESSION_ID] === self::SESSION_LR) {
                $levelName = $this->session[$console][self::SESSION_DATA];
                $this->texterApi->removeFtsByLevelName($levelName);
                $message = $this->lang->translateString("command.txtadm.lr.success", [
                  $this->session[$console][self::SESSION_DATA]
                ]);
                $logger->info(TF::GREEN.$message);
                unset($this->session[$console]);
                break;
              }
            }
            if (isset($args[1])) {
              $message = $this->lang->translateString("command.txtadm.lr.warning.console", [
                $args[1]
              ]);
              $logger->warning($message);
              $this->session[$console] = [
                self::SESSION_ID => self::SESSION_LR,
                self::SESSION_DATA => $args[1]
              ];
            }else {
              $message = $this->lang->translateString("command.txtadm.lr.usage");
              $logger->info($message);
            }
          break;

          case 'info':
          case 'i':
            $message = $this->lang->translateString("command.txtadm.i", [
              $this->core->getDescription()->getVersion(),
              Core::CODENAME
            ]);
            $logger->info(TF::AQUA.$message);
          break;

          default:
            $message = $this->lang->translateString("command.txtadm.usage.console");
            $this->core->getLogger()->info($message);
          break;
        }
      }else {
        $message = $this->lang->translateString("command.txtadm.usage.console");
        $this->core->getLogger()->info($message);
      }
    }
  }
}
