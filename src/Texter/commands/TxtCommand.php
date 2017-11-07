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
 * TxtCommand
 */
class TxtCommand extends Command{

  /** @var string $help */
  private $help = "";

  public function __construct(Main $main){
    $this->main = $main;
    $this->api = $main->getAPI();
    $this->lang = Lang::getInstance();
    parent::__construct("txt", $this->lang->transrateString("command.description.txt"), "/txt <add | remove | update | help>");//登録
    //
    $this->setPermission("texter.command.txt");
    //
    $this->help  = $this->lang->transrateString("command.txt.usage")."\n";
    $this->help .= $this->lang->transrateString("command.txt.usage.add")."\n";
    $this->help .= $this->lang->transrateString("command.txt.usage.remove")."\n";
    $this->help .= $this->lang->transrateString("command.txt.usage.update")."\n";
    $this->help .= $this->lang->transrateString("command.txt.usage.indent");
    //
    $this->lim   = $this->main->getCharaLimit();
    $this->feed  = $this->main->getFeedLimit();
    $this->world = $this->main->getWorldLimit();
  }

  public function execute(CommandSender $sender, string $label, array $args){
    if (!$this->main->isEnabled()) return false;
    if (!$this->testPermission($sender)) return false;
    if ($sender instanceof Player) {
      $lev = $sender->level;
      $levn = $lev->getName();
      if (array_key_exists($levn, $this->world)) {
        $message = $this->lang->transrateString("command.txt.world");
        $sender->sendMessage(TF::RED . Lang::PREFIX . $message);
      }else {
        if (isset($args[0])) {
          switch (strtolower($args[0])) { // subCommand
            case 'add':
            case 'a':
              if (!empty($args[1])) { // Title
                $title = str_replace("#", "\n", $args[1]);
                if (!empty($args[2])) { // Text
                  $texts = array_slice($args, 2);
                  $text = str_replace("#", "\n", implode(" ", $texts));
                }else {
                  $text = "";
                }
                if (!$sender->isOp()) {
                  $message = $this->checkTextLimit($title.$text);
                  if ($message !== true) {
                    $sender->sendMessage(TF::RED . Lang::PREFIX . $message);
                    return true;
                  }
                  $title = TF::clean($title);
                  $text = TF::clean($text);
                }
                $x = sprintf('%0.1f', $sender->x);
                $y = sprintf('%0.1f', $sender->y + 1);
                $z = sprintf('%0.1f', $sender->z);
                $name = $sender->getName();
                $ft = new FT($lev, $x, $y, $z, $title, $text, $name);
                if ($ft->failed) {
                  $message = $this->lang->transrateString("txt.exists");
                  $sender->sendMessage(TF::RED . Lang::PREFIX . $message);
                }else {
                  $message = $this->lang->transrateString("command.txt.set");
                  $sender->sendMessage(TF::AQUA . Lang::PREFIX . $message);
                }
              }else {
                $message = $this->lang->transrateString("command.txt.usage.add");
                $sender->sendMessage(TF::AQUA . Lang::PREFIX . $message);
              }
            break;

            case 'remove':
            case 'r':
              if (isset($args[1])) { // entityId
                $eid = (int)$args[1];
                $ft = $this->api->getFt($levn, $eid);
                if ($ft !== null) {
                  if ($ft->canEditFt($sender)) {
                    $ft->remove();
                    $message = $this->lang->transrateString("command.txt.remove");
                    $sender->sendMessage(TF::AQUA . Lang::PREFIX . $message);
                  }else {
                    $message = $this->lang->transrateString("command.txt.permission");
                    $sender->sendMessage(TF::RED . Lang::PREFIX . $message);
                  }
                }else {
                  $message = $this->lang->transrateString("txt.doesn`t.exists");
                  $sender->sendMessage(TF::RED . Lang::PREFIX . $message);
                }
              }else {
                $message = $this->lang->transrateString("command.txt.usage.remove");
                $sender->sendMessage(TF::AQUA . Lang::PREFIX . $message);
              }
            break;

            case 'update':
            case 'u':
              if (isset($args[1]) && isset($args[2]) && !empty($args[3])) {
                // eid && title" or "text" && contents
                $eid = (int)$args[1];
                $ft = $this->api->getFt($levn, $eid);
                if ($ft !== null) {
                  if ($ft->canEditFt($sender)) {
                    switch (strtolower($args[2])) {
                      case 'title':
                        $title = str_replace("#", "\n", $args[3]);
                        if (!$sender->isOp()) {
                          $message = $this->checkTextLimit($title.$ft->text);
                          if ($message !== true) {
                            $sender->sendMessage(TF::RED . Lang::PREFIX . $message);
                            return true;
                          }
                          $title = TF::clean($title);
                        }
                        $ft->setTitle($args[3]);
                        $message = $this->lang->transrateString("command.txt.updated");
                        $sender->sendMessage(TF::AQUA . Lang::PREFIX . $message);
                      break;

                      case 'text':
                        if (!empty($args[3])) {
                          $texts = array_slice($args, 3);
                          $text = str_replace("#", "\n", implode(" ", $texts));
                        }else {
                          $text = "";
                        }
                        if (!$sender->isOp()) {
                          $message = $this->checkTextLimit($ft->title.$text);
                          if ($message !== true) {
                            $sender->sendMessage(TF::RED . Lang::PREFIX . $message);
                            return true;
                          }
                          $text = TF::clean($text);
                        }
                        $ft->setText($text);
                        $message = $this->lang->transrateString("command.txt.updated");
                        $sender->sendMessage(TF::AQUA . Lang::PREFIX . $message);
                      break;

                      default:
                        $message = $this->lang->transrateString("command.txt.usage.update");
                        $sender->sendMessage(TF::AQUA . Lang::PREFIX . $message);
                      break;
                    }
                  }else {
                    $message = $this->lang->transrateString("command.txt.permission");
                    $sender->sendMessage(TF::RED . Lang::PREFIX . $message);
                  }
                }else {
                  $message = $this->lang->transrateString("txt.doesn`t.exists");
                  $sender->sendMessage(TF::RED . Lang::PREFIX . $message);
                }
              }else {
                $message = $this->lang->transrateString("command.txt.usage.update");
                $sender->sendMessage(TF::AQUA . Lang::PREFIX . $message);
              }
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
      }
    }else {
      $message = $this->lang->transrateString("command.console");
      $this->main->getLogger()->info(TF::RED . $message);
    }
    return true;
  }

  /**
   * テキストのルールに違反していないか確かめます
   * @param  string      $text
   * @return string|bool
   */
  private function checkTextLimit(string $text){
    if ($this->lim > -1 && mb_strlen($text, "UTF-8") > $this->lim) {
      $message = $this->lang->transrateString("command.txt.limit", ["{limit}"], [$this->lim]);
      return $message;
    }else {
      if ($this->feed > -1 && mb_substr_count($text, "\n" , "UTF-8") > $this->feed) {
        $message = $this->lang->transrateString("command.txt.feed", ["{feed}"], [$this->feed]);
        return $message;
      }else {
        return true;
      }
    }
  }
}
