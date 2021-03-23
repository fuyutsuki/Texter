<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\util\dependencies;

use aieuo\mineflow\variable\VariableHelper;
use jp\mcbe\fuyutsuki\Texter\util\Imconstructable;

/**
 * Class Mineflow
 * @package jp\mcbe\fuyutsuki\Texter\util\dependencies
 */
final class Mineflow {

	use Imconstructable;

	/** @var bool */
	private static $isAvailable = false;
	/** @var VariableHelper */
	private static $variableHelper;

	public static function isAvailable(): bool {
		return self::$isAvailable;
	}

	public static function setAvailable(bool $value = true) {
		self::$isAvailable = $value;
	}

	public static function variableHelper(): VariableHelper {
		return self::$variableHelper;
	}

	public static function setVariableHelper(VariableHelper $helper) {
		self::$variableHelper = $helper;
	}

}