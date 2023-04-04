<html>
    <head>
        <title>管理中心</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="css/admin.css">
    </head>
    <body>
        <div id="container-main">
            <h1>登录</h1>
            <form action="admin.php" method="POST" style="vertical-align:middle;">
                <label for="email" style="margin: 0 30 0 auto;">Email:</label>
                <input type="email" id="email" name="email" required>
                <br />
                <label for="pwd" style="margin: 0 auto;">Password:</label>
                <input type="password" id="pwd" name="pwd" required>
                <br />
                <br />
                <br />
                <input type="submit" value="登录" style="bottom: 0px; font-size: larger;">
            </form>
        </div>
    </body>
</html>