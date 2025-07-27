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

/**
 * Represents an icon which can be placed next to options on menus, or as the icon for the server-settings form type.
 */
class FormIcon implements \JsonSerializable{
	public const IMAGE_TYPE_URL = "url";
	public const IMAGE_TYPE_PATH = "path";

	/** @var string */
	private $type;
	/** @var string */
	private $data;

	/**
	 * @param string $data URL or path depending on the type chosen.
	 * @param string $type Can be one of the constants at the top of the file, but only "url" is known to work.
	 */
	public function __construct(string $data, string $type = self::IMAGE_TYPE_URL){
		$this->type = $type;
		$this->data = $data;
	}

	public function getType() : string{
		return $this->type;
	}

	public function getData() : string{
		return $this->data;
	}

	#[\ReturnTypeWillChange]
	public function jsonSerialize(){
		return [
			"type" => $this->type,
			"data" => $this->data
		];
	}
}