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

use pocketmine\Player;
use pocketmine\utils\TextFormat;
use tokyo\pmmp\libform\element\Dropdown;
use tokyo\pmmp\libform\element\Input;
use tokyo\pmmp\libform\element\Label;
use tokyo\pmmp\libform\FormApi;
use tokyo\pmmp\Texter\Core;
use tokyo\pmmp\Texter\data\ConfigData;
use tokyo\pmmp\Texter\TexterApi;

/**
 * Class TxtEdit
 * @package tokyo\pmmp\Texter\command\sub
 */
class TxtEdit extends TexterSubCommand {

  /** @var int response key */
  public const FT_NAME = 1;
  public const TYPE = 2;
  public const TITLE = 0;
  public const TEXT = 1;
  public const CONTENT = 4;

  public function execute(string $default = ""): void {
    $description = $this->lang->translateString("form.edit.description");
    $ftName = $this->lang->translateString("form.ftname");
    $type = $this->lang->translateString("form.edit.type");
    $title = $this->lang->translateString("form.title");
    $text = $this->lang->translateString("form.text");
    $tips = $this->lang->translateString("command.txt.usage.indent");
    $content = $this->lang->translateString("form.edit.content");

    $custom = FormApi::makeCustomForm(function (Player $player, ?array $response) {
      if (!FormApi::formCancelled($response)) {
        $level = $player->getLevel();
        if (!empty($response[self::FT_NAME])) {
          $ft = TexterApi::getFtByLevel($level, $response[self::FT_NAME]);
          if ($ft !== null) {
            $cd = ConfigData::make();
            switch ($response[self::TYPE]) {
              case self::TITLE:
                if ($cd->checkCharLimit(TextFormat::clean($response[self::TITLE].$ft->getText()))) {
                  // TODO
                }
                break;
              case self::TEXT:
                break;
            }
          }else {
            // なかったら
          }
        }else {
          // 文字列からなら
        }
      }
    });

    $custom
      ->addElement(new Label($description))
      ->addElement(new Input($ftName, $ftName, $default))
      ->addElement(new Dropdown($type, [$title, $text]))
      ->addElement(new Label($tips))
      ->addElement(new Input($content, $content))
      ->setTitle(Core::PREFIX . "/txt edit")
      ->sendToPlayer($this->player);
  }
}