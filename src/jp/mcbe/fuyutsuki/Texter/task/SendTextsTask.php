<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\task;

use jp\mcbe\fuyutsuki\Texter\data\FloatingTextData;
use jp\mcbe\fuyutsuki\Texter\text\FloatingTextCluster;
use jp\mcbe\fuyutsuki\Texter\text\SendType;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\world\World;
use function array_shift;

class SendTextsTask extends Task {

	public const DELAY_TICKS = 5;// 0.25s
	public const TICKING_PERIOD = 2;// 0.1s

	/** @var FloatingTextCluster[] */
	private array $remain;

	public function __construct(
		private readonly Player $target,
		World                   $sendTo,
		private readonly SendType $type
	) {
		$data = FloatingTextData::getInstance($sendTo->getFolderName());
		$this->remain = $data !== null ? $data->floatingTexts() : [];
	}

	public function onRun(): void {
		if (empty($this->remain) || !$this->target->isConnected()) {
			$this->onSuccess();
		}else {
			$floatingText = array_shift($this->remain);
			$floatingText->sendToPlayer($this->target, $this->type);
		}
	}

	private function onSuccess(): void {
		$this->getHandler()->cancel();
	}
}