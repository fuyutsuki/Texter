package jp.mcbe.fuyutsuki.Texter;

import jp.mcbe.fuyutsuki.Texter.data.FloatingTextData;
import jp.mcbe.fuyutsuki.Texter.task.SendTextsTask;
import jp.mcbe.fuyutsuki.Texter.text.SendType;
import lombok.Getter;
import org.powernukkitx.Player;
import org.powernukkitx.Server;
import org.powernukkitx.entity.Entity;
import org.powernukkitx.event.EventHandler;
import org.powernukkitx.event.Listener;
import org.powernukkitx.event.entity.EntityTeleportEvent;
import org.powernukkitx.event.level.LevelLoadEvent;
import org.powernukkitx.event.player.PlayerJoinEvent;
import org.powernukkitx.level.Level;
import org.powernukkitx.plugin.PluginBase;

@Getter
public class EventListener implements Listener {
	private PluginBase plugin;

	public EventListener(PluginBase plugin) {
		this.plugin = plugin;
	}

	@EventHandler
	public void onJoin(PlayerJoinEvent event) {
		Player player = event.getPlayer();
		Level level = player.getLevel();

		SendTextsTask sendTask = new SendTextsTask(player, level, SendType.ADD);
		Server.getInstance().getScheduler().scheduleDelayedRepeatingTask(sendTask, SendTextsTask.DELAY_TICKS, SendTextsTask.TICKING_PERIOD);
	}

	@EventHandler
	public void onLoadLevel(LevelLoadEvent event) {
		String folderName = event.getLevel().getFolderName();
		if (FloatingTextData.getInstance(folderName) == null) {
			FloatingTextData floatingTextData = new FloatingTextData(plugin, folderName);
			floatingTextData.generateFloatingTexts(plugin);
			Server.getInstance().getLogger().info("Loaded FloatingTextCluster file: " + folderName + ".json");
		}
	}

	@EventHandler
	public void onEntityLevelChange(EntityTeleportEvent event) {
		Entity entity = event.getEntity();
		if (entity instanceof Player) {
			Level from = event.getFrom().getLevel();
			Level to = event.getTo().getLevel();

			SendTextsTask removeTask = new SendTextsTask((Player) entity, from, SendType.REMOVE);
			SendTextsTask addTask = new SendTextsTask((Player) entity, to, SendType.ADD);

			Server.getInstance().getScheduler().scheduleDelayedRepeatingTask(removeTask, SendTextsTask.DELAY_TICKS, SendTextsTask.TICKING_PERIOD);
			Server.getInstance().getScheduler().scheduleDelayedRepeatingTask(addTask, SendTextsTask.DELAY_TICKS, SendTextsTask.TICKING_PERIOD);
		}
	}
}
