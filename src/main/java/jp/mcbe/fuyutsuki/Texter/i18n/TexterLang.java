package jp.mcbe.fuyutsuki.Texter.i18n;

import jp.mcbe.fuyutsuki.Texter.util.StringArrayMultitonRegistry;
import lombok.Setter;
import org.powernukkitx.lang.BaseLang;

import java.io.File;

public class TexterLang extends BaseLang {

	public static final String LANGUAGE_EXTENSION = "ini";
	public static final String FALLBACK_LANGUAGE = "en_us";

	private static final StringArrayMultitonRegistry<TexterLang> INSTANCES = new StringArrayMultitonRegistry<>();

	@Setter
	private static String consoleLocale = FALLBACK_LANGUAGE;

	public TexterLang(File file) {
		super(
			stripExtension(file.getName()),
			file.getParent() + File.separator,
			FALLBACK_LANGUAGE
		);
		String locale = stripExtension(file.getName());
		INSTANCES.register(locale.toLowerCase(), this);
	}

	private static String stripExtension(String fileName) {
		String suffix = "." + TexterLang.LANGUAGE_EXTENSION;
		if (fileName.endsWith(suffix)) {
			return fileName.substring(0, fileName.length() - suffix.length());
		}
		return fileName;
	}

	public static TexterLang fromConsole() {
		return fromLocale(consoleLocale);
	}

	public static TexterLang fromLocale(String locale) {
		TexterLang lang = INSTANCES.get(locale.toLowerCase());
		if (lang != null) {
			return lang;
		}
		return INSTANCES.get(FALLBACK_LANGUAGE);
	}
}
