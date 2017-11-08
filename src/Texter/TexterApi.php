<?php

/**
 * ## English
 *
 * Texter, the display FloatingTextPerticle plugin for PocketMine-MP
 * Copyright (c) 2017 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * Released under the "MIT license".
 * You should have received a copy of the MIT license
 * along with this program.  If not, see
 * < https://opensource.org/licenses/mit-license >.
 *
 * ---------------------------------------------------------------------
 * ## 日本語
 *
 * TexterはPocketMine-MP向けのFloatingTextPerticleを表示するプラグインです。
 * Copyright (c) 2017 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * このソフトウェアは"MITライセンス"下で配布されています。
 * あなたはこのプログラムと共にMITライセンスのコピーを受け取ったはずです。
 * 受け取っていない場合、下記のURLからご覧ください。
 * < https://opensource.org/licenses/mit-license >
 */

namespace Texter;

# Pocketmine
use pocketmine\Player;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\level\{
  Level,
  Position};
use pocketmine\math\Vector3;
use pocketmine\utils\{
  TextFormat as TF,
  UUID};

# Texter
use Texter\Main;
use Texter\language\Lang;
use Texter\text\{
  CantRemoveFloatingText as CRFT,
  FloatingText as FT};
use Texter\utils\TunedConfig as Config;

/**
 * TexterApi
 */
class TexterApi{

  /** @var TexterApi */
  private static $instance = null;
  /** @var Config $crft_config */
  private $crft_config = null;
  /** @var Config $ft_config */
  private $ft_config = null;
  /** @var array $crfts[$levelName][] = $pk */
  private $crfts = [];
  /** @var array $ft[$levelName][] = $pk */
  private $fts = [];

  public function __construct(Main $main){
    self::$instance = $this;
    $this->main = $main;
    $this->language = Lang::getInstance();
    $this->crft_config = new Config($main->getDataFolder().Main::FILE_CRFT, Config::JSON);
    $this->crft_config->setAll([]);
    $this->ft_config = new Config($main->getDataFolder().Main::FILE_FT, Config::JSON);
    $this->ft_config->setAll([]);
  }

  /****************************************************************************/
  /* get/set(情報取得/変更) 関連 */
  /**
   * インスタンスを取得
   * @return TexterApi
   */
  public static function getInstance(): TexterApi{
    return self::$instance;
  }

  /**
   * 現在使用中の言語を取得します
   * @return string "eng"|"jpn"
   */
  public function getLanguage(): string{
    return $this->language->getLang();
  }

  /**
   * テキストをファイルに保存します
   * @param  CRFT $text
   * @param  bool $new  新規かどうか
   * @return bool
   */
  public function saveCrft(CRFT $text, bool $new = false): bool{
    $levelName = $text->level->getName();
    $key = $levelName . $text->z . $text->x . $text->y;
    if ($new) { // 新規
      if ($this->crft_config->exists($key)) { // 既設
        return false;
      }else {
        $this->crfts[$levelName][$text->eid] = $text;
      }
    }
    $data = [
      "WORLD" => $levelName,
      "Xvec"  => sprintf('%0.1f', $text->x),
      "Yvec"  => sprintf('%0.1f', $text->y),
      "Zvec"  => sprintf('%0.1f', $text->z),
      "TITLE" => $text->title,
      "TEXT"  => $text->text
    ];
    $this->crft_config->set($key, $data);
    $this->crft_config->save();
    return true;
  }

  /**
   * テキストをファイルに保存します
   * @param  FT     $text
   * @param  bool   $new  新規かどうか
   * @return bool
   */
  public function saveFt(FT $text, bool $new = false): bool{
    $levelName = $text->level->getName();
    $key = $levelName . $text->z . $text->x . $text->y;
    if ($new) { // 新規
      if ($this->ft_config->exists($key)) { // 既設
        return false;
      }else {
        $this->fts[$levelName][$text->eid] = $text;
      }
    }
    $data = [
      "WORLD" => $levelName,
      "Xvec"  => sprintf('%0.1f', $text->x),
      "Yvec"  => sprintf('%0.1f', $text->y),
      "Zvec"  => sprintf('%0.1f', $text->z),
      "TITLE" => $text->title,
      "TEXT"  => $text->text,
      "OWNER" => $text->owner
    ];
    $this->ft_config->set($key, $data);
    $this->ft_config->save();
    return true;
  }

