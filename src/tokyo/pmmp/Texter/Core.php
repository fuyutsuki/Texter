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
  utils\TextFormat as TF
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
  public const FILE_CONFIG_VER = 22;
  public const FILE_CRFTS      = "crfts.json";
  public const FILE_FTS        = "fts.json";

  public const DS = DIRECTORY_SEPARATOR;

  /** @var ?TexterApi */
  private $api = null;
  /** @var ?Config */
  private $config = null;
  /** @var string */
  private $dir = "";
  /** @var ?BaseLang */
  private $lang = null;
  /** @var string[] */
  private $language = [
    "eng" => "English",
    "jpn" => "日本語"
  ];

  public function onLoad() {
    $this->initApi();
    $this->initFiles();
    $this->initLanguage();
    // TODO:
    // $this->registerCommand();
    // $this->checkUpdate();
    // $this->setTimezone();
  }

  public function onEnable() {
    $listener = new EventListener($this);
    $this->getServer()->getPluginManager()->registerEvents($listener, $this);
    $message = $this->lang->translateString("on.enable.message", [
      TF::GREEN.$this->getDescription()->getFullName(),
      TF::BLUE.self::CODENAME.TF::GREEN
    ]);
    $this->getLogger()->info($message);
  }

  public function onDisable() {

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
    $this->dir = $this->getDataFolder();
    $this->saveResource(self::FILE_FTS);
    $this->saveResource(self::FILE_CRFTS);
    $this->saveResource(self::FILE_CONFIG);
    $this->config = new Config($this->dir.self::FILE_CONFIG, Config::YAML);
  }

  /**
   * @link onLoad() initLanguage
   * @return void
   */
  private function initLanguage(): void {
    $langCode = (string)$this->config->get("language");
    $this->saveResource("language".self::DS.$langCode.".ini");
    $this->lang = new BaseLang($langCode, $this->dir."language".self::DS, "eng");
    $message = $this->lang->translateString("language.selected", [
      $this->lang->translateString("language.name"),
      $langCode
    ]);
    $this->getLogger()->info(TF::GREEN.$message);
  }
}
