package jp.mcbe.fuyutsuki.Texter.text;

import org.powernukkitx.Player;
import org.powernukkitx.level.Level;

import java.util.Collection;

public interface Sendable {

	void sendToPlayer(Player player, SendType type);

	void sendToPlayers(Collection<? extends Player> players, SendType type);

	void sendToLevel(Level level, SendType type);
}