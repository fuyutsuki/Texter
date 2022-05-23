<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\i18n;

use jp\mcbe\fuyutsuki\Texter\util\StringArrayMultiton;
use pocketmine\lang\Language;
use SplFileInfo;
use function strtolower;

class TexterLang extends Language {

	use StringArrayMultiton {
		StringArrayMultiton::__construct as stringArrayMultitonConstruct;
	}

	public const LANGUAGE_EXTENSION = "ini";
	public const FALLBACK_LANGUAGE = "en_us";

	private static string $consoleLocale = self::FALLBACK_LANGUAGE;

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