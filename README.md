# Bilibili Catcher
跟踪B站最新状况：
- [x] 当日分区投稿情况
- [x] 当前在线人数最多视频
---
查询用户成分:
- [x] 基本信息查询 :sweat_smile:
- [x] 基于动态关键词生成云图
- [x] 基于历史投稿生成标签云图
---
查询视频:
- [x] 基本信息查询
- [x] 获取视频下载源
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

4. [/api/userdynamicskey.php](https://github.com/ImMappyJ/bilibili-catcher/blob/master/api/userdynamicskey.php)中的
    ```php
        $k_v = [
            "原" => "原神",
            "崩" => "崩坏",
            "农" => "王者荣耀",
            "粥" => "明日方舟"
        ];
    ```
    则为动态成分关键词，可以按照需要添加，格式为
    ```php
        $k_v = [
            "成分昵称" => "检测关键词",
            "成分昵称" => "检测关键词"
        ];
    ```
   在上述代码中，若动态中存在**原神**则会显示**原**

5. 到此配置完毕
## 注意事项

- `_BILI_COOKIE`的值必须可用，只有配置成功才能够下载720p及以上清晰度视频。
