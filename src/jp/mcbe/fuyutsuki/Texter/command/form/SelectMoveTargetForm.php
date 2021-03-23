<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\command\form;

use jojoe77777\FormAPI\SimpleForm;
use jp\mcbe\fuyutsuki\Texter\command\sub\MoveSubCommand;
use jp\mcbe\fuyutsuki\Texter\i18n\TexterLang;
use jp\mcbe\fuyutsuki\Texter\Main;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

/**
 * Class SelectMoveTargetForm
 * @package jp\mcbe\fuyutsuki\Texter\command\form
 */
class SelectMoveTargetForm extends SimpleForm {

	/** @var TexterLang */
	private $lang;
	/** @var string */
	private $name;

	public function __construct(TexterLang $lang, string $name) {
		parent::__construct(null);
		$this->lang = $lang;
		$this->name = $name;

		$this->setTitle(Main::prefix() . " txt > move > select target");
		$this->setContent($lang->translateString("form.move.select.target.description"));
		$this->addButton($lang->translateString("form.move.here"), -1, "", FormLabels::HERE);
		$this->addButton($lang->translateString("form.move.position"), -1, "", FormLabels::POSITION);
		$this->addButton(TextFormat::DARK_RED . $lang->translateString("form.close"));
	}

	public function handleResponse(Player $player, $data): void {
		$this->processData($data);
		if ($data === null || !is_string($data)) return;
		switch ($data) {
			case FormLabels::HERE:
				$subCommand = new MoveSubCommand($this->name);
				$subCommand->setPosition($player);
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