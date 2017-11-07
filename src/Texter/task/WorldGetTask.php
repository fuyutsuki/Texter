<?php

namespace Texter\task;

# Pocketmine
use pocketmine\Player;
use pocketmine\scheduler\PluginTask;

# Texter
use Texter\Main;
use Texter\text\{
  CantRemoveFloatingText as CRFT,
  FloatingText as FT};

/**
 * 1秒遅らせて移動後のワールドを取得するタスク
 */
class WorldGetTask extends PluginTask{

  public function __construct(Main $main, Player $p){
    parent::__construct($main);
    $this->api = $main->getApi();
    $this->p = $p;
  }

  public function onRun(int $tick){
    $p = $this->p;
    $lev = $p->getLevel();
    //
    $crfts = $this->api->getCrftsByLevel($lev);
    if (!empty($crfts)) {
      foreach ($crfts as $crft) {
        $crft->sendToPlayer($p, CRFT::SEND_TYPE_ADD);
      }
    }
    $fts = $this->api->getFtsByLevel($lev);
    if (!empty($fts)) {
      foreach ($fts as $ft) {
        $ft->sendToPlayer($p, FT::SEND_TYPE_ADD);
      }
    }
  }
}
