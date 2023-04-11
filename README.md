# Bilibili Catcher
---
跟踪B站最新状况：
- [x] 当日分区投稿情况
- [x] 当前在线人数最多视频
---
查询用户成分:
- [x] 基本信息查询
- [x] 基于动态关键词生成云图
- [x] 基于历史投稿生成标签云图
- [ ] *用户评价(正在开发)*
---
查询视频:
- [x] 基本信息查询
- [x] 获取视频下载源
- [ ] *弹幕主人查询(正在开发)*
---
## 食用方法

1. 经过测试，该程序在**PHP-56**~**PHP-81**可以正常运行，其余版本可以自行测试。
2. 进入[/api/class.php](https://github.com/ImMappyJ/bilibili-catcher/blob/master/api/class.php)有如下代码:
    ```php
    define("_WEB_URL","http://localhost"); //个人域名
    define("_BILI_COOKIE",""); //bilibili 用户cookie (用于下载视频)
    ```
    修改常量为你当前的值，`_WEB_URL`的值必须携带**https**或**http**

3. [footer.php](https://github.com/ImMappyJ/bilibili-catcher/blob/master/footer.php)中修改你的备案信息与版权信息
4. 接下来便可以使用了
   
## 注意事项

- `_BILI_COOKIE`的值必须可用，只有配置成功才能够下载720p及以上清晰度视频。
