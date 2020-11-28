<?php

/**
 * // English
 *
 * Texter, the display FloatingTextPerticle plugin for PocketMine-MP
 * Copyright (c) 2019-2020 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * This software is distributed under "NCSA license".
 * You should have received a copy of the NCSA license
 * along with this program.  If not, see
 * < https://opensource.org/licenses/NCSA >.
 *
 * ---------------------------------------------------------------------
 * // 日本語
 *
 * TexterはPocketMine-MP向けのFloatingTextPerticleを表示するプラグインです
 * Copyright (c) 2019-2020 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * このソフトウェアは"NCSAライセンス"下で配布されています。
 * あなたはこのプログラムと共にNCSAライセンスのコピーを受け取ったはずです。
 * 受け取っていない場合、下記のURLからご覧ください。
 * < https://opensource.org/licenses/NCSA >
 */

declare(strict_types = 1);

namespace tokyo\pmmp\Texter\i18n;

use tokyo\pmmp\Texter\Core;
use tokyo\pmmp\Texter\data\ConfigData;
use function strtolower;

/**
 * Class Lang
 * @package tokyo\pmmp\Texter\language
 */
class Lang {

  public const DIR = "language";
  public const FALLBACK = "en_us";

  /** @var Lang */
  private static $instance;
  /** @var Language[] */
  private static $language;
  /** @var string */
  private static $consoleLang = self::FALLBACK;
  /** @var string[] */
  private static $available = [
    "en_us",
    "ja_jp",
    "ru_ru",
    "tr_tr",
    "zh_cn",
    "ko_kr",
    "id_id",
  ];

  public function __construct(Core $core) {
    self::$instance = $this;
    self::$consoleLang = ConfigData::make()->getLocale();
    foreach (self::$available as $lang) {
      $core->saveResource(Lang::DIR . DIRECTORY_SEPARATOR . $lang . ".ini", true);
      $this->register(new Language($lang));
    }
  }

  /**
   * @param Language $language
   * @return Lang
   */
  public function register(Language $language): self {
    self::$language[$language->getLang()] = $language;
    return self::$instance;
  }

  /**
   * @param string $lang
   * @return Language
   */
  public static function fromLocale(string $lang): Language {
    $lLang = strtolower($lang);
    if (isset(self::$language[$lLang])) {
      return self::$language[$lLang];
    }else {
      return self::$language[self::FALLBACK];
    }
  }

  /**
   * @return Language
   */
  public static function fromConsole(): Language {
    return self::fromLocale(self::$consoleLang);
  }

  /**
   * @return Lang
   */
  public static function get(): self {
    return self::$instance;
  }
}
