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

/**
 * API for Minecraft: Bedrock custom UI (forms)
 */
namespace jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\dktapps\pmforms;

use pocketmine\form\Form;

/**
 * Base class for a custom form. Forms are serialized to JSON data to be sent to clients.
 */
abstract class BaseForm implements Form{

	/** @var string */
	protected $title;

	public function __construct(string $title){
		$this->title = $title;
	}

	/**
	 * Returns the text shown on the form title-bar.
	 */
	public function getTitle() : string{
		return $this->title;
	}

	/**
	 * Serializes the form to JSON for sending to clients.
	 * @return mixed[]
	 */
	final public function jsonSerialize() : array{
		$ret = $this->serializeFormData();
		$ret["type"] = $this->getType();
		$ret["title"] = $this->getTitle();

		return $ret;
	}

	/**
	 * Returns the type used to show this form to clients
	 */
	abstract protected function getType() : string;

	/**
	 * Serializes additional data needed to show this form to clients.
	 * @return mixed[]
	 */
	abstract protected function serializeFormData() : array;

}