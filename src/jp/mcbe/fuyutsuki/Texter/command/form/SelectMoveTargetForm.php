<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\command\form;

use jp\mcbe\fuyutsuki\Texter\libs\_75c42800fe7b2380\dktapps\pmforms\MenuForm;
use jp\mcbe\fuyutsuki\Texter\libs\_75c42800fe7b2380\dktapps\pmforms\MenuOption;
use jp\mcbe\fuyutsuki\Texter\command\sub\MoveSubCommand;
use jp\mcbe\fuyutsuki\Texter\i18n\TexterLang;
use jp\mcbe\fuyutsuki\Texter\Main;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use Ramsey\Uuid\Uuid;

class SelectMoveTargetForm extends MenuForm {

	/** @var string[] */
	private array $keys;

	public function __construct(
		private TexterLang $lang,
		private string $name
	) {
		$options = [
			FormLabels::HERE => new MenuOption($lang->translateString("form.move.here")),
			FormLabels::POSITION => new MenuOption($lang->translateString("form.move.position")),
			Uuid::uuid4()->getBytes() => new MenuOption(TextFormat::DARK_RED . $lang->translateString("form.close"))
		];

		$this->keys = array_keys($options);
		parent::__construct(
			Main::prefix() . " txt > move > select target",
			$lang->translateString("form.move.select.target.description"),
			$options,
			function(Player $player, int $selected): void {
				$this->handleSubmit($player, $selected);
			}
		);
	}

	private function handleSubmit(Player $player, int $selected): void {
		$label = $this->keys[$selected];
		switch ($label) {
			case FormLabels::HERE:
				$subCommand = new MoveSubCommand($this->name);
				$subCommand->setPosition($player->getPosition());
				$subCommand->execute($player);
				break;

			case FormLabels::POSITION:
				$form = new MoveFloatingTextToPositionForm($this->lang, $this->name);
				$player->sendForm($form);
				break;
		}
	}

	public static function send(Player $player, string $name) {
		$lang = TexterLang::fromLocale($player->getLocale());
		$form = new self($lang, $name);
		$player->sendForm($form);
	}

}