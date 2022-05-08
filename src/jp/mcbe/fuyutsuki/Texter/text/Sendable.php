<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\text;

use pocketmine\{
	level\Level,
	Player};

interface Sendable {

	public function sendToPlayer(Player $player, SendType $type);

	public function sendToPlayers(array $players, SendType $type);

	public function sendToLevel(Level $level, SendType $type);

}