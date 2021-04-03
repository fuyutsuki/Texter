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

use jp\mcbe\fuyutsuki\Texter\command\TexterCommand;
use jp\mcbe\fuyutsuki\Texter\data\FloatingTextData;
use jp\mcbe\fuyutsuki\Texter\i18n\TexterLang;
use jp\mcbe\fuyutsuki\Texter\task\SendTextsTask;
use jp\mcbe\fuyutsuki\Texter\text\SendType;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\level\LevelLoadEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

/**
 * Class EventListener
 * @package jp\mcbe\fuyutsuki\Texter
 */
class EventListener implements Listener {

	/** @var Plugin */
	private $plugin;

	public function __construct(Plugin $plugin) {
		$this->plugin = $plugin;
	}

	public function onJoinPlayer(PlayerJoinEvent $ev) {
		$player = $ev->getPlayer();
		$level = $player->getLevel();
		$sendTask = new SendTextsTask($this->plugin, $player, $level, new SendType(SendType::ADD));
		$this->plugin->getScheduler()->scheduleDelayedRepeatingTask($sendTask, SendTextsTask::DELAY_TICKS, SendTextsTask::TICKING_PERIOD);
	}

	public function onLoadLevel(LevelLoadEvent $ev) {
		$folderName = $ev->getLevel()->getFolderName();
		if (FloatingTextData::getInstance($folderName) === null) {
			$floatingTextData = new FloatingTextData($this->plugin, $folderName);
			$floatingTextData->generateFloatingTexts($this->plugin);
			$this->plugin->getLogger()->debug("Loaded FloatingTextCluster file: {$folderName}.json");
		}
	}

	public function onEntityLevelChange(EntityLevelChangeEvent $ev) {
		$entity = $ev->getEntity();
		if ($entity instanceof Player) {
			$from = $ev->getOrigin();
			$to = $ev->getTarget();
			$removeTask = new SendTextsTask($this->plugin, $entity, $from, new SendType(SendType::REMOVE));
			$addTask = new SendTextsTask($this->plugin, $entity, $to, new SendType(SendType::ADD));
			$scheduler = $this->plugin->getScheduler();
			$scheduler->scheduleDelayedRepeatingTask($removeTask, SendTextsTask::DELAY_TICKS, SendTextsTask::TICKING_PERIOD);
			$scheduler->scheduleDelayedRepeatingTask($addTask, SendTextsTask::DELAY_TICKS, SendTextsTask::TICKING_PERIOD);
		}
	}

	public function onSendPacket(DataPacketSendEvent $ev) {
		$pk = $ev->getPacket();
		if ($pk->pid() === ProtocolInfo::AVAILABLE_COMMANDS_PACKET) {
			/** @var AvailableCommandsPacket $pk */
			if (isset($pk->commandData[TexterCommand::NAME])) {
				$locale = $ev->getPlayer()->getLocale();
				$texterCommand = $pk->commandData[TexterCommand::NAME];
				$texterCommand->commandDescription = TexterLang::fromLocale($locale)->translateString(TexterCommand::DESCRIPTION);
			}
		}
	}

}