<?php

namespace jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\xxFLORII\bStats\async;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class MetricsSendTask extends AsyncTask {

    private string $jsonData;
    private string $url;
    private bool $logFailedRequests;

    public function __construct(string $jsonData, string $url, bool $logFailedRequests) {
        $this->jsonData = $jsonData;
        $this->url = $url;
        $this->logFailedRequests = $logFailedRequests;
    }

    public function onRun(): void {
        $ch = curl_init($this->url);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Content-Length: " . strlen($this->jsonData),
        ]);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $errno = curl_errno($ch);

        curl_close($ch);

        $this->setResult([
            'success' => $response !== false && !$errno,
            'error' => $error,
            'errno' => $errno,
            'response' => $response
        ]);
    }

    public function onCompletion(): void {
        $result = $this->getResult();

        if (!$result['success'] && $this->logFailedRequests) {
            Server::getInstance()->getLogger()->error(
                "Error whilst sending data to bStats: " . $result['error']
            );
        }
    }

}