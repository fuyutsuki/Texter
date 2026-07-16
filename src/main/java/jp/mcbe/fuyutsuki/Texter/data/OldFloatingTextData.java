package jp.mcbe.fuyutsuki.Texter.data;

import jp.mcbe.fuyutsuki.Texter.text.FloatingTextCluster;
import org.powernukkitx.plugin.PluginBase;
import org.powernukkitx.utils.Config;

import java.io.File;
import java.util.ArrayList;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;

public final class OldFloatingTextData extends Config {

	public static final String FILE_FT = "ft.json";
	public static final String FILE_UFT = "uft.json";

	private final String filePath;

	private final PluginBase plugin;

	public OldFloatingTextData(PluginBase plugin, String path, String file) {
		super(path + file, Config.JSON);
		this.plugin = plugin;
		this.filePath = path + file;
	}

	@SuppressWarnings("unchecked")
	public void convert() {
		Map<String, Object> fts = this.getAll();

		for (Map.Entry<String, Object> worldEntry : fts.entrySet()) {
			String worldName = worldEntry.getKey();
			Map<String, Object> texts = (Map<String, Object>) worldEntry.getValue();

			FloatingTextData floatingTextData = FloatingTextData.getInstance(worldName);
			if (floatingTextData == null) {
				floatingTextData = new FloatingTextData(plugin, worldName);
			}

			for (Map.Entry<String, Object> textEntry : texts.entrySet()) {
				String textName = textEntry.getKey();
				Map<String, Object> val = (Map<String, Object>) textEntry.getValue();

				Map<String, Object> arr = new LinkedHashMap<>();
				arr.put(Data.KEY_X, ((Number) val.get(Data.KEY_OLD_X)).doubleValue());
				arr.put(Data.KEY_Y, ((Number) val.get(Data.KEY_OLD_Y)).doubleValue());
				arr.put(Data.KEY_Z, ((Number) val.get(Data.KEY_OLD_Z)).doubleValue());

				List<String> texts_ = new ArrayList<>();
				texts_.add(val.get(Data.KEY_OLD_TITLE) + "#" + val.get(Data.KEY_OLD_TEXT));
				arr.put(Data.KEY_TEXTS, texts_);

				FloatingTextCluster floatingTextCluster = FloatingTextCluster.fromMap(textName, arr);
				floatingTextData.store(floatingTextCluster, false);
			}

			floatingTextData.save();
		}

		removeFile();
	}

	private void removeFile() {
		new File(this.filePath).delete();
	}
}