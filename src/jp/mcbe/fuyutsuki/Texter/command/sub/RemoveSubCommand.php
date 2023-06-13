<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\command\sub;

use jp\mcbe\fuyutsuki\Texter\data\FloatingTextData;
use jp\mcbe\fuyutsuki\Texter\i18n\TexterLang;
use jp\mcbe\fuyutsuki\Texter\Main;
use jp\mcbe\fuyutsuki\Texter\text\SendType;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class RemoveSubCommand extends TexterSubCommand {

	public const NAME = "remove";
	public const ALIAS = "r";

	public function __construct(
		private string $name
	) {
	}

	public function execute(Player $player) {
		$lang = TexterLang::fromLocale($player->getLocale());
		$world = $player->getWorld();
		$folderName = $world->getFolderName();
		$floatingTextData = FloatingTextData::getInstance($folderName);

		if ($floatingTextData->existsFloatingText($this->name)) {
			$floatingText = $floatingTextData->floatingText($this->name);
			$floatingText->sendToWorld($world, SendType::REMOVE);
			$floatingTextData->removeFloatingText($floatingText->name());
			$floatingTextData->save();
			$message = TextFormat::GREEN . $lang->translateString("command.txt.remove.success", [
				$this->name
			]);
		}else {
			$message = TextFormat::RED . $lang->translateString("error.ft.name.not.exists", [
				$this->name
			]);
		}
		$player->sendMessage(Main::prefix() . " $message");
	}
}