<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\text;

trait Nameable {

	public function __construct(
		protected string $name
	) {
	}

	public function name(): string {
		return $this->name;
	}

}