<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\command\form;

use jojoe77777\FormAPI\SimpleForm;
use jp\mcbe\fuyutsuki\Texter\command\sub\EditSubCommand;
use jp\mcbe\fuyutsuki\Texter\command\sub\RemoveSubCommand;
use jp\mcbe\fuyutsuki\Texter\i18n\TexterLang;
use jp\mcbe\fuyutsuki\Texter\Main;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class SelectActionForm extends SimpleForm {

	public function __construct(
		TexterLang $lang,
		private string $name
	) {
		parent::__construct(null);
		$edit = TextFormat::BOLD . $lang->translateString("form.edit")."\n".TextFormat::RESET;
		$move = TextFormat::BOLD . $lang->translateString("form.move")."\n".TextFormat::RESET;
		$remove = TextFormat::BOLD . $lang->translateString("form.remove")."\n".TextFormat::RESET;

		$this->setTitle(Main::prefix() . " txt > list > select action");
		$this->setContent($lang->translateString("form.list.description.2", [
			$name
		]));
		$this->addButton($edit . $lang->translateString("form.edit.description"), -1, "", FormLabels::EDIT);
		$this->addButton($move . $lang->translateString("form.move.description"), -1, "", FormLabels::MOVE);
		$this->addButton($remove . $lang->translateString("form.remove.description"), -1, "", FormLabels::REMOVE);
		$this->addButton(TextFormat::DARK_RED . $lang->translateString("form.close"));
	}

	public function handleResponse(Player $player, $data): void {
		$this->processData($data);
		if (!is_string($data)) return;

		switch ($data) {
			case FormLabels::EDIT:
				$subCommand = new EditSubCommand($this->name);
				$subCommand->execute($player);
				break;

			case FormLabels::MOVE:
				SelectMoveTargetForm::send($player, $this->name);
				break;

			case FormLabels::REMOVE:
				$subCommand = new RemoveSubCommand($this->name);
				$subCommand->execute($player);
				break;
		}
	}

}