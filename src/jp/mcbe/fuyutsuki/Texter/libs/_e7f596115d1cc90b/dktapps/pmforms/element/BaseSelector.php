<?php

/*
 * This file is part of pmforms.
 * Copyright (C) 2018-2025 Dylan K. Taylor <https://github.com/dktapps-pm-pl/pmforms>
 *
 * pmforms is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\libs\_e7f596115d1cc90b\dktapps\pmforms\element;

use pocketmine\form\FormValidationException;
use function array_values;
use function gettype;
use function is_int;

abstract class BaseSelector extends CustomFormElement{
	/** @var int */
	protected $defaultOptionIndex;
	/** @var string[] */
	protected $options;

	/**
	 * @param string[] $options
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct(string $name, string $text, array $options, int $defaultOptionIndex = 0){
		parent::__construct($name, $text);
		$this->options = array_values($options);

		if(!isset($this->options[$defaultOptionIndex])){
			throw new \InvalidArgumentException("No option at index $defaultOptionIndex, cannot set as default");
		}
		$this->defaultOptionIndex = $defaultOptionIndex;
	}

	public function validateValue($value) : void{
		if(!is_int($value)){
			throw new FormValidationException("Expected int, got " . gettype($value));
		}
		if(!isset($this->options[$value])){
			throw new FormValidationException("Option $value does not exist");
		}
	}

	/**
	 * Returns the text of the option at the specified index, or null if it doesn't exist.
	 */
	public function getOption(int $index) : ?string{
		return $this->options[$index] ?? null;
	}

	public function getDefaultOptionIndex() : int{
		return $this->defaultOptionIndex;
	}

	public function getDefaultOption() : string{
		return $this->options[$this->defaultOptionIndex];
	}

	/**
	 * @return string[]
	 */
	public function getOptions() : array{
		return $this->options;
	}
}