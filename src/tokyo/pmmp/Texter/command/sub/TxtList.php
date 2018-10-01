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
use tokyo\pmmp\libform\element\Button;
use tokyo\pmmp\libform\FormApi;
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
    $description = $this->lang->translateString("form.list.description.1");

    $fts = TexterApi::getFtsByLevel($this->player->getLevel());
    $search = [];
    foreach ($fts as $name => $ft) {
      /** @var FloatingText $ft */
      if ($this->player->distance($ft) <= 10 && $ft->isOwner($this->player)) {
        $search[] = $ft;
      }
    }

    $list1 = FormApi::makeListForm(function (Player $player, ?int $key) use ($search) {
      if (!FormApi::formCancelled($key)) {
        $target = $search[$key];
        $description = $this->lang->translateString("form.list.description.2", [
          $target->getName()
        ]);
        $list2 = FormApi::makeListForm(function (Player $player, ?int $key) use ($target) {
          if (!FormApi::formCancelled($key)) {
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

        $list2
          ->setContent($description)
          ->addButton(new Button("edit"))
          ->addButton(new Button("move"))
          ->addButton(new Button("remove"))
          ->setTitle(Core::PREFIX . "/txt list")
          ->sendToPlayer($player);
      }
    });

    $list1
      ->setContent($description)
      ->setTitle(Core::PREFIX . "/txt list");
    foreach ($search as $ft) $list1->addButton(new Button($ft->getName()));
    $list1->sendToPlayer($this->player);
  }
}