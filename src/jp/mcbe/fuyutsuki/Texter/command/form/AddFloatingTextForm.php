<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\command\form;

use jojoe77777\FormAPI\CustomForm;
use jp\mcbe\fuyutsuki\Texter\data\FloatingTextData;
use jp\mcbe\fuyutsuki\Texter\i18n\TexterLang;
use jp\mcbe\fuyutsuki\Texter\Main;
use jp\mcbe\fuyutsuki\Texter\text\FloatingTextCluster;
use jp\mcbe\fuyutsuki\Texter\text\SendType;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

/**
 * Class AddFloatingTextForm
 * @package jp\mcbe\fuyutsuki\Texter\command\form
 */
class AddFloatingTextForm extends CustomForm {

	/** @var ?FloatingTextSession */
	private $session;

	public function __construct(Player $player) {
		parent::__construct(null);
		$playerName = $player->getLowerCaseName();
		$lang = TexterLang::fromLocale($player->getLocale());
		$this->session = FloatingTextSession::get($playerName);
		if ($this->session === null) {
			$this->session = new FloatingTextSession($playerName, $lang);
		}

		$inputName = $lang->translateString("form.ft.name.unique");

		$this->setTitle(Main::prefix() . " txt > " . ($this->session->isEdit() ? "edit" : "add"));

		if ($this->session->hasNoTexts()) {
			$this->session->setNoTexts(false);
			$this->addLabel(TextFormat::RED . "[!]" . $lang->translateString("form.add.error.no.texts"));
		}
		if ($this->session->isDuplicateName()) {
			$this->session->setDuplicateName(false);
			$this->addLabel(TextFormat::RED . "[!]" . $lang->translateString("error.ft.name.exists", [
				$this->session->name(),
			]));
		}

		$this->addLabel($lang->translateString("form.add.description"));
		$this->addInput($inputName, $inputName, $this->session->name(), FormLabels::NAME);

		if (count($this->session->texts()) >= 2) {
			$spacing = $this->session->spacing()->multiply(10)->add(30, 30, 30);
			$range = range(-30, 30);
			foreach ($range as $k => $v) {
				$range[$k] = (string)($v/10);
			}
			$this->addLabel($lang->translateString("form.add.spacing.description"));
			$this->addStepSlider($lang->translateString("form.add.spacing.x"), $range, $spacing->getFloorX(), FormLabels::X);
			$this->addStepSlider($lang->translateString("form.add.spacing.y"), $range, $spacing->getFloorY(), FormLabels::Y);
			$this->addStepSlider($lang->translateString("form.add.spacing.z"), $range, $spacing->getFloorZ(), FormLabels::Z);
			$this->addLabel($lang->translateString("command.txt.usage.cluster.remove"));
		}

		$this->addLabel($lang->translateString("command.txt.usage.new.line"));
		if (empty($this->session->texts())) {
			$this->session->addText("");
		}
		foreach ($this->session->texts() as $key => $text) {
			$inputText = $lang->translateString("form.add.text", [
				$key + 1,
			]);
			$this->addInput($inputText, $inputText, $text, FormLabels::TEXT . "_{$key}");
		}
		$this->addToggle($lang->translateString("form.add.more.ft"), null, FormLabels::ADD_MORE);
	}

	public function handleResponse(Player $player, $data): void {
		if ($data === null) {
			FloatingTextSession::remove($player->getLowerCaseName());
			return;
		}

		$this->processData($data);

		$this->session->setName($data[FormLabels::NAME]);
		$texts = $this->session->texts();
		$this->session->setTexts([]);

		$empty = 0;
		foreach ($texts as $key => $text) {
			if (!empty($data[FormLabels::TEXT . "_{$key}"])) {
				$this->session->addText($data[FormLabels::TEXT . "_{$key}"]);
			}else {
				++$empty;
			}
		}
		if (empty($this->session->texts())) {
			$this->session->setNoTexts();
			self::send($player);
			return;
		}
		$countTexts = count($texts);
		if ($countTexts >= 2 && $empty > 0) {
			self::send($player);
			return;
		}

		$spacing = null;
		if (count($texts) >= 2) {
			$spacing = new Vector3($data[FormLabels::X], $data[FormLabels::Y], $data[FormLabels::Z]);
			$spacing = $spacing->subtract(30, 30, 30)->divide(10);
		}

		if ($data[FormLabels::ADD_MORE]) {
			$this->session->addText("");
			self::send($player);
		}else {
			$level = $player->getLevel();
			$folderName = $level->getFolderName();
			$floatingTextData = FloatingTextData::getInstance($folderName);

			if ($floatingTextData->notExistsFloatingText($this->session->name()) || $this->session->isEdit()) {
				$pos = $player->up();
				if ($this->session->isEdit()) {
					$floatingText = $floatingTextData->floatingText($this->session->name());
					$floatingText->sendToLevel($level, new SendType(SendType::REMOVE));
					$pos = $floatingText->position();
				}
				$floatingText = new FloatingTextCluster($pos, $this->session->name(), $spacing, $this->session->texts());
				$floatingText->sendToLevel($level, new SendType(SendType::ADD));
				$floatingTextData->store($floatingText);
				$floatingTextData->save();
				FloatingTextSession::remove($player->getLowerCaseName());
				$operate = $this->session->isEdit() ? "edit" : "add";
				$message = TextFormat::GREEN . $this->session->lang()->translateString("command.txt.{$operate}.success", [
					$floatingText->name()
				]);
				$player->sendMessage(Main::prefix() . " {$message}");
			} else {
				$this->session->setDuplicateName();
				self::send($player);
			}
		}
	}

	public static function send(Player $player) {
		$form = new self($player);
		$player->sendForm($form);
	}

}