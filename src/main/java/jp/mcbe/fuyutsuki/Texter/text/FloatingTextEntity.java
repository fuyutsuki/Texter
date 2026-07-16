package jp.mcbe.fuyutsuki.Texter.text;

import org.cloudburstmc.protocol.bedrock.codec.v291.serializer.FullChunkDataSerializer_v291;
import org.jetbrains.annotations.NotNull;
import org.powernukkitx.entity.Entity;
import org.powernukkitx.level.Level;
import org.powernukkitx.level.format.Chunk;
import org.powernukkitx.level.format.IChunk;
import org.powernukkitx.math.Vector3;
import org.powernukkitx.nbt.tag.CompoundTag;

public class FloatingTextEntity extends Entity {

	@Override
	public @NotNull String getIdentifier() {
		return Entity.FALLING_BLOCK;
	}

	@Override
	public float getWidth() {
		return 0f;
	}

	@Override
	public float getHeight() {
		return 0f;
	}

	public FloatingTextEntity(IChunk chunk, CompoundTag nbt) {
		super(chunk, nbt);
	}

	@Override
	protected void initEntity() {
		super.initEntity();
		setImmobile(true);
		setScale(0f);
		setNameTagAlwaysVisible(true);
		setNameTagVisible(true);
	}

	@Override
	public boolean onUpdate(int currentTick) {
		return false;
	}

	public static FloatingTextEntity spawn(Level level, Vector3 position, String text) {
		CompoundTag nbt = Entity.getDefaultNBT(position);
		IChunk chunk = level.getChunk((int) position.x >> 4, (int) position.z >> 4, true);
		FloatingTextEntity entity = new FloatingTextEntity(chunk, nbt);
		entity.setNameTag(text);
		entity.spawnToAll();
		return entity;
	}
}