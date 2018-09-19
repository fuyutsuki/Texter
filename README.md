<img src="/assets/Texter.png" width="400px">  
<!-- for poggit
<img src="https://raw.githubusercontent.com/fuyutsuki/Texter/master/assets/Texter.png" width="400px">  
-->

[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/fuyutsuki/Texter/blob/master/LICENSE)
[![](https://poggit.pmmp.io/shield.state/Texter)](https://poggit.pmmp.io/p/Texter)
[![](https://poggit.pmmp.io/shield.api/Texter)](https://poggit.pmmp.io/p/Texter)  

[![](https://poggit.pmmp.io/shield.dl/Texter)](https://poggit.pmmp.io/p/Texter) / [![](https://poggit.pmmp.io/shield.dl.total/Texter)](https://poggit.pmmp.io/p/Texter)

[![PoggitCI Badge](https://poggit.pmmp.io/ci.badge/fuyutsuki/Texter/Texter)](https://poggit.pmmp.io/ci/fuyutsuki/Texter/Texter)

### Overview
Select Language: [English](#eng), [日本語](#jpn)

***
<a name="eng"></a>
# English

**This branch is under development. It may have many bugs.**  

## Texter
Texter is plugin that displays and deletes FloatingTextPerticle supported to multi-world.  
Latest: ver **3.0.0**  

### Supporting
- [x] Minecraft(Bedrock) v1.6.x
- [x] Multi-language (English, 日本語)
- [x] Multi-world display
- [ ] Text Events
- [ ] API 4.0.0 (for Altay)

### Download
You may grab the plugin from [Source](https://github.com/fuyutsuki/Texter/archive/master.zip), or download the latest .phar from [Poggit](https://poggit.pmmp.io/ci/fuyutsuki/Texter/Texter).  

### Commands
#### General command
| \ |command|alias|
|:--:|:--:|:--:|
|Add text|`/txt add`|`/txt a`|
|Edit text|`/txt edit`|`/txt e`|
|Move text|`/txt move`|`/txt m`|
|Remove text|`/txt remove`|`/txt r`|
|Listup texts|`/txt list`|`/txt l`|
|Help|`/txt or /txt help`|`/txt ?`|

#### Management command
| \ |command|argument|alias|
|:--:|:--:|:--:|:--:|
|Remove all|`/txtadm allremove`|`none`|`/tadm ar`|
|Remove texts/user|`/txtadm userremove`|`<userName>`|`/tadm ur`|
|Remove texts/level|`/txtadm levelremove`|`<worldName>`|`/tadm lr`|
|Info|`/txtadm info`|`none`|`/tadm i`|
|Help|`/txtadm or /txtadm help`|`none`|`/tadm ?`|

**Please use `#` for line breaks.**

### json notation
- uft.json
```json
{
  "LevelFolderName": {
    "TextName(Unique)": {
      "Xvec": 128,
      "Yvec": 90,
      "Zvec": 128,
      "TITLE": "Title",
      "TEXT": "Text(New line with #)"
    }
  }
}
```
- ft.json
```json
{
  "LevelFolderName": {
    "TextName1(Unique)": {
      "Xvec": 128,
      "Yvec": 90,
      "Zvec": 128,
      "TITLE": "Title",
      "TEXT": "Text(New line with #)",
      "OWNER": "Steve"
    }
  }
}
```

***
<a name="jpn"></a>
# 日本語

**このブランチは開発中です。多くのバグを含む可能性があります。**  

## Texter
TexterはFloatingTextPerticleを複数ワールドに渡り表示、編集、移動、削除ができるプラグインです。  
最新バージョン: **3.0.0**  

### 対応状況
- [x] Minecraft(Bedrockエンジン) v1.6.x
- [x] 複数言語 (English, 日本語)
- [x] 複数ワールドの表示
- [ ] テキスト関係イベント
- [ ] API 4.0.0対応 (Altay向け)

### ダウンロード
ソースファイル群は [こちら](https://github.com/fuyutsuki/Texter/archive/master.zip)  
最新の .pharファイルが必要であれば [PoggitCI](https://poggit.pmmp.io/ci/fuyutsuki/Texter/Texter) の最新ビルドの **Download** から。  

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

#### 管理用コマンド
| \ |コマンド|引数|エイリアス|
|:--:|:--:|:--:|:--:|
|浮き文字すべて削除|`/txtadm allremove`|`なし`|`/tadm ar`|
|ユーザーの浮き文字を削除|`/txtadm userremove`|`<ユーザー名>`|`/tadm ur`|
|ワールドの浮き文字を削除|`/txtadm levelremove`|`<ワールド名>`|`/tadm lr`|
|浮き文字の各種情報を見る|`/txtadm info`|`なし`|`/tadm i`|
|ヘルプ|`/txtadm or /txtadm help`|`なし`|`/tadm ?`|

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
