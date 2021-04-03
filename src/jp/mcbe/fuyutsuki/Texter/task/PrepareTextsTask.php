<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\task;

use jp\mcbe\fuyutsuki\Texter\data\Data;
use jp\mcbe\fuyutsuki\Texter\data\FloatingTextData;
use jp\mcbe\fuyutsuki\Texter\i18n\TexterLang;
use jp\mcbe\fuyutsuki\Texter\text\FloatingTextCluster;
use pocketmine\plugin\Plugin;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use function array_shift;
use function count;

/**
 * Class PrepareTextsTask
 * @package jp\mcbe\fuyutsuki\Texter\task
 */
class PrepareTextsTask extends Task {

	public const TICKING_PERIOD = 2;

	/** @var Plugin */
	private $plugin;
	/** @var Server */
	private $server;
	/** @var FloatingTextData */
	private $data;
	/** @var array */
	private $remain;
	/** @var string[] */
	private $names;

	public function __construct(Plugin $plugin, FloatingTextData $floatingTextData) {
		$this->plugin = $plugin;
		$this->server = $plugin->getServer();
		$this->data = $floatingTextData;
		$folderName = $floatingTextData->folderName();
		if (!$this->server->isLevelLoaded($folderName)) {
			$this->server->loadLevel($folderName);
		}
		$this->remain = $floatingTextData->getAll();
		$this->names = $floatingTextData->getAll(true);
	}

	public function onRun(int $currentTick) {
		if (empty($this->remain)) {
			$this->onSuccess();
		}else {
			$name = (string) array_shift($this->names);
			$value = array_shift($this->remain);
			if (is_array($value)) {
				$floatingText = FloatingTextCluster::fromArray($name, $value);
				$this->data->store($floatingText, true);
			}
		}
	}

	public function onSuccess() {
		if ($this->plugin !== null && $this->plugin->isEnabled()) {
			$message = TexterLang::fromConsole()->translateString("on.enable.prepared", [
				$this->data->folderName(),
				count($this->data->floatingTexts())
			]);
			$this->plugin->getLogger()->info(TextFormat::GREEN . $message);
			$this->plugin->getScheduler()->cancelTask($this->getTaskId());
		}
	}
}