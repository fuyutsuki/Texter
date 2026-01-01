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

namespace jp\mcbe\fuyutsuki\Texter\libs\_e7f596115d1cc90b\dktapps\pmforms;

/**
 * Represents an option on a MenuForm. The option is shown as a button and may optionally have an image next to it.
 */
class MenuOption implements \JsonSerializable{

	/** @var string */
	private $text;
	/** @var FormIcon|null */
	private $image;

	public function __construct(string $text, ?FormIcon $image = null){
		$this->text = $text;
		$this->image = $image;
	}

	public function getText() : string{
		return $this->text;
	}

	public function hasImage() : bool{
		return $this->image !== null;
	}

	public function getImage() : ?FormIcon{
		return $this->image;
	}

	#[\ReturnTypeWillChange]
	public function jsonSerialize(){
		$json = [
			"text" => $this->text
		];

		if($this->hasImage()){
			$json["image"] = $this->image;
		}

		return $json;
	}
}