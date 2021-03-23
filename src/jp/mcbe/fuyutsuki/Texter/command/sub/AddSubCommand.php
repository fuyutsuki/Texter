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

namespace jp\mcbe\fuyutsuki\Texter\command\sub;

use jp\mcbe\fuyutsuki\Texter\command\form\FloatingTextSession;
use jp\mcbe\fuyutsuki\Texter\data\FloatingTextData;
use jp\mcbe\fuyutsuki\Texter\i18n\TexterLang;
use jp\mcbe\fuyutsuki\Texter\Main;
use jp\mcbe\fuyutsuki\Texter\text\FloatingTextCluster;
use jp\mcbe\fuyutsuki\Texter\text\SendType;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

/**
 * Class AddSubCommand
 * @package jp\mcbe\fuyutsuki\Texter\command\sub
 */
class AddSubCommand extends TexterSubCommand {

	public const NAME = "add";
	public const ALIAS = "a";

	/** @var string */
	private $name;
	/** @var string */
	private $text;

	public function __construct(string $name, string $text) {
		$this->name = $name;
		$this->text = $text;
	}

	public function execute(Player $player) {
		$level = $player->getLevel();
		$folderName = $level->getFolderName();
		$floatingTextData = FloatingTextData::getInstance($folderName);
		$lang = TexterLang::fromLocale($player->getLocale());

		if ($floatingTextData->notExistsFloatingText($this->name)) {
			$floatingText = new FloatingTextCluster($player->up(), $this->name, null, [$this->text]);
			$floatingText->sendToLevel($level, new SendType(SendType::ADD));
			$floatingTextData->store($floatingText);
			$floatingTextData->save();
			FloatingTextSession::remove($player->getLowerCaseName());
			$message = TextFormat::GREEN . $lang->translateString("command.txt.add.success", [
				$this->name
			]);
		}else {
			$message = TextFormat::RED . $lang->translateString("error.ft.name.exists", [
				$this->name
			]);
		}
		$player->sendMessage(Main::prefix() . " {$message}");
	}

}