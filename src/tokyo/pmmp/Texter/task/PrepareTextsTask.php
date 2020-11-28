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

namespace tokyo\pmmp\Texter\task;

use pocketmine\level\Position;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use tokyo\pmmp\Texter\Core;
use tokyo\pmmp\Texter\data\Data;
use tokyo\pmmp\Texter\data\FloatingTextData;
use tokyo\pmmp\Texter\data\UnremovableFloatingTextData;
use tokyo\pmmp\Texter\i18n\Lang;
use tokyo\pmmp\Texter\text\FloatingText;
use tokyo\pmmp\Texter\text\UnremovableFloatingText;
use tokyo\pmmp\Texter\TexterApi;
use function count;

/**
 * Class PrepareTextsTask
 * @package tokyo\pmmp\Texter\task
 */
class PrepareTextsTask extends Task {

  /** @var Server */
  private $server;
  /** @var array */
  private $ufts;
  /** @var int */
  private $uftsCount = 0;
  /** @var int */
  private $uftsMax;
  /** @var array */
  private $fts;
  /** @var int */
  private $ftsCount = 0;
  /** @var int */
  private $ftsMax;

  public function __construct() {
    $this->server = Server::getInstance();
    $this->ufts = UnremovableFloatingTextData::make()->getData();
    $this->uftsMax = count($this->ufts);
    $this->fts = FloatingTextData::make()->getData();
    $this->ftsMax = count($this->fts);
  }

  public function onRun(int $tick) {
    if ($this->uftsCount === $this->uftsMax) {
      if ($this->ftsCount === $this->ftsMax) {
        $this->onSuccess();
      }else {
        $data = $this->fts[$this->ftsCount];
        $textName = $data[Data::KEY_NAME];
        $loaded = Server::getInstance()->isLevelLoaded($data[Data::KEY_LEVEL]);
        $canLoad = true;
        if (!$loaded) $canLoad = $this->server->loadLevel($data[Data::KEY_LEVEL]);
        if ($canLoad) {
          $level = $this->server->getLevelByName($data[Data::KEY_LEVEL]);
          if ($level !== null) {
            $x = $data[Data::KEY_X];
            $y = $data[Data::KEY_Y];
            $z = $data[Data::KEY_Z];
            $pos = new Position($x, $y, $z, $level);
            $title = $data[Data::KEY_TITLE];
            $text = $data[Data::KEY_TEXT];
            $owner = $data[FloatingTextData::KEY_OWNER];
            $ft = new FloatingText($textName, $pos, $title, $text, $owner);
            TexterApi::registerText($ft);
          }
        }
        ++$this->ftsCount;
      }
    }else {
      $data = $this->ufts[$this->uftsCount];
      $textName = $data[Data::KEY_NAME];
      $loaded = $this->server->isLevelLoaded($data[Data::KEY_LEVEL]);
      $canLoad = true;
      if (!$loaded) $canLoad = $this->server->loadLevel($data[Data::KEY_LEVEL]);
      if ($canLoad) {
        $level = $this->server->getLevelByName($data[Data::KEY_LEVEL]);
        if ($level !== null) {
          $x = $data[Data::KEY_X];
          $y = $data[Data::KEY_Y];
          $z = $data[Data::KEY_Z];
          $pos = new Position($x, $y, $z, $level);
          $title = $data[Data::KEY_TITLE];
          $text = $data[Data::KEY_TEXT];
          $uft = new UnremovableFloatingText($textName, $pos, $title, $text);
          TexterApi::registerText($uft);
        }
      }
      ++$this->uftsCount;
    }
  }

  private function onSuccess(): void {
    $plugin = $this->server->getPluginManager()->getPlugin("Texter");
    if ($plugin !== null && $plugin->isEnabled()) {
      $message = Lang::fromConsole()->translateString("on.enable.prepared", [
        count(TexterApi::getUfts(), COUNT_RECURSIVE) - count(TexterApi::getUfts()),
        count(TexterApi::getFts(), COUNT_RECURSIVE) - count(TexterApi::getFts())
      ]);
      $core = Core::get();
      $core->getLogger()->info(TextFormat::GREEN . $message);
      $core->getScheduler()->cancelTask($this->getTaskId());
    }
  }
}
