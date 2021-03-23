<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\util;

/**
 * Trait StringArrayMultiton
 * @package jp\mcbe\fuyutsuki\Texter\util
 */
trait StringArrayMultiton {

	/** @var static[] */
	protected static $instances = [];

	final public function __construct(string $key) {
		static::$instances[$key] = $this;
	}

	/**
	 * @param string $key
	 * @return static|null
	 */
	final public static function getInstance(string $key): ?self {
		return static::$instances[$key] ?? null;
	}

	final public static function removeInstance(string $key) {
		unset(static::$instances[$key]);
	}

}