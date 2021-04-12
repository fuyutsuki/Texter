<div align="center">

<img src="/assets/Texter.png" width="600px">

<h1>Texter</h1>

Texter is a plugin for [PocketMine-MP](https://github.com/pmmp/PocketMine-MP) that supports multiple worlds and allows you to add, edit, move, and delete FloatingText.

[![GitHub](https://img.shields.io/github/license/fuyutsuki/Texter?style=flat-square)](https://github.com/fuyutsuki/Texter/blob/master/LICENSE)
[![](https://poggit.pmmp.io/shield.state/Texter&style=flat-square)](https://poggit.pmmp.io/p/Texter)
[![](https://poggit.pmmp.io/shield.api/Texter&style=flat-square)](https://poggit.pmmp.io/p/Texter)

[![](https://poggit.pmmp.io/shield.dl/Texter&style=flat-square)](https://poggit.pmmp.io/p/Texter) / [![](https://poggit.pmmp.io/shield.dl.total/Texter&style=flat-square)](https://poggit.pmmp.io/p/Texter)

[![PoggitCI Badge](https://poggit.pmmp.io/ci.badge/fuyutsuki/Texter/Texter)](https://poggit.pmmp.io/ci/fuyutsuki/Texter/Texter)

</div>

***

<!--
**This branch is under development. It may contain many bugs.**
-->

Other languages:
- [日本語](/.github/readme/ja_jp.md)
- [Русский](/.github/readme/ru_ru.md)
- [Tiếng Việt](/.github/readme/vi_vn.md)


:inbox_tray: Download
-----------------------------------------

* [Poggit](https://poggit.pmmp.io/p/Texter)


:sparkles: Features
-----------------------------------------

#### Commands

All commands have permissions set to `texter.command.txt` (OP only).

| \ |Command|aliases|usage|
|:--:|:--:|:--:|:--|
|Add text|`/txt add`|`/txt a`|`/txt add [name] [text]`|
|Edit text|`/txt edit`|`/txt e`|`/txt edit [name] [text]`|
|Move text|`/txt move`|`/txt m`|`/txt move [name] [here\|x y z]`|
|Remove text|`/txt remove`|`/txt r`|`/txt remove [name]`|
|help|`/txt`||`/txt`|

**Please insert `#` for line breaks**.

#### Variables

If you have installed [Mineflow >= 2.0](https://poggit.pmmp.io/p/Mineflow), you can apply variables to floating characters.

|name|type|available properties|
|:----|:-|:----------------|
|`server_name`|string||
|`microtime`|number||
|`time`|string||
|`date`|string||
|`default_world`|string||
|`player`|Player|[Mineflow#Player](https://github.com/aieuo/Mineflow#player)|
|`ft`|FloatingText|`name(string), pos(Vector3), spacing(Vector3), texts(list)`|


:symbols: Language
-----------------------------------------

You can change the language in the console by changing the `locale` in [config.yml](/resources/config.yml).  
The supported languages will be automatically translated according to the language settings of each player's client.

#### Support status

PR in other languages is welcome!

- [x] en_us(English)
- [ ] id_id(Indonesian)
- [x] ja_jp(Japanese)
- [ ] ko_kr(Korean)
- [ ] ru_ru(Russian)
- [ ] tr_tr(Turkish)
- [ ] zh_cn(Chinese/Simplified)
- [x] vi_vn(Vietnamese)