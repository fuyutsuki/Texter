# 4.x 変更履歴

## 4.0.x

### 4.0.7

#### :bug: バグ修正

- ロシア語の翻訳ファイルが破損していた問題を修正しました

### 4.0.6

#### :globe_with_meridians: 国際化

- @iteplenkyによる翻訳: ロシア語 の追加
- 使用していないテキストを削除

### 4.0.5

#### :bug: バグ修正

- 旧バージョンからのデータ移行の際に浮き文字名が数字のみだと型の不整合によりサーバーがクラッシュする不具合を修正 (#115)

### 4.0.4

#### :arrow_heading_up: PMMPの変更に追従

- Minecraft: Bedrock Edition 1.16.220 リリースに伴う [pmmp/PocketMine-MP](https://github.com/pmmp/PocketMine-MP) の最新リリース `3.19.0` での動作確認を行いました
- アイテムスタックの変更に対応 (#111)

#### :warning: 後方互換性を損なう変更

- TexterはPocketMine-MP 3.19.0以上が必要になりました

### 4.0.3

#### :bug: バグ修正

- 特定の操作をした際にサーバーがクラッシュするバグを修正しました  
  クレジット: BlitzGames(UK), MCBEPU(CN), OneMine Хаб(RU), Sulfuritium(FR), YukioLifeServer(JP), GrieferSucht(DE), HayalCraft
  
### 4.0.2

#### :bug: バグ修正

- 特定のコマンド操作を行ったときにプレイヤーがブロックされるバグを修正しました

### 4.0.1

#### :globe_with_meridians: 国際化

- @TobyDev265による翻訳: ベトナム語 を追加しました

### 4.0.0

#### ✨ 新機能

##### 浮き文字

- 浮き文字に挿入できる変数を導入しました  
  記法はMineflowと同様、`{変数名}`です。
  - [MineFlow >= 2.0](https://poggit.pmmp.io/p/Mineflow) が必要です。
  - Mineflowの[一部の変数](/.github/readme/ja_jp.md#変数)を扱うことが出来ます。
    - Mineflowを用いた浮き文字の操作は要望があれば4.1以降に実装するかもしれません。

##### コマンド

- `/txt move` コマンドで移動先の座標を指定できるようになりました

##### 開発者

- 後述の `FloatingTextCluster` を用いて複数の浮き文字を簡単に扱うことができるようになりました

#### ✔ 変更点

##### コマンド

- `texter.command.txt` を `op` に設定しました。  
  もし以前のようにOP以外にも使用させたい場合、[PurePerms](https://poggit.pmmp.io/p/PurePerms) などの権限プラグインを使用することを検討してください。
- `/txt list` コマンドを削除しました  
  今後はそれぞれのコマンドで必要に応じて自動で浮き文字がリストアップされます。

##### 設定

- 以下の設定項目を `config.yml` から削除しました
  - `can.use.only.op`
  - `char`
  - `feed`
  - `world`

##### 開発者

- 浮き文字の役割を変更しました
	- **FloatingText**  
		浮き文字の最小単位です。浮き文字の名前は浮き文字の下部に表示されなくなりました。
	- **FloatingTextCluster**  
		一つまたは複数の浮き文字で構成された浮き文字群で、コマンドでまとめて操作することが出来ます。