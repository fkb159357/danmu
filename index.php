<?php
//附加服务器环境检测
if (version_compare(PHP_VERSION, '5.3.0') < 0) {
    header("Content-Type: text/html; charset=utf-8");
    die('<h3 align=center style="margin-top:100px;font-family:微软雅黑;">检测到PHP版本低于5.3.0，建议安装<a href="http://pan.baidu.com/s/1o6kgqHO" target="_blank">WampServer 2.2(PHP 5.3.x)</a>以上的版本后，再次执行该安装向导。</h3><br><h6 align=center><a href="http://miku.us/" target="_blank">.</a></h6>');
}

if (! function_exists('curl_init')) {
    header("Content-Type: text/html; charset=utf-8");
    print("<h1 align=right>打开姿势不对哦 (((( <font color=red>PHP-CURL扩展</font>没有启用~~</h1>");
    print("<h1 align=right>如果你使用WAMPSERVER2.2或更高版本，请<font color=red>左</font>键点击wamp的托盘图标。</h1>");
    print("<h1 align=right>在菜单中找到<font color=red>“PHP -> PHP扩展 -> php_curl”</font>，勾选此项，重新启动WampServer即可食用。</h1>");
    die("<h1 align=right>不知道食用方法？找你旁边的技♂术♂童♂鞋帮忙吧 )→_→) </h1>");
}

if (! function_exists('mysqli_connect')) {
    header("Content-Type: text/html; charset=utf-8");
    print("<h1 align=right>打开姿势不对哦 (((( <font color=red>PHP-MYSQL<font color=blue>i</font>扩展</font>没有启用~~</h1>");
    print("<h1 align=right>如果你使用WAMPSERVER2.2或更高版本，请<font color=red>左</font>键点击wamp的托盘图标。</h1>");
    print("<h1 align=right>在菜单中找到<font color=red>“PHP -> PHP扩展 -> php_mysql<font color=blue>i</font>”</font>，勾选此项，重新启动WampServer即可食用。</h1>");
    die("<h1 align=right>不知道食用方法？找你旁边的技♂术♂童♂鞋帮忙吧 )→_→) </h1>");
}

require 'core/base/__include.php';