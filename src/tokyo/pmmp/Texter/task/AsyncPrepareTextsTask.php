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

namespace tokyo\pmmp\Texter\task;

use pocketmine\level\Position;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use tokyo\pmmp\Texter\data\Data;
use tokyo\pmmp\Texter\data\FloatingTextData;
use tokyo\pmmp\Texter\text\FloatingText;
use tokyo\pmmp\Texter\text\UnremovableFloatingText;

/**
 * Class AsyncPrepareTextsTask
 * @package tokyo\pmmp\Texter\task
 */
class AsyncPrepareTextsTask extends AsyncTask {

  /** @var int */
  public const TYPE_UNREMOVABLE = 0;
  public const TYPE_REMOVABLE = 1;

  /** @var string */
  private $serialized;
  /** @var int */
  private $type;

  public function __construct(array $data, int $type) {
    if (empty($data)) {
      $this->cancelRun();
    }else {
      $this->serialized = serialize($data);
      $this->type = $type;
    }
  }

  public function onRun(): void {
    $data = unserialize($this->serialized);
    $result = [];
    switch ($this->type) {
      case self::TYPE_UNREMOVABLE:
        foreach ($data as $level => $ufts) {
          foreach ($ufts as $name => $val) {
            $result[$level][$name] = new UnremovableFloatingText(
              $name,
              new Position($val[Data::KEY_X], $val[Data::KEY_Y], $val[Data::KEY_Z]),
              $val[Data::KEY_TITLE],
              $val[Data::KEY_TEXT]
            );
          }
        }
        break;

      case self::TYPE_REMOVABLE:
        foreach ($data as $level => $fts) {
          foreach ($fts as $name => $val) {
            $result[$level][$name] = new FloatingText(
              $name,
              new Position($val[Data::KEY_X], $val[Data::KEY_Y], $val[Data::KEY_Z]),
              $val[Data::KEY_TITLE],
              $val[Data::KEY_TEXT],
              $val[FloatingTextData::KEY_OWNER]
            );
          }
        }
        break;
    }
    $this->setResult($result);
  }

  public function onCompletion(Server $server) {
    $result = $this->getResult();
    $this->loadLevels($server, $result);
    var_dump($result);
    // register to TexterApi
  }

  private function loadLevels(Server $server, array $uftd): void {
    foreach ($uftd as $levelName => $ufts) {
      foreach ($ufts as $name => $uft) {
        if (!$server->isLevelLoaded($levelName)) $server->loadLevel($levelName);
        $uft->level = $server->getLevelByName($levelName);
      }
    }
  }
}