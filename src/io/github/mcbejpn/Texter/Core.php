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
 * TexterはPocketMine-MP向けのFloatingTextPerticleを表示するプラグインです。
 * Copyright (c) 2018 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * このソフトウェアは"MITライセンス"下で配布されています。
 * あなたはこのプログラムと共にMITライセンスのコピーを受け取ったはずです。
 * 受け取っていない場合、下記のURLからご覧ください。
 * < https://opensource.org/licenses/mit-license >
 */

namespace io\github\mcbejpn\Texter;

// pocketmine
use pocketmine\{
  plugin\PluginBase,
  lang\BaseLang
};

// texter
use io\github\mcbejpn\Texter\{
  texts\Text,
  texts\CantRemoveFloatingText as CRFT,
  texts\FloatingText as FT
};

/**
 * TexterCore
 */
class Core extends PluginBase {

  public const CODENAME = "Phyllorhiza punctata";

  /** @var string */
  public $dir = "";
  /** @var ?TextsDataManager */
  private $tdm = null;
  /** @var ?TexterApi */
  private $api = null;
  /** @var ?BaseLang */
  private $lang = null;

  /**
   * @return ?TextsDataManager
   */
  public function getTextsDataManager(): ?TextsDataManager {
    return $this->tdm;
  }

  /**
   * @return ?TexterApi
   */
  public function getApi(): ?TexterApi {
    return $this->api;
  }

  /**
   * @return ?BaseLang
   */
  public function getLang(): ?BaseLang {
    return $this->lang;
  }

  public function onLoad() {
    $this->initDataManager();
    $this->initApi();
    $this->initLang();
    $this->registerCommands();
    $this->checkUpdate();
    $this->prepareTexts();
    $this->setTimezone();
  }

  public function onEnable() {
    $listener = new EventListener($this);
    $this->getServer()->getPluginManager()->registerEvents($listener, $this);
  }

  /**
   * @return void
   */
  private function initDataManager(): void {
    $this->tdm = new TextsDataManager($this);
  }

  /**
   * @return void
   */
  private function initApi(): void {
    $this->api = new TexterApi($this);
  }

  /**
   * @return void
   */
  private function initLang(): void {
    $langCode = $this->tdm->getLangCode();
  }
}
