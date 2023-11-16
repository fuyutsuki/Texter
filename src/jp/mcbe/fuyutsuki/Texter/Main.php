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

namespace jp\mcbe\fuyutsuki\Texter;

use Exception;
use jp\mcbe\fuyutsuki\Texter\command\TexterCommand;
use jp\mcbe\fuyutsuki\Texter\data\ConfigData;
use jp\mcbe\fuyutsuki\Texter\data\FloatingTextData;
use jp\mcbe\fuyutsuki\Texter\data\OldFloatingTextData;
use jp\mcbe\fuyutsuki\Texter\i18n\TexterLang;
use jp\mcbe\fuyutsuki\Texter\task\CheckUpdateTask;
use jp\mcbe\fuyutsuki\Texter\util\dependencies\Dependencies;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\TextFormat;
use pocketmine\utils\VersionString;
use function array_key_last;
use function explode;
use function file_exists;
use function is_dir;
use function mkdir;
use function str_starts_with;

class Main extends PluginBase {

	private const PHAR_HEADER = "phar://";

	private static string $prefix;

	private ConfigData $config;
	private TexterLang $lang;

	public static function canLoadDependencyFromComposer(): bool {
		return file_exists(dirname(__DIR__, 5) . '/vendor/autoload.php');
	}

	public static function loadDependency(): void {
		require_once dirname(__DIR__, 5) . '/vendor/autoload.php';
	}

	public function onLoad(): void {
		self::setPrefix();
		$this->loadResources();
		$this->registerCommands();
		$this->convertOldFloatingTexts();
		$this->loadFloatingTexts();
		$this->checkUpdate();
	}

	public function onEnable(): void {
		$pluginManager = $this->getServer()->getPluginManager();
		if ($this->checkPackaged()) {
			$pluginManager->registerEvents(new EventListener($this), $this);
		}else {
			$pluginManager->disablePlugin($this);
		}
	}

	private function loadResources() {
		$this->config = new ConfigData($this);
		//
		$oldLanguageDir = $this->getDataFolder() . "language";
		if (file_exists($oldLanguageDir)) {
			$this->unlinkRecursive($oldLanguageDir);
		}

		$resources = $this->getResources();
		foreach ($resources as $resource) {
			$fileName = $resource->getFileName();
			$extension = $this->getFileExtension($fileName);

			if ($extension !== TexterLang::LANGUAGE_EXTENSION) continue;

			$lang = new TexterLang($resource);
			$this->getLogger()->debug("Loaded language file: {$lang->getLang()}.ini");
		}
		TexterLang::setConsoleLocale($this->config->getLocale());
		$this->lang = TexterLang::fromConsole();
		$message = $this->lang->translateString("language.selected", [
			$this->lang->getName(),
			$this->lang->getLang(),
		]);
		$this->getLogger()->info(TextFormat::GREEN . $message);

		if ($this->config->isUpdater()) {
			$message = $this->lang->translateString("on.load.is.updater");
			$this->getLogger()->notice($message);
		}
	}

	private function registerCommands() {
		if ($isCanUse = $this->config->isCanUseCommands()) {
			$commandMap = $this->getServer()->getCommandMap();
			$commandMap->register($this->getName(), new TexterCommand($this), TexterCommand::NAME);
		}
		$message = $this->lang->translateString("on.load.commands." . ($isCanUse ? "on" : "off"));
		$this->getLogger()->info(($isCanUse ? TextFormat::GREEN : TextFormat::RED) . $message);
	}

