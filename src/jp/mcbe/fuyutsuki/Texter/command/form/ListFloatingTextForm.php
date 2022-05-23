<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\command\form;

use jojoe77777\FormAPI\SimpleForm;
use jp\mcbe\fuyutsuki\Texter\command\sub\EditSubCommand;
use jp\mcbe\fuyutsuki\Texter\command\sub\MoveSubCommand;
use jp\mcbe\fuyutsuki\Texter\data\FloatingTextData;
use jp\mcbe\fuyutsuki\Texter\i18n\TexterLang;
use jp\mcbe\fuyutsuki\Texter\Main;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class ListFloatingTextForm extends SimpleForm {

	private TexterLang $lang;

	private ?FloatingTextData $floatingTextData;

	public function __construct(
		Player $player,
		private string $action = ""
	) {
		parent::__construct(null);
		$this->lang = TexterLang::fromLocale($player->getLocale());

		$this->setTitle(Main::prefix() . " txt > list");
		$this->setContent($this->lang->translateString("form.list.description.1"));

		$folderName = $player->getWorld()->getFolderName();
		$this->floatingTextData = FloatingTextData::getInstance($folderName);

		$floatingTexts = $this->floatingTextData->floatingTexts();
		foreach ($floatingTexts as $name => $floatingText) {
			if ($player->getPosition()->distanceSquared($floatingText->position()) <= 100) {
				$text = mb_substr($floatingText->get(0)->text(), 0, 26);
				$this->addButton("[{$floatingText->name()}]\n" . TextFormat::BOLD . "$text...", -1, "", $name);
			}
		}
		$this->addButton(TextFormat::DARK_RED . $this->lang->translateString("form.close"));
	}

	public function handleResponse(Player $player, $data): void {
		$this->processData($data);
		if (!is_string($data)) return;

		if ($this->floatingTextData->existsFloatingText($data)) {
			if (!empty($this->action)) {
				switch ($this->action) {
					case EditSubCommand::NAME:
					case EditSubCommand::ALIAS:
						$subCommand = new EditSubCommand($data);
						$subCommand->execute($player);
						return;

					case MoveSubCommand::NAME:
					case MoveSubCommand::ALIAS:
						SelectMoveTargetForm::send($player, $data);
						return;
				}
			}
			$form = new SelectActionForm($this->lang, $data);
			$player->sendForm($form);
		}
	}

	public static function send(Player $player, string $action = "") {
		$form = new self($player, $action);
		$player->sendForm($form);
	}

}