<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\util\dependencies;

use aieuo\mineflow\variable\VariableHelper;
use jp\mcbe\fuyutsuki\Texter\util\Imconstructable;

final class Mineflow {

	use Imconstructable;

	private static bool $isAvailable = false;
	private static VariableHelper $variableHelper;

	public static function isAvailable(): bool {
		return self::$isAvailable;
	}

	public static function setAvailable(bool $value = true): void {
		self::$isAvailable = $value;
	}

	public static function variableHelper(): VariableHelper {
		return self::$variableHelper;
	}

	public static function setVariableHelper(VariableHelper $helper): void {
		self::$variableHelper = $helper;
	}

}