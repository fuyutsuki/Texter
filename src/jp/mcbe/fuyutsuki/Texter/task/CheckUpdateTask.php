<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\task;

use Exception;
use jp\mcbe\fuyutsuki\Texter\Main;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\VersionString;
use function curl_close;
use function curl_errno;
use function curl_error;
use function curl_exec;
use function curl_init;
use function curl_setopt_array;
use function json_decode;

class CheckUpdateTask extends AsyncTask {

	/**
	 * @throws Exception
	 */
	public function onRun(): void {
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

	public function onCompletion(): void {
		$plugin = Server::getInstance()->getPluginManager()->getPlugin("Texter");
		if ($plugin !== null && $plugin->isEnabled()) {
			/** @var Main $plugin */
			$data = $this->getResult();
			if (isset($data["tag_name"], $data["html_url"])) {
				$pattern = "/(?<=v)\d+\.\d+\.\d+/";
				preg_match($pattern, $data["tag_name"], $version);

				$ver = new VersionString($version[0]);
				$plugin->compareVersion(true, $ver, $data["html_url"]);
			}else {
				$plugin->compareVersion(false);
			}
		}
	}
}