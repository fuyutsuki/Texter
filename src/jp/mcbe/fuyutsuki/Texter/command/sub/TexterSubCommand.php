<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\command\sub;

use pocketmine\player\Player;

abstract class TexterSubCommand {

	abstract public function execute(Player $player);

}