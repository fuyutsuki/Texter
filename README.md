<img src="/assets/Texter.png" width="400px">  

[![GitHub license](https://img.shields.io/badge/license-UIUC/NCSA-blue.svg)](https://github.com/fuyutsuki/Texter/blob/master/LICENSE)
[![](https://poggit.pmmp.io/shield.state/Texter)](https://poggit.pmmp.io/p/Texter)
[![](https://poggit.pmmp.io/shield.api/Texter)](https://poggit.pmmp.io/p/Texter)  

[![](https://poggit.pmmp.io/shield.dl/Texter)](https://poggit.pmmp.io/p/Texter) / [![](https://poggit.pmmp.io/shield.dl.total/Texter)](https://poggit.pmmp.io/p/Texter)

[![PoggitCI Badge](https://poggit.pmmp.io/ci.badge/fuyutsuki/Texter/Texter)](https://poggit.pmmp.io/ci/fuyutsuki/Texter/Texter)

### Overview

Select Language: [English](#eng), [日本語](#jpn), [русский](#rus), [中文](#chs)

***

<a name="eng"></a>

# English

<!--
**This branch is under development. It may have many bugs.**  
-->

## Texter

Texter is plugin that displays and deletes FloatingTextPerticle supported to multi-world.  
Latest: ver **3.4.2**  

### Supporting

- [x] Minecraft(Bedrock)
- [x] Multi-language (English, Japanese, Russian)
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

***

<a name="jpn"></a>

# 日本語

<!--
**このブランチは開発中です。多くのバグを含む可能性があります。**  
-->

## Texter

TexterはFloatingTextPerticleを複数ワールドに渡り表示、編集、移動、削除ができるプラグインです。  
最新バージョン: **3.4.2**  

### 対応状況

- [x] Minecraft(Bedrock)
- [x] 複数言語 (英語, 日本語)
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

***

<a name="rus"></a>

# Японский

**Эта отрасль находится в стадии разработки. Может содержать много ошибок.**

## Текстер

Texter - это плагин, который позволяет вам просматривать, редактировать, перемещать и удалять FloatingTextPerticle в нескольких мирах.
Последняя версия: **3.4.2**

### Статус поддержки

- [x] Minecraft (основа)
- [x] Несколько языков (английский, японский, Японский)
- [x] Показать несколько миров

### Скачать

* [Поггит](https://poggit.pmmp.io/p/Texter)

### команда

#### Общая команда

| \ | Команда | псевдоним |
|: -: |: -: |: -: |
| Добавить плавающие символы | `/ txt add` |` / txt a` |
| Плавающее редактирование символов | `/ txt edit` |` / txt e` |
| Переместить плавающие символы | `/ txt move` |` / txt m` |
| Удалить плавающие символы | `/ txt remove` |` / txt r` |
| Плавающий список символов | `/ txt list` |` txt l` |
| Помощь | `/ txt или / txt help` |` / txt? `|

**Используйте `#` для разрывов строк.**

### json нотация

- uft.json
```json
{
  "Имя мировой папки": {
    "Плавающее имя персонажа (уникальное)": {
      "Xvec": 128,
      "Yvec": 90,
      "Zvec": 128,
      "TITLE": "Заголовок",
      "ТEXT": "текст (новая строка #)"
    }
  }
}
```

- ft.json
```json
{
  "Имя мировой папки": {
    "Плавающее имя персонажа (уникальное)": {
      "Xvec": 128,
      "Yvec": 90,
      "Zvec": 128,
      "TITLE": "Заголовок",
      "TEXT": "текст (перевод строки - #)",
      "OWNER": "Стив"
    }
  }
}
```

<a name="chs"></a>

# 中文

<!--
**这个插件仍在开发中。它可能有很多bug。**  
-->

## Texter

Texter是一个插件，显示和删除浮动的文本且支持多世界。
最新版本: ver **3.4.2**  

### 支持

- [x] Minecraft(Bedrock)
- [x] 多语言 (英文, 日语, 俄罗斯语，中文)
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
