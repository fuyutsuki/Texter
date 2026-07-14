package jp.mcbe.fuyutsuki.Texter.text;

import lombok.Getter;

@Getter
public enum SendType {

	ADD("add"),
	EDIT("edit"),
	MOVE("move"),
	REMOVE("remove");

	private final String value;

	SendType(String value) {
		this.value = value;
	}

	@Override
	public String toString() {
		return value;
	}
}