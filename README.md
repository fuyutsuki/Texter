<img src="/assets/Texter.png" width="400px">  

[![GitHub license](https://img.shields.io/badge/license-UIUC/NCSA-blue.svg)](https://github.com/fuyutsuki/Texter/blob/master/LICENSE)
[![](https://poggit.pmmp.io/shield.state/Texter)](https://poggit.pmmp.io/p/Texter)
[![](https://poggit.pmmp.io/shield.api/Texter)](https://poggit.pmmp.io/p/Texter)  

[![](https://poggit.pmmp.io/shield.dl/Texter)](https://poggit.pmmp.io/p/Texter) / [![](https://poggit.pmmp.io/shield.dl.total/Texter)](https://poggit.pmmp.io/p/Texter)

[![PoggitCI Badge](https://poggit.pmmp.io/ci.badge/fuyutsuki/Texter/Texter)](https://poggit.pmmp.io/ci/fuyutsuki/Texter/Texter)

### Overview

You can set the plugin language by changing the `locale` in [config.yml](/resources/config.yml)  
also, supported languages are automatically displayed according to the locale of the client.

Select another language:
[日本語](./.github/readme/ja_jp.md),
[русский](./.github/readme/ru_ru.md),
[中文](./.github/readme/zh_cn.md),
[Türkçe](./.github/readme/tr_tr.md)

***

## Texter

Texter is plugin that displays and deletes FloatingTextParticle supported to multi-world.  
Latest: ver **3.4.6**  


<!--
**This branch is under development. It may have many bugs.**  
-->


### Supporting

- [x] Minecraft(Bedrock)
- [x] Multi-language (English, Japanese, Russian, Chinese, Turkish)
- [x] Multi-world display

### Download

* [Poggit](https://poggit.pmmp.io/p/Texter)

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
