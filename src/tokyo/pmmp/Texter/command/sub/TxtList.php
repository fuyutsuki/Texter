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

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use tokyo\pmmp\Texter\Core;
use tokyo\pmmp\Texter\text\FloatingText;
use tokyo\pmmp\Texter\TexterApi;

/**
 * Class TxtList
 * @package tokyo\pmmp\Texter\command\sub
 */
class TxtList extends TexterSubCommand {

  /** @var int response key */
  public const EDIT = 0;
  public const MOVE = 1;
  public const REMOVE = 2;

  public function execute(string $default = ""): void {
    $pluginDescription = Core::get()->getDescription();
    $description = $this->lang->translateString("form.list.description.1");

    $fts = TexterApi::getFtsByLevel($this->player->getLevel());
    $search = [];
    foreach ($fts as $name => $ft) {
      /** @var FloatingText $ft */
      if ($this->player->distance($ft) <= 10 && $ft->isOwner($this->player)) {
        $search[] = $ft;
      }
    }

    $list1 = new SimpleForm(function (Player $player, ?int $key) use ($pluginDescription, $search) {
      if ($key !== null) {
        $target = $search[$key];
        $description = $this->lang->translateString("form.list.description.2", [
          $target->getName()
        ]);
        $list2 = new SimpleForm(function (Player $player, ?int $key) use ($target) {
          if ($key !== null) {
            switch ($key) {
              case self::EDIT:
                new TxtEdit($player, $target->getName());
                break;
              case self::MOVE:
                new TxtMove($player, $target->getName());
                break;
              case self::REMOVE:
                new TxtRemove($player, $target->getName());
                break;
            }
          }
        });

        $list2->setTitle("[{$pluginDescription->getPrefix()}] /txt list");
        $list2->setContent($description);
        $list2->addButton("edit");
        $list2->addButton("move");
        $list2->addButton("remove");
        $player->sendForm($list2);
      }
    });

    $list1->setTitle("[{$pluginDescription->getPrefix()}] /txt list");
    $list1->setContent($description);
    foreach ($search as $ft) $list1->addButton($ft->getName());
    $this->player->sendForm($list1);
  }
}