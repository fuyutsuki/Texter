<?php

namespace jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\xxFLORII\bStats\settings;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;

class MetricsSettings {
    private bool $enabled = true;
    private ?int $pluginId = null;
    private bool $log_failed_requests = false;
    private bool $log_sent_data = false;
    private bool $log_response_status_text = false;
    private ?string $serverUUID = null;
    private ?string $pluginName = null;
    private string $metricsVersion = "3.1.1-SNAPSHOT";

    public function __construct(PluginBase $plugin, int $pluginId) {
        @mkdir($plugin->getDataFolder() . "/bStats/");
        if (!is_file($plugin->getDataFolder() . "/bStats/config.yml")) {
            $config = new Config($plugin->getDataFolder() . "/bStats/config.yml", Config::YAML);
            $config->set("enabled", true);
            $config->set("plugin-id", $pluginId);
            $config->set("log_failed_requests", false);
            $config->set("log_sent_data", false);
            $config->set("log_response_status_text", false);

            $config->save();
        }

        $config = new Config($plugin->getDataFolder() . "/bStats/config.yml");
        $this->setEnabled($config->get("enabled", true));
        $this->setPluginId($config->get("plugin-id", null));
        $this->setLogFailedRequests($config->get("log_failed_requests", false));
        $this->setLogSentData($config->get("log_sent_data", false));
        $this->setLogResponseStatusText($config->get("log_response_status_text", false));

        $this->serverUUID = $plugin->getServer()->getServerUniqueId()->toString();
        $this->pluginName = $plugin->getDescription()->getName();
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    protected function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return int|null
     */
    public function getPluginId(): ?int
    {
        return $this->pluginId;
    }

    /**
     * @param int|null $pluginId
     */
    protected function setPluginId(?int $pluginId): void
    {
        $this->pluginId = $pluginId;
    }

    /**
     * @return bool
     */
    public function isLogFailedRequests(): bool
    {
        return $this->log_failed_requests;
    }

    /**
     * @param bool $log_failed_requests
     */
    protected function setLogFailedRequests(bool $log_failed_requests): void
    {
        $this->log_failed_requests = $log_failed_requests;
    }

    /**
     * @return bool
     */
    public function isLogSentData(): bool
    {
        return $this->log_sent_data;
    }

    /**
     * @param bool $log_sent_data
     */
    protected function setLogSentData(bool $log_sent_data): void
    {
        $this->log_sent_data = $log_sent_data;
    }

    /**
     * @return bool
     */
    public function isLogResponseStatusText(): bool
    {
        return $this->log_response_status_text;
    }

    /**
     * @param bool $log_response_status_text
     */
    protected function setLogResponseStatusText(bool $log_response_status_text): void
    {
        $this->log_response_status_text = $log_response_status_text;
    }

    /**
     * @return string|null
     */
    public function getServerUUID(): ?string
    {
        return $this->serverUUID;
    }

    /**
     * @param string|null $serverUUID
     */
    protected function setServerUUID(?string $serverUUID): void
    {
        $this->serverUUID = $serverUUID;
    }

    /**
     * @return string|null
     */
    public function getPluginName(): ?string
    {
        return $this->pluginName;
    }

    /**
     * @return string
     */
    public function getMetricsVersion(): string
    {
        return $this->metricsVersion;
    }
}