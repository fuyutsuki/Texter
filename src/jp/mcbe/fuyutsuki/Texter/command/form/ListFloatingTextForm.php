<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\command\form;

use jojoe77777\FormAPI\SimpleForm;
use jp\mcbe\fuyutsuki\Texter\command\sub\EditSubCommand;
use jp\mcbe\fuyutsuki\Texter\command\sub\MoveSubCommand;
use jp\mcbe\fuyutsuki\Texter\data\FloatingTextData;
use jp\mcbe\fuyutsuki\Texter\i18n\TexterLang;
use jp\mcbe\fuyutsuki\Texter\Main;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

/**
 * Class ListFloatingTextForm
 * @package jp\mcbe\fuyutsuki\Texter\command\form
 */
class ListFloatingTextForm extends SimpleForm {

	/** @var TexterLang */
	private $lang;
	/** @var string */
	private $action;

	/** @var FloatingTextData */
	private $floatingTextData;

	public function __construct(Player $player, string $action = "") {
		parent::__construct(null);
		$this->lang = TexterLang::fromLocale($player->getLocale());
		$this->action = $action;

		$this->setTitle(Main::prefix() . " txt > list");
		$this->setContent($this->lang->translateString("form.list.description.1"));

		$folderName = $player->getLevel()->getFolderName();
		$this->floatingTextData = FloatingTextData::getInstance($folderName);

		$floatingTexts = $this->floatingTextData->floatingTexts();
		foreach ($floatingTexts as $name => $floatingText) {
			if ($player->distance($floatingText->position()) <= 10) {
				$text = mb_substr($floatingText->get(0)->text(), 0, 26);
				$this->addButton("[{$floatingText->name()}]\n" . TextFormat::BOLD . "{$text}...", -1, "", $name);
			}
		}
		$this->addButton(TextFormat::DARK_RED . $this->lang->translateString("form.close"));
	}

	public function handleResponse(Player $player, $data): void {
		$this->processData($data);
		if ($data === null || !is_string($data)) return;

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