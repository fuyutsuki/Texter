<?php

/**
 * ## English
 *
 * Texter, the display FloatingTextPerticle plugin for PocketMine-MP
 * Copyright (c) 2017 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * This software is distributed under "MIT license".
 * You should have received a copy of the MIT license
 * along with this program.  If not, see
 * < https://opensource.org/licenses/mit-license >.
 *
 * ---------------------------------------------------------------------
 * ## 日本語
 *
 * TexterはPocketMine-MP向けのFloatingTextPerticleを表示するプラグインです。
 * Copyright (c) 2017 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * このソフトウェアは"MITライセンス"下で配布されています。
 * あなたはこのプログラムと共にMITライセンスのコピーを受け取ったはずです。
 * 受け取っていない場合、下記のURLからご覧ください。
 * < https://opensource.org/licenses/mit-license >
 */

namespace tokyo\pmmp\Texter;

// Pocketmine
use pocketmine\{
  lang\BaseLang,
  plugin\PluginBase,
  utils\Config,
  utils\TextFormat as TF,
  utils\UUID
};

// Texter
use tokyo\pmmp\Texter\{
  EventListener,
  TexterApi
};

/**
 * Texter Core
 */
class Core extends PluginBase {

  public const VERSION  = "2.3.0";
  public const CODENAME = "Phyllorhiza punctata";

  public const FILE_CONFIG     = "config.yml";
  public const FILE_CONFIG_VER = 23;

  public const DS = DIRECTORY_SEPARATOR;

  /** @var ?TexterApi */
  private $api = null;
  /** @var ?BaseLang */
  private $lang = null;
  /** @var ?Config */
  private $config = null;
  /** @var int */
  private $char = 50;
  /** @var int */
  private $feed = 3;
  /** @var string[] */
  private $worlds = [];
  /** @var string */
  public $dir = "";

  public function onLoad() {
    $this->dir = $this->getDataFolder();
    $this->initApi();
    $this->initFiles();
    $this->initLanguage();
    $this->loadLimits();
    // TODO:
    // $this->registerCommand();
    // $this->checkUpdate();
    // $this->setTimezone();
  }

  public function onEnable() {
    $this->api->prepareTexts();
    $listener = new EventListener($this);
    $this->getServer()->getPluginManager()->registerEvents($listener, $this);
    $message = $this->lang->translateString("on.enable.message", [
      TF::GREEN.$this->getDescription()->getFullName(),
      TF::BLUE.self::CODENAME.TF::GREEN
    ]);
    $this->getLogger()->info($message);
  }

  public function onDisable() {
    // TODO:
  }

  /**
   * @return ?TexterApi
   */
  public function getApi(): ?TexterApi{
    return $this->api;
  }

  /**
   * @return ?BaseLang
   */
  public function getLang(): ?BaseLang{
    return $this->lang;
  }

  /**
   * @return int
   */
  public function getCharLimit(): int{
    return $this->char;
  }

  /**
   * @return int
   */
  public function getFeedLimit(): int{
    return $this->feed;
  }

  /**
   * @return array
   */
  public function getWorldLimit(): array{
    return $this->worlds;
  }

  /**
   * @link onLoad() initApi
   * @return void
   */
  private function initApi(): void {
    $this->api = new TexterApi($this);
  }

  /**
   * @link onLoad() initFiles
   * @return void
   */
  private function initFiles(): void {
    $this->saveResource(self::FILE_CONFIG);
    $this->config = new Config($this->dir.self::FILE_CONFIG, Config::YAML);
  }

  /**
   * @link onLoad() initLanguage
   * @return void
   */
  private function initLanguage(): void {
    $langCode = (string)$this->config->get("language");
    $this->saveResource("language".self::DS."eng.ini");
    $this->saveResource("language".self::DS.$langCode.".ini");
    $this->lang = new BaseLang($langCode, $this->dir."language".self::DS, "eng");
    $message = $this->lang->translateString("language.selected", [
      $this->lang->translateString("language.name"),
      $langCode
    ]);
    $this->getLogger()->info(TF::GREEN.$message);
  }

  /**
   * @link onLoad() loadLimits
   * @return void
   */
  private function loadLimits(): void {
    try {
      $char = $this->config->get("char");
      if ($char !== false) {
        if (is_int($char)) {
          $this->char = $char;
        }else {
          $message = $this->lang->translateString("error.config.limit", [
            "char",
            50
          ]);
          throw new \ErrorException($message, E_NOTICE);
        }
      }
    } catch (\Exception $e) {
      $this->getLogger()->notice($e->getMessage());
    }
    try {
      $feed = $this->config->get("feed");
      if ($feed !== false) {
        if (is_int($feed)) {
          $this->feed = $feed;
        }else {
          $message = $this->lang->translateString("error.config.limit", [
            "feed",
            3
          ]);
          throw new \ErrorException($message, E_NOTICE);
        }
      }
    } catch (\Exception $e) {
      $this->getLogger()->notice($e->getMessage());
    }
    try {
      $worlds = $this->config->get("worlds");
      if ($worlds !== false) {
        if (is_string($worlds)) {
          $this->worlds = [$worlds => ""];
        }elseif(is_array($worlds)) {
          $this->worlds = array_flip($worlds);
        }else {
          $message = $this->lang->translateString("error.config.limit", [
            "world",
            "[] (unlimited)"
          ]);
          throw new \ErrorException($message, E_NOTICE);
        }
      }else {
        $this->worlds = [];
      }
    } catch (\ErrorException $e) {
      $this->getLogger()->notice($e->getMessage());
    }
  }
}
