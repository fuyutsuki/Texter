<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\task;

use Exception;
use jp\mcbe\fuyutsuki\Texter\{
	Main};
use pocketmine\{
	scheduler\AsyncTask,
	Server,
	utils\VersionString};
use function curl_init;
use function curl_setopt_array;
use function curl_exec;
use function curl_errno;
use function curl_error;
use function curl_close;
use function json_decode;

/**
 * Class CheckUpdateTask
 * @package jp\mcbe\fuyutsuki\Texter\task
 */
class CheckUpdateTask extends AsyncTask {

	/**
	 * @throws Exception
	 */
	public function onRun() {
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL => "https://api.github.com/repos/fuyutsuki/Texter/releases/latest",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_USERAGENT => "php_".PHP_VERSION,
			CURLOPT_SSL_VERIFYPEER => false
		]);
		$json = curl_exec($curl);
		$errorNo = curl_errno($curl);
		if ($errorNo) {
			$error = curl_error($curl);
			throw new Exception($error);
		}
		curl_close($curl);
		$data = json_decode($json, true);
		$this->setResult($data);
	}

	public function onCompletion(Server $server) {
		$plugin = $server->getPluginManager()->getPlugin("Texter");
		if ($plugin !== null && $plugin->isEnabled()) {
			/** @var Main $plugin */
			$data = $this->getResult();
			if (isset($data["name"], $data["html_url"])) {
				$ver = new VersionString($data["name"]);
				$plugin->compareVersion(true, $ver, $data["html_url"]);
			}else {
				$plugin->compareVersion(false);
			}
		}
	}
}