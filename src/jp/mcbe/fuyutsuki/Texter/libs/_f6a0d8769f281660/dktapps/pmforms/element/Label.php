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

namespace jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\dktapps\pmforms\element;

use function assert;

/**
 * Element which displays some text on a form.
 */
class Label extends CustomFormElement{

	public function getType() : string{
		return "label";
	}

	public function validateValue($value) : void{
		assert($value === null);
	}

	protected function serializeElementData() : array{
		return [];
	}
}