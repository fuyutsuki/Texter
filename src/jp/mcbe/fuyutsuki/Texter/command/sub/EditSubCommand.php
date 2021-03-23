<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\command\sub;

use jp\mcbe\fuyutsuki\Texter\command\form\AddFloatingTextForm;
use jp\mcbe\fuyutsuki\Texter\command\form\FloatingTextSession;
use jp\mcbe\fuyutsuki\Texter\data\FloatingTextData;
use jp\mcbe\fuyutsuki\Texter\i18n\TexterLang;
use jp\mcbe\fuyutsuki\Texter\Main;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

/**
 * Class EditSubCommand
 * @package jp\mcbe\fuyutsuki\Texter\command\sub
 */
class EditSubCommand extends TexterSubCommand {

	public const NAME = "edit";
	public const ALIAS = "e";

	/** @var string */
	private $name;

	public function __construct(string $name) {
		$this->name = $name;
	}

	public function execute(Player $player) {
		$level = $player->getLevel();
		$folderName = $level->getFolderName();
		$floatingTextData = FloatingTextData::getInstance($folderName);
		$lang = TexterLang::fromLocale($player->getLocale());

		if ($floatingTextData !== null && $floatingTextData->existsFloatingText($this->name)) {
			$floatingText = $floatingTextData->floatingText($this->name);
			$session = new FloatingTextSession($player->getLowerCaseName(), $lang);
			$session->setName($floatingText->name());
			$session->setSpacing($floatingText->spacing());
			foreach ($floatingText->all() as $text) {
				$session->addText($text->text());
			}
			$session->setEdit();
			$form = new AddFloatingTextForm($player);
			$player->sendForm($form);
		}else {
			$message = Main::prefix() . " " . TextFormat::RED . $lang->translateString("error.ft.name.not.exists", [
				$this->name
			]);
			$player->sendMessage($message);
		}
	}
}