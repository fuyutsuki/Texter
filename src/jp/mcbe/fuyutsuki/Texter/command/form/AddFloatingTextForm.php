<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\command\form;

use jp\mcbe\fuyutsuki\Texter\libs\_75c42800fe7b2380\dktapps\pmforms\CustomForm;
use jp\mcbe\fuyutsuki\Texter\libs\_75c42800fe7b2380\dktapps\pmforms\CustomFormResponse;
use jp\mcbe\fuyutsuki\Texter\libs\_75c42800fe7b2380\dktapps\pmforms\element\Input;
use jp\mcbe\fuyutsuki\Texter\libs\_75c42800fe7b2380\dktapps\pmforms\element\Label;
use jp\mcbe\fuyutsuki\Texter\libs\_75c42800fe7b2380\dktapps\pmforms\element\StepSlider;
use jp\mcbe\fuyutsuki\Texter\libs\_75c42800fe7b2380\dktapps\pmforms\element\Toggle;
use jp\mcbe\fuyutsuki\Texter\data\FloatingTextData;
use jp\mcbe\fuyutsuki\Texter\i18n\TexterLang;
use jp\mcbe\fuyutsuki\Texter\Main;
use jp\mcbe\fuyutsuki\Texter\text\FloatingTextCluster;
use jp\mcbe\fuyutsuki\Texter\text\SendType;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use Ramsey\Uuid\Uuid;
use function count;
use function range;
use function strtolower;

class AddFloatingTextForm extends CustomForm {

	private ?FloatingTextSession $session;

	public function __construct(Player $player) {
		$playerName = strtolower($player->getName());
		$lang = TexterLang::fromLocale($player->getLocale());
		$this->session = FloatingTextSession::get($playerName);
		if ($this->session === null) {
			$this->session = new FloatingTextSession($playerName, $lang);
		}

		$elements = [];
		$inputName = $lang->translateString("form.ft.name.unique");

		if ($this->session->hasNoTexts()) {
			$this->session->setNoTexts(false);
			$elements[] = new Label(Uuid::uuid4()->getBytes(), TextFormat::RED . "[!]" . $lang->translateString("form.add.error.no.texts"));
		}
		if ($this->session->isDuplicateName()) {
			$this->session->setDuplicateName(false);
			$elements[] = new Label(Uuid::uuid4()->getBytes(), TextFormat::RED . "[!]" . $lang->translateString("error.ft.name.exists", [
				$this->session->name(),
			]));
		}

		$elements[] = new Label(Uuid::uuid4()->getBytes(), $lang->translateString("form.add.description"));
		if ($this->session->isEdit()) {
			$elements[] = new Label(Uuid::uuid4()->getBytes(), "$inputName: {$this->session->name()}");
		}else {
			$elements[] = new Input(FormLabels::NAME, $inputName, $inputName, $this->session->name());
		}

		if (count($this->session->texts()) >= 2) {
			$spacing = $this->session->spacing();
			if ($spacing->equals(Vector3::zero())) {
				$spacing = $spacing->add(0, -0.3, 0);
			}
			$range = range(-30, 30);
			foreach ($range as $k => $v) {
				$range[$k] = (string)($v/10);
			}
			$flipedRange = array_flip($range);
			$elements[] = new Label(Uuid::uuid4()->getBytes(), $lang->translateString("form.add.spacing.description"));
			$elements[] = new StepSlider(FormLabels::X, $lang->translateString("form.add.spacing.x"), $range, $flipedRange[(string) $spacing->getX()]);
			$elements[] = new StepSlider(FormLabels::Y, $lang->translateString("form.add.spacing.y"), $range, $flipedRange[(string) $spacing->getY()]);
			$elements[] = new StepSlider(FormLabels::Z, $lang->translateString("form.add.spacing.z"), $range, $flipedRange[(string) $spacing->getZ()]);
			$elements[] = new Label(Uuid::uuid4()->getBytes(), $lang->translateString("command.txt.usage.cluster.remove"));
		}

		$elements[] = new Label(Uuid::uuid4()->getBytes(), $lang->translateString("command.txt.usage.new.line"));
		if (empty($this->session->texts())) {
			$this->session->addText("");
		}
		foreach ($this->session->texts() as $key => $text) {
			$inputText = $lang->translateString("form.add.text", [
				$key + 1,
			]);
			$elements[] = new Input(FormLabels::TEXT . "_$key", $inputText, $inputText, $text);
		}
		$elements[] = new Toggle(FormLabels::ADD_MORE, $lang->translateString("form.add.more.ft"));

		parent::__construct(
			Main::prefix() . " txt > " . ($this->session->isEdit() ? SendType::EDIT->value : SendType::ADD->value),
			$elements,
			function(Player $player, CustomFormResponse $response): void {
				$this->handleSubmit($player, $response);
			},
			function(Player $player): void {
				FloatingTextSession::remove(strtolower($player->getName()));
			}
		);

	}

	private function handleSubmit(Player $player, CustomFormResponse $response): void {
		if (!$this->session->isEdit()) {
			$name = $response->getString(FormLabels::NAME);
			$this->session->setName($name);
		}

		$texts = $this->session->texts();
		$this->session->setTexts([]);

		$empty = 0;
		foreach ($texts as $key => $text) {
			if (!empty($response->getString(FormLabels::TEXT . "_$key"))) {
				$this->session->addText($response->getString(FormLabels::TEXT . "_$key"));
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
			$spacing = new Vector3($response->getFloat(FormLabels::X), $response->getFloat(FormLabels::Y), $response->getFloat(FormLabels::Z));
			$spacing = $spacing->subtract(30, 30, 30)->divide(10);
		}

		if ($response->getBool(FormLabels::ADD_MORE)) {
			$this->session->addText("");
			self::send($player);
		}else {
			$world = $player->getWorld();
			$folderName = $world->getFolderName();
			$floatingTextData = FloatingTextData::getInstance($folderName);

			if ($floatingTextData->notExistsFloatingText($this->session->name()) || $this->session->isEdit()) {
				$pos = $player->getPosition()->up();
				if ($this->session->isEdit()) {
					$floatingText = $floatingTextData->floatingText($this->session->name());
					$floatingText->sendToWorld($world, SendType::REMOVE);
					$pos = $floatingText->position();
				}
				$floatingText = new FloatingTextCluster($pos, $this->session->name(), $spacing, $this->session->texts());
				$floatingText->sendToWorld($world, SendType::ADD);
				$floatingTextData->store($floatingText);
				$floatingTextData->save();
				FloatingTextSession::remove(strtolower($player->getName()));
				$operate = $this->session->isEdit() ? SendType::EDIT->value : SendType::ADD->value;
				$message = TextFormat::GREEN . $this->session->lang()->translateString("command.txt.$operate.success", [
					$floatingText->name()
				]);
				$player->sendMessage(Main::prefix() . " $message");
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