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

namespace tokyo\pmmp\Texter;

// pocketmine
use pocketmine\{
  lang\BaseLang,
  plugin\PluginBase,
  utils\TextFormat as TF
};

// texter
use tokyo\pmmp\Texter\{
  command\TxtCommand,
  command\TxtAdmCommand,
  manager\ConfigDataManager,
  manager\CrftsDataManager,
  manager\FtsDataManager,
  manager\Manager,
  task\CheckUpdateTask,
  task\PrepareTextsTask
};

// mcbeformapi
use tokyo\pmmp\MCBEFormAPI\{
  FormApi
};

/**
 * TexterCore
 */
class Core extends PluginBase {

  const LANG_DIR = "language";
  const LANG_FALLBACK = "eng";

  const CODENAME = "Phyllorhiza punctata";
  const CONFIG_VERSION = 23;
  const DS = DIRECTORY_SEPARATOR;
  const PREFIX = "[Texter] ";

  /** @var string */
  public $dir = "";
  /** @var ?ConfigDataManager */
  private $configDm = null;
  /** @var ?CrftsDataManager */
  private $crftsDm = null;
  /** @var ?FtsDataManager */
  private $ftsDm = null;
  /** @var ?TexterApi */
  private $texter = null;
  /** @var ?FormApi */
  private $form = null;
  /** @var ?BaseLang */
  private $lang = null;

  /**
   * @return ?ConfigDataManager
   */
  public function getConfigDataManager(): ?ConfigDataManager {
    return $this->configDm;
  }

  /**
   * @return ?CrftsDataManager
   */
  public function getCrftsDataManager(): ?CrftsDataManager {
    return $this->crftsDm;
  }

  /**
   * @return ?FtsDataManager
   */
  public function getFtsDataManager(): ?FtsDataManager {
    return $this->ftsDm;
  }

  /**
   * @return ?TexterApi
   */
  public function getTexterApi(): ?TexterApi {
    return $this->texter;
  }

  /**
   * @return ?FormApi
   */
  public function getFormApi(): ?FormApi {
    return $this->form;
  }

  /**
   * @return ?BaseLang
   */
  public function getLang(): ?BaseLang {
    return $this->lang;
  }

  public function onLoad() {
    $this->dir = $this->getDataFolder();
    $this->initDataManagers();
    $this->initTexterApi();
    $this->initLang();
    $this->registerCommands();
    $this->setTimezone();
  }

  public function onEnable() {
    $result = $this->checkConfigFormat();
    if ($result !== false) {
      $this->checkUpdate();
      $this->initFormApi();
      $this->prepareTexts();
      $listener = new EventListener($this);
      $this->getServer()->getPluginManager()->registerEvents($listener, $this);
      $message = $this->lang->translateString("on.enable.message", [
        $this->getDescription()->getFullName(),
        TF::BLUE.self::CODENAME.TF::GREEN
      ]);
      $this->getLogger()->info(TF::GREEN.$message);
    }
  }

  private function initDataManagers() {
    $this->configDm = new ConfigDataManager($this);
    $this->crftsDm = new CrftsDataManager($this);
    $this->ftsDm = new FtsDataManager($this);
  }

  private function initTexterApi() {
    $this->texter = new TexterApi($this);
  }

  private function initLang() {
    $langCode = $this->configDm->getLangCode();
    $this->saveResource(self::LANG_DIR.self::DS."eng.ini");
    $this->saveResource(self::LANG_DIR.self::DS.$langCode.".ini");
    $this->lang = new BaseLang($langCode, $this->dir.self::LANG_DIR.self::DS, self::LANG_FALLBACK);
    $message = $this->lang->translateString("language.selected", [
      $this->lang->translateString("language.name"),
      $langCode
    ]);
    $this->getLogger()->info(TF::GREEN.$message);
  }

  private function registerCommands() {
    if ($this->configDm->getCanUseCommands()) {
      $map = $this->getServer()->getCommandMap();
      $commands = [
        new TxtCommand($this),
        new TxtAdmCommand($this)
      ];
      $map->registerAll($this->getName(), $commands);
      $message = $this->lang->translateString("on.load.commands.on");
      $this->getLogger()->info(TF::GREEN.$message);
    }else {
      $message = $this->lang->translateString("on.load.commands.off");
      $this->getLogger()->info(TF::RED.$message);
    }
  }

  private function checkUpdate() {
    if ($this->configDm->getCheckUpdate()) {
      try {
        $task = new CheckUpdateTask();
        $this->getServer()->getScheduler()->scheduleAsyncTask($task);
      } catch (\Exception $e) {
        $this->getLogger()->warning($e->getMessage());
      }
    }
    if (strpos($this->getDescription()->getVersion(), "-") !== false) {
      $this->getLogger()->notice($this->lang->translateString("version.dev"));
    }
  }

  public function versionCompare(string $newVer, string $url) {
    $curVer = $this->getDescription()->getVersion();
    if (version_compare($newVer, $curVer, "=")) {
      $message = $this->lang->translateString("on.load.update.nothing", [
        $curVer
      ]);
      $this->getLogger()->notice($message);
    }elseif (version_compare($newVer, $curVer, ">")) {
      $message1 = $this->lang->translateString("on.load.update.available.1", [
        $newVer,
        $curVer
      ]);
      $message2 = $this->lang->translateString("on.load.update.available.2");
      $message3 = $this->lang->translateString("on.load.update.available.3", [
        $url
      ]);
      $this->getLogger()->notice($message1);
      $this->getLogger()->notice($message2);
      $this->getLogger()->notice($message3);
    }else {
      $message = $this->lang->translateString("on.load.version.dev");
      $this->getLogger()->warning($message);
    }
  }

  private function setTimezone() {
    $timezone = $this->configDm->getTimezone();
    if ($timezone !== "") {
      date_default_timezone_set($timezone);
      $message = $this->lang->translateString("on.load.timezone", [
        $timezone
      ]);
      $this->getLogger()->info(TF::GREEN.$message);
    }
  }

  private function checkConfigFormat(): bool {
    $result1 = !$this->configDm->getConfig()->exists("version") || $this->configDm->getConfigVer() < self::CONFIG_VERSION;
    $result2 = $this->crftsDm->isOldFormat();
    $result3 = $this->ftsDm->isOldFormat();
    $files = [];
    if ($result1) $files[] = "config.yml";
    if ($result2) $files[] = "crfts.json";
    if ($result3) $files[] = "fts.json";
    if ($result1 || $result2 || $result3) {
      $fileList = implode(", ", $files);
      $message = $this->lang->translateString("on.load.config.update", [
        $fileList
      ]);
      $this->getLogger()->notice($message);
      $this->getServer()->getPluginManager()->disablePlugin($this);
      return false;
    }
    return true;
  }

  private function initFormApi() {
    $this->form = new FormApi($this);
  }

  private function prepareTexts() {
    $task = new PrepareTextsTask($this);
    $this->getServer()->getScheduler()->scheduleRepeatingTask($task, 1);
  }
}
