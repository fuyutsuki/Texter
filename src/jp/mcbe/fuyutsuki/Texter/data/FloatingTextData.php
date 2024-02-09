<?php

/**
 * // English
 *
 * Texter, the display FloatingTextPerticle plugin for PocketMine-MP
 * Copyright (c) 2019-2021 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * This software is distributed under "NCSA license".
 * You should have received a copy of the NCSA license
 * along with this program.  If not, see
 * < https://opensource.org/licenses/NCSA >.
 *
 * ---------------------------------------------------------------------
 * // 日本語
 *
 * TexterはPocketMine-MP向けのFloatingTextPerticleを表示するプラグインです
 * Copyright (c) 2019-2021 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * このソフトウェアは"NCSAライセンス"下で配布されています。
 * あなたはこのプログラムと共にNCSAライセンスのコピーを受け取ったはずです。
 * 受け取っていない場合、下記のURLからご覧ください。
 * < https://opensource.org/licenses/NCSA >
 */

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\data;

use jp\mcbe\fuyutsuki\Texter\task\PrepareTextsTask;
use jp\mcbe\fuyutsuki\Texter\text\FloatingTextCluster;
use jp\mcbe\fuyutsuki\Texter\util\StringArrayMultiton;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class FloatingTextData extends Config {

	use StringArrayMultiton {
		StringArrayMultiton::__construct as stringArrayMultitonConstruct;
	}

	public const FLOATING_TEXT_DIRECTORY = "floating_text" . DIRECTORY_SEPARATOR;

	private string $folderName;
	/** @var FloatingTextCluster[] */
	private array $floatingTexts = [];

	public function __construct(PluginBase $plugin, string $worldFolderName) {
		$floatingTextDir = $plugin->getDataFolder() . self::FLOATING_TEXT_DIRECTORY;
		parent::__construct($floatingTextDir . "$worldFolderName.json", Config::JSON);
		$this->setJsonOptions(Data::JSON_OPTIONS);
		$this->stringArrayMultitonConstruct($worldFolderName);
		$this->folderName = $worldFolderName;
	}

	public function generateFloatingTexts(PluginBase $plugin) {
		$prepare = new PrepareTextsTask($plugin, $this);
		$plugin->getScheduler()->scheduleRepeatingTask($prepare, PrepareTextsTask::TICKING_PERIOD);
	}

	public function folderName(): string {
		return $this->folderName;
	}

	public function floatingTexts(): array {
		return $this->floatingTexts;
	}

	public function floatingText(string $name): ?FloatingTextCluster {
		return $this->floatingTexts[$name] ?? null;
	}

	public function store(FloatingTextCluster $text, bool $onlyCache = false) {
		$name = $text->name();
		$this->floatingTexts[$name] = $text;
		if (!$onlyCache) {
			$this->set($name, $text);
		}
	}

	public function removeFloatingText(string $name, bool $onlyCache = false) {
		unset($this->floatingTexts[$name]);
		if (!$onlyCache) {
			$this->remove($name);
		}
	}

	public function existsFloatingText(string $name, bool $onlyCache = false): bool {
		$result = isset($this->floatingTexts[$name]);
		if (!$onlyCache) {
			$result = $result && $this->exists($name, true);
		}
		return $result;
	}

	public function notExistsFloatingText(string $name, bool $onlyCache = false): bool {
		return !$this->existsFloatingText($name, $onlyCache);
	}

}