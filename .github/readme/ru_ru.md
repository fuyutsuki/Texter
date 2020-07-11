<img src="/assets/Texter.png" width="400px">  

[![GitHub license](https://img.shields.io/badge/license-UIUC/NCSA-blue.svg)](https://github.com/fuyutsuki/Texter/blob/master/LICENSE)
[![](https://poggit.pmmp.io/shield.state/Texter)](https://poggit.pmmp.io/p/Texter)
[![](https://poggit.pmmp.io/shield.api/Texter)](https://poggit.pmmp.io/p/Texter)  

[![](https://poggit.pmmp.io/shield.dl/Texter)](https://poggit.pmmp.io/p/Texter) / [![](https://poggit.pmmp.io/shield.dl.total/Texter)](https://poggit.pmmp.io/p/Texter)

[![PoggitCI Badge](https://poggit.pmmp.io/ci.badge/fuyutsuki/Texter/Texter)](https://poggit.pmmp.io/ci/fuyutsuki/Texter/Texter)

### резюме

Вы можете изменить язык в консоли, изменив `locale` в [config.yml](/resources/config.yml).  
Поддерживаемые языки автоматически переводятся в соответствии с настройками языка клиента каждого игрока.

Другие языки:
[English](/README.md),
[日本語](./ja_jp.md),
[中文](./zh_cn.md),
[Türkçe](./tr_tr.md),
[한국어](./ko_kr.md),
[Indonesia](./id_id.md)

***

## Текстер

Texter - это плагин, который позволяет вам просматривать, редактировать, перемещать и удалять FloatingTextPerticle в нескольких мирах.
Последняя версия: **3.4.9**


<!--
**Эта отрасль находится в стадии разработки. Может содержать много ошибок.**
-->


### Статус поддержки

- [x] Minecraft (основа)
- [x] Несколько языков (английский, японский, Японский, китайский, турецкий)
- [x] Показать несколько миров

### Скачать

* [Поггит](https://poggit.pmmp.io/p/Texter)

### команда

#### Общая команда

| \ |Команда|псевдоним|
|:-:|:-:|:-:|
|Добавить плавающие символы|`/txt add`|`/txt a`|
|Плавающее редактирование символов|`/txt edit`|`/txt e`|
|Переместить плавающие символы|`/txt move`|`/txt m`|
|Удалить плавающие символы|`/txt remove`|`/txt r`|
|Плавающий список символов|`/txt list`|`/txt l`|
|Помощь|`/txt или /txt help`|`/txt ?`|

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
