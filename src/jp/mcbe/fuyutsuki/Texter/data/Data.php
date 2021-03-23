<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\data;

use jp\mcbe\fuyutsuki\Texter\util\Imconstructable;

/**
 * Class Data
 * @package jp\mcbe\fuyutsuki\Texter\data
 */
final class Data {

	use Imconstructable;

	public const KEY_OLD_X = "Xvec";
	public const KEY_OLD_Y = "Yvec";
	public const KEY_OLD_Z = "Zvec";
	public const KEY_OLD_TITLE = "TITLE";
	public const KEY_OLD_TEXT = "TEXT";

	public const KEY_X = "x";
	public const KEY_Y = "y";
	public const KEY_Z = "z";
	public const KEY_TEXTS = "texts";
	public const KEY_SPACING = "spacing";

	public const JSON_OPTIONS = JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE;

}