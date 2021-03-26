<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\i18n;

use jp\mcbe\fuyutsuki\Texter\util\StringArrayMultiton;
use pocketmine\lang\BaseLang;
use SplFileInfo;

/**
 * Class MineflowLang
 * @package jp\mcbe\fuyutsuki\Texter\i18n
 */
class MineflowLang extends BaseLang {

	use StringArrayMultiton {
		StringArrayMultiton::__construct as stringArrayMultitonConstruct;
	}

	public const FALLBACK_LANGUAGE = "eng";

	public function __construct(SplFileInfo $file) {
		$locale = $file->getBasename("." . TexterLang::LANGUAGE_EXTENSION);
		parent::__construct($locale, $file->getPath() . DIRECTORY_SEPARATOR, self::FALLBACK_LANGUAGE);
		$this->stringArrayMultitonConstruct($locale);
	}

	public function getAll(): array {
		return $this->lang;
	}

	/**
	 * @return self[]
	 */
	public static function all(): array {
		return self::$instances;
	}

}