<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\i18n;

use jp\mcbe\fuyutsuki\Texter\util\StringArrayMultiton;
use pocketmine\{
	lang\BaseLang};
use SplFileInfo;
use function strtolower;

/**
 * Class TexterLang
 * @package jp\mcbe\fuyutsuki\Texter\i18n
 */
class TexterLang extends BaseLang {

	use StringArrayMultiton {
		StringArrayMultiton::__construct as stringArrayMultitonConstruct;
	}

	public const LANGUAGE_EXTENSION = "ini";
	public const FALLBACK_LANGUAGE = "en_us";

	/** @var string */
	private static $consoleLocale = self::FALLBACK_LANGUAGE;

	public function __construct(SplFileInfo $file) {
		$locale = $file->getBasename("." . TexterLang::LANGUAGE_EXTENSION);
		parent::__construct($locale, $file->getPath() . DIRECTORY_SEPARATOR, self::FALLBACK_LANGUAGE);
		$this->stringArrayMultitonConstruct($locale);
	}

	public static function setConsoleLocale(string $locale) {
		self::$consoleLocale = $locale;
	}

	public static function fromConsole(): TexterLang {
		return self::fromLocale(self::$consoleLocale);
	}

	public static function fromLocale(string $locale): TexterLang {
		return self::$instances[strtolower($locale)] ?? self::$instances[self::FALLBACK_LANGUAGE];
	}

}