  /**
   * テキストをファイルから消去します
   * @param  FT   $text
   * @return bool true
   */
  public function removeText(FT $text): bool{
    $levelName = $text->level->getName();
    $key = $levelName . $text->z . $text->x . $text->y;
    $this->ft_config->remove($key);
    $this->ft_config->save();
    unset($this->fts[$levelName][$text->eid]);
    return true;
  }

  /**
   * すべてのcrftを返します
   * @return array $this->crfts
   */
  public function getCrfts(): array{
    return $this->crfts;
  }

  /**
   * 指定されたワールドのすべてのcrftを返します
   * @param  Level      $level
   * @return array
   */
  public function getCrftsByLevel(Level $level): array{
    $levelName = $level->getName();
    if (!isset($this->crfts[$levelName])) {
      return [];
    }else {
      return $this->crfts[$levelName];
    }
  }

  /**
   * 指定されたワールドのすべてのcrftを返します
   * @param  string     $levelName
   * @return array
   */
  public function getCrftsByLevelName(string $levelName): array{
    if (!isset($this->crfts[$levelName])) {
      return [];
    }else {
      return $this->crfts[$levelName];
    }
  }

  /**
   * 指定されたワールド, eidのcrftを取得します
   * @param  string $levelName
   * @param  int    $entityId
   * @return null|CRFT
   */
  public function getCrft(string $levelName, int $entityId){
    if (!isset($this->crfts[$levelName][$entityId])) {
      return null;
    }else{
      return $this->crfts[$levelName][$entityId];
    }
  }

  /**
   * crftの個数を返します
   * @return int
   */
  public function getCrftsCount(): int{
    $cc = 0;
    $crfts = $this->getCrfts();
    if ($crfts !== false) {
      foreach ($crfts as $levCrfts) {
        foreach ($levCrfts as $crft) {
          ++$cc;
        }
      }
    }
    return $cc;
  }

  /**
   * すべてのftを返します
   * @return array $this->fts
   */
  public function getFts(): array{
    return $this->fts;
  }

  /**
   * 指定されたワールドのすべてのftを返します
   * @param  Level      $level
   * @return array
   */
  public function getFtsByLevel(Level $level): array{
    $levelName = $level->getName();
    if (!isset($this->fts[$levelName])) {
      return [];
    }else {
      return $this->fts[$levelName];
    }
  }

  /**
   * 指定されたワールドのすべてのftを返します
   * @param  string     $levelName
   * @return array
   */
  public function getFtsByLevelName(string $levelName): array{
    if (!isset($this->fts[$levelName])) {
      return [];
    }else {
      return $this->fts[$levelName];
    }
  }

  /**
   * 指定されたユーザー名所有のftをすべて取得します
   * @param  string $name
   * @return array
   */
  public function getFtsByName(string $name): array{
    $name = strtolower($name);
    $fts = $this->getFts();
    if (empty($fts)) {
      return [];
    }else {
      $return = [];
      foreach ($fts as $levFts) {
        foreach ($levFts as $ft) {
          if ($ft->owner === $name) {
            $return[] = $ft;
          }
        }
      }
      return $return;
    }
  }

  /**
   * 指定されたワールド,eidのftを取得します
   * @param  string $levelName
   * @param  int    $entityId
   * @return null|FT
   */
  public function getFt(string $levelName, int $entityId){
    if (!isset($this->fts[$levelName][$entityId])) {
      return null;
    }else{
      return $this->fts[$levelName][$entityId];
    }
  }

  /**
   * ftの個数を返します
   * @return int
   */
  public function getFtsCount(): int{
    $fc = 0;
    $fts = $this->getFts();
    if ($fts !== false) {
      foreach ($fts as $levFts) {
        foreach ($levFts as $fts) {
          ++$fc;
        }
      }
    }
    return $fc;
  }
}
