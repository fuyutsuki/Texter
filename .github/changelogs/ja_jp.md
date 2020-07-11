# 3.x

## 3.4.x

### 3.4.9

- @VicoSilalahiによる翻訳: インドネシア語 を追加しました

### 3.4.8

- Minecraft: Bedrock Edition 1.16.0.20 リリースに伴う [pmmp/PocketMine-MP](https://github.com/pmmp/PocketMine-MP) の最新リリース `3.14.0` での動作確認を行いました

### 3.4.7

- @minijahamによる翻訳: 韓国語 を追加しました

### 3.4.6

- Minecraft: Bedrock Edition 1.14.60 hotfix リリースに伴う [pmmp/PocketMine-MP](https://github.com/pmmp/PocketMine-MP) の最新リリース `3.12.0` での動作確認を行いました

### 3.4.5

- @SuperAdam47による翻訳: トルコ語 の修正

### 3.4.4

- それぞれの言語に対応した `changelogs` を追加しました
- @SuperAdam47氏による翻訳: トルコ語 を追加しました
- @SuperYYT氏による翻訳: 中国語 の修正

### 3.4.3

- @SuperYYT氏による翻訳: 中国語 を追加しました

### 3.4.2

- @No4NaMe氏による翻訳: ロシア語 を追加しました

### 3.4.1

- Minecraft: Bedrock Edition 1.14.0 リリースに伴う [pmmp/PocketMine-MP](https://github.com/pmmp/PocketMine-MP) の最新リリース `3.11.1` での動作確認を行いました

### 3.4.0

- [pmmp/PocketMine-MP](https://github.com/pmmp/PocketMine-MP)に対応しました。それに伴い、このリリースではpmmpの変更に追従していないフォークは動作しなくなります

***

## 3.3.x

### 3.3.1

- [#65](https://github.com/fuyutsuki/Texter/issues/65) を修正
- Minecraft: Bedrock Edition 1.13.0.34 に対応しました。対応確認済み環境は以下のリストの通りです。
  * https://github.com/NetherGamesMC/PocketMine-MP
  * https://github.com/JackMD/PocketMine-MP
  * https://github.com/FoxelTeam/Foxel
  * https://github.com/Saisana299/PMMP-MCBE1.13
  
### 3.3.0

- MC:BE 1.13.0.34に対応しました。対応確認済み環境は以下のリストの通りです。
  * https://github.com/NetherGamesMC/PocketMine-MP
- libformを廃止し、FormAPIへ切り替えました。したがってこのプラグインを動かす際にはFormAPIが必須になりました。以下のリンクからダウンロードできます。
  https://poggit.pmmp.io/p/FormAPI/1.3.0

***

## 3.2.x

### 3.2.1

- Minecraft: Bedrock Edition 1.12.0 に対応しました。

***

## 3.1.x

### 3.1.0

- ライセンスをMITからUIUC(NCSA)に変更 - e256061
- API 4.0のサポート打ち切り - f06394f
- /txtをopのみ使用できるようにするかどうかの設定を追加 - 08f4a29 (#51)

***

## 3.0.x

### 3.0.8

- UnremovableFloatingTextで存在しないプロパティを使用していた問題を修正 - d6d4562 (#48)

### 3.0.7

- キーが存在するかどうかの確認をしていなかった問題を修正 - 9391c8c (#47)

### 3.0.6

- uft.jsonやft.jsonから読み込まれる値の型がFloatingTextの指定する型と合わない不具合を修正 - d5b9cad (#44)

### 3.0.5

- 天候が雨のときに浮き文字が煙を発する不具合を修正 - 9ed5a4d (#43)

### 3.0.4

- /txt removeコマンドが正しく動作しない不具合を修正 - a0cb539

### 3.0.3

- FloatingTextの名前やワールド名にドットが含まれているとft.jsonのフォーマットが崩れる問題を修正 - 7684786

### 3.0.2

- Core::checkPackaged() を修正

### 3.0.1

- [#40](https://github.com/fuyutsuki/Texter/issues/40), [#41](https://github.com/fuyutsuki/Texter/issues/41) を修正

### 3.0.0

- /txtadm を削除
- デバイスの言語設定からゲーム内の言語も変わるように
- タイムゾーンの設定を削除しました
- CantRemoveFloatingTextをUnremovableFloatingTextに改名

***

# 2.x

## 2.4.x

### 2.4.3

- [#33](https://github.com/fuyutsuki/Texter/issues/33) の修正

### 2.4.2

- [#31](https://github.com/fuyutsuki/Texter/issues/31) の修正

### 2.4.1

- APIバージョンの廃止に対応 👍
- スケジューラの仕様変更への対応
- 内部関数整理
- [#17](https://github.com/fuyutsuki/Texter/issues/17) の修正

### 2.4.0

- スケジューラの仕様変更への対応
- 内部関数整理

***

## 2.3.x

### 2.3.4

- [#11](https://github.com/fuyutsuki/Texter/issues/11) の修正

### 2.3.3

- 3.0.0-ALPHA12への対応

### 2.3.2

- [#10](https://github.com/fuyutsuki/Texter/issues/10) の修正

### 2.3.1

- Minecraft 1.2.13 への対応
- いくつかのバグ、タイプヒントを修正
- 翻訳を修正
- php7.0.xをお使いの方はlegacy/php7.0.xのブランチのビルドからダウンロードして下さい

### 2.3.0

- APIバージョン 3.0.0-ALPHA11 への対応
- 大規模なシステム変更

***

## 2.2.x

### 2.2.6

- APIバージョン 3.0.0-ALPHA10 への対応
- APIの細かな変更

### 2.2.5

- Poggitでリリースしました🎉
  ダウンロードはこちらから
  
### 2.2.4

- Data restored.