<?php

/**
 * // English
 *
 * Texter, the display FloatingTextPerticle plugin for PocketMine-MP
 * Copyright (c) 2018 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * This software is distributed under "MIT license".
 * You should have received a copy of the MIT license
 * along with this program.  If not, see
 * < https://opensource.org/licenses/mit-license >.
 *
 * ---------------------------------------------------------------------
 * // 日本語
 *
 * TexterはPocketMine-MP向けのFloatingTextPerticleを表示するプラグインです。
 * Copyright (c) 2018 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * このソフトウェアは"MITライセンス"下で配布されています。
 * あなたはこのプログラムと共にMITライセンスのコピーを受け取ったはずです。
 * 受け取っていない場合、下記のURLからご覧ください。
 * < https://opensource.org/licenses/mit-license >
 */

namespace tokyo\pmmp\Texter\task;

// pocketmine
use pocketmine\{
  Server,
  scheduler\AsyncTask
};

/**
 * CheckUpdateTaskClass
 */
class CheckUpdateTask extends AsyncTask {

  public function onRun() {
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => "https://api.github.com/repos/fuyutsuki/Texter/releases",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_USERAGENT => "php_".PHP_VERSION,
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
    $plugin = $server->getPluginManager()->getPlugin("Texter");
    if ($plugin !== null) {
      $data = $this->getResult();
      if (isset($data[0])) {
        $plugin->versionCompare(true, $data[0]["name"], $data[0]["html_url"]);
      }else {
        $plugin->versionCompare(false);
      }
    }
  }
}
