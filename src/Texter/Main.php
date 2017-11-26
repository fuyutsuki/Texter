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

namespace Texter;

# Pocketmine
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\command\{
  Command,
  CommandSender};
use pocketmine\entity\Entity;
use pocketmine\event\{
  Listener,
  entity\EntityLevelChangeEvent,
  player\PlayerJoinEvent};
use pocketmine\item\Item;
use pocketmine\level\{
  Level,
  Position};
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TF;

# Texter
use Texter\EventListener;
use Texter\TexterApi;
use Texter\commands\{
  TxtCommand,
  TxtAdmCommand};
use Texter\language\Lang;
use Texter\text\{
  CantRemoveFloatingText as CRFT,
  FloatingText as FT};
use Texter\task\{
  CheckUpdateTask,
  WorldGetTask};
use Texter\utils\TunedConfig as Config;

define("DS", DIRECTORY_SEPARATOR);

class Main extends PluginBase {

  public const NAME = "Texter";
  public const VERSION = "v2.2.6";
  public const CODENAME = "Papilio dehaanii(カラスアゲハ)";

  public const FILE_CONFIG = "config.yml";
  public const FILE_CRFT = "crfts.json";
  public const FILE_FT = "fts.json";

  public const CONFIG_VERSION = 22;

  /** @var bool $devmode */
  public $devmode = false;
  /** @var string $dir */
  public $dir = "";
  /** @var Config $config */
  private $config = null;
  /** @var TexterApi $api */
  private $api = null;
  /** @var Lang $language */
  private $language = null;
  /** @var array $crfts */
  private $crfts = [];
  /** @var array $fts */
  private $fts = [];

  /****************************************************************************/
  /* Public functions */

  /**
   * TexterApiを取得します
   * @return TexterApi $this->api
   */
  public function getApi(): TexterApi{
    return $this->api;
  }

  /**
   * 文字数制限のための文字数を取得します
   * @return int
   */
  public function getCharaLimit(): int{
    return (int)$this->config->get("limit");
  }

  /**
   * 改行数制限のための改行数を設定します
   * @return int
   */
  public function getFeedLimit(): int{
    return (int)$this->config->get("feed");
  }

  /**
   * ワールド制限のためのワールド名を配列で取得します
   * @return array
   */
  public function getWorldLimit(): array{
    $worlds = $this->config->get("world");
    if ($worlds !== false) {
      return array_flip($worlds);
    }else {
      return [];
    }
  }

  /****************************************************************************/
  /* PMMP Api */

  public function onLoad(){
    $this->loadFiles();
    $this->initApi();
    $this->registerCommands();
    $this->checkUpdate();
    $this->setTimezone();
  }

  public function onEnable(){
    $this->prepareTexts();
    $listener = new EventListener($this);
    $this->getServer()->getPluginManager()->registerEvents($listener, $this);
    $this->getLogger()->info(TF::GREEN.self::NAME." ".self::VERSION." - ".TF::BLUE."\"".self::CODENAME."\" ".TF::GREEN.$this->language->transrateString("on.enable"));
  }

  /****************************************************************************/
  /* Private functions */

  /**
   * 各種ファイルを読み込みます
   * @return void
   */
  private function loadFiles(): void{
    $this->dir = $this->getDataFolder();
    //
    if(!file_exists($this->dir)){
      mkdir($this->dir);
    }
    if(!file_exists($this->dir.self::FILE_CONFIG)){
      file_put_contents($this->dir.self::FILE_CONFIG, $this->getResource(self::FILE_CONFIG));
    }
    if(!file_exists($this->dir.self::FILE_CRFT)){
      file_put_contents($this->dir.self::FILE_CRFT, $this->getResource(self::FILE_CRFT));
    }
    // config.yml
    $this->config = new Config($this->dir.self::FILE_CONFIG, Config::YAML);
    // Lang
    $lang = $this->config->get("language");
    if ($lang !== false) {
      $this->language = new Lang($this, $lang);
      $this->getLogger()->info(TF::GREEN.$this->language->transrateString("lang.registered", ["{lang}"], [$this->language->getLang()]));
    }else {
      $this->getLogger()->error("Invalid language settings. If you have any questions, please contact the issue.");
    }
    // crfts.json
    $crft_config = new Config($this->dir.self::FILE_CRFT, Config::JSON);
    $this->crfts = $crft_config->getAll();
    // fts.json
    $ft_config = new Config($this->dir.self::FILE_FT, Config::JSON);
    $this->fts = $ft_config->getAll();
    // CheckConfigVersion
    if (!$this->config->exists("configVersion") ||
        $this->config->get("configVersion") < self::CONFIG_VERSION) {
      $this->getLogger()->notice($this->language->transrateString("config.update", ["{newer}"], [self::CONFIG_VERSION]));
    }
  }

