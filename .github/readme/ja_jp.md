<div align="center">

<img src="/assets/Texter.png" width="600px">

<h1>Texter</h1>

Texterは複数ワールドに対応した、FloatingText(浮き文字)の追加、編集、移動、削除ができる[PocketMine-MP](https://github.com/pmmp/PocketMine-MP)用プラグインです。

[![GitHub](https://img.shields.io/github/license/fuyutsuki/Texter?style=flat-square)](https://github.com/fuyutsuki/Texter/blob/master/LICENSE)
[![](https://poggit.pmmp.io/shield.state/Texter&style=flat-square)](https://poggit.pmmp.io/p/Texter)
[![](https://poggit.pmmp.io/shield.api/Texter&style=flat-square)](https://poggit.pmmp.io/p/Texter)

[![](https://poggit.pmmp.io/shield.dl/Texter&style=flat-square)](https://poggit.pmmp.io/p/Texter) / [![](https://poggit.pmmp.io/shield.dl.total/Texter&style=flat-square)](https://poggit.pmmp.io/p/Texter)

[![PoggitCI Badge](https://poggit.pmmp.io/ci.badge/fuyutsuki/Texter/Texter)](https://poggit.pmmp.io/ci/fuyutsuki/Texter/Texter)

</div>

***

<!--
**このブランチは開発中です。多くのバグを含む可能性があります。**
-->

他の言語:
- [English](/README.md)
- [Русский](/.github/readme/ru_ru.md)
- [Tiếng Việt](/.github/readme/vi_vn.md)


:inbox_tray: ダウンロード
-----------------------------------------

* [Poggit](https://poggit.pmmp.io/p/Texter)


:sparkles: 機能
-----------------------------------------

#### コマンド

すべてのコマンドの権限は `texter.command.txt` (OPのみ) に設定してあります。

| \ |コマンド|エイリアス|使い方|
|:--:|:--:|:--:|:--|
|浮き文字追加|`/txt add`|`/txt a`|`/txt add [name] [text]`|
|浮き文字編集|`/txt edit`|`/txt e`|`/txt edit [name] [text]`|
|浮き文字移動|`/txt move`|`/txt m`|`/txt move [name] [here\|x y z]`|
|浮き文字削除|`/txt remove`|`/txt r`|`/txt remove [name]`|
|ヘルプ|`/txt`||`/txt`|

**浮き文字の改行の際には `#` を文中に挿入してください。**

#### 変数

[Mineflow >= 2.0](https://poggit.pmmp.io/p/Mineflow)を導入している場合、浮き文字に変数を適用することが出来ます。

|変数名|型|使用できるプロパティ|
|:----|:-|:----------------|
|`server_name`|string||
|`microtime`|number||
|`time`|string||
|`date`|string||
|`default_world`|string||
|`player`|Player|[Mineflow#Player](https://github.com/aieuo/Mineflow#player)|
|`ft`|FloatingText|`name(string), pos(Vector3), spacing(Vector3), texts(list)`|


:symbols: 言語
-----------------------------------------

[config.yml](/resources/config.yml) の中の `locale` を変更することでコンソール上の言語を変更できます。  
また、対応している言語に関してはそれぞれのプレイヤーのクライアントの言語設定に応じて自動的に翻訳されます。

#### 対応状況

他言語のPRを歓迎します！

- [x] en_us(英語)
- [ ] id_id(インドネシア語)
- [x] ja_jp(日本語)
- [ ] ko_kr(韓国語)
- [x] ru_ru(ロシア語)
- [ ] tr_tr(トルコ語)
- [ ] zh_cn(中国語/簡体)
- [x] vi_vn(ベトナム語)
