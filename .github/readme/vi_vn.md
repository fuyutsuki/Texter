<div align="center">

<img src="/assets/Texter.png" width="600px">

<h1>Texter</h1>

Texter là plugin cho [PocketMine-MP](https://github.com/pmmp/PocketMine-MP) hỗ trợ nhiều thế giới và cho phép tạo, xóa, di chuyển, chỉnh sửa các chữ nổi.

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

Ngôn ngữ khác:
- [English](/README.md)
- [日本語](/.github/readme/ja_jp.md)
- [Русский](/.github/readme/ru_ru.md)


:inbox_tray: Tải về
-----------------------------------------

* [Poggit](https://poggit.pmmp.io/p/Texter)


:sparkles: Tính năng
-----------------------------------------

#### Lệnh

Tất cả các lệnh điều có quyền đặt thành `texter.command.txt` (chỉ dành cho OP).

| \ |Lệnh|Lệnh tắt|Cách dùng|
|:--:|:--:|:--:|:--|
|Thêm chữ|`/txt add`|`/txt a`|`/txt add [name] [text]`|
|Chỉnh sửa chữ|`/txt edit`|`/txt e`|`/txt edit [name] [text]`|
|Di chuyển chữ|`/txt move`|`/txt m`|`/txt move [name] [here\|x y z]`|
|Xóa chữ|`/txt remove`|`/txt r`|`/txt remove [name]`|
|help|`/txt`||`/txt`|

**Vui lòng thêm `#` để xuống hàng**.

#### Biến

Nếu bạn đã tải [Mineflow >= 2.0](https://poggit.pmmp.io/p/Mineflow), bạn có thể thêm biến vào chữ nổi.

|Tên|Kiểu dữ liệu|Có sẵn|
|:----|:-|:----------------|
|`server_name`|string||
|`microtime`|number||
|`time`|string||
|`date`|string||
|`default_world`|string||
|`player`|Player|[Mineflow#Player](https://github.com/aieuo/Mineflow#player)|
|`ft`|FloatingText|`name(string), pos(Position without world), spacing(Position without world)`|


:symbols: Ngôn ngữ
-----------------------------------------

Bạn có thể đổi ngôn ngữ trong bảng điều khiển (console) bằng cách chỉnh sửa `locale` trong [config.yml](/resources/config.yml).  
Các ngôn ngữ được hỗ trợ sẽ được dịch tự động theo cài đặt ngôn ngữ của mỗi người chơi

#### Trạng thái hỗ trợ

PR luôn được mở!

- [x] en_us(Tiếng Anh)
- [ ] id_id(Tiếng In-đô-nê-xi-a)
- [x] ja_jp(Tiếng Nhật)
- [ ] ko_kr(Tiếng Hàng)
- [x] ru_ru(Tiếng Nga)
- [ ] tr_tr(Tiếng Thủ Nhĩ Kỳ)
- [ ] zh_cn(Tiếng Trung Quốc)
- [x] vi_vn(Tiếng Việt)
