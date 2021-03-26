<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\mineflow\variable;

use aieuo\mineflow\variable\DummyVariable;
use aieuo\mineflow\variable\ListVariable;
use aieuo\mineflow\variable\object\Vector3ObjectVariable;
use aieuo\mineflow\variable\ObjectVariable;
use aieuo\mineflow\variable\StringVariable;
use aieuo\mineflow\variable\Variable;
use jp\mcbe\fuyutsuki\Texter\text\FloatingTextCluster;

/**
 * Class FloatingTextObjectVariable
 * @package jp\mcbe\fuyutsuki\Texter\mineflow\variable
 */
class FloatingTextObjectVariable extends ObjectVariable {

	public const DEFAULT_NAME = "ft";

	public const PROPERTY_POSITION = "pos";
	public const PROPERTY_NAME = "name";
	public const PROPERTY_SPACING = "spacing";
	public const PROPERTY_TEXTS = "texts";

	public function __construct(FloatingTextCluster $floatingTextCluster, ?string $str = null) {
		parent::__construct($floatingTextCluster, $str);
	}

	public function getValueFromIndex(string $index): ?Variable {
		$floatingTextCluster = $this->floatingText();
		switch ($index) {
			case self::PROPERTY_POSITION:
				$variable = new Vector3ObjectVariable($floatingTextCluster->position(), self::PROPERTY_POSITION);
				break;
			case self::PROPERTY_NAME:
				$variable = new StringVariable($floatingTextCluster->name());
				break;
			case self::PROPERTY_SPACING:
				$variable = new Vector3ObjectVariable($floatingTextCluster->spacing(), self::PROPERTY_SPACING);
				break;
			case self::PROPERTY_TEXTS:
				$texts = [];
				foreach ($floatingTextCluster->all() as $floatingText) {
					$texts[] = new StringVariable($floatingText->text());
				}
				$variable = new ListVariable($texts, self::PROPERTY_TEXTS);
				break;
			default:
				$variable = null;
		}
		return $variable;
	}

	public function floatingText(): FloatingTextCluster {
		/** @var FloatingTextCluster $value */
		$value = $this->getValue();
		return $value;
	}

	public static function getValuesDummy(): array {
		return [
			self::PROPERTY_POSITION => new DummyVariable(DummyVariable::VECTOR3),
			self::PROPERTY_NAME => new DummyVariable(DummyVariable::STRING),
			self::PROPERTY_SPACING => new DummyVariable(DummyVariable::VECTOR3),
			self::PROPERTY_TEXTS => new DummyVariable(DummyVariable::LIST),
		];
	}

}