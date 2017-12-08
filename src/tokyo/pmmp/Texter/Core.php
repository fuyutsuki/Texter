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

  /** @var ?TexterApi */
  private $api = null;
  /** @var string */
  private $langCode = "";
  /** @var string[] */
  private $language = [
    "eng" => "English",
    "jpn" => "日本語"
  ];

  public function onLoad() {
    // TODO:
    $this->initApi();
    $this->initFiles();
    $this->initLanguage();
    // $this->checkUpdate();
    // $this->setTimezone();
  }

  public function onEnable() {
    $listener = new EventListener($this);
    $this->getServer()->getPluginManager()->registerEvents($listener, $this);
    $message  = TF::GREEN.$this->getDescription()->getFullName();
    $message .= " - ".TF::BLUE."\"".self::CODENAME."\"";
    $message .= TF::GREEN;// TODO: transrate
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
    $dir = $this->getDataFolder();
    
  }

  /**
   * @link onLoad() initLanguage
   * @return void
   */
  private function initLanguage(): void {
    // TODO: 2017/12/08
    $lang = new BaseLang();
  }
}
