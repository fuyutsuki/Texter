<div align="center">

<img src="/assets/Texter.png" width="600px">

<h1>Texter</h1>

Texter - это плагин для [PocketMine-MP](https://github.com/pmmp/PocketMine-MP) с поддержкой мультимиров и возможностью добавлять, изменять, перемещать и удалять летающий текст.

[![GitHub](https://img.shields.io/github/license/fuyutsuki/Texter?style=flat-square)](https://github.com/fuyutsuki/Texter/blob/master/LICENSE)
[![](https://poggit.pmmp.io/shield.state/Texter&style=flat-square)](https://poggit.pmmp.io/p/Texter)
[![](https://poggit.pmmp.io/shield.api/Texter&style=flat-square)](https://poggit.pmmp.io/p/Texter)

[![](https://poggit.pmmp.io/shield.dl/Texter&style=flat-square)](https://poggit.pmmp.io/p/Texter) / [![](https://poggit.pmmp.io/shield.dl.total/Texter&style=flat-square)](https://poggit.pmmp.io/p/Texter)

[![значок PoggitCI](https://poggit.pmmp.io/ci.badge/fuyutsuki/Texter/Texter)](https://poggit.pmmp.io/ci/fuyutsuki/Texter/Texter)

</div>

***

<!--
**Эта ветка в стадии разработки. Может содержать много ошибок**
-->

Другие языки:
- [English](/README.md)
- [日本語](/.github/readme/ja_jp.md)
- [Tiếng Việt](/.github/readme/vi_vn.md)


:inbox_tray: Скачать
-----------------------------------------

* [Poggit](https://poggit.pmmp.io/p/Texter)


:sparkles: Функции
-----------------------------------------

#### Команды

Для всех команд установлены права доступа `texter.command.txt` (только оператору).

| \ |Команда|субкоманды|использование|
|:--:|:--:|:--:|:--|
|Добавить текст|`/txt add`|`/txt a`|`/txt add [название] [содержание]`|
|Изменить текст|`/txt edit`|`/txt e`|`/txt edit [название] [содержание]`|
|Переместить текст|`/txt move`|`/txt m`|`/txt move [название] [here\|x y z]`|
|Удалить текст|`/txt remove`|`/txt r`|`/txt remove [название]`|
|Помощь|`/txt`||`/txt`|

**Пожалуйста, используйте `#` для переноса строк**.

#### Переменные

Если у Вас установлен [Mineflow >= 2.0](https://poggit.pmmp.io/p/Mineflow), то появится возможность использовать переменные в летающем тексте.

|название|тип|доступные свойства|
|:----|:-|:----------------|
|`server_name`|строка||
|`microtime`|число||
|`time`|строка||
|`date`|строка||
|`default_world`|строка||
|`player`|Игрок|[Mineflow#Player](https://github.com/aieuo/Mineflow#player)|
|`ft`|Летающий текст|`name(строка), pos(Vector3), spacing(Vector3), texts(список)`|


:symbols: Язык
-----------------------------------------

Вы можете сменить язык через консоль с изменением `locale` в [config.yml](/resources/config.yml).  
Поддерживаемые языки будут автоматически установлены для игрока в соответствии с его игровым языком.

#### Статус

Запросы (PR) на других языках приветствуются! 

- [x] en_us(English)
- [ ] id_id(Indonesian)
- [x] ja_jp(Japanese)
- [ ] ko_kr(Korean)
- [x] ru_ru(Russian)
- [ ] tr_tr(Turkish)
- [ ] zh_cn(Chinese/Simplified)
- [x] vi_vn(Vietnamese)
