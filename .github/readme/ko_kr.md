<img src="/assets/Texter.png" width="400px">  

[![GitHub license](https://img.shields.io/badge/license-UIUC/NCSA-blue.svg)](https://github.com/fuyutsuki/Texter/blob/master/LICENSE)
[![](https://poggit.pmmp.io/shield.state/Texter)](https://poggit.pmmp.io/p/Texter)
[![](https://poggit.pmmp.io/shield.api/Texter)](https://poggit.pmmp.io/p/Texter)  

[![](https://poggit.pmmp.io/shield.dl/Texter)](https://poggit.pmmp.io/p/Texter) / [![](https://poggit.pmmp.io/shield.dl.total/Texter)](https://poggit.pmmp.io/p/Texter)

[![PoggitCI Badge](https://poggit.pmmp.io/ci.badge/fuyutsuki/Texter/Texter)](https://poggit.pmmp.io/ci/fuyutsuki/Texter/Texter)

### 개요

플러그인의 언어를 바꾸시려면 [config.yml](/resources/config.yml)으로 가신 뒤, `locale`을 바꾸시면 됩니다. 
지원되는 언어은 locale을 따라서 자동으로 표시가 됩니다.

지원이 되는 언어:
[English](/README.md),
[日本語](./.github/readme/ja_jp.md),
[русский](./.github/readme/ru_ru.md),
[中文](./.github/readme/zh_cn.md),
[Türkçe](./.github/readme/tr_tr.md),
[Indonesia](./id_id.md)

***

## 텍스터

텍스터 플러그인은 홀로그램 사용과 멀티월드 지원을 합니다! 
최신 버전: ver **3.4.9**  

<!--
**이 지점은 개발 중입니다. 많은 버그가있을 수 있습니다**  
-->

### 텍스터는 무었을 지원 하나요?

- [x] 마인크래프트(Bedrock, 포켓에디션)
- [x] 다양한 언어 (영어, 일본어, 러시아어, 중국어, 터키어, 한국어)
- [x] 멀티월드 홀로그램 디스플레이

### 다운로드

* [Poggit](https://poggit.pmmp.io/p/Texter)

### 커맨드

#### 기본 커맨드

| \ |커맨드|쇼트컷|
|:--:|:--:|:--:|
|텍스트 추가|`/txt add`|`/txt a`|
|텍스트 편집|`/txt edit`|`/txt e`|
|텍스트 이동|`/txt move`|`/txt m`|
|텍스트 제거|`/txt remove`|`/txt r`|
|텍스트 리스트|`/txt list`|`/txt l`|
|도움|`/txt or /txt help`|`/txt ?`|

**`#` 로 여러 줄을 만들수있습니다**

### json 노트.(직접 게임접속을 하지 않아도 여기서 편집을 할수있습니다)

- uft.json
```json
{
  "월드 폴더 이름": {
    "텍스트 이름": {
      "Xvec": 128,
      "Yvec": 90,
      "Zvec": 128,
      "TITLE": "제목",
      "TEXT": "텍스트(#로 새로운 라인을 만들수 있습니다)"
    }
  }
}
```

- ft.json
```json
{
  "월드 폴더 이름": {
    "텍스트 이름": {
      "Xvec": 128,
      "Yvec": 90,
      "Zvec": 128,
      "TITLE": "제목",
      "TEXT": "텍스트(#로 새로운 라인을 만들수 있습니다)",
      "OWNER": "유저네임"
    }
  }
}
```
