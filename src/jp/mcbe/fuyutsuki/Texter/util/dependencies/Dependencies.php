<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\util\dependencies;

use jp\mcbe\fuyutsuki\Texter\util\Imconstructable;

final class Dependencies {

	use Imconstructable;

	public const PLUGIN_NAMESPACE = "\\jp\\mcbe\\fuyutsuki\\Texter";

	public const PHARYNX_LIBRARY_DIR = "\\libs";
	public const PMFORMS = "\\dktapps\\pmforms\\BaseForm";

}