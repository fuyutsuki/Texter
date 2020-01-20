<?php

/**
 * // English
 *
 * Texter, the display FloatingTextPerticle plugin for PocketMine-MP
 * Copyright (c) 2019-2020 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * This software is distributed under "NCSA license".
 * You should have received a copy of the NCSA license
 * along with this program.  If not, see
 * < https://opensource.org/licenses/NCSA >.
 *
 * ---------------------------------------------------------------------
 * // 日本語
 *
 * TexterはPocketMine-MP向けのFloatingTextPerticleを表示するプラグインです
 * Copyright (c) 2019-2020 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * このソフトウェアは"NCSAライセンス"下で配布されています。
 * あなたはこのプログラムと共にNCSAライセンスのコピーを受け取ったはずです。
 * 受け取っていない場合、下記のURLからご覧ください。
 * < https://opensource.org/licenses/NCSA >
 */

declare(strict_types = 1);

namespace tokyo\pmmp\Texter\command\sub;

use jojoe77777\FormAPI\CustomForm;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use tokyo\pmmp\Texter\Core;
use tokyo\pmmp\Texter\data\ConfigData;
use tokyo\pmmp\Texter\text\FloatingText;
use tokyo\pmmp\Texter\TexterApi;

/**
 * Class TxtAdd
 * @package tokyo\pmmp\Texter\command\sub
 */
class TxtAdd extends TexterSubCommand {

  /** @var int response key */
  public const NAME = 1;
  public const TITLE = 3;
  public const TEXT = 4;

  public function execute(string $default = ""): void {
    $pluginDescription = Core::get()->getDescription();
    $description = $this->lang->translateString("form.add.description");
    $ftName = $this->lang->translateString("form.ftname.unique");
    $indent = $this->lang->translateString("command.txt.usage.indent");
    $title = $this->lang->translateString("form.title");
    $text = $this->lang->translateString("form.text");

    $custom = new CustomForm(function (Player $player, ?array $response) use ($pluginDescription) {
      if ($response !== null) {
        $level = $player->getLevel();
        if (!empty($response[self::NAME])) {
          $exists = TexterApi::getFtByLevel($level, $response[self::NAME]);
          if ($exists === null) {
            $title = $player->isOp() ? $response[self::TITLE] : TextFormat::clean($response[self::TITLE]);
            $text = $player->isOp() ? $response[self::TEXT] : TextFormat::clean($response[self::TEXT]);
            $ft = new FloatingText($response[self::NAME], Position::fromObject($player->add(0, 1, 0), $level), $title, $text, $player->getName());
            $cd = ConfigData::make();
            if ($cd->checkCharLimit($ft->getTextsForCheck(FloatingText::CHECK_CHAR))) {
              if ($cd->checkFeedLimit($ft->getTextsForCheck(FloatingText::CHECK_FEED))) {
                $ft->sendToLevel($level);
                TexterApi::registerText($ft);
                $message = $this->lang->translateString("command.txt.add.success", [
                  TextFormat::clean($response[self::NAME])
                ]);
                $player->sendMessage(TextFormat::GREEN . "[{$pluginDescription->getPrefix()}] $message");
              }else {
                $message = $this->lang->translateString("error.config.limit.feed", [
                  $cd->getFeedLimit()
                ]);
                $player->sendMessage(TextFormat::RED . "[{$pluginDescription->getPrefix()}] $message");
              }
            }else {
              $message = $this->lang->translateString("error.config.limit.char", [
                $cd->getCharLimit()
              ]);
              $player->sendMessage(TextFormat::RED . "[{$pluginDescription->getPrefix()}] $message");
            }
          }else {
            $message = $this->lang->translateString("error.ftname.exists", [
              $response[self::NAME]
            ]);
            $player->sendMessage(TextFormat::RED . "[{$pluginDescription->getPrefix()}] $message");
          }
        }else {
          $message = $this->lang->translateString("error.ftname.not.specified");
          $player->sendMessage(TextFormat::RED . "[{$pluginDescription->getPrefix()}] $message");
        }
      }
    });

    $custom->setTitle("[{$pluginDescription->getPrefix()}] /txt add");
    $custom->addLabel($description);
    $custom->addInput($ftName, $ftName);
    $custom->addLabel($indent);
    $custom->addInput($title, $title);
    $custom->addInput($text, $text);
    $this->player->sendForm($custom);
  }
}