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

namespace jp\mcbe\fuyutsuki\Texter\libs\_6a768d6c7cff751f\dktapps\pmforms\element;

use pocketmine\form\FormValidationException;
use function gettype;
use function is_float;
use function is_int;

class Slider extends CustomFormElement{

	/** @var float */
	private $min;
	/** @var float */
	private $max;
	/** @var float */
	private $step;
	/** @var float */
	private $default;

	public function __construct(string $name, string $text, float $min, float $max, float $step = 1.0, ?float $default = null){
		parent::__construct($name, $text);

		if($this->min > $this->max){
			throw new \InvalidArgumentException("Slider min value should be less than max value");
		}
		$this->min = $min;
		$this->max = $max;

		if($default !== null){
			if($default > $this->max || $default < $this->min){
				throw new \InvalidArgumentException("Default must be in range $this->min ... $this->max");
			}
			$this->default = $default;
		}else{
			$this->default = $this->min;
		}

		if($step <= 0){
			throw new \InvalidArgumentException("Step must be greater than zero");
		}
		$this->step = $step;
	}

	public function getType() : string{
		return "slider";
	}

	public function validateValue($value) : void{
		if(!is_float($value) && !is_int($value)){
			throw new FormValidationException("Expected float, got " . gettype($value));
		}
		if($value < $this->min || $value > $this->max){
			throw new FormValidationException("Value $value is out of bounds (min $this->min, max $this->max)");
		}
	}

	public function getMin() : float{
		return $this->min;
	}

	public function getMax() : float{
		return $this->max;
	}

	public function getStep() : float{
		return $this->step;
	}

	public function getDefault() : float{
		return $this->default;
	}

	protected function serializeElementData() : array{
		return [
			"min" => $this->min,
			"max" => $this->max,
			"default" => $this->default,
			"step" => $this->step
		];
	}
}