<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\libs\_465acad253416138\dktapps\pmforms;

use jp\mcbe\fuyutsuki\Texter\libs\_465acad253416138\dktapps\pmforms\element\CustomFormElement;
use pocketmine\form\FormValidationException;
use pocketmine\player\Player;
use pocketmine\utils\Utils;
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
	 * @param \Closure|null       $onClose signature `function(Player $player)`
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
			if(($actual = count($data)) !== ($expected = count($this->elements))){
				throw new FormValidationException("Expected $expected result data, got $actual");
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

			($this->onSubmit)($player, new CustomFormResponse($values));
		}else{
			throw new FormValidationException("Expected array or null, got " . gettype($data));
		}
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