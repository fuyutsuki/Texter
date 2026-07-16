package jp.mcbe.fuyutsuki.Texter.util;

public final class SingletonHolder<T> {

	private T instance;

	public void register(T instance) {
		this.instance = instance;
	}

	public T get() {
		return instance;
	}
}