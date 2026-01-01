<?php

namespace jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\xxFLORII\bStats;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\xxFLORII\bStats\async\MetricsSendTask;
use jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\xxFLORII\bStats\charts\CustomChart;
use jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\xxFLORII\bStats\settings\MetricsSettings;

class Metrics {
    private PluginBase $plugin;
    private MetricsSettings $metricsSettings;

    /** @var CustomChart[] $charts */
    private array $charts = [];

    public function __construct(PluginBase $plugin, int $pluginId) {
        $this->plugin = $plugin;
        $this->metricsSettings = new MetricsSettings($plugin, $pluginId);

        if ($this->metricsSettings->getPluginId() == null || gettype($this->metricsSettings->getPluginId()) != "integer") $plugin->getLogger()->notice($plugin->getDataFolder()."bStats/config.yml: Key 'plugin-id' must be an integer!");
        if ($this->getMetricsSettings()->isEnabled()) {
            Server::getInstance()->getLogger()->info($plugin->getName() . " collect metrics and send them to bStats (https://bStats.org).");
            Server::getInstance()->getLogger()->info("bStats collects some basic information for plugin authors, like how many people use, their plugin and their total player count.");
            Server::getInstance()->getLogger()->info("It's recommended to keep bStats enabled, but if you're not comfortable with this, you can opt-out by editing the config.yml file in the '/bStats/' folder and setting enabled to false.");
        }
    }

    public function add(CustomChart $chart): self{
        $this->charts[$chart->getCustomId()] = $chart;
        return $this;
    }

    public function remove(string $custom_id): self{
        if (isset($this->charts[$custom_id])) unset($this->charts[$custom_id]);
        return $this;
    }

    /**
     * @return MetricsSettings
     */
    public function getMetricsSettings(): MetricsSettings
    {
        return $this->metricsSettings;
    }

    public function sendData(): void
    {
        $customCharts = [];

        foreach ($this->charts as $chart) {
            $customCharts[] = $chart->jsonSerialize();
        }

        $server = $this->plugin->getServer();

        if (stristr(PHP_OS, 'win')) {
            $output = trim(shell_exec('wmic cpu get NumberOfCores'));
            $coreCount = preg_match_all('/\d+/', $output, $matches) ? (int) $matches[0][0] : 0;
        } else {
            $coreCount = (int) shell_exec('nproc');
        }

        $optional_data = [
            "onlineMode"    => $server->getOnlineMode() ? 1 : 0,
            "playerAmount"  => count($server->getOnlinePlayers()),
            "bukkitName"    => $server->getName(),
            "osName"        => php_uname("s"),
            "osArch"        => php_uname("m"),
            "osVersion"     => php_uname("v"),
            "coreCount"     => $coreCount,
        ];

        $data = json_encode([
            ...$optional_data,
            "serverUUID" => $this->getMetricsSettings()->getServerUUID(),
            "metricsVersion" => $this->getMetricsSettings()->getMetricsVersion(),
            "service" => [
                "id" => $this->getMetricsSettings()->getPluginId(),
                "customCharts" => $customCharts
            ]
        ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->plugin->getLogger()->error("Error whilst encoding bStats data: " . json_last_error_msg());
            return;
        }

        $this->plugin->getServer()->getAsyncPool()->submitTask(
            new MetricsSendTask(
                $data,
                'https://bstats.org/api/v2/data/bukkit',
                $this->getMetricsSettings()->isLogFailedRequests()
            )
        );
    }

    public function scheduleMetricsDataSend(): void {
        $initialDelayMinutes = 3 + (mt_rand(0, 3000) / 1000);
        $initialDelayTicks = (int)($initialDelayMinutes * 60 * 20);

        $secondDelayMinutes = mt_rand(0, 30000) / 1000;
        $secondDelayTicks = (int)($secondDelayMinutes * 60 * 20);

        $repeatIntervalTicks = 20 * 60 * 30;

        $plugin = $this->plugin;
        $metrics = $this;

        $this->plugin->getScheduler()->scheduleDelayedTask(
            new class($plugin, $metrics, $secondDelayTicks, $repeatIntervalTicks) extends Task {
                private Plugin $plugin;
                private Metrics $metrics;
                private int $secondDelayTicks;
                private int $repeatIntervalTicks;

                public function __construct(Plugin $plugin, Metrics $metrics, int $secondDelayTicks, int $repeatIntervalTicks) {
                    $this->plugin = $plugin;
                    $this->metrics = $metrics;
                    $this->secondDelayTicks = $secondDelayTicks;
                    $this->repeatIntervalTicks = $repeatIntervalTicks;
                }

                public function onRun(): void {
                    if ($this->metrics->getMetricsSettings()->isEnabled()) {
                        $this->metrics->sendData();
                    }

                    $this->plugin->getScheduler()->scheduleDelayedRepeatingTask(
                        new class($this->plugin, $this->metrics) extends Task {
                            private Plugin $plugin;
                            private Metrics $metrics;

                            public function __construct(Plugin $plugin, Metrics $metrics) {
                                $this->plugin = $plugin;
                                $this->metrics = $metrics;
                            }

                            public function onRun(): void {
                                if ($this->metrics->getMetricsSettings()->isEnabled()) {
                                    $this->metrics->sendData();
                                }
                            }
                        },
                        $this->secondDelayTicks,
                        $this->repeatIntervalTicks
                    );
                }
            },
            $initialDelayTicks
        );
    }
}