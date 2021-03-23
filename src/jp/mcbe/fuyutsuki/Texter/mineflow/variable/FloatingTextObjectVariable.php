<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\mineflow\variable;

use aieuo\mineflow\variable\DummyVariable;
use aieuo\mineflow\variable\object\PositionObjectVariable;
use aieuo\mineflow\variable\ObjectVariable;
use aieuo\mineflow\variable\StringVariable;
use aieuo\mineflow\variable\Variable;
use jp\mcbe\fuyutsuki\Texter\text\FloatingTextCluster;
use pocketmine\level\Position;

/**
 * Class FloatingTextObjectVariable
 * @package jp\mcbe\fuyutsuki\Texter\mineflow\variable
 */
class FloatingTextObjectVariable extends ObjectVariable {

	public const DEFAULT_NAME = "ft";

	public const PROPERTY_NAME = "name";
	public const PROPERTY_POSITION = "pos";
	public const PROPERTY_SPACING = "spacing";

	public function __construct(FloatingTextCluster $floatingText, string $name = self::DEFAULT_NAME, ?string $str = null) {
		parent::__construct($floatingText, $name, $str);
	}

	public function getValueFromIndex(string $index): ?Variable {
		$floatingText = $this->floatingText();
		switch ($index) {
			case self::PROPERTY_NAME:
				$variable = new StringVariable($floatingText->name(), self::PROPERTY_NAME);
				break;
			case self::PROPERTY_POSITION:// TODO: Mineflow should implements Vector3ObjectVariable
				$variable = new PositionObjectVariable(Position::fromObject($floatingText->position()), self::PROPERTY_POSITION);
				break;
			case self::PROPERTY_SPACING:// TODO: Mineflow should implements Vector3ObjectVariable
				$variable = new PositionObjectVariable(Position::fromObject($floatingText->spacing()), self::PROPERTY_SPACING);
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

	public static function getValuesDummy(string $name): array {
		return [
			new DummyVariable("{$name}." . self::PROPERTY_NAME, DummyVariable::STRING),
			new DummyVariable("{$name}." . self::PROPERTY_POSITION, DummyVariable::POSITION),
			new DummyVariable("{$name}." . self::PROPERTY_SPACING, DummyVariable::POSITION)
		];
	}

}