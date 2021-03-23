<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\util\dependencies;

use jp\mcbe\fuyutsuki\Texter\util\Imconstructable;

/**
 * Class Dependencies
 * @package jp\mcbe\fuyutsuki\Texter\util\dependencies
 */
final class Dependencies {

	use Imconstructable;

	public const SOFT_MINEFLOW = "Mineflow";

	public const PLUGIN_NAMESPACE = "\\jp\\mcbe\\fuyutsuki\\Texter";

	public const PACKAGED_LIBRARY_NAMESPACE = self::PLUGIN_NAMESPACE . "\\libs";
	public const LIB_FORM_API = "\\jojoe77777\\FormAPI\\FormAPI";

}