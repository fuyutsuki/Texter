<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\command\form;

use jp\mcbe\fuyutsuki\Texter\libs\_75c42800fe7b2380\dktapps\pmforms\MenuForm;
use jp\mcbe\fuyutsuki\Texter\libs\_75c42800fe7b2380\dktapps\pmforms\MenuOption;
use jp\mcbe\fuyutsuki\Texter\command\sub\EditSubCommand;
use jp\mcbe\fuyutsuki\Texter\command\sub\MoveSubCommand;
use jp\mcbe\fuyutsuki\Texter\data\FloatingTextData;
use jp\mcbe\fuyutsuki\Texter\i18n\TexterLang;
use jp\mcbe\fuyutsuki\Texter\Main;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use Ramsey\Uuid\Uuid;

class ListFloatingTextForm extends MenuForm {

	private TexterLang $lang;

	private ?FloatingTextData $floatingTextData;

	/** @var string[] */
	private array $keys;

	public function __construct(
		Player $player,
		private string $action = ""
	) {
		$this->lang = TexterLang::fromLocale($player->getLocale());

		$folderName = $player->getWorld()->getFolderName();
		$this->floatingTextData = FloatingTextData::getInstance($folderName);

		$options = [];
		$floatingTexts = $this->floatingTextData->floatingTexts();
		foreach ($floatingTexts as $floatingText) {
			if ($player->getPosition()->distanceSquared($floatingText->position()) <= 100) {
				$text = mb_substr($floatingText->get(0)->text(), 0, 26);
				$options[$floatingText->name()] = new MenuOption("[{$floatingText->name()}]\n" . TextFormat::BOLD . "$text...");
			}
		}
		$options[Uuid::uuid4()->getBytes()] = new MenuOption(TextFormat::DARK_RED . $this->lang->translateString("form.close"));

		$this->keys = array_keys($options);
		parent::__construct(
			Main::prefix() . " txt > list",
			$this->lang->translateString("form.list.description.1"),
			$options,
			function(Player $player, int $selected): void {
				$this->handleSubmit($player, $selected);
			}
		);
	}

	public function handleSubmit(Player $player, int $selected): void {
		$selectedKey = (string) $this->keys[$selected];
		if ($this->floatingTextData->existsFloatingText($selectedKey)) {
			if (!empty($this->action)) {
				switch ($this->action) {
					case EditSubCommand::NAME:
					case EditSubCommand::ALIAS:
						$subCommand = new EditSubCommand($selectedKey);
						$subCommand->execute($player);
						return;

					case MoveSubCommand::NAME:
					case MoveSubCommand::ALIAS:
						SelectMoveTargetForm::send($player, $selectedKey);
						return;
				}
			}
			$form = new SelectActionForm($this->lang, $selectedKey);
			$player->sendForm($form);
		}
	}

	public static function send(Player $player, string $action = "") {
		$form = new self($player, $action);
		$player->sendForm($form);
	}

}