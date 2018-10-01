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

namespace tokyo\pmmp\Texter;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use tokyo\pmmp\libform\FormApi;
use tokyo\pmmp\Texter\command\TxtAdmCommand;
use tokyo\pmmp\Texter\command\TxtCommand;
use tokyo\pmmp\Texter\data\ConfigData;
use tokyo\pmmp\Texter\data\FloatingTextData;
use tokyo\pmmp\Texter\data\UnremovableFloatingTextData;
use tokyo\pmmp\Texter\i18n\Lang;
use tokyo\pmmp\Texter\task\AsyncPrepareTextsTask;

/**
 * TODO
 * 自動データアプデ
 * アップデート通知送信
 * アプデ内容を出力できるように
 * 表示関連実装
 *
 * Class Core
 * @package tokyo\pmmp\Texter
 */
class Core extends PluginBase implements Listener {

  /** @var string */
  public const PREFIX = "[Texter] ";

  /** @var Core */
  private static $core;

  public function onLoad(): void {
    self::$core = $this;
    $this
      ->checkOldDirectories()// Rename 2.x.y series files
      ->loadResources()
      ->loadLanguage()
      ->registerCommands()
      ->prepareTexts();
  }

  public function onEnable(): void {
    FormApi::register($this);
    $listener = new EventListener;
    $this->getServer()->getPluginManager()->registerEvents($listener, $this);
  }

  private function checkOldDirectories(): self {
    $dir = $this->getDataFolder();
    if (file_exists("{$dir}crfts.json")) {
      rename("{$dir}crfts.json", "{$dir}uft.json");
    }
    if (file_exists("{$dir}fts.json")) {
      rename("{$dir}fts.json", "{$dir}ft.json");
    }
    // TODO: message?
    return $this;
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
    // TODO: message?
    return $this;
  }

  private function registerCommands(): self {
    if ($canUse = ConfigData::make()->canUseCommands()) {
      $map = $this->getServer()->getCommandMap();
      $commands = [
        new TxtCommand,
        new TxtAdmCommand
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
    $uftd = UnremovableFloatingTextData::make()->getAll();
    $ftd = FloatingTextData::make()->getAll();
    $pool = $this->getServer()->getAsyncPool();
    $pool->submitTask(new AsyncPrepareTextsTask($uftd, AsyncPrepareTextsTask::TYPE_UNREMOVABLE));
    $pool->submitTask(new AsyncPrepareTextsTask($ftd, AsyncPrepareTextsTask::TYPE_REMOVABLE));
    return $this;
  }

  /**
   * @return Core
   */
  public static function get(): Core {
    return self::$core;
  }
}