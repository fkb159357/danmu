<?php
if (! (@$my = User::isLogin()) || ! Rooter::isRooter($my->passport)) putalert('你不是管理员或未登录');

//指定开发环境和线上环境不进行修复操作，直接跳到首页。配置详见Install.filter.php
$ret = call_user_func_array(array(new InstallFilter, 'in_ignore'), array());
if ($ret) { header("Location: " . DI_URL_PREFIX);die;/* 防止非浏览器模式下继续执行后续内容 */ }

session(null);//清空本应用所有会话
unlink(DI_LET_PATH . 'repair/install_step');
header("Location: " . DI_URL_PREFIX . "?repair.install");