  /**
   * APIを初期化します
   * @return void
   */
  private function initApi(): void{
    $this->api = new TexterApi($this);
  }

  /**
   * コマンドを登録します
   * @return void
   */
  private function registerCommands(): void{
    if ((bool)$this->config->get("canUseCommands")) {
      $map = $this->getServer()->getCommandMap();
      $commands = [
        new TxtCommand($this),
        new TxtAdmCommand($this)
      ];
      $map->registerAll(self::NAME, $commands);
      $this->getLogger()->info(TF::GREEN.$this->language->transrateString("commands.registered"));
    }else {
      $this->getLogger()->info(TF::RED.$this->language->transrateString("commands.unavailable"));
    }
  }

  /**
   * アップデートを確認するために非同期タスクを実行します
   * @return void
   */
  private function checkUpdate(): void{
    if ((bool)$this->config->get("checkUpdate")) {
      try {
        $async = new CheckUpdateTask();
        $this->getServer()->getScheduler()->scheduleAsyncTask($async);
      } catch (\Exception $e) {
        $this->getLogger()->warning($e->getMessage());
      }
    }
    if (strpos(self::VERSION, "-") !== false) {
      $this->getLogger()->notice($this->language->transrateString("version.pre"));
      $this->devmode = true;
    }
  }

  /**
   * 非同期タスクで取得したデータを比較します
   * @param  array $data
   * @return void
   */
  public function versionCompare(array $data): void{
    $curver = str_replace("v", "", self::VERSION);
    $newver = str_replace("v", "", $data[0]["name"]);
    if ($this->getDescription()->getVersion() !== $curver) {
      $this->getLogger()->warning($this->language->transrateString("version.warning"));
    }
    if (version_compare($newver, $curver, "=")) {
      $this->getLogger()->notice($this->language->transrateString("update.unnecessary", ["{curver}"], [$curver]));
    }elseif (version_compare($newver, $curver, ">")){
      $this->getLogger()->notice($this->language->transrateString("update.available.1", ["{newver}", "{curver}"], [$newver, $curver]));
      $this->getLogger()->notice($this->language->transrateString("update.available.2"));
      $this->getLogger()->notice($this->language->transrateString("update.available.3", ["{url}"], [$data[0]["html_url"]]));
    }
  }

  /**
   * タイムゾーンを設定します
   * @return void
   */
  private function setTimezone(): void{
    $timezone = $this->config->get("timezone");
    if ($timezone !== false) {
      date_default_timezone_set($timezone);
      $this->getLogger()->info(TF::GREEN.$this->language->transrateString("timezone", ["{zone}"], [$timezone]));
    }
  }

  /**
   * テキストを生成します
   * @return void
   * TODO: 非同期化?
   */
  private function prepareTexts(): void{
    if (!empty($this->crfts)) {
      foreach ($this->crfts as $value) {
        $title = isset($value["TITLE"]) ? str_replace("#", "\n", $value["TITLE"]) : "";
        $text = isset($value["TEXT"]) ? str_replace("#", "\n", $value["TEXT"]) : "";
        if (is_null($value["WORLD"]) || $value["WORLD"] === "default"){
          $value["WORLD"] = $this->getServer()->getDefaultLevel()->getName();
        }
        //
        if ($this->getServer()->loadLevel($value["WORLD"])) {
          $level = $this->getServer()->getLevelByName($value["WORLD"]);
          $crft = new CRFT($level, $value["Xvec"], $value["Yvec"], $value["Zvec"], $title, $text);
          if ($crft->failed) {
            $message = $this->language->transrateString("txt.failed");
            $this->getLogger()->notice($message);
          }
        }else {
          $message = $this->language->transrateString("world.not.exists", ["{world}"], [$value["WORLD"]]);
          $this->getLogger()->notice($message);
        }
      }
    }
    if (!empty($this->fts)) {
      foreach ($this->fts as $value) {
        $title = isset($value["TITLE"]) ? str_replace("#", "\n", $value["TITLE"]) : "";
        $text = isset($value["TEXT"]) ? str_replace("#", "\n", $value["TEXT"]) : "";
        if (is_null($value["WORLD"]) || $value["WORLD"] === "default"){
          $value["WORLD"] = $this->getServer()->getDefaultLevel()->getName();
        }
        //
        if ($this->getServer()->loadLevel($value["WORLD"])) {
          $level = $this->getServer()->getLevelByName($value["WORLD"]);
          $ft = new FT($level, $value["Xvec"], $value["Yvec"], $value["Zvec"], $title, $text, strtolower($value["OWNER"]));
          if ($ft->failed) {
            $message = $this->language->transrateString("txt.failed");
            $this->getLogger()->notice($message);
          }
        }else {
          $message = $this->language->transrateString("world.not.exists", ["{world}"], [$value["WORLD"]]);
          $this->getLogger()->notice($message);
        }
      }
    }
  }
}
