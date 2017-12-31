<?php
namespace tokyo\pmmp\Texter\scheduler;

// Pocetmine
use pocketmine\{
  math\Vector3,
  scheduler\PluginTask,
  utils\TextFormat as TF
};

// Texter
use tokyo\pmmp\Texter\{
  Core,
  text\CantRemoveFloatingText as CRFT,
  text\FloatingText as FT
};

/**
 * @link prepareTexts()
 */
class PrepareTextsTask extends PluginTask {

  /** @var ?Core */
  private $core = null;
  /** @var mixed[] */
  private $crfts = [];
  /** @var mixed[] */
  private $fts = [];
  /** @var int */
  private $keyCrft = 0;
  /** @var int */
  private $keyFt = 0;
  /** @var CantRemoveFloatingText[] */
  private $processedCrfts = [];
  /** @var FloatingText[] */
  private $processedFts = [];

  public function __construct(Core $core, array $crfts, array $fts) {
    parent::__construct($core);
    $this->core = $core;
    $this->crfts = array_values($crfts);
    $this->fts = array_values($fts);
  }

  public function onRun(int $tick) {
    if (array_key_exists($this->keyCrft, $this->crfts)) {
      $tmpCrft = $this->crfts[$this->keyCrft];
      $title = $tmpCrft["TITLE"];
      $text = $tmpCrft["TEXT"];
      $pos = new Vector3($tmpCrft["Xvec"], $tmpCrft["Yvec"], $tmpCrft["Zvec"]);
      $level = $this->core->getServer()->getLevelByName($tmpCrft["WORLD"]);
      $crft = new CRFT($level, $title, $text, $pos);
      $this->processedCrfts[$crft->getEid()] = $crft;
      ++$this->keyCrft;
    }elseif (array_key_exists($this->keyFt, $this->fts)) {
      $tmpFt = $this->fts[$this->keyFt];
      $title = $tmpFt["TITLE"];
      $text = $tmpFt["TEXT"];
      $pos = new Vector3($tmpCrft["Xvec"], $tmpCrft["Yvec"], $tmpCrft["Zvec"]);
      $level = $this->core->getServer()->getLevelByName($tmpFt["WORLD"]);
      $ft = new FT($level, $title, $text, $pos);
      $this->processedFts[$ft->getEid()] = $ft;
      ++$this->keyFt;
    }else {
      $this->onComplete();
    }
  }

  private function onComplete(): void {
    $message = $this->core->getLang()->translateString("on.enable.prepared", [
      count($this->processedCrfts),
      count($this->processedFts)
    ]);
    $this->core->getLogger()->info(TF::GREEN.$message);
    $api = $this->core->getApi();
    $api->registerTexts($this->processedCrfts);
    $api->registerTexts($this->processedFts);
    $this->core->getServer()->getScheduler()->cancelTask($this->getTaskId());
  }
}
