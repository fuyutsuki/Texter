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
  public const JPN = "jpn";
  public const ENG = "eng";

  public const PREFIX = "[Texter] ";

  /** @var string $dir */
  public $dir = "";
  /** @var Main $main */
  private $main = null;
  /** @var Lang $instance */
  private static $instance = null;
  /** @var Config $config */
  private $config = null;
  /** @var string $lang */
  private $lang = "";
  /** @var array $langList */
  private $langList = [
    self::JPN => "日本語",
    self::ENG => "English"
  ];

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
   * 現在使用中の言語を取得(jpn, eng)
   * @return string "eng"|"jpn"
   */
  public function getLangCode(): string{
    return $this->lang;
  }

  /**
   * 現在使用中の言語を取得
   * @return string "English"|"日本語"
   */
  public function getLang(): string{
    return $this->langList[$this->lang];
  }

  /**
   * 使用する言語を取得
   * @param  string $lang
   * @return string $this->langList[$this->lang]
   */
  public function setLang(string $lang): string{
    switch (strtolower($lang)) {
      case self::ENG:
        $this->lang = self::ENG;
      break;

      case self::JPN:
        $this->lang = self::JPN;
      break;

      default:
        $this->lang = self::ENG;
      break;
    }
    $this->config = new Config(__DIR__.DS.$this->lang.".json", Config::JSON);
    return $this->langList[$this->lang];
  }

  /**
   * 翻訳
   * @param  string $key
   * @param  array  $search  = []
   * @param  array  $replace = []
   * @return string
   */
  public function transrateString(string $key, array $search = [], array $replace = []): string{
    $result = $this->config->get($key);
    if ($result !== false) {
      $result = str_replace($search, $replace, $result);
      return $result;
    }else {
      return $key;
    }
  }
}
