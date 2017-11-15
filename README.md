<img src="/assets/Texter.png" width="400px">  
<!-- for poggit
<img src="https://raw.githubusercontent.com/fuyutsuki/Texter/master/assets/Texter.png" width="400px">  
-->

[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/fuyutsuki/Texter/blob/master/LICENSE)
[![Github All Releases](https://img.shields.io/github/downloads/fuyutsuki/Texter/total.svg)](https://github.com/fuyutsuki/Texter/releases)  

[![PoggitCI Badge](https://poggit.pmmp.io/ci.badge/fuyutsuki/Texter/Texter)](https://poggit.pmmp.io/ci/fuyutsuki/Texter/Texter)

### Overview
Select Language: [English](#eng), [日本語](#jpn)

***
<a name="eng"></a>
# English

<!--
## !! Caution !!
This branch is under development.
It may have many bugs.
-->

## Texter
Texter is plugin that displays and deletes FloatingTextPerticle supported to multi-world.  
Latest: ver **2.2.5** _Papilio dehaanii(カラスアゲハ)_  

### Supporting
- [x] Multi-language (eng, jpn)
- [x] Multi-world display
- [x] Minecraft(Bedrock) v1.2.x

### Download
You may grab the plugin from [Source](https://github.com/fuyutsuki/Texter/archive/master.zip), or download the latest .phar from [Poggit](https://poggit.pmmp.io/ci/fuyutsuki/Texter/Texter).  

### Commands
#### General command
| \ |command|argument|alias|
|:--:|:--:|:--:|:--:|
|Add text|`/txt add`|`<title> [text]`|`/txt a`|
|Remove text|`/txt remove`|`<ID>`|`/txt r`|
|Update text|`/txt update`|`<title, text> <ID> <message>`|`/txt u`|
|Help|`/txt or /txt help`|`none`|`/txt ?`|

#### Management command
| \ |command|argument|alias|
|:--:|:--:|:--:|:--:|
|Remove all|`/txtadm allremove`|`none`|`/tadm ar`|
|Remove texts/user|`/txtadm userremove`|`<username>`|`/tadm ur`|
|Info|`/txtadm info`|`none`|`/tadm i`|
|Help|`/txtadm or /txtadm help`|`none`|`/tadm ?`|

**Please use `#` for line breaks.**

### json notation
```json
anythingUniqueValue: {
  "WORLD" : "worldName",
  "Xvec" : 128,
  "Yvec" : 90,
  "Zvec" : 128,
  "TITLE" : "title",
  "TEXT" : "1st Line#2nd Line..."
}
```
It is output as follows.  
<img src="/assets/Example.jpg" width="320px">
<!-- for poggit
<img src="https://raw.githubusercontent.com/fuyutsuki/Texter/master/assets/Example.jpg" width="320px">
-->
***
<a name="jpn"></a>
# 日本語

<!--
## !! 注意 !!
このブランチは開発中です。多くのバグを含む可能性があります。
-->

## Texter
TexterはFloatingTextPerticleを複数ワールドに渡り表示、削除ができるプラグインです。  
最新バージョン: **2.2.5** _Papilio dehaanii(カラスアゲハ)_  

### 対応状況
- [x] 複数言語 (eng, jpn)
- [x] 複数ワールドの表示
- [x] Minecraft(Bedrockエンジン) v1.2.x

### ダウンロード
ソースファイル群は [こちら](https://github.com/fuyutsuki/Texter/archive/master.zip)  
最新の .pharファイルが必要であれば [PoggitCI](https://poggit.pmmp.io/ci/fuyutsuki/Texter/Texter) の最新ビルドの **Download** から。  

### コマンド
#### 一般用コマンド
| \ |コマンド|引数|エイリアス|
|:--:|:--:|:--:|:--:|
|浮き文字追加|`/txt add`|`<タイトル> [テキスト]`|`/txt a`|
|浮き文字削除|`/txt remove`|`<ID>`|`/txt r`|
|浮き文字更新|`/txt update`|`<タイトル, テキスト> <ID> <メッセージ>`|`/txt u`|
|ヘルプ|`/txt or /txt help`|`無し`|`/txt ?`|

#### 管理用コマンド
| \ |コマンド|引数|エイリアス|
|:--:|:--:|:--:|:--:|
|浮き文字すべて削除|`/txtadm allremove`|`none`|`/tadm ar`|
|ユーザーの浮き文字を削除|`/txtadm userremove`|`<username>`|`/tadm ur`|
|浮き文字の各種情報を見る|`/txtadm info`|`none`|`/tadm i`|
|ヘルプ|`/txtadm or /txtadm help`|`none`|`/tadm ?`|

**改行の際には `#` を使用してください。**

### json記法
```json
{
  "一意な文字列": {
    "WORLD" : "world",
    "Xvec" : 128,
    "Yvec" : 90,
    "Zvec" : 128,
    "TITLE" : "title",
    "TEXT" : "1st Line#2nd Line"
  }
}
```

こう書くことで以下のように出力されます。  
<img src="/assets/Example.jpg" width="320px">
<!-- for poggit
<img src="https://raw.githubusercontent.com/fuyutsuki/Texter/master/assets/Example.jpg" width="320px">
-->
