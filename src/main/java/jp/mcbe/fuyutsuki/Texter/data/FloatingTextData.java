package jp.mcbe.fuyutsuki.Texter.data;

import jp.mcbe.fuyutsuki.Texter.task.PrepareTextsTask;
import jp.mcbe.fuyutsuki.Texter.text.FloatingTextCluster;
import jp.mcbe.fuyutsuki.Texter.util.StringArrayMultitonRegistry;
import org.powernukkitx.plugin.PluginBase;
import org.powernukkitx.utils.Config;

import java.io.File;
import java.util.LinkedHashMap;
import java.util.Map;

public final class FloatingTextData extends Config {

	public static final String FLOATING_TEXT_DIRECTORY = "floating_text" + File.separator;

	private static final StringArrayMultitonRegistry<FloatingTextData> INSTANCES = new StringArrayMultitonRegistry<>();

	private final String folderName;

	private final Map<String, FloatingTextCluster> floatingTexts = new LinkedHashMap<>();

	public FloatingTextData(PluginBase plugin, String worldFolderName) {
		super(plugin.getDataFolder() + FLOATING_TEXT_DIRECTORY + worldFolderName + ".json", Config.JSON);

		this.folderName = worldFolderName;
		INSTANCES.register(worldFolderName, this);
	}

	public static FloatingTextData getInstance(String key) {
		return INSTANCES.get(key);
	}

	public static void removeInstance(String key) {
		INSTANCES.remove(key);
	}

	public void generateFloatingTexts(PluginBase plugin) {
		PrepareTextsTask prepare = new PrepareTextsTask(plugin, this);
		plugin.getServer().getScheduler().scheduleRepeatingTask(
			plugin, prepare, PrepareTextsTask.TICKING_PERIOD
		);
	}

	public String folderName() {
		return folderName;
	}

	public Map<String, FloatingTextCluster> floatingTexts() {
		return floatingTexts;
	}

	public FloatingTextCluster floatingText(String name) {
		return floatingTexts.get(name);
	}

	public void store(FloatingTextCluster text, boolean onlyCache) {
		String name = text.name();
		floatingTexts.put(name, text);
		if (!onlyCache) {
			this.set(name, text);
		}
	}

	public void removeFloatingText(String name, boolean onlyCache) {
		floatingTexts.remove(name);
		if (!onlyCache) {
			this.remove(name);
		}
	}

	public boolean existsFloatingText(String name, boolean onlyCache) {
		boolean result = floatingTexts.containsKey(name);
		if (!onlyCache) {
			result = result && this.exists(name, true);
		}
		return result;
	}

	public boolean notExistsFloatingText(String name, boolean onlyCache) {
		return !existsFloatingText(name, onlyCache);
	}
}