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

use pocketmine\form\FormValidationException;
use pocketmine\player\Player;
use pocketmine\utils\Utils;
use function array_values;
use function gettype;
use function is_int;

/**
 * This form type presents a menu to the user with a list of options on it. The user may select an option or close the
 * form by clicking the X in the top left corner.
 *
 * @phpstan-type OnSubmit \Closure(Player $player, int $selectedOption) : void
 * @phpstan-type OnClose \Closure(Player $player) : void
 */
class MenuForm extends BaseForm{

	/** @var string */
	protected $content;
	/** @var MenuOption[] */
	private $options;
	/**
	 * @var \Closure
	 * @phpstan-var OnSubmit
	 */
	private $onSubmit;
	/**
	 * @var \Closure|null
	 * @phpstan-var OnClose
	 */
	private $onClose = null;

	/**
	 * @param MenuOption[]  $options
	 * @param \Closure      $onSubmit signature `function(Player $player, int $selectedOption)`
	 * @param \Closure|null $onClose  signature `function(Player $player)`
	 *
	 * @phpstan-param OnSubmit     $onSubmit
	 * @phpstan-param OnClose|null $onClose
	 */
	public function __construct(string $title, string $text, array $options, \Closure $onSubmit, ?\Closure $onClose = null){
		parent::__construct($title);
		$this->content = $text;
		$this->options = array_values($options);
		Utils::validateCallableSignature(function(Player $player, int $selectedOption) : void{}, $onSubmit);
		$this->onSubmit = $onSubmit;
		if($onClose !== null){
			Utils::validateCallableSignature(function(Player $player) : void{}, $onClose);
			$this->onClose = $onClose;
		}
	}

	public function getOption(int $position) : ?MenuOption{
		return $this->options[$position] ?? null;
	}

	final public function handleResponse(Player $player, $data) : void{
		if($data === null){
			if($this->onClose !== null){
				($this->onClose)($player);
			}
		}elseif(is_int($data)){
			if(!isset($this->options[$data])){
				throw new FormValidationException("Option $data does not exist");
			}
			($this->onSubmit)($player, $data);
		}else{
			throw new FormValidationException("Expected int or null, got " . gettype($data));
		}
	}

	protected function getType() : string{
		return "form";
	}

	protected function serializeFormData() : array{
		return [
			"content" => $this->content,
			"buttons" => $this->options //yes, this is intended (MCPE calls them buttons)
		];
	}
}