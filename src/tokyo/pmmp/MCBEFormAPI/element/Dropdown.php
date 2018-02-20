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
 * DropdownClass
 */
class Dropdown extends Element {

  /** @var string */
  protected const ELEMENT_NAME = "dropdown";

  /** @var string[] */
  protected $options = [];
  /** @var int */
  protected $defaultKey = 0;

  public function __construct(string $text, array $options = []) {
    $this->text = $text;
    $this->options = $options;
  }

  public function addOption(string $option, $isDefault = false): Dropdown {
    $this->defaultKey = $isDefault ? count($this->options) : $this->defaultKey;
    $this->options[] = $option;
    return $this;
  }

  public function removeOption(string $option): Dropdown {
    $flip = array_flip($this->options);
    if (array_key_exists($option, $flip)) {
      $key = $flip[$option];
      unset($this->options[$key]);
    }
    return $this;
  }

  public function getDefault(): string {
    return empty($this->options)? "" : $this->options[$this->defaultKey];
  }

  public function setDefault(string $option): Dropdown {
    $flip = array_flip($this->options);
    if (array_key_exists($option, $flip)) {
      $key = $flip[$option];
      $this->defaultKey = $key;
    }else {
      throw new \OutOfRangeException("Invalid option " . $option);
    }
  }

  public function format(): array {
    $data = [
      Form::KEY_TYPE => self::ELEMENT_NAME,
      Form::KEY_TEXT => $this->text,
      Form::KEY_OPTIONS => $this->options,
      Form::KEY_DEFAULT => $this->defaultKey
    ];
    return $data;
  }
}
