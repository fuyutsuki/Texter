<?php

namespace Texter\commands;

# Pocketmine
use pocketmine\Player;
use pocketmine\command\{
  Command,
  CommandSender};
use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat as TF;

# Texter
use Texter\Main;
use Texter\language\Lang;
use Texter\text\FloatingText as FT;
/**
 * TxtAdmCommand
 */
class TxtAdmCommand extends Command{

  /** @var string $help */
  private $help = "";

  public function __construct(Main $main){
    $this->main = $main;
    $this->api = $main->getAPI();
    $this->lang = Lang::getInstance();
    parent::__construct("txtadm", $this->lang->transrateString("command.description.txtadm"), "/txtadm <ar | ur | info>", ["tadm"]);//登録
    //
    $this->setPermission("texter.command.txtadm");
    //
    $this->help  = $this->lang->transrateString("command.txt.usage")."\n";
    $this->help .= $this->lang->transrateString("command.txtadm.usage.allremove")."\n";
    $this->help .= $this->lang->transrateString("command.txtadm.usage.userremove")."\n";
    $this->help .= $this->lang->transrateString("command.txtadm.usage.info");
  }

  public function execute(CommandSender $sender, string $label, array $args){
    if (!$this->main->isEnabled()) return false;
    if (!$this->testPermission($sender)) return false;
    if (isset($args[0])) {
      $name = $sender->getName();
      $lev = $sender->level;
      $levn = $lev->getName();
      switch (strtolower($args[0])) { // subCommand
        case 'allremove':
        case 'ar':
          $fts = $this->api->getFts();
          $count = $this->api->getFtsCount();
          if ($fts === 0) {
            $message = $this->lang->transrateString("command.txtadm.notexists");
            $sender->sendMessage(TF::RED . Lang::PREFIX . $message);
          }else {
            foreach ($fts as $levFts) {
              foreach ($levFts as $ft) {
                $ft->remove();
              }
            }
            $message = $this->lang->transrateString("command.txtadm.allremove", ["{count}"], [$count]);
            $sender->sendMessage(TF::AQUA . Lang::PREFIX . $message);
          }
        break;

        case 'userremove':
        case 'ur':
          if (isset($args[1])) { // username
            $fts = $this->api->getFtsByName($args[1]);
            if (empty($fts)) {
              $message = $this->lang->transrateString("txt.user.doesn`t.exists");
              $sender->sendMessage(TF::RED . Lang::PREFIX . $message);
            }else {
              $message = $this->lang->transrateString("command.txtadm.userremove", ["{user}"], [$args[1]]);
              $sender->sendMessage(TF::AQUA . Lang::PREFIX . $message);
            }
          }else {
            $message = $this->lang->transrateString("command.txtadm.usage.ur");
            $sender->sendMessage(TF::AQUA . Lang::PREFIX . $message);
          }
        break;

        case 'info':
        case 'i':
          $crfts = $this->api->getCrftsCount();
          $fts = $this->api->getFtsCount();
          $message  = TF::AQUA . Lang::PREFIX . "\n";
          $message .= TF::AQUA . "crfts: " . TF::GOLD . $crfts . "\n";
          $message .= TF::AQUA . "fts: " . TF::GOLD . $fts . "\n";
          $message .= TF::GRAY . Main::NAME . " " . Main::VERSION . " - " . Main::CODENAME;
          $sender->sendMessage($message);
        break;

        case 'help':
        case 'h':
        case '?':
        default:
          $sender->sendMessage(TF::AQUA . Lang::PREFIX . $this->help);
        break;
      }
    }else {
      $sender->sendMessage(TF::AQUA . Lang::PREFIX . $this->help);
    }
    return true;
  }
}
