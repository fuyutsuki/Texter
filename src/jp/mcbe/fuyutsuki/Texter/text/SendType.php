<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\text;

use pocketmine\utils\EnumTrait;

/**
 * @method static SendType ADD()
 * @method static SendType EDIT()
 * @method static SendType MOVE()
 * @method static SendType REMOVE()
 */
final class SendType {
	use EnumTrait;

	protected static function setup(): void {
		self::registerAll(
			new self("add"),
			new self("edit"),
			new self("move"),
			new self("remove"),
		);
	}
}