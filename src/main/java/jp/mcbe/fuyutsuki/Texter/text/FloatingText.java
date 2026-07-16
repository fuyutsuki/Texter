package jp.mcbe.fuyutsuki.Texter.text;

import lombok.Setter;
import org.cloudburstmc.protocol.bedrock.codec.ActorDataTypeMap;
import org.cloudburstmc.protocol.bedrock.data.actor.ActorDataMap;
import org.cloudburstmc.protocol.bedrock.data.actor.ActorDataType;
import org.cloudburstmc.protocol.bedrock.data.actor.ActorDataTypes;
import org.cloudburstmc.protocol.bedrock.data.actor.ActorFlags;
import org.cloudburstmc.protocol.bedrock.packet.AddActorPacket;
import org.cloudburstmc.protocol.bedrock.packet.BedrockPacket;
import org.cloudburstmc.protocol.bedrock.packet.RemoveActorPacket;
import org.cloudburstmc.protocol.bedrock.packet.SetActorDataPacket;
import org.powernukkitx.Player;
import org.powernukkitx.entity.Entity;
import org.powernukkitx.level.Level;
import org.powernukkitx.math.Vector3;
import org.powernukkitx.math.Vector3f;

import java.util.Collection;
import java.util.concurrent.atomic.AtomicLong;

public final class FloatingText implements Sendable {

	private Vector3 position;
	private String text;
	@Setter
	private FloatingTextCluster parent;
	private FloatingTextEntity entity;

	public FloatingText(Vector3 position, String text, FloatingTextCluster parent) {
		this.position = position;
		setText(text);
		setParent(parent);
	}

	public Vector3 position() { return position; }

	public void setPosition(Vector3 position) {
		this.position = position;
		if (entity != null) {
			entity.teleport(position);
		}
	}

	public String text() {
		return text.replace("\n", "#");
	}

	public void setText(String text) {
		this.text = text.replace("#", "\n");
		if (entity != null) {
			entity.setNameTag(this.text);
		}
	}

	public FloatingTextCluster parent() {
		return parent;
	}

	@Override
	public void sendToPlayer(Player player, SendType type) {
		switch (type) {
			case ADD -> {
				if (entity == null) {
					entity = FloatingTextEntity.spawn(player.getLevel(), position, text);
				} else {
					entity.spawnTo(player);
				}
			}
			case EDIT -> {
				if (entity != null) entity.setNameTag(text);
			}
			case MOVE -> {
				if (entity != null) entity.teleport(position);
			}
			case REMOVE -> {
				if (entity != null) entity.despawnFrom(player);
			}
		}
	}

	@Override
	public void sendToPlayers(Collection<? extends Player> players, SendType type) {
		for (Player player : players) {
			sendToPlayer(player, type);
		}
	}

	@Override
	public void sendToLevel(Level level, SendType type) {
		switch (type) {
			case ADD -> {
				if (entity == null) entity = FloatingTextEntity.spawn(level, position, text);
				else entity.spawnToAll();
			}
			case EDIT -> {
				if (entity != null) entity.setNameTag(text);
			}
			case MOVE -> {
				if (entity != null) entity.teleport(position);
			}
			case REMOVE -> {
				if (entity != null) entity.despawnFromAll();
			}
		}
	}
}