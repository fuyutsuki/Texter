<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\command\sub;

use jp\mcbe\fuyutsuki\Texter\data\FloatingTextData;
use jp\mcbe\fuyutsuki\Texter\i18n\TexterLang;
use jp\mcbe\fuyutsuki\Texter\Main;
use jp\mcbe\fuyutsuki\Texter\text\SendType;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

/**
 * Class MoveSubCommand
 * @package jp\mcbe\fuyutsuki\Texter\command\sub
 */
class MoveSubCommand extends TexterSubCommand {

	public const NAME = "move";
	public const ALIAS = "m";

	/** @var string */
	private $name;
	/** @var Vector3 */
	private $position;

	public function __construct(string $name) {
		$this->name = $name;
	}

	public function setPosition(Vector3 $position) {
		$this->position = $position;
	}

	public function setPositionByString(string $x, string $y, string $z) {
		$this->position = new Vector3((float)$x, (float)$y, (float)$z);
	}

	public function execute(Player $player) {
		$level = $player->getLevel();
		$folderName = $level->getFolderName();
		$floatingTextData = FloatingTextData::getInstance($folderName);
		$lang = TexterLang::fromLocale($player->getLocale());

		if ($floatingTextData->existsFloatingText($this->name)) {
			$position = $this->position->up()->round(1);
			$floatingText = $floatingTextData->floatingText($this->name);
			$floatingText->setPosition($position);
			$floatingText->recalculatePosition();
			$floatingText->sendToLevel($level, new SendType(SendType::MOVE));
			$floatingTextData->store($floatingText);
			$floatingTextData->save();
			$message = TextFormat::GREEN . $lang->translateString("command.txt.move.success", [
				$this->name,
				"x: {$position->x}, y: {$position->y}, z: {$position->z}"
			]);
		}else {
			$message = TextFormat::RED . $lang->translateString("error.ft.name.not.exists", [
				$this->name
			]);
		}
		$player->sendMessage(Main::prefix() . " {$message}");
	}
}