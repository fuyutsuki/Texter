package jp.mcbe.fuyutsuki.Texter;

import jp.mcbe.fuyutsuki.Texter.data.ConfigData;
import jp.mcbe.fuyutsuki.Texter.data.FloatingTextData;
import jp.mcbe.fuyutsuki.Texter.data.OldFloatingTextData;
import jp.mcbe.fuyutsuki.Texter.i18n.TexterLang;
import org.powernukkitx.plugin.PluginBase;
import org.powernukkitx.scheduler.Task;
import org.powernukkitx.utils.TextFormat;

import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Enumeration;
import java.util.List;
import java.util.jar.JarEntry;
import java.util.jar.JarFile;

public class Texter extends PluginBase {

	private TexterLang lang;

	@Override
	public void onLoad() {
		this.convertOldFloatingTexts();
		this.loadFloatingTexts();
	}

	@Override
	public void onEnable() {
		super.onEnable();
	}

	private void loadResources() {
		ConfigData config = new ConfigData(this);

		File oldLanguageDir = new File(getDataFolder(), "language");
		if (oldLanguageDir.exists()) {
			unlinkRecursive(oldLanguageDir);
		}

		for (String localeFileName : listLanguageResourceNames()) {
			saveResource("resources/language/" + localeFileName, false);
		}

		File extractedLanguageDir = new File(getDataFolder(), "language");
		File[] files = extractedLanguageDir.listFiles();
		if (files != null) {
			for (File file : files) {
				String extension = getFileExtension(file.getName());
				if (!extension.equals(TexterLang.LANGUAGE_EXTENSION)) continue;

				TexterLang lang = new TexterLang(file);
				getLogger().debug("Loaded language file: " + lang.getLang() + ".ini");
			}
		}

		TexterLang.setConsoleLocale(config.getLocale());
		this.lang = TexterLang.fromConsole();

		String message = lang.tr("language.selected",
			lang.getName(),
			lang.getLang()
		);
		getLogger().info(TextFormat.GREEN + message);

		if (config.isUpdater()) {
			String updaterMessage = lang.tr("on.load.is.updater");
			getLogger().notice(updaterMessage);
		}
	}

	private List<String> listLanguageResourceNames() {
		List<String> result = new ArrayList<>();
		String prefix = "resources/language/";

		File jarFile = new File(getClass().getProtectionDomain().getCodeSource().getLocation().getPath());

		try (JarFile jar = new JarFile(jarFile)) {
			Enumeration<JarEntry> entries = jar.entries();
			while (entries.hasMoreElements()) {
				JarEntry entry = entries.nextElement();
				String name = entry.getName();
				if (!entry.isDirectory() && name.startsWith(prefix) && name.endsWith("." + TexterLang.LANGUAGE_EXTENSION)) {
					result.add(name.substring("resources/".length()));
				}
			}
		} catch (IOException e) {
			getLogger().error("Could not scan jar for language resources", e);
		}

		return result;
	}

	private String getFileExtension(String fileName) {
		int dotIndex = fileName.lastIndexOf('.');
		return dotIndex >= 0 ? fileName.substring(dotIndex + 1) : "";
	}

	private void unlinkRecursive(File dir) {
		File[] files = dir.listFiles();
		if (files != null) {
			for (File file : files) {
				if (file.isDirectory()) {
					unlinkRecursive(file);
				} else {
					file.delete();
				}
			}
		}
		dir.delete();
	}

	private void convertOldFloatingTexts() {
		String floatingTextDir = getDataFolder() + FloatingTextData.FLOATING_TEXT_DIRECTORY;
		File floatingTextDirFile = new File(floatingTextDir);
		if (!floatingTextDirFile.exists()) {
			floatingTextDirFile.mkdirs();
		}

		File ftFileCheck = new File(getDataFolder(), OldFloatingTextData.FILE_FT);
		if (ftFileCheck.exists()) {
			getLogger().notice(lang.tr("on.load.old.format.converting", OldFloatingTextData.FILE_FT));
			OldFloatingTextData ftFile = new OldFloatingTextData(this, getDataFolder().getPath() + File.separator, OldFloatingTextData.FILE_FT);
			ftFile.convert();
		}

		File uftFileCheck = new File(getDataFolder(), OldFloatingTextData.FILE_FT);
		if (uftFileCheck.exists()) {
			getLogger().notice(lang.tr("on.load.old.format.converting", OldFloatingTextData.FILE_UFT));
			OldFloatingTextData uftFile = new OldFloatingTextData(this, getDataFolder().getPath() + File.separator, OldFloatingTextData.FILE_UFT);
			uftFile.convert();
		}
	}

	private void loadFloatingTexts() {
		getServer().getScheduler().scheduleDelayedTask(this, new Task() {
			@Override
			public void onRun(int currentTick) {
				String defaultWorldFolderName = getServer().getDefaultLevel().getFolderName();
				FloatingTextData floatingTextData = new FloatingTextData(Texter.this, defaultWorldFolderName);
				floatingTextData.generateFloatingTexts(Texter.this);
				getLogger().debug("Loaded FloatingText file: " + defaultWorldFolderName + ".json");
			}
		},5 * 20);
	}

	@Override
	public void onDisable() {
		super.onDisable();
	}
}
