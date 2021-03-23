<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\util;

/**
 * Trait Singleton
 * @package jp\mcbe\fuyutsuki\Texter\util
 */
trait Singleton {

	/** @var static|null $instance */
	protected static $instance;

	final public function __construct() {
		static::$instance = $this;
	}

	/**
	 * @return static|null
	 */
	final public static function getInstance(): ?self {
		return static::$instance ?? null;
	}

	final public static function removeInstance() {
		static::$instance = null;
	}

}