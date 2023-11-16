<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\command\form;

use jp\mcbe\fuyutsuki\Texter\libs\_75c42800fe7b2380\dktapps\pmforms\CustomForm;
use jp\mcbe\fuyutsuki\Texter\libs\_75c42800fe7b2380\dktapps\pmforms\CustomFormResponse;
use jp\mcbe\fuyutsuki\Texter\libs\_75c42800fe7b2380\dktapps\pmforms\element\Input;
use jp\mcbe\fuyutsuki\Texter\libs\_75c42800fe7b2380\dktapps\pmforms\element\Label;
use jp\mcbe\fuyutsuki\Texter\data\FloatingTextData;
use jp\mcbe\fuyutsuki\Texter\i18n\TexterLang;
use jp\mcbe\fuyutsuki\Texter\Main;
use jp\mcbe\fuyutsuki\Texter\text\SendType;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use Ramsey\Uuid\Uuid;

class MoveFloatingTextToPositionForm extends CustomForm {

	public function __construct(
		private TexterLang $lang,
		private string $name,
		bool $isNotValid = false
	) {
		$inputX = $lang->translateString("form.move.position.x");
		$inputY = $lang->translateString("form.move.position.y");
		$inputZ = $lang->translateString("form.move.position.z");

		if ($isNotValid) {
			$elements[] = new Label(Uuid::uuid4()->toString(), $lang->translateString("form.move.position.error.is.not.valid"));
		}
		$elements[] = new Label(Uuid::uuid4()->toString(), $lang->translateString("form.move.position.description"));
		$elements[] = new Input(FormLabels::X, $inputX, $inputX);
		$elements[] = new Input(FormLabels::Y, $inputY, $inputY);
		$elements[] = new Input(FormLabels::Z, $inputZ, $inputZ);

		parent::__construct(
			Main::prefix() . " txt > move > position",
			$elements,
			function(Player $player, CustomFormResponse $response): void {
				$this->handleSubmit($player, $response);
			}
		);
	}

	private function handleSubmit(Player $player, CustomFormResponse $response): void {
		if (!empty($response->getString(FormLabels::X)) &&
			!empty($response->getString(FormLabels::Y)) &&
			!empty($response->getString(FormLabels::Z))
		) {
			$position = new Vector3(
				(float)$response->getString(FormLabels::X),
				(float)$response->getString(FormLabels::Y),
				(float)$response->getString(FormLabels::Z)
			);
			$world = $player->getWorld();
			$folderName = $world->getFolderName();
			$floatingTextData = FloatingTextData::getInstance($folderName);

			if ($floatingTextData->existsFloatingText($this->name)) {
				$floatingText = $floatingTextData->floatingText($this->name);
				$floatingText->setPosition($position);
				$floatingText->recalculatePosition();
				$floatingText->sendToWorld($world, SendType::MOVE);
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