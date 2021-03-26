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

use aieuo\mineflow\Main as MineflowMain;
use aieuo\mineflow\variable\DefaultVariables;
use http\Exception;
use jp\mcbe\fuyutsuki\Texter\command\TexterCommand;
use jp\mcbe\fuyutsuki\Texter\data\ConfigData;
use jp\mcbe\fuyutsuki\Texter\data\FloatingTextData;
use jp\mcbe\fuyutsuki\Texter\data\OldFloatingTextData;
use jp\mcbe\fuyutsuki\Texter\i18n\MineflowLang;
use jp\mcbe\fuyutsuki\Texter\i18n\TexterLang;
use jp\mcbe\fuyutsuki\Texter\task\CheckUpdateTask;
use jp\mcbe\fuyutsuki\Texter\util\dependencies\Dependencies;
use jp\mcbe\fuyutsuki\Texter\util\dependencies\Mineflow;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\utils\VersionString;
use function array_key_last;
use function class_exists;
use function explode;
use function file_exists;
use function glob;
use function mkdir;

/**
 * Class Main
 * @package jp\mcbe\fuyutsuki\Texter
 */
class Main extends PluginBase {

	/** @var string */
	private static $prefix;

	/** @var ConfigData */
	private $config;
	/** @var TexterLang */
	private $lang;

	public function onLoad() {
		self::setPrefix();
		$this->loadResources();
		$this->registerCommands();
		$this->loadFloatingTexts();
		$this->checkUpdate();
	}

	public function onEnable() {
		$pluginManager = $this->getServer()->getPluginManager();
		if ($this->isPackaged()) {
			$pluginManager->registerEvents(new EventListener($this), $this);
			$this->mineflowLinkage();
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
			$folderName = $this->getFileName($resource->getPath());
			$fileName = $resource->getFileName();
			$extension = $this->getFileExtension($fileName);
			if ($extension !== TexterLang::LANGUAGE_EXTENSION) continue;

			if ($folderName === "mineflow") {
				$lang = new MineflowLang($resource);
				$this->getLogger()->debug("Loaded language file: {$folderName}\\{$lang->getLang()}.ini");
			}else {
				$lang = new TexterLang($resource);
				$this->getLogger()->debug("Loaded language file: {$lang->getLang()}.ini");
			}
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

	private function loadFloatingTexts() {
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

		$worldsPath = $this->findWorldsPath();
		foreach ($worldsPath as $worldPath) {
			$folderName = $this->getFileName($worldPath);
			$floatingTextData = FloatingTextData::getInstance($folderName);
			if ($floatingTextData === null) {
				$floatingTextData = new FloatingTextData($this, $folderName);
			}
			$floatingTextData->generateFloatingTexts($this);
			$this->getLogger()->debug("Loaded FloatingText file: {$folderName}.json");
		}
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

	private function mineflowLinkage() {
		$mineflow = $this->getServer()->getPluginManager()->getPlugin(Dependencies::SOFT_MINEFLOW);
		if ($mineflow !== null) {
			/** @var MineflowMain $mineflow */
			Mineflow::setAvailable();

			$variableHelper = $mineflow::getVariableHelper();
			foreach (DefaultVariables::getServerVariables() as $varName => $defaultVariable) {
				$variableHelper->add($varName, $defaultVariable);
			}
			Mineflow::setVariableHelper($variableHelper);
		}
	}

	private function isPackaged(): bool {
		if ($this->isPhar()) {
			if (class_exists(Dependencies::PACKAGED_LIBRARY_NAMESPACE . Dependencies::LIB_FORM_API)) {
				return true;// PoggitCI
			}else {
				$message = $this->lang->translateString("error.on.enable.not.packaged");
				$this->getLogger()->critical($message);
				return false;
			}
		}else {
			$plugins = $this->getServer()->getPluginManager()->getPlugins();
			if (isset($plugins["DEVirion"])) {
				if (class_exists(Dependencies::LIB_FORM_API)) {
					return true;// developer
				}else {
					$message = $this->lang->translateString("error.on.enable.not.found.libformapi");
					$this->getLogger()->critical($message);
					return false;
				}
			}else {
				$message = $this->lang->translateString("error.on.enable.not.packaged");
				$this->getLogger()->critical($message);
				return false;
			}
		}
	}

	private function unlinkRecursive(string $dir): bool {
		$files = array_diff(scandir($dir), [".", ".."]);
		foreach ($files as $file) {
			$path = $dir . DIRECTORY_SEPARATOR . $file;
			is_dir($path) ? $this->unlinkRecursive($path) : unlink($path);
		}
		return rmdir($dir);
	}

	private function getFileName(string $path): string {
		$exploded = explode(DIRECTORY_SEPARATOR, $path);
		return $exploded[array_key_last($exploded)];
	}

	private function getFileExtension(string $path): string {
		$exploded = explode(".", $path);
		return $exploded[array_key_last($exploded)];
	}

	private function getWorldsPath(): string {
		return $this->getServer()->getDataPath() . "worlds" . DIRECTORY_SEPARATOR;
	}

	private function findWorldsPath(): array {
		return glob($this->getWorldsPath() . "*");
	}

	public static function prefix(): string {
		return self::$prefix;
	}

	private function setPrefix() {
		self::$prefix = "[{$this->getDescription()->getPrefix()}]";
	}

}