<?php
if (! (@$my = User::isLogin()) || ! Rooter::isRooter($my->passport)) putalert('你不是管理员或未登录');

//指定开发环境和线上环境不进行安装操作，直接跳到首页。配置详见Install.filter.php
$ret = call_user_func_array(array(new InstallFilter, 'in_ignore'), array());
if ($ret) putalert('线上环境禁止维护');
?><html>
<head>
<title>维护</title>
<style type="text/css">
body{font-family: 微软雅黑; font-size: 36px; font-weight: bold;}
div#menu{width: 800px; margin: 100px auto; text-align: center; }
</style>
</head>
<body>
<div id="menu">
    <a href="javascript:;" onclick="confirm('本操作不可逆，你确定？') && (location.href='?repair.fixconf')">修复弹幕加载</a><br><br>
    <a href="javascript:;" onclick="confirm('本操作不可逆，你确定？') && (location.href='?repair.reinstall')">重新装服务器</a><br><br>
</div>
</body>
</html>