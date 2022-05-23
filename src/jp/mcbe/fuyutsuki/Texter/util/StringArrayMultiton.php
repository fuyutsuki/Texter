<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\util;

trait StringArrayMultiton {

	/** @var static[] */
	protected static array $instances = [];

	final public function __construct(string $key) {
		static::$instances[$key] = $this;
	}

	final public static function getInstance(string $key): ?static {
		return static::$instances[$key] ?? null;
	}

	final public static function removeInstance(string $key): void {
		unset(static::$instances[$key]);
	}

}