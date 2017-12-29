<?php
namespace Texter\scheduler;

// Pocetmine
use pocketmine\{
  scheduler\PluginTask,
  utils\TextFormat as TF
};

// Texter
use Texter\{
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
      $tmpCrft = $this->crft[$this->keyCrft];
      $world = $this->core->getServer()->getLevelByName($tmpCrft["WORLD"]);
      $x = $tmpCrft["Xvec"];
      $y = $tmpCrft["Yvec"];
      $z = $tmpCrft["Zvec"];
      $title = $tmpCrft["TITLE"];
      $text = $tmpCrft["TEXT"];
      $crft = new CRFT($title, $text, $world, $x, $y, $z);
      $this->processedCrfts[$crft->getId()] = $crft;
    }elseif (array_key_exists($this->keyFt, $this->fts)) {
      $tmpFt = $this->ft[$this->keyFt];
      $world = $this->core->getServer()->getLevelByName($tmpFt["WORLD"]);
      $x = $tmpFt["Xvec"];
      $y = $tmpFt["Yvec"];
      $z = $tmpFt["Zvec"];
      $title = $tmpFt["TITLE"];
      $text = $tmpFt["TEXT"];
      $ft = new FT($title, $text, $world, $x, $y, $z);
      $this->processedFts[$ft->getId()] = $ft;
    }else {
      $this->onComplete();
    }
  }

  private function onComplete(): void{
    $this->core->getLang()->translateString("on.enable.prepared", [
      ++$keyCrft,
      ++$keyFt
    ]);
    $this->core->getLogger()->info(TF::GREEN.$message);
  }
}
