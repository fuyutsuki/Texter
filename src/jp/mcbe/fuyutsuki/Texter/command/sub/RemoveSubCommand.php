<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\command\sub;

use jp\mcbe\fuyutsuki\Texter\data\FloatingTextData;
use jp\mcbe\fuyutsuki\Texter\i18n\TexterLang;
use jp\mcbe\fuyutsuki\Texter\Main;
use jp\mcbe\fuyutsuki\Texter\text\SendType;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

/**
 * Class RemoveSubCommand
 * @package jp\mcbe\fuyutsuki\Texter\command\sub
 */
class RemoveSubCommand extends TexterSubCommand {

	public const NAME = "remove";
	public const ALIAS = "r";

	/** @var string */
	private $name;

	public function __construct(string $name) {
		$this->name = $name;
	}

	public function execute(Player $player) {
		$lang = TexterLang::fromLocale($player->getLocale());
		$level = $player->getLevel();
		$folderName = $level->getFolderName();
		$floatingTextData = FloatingTextData::getInstance($folderName);

		if ($floatingTextData->existsFloatingText($this->name)) {
			$floatingText = $floatingTextData->floatingText($this->name);
			$floatingText->sendToLevel($level, new SendType(SendType::REMOVE));
			$floatingTextData->removeFloatingText($floatingText->name());
			$floatingTextData->save();
			$message = TextFormat::GREEN . $lang->translateString("command.txt.remove.success", [
				$this->name
			]);
		}else {
			$message = TextFormat::RED . $lang->translateString("error.ft.name.exists", [
				$this->name
			]);
		}
		$player->sendMessage(Main::prefix() . " {$message}");
	}
}