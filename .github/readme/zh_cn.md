<img src="/assets/Texter.png" width="400px">  

[![GitHub license](https://img.shields.io/badge/license-UIUC/NCSA-blue.svg)](https://github.com/fuyutsuki/Texter/blob/master/LICENSE)
[![](https://poggit.pmmp.io/shield.state/Texter)](https://poggit.pmmp.io/p/Texter)
[![](https://poggit.pmmp.io/shield.api/Texter)](https://poggit.pmmp.io/p/Texter)  

[![](https://poggit.pmmp.io/shield.dl/Texter)](https://poggit.pmmp.io/p/Texter) / [![](https://poggit.pmmp.io/shield.dl.total/Texter)](https://poggit.pmmp.io/p/Texter)

[![PoggitCI Badge](https://poggit.pmmp.io/ci.badge/fuyutsuki/Texter/Texter)](https://poggit.pmmp.io/ci/fuyutsuki/Texter/Texter)

### 概要

您可以通过在 [config.yml](/resources/config.yml) 中更改 `locale` 来更改控制台上的语言。  
支持的语言会根据每个播放器语言设置自动翻译。

其他语言:
[English](/README.md),
[日本語](./ja_jp.md),
[русский](./ru_ru.md),
[Türkçe](./tr_tr.md),
[한국어](./ko_kr.md),
[Indonesia](./id_id.md)

## Texter

Texter是一个插件，显示和删除浮动的文本且支持多世界。
最新版本: **3.4.9**  


<!--
**这个插件仍在开发中。它可能有很多瑕疵。**
-->


### 支持

- [x] Minecraft(Bedrock)
- [x] 多语言 (英文, 日语, 俄罗斯语， 中文, 土耳其语)
- [x] 多世界显示

### 下载

* [Poggit](https://poggit.pmmp.io/p/Texter)

### 指令

#### 常规指令

| \ |指令|缩写|
|:--:|:--:|:--:|
|新增文本|`/txt add`|`/txt a`|
|编辑文本|`/txt edit`|`/txt e`|
|移动文本|`/txt move`|`/txt m`|
|移除文本|`/txt remove`|`/txt r`|
|陈列文本|`/txt list`|`/txt l`|
|帮助|`/txt or /txt help`|`/txt ?`|

**请使用 `#` 换行**

### json注释

- uft.json
```json
{
  "地图文件夹名称": {
    "文本名称1(唯一)": {
      "Xvec": 128,
      "Yvec": 90,
      "Zvec": 128,
      "TITLE": "标题",
      "TEXT": "文本(使用“#”换行)"
    }
  }
}
```

- ft.json
```json
{
  "地图文件夹名称": {
    "文本名称1(唯一)": {
      "Xvec": 128,
      "Yvec": 90,
      "Zvec": 128,
      "TITLE": "标题",
      "TEXT": "文本(使用“#”换行)",
      "OWNER": "史蒂夫"
    }
  }
}
```
