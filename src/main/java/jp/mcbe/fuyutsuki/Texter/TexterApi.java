package jp.mcbe.fuyutsuki.Texter;

import jp.mcbe.fuyutsuki.Texter.data.FloatingTextData;
import jp.mcbe.fuyutsuki.Texter.text.FloatingTextCluster;
import org.powernukkitx.level.Level;


public final class TexterApi {

	private TexterApi() { }

	/**
	 * Register FloatingTextCluster to the TexterAPI to show/hide floating text when
	 * moving between levels on a server with multiple levels.
	 *
	 * @param level        the level
	 * @param floatingText the FloatingTextCluster to register
	 * @return true if registered
	 */
	public static boolean register(Level level, FloatingTextCluster floatingText) {
		FloatingTextData floatingTextData = FloatingTextData.getInstance(level.getFolderName());
		if (floatingTextData.notExistsFloatingText(floatingText.name(), false)) {
			floatingTextData.store(floatingText, false);
			floatingTextData.save();
			return true;
		}
		return false;
	}

	/**
	 * Unregister FloatingTextCluster.
	 *
	 * @param level        the level
	 * @param floatingText the FloatingTextCluster to unregister
	 * @return true if unregistered
	 */
	public static boolean unregister(Level level, FloatingTextCluster floatingText) {
		FloatingTextData floatingTextData = FloatingTextData.getInstance(level.getFolderName());
		if (floatingTextData.existsFloatingText(floatingText.name(), false)) {
			floatingTextData.removeFloatingText(floatingText.name(), false);
			floatingTextData.save();
			return true;
		}
		return false;
	}
}