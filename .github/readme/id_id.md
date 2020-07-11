<img src="/assets/Texter.png" width="400px">  

[![GitHub license](https://img.shields.io/badge/license-UIUC/NCSA-blue.svg)](https://github.com/fuyutsuki/Texter/blob/master/LICENSE)
[![](https://poggit.pmmp.io/shield.state/Texter)](https://poggit.pmmp.io/p/Texter)
[![](https://poggit.pmmp.io/shield.api/Texter)](https://poggit.pmmp.io/p/Texter)  

[![](https://poggit.pmmp.io/shield.dl/Texter)](https://poggit.pmmp.io/p/Texter) / [![](https://poggit.pmmp.io/shield.dl.total/Texter)](https://poggit.pmmp.io/p/Texter)

[![PoggitCI Badge](https://poggit.pmmp.io/ci.badge/fuyutsuki/Texter/Texter)](https://poggit.pmmp.io/ci/fuyutsuki/Texter/Texter)

### Gambaran

Anda dapat mengatur bahasa plugin dengan mengubah `locale` di [config.yml](/resources/config.yml)
juga, bahasa yang didukung akan ditampilkan secara otomatis sesuai dengan lokal klien.

Pilih bahasa lain:
[English](/README.md),
[日本語](./.github/readme/ja_jp.md),
[русский](./.github/readme/ru_ru.md),
[中文](./.github/readme/zh_cn.md),
[Türkçe](./.github/readme/tr_tr.md),
[한국어](./.github/readme/ko_kr.md)

***

## Texter

Texter adalah plugin yang menampilkan dan menghapus FloatingTextParticle yang didukung untuk multi-dunia.  
Terbaru: ver **3.4.9**


<!--
**Cabang ini sedang dikembangkan. Mungkin ada banyak bug.**  
-->


### Mendukung

- [x] Minecraft (Batuan Dasar)
- [x] Multi-bahasa (Inggris, Jepang, Rusia, Cina, Turki, Korea, Indonesia)
- [x] Layar multi-dunia

### Unduh

* [Poggit](https://poggit.pmmp.io/p/Texter)

### Perintah

#### Perintah umum

| \ |command|alias|
|:--:|:--:|:--:|
|Menambahkan teks|`/txt add`|`/txt a`|
|Sunting teks|`/txt edit`|`/txt e`|
|Memindahkan teks|`/txt move`|`/txt m`|
|hapus teks|`/txt remove`|`/txt r`|
|teks daftar|`/txt list`|`/txt l`|
|Tolong|`/txt or /txt help`|`/txt ?`|

**Silakan gunakan `#` untuk jeda baris.**

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