	private function convertOldFloatingTexts() {
		$floatingTextDir = $this->getDataFolder() . FloatingTextData::FLOATING_TEXT_DIRECTORY;
		if (!file_exists($floatingTextDir)) {
			mkdir($floatingTextDir, 0755, true);
		}

		$dir = $this->getDataFolder();
		if (file_exists($dir . OldFloatingTextData::FILE_FT)) {
			$this->getLogger()->notice($this->lang->translateString("on.load.old.format.converting", [
				OldFloatingTextData::FILE_FT
			]));
			$ftFile = new OldFloatingTextData($this, $dir, OldFloatingTextData::FILE_FT);
			$ftFile->convert();
		}
		if (file_exists($dir . OldFloatingTextData::FILE_UFT)) {
			$this->getLogger()->notice($this->lang->translateString("on.load.old.format.converting", [
				OldFloatingTextData::FILE_UFT
			]));
			$uftFile = new OldFloatingTextData($this, $dir, OldFloatingTextData::FILE_UFT);
			$uftFile->convert();
		}
	}

	private function loadFloatingTexts() {
		$this->getScheduler()->scheduleDelayedTask(
			new ClosureTask(function () {
				$defaultWorldFolderName = $this->getServer()->getWorldManager()->getDefaultWorld()->getFolderName();
				$floatingTextData = new FloatingTextData($this, $defaultWorldFolderName);
				$floatingTextData->generateFloatingTexts($this);
				$this->getLogger()->debug("Loaded FloatingText file: $defaultWorldFolderName.json");
			}),
			$this->isDebug() ? 5 * 20 : 1
		);
	}

	public function checkUpdate() {
		if ($this->config->isCheckUpdate()) {
			try {
				$this->getServer()->getAsyncPool()->submitTask(new CheckUpdateTask);
			} catch (Exception $ex) {
				$this->getLogger()->warning($ex->getMessage());
			}
		}
	}

	public function compareVersion(bool $success, ?VersionString $latest = null, string $url = "") {
		if ($success) {
			$verStr = $this->getDescription()->getVersion();
			$current = new VersionString($verStr);
			switch ($current->compare($latest)) {
				case -1:// current > latest
					$message = $this->lang->translateString("on.load.version.dev");
					$this->getLogger()->warning($message);
					break;

				case 0:// current = latest
					$message = $this->lang->translateString("on.load.update.nothing", [
						$verStr,
					]);
					$this->getLogger()->notice($message);
					break;

				case 1:// current < latest
					$messages = [
						$this->lang->translateString("on.load.update.available.1", [
							$latest->getFullVersion(),
							$verStr,
						]),
						$this->lang->translateString("on.load.update.available.2"),
						$this->lang->translateString("on.load.update.available.3", [
							$url,
						]),
					];
					foreach ($messages as $message) {
						$this->getLogger()->notice($message);
					}
			}
		}else {
			$message = $this->lang->translateString("on.load.update.offline");
			$this->getLogger()->notice($message);
		}
	}

	private function checkPackaged(): bool {
		if ($this->isPhar() && $this->isPackagedByPharynx()) {
			return true; // pharynx
		}elseif (Main::canLoadDependencyFromComposer()) {
			Main::loadDependency();
			if (class_exists(Dependencies::PMFORMS)) {
				return true; // developer
			}
		}

		$message = $this->lang->translateString("error.on.enable.not.packaged");
		$this->getLogger()->critical($message);
		return false;
	}

	private function unlinkRecursive(string $dir): bool {
		$files = array_diff(scandir($dir), [".", ".."]);
		foreach ($files as $file) {
			$path = $dir . DIRECTORY_SEPARATOR . $file;
			is_dir($path) ? $this->unlinkRecursive($path) : unlink($path);
		}
		return rmdir($dir);
	}

	private function getFileExtension(string $path): string {
		$exploded = explode(".", $path);
		return $exploded[array_key_last($exploded)];
	}

	private function isDebug(): bool {
		return Main::canLoadDependencyFromComposer() && class_exists(Dependencies::PMFORMS);
	}

	private function isPhar(): bool {
		return str_starts_with($this->getFile(), self::PHAR_HEADER);
	}

	private function isPackagedByPharynx(): bool {
		return is_file($this->getFile() . Dependencies::PACKAGED_POGGIT_FILE);
	}

	public static function prefix(): string {
		return self::$prefix;
	}

	private function setPrefix() {
		self::$prefix = "[{$this->getDescription()->getPrefix()}]";
	}

}