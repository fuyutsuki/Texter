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

namespace tokyo\pmmp\Texter\command;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use tokyo\pmmp\Texter\i18n\Lang;

class TxtAdmCommand extends Command {

  public function __construct() {
    $this->setPermission("texter.command.txtadm");
    $cl = Lang::fromConsole();
    $description = $cl->translateString("command.txtadm.description");
    $usage = $cl->translateString("command.txtadm.usage");
    parent::__construct("txtadm", $description, $usage);
  }

  public function execute(CommandSender $sender, string $commandLabel, array $args) {
    // TODO: Implement execute() method.
  }
}