<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\command\sub;

use pocketmine\Player;

/**
 * Class TexterSubCommand
 * @package jp\mcbe\fuyutsuki\Texter\command\sub
 */
abstract class TexterSubCommand {

	abstract public function execute(Player $player);

}