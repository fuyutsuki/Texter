package jp.mcbe.fuyutsuki.Texter.text;

import com.google.gson.JsonObject;
import jp.mcbe.fuyutsuki.Texter.data.Data;
import jp.mcbe.fuyutsuki.Texter.util.Serializable;
import lombok.Setter;
import org.powernukkitx.Player;
import org.powernukkitx.level.Level;
import org.powernukkitx.math.Vector3;

import java.util.*;

public class FloatingTextCluster extends Nameable implements Sendable, Serializable {

	@Setter
	private Vector3 position;
	private Vector3 spacing;

	private final List<FloatingText> floatingTexts = new ArrayList<>();

	public FloatingTextCluster(Vector3 position, String name, Vector3 spacing, List<String> texts) {
		super(name);
		this.position = position;
		setSpacing(spacing);
		generateFloatingText(texts);
	}

	@Override
	public String getName() {
		return name;
	}

	public Vector3 position() {
		return position;
	}

	public Vector3 spacing() {
		return spacing;
	}

	public void setSpacing(Vector3 spacing) {
		this.spacing = spacing != null ? spacing : new Vector3(0, 0, 0);
	}

	public Vector3 calculateSpacing(int index) {
		return position.add(spacing.multiply(index));
	}

	public void recalculatePosition() {
		for (int index = 0; index < floatingTexts.size(); index++) {
			FloatingText floatingText = floatingTexts.get(index);
			if (floatingText != null) {
				floatingText.setPosition(calculateSpacing(index));
			}
		}
	}

	public void generateFloatingText(List<String> texts) {
		for (int index = 0; index < texts.size(); index++) {
			floatingTexts.add(new FloatingText(
				calculateSpacing(index),
				texts.get(index),
				this
			));
		}
	}

	public List<FloatingText> all() {
		return floatingTexts;
	}

	public FloatingText get(int index) {
		if (index < 0 || index >= floatingTexts.size()) {
			return null;
		}
		return floatingTexts.get(index);
	}

	public void append(FloatingText floatingText) {
		floatingTexts.add(floatingText);
	}

	public void update(int index, FloatingText floatingText) {
		floatingTexts.set(index, floatingText);
	}

	public void remove(int index) {
		if (index >= 0 && index < floatingTexts.size()) {
			floatingTexts.set(index, null);
		}
	}

	public void resetIndex() {
		floatingTexts.removeIf(java.util.Objects::isNull);
	}

	@Override
	public void sendToPlayer(Player player, SendType type) {
		for (FloatingText floatingText : floatingTexts) {
			if (floatingText != null) {
				floatingText.sendToPlayer(player, type);
			}
		}
	}

	@Override
	public void sendToPlayers(Collection<? extends Player> players, SendType type) {
		for (FloatingText floatingText : floatingTexts) {
			if (floatingText != null) {
				floatingText.sendToPlayers(players, type);
			}
		}
	}

	@Override
	public void sendToLevel(Level world, SendType type) {
		for (FloatingText floatingText : floatingTexts) {
			if (floatingText != null) {
				floatingText.sendToLevel(world, type);
			}
		}
	}

	@Override
	public Map<String, Object> jsonSerialize() {
		Vector3 rounded = round(position, 1);

		Map<String, Object> result = new LinkedHashMap<>();
		result.put(Data.KEY_X, rounded.x);
		result.put(Data.KEY_Y, rounded.y);
		result.put(Data.KEY_Z, rounded.z);

		if (floatingTexts.size() >= 2) {
			Map<String, Object> spacingMap = new LinkedHashMap<>();
			spacingMap.put(Data.KEY_X, spacing.x);
			spacingMap.put(Data.KEY_Y, spacing.y);
			spacingMap.put(Data.KEY_Z, spacing.z);
			result.put(Data.KEY_SPACING, spacingMap);
		}

		resetIndex();

		List<String> texts = new ArrayList<>();
		for (FloatingText floatingText : floatingTexts) {
			texts.add(floatingText.text());
		}
		result.put(Data.KEY_TEXTS, texts);

		return result;
	}

	@SuppressWarnings("unchecked")
	public static FloatingTextCluster fromMap(String name, Map<String, Object> map) {
		Vector3 spacing = null;
		if (map.containsKey(Data.KEY_SPACING)) {
			Map<String, Object> spacingMap = (Map<String, Object>) map.get(Data.KEY_SPACING);
			spacing = new Vector3(
				((Number) spacingMap.get(Data.KEY_X)).doubleValue(),
				((Number) spacingMap.get(Data.KEY_Y)).doubleValue(),
				((Number) spacingMap.get(Data.KEY_Z)).doubleValue()
			);
		}

		Vector3 position = new Vector3(
			((Number) map.get(Data.KEY_X)).doubleValue(),
			((Number) map.get(Data.KEY_Y)).doubleValue(),
			((Number) map.get(Data.KEY_Z)).doubleValue()
		);

		List<String> texts = (List<String>) map.get(Data.KEY_TEXTS);

		return new FloatingTextCluster(position, name, spacing, texts);
	}

	private static Vector3 round(Vector3 vector, int precision) {
		double factor = Math.pow(10, precision);
		return new Vector3(
			Math.round(vector.x * factor) / factor,
			Math.round(vector.y * factor) / factor,
			Math.round(vector.z * factor) / factor
		);
	}
}