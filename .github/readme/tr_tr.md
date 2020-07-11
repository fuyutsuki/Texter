<img src="/assets/Texter.png" width="400px">

[![GitHub lisansı](https://img.shields.io/badge/license-UIUC/NCSA-blue.svg)](https://github.com/fuyutsuki/Texter/blob/master/LICENSE)
[![](https://poggit.pmmp.io/shield.state/Texter)](https://poggit.pmmp.io/p/Texter)
[![](https://poggit.pmmp.io/shield.api/Texter)](https://poggit.pmmp.io/p/Texter)

[![](https://poggit.pmmp.io/shield.dl/Texter)](https://poggit.pmmp.io/p/Texter) / [![](https://poggit.pmmp.io/shield.dl.total/Texter)](https://poggit.pmmp.io/p/Texter)

[![PoggitCI Rozeti](https://poggit.pmmp.io/ci.badge/fuyutsuki/Texter/Texter)](https://poggit.pmmp.io/ci/fuyutsuki/Texter/Texter)

### Genel Bakış

Eklenti dilini, [config.yml](/resources/config.yml) içindeki “yerel ayarı” değiştirerek ayarlayabilirsiniz.  
ayrıca, desteklenen diller istemcinin yerel ayarına göre otomatik olarak görüntülenir.

Başka bir dil seçin:
[English](/README.md),
[日本語](./ja_jp.md),
[Русский](./ru_ru.md),
[中文](./zh_cn.md),
[한국어](./ko_kr.md),
[Indonesia](./id_id.md)

***

## Texter

Texter, çoklu dünya için desteklenen FloatingTextPerticle'ı görüntüleyen ve silen eklentidir.
En son: ver **3.4.9**


<!--
**Bu dal geliştirilme aşamasındadır. Çok fazla hata olabilir.**
-->


### Destekleyici

- [x] Minecraft (Ana kaya)
- [x] Çok dilli (İngilizce, Japonca, Rusça, Çince, Türkçe)
- [x] Çok dünyalı ekran

### İndir

* [Poggit](https://poggit.pmmp.io/p/Texter)

### Komutlar

#### Genel komut

| \ |komut|takma ad|
|:-:|:-:|:-:|
|Metin ekle|`/txt add`|`/txt a`|
|Metni düzenle|`/txt edit`|`/txt e`|
|Metni taşı|`/txt move`|`/txt m`|
|Metni kaldır|`/txt remove`|`/txt r`|
|Liste metinleri|`/txt list`|`/ txt l`|
|Yardım|`/txt or /txt help`|`/txt ?`|

**Satır kesmeleri için lütfen "#" işaretini kullanın.**

### json gösterimi

- uft.json
```json
{
  "LevelFolderName": {
    "MetinAdı (Benzersiz)": {
      "Xvec": 128,
      "Yvec": 90,
      "Zvec": 128,
      "TITLE": "Başlık",
      "TEXT": "Metin (# ile yeni satır)"
    }
  }
}
```

- ft.json
```json
{
  "LevelFolderName": {
    "TextName1 (Benzersiz)": {
      "Xvec": 128,
      "Yvec": 90,
      "Zvec": 128,
      "TITLE": "Başlık",
      "TEXT": "Metin (# ile yeni satır)",
      "OWNER": "Steve"
    }
  }
}
```
