package jp.mcbe.fuyutsuki.Texter.text;

import com.google.gson.JsonObject;
import org.powernukkitx.Player;
import org.powernukkitx.level.Level;

import java.util.Collection;

public class FloatingTextCluster extends Nameable implements Sendable {

	public FloatingTextCluster(String name) {
		super(name);
	}

	@Override
	public void sendToPlayer(Player player, SendType type) {
		// ...
	}

	@Override
	public void sendToPlayers(Collection<? extends Player> players, SendType type) {
		// ...
	}

	@Override
	public void sendToLevel(Level level, SendType type) {
		// ...
	}

	@Override
	public String getName() {
		return name;
	}

	public JsonObject toJson() {
		JsonObject json = new JsonObject();
		json.addProperty("name", name());
		return json;
	}
}