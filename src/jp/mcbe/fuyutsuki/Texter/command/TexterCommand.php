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

namespace jp\mcbe\fuyutsuki\Texter\command;

use jp\mcbe\fuyutsuki\Texter\command\form\AddFloatingTextForm;
use jp\mcbe\fuyutsuki\Texter\command\form\ListFloatingTextForm;
use jp\mcbe\fuyutsuki\Texter\command\sub\AddSubCommand;
use jp\mcbe\fuyutsuki\Texter\command\sub\EditSubCommand;
use jp\mcbe\fuyutsuki\Texter\command\sub\MoveSubCommand;
use jp\mcbe\fuyutsuki\Texter\command\sub\RemoveSubCommand;
use jp\mcbe\fuyutsuki\Texter\i18n\TexterLang;
use jp\mcbe\fuyutsuki\Texter\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

/**
 * Class TexterCommand
 * @package jp\mcbe\fuyutsuki\Texter\command
 */
class TexterCommand extends PluginCommand {

	public const NAME = "txt";
	public const DESCRIPTION = "command.txt.description";
	public const USAGE = "command.txt.usage";
	public const PERMISSION = "texter.command.txt";

	public function __construct(Main $plugin) {
		parent::__construct(self::NAME, $plugin);
		$consoleLang = TexterLang::fromConsole();
		$description = $consoleLang->translateString(self::DESCRIPTION);
		$usage = $consoleLang->translateString(self::USAGE);
		$this->setDescription($description);
		$this->setUsage($usage);
		$this->setPermission(self::PERMISSION);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
		$plugin = $this->getPlugin();
		if ($plugin->isDisabled() || !$this->testPermission($sender)) return false;

		if ($sender instanceof Player) {
			$serverLang = $sender->getServer()->getLanguage();
			$playerLang = TexterLang::fromLocale($sender->getLocale());
			if (isset($args[0])) {
				$subCommandLabel = strtolower(array_shift($args));
				switch ($subCommandLabel) {
					case AddSubCommand::NAME:
					case AddSubCommand::ALIAS:
						if (!empty($args[0])) {
							$name = array_shift($args);
							if (!empty($args[0])) {
								$subCommand = new AddSubCommand($name, implode(" ", $args));
								$subCommand->execute($sender);
							}else {
								$message = $serverLang->translateString("commands.generic.usage", [
									$playerLang->translateString("command.txt.add.usage")
								]);
								$sender->sendMessage(Main::prefix() . " {$message}");
							}
						}else {
							AddFloatingTextForm::send($sender);
						}
						break;

					case EditSubCommand::NAME:
					case EditSubCommand::ALIAS:
						if (!empty($args[0])) {
							$name = array_shift($args);
							$subCommand = new EditSubCommand($name);
							$subCommand->execute($sender);
						}else {
							ListFloatingTextForm::send($sender, $subCommandLabel);
						}
						break;

					case MoveSubCommand::NAME:
					case MoveSubCommand::ALIAS:
						if (!empty($args[0])) {
							$name = array_shift($args);
							$subCommand = new MoveSubCommand($name);
							$argsCount = count($args);
							if (1 <= $argsCount && $argsCount <= 3) {
								if ($argsCount !== 2) {
									if ($args[0] === "here") {
										$subCommand->setPosition($sender);
									}
									if (!empty($args[0]) && !empty($args[1]) && !empty($args[2])) {
										$subCommand->setPositionByString($args[0], $args[1], $args[2]);
									}
									$subCommand->execute($sender);
									return true;
								}
							}
							$message = $serverLang->translateString("commands.generic.usage", [
								$playerLang->translateString("command.txt.move.usage")
							]);
							$sender->sendMessage(Main::prefix() . " {$message}");
						}else {
							ListFloatingTextForm::send($sender, $subCommandLabel);
						}
						break;

					case RemoveSubCommand::NAME:
					case RemoveSubCommand::ALIAS:
						if (!empty($args[0])) {
							$name = array_shift($args);
							$subCommand = new RemoveSubCommand($name);
							$subCommand->execute($sender);
						}else {
							$message = $serverLang->translateString("commands.generic.usage", [
								$playerLang->translateString("command.txt.remove.usage")
							]);
							$sender->sendMessage(Main::prefix() . " {$message}");
						}
						break;

					default:
						throw new InvalidCommandSyntaxException;
				}
			}else {
				throw new InvalidCommandSyntaxException;
			}
		}else {
			$message = TexterLang::fromConsole()->translateString("error.console");
			$plugin->getLogger()->info(TextFormat::RED . $message);
		}
		return true;
	}

}