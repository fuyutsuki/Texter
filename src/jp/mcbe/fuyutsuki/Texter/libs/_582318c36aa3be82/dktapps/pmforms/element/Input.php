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

namespace jp\mcbe\fuyutsuki\Texter\libs\_582318c36aa3be82\dktapps\pmforms\element;

use pocketmine\form\FormValidationException;
use function gettype;
use function is_string;

/**
 * Element which accepts text input. The text-box can have a default value, and may also have a text hint when there is
 * no text in the box.
 */
class Input extends CustomFormElement{

	/** @var string */
	private $hint;
	/** @var string */
	private $default;

	public function __construct(string $name, string $text, string $hintText = "", string $defaultText = ""){
		parent::__construct($name, $text);
		$this->hint = $hintText;
		$this->default = $defaultText;
	}

	public function getType() : string{
		return "input";
	}

	public function validateValue($value) : void{
		if(!is_string($value)){
			throw new FormValidationException("Expected string, got " . gettype($value));
		}
	}

	/**
	 * Returns the text shown in the text-box when the box is not focused and there is no text in it.
	 */
	public function getHintText() : string{
		return $this->hint;
	}

	/**
	 * Returns the text which will be in the text-box by default.
	 */
	public function getDefaultText() : string{
		return $this->default;
	}

	protected function serializeElementData() : array{
		return [
			"placeholder" => $this->hint,
			"default" => $this->default
		];
	}
}