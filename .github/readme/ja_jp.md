<img src="/assets/Texter.png" width="400px">  

[![GitHub license](https://img.shields.io/badge/license-UIUC/NCSA-blue.svg)](https://github.com/fuyutsuki/Texter/blob/master/LICENSE)
[![](https://poggit.pmmp.io/shield.state/Texter)](https://poggit.pmmp.io/p/Texter)
[![](https://poggit.pmmp.io/shield.api/Texter)](https://poggit.pmmp.io/p/Texter)  

[![](https://poggit.pmmp.io/shield.dl/Texter)](https://poggit.pmmp.io/p/Texter) / [![](https://poggit.pmmp.io/shield.dl.total/Texter)](https://poggit.pmmp.io/p/Texter)

[![PoggitCI Badge](https://poggit.pmmp.io/ci.badge/fuyutsuki/Texter/Texter)](https://poggit.pmmp.io/ci/fuyutsuki/Texter/Texter)

### 概要

[config.yml](/resources/config.yml) の中の `locale` を変更することでコンソール上の言語を変更できます。  
また、対応している言語に関してはそれぞれのプレイヤーのクライアントの言語設定に応じて自動的に翻訳されます。

他の言語:
[English](/README.md),
[русский](./ru_ru.md),
[中文](./zh_cn.md),
[Türkçe](./tr_tr.md),
[한국어](./ko_kr.md),
[Indonesia](./id_id.md)

***

## Texter

TexterはFloatingTextPerticleを複数ワールドに渡り表示、編集、移動、削除ができるプラグインです。  
最新バージョン: **3.4.9**  


<!--
**このブランチは開発中です。多くのバグを含む可能性があります。**  
-->


### 対応状況

- [x] Minecraft(Bedrock)
- [x] 複数言語 (英語, 日本語, ロシア語, 中国語, トルコ語)
- [x] 複数ワールドの表示

### ダウンロード

* [Poggit](https://poggit.pmmp.io/p/Texter)

### コマンド

#### 一般用コマンド

| \ |コマンド|エイリアス|
|:--:|:--:|:--:|
|浮き文字追加|`/txt add`|`/txt a`|
|浮き文字編集|`/txt edit`|`/txt e`|
|浮き文字移動|`/txt move`|`/txt m`|
|浮き文字削除|`/txt remove`|`/txt r`|
|浮き文字リスト|`/txt list`|`txt l`|
|ヘルプ|`/txt or /txt help`|`/txt ?`|

**改行の際には `#` を使用してください。**

### json 記法

- uft.json
```json
{
  "ワールドフォルダ名": {
    "浮き文字名(一意)": {
      "Xvec": 128,
      "Yvec": 90,
      "Zvec": 128,
      "TITLE": "タイトル",
      "TEXT": "テキスト(改行は #)"
    }
  }
}
```

- ft.json
```json
{
  "ワールドフォルダ名": {
    "浮き文字名(一意)": {
      "Xvec": 128,
      "Yvec": 90,
      "Zvec": 128,
      "TITLE": "タイトル",
      "TEXT": "テキスト(改行は #)",
      "OWNER": "Steve"
    }
  }
}
```
