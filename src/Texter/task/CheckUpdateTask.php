<?php

namespace Texter\task;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\scheduler\AsyncTask;

/**
 * バージョンの確認
 */
class CheckUpdateTask extends AsyncTask{

  public function onRun(){
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => "https://api.github.com/repos/fuyutsuki/Texter/releases",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_USERAGENT => "getGitHubAPI",
      CURLOPT_SSL_VERIFYPEER => false
    ]);
    $json = curl_exec($curl);

    $errorno = curl_errno($curl);
    if ($errorno) {
      $error = curl_error($curl);
      throw new \Exception($error);
    }
    curl_close($curl);
    $data = json_decode($json, true);
    $this->setResult($data);
  }

  public function onCompletion(Server $server){
    $main = $server->getPluginManager()->getPlugin("Texter");
    $main->versionCompare($this->getResult());
  }
}
