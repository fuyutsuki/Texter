package jp.mcbe.fuyutsuki.Texter.data;

import jp.mcbe.fuyutsuki.Texter.i18n.TexterLang;
import jp.mcbe.fuyutsuki.Texter.util.SingletonHolder;
import org.powernukkitx.plugin.PluginBase;
import org.powernukkitx.utils.Config;

public final class ConfigData extends Config {

	public static final String FILE_NAME = "config.yml";

	private static final SingletonHolder<ConfigData> INSTANCE = new SingletonHolder<>();

	public ConfigData(PluginBase plugin) {
		plugin.saveResource(FILE_NAME);
		super(
			plugin.getDataFolder() + FILE_NAME,
			Config.YAML
		);
		INSTANCE.register(this);
	}

	public static ConfigData getInstance() {
		return INSTANCE.get();
	}

	public boolean isUpdater() {
		// < 4.0.0
		return this.exists("world");
	}

	public String getLocale() {
		return this.getString("locale", TexterLang.FALLBACK_LANGUAGE).toLowerCase();
	}

	public boolean isCheckUpdate() {
		return this.getBoolean("check.update", true);
	}

	public boolean isCanUseCommands() {
		return this.getBoolean("can.use.commands", true);
	}
}