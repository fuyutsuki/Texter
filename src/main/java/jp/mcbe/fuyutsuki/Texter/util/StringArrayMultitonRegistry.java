package jp.mcbe.fuyutsuki.Texter.util;

import java.util.Map;
import java.util.concurrent.ConcurrentHashMap;

public final class StringArrayMultitonRegistry<T> {

	private final Map<String, T> instances = new ConcurrentHashMap<>();

	public void register(String key, T instance) {
		instances.put(key, instance);
	}

	public T get(String key) {
		return instances.get(key);
	}

	public void remove(String key) {
		instances.remove(key);
	}
}