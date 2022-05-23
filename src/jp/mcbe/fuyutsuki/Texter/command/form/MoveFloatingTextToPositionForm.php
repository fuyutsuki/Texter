<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\command\form;

use jojoe77777\FormAPI\CustomForm;
use jp\mcbe\fuyutsuki\Texter\data\FloatingTextData;
use jp\mcbe\fuyutsuki\Texter\i18n\TexterLang;
use jp\mcbe\fuyutsuki\Texter\Main;
use jp\mcbe\fuyutsuki\Texter\text\SendType;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class MoveFloatingTextToPositionForm extends CustomForm {

	public function __construct(
		private TexterLang $lang,
		private string $name,
		bool $isNotValid = false
	) {
		parent::__construct(null);
		$inputX = $lang->translateString("form.move.position.x");
		$inputY = $lang->translateString("form.move.position.y");
		$inputZ = $lang->translateString("form.move.position.z");

		$this->setTitle(Main::prefix() . " txt > move > position");
		if ($isNotValid) {
			$this->addLabel($lang->translateString("form.move.position.error.is.not.valid"));
		}
		$this->addLabel($lang->translateString("form.move.position.description"));
		$this->addInput($inputX, $inputX, null, FormLabels::X);
		$this->addInput($inputY, $inputY, null, FormLabels::Y);
		$this->addInput($inputZ, $inputZ, null, FormLabels::Z);
	}

	public function handleResponse(Player $player, $data): void {
		$this->processData($data);
		if ($data === null) return;
		if (!empty($data[FormLabels::X]) && !empty($data[FormLabels::Y]) && !empty($data[FormLabels::Z])) {
			$position = new Vector3(
				floatval($data[FormLabels::X]),
				floatval($data[FormLabels::Y]),
				floatval($data[FormLabels::Z])
			);
			$world = $player->getWorld();
			$folderName = $world->getFolderName();
			$floatingTextData = FloatingTextData::getInstance($folderName);

			if ($floatingTextData->existsFloatingText($this->name)) {
				$floatingText = $floatingTextData->floatingText($this->name);
				$floatingText->setPosition($position);
				$floatingText->recalculatePosition();
				$floatingText->sendToWorld($world, SendType::MOVE());
				$floatingTextData->store($floatingText);
				$floatingTextData->save();
				$player->sendMessage(Main::prefix() . " " . $this->lang->translateString("command.txt.move.success", [
					$this->name,
					"x: $position->x, y: $position->y, z: $position->z"
				]));
			}
		}else {
			$this->resend($player, true);
		}
	}

	public function resend(Player $player, bool $isNotValid = false) {
		$form = new self($this->lang, $this->name, $isNotValid);
		$player->sendForm($form);
	}

}