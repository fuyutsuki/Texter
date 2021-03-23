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

use jp\mcbe\fuyutsuki\Texter\{
	i18n\TexterLang,
	util\Singleton};
use pocketmine\{
	plugin\PluginBase,
	utils\Config};
use function strtolower;

/**
 * Class ConfigData
 * @package jp\mcbe\fuyutsuki\Texter\data
 */
class ConfigData extends Config {

	use Singleton {
		Singleton::__construct as singletonConstruct;
	}

	public const FILE_NAME = "config.yml";

	public function __construct(PluginBase $plugin) {
		$plugin->saveResource(self::FILE_NAME);
		parent::__construct($plugin->getDataFolder() . self::FILE_NAME, Config::YAML);
		$this->singletonConstruct();
	}

	public function isUpdater(): bool {
		return $this->exists("world");// < 4.0.0
	}

	public function getLocale(): string {
		return strtolower($this->get("locale", TexterLang::FALLBACK_LANGUAGE));
	}

	public function isCheckUpdate(): bool {
		return (bool) $this->get("check.update", true);
	}

	public function isCanUseCommands(): bool {
		return (bool) $this->get("can.use.commands", true);
	}

}