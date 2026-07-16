package jp.mcbe.fuyutsuki.Texter.task;

import jp.mcbe.fuyutsuki.Texter.data.FloatingTextData;
import jp.mcbe.fuyutsuki.Texter.i18n.TexterLang;
import jp.mcbe.fuyutsuki.Texter.text.FloatingTextCluster;
import org.powernukkitx.plugin.PluginBase;
import org.powernukkitx.scheduler.Task;
import org.powernukkitx.utils.TextFormat;

import java.util.ArrayDeque;
import java.util.Deque;
import java.util.LinkedHashMap;
import java.util.Map;

public class PrepareTextsTask extends Task {
	public static final int TICKING_PERIOD = 2;

	private final PluginBase plugin;
	private final FloatingTextData data;

	private final Deque<String> names;
	private final Deque<Object> remain;

	public PrepareTextsTask(PluginBase plugin, FloatingTextData data) {
		this.plugin = plugin;
		this.data = data;

		String folderName = data.folderName();

		if (!plugin.getServer().isLevelLoaded(folderName)) {
			plugin.getServer().loadLevel(folderName);
		}

		Map<String, Object> all = new LinkedHashMap<>(data.getAll());
		this.names = new ArrayDeque<>(all.keySet());
		this.remain = new ArrayDeque<>(all.values());
	}

	@Override
	public void onRun(int currentTick) {
		if (remain.isEmpty()) {
			onSuccess();
		} else {
			String name = names.poll();
			Object value = remain.poll();
			if (value instanceof Map<?, ?> map) {
				@SuppressWarnings("unchecked")
				Map<String, Object> castedMap = (Map<String, Object>) map;
				FloatingTextCluster floatingText = FloatingTextCluster.fromMap(name, castedMap);
				data.store(floatingText, true);
			}
		}
	}

	public void onSuccess() {
		if (plugin.isEnabled()) {
			String message = TexterLang.fromConsole().tr("on.enable.prepared",
				data.folderName(),
				String.valueOf(data.floatingTexts().size())
			);
			plugin.getLogger().info(TextFormat.GREEN + message);
			this.getTaskId();
			plugin.getServer().getScheduler().cancelTask(this.getTaskId());
		}
	}
}
