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

namespace tokyo\pmmp\Texter\command\sub;

use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use tokyo\pmmp\libform\element\Input;
use tokyo\pmmp\libform\element\Label;
use tokyo\pmmp\libform\FormApi;
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
    $ftName = $this->lang->translateString("form.ftname.unique");
    $title = $this->lang->translateString("form.title");
    $text = $this->lang->translateString("form.text");

    $custom = FormApi::makeCustomForm(function (Player $player, ?array $response) {
      if (!FormApi::formCancelled($response)) {
        $level = $player->getLevel();
        if (!empty($response[self::NAME])) {
          $exists = TexterApi::getFtByLevel($level, $response[self::NAME]);
          if ($exists === null) {
            $title = $player->isOp() ? $response[self::TITLE] : TextFormat::clean($response[self::TITLE]);
            $text = $player->isOp() ? $response[self::TEXT] : TextFormat::clean($response[self::TEXT]);
            $ft = new FloatingText($response[self::NAME], Position::fromObject($player->add(0, 1, 0), $level), $title, $text, $player->getName());
            $cd = ConfigData::make();
            if ($cd->checkCharLimit($ft->getTextsForCheck())) {
              if ($cd->checkFeedLimit($ft->getTextsForCheck())) {
                $ft->sendToLevel($level);
                TexterApi::registerText($ft);
                $message = $this->lang->translateString("command.txt.add.success", [
                  TextFormat::clean($response[self::NAME])
                ]);
              }else {
                $message = $this->lang->translateString("error.config.limit.feed", [
                  $cd->getFeedLimit()
                ]);
              }
            }else {
              $message = $this->lang->translateString("error.config.limit.char", [
                $cd->getCharLimit()
              ]);
            }
          }else {
            $message = $this->lang->translateString("error.ftname.exists", [
              $response[self::NAME]
            ]);
          }
        }else {
          $message = $this->lang->translateString("error.ftname.not.specified");
        }
        if (isset($message))
          $player->sendMessage(Core::PREFIX . $message);
      }
    });

    $custom
      ->addElement(new Label($this->lang->translateString("form.add.description")))
      ->addElement(new Input($ftName, $ftName))
      ->addElement(new Label($this->lang->translateString("command.txt.usage.indent")))
      ->addElement(new Input($title, $title))
      ->addElement(new Input($text, $text))
      ->setTitle(Core::PREFIX . "/txt add")
      ->sendToPlayer($this->player);
  }
}