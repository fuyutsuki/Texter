<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\command\form;

use jp\mcbe\fuyutsuki\Texter\i18n\TexterLang;
use jp\mcbe\fuyutsuki\Texter\util\StringArrayMultiton;
use pocketmine\math\Vector3;

/**
 * Class FloatingTextSession
 * @package jp\mcbe\fuyutsuki\Texter\command\session
 */
class FloatingTextSession {

	use StringArrayMultiton {
		StringArrayMultiton::__construct as stringArrayMultitonConstruct;
		StringArrayMultiton::getInstance as get;
		StringArrayMultiton::removeInstance as remove;
	}

	/** @var TexterLang */
	private $lang;

	/** @var string */
	private $name = "";
	/** @var Vector3 */
	private $spacing;
	/** @var string[] */
	private $texts = [];

	/** @var bool */
	private $hasNoTexts = false;
	/** @var bool */
	private $isDuplicateName = false;
	/** @var bool */
	private $isEdit = false;

	public function __construct(string $key, TexterLang $lang) {
		$this->stringArrayMultitonConstruct($key);
		$this->lang = $lang;
		$this->setSpacing();
	}

	public function lang(): TexterLang {
		return $this->lang;
	}

	public function name(): string {
		return $this->name;
	}

	public function setName(string $name) {
		$this->name = $name;
	}

	public function spacing(): Vector3 {
		return $this->spacing;
	}

	public function setSpacing(?Vector3 $spacing = null) {
		$this->spacing = $spacing ?? new Vector3;
	}

	public function texts(): array {
		return $this->texts;
	}

	public function addText(string $text) {
		$this->texts[] = $text;
	}

	public function setTexts(array $texts) {
		$this->texts = $texts;
	}

	public function hasNoTexts(): bool {
		return $this->hasNoTexts;
	}

	public function setNoTexts(bool $value = true) {
		$this->hasNoTexts = $value;
	}

	public function isDuplicateName(): bool {
		return $this->isDuplicateName;
	}

	public function setDuplicateName(bool $value = true) {
		$this->isDuplicateName = $value;
	}

	public function isEdit(): bool {
		return $this->isEdit;
	}

	public function setEdit(bool $value = true) {
		$this->isEdit = $value;
	}

}