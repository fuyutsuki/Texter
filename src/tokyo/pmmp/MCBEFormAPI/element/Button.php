<?php

/**
 * // English
 *
 * MCBEFormAPI is a plugin for PocketMine-MP for easy operation of forms
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
 * MCBEFormAPIは、フォームを簡単に操作するためのpocketmine-MP向けプラグインです
 * Copyright (c) 2018 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * このソフトウェアは"MITライセンス"下で配布されています。
 * あなたはこのプログラムと共にMITライセンスのコピーを受け取ったはずです。
 * 受け取っていない場合、下記のURLからご覧ください。
 * < https://opensource.org/licenses/mit-license >
 */

namespace tokyo\pmmp\MCBEFormAPI\element;

// mcbeformapi
use tokyo\pmmp\MCBEFormAPI\{
  form\Form
};

/**
 * ButtonClass
 */
class Button {

  /** @var string */
  protected const ELEMENT_NAME = "button";
  /** @var string */
  public const IMAGE_TYPE_PATH = "path";
  public const IMAGE_TYPE_URL = "url";

  /** @var string */
  protected $imageType = "";
  /** @var string */
  protected $imagePath = "";
  /** @var string */
  public $text = "";

  public function __construct(string $text) {
    $this->text = $text;
  }

  public function setImage(string $imagePath, string $imageType = self::IMAGE_TYPE_PATH): Button {
    switch ($imageType) {
      case self::IMAGE_TYPE_PATH:
      case self::IMAGE_TYPE_URL:
        $this->$imagePath = $imagePath;
        $this->$imageType = $imageType;
      break;

      default:
        throw new \InvalidArgumentException("The image type must be an string value Button::IMAGE_TYPE_PATH or Button::IMAGE_TYPE_URL");
      break;
    }
    return $this;
  }

  public function format(): array {
    $data = [
      Form::KEY_TYPE => self::ELEMENT_NAME,
      Form::KEY_TEXT => $this->text
    ];
    if (!empty($this->imageType)) {
      $data[Form::KEY_IMAGE] = [
        Form::KEY_TYPE => $this->imageType,
        Form::KEY_DATA => $this->imagePath
      ];
    }
    return $data;
  }
}
