<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\util;

trait Singleton {

	/** @var static|null $instance */
	protected static $instance;

	final public function __construct() {
		static::$instance = $this;
	}

	final public static function getInstance(): ?static {
		return static::$instance ?? null;
	}

	final public static function removeInstance(): void {
		static::$instance = null;
	}

}