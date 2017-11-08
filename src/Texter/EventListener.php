<?php
namespace Texter;

# Pocketmine
use pocketmine\Player;
use pocketmine\event\{
  Listener,
  player\PlayerJoinEvent,
  entity\EntityLevelChangeEvent};

# Texter
use Texter\task\WorldGetTask;
use Texter\text\{
  CantRemoveFloatingText as CRFT,
  FloatingText as FT};

/**
 * EventListener
 */
class EventListener implements Listener{

  /** @var Main $main */
  private $main = null;
  /** @var TexterApi $api */
  private $api = null;

  public function __construct(Main $main){
    $this->main = $main;
    $this->api = $main->getApi();
  }

  public function onJoin(PlayerJoinEvent $e){
    $p = $e->getPlayer();
    $lev = $p->getLevel();
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

  public function onLevelChange(EntityLevelChangeEvent $e){
    $p = $e->getEntity();
    if ($p instanceof Player) {
      $lev = $p->getLevel();
      $crfts = $this->api->getCrftsByLevel($lev);
      if (!empty($crfts)) {
        foreach ($crfts as $crft) {
          $crft->sendToPlayer($p, CRFT::SEND_TYPE_REMOVE);
        }
      }
      $fts = $this->api->getFtsByLevel($lev);
      if (!empty($fts)) {
        foreach ($fts as $ft) {
          $ft->sendToPlayer($p, FT::SEND_TYPE_REMOVE);
        }
      }
      $task = new WorldGetTask($this->main, $p);
      $this->main->getServer()->getScheduler()->scheduleDelayedTask($task, 20);
    }
  }
}
