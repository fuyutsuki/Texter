<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\command\form;

use jp\mcbe\fuyutsuki\Texter\libs\_75c42800fe7b2380\dktapps\pmforms\MenuForm;
use jp\mcbe\fuyutsuki\Texter\libs\_75c42800fe7b2380\dktapps\pmforms\MenuOption;
use jp\mcbe\fuyutsuki\Texter\command\sub\EditSubCommand;
use jp\mcbe\fuyutsuki\Texter\command\sub\RemoveSubCommand;
use jp\mcbe\fuyutsuki\Texter\i18n\TexterLang;
use jp\mcbe\fuyutsuki\Texter\Main;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use Ramsey\Uuid\Uuid;

class SelectActionForm extends MenuForm {

	/** @var string[] */
	private array $keys;

	public function __construct(
		TexterLang $lang,
		private string $name
	) {
		$edit = TextFormat::BOLD . $lang->translateString("form.edit")."\n".TextFormat::RESET;
		$move = TextFormat::BOLD . $lang->translateString("form.move")."\n".TextFormat::RESET;
		$remove = TextFormat::BOLD . $lang->translateString("form.remove")."\n".TextFormat::RESET;

		$options = [
			FormLabels::EDIT => new MenuOption($edit . $lang->translateString("form.edit.description")),
			FormLabels::MOVE => new MenuOption($move . $lang->translateString("form.move.description")),
			FormLabels::REMOVE => new MenuOption($remove . $lang->translateString("form.remove.description")),
			Uuid::uuid4()->getBytes() => new MenuOption(TextFormat::DARK_RED . $lang->translateString("form.close")),
		];

		$this->keys = array_keys($options);
		parent::__construct(
			Main::prefix() . " txt > list > select action",
			$lang->translateString("form.list.description.2", [
				$name
			]),
			$options,
			function(Player $player, int $selected): void {
				$this->handleSubmit($player, $selected);
			}
		);
	}

	public function handleSubmit(Player $player, int $selected): void {
		$label = $this->keys[$selected];
		switch ($label) {
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