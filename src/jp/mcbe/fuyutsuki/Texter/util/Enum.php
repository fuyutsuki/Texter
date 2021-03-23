<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\util;

/**
 * Trait Enum
 * @package jp\mcbe\fuyutsuki\Texter\util
 */
trait Enum {

	/** @var int */
	private $value;

	final public function __construct(int $value) {
		$this->value = $value;
	}

	final public function value(): int {
		return $this->value;
	}

}