<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\text;

use jp\mcbe\fuyutsuki\Texter\util\Enum;

/**
 * Class SendType
 * @package jp\mcbe\fuyutsuki\Texter\text
 */
final class SendType {
	use Enum;

	public const ADD = 0;
	public const EDIT = 1;
	public const MOVE = 2;
	public const REMOVE = 3;

}