<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\task;

use jp\mcbe\fuyutsuki\Texter\data\FloatingTextData;
use jp\mcbe\fuyutsuki\Texter\i18n\TexterLang;
use jp\mcbe\fuyutsuki\Texter\text\FloatingTextCluster;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;
use function array_shift;
use function count;

class PrepareTextsTask extends Task {

	public const TICKING_PERIOD = 2;

	private array $remain;
	/** @var string[] */
	private array $names;

	public function __construct(
		private PluginBase $plugin,
		private FloatingTextData $data
	) {
		$worldManager = $this->plugin->getServer()->getWorldManager();
		$folderName = $this->data->folderName();
		if (!$worldManager->isWorldLoaded($folderName)) {
			$worldManager->loadWorld($folderName);
		}
		$this->remain = $this->data->getAll();
		$this->names = $this->data->getAll(true);
	}

	public function onRun(): void {
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
		if ($this->plugin?->isEnabled()) {
			$message = TexterLang::fromConsole()->translateString("on.enable.prepared", [
				$this->data->folderName(),
				count($this->data->floatingTexts())
			]);
			$this->plugin->getLogger()->info(TextFormat::GREEN . $message);
			$this->getHandler()->cancel();
		}
	}
}