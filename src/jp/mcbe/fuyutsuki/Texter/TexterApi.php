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

use jp\mcbe\fuyutsuki\Texter\data\FloatingTextData;
use jp\mcbe\fuyutsuki\Texter\text\FloatingTextCluster;
use jp\mcbe\fuyutsuki\Texter\util\Imconstructable;
use pocketmine\level\Level;

/**
 * Class TexterApi
 * @package jp\mcbe\fuyutsuki\Texter
 */
final class TexterApi {

	use Imconstructable;

	/**
	 * Register FloatingTextCluster to the TexterAPI to show/hide floating text when
	 * moving between worlds on a server with multiple worlds.
	 * @param Level $level
	 * @param FloatingTextCluster $floatingText
	 * @return bool registrable?
	 */
	public static function register(Level $level, FloatingTextCluster $floatingText): bool {
		$floatingTextData = FloatingTextData::getInstance($level->getFolderName());
		if ($floatingTextData->notExistsFloatingText($floatingText->name())) {
			$floatingTextData->store($floatingText);
			$floatingTextData->save();
			return true;
		}
		return false;
	}

	/**
	 * Unregister FloatingTextCluster.
	 * @param Level $level
	 * @param FloatingTextCluster $floatingText
	 * @return bool unregistered?
	 */
	public static function unregister(Level $level, FloatingTextCluster $floatingText): bool {
		$floatingTextData = FloatingTextData::getInstance($level->getFolderName());
		if ($floatingTextData->existsFloatingText($floatingText->name())) {
			$floatingTextData->removeFloatingText($floatingText->name());
			$floatingTextData->save();
			return true;
		}
		return false;
	}



}