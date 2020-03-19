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

namespace tokyo\pmmp\Texter;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\utils\VersionString;
use tokyo\pmmp\Texter\command\TxtCommand;
use tokyo\pmmp\Texter\data\ConfigData;
use tokyo\pmmp\Texter\data\FloatingTextData;
use tokyo\pmmp\Texter\data\UnremovableFloatingTextData;
use tokyo\pmmp\Texter\i18n\Lang;
use tokyo\pmmp\Texter\task\CheckUpdateTask;
use tokyo\pmmp\Texter\task\PrepareTextsTask;
use function class_exists;

/**
 * Class Core
 * @package tokyo\pmmp\Texter
 */
class Core extends PluginBase implements Listener {

  /** @var Core */
  private static $core;
  /** @var bool */
  private static $isUpdater = false;

  public function onLoad() {
    self::$core = $this;
    $this
      ->loadResources()
      ->loadLanguage()
      ->registerCommands()
      ->prepareTexts()
      ->checkUpdate();
  }

  public function onEnable() {
    if ($this->checkPackaged()) {
      $listener = new EventListener;
      $this->getServer()->getPluginManager()->registerEvents($listener, $this);
    }else {
      $this->getServer()->getPluginManager()->disablePlugin($this);
    }
  }

  private function loadResources(): self {
    $dir = $this->getDataFolder();
    new ConfigData($this, $dir, "config.yml");
    new UnremovableFloatingTextData($this, $dir, "uft.json");
    new FloatingTextData($this, $dir, "ft.json");
    return $this;
  }

  private function loadLanguage(): self {
    new Lang($this);
    $cl = Lang::fromConsole();
    $message1 = $cl->translateString("language.selected", [
      $cl->getName(),
      $cl->getLang()
    ]);
    $this->getLogger()->info(TextFormat::GREEN . $message1);
    if (self::isUpdater()) {
      $message2 = $cl->translateString("on.load.is.updater");
      $this->getLogger()->notice($message2);
    }
    return $this;
  }

  private function registerCommands(): self {
    if ($canUse = ConfigData::make()->canUseCommands()) {
      $map = $this->getServer()->getCommandMap();
      $commands = [
        new TxtCommand
      ];
      $map->registerAll($this->getName(), $commands);
      $message = Lang::fromConsole()->translateString("on.load.commands.on");
    }else {
      $message = Lang::fromConsole()->translateString("on.load.commands.off");
    }
    $this->getLogger()->info(($canUse ? TextFormat::GREEN : TextFormat::RED) . $message);
    return $this;
  }

  private function prepareTexts(): self {
    $prepare = new PrepareTextsTask;
    $this->getScheduler()->scheduleDelayedRepeatingTask($prepare, 20, 1);
    return $this;
  }

  private function checkUpdate(): self {
    if (ConfigData::make()->checkUpdate()) {
      try {
        $this->getServer()->getAsyncPool()->submitTask(new CheckUpdateTask);
      } catch (\Exception $ex) {
        $this->getLogger()->warning($ex->getMessage());
      }
    }
    return $this;
  }

  public function compareVersion(bool $success, ?VersionString $new = null, string $url = "") {
    $cl = Lang::fromConsole();
    $logger = $this->getLogger();
    if ($success) {
      $current = new VersionString($this->getDescription()->getVersion());
      switch ($current->compare($new)) {
        case -1:// new: older
          $message = $cl->translateString("on.load.version.dev");
          $logger->warning($message);
          break;

        case 0:// same
          $message = $cl->translateString("on.load.update.nothing", [
            $current->getFullVersion()
          ]);
          $logger->notice($message);
          break;

        case 1:// new: newer
          $messages[] = $cl->translateString("on.load.update.available.1", [
            $new->getFullVersion(),
            $current->getFullVersion()
          ]);
          $messages[] = $cl->translateString("on.load.update.available.2");
          $messages[] = $cl->translateString("on.load.update.available.3", [
            $url
          ]);
          foreach ($messages as $message) $logger->notice($message);
      }
    }else {
      $message = $cl->translateString("on.load.update.offline");
      $logger->notice($message);
    }
  }

  private function checkPackaged(): bool {
    $cl = Lang::fromConsole();
    $logger = $this->getLogger();
    if ($this->isPhar()) {
      if (class_exists("\\tokyo\\pmmp\\Texter\\libs\\jojoe77777\\FormAPI\\FormAPI")) {
        return true;// PoggitCI
      }else {
        $message = $cl->translateString("error.on.enable.not.packaged");
        $logger->critical($message);
        return false;
      }
    }else {
      $plugins = $this->getServer()->getPluginManager()->getPlugins();
      if (isset($plugins["DEVirion"]) || isset($plugins["FormAPI"])) {
        if (class_exists("\\jojoe77777\\FormAPI\\FormAPI")) {
          return true;// developer
        }else {
          $message = $cl->translateString("error.on.enable.not.found.libformapi");
          $logger->critical($message);
          return false;
        }
      }else {
        $message = $cl->translateString("error.on.enable.not.packaged");
        $logger->critical($message);
        return false;
      }
    }
  }

  public static function isUpdater(): bool {
    return self::$isUpdater;
  }

  public static function setIsUpdater(bool $bool = true) {
    self::$isUpdater = $bool;
  }

  public static function get(): Core {
    return self::$core;
  }
}