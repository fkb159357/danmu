<?php
if (! (@$my = User::isLogin()) || ! Rooter::isRooter($my->passport)) putalert('你不是管理员或未登录');

//指定开发环境和线上环境不进行修复操作，直接跳到首页。配置详见Install.filter.php
$ret = call_user_func_array(array(new InstallFilter, 'in_ignore'), array());
if ($ret) { header("Location: " . DI_URL_PREFIX);die;/* 防止非浏览器模式下继续执行后续内容 */ }


$f_conf = 'res/biz/danmu/player/conf.xml';
$c_conf = '<?xml version="1.0" encoding="utf-8"?>
<conf>
  <performance>
    <!-- 最长弹幕/像素 -->
    <maxwidth>2048</maxwidth>
    <!-- 最高弹幕/像素 -->
    <maxheight>768</maxheight>
    <!-- 表面弹幕容量,包括有特效和无特效 -->
    <maxonstage>120</maxonstage>
    <!-- 特效弹幕容量,如果超出该容量,但是未达到表面弹幕容量,超出部分为无特效 -->
    <maxwitheffect>80</maxwitheffect>
  </performance>
  <server>
    <!-- 使用mukio播放器的方法处理参数,不用改变 -->
    <onhost>yes</onhost>
    <!-- 弹幕加载地址,变量{$id}为弹幕id -->
    <load>http://danmu.me/?danmu/load/{$id}</load>
    <!-- POST发送地址,如果不提供则不发送,变量可用 -->
    <send>http://danmu.me/?danmu/send/{$id}</send>
    <!-- Amf的POST发送地址,优先gateway -->
    <gateway></gateway>
  </server>
</conf>';
file_put_contents($f_conf, str_replace('http://danmu.me/', DI_URL_PREFIX, $c_conf));
header("Content-type: text/html; charset=utf-8");
echo '修复完成';
