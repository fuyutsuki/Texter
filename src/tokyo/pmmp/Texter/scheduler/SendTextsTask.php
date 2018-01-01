<?php
namespace tokyo\pmmp\Texter\scheduler;

// Pocetmine
use pocketmine\{
  Player,
  level\Level,
  level\Position,
  scheduler\PluginTask,
  utils\TextFormat as TF
};

// Texter
use tokyo\pmmp\Texter\{
  Core,
  text\CantRemoveFloatingText as CRFT,
  text\FloatingText as FT,
  text\Text
};

/**
 * @link EventListener
 */
class SendTextsTask extends PluginTask {

  /** @var ?Core */
  private $core = null;
  /** @var ?Player */
  private $player = null;
  /** @var ?Level */
  private $level = null;
  /** @var CantRemoveFloatingText[] */
  private $crfts = [];
  /** @var int */
  private $keyCrfts = 0;
  /** @var FloatingText[] */
  private $fts = [];
  /** @var int */
  private $keyFts = 0;

  public function __construct(Core $core, Player $player, Level $level) {
    parent::__construct($core);
    $this->core = $core;
    $this->player = $player;
    $this->level = $level;
    $api = $this->core->getApi();
    $this->crfts = array_values($api->getCrftsByLevel($level));
    $this->fts   = array_values($api->getFtsByLevel($level));
  }

  public function onRun(int $tick) {
    if (array_key_exists($this->keyCrfts, $this->crfts)) {
      $this->crfts[$this->keyCrfts]->sendToPlayer(Text::SEND_TYPE_ADD, $this->player);
      ++$this->keyCrfts;
    }elseif (array_key_exists($this->keyFts, $this->fts)) {
      $this->fts[$this->keyFts]->sendToPlayer(Text::SEND_TYPE_ADD, $this->player);
      ++$this->keyFts;
    }else {
      $this->onComplete();
    }
  }

  private function onComplete(): void {
    $this->core->getServer()->getScheduler()->cancelTask($this->getTaskId());
  }
}
