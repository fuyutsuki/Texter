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

namespace jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\dktapps\pmforms;

use pocketmine\form\FormValidationException;
use pocketmine\player\Player;
use pocketmine\utils\Utils;
use function gettype;
use function is_bool;

/**
 * This form type presents a simple "yes/no" dialog with two buttons.
 *
 * @phpstan-type OnSubmit \Closure(Player $player, bool $choice) : void
 * @phpstan-type OnClose \Closure(Player $player) : void
 */
class ModalForm extends BaseForm{

	/** @var string */
	private $content;
	/**
	 * @var \Closure
	 * @phpstan-var OnSubmit
	 */
	private $onSubmit;
	/** @var string */
	private $button1;
	/** @var string */
	private $button2;
	/**
	 * @var \Closure|null
	 * @phpstan-var OnClose
	 */
	private $onClose = null;

	/**
	 * @param string        $title         Text to put on the title of the dialog.
	 * @param string        $text          Text to put in the body.
	 * @param \Closure      $onSubmit      signature `function(Player $player, bool $choice)`
	 * @param string        $yesButtonText Text to show on the "Yes" button. Defaults to client-translated "Yes" string.
	 * @param string        $noButtonText  Text to show on the "No" button. Defaults to client-translated "No" string.
	 * @param \Closure|null $onClose       signature `function(Player $player)`
	 *
	 * @phpstan-param OnSubmit $onSubmit
	 * @phpstan-param OnClose|null $onClose
	 */
	public function __construct(string $title, string $text, \Closure $onSubmit, string $yesButtonText = "gui.yes", string $noButtonText = "gui.no", ?\Closure $onClose = null){
		parent::__construct($title);
		$this->content = $text;
		Utils::validateCallableSignature(function(Player $player, bool $choice) : void{}, $onSubmit);
		$this->onSubmit = $onSubmit;
		$this->button1 = $yesButtonText;
		$this->button2 = $noButtonText;
		if($onClose !== null){
			Utils::validateCallableSignature(function(Player $player) : void{}, $onClose);
			$this->onClose = $onClose;
		}
	}

	public function getYesButtonText() : string{
		return $this->button1;
	}

	public function getNoButtonText() : string{
		return $this->button2;
	}

	final public function handleResponse(Player $player, $data) : void{
		if($data === null){
			if($this->onClose !== null){
				($this->onClose)($player);
			}
		}elseif(is_bool($data)){
			($this->onSubmit)($player, $data);
		}else{
			throw new FormValidationException("Expected bool, got " . gettype($data));
		}
	}

	protected function getType() : string{
		return "modal";
	}

	protected function serializeFormData() : array{
		return [
			"content" => $this->content,
			"button1" => $this->button1,
			"button2" => $this->button2
		];
	}
}