<?php

/**
 * // English
 *
 * Texter, the display FloatingTextPerticle plugin for PocketMine-MP
 * Copyright (c) 2018 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * This software is distributed under "MIT license".
 * You should have received a copy of the MIT license
 * along with this program.  If not, see
 * < https://opensource.org/licenses/mit-license >.
 *
 * ---------------------------------------------------------------------
 * // 日本語
 *
 * TexterはPocketMine-MP向けのFloatingTextPerticleを表示するプラグインです
 * Copyright (c) 2018 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * このソフトウェアは"MITライセンス"下で配布されています。
 * あなたはこのプログラムと共にMITライセンスのコピーを受け取ったはずです。
 * 受け取っていない場合、下記のURLからご覧ください。
 * < https://opensource.org/licenses/mit-license >
 */

declare(strict_types = 1);

namespace tokyo\pmmp\Texter\i18n;

use tokyo\pmmp\Texter\Core;
use tokyo\pmmp\Texter\data\ConfigData;

/**
 * Class Lang
 * @package tokyo\pmmp\Texter\language
 */
class Lang {

  /** @var string */
  public const DIR = "language";
  public const FALLBACK = "en_US";

  /** @var Lang */
  private static $instance;
  /** @var Language[] */
  public static $language;
  /** @var string */
  public static $consoleLang = self::FALLBACK;
  /** @var string[] */
  private static $available = [
    "en_US",
    "ja_JP"
  ];

  public function __construct(Core $core) {
    self::$instance = $this;
    self::$consoleLang = ConfigData::make()->getLocale();
    foreach (self::$available as $lang) {
      $core->saveResource(Lang::DIR . DIRECTORY_SEPARATOR . $lang . ".ini");
      $this->register(new Language($lang));
    }
  }

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

  public static function fromConsole(): Language {
    return self::fromLocale(self::$consoleLang);
  }

  public static function get(): self {
    return self::$instance;
  }
}