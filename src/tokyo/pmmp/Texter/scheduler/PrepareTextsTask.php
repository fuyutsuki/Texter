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
      $title = $tmpCrft["TITLE"];
      $text = $tmpCrft["TEXT"];
      $x = $tmpCrft["Xvec"];
      $y = $tmpCrft["Yvec"];
      $z = $tmpCrft["Zvec"];
      $level = $this->core->getServer()->getLevelByName($tmpCrft["WORLD"]);
      $crft = new CRFT($title, $text, $x, $y, $z, $level);
      $this->processedCrfts[$crft->getId()] = $crft;
    }elseif (array_key_exists($this->keyFt, $this->fts)) {
      $tmpFt = $this->ft[$this->keyFt];
      $title = $tmpFt["TITLE"];
      $text = $tmpFt["TEXT"];
      $x = $tmpFt["Xvec"];
      $y = $tmpFt["Yvec"];
      $z = $tmpFt["Zvec"];
      $world = $this->core->getServer()->getLevelByName($tmpFt["WORLD"]);
      $ft = new FT($title, $text, $x, $y, $z, $world);
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
