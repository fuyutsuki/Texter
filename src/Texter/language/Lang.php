<?php
namespace Texter\language;

use Texter\Main;
use Texter\utils\TunedConfig as Config;

/**
 * 言語選択、文字列翻訳など
 */
class Lang {

  /**
   * 利用可能な言語
   * Available languages
   */
  const JPN = "jpn";
  const ENG = "eng";

  const PREFIX = "[Texter] ";

  /** @var string $dir */
  public $dir = "";
  /** @var Main $main */
  private $main = null;
  /** @var Lang $instance */
  private static $instance = null;
  /** @var Config $config */
  private $config = null;
  /** @var string $language */
  private $language = "";

  public function __construct(Main $main, string $lang) {
    self::$instance = $this;
    $this->main = $main;
    $this->dir = $main->getDataFolder();
    $this->setLang($lang);
  }

  /**
   * 静的にインスタンス取得
   * @return Lang
   */
  public static function getInstance(): Lang{
    return self::$instance;
  }

  /**
   * 現在使用中の言語を取得
   * @return string $this->language
   */
  public function getLang(): string{
    return $this->language;
  }

  /**
   * 使用する言語を取得
   * @param  string $lang
   * @return string $this->language
   */
  public function setLang(string $lang): string{
    switch (strtolower($lang)) {
      case self::ENG:
        $this->language = self::ENG;
      break;

      case self::JPN:
        $this->language = self::JPN;
      break;

      default:
        $this->language = self::ENG;
      break;
    }
    $this->lang = new Config(__DIR__.DS.$this->language.".json", Config::JSON);
    return $this->language;
  }

  /**
   * 翻訳
   * @param  string $key
   * @param  array  $search  = []
   * @param  array  $replace = []
   * @return string
   */
  public function transrateString(string $key, array $search = [], array $replace = []): string{
    $result = $this->lang->get($key);
    if ($result !== false) {
      $result = str_replace($search, $replace, $result);
      return $result;
    }else {
      return $key;
    }
  }
}
