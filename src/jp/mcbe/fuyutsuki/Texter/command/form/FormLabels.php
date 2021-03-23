<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\command\form;

use jp\mcbe\fuyutsuki\Texter\util\Imconstructable;

/**
 * Class FormLabels
 * @package jp\mcbe\fuyutsuki\Texter\command\form
 */
final class FormLabels {

	use Imconstructable;

	public const NAME = "name";
	public const X = "x";
	public const Y = "y";
	public const Z = "z";
	public const TEXT = "text";
	public const ADD_MORE = "add_more";

	public const EDIT = "edit";
	public const MOVE = "move";
	public const REMOVE = "remove";

	public const HERE = "here";
	public const POSITION = "position";

}