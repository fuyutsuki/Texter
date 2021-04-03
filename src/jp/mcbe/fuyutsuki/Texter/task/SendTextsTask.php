<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\task;

use jp\mcbe\fuyutsuki\Texter\data\FloatingTextData;
use jp\mcbe\fuyutsuki\Texter\text\FloatingTextCluster;
use jp\mcbe\fuyutsuki\Texter\text\SendType;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\scheduler\Task;

/**
 * Class SendTextsTask
 * @package jp\mcbe\fuyutsuki\Texter\task
 */
class SendTextsTask extends Task {

	public const DELAY_TICKS = 5;// 0.25s
	public const TICKING_PERIOD = 2;// 0.1s

	/** @var Plugin */
	private $plugin;
	/** @var Player */
	private $target;
	/** @var Level */
	private $sendTo;
	/** @var SendType */
	private $type;

	/** @var FloatingTextCluster[] */
	private $remain;

	public function __construct(Plugin $plugin, Player $target, Level $sendTo, SendType $type) {
		$this->plugin = $plugin;
		$this->target = $target;
		$this->sendTo = $sendTo;
		$this->type = $type;
		$data = FloatingTextData::getInstance($sendTo->getFolderName());
		$this->remain = $data !== null ? $data->floatingTexts() : [];
	}

	public function onRun(int $currentTick) {
		if (empty($this->remain)) {
			$this->onSuccess();
		}else {
			$floatingText = array_shift($this->remain);
			$floatingText->sendToPlayer($this->target, $this->type);
		}
	}

	private function onSuccess() {
		$this->plugin->getScheduler()->cancelTask($this->getTaskId());
	}
}