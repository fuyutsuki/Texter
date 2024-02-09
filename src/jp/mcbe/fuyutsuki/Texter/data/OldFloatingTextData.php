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

use jp\mcbe\fuyutsuki\Texter\text\FloatingTextCluster;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class OldFloatingTextData extends Config {

	public const FILE_FT = "ft.json";
	public const FILE_UFT = "uft.json";

	public function __construct(
		private PluginBase $plugin,
		string $path,
		string $file
	) {
		parent::__construct($path.$file, Config::JSON);
	}

	public function convert() {
		$fts = $this->getAll();
		foreach ($fts as $worldName => $texts) {
			$floatingTextData = FloatingTextData::getInstance($worldName);
			if ($floatingTextData === null) {
				$floatingTextData = new FloatingTextData($this->plugin, $worldName);
			}

			foreach ($texts as $textName => $val) {
				$arr = [
					Data::KEY_X => (float) $val[Data::KEY_OLD_X],
					Data::KEY_Y => (float) $val[Data::KEY_OLD_Y],
					Data::KEY_Z => (float) $val[Data::KEY_OLD_Z],
					Data::KEY_TEXTS => [
						$val[Data::KEY_OLD_TITLE] . "#" . $val[Data::KEY_OLD_TEXT]
					],
				];
				$floatingTextCluster = FloatingTextCluster::fromArray((string)$textName, $arr);
				$floatingTextData->store($floatingTextCluster);
			}

			$floatingTextData->save();
		}
		$this->removeFile();
	}

	private function removeFile() {
		unlink($this->getPath());
	}

}