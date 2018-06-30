<?php
namespace tokyo\pmmp\Texter\i18n;

use pocketmine\lang\BaseLang;
use pocketmine\Player;
use pocketmine\utils\MainLogger;
use pocketmine\utils\TextFormat;
use tokyo\pmmp\Texter\Core;

class Lang extends BaseLang {

  /** @var string */
  public const LANG_DIR = "i18n";
  public const FALLBACK_LANGUAGE = "en_US";
  public const AVAILABLE_LANG = [
    "en_US",
    "ja_JP"
  ];

  /** @var Lang[] */
  private static $langs = [
    /**
    "en_US" => Lang(),
    "ja_JP" => Lang(),
     */
  ];

  /** @var Lang */
  private static $consoleLang;

  /**
   * @param Player $player
   * @return Lang
   */
  public static function detectLang(Player $player): Lang {
    $locale = $player->getLocale();
    return self::detectLangByStr($locale);
  }

  /**
   * @param string $locale
   * @return Lang
   */
  public static function detectLangByStr(string $locale = self::FALLBACK_LANGUAGE): Lang {
    if (isset(self::$langs[$locale])) {
      return self::$langs[$locale];
    }else {
      MainLogger::getLogger()->info(TextFormat::YELLOW.Core::PREFIX."Missing required i18n file: ".$locale.".ini");
      return self::$langs[self::FALLBACK_LANGUAGE];
    }
  }

  /**
   * @param array $langs
   */
  public static function registerLanguages(array $langs): void {
    self::$langs = $langs;
  }

  /**
   * @param Lang $lang
   */
  public static function register(Lang $lang): void {
    self::$consoleLang = $lang;
  }

  /**
   * @param Lang $lang
   * @return Lang
   */
  public static function getConsoleLang(Lang $lang): Lang {
    return self::$consoleLang;
  }
}