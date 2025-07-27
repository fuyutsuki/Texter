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

namespace jp\mcbe\fuyutsuki\Texter\libs\_6a768d6c7cff751f\dktapps\pmforms;

use jp\mcbe\fuyutsuki\Texter\libs\_6a768d6c7cff751f\dktapps\pmforms\element\CustomFormElement;
use jp\mcbe\fuyutsuki\Texter\libs\_6a768d6c7cff751f\dktapps\pmforms\element\Label;
use pocketmine\form\FormValidationException;
use pocketmine\player\Player;
use pocketmine\utils\AssumptionFailedError;
use pocketmine\utils\Utils;
use function array_fill;
use function array_values;
use function count;
use function gettype;
use function is_array;

/**
 * @phpstan-type OnSubmit \Closure(Player $player, CustomFormResponse $data) : void
 * @phpstan-type OnClose \Closure(Player $player) : void
 */
class CustomForm extends BaseForm{

	/** @var CustomFormElement[] */
	private $elements;
	/** @var CustomFormElement[] */
	private $elementMap = [];
	/**
	 * @var \Closure
	 * @phpstan-var OnSubmit
	 */
	private $onSubmit;
	/**
	 * @var \Closure|null
	 * @phpstan-var OnClose|null
	 */
	private $onClose = null;

	/**
	 * @param CustomFormElement[] $elements
	 * @param \Closure            $onSubmit signature `function(Player $player, CustomFormResponse $data)`
	 * @param \Closure|null       $onClose  signature `function(Player $player)`
	 *
	 * @phpstan-param OnSubmit     $onSubmit
	 * @phpstan-param OnClose|null $onClose
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct(string $title, array $elements, \Closure $onSubmit, ?\Closure $onClose = null){
		parent::__construct($title);
		$this->elements = array_values($elements);
		foreach($this->elements as $element){
			if(isset($this->elementMap[$element->getName()])){
				throw new \InvalidArgumentException("Multiple elements cannot have the same name, found \"" . $element->getName() . "\" more than once");
			}
			$this->elementMap[$element->getName()] = $element;
		}

		Utils::validateCallableSignature(function(Player $player, CustomFormResponse $response) : void{}, $onSubmit);
		$this->onSubmit = $onSubmit;
		if($onClose !== null){
			Utils::validateCallableSignature(function(Player $player) : void{}, $onClose);
			$this->onClose = $onClose;
		}
	}

	public function getElement(int $index) : ?CustomFormElement{
		return $this->elements[$index] ?? null;
	}

	public function getElementByName(string $name) : ?CustomFormElement{
		return $this->elementMap[$name] ?? null;
	}

	/**
	 * @return CustomFormElement[]
	 */
	public function getAllElements() : array{
		return $this->elements;
	}

	final public function handleResponse(Player $player, $data) : void{
		if($data === null){
			if($this->onClose !== null){
				($this->onClose)($player);
			}
		}elseif(is_array($data)){
			($this->onSubmit)($player, $this->buildResponseFromData($data));
		}else{
			throw new FormValidationException("Expected array or null, got " . gettype($data));
		}
	}

	/**
	 * @param mixed[] $data
	 * @throws FormValidationException
	 */
	public function buildResponseFromData(array $data) : CustomFormResponse{
		$actual = count($data);
		$expected = count($this->elements);
		if($actual > $expected){
			throw new FormValidationException("Too many result elements, expected $expected, got $actual");
		}elseif($actual < $expected){
			//In 1.21.70, the client doesn't send nulls for labels, so we need to polyfill them here to
			//maintain the old behaviour
			$noLabelsIndexMapping = [];
			foreach($this->elements as $index => $element){
				if(!($element instanceof Label)){
					$noLabelsIndexMapping[] = $index;
				}
			}
			$expectedWithoutLabels = count($noLabelsIndexMapping);
			if($actual !== $expectedWithoutLabels){
				throw new FormValidationException("Wrong number of result elements, expected either " .
					$expected .
					" (with label values, <1.21.70) or " .
					$expectedWithoutLabels .
					" (without label values, >=1.21.70), got " .
					$actual
				);
			}

			//polyfill the missing nulls
			$mappedData = array_fill(0, $expected, null);
			foreach($data as $givenIndex => $value){
				$internalIndex = $noLabelsIndexMapping[$givenIndex] ?? null;
				if($internalIndex === null){
					throw new FormValidationException("Can't map given offset $givenIndex to an internal element offset (while correcting for labels)");
				}
				//set the appropriate values according to the given index
				//this could (?) still leave unexpected nulls, but the validation below will catch that
				$mappedData[$internalIndex] = $value;
			}
			if(count($mappedData) !== $expected){
				throw new AssumptionFailedError("This should always match");
			}
			$data = $mappedData;
		}

		$values = [];

		foreach($data as $index => $value){
			if(!isset($this->elements[$index])){
				throw new FormValidationException("Element at offset $index does not exist");
			}
			$element = $this->elements[$index];
			try{
				$element->validateValue($value);
			}catch(FormValidationException $e){
				throw new FormValidationException("Validation failed for element \"" . $element->getName() . "\": " . $e->getMessage(), 0, $e);
			}
			$values[$element->getName()] = $value;
		}

		return new CustomFormResponse($values);
	}

	protected function getType() : string{
		return "custom_form";
	}

	protected function serializeFormData() : array{
		return [
			"content" => $this->elements
		];
	}
}