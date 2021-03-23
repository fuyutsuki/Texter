<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\text;

/**
 * Trait Nameable
 * @package jp\mcbe\fuyutsuki\Texter\text
 */
trait Nameable {

	/** @var string */
	protected $name;

	public function __construct(string $name) {
		$this->name = $name;
	}

	public function name(): string {
		return $this->name;
	}

}