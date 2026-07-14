package jp.mcbe.fuyutsuki.Texter.text;

public abstract class Nameable {

	protected final String name;

	protected Nameable(String name) {
		this.name = name;
	}

	public String name() {
		return name;
	}

	public abstract String getName();
}