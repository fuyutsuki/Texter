package jp.mcbe.fuyutsuki.Texter.task;

import jp.mcbe.fuyutsuki.Texter.data.FloatingTextData;
import jp.mcbe.fuyutsuki.Texter.text.FloatingTextCluster;
import jp.mcbe.fuyutsuki.Texter.text.SendType;
import org.powernukkitx.Player;
import org.powernukkitx.level.Level;
import org.powernukkitx.scheduler.Task;

import java.util.ArrayDeque;
import java.util.Deque;

public final class SendTextsTask extends Task {

	public static final int DELAY_TICKS = 5;   // 0.25s
	public static final int TICKING_PERIOD = 2; // 0.1s

	private final Player target;
	private final SendType type;
	private final Deque<FloatingTextCluster> remain;

	public SendTextsTask(Player target, Level sendTo, SendType type) {
		this.target = target;
		this.type = type;

		FloatingTextData data = FloatingTextData.getInstance(sendTo.getFolderName());
		this.remain = data != null
			? new ArrayDeque<>(data.floatingTexts().values())
			: new ArrayDeque<>();
	}

	@Override
	public void onRun(int currentTick) {
		if (remain.isEmpty() || !target.isConnected()) {
			onSuccess();
		} else {
			FloatingTextCluster floatingText = remain.poll();
			assert floatingText != null;
			floatingText.sendToPlayer(target, type);
		}
	}

	private void onSuccess() {
		cancel();
	}
}