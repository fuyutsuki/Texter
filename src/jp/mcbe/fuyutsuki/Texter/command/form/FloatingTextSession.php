<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\command\form;

use jp\mcbe\fuyutsuki\Texter\i18n\TexterLang;
use jp\mcbe\fuyutsuki\Texter\util\StringArrayMultiton;
use pocketmine\math\Vector3;

class FloatingTextSession {

	use StringArrayMultiton {
		StringArrayMultiton::__construct as stringArrayMultitonConstruct;
		StringArrayMultiton::getInstance as get;
		StringArrayMultiton::removeInstance as remove;
	}

	private string $name = "";
	private Vector3 $spacing;
	/** @var string[] */
	private array $texts = [];

	private bool $hasNoTexts = false;
	private bool $isDuplicateName = false;
	private bool $isEdit = false;

	public function __construct(
		string $key,
		private TexterLang $lang
	) {
		$this->stringArrayMultitonConstruct($key);
		$this->setSpacing();
	}

	public function lang(): TexterLang {
		return $this->lang;
	}

	public function name(): string {
		return $this->name;
	}

	public function setName(string $name): void {
		$this->name = $name;
	}

	public function spacing(): Vector3 {
		return $this->spacing;
	}

	public function setSpacing(?Vector3 $spacing = null): void {
		$this->spacing = $spacing ?? Vector3::zero();
	}

	public function texts(): array {
		return $this->texts;
	}

	public function addText(string $text): void {
		$this->texts[] = $text;
	}

	public function setTexts(array $texts): void {
		$this->texts = $texts;
	}

	public function hasNoTexts(): bool {
		return $this->hasNoTexts;
	}

	public function setNoTexts(bool $value = true): void {
		$this->hasNoTexts = $value;
	}

	public function isDuplicateName(): bool {
		return $this->isDuplicateName;
	}

	public function setDuplicateName(bool $value = true): void {
		$this->isDuplicateName = $value;
	}

	public function isEdit(): bool {
		return $this->isEdit;
	}

	public function setEdit(bool $value = true): void {
		$this->isEdit = $value;
	}

}