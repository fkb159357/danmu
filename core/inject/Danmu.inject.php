<?php

class DanmuInject extends DIInject {
    
    //Danmu模块级过滤
    function doInject(){
        $this->isNeedInstall();
        $this->fixconf();
    }
    
    //进入首页前，确定是否需要安装服务器
	private function isNeedInstall(){
		call_user_func_array(array(new InstallFilter, 'doFilter'), array());
	}	
	
	//加载弹幕之前，先判断IP是否被改。每120s内有后一半时间需要检测
	private function fixconf(){
	    if (function_exists('file_get_contents')) {
	        $now = time();
	        $start = date('Y-m-d');
	        $gap = 60;
	        $inGap = ($now - $start) % (2*$gap) > $gap;
	        if ($inGap) {
	            $f = 'res/biz/danmu/player/conf.xml';
	            $c = file_get_contents($f);
	            $keyword = DI_URL_PREFIX . '?danmu/load';
	            if (false === strpos($c, $keyword)) {
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
    <load>' . DI_URL_PREFIX . '?danmu/load/{$id}</load>
    <!-- POST发送地址,如果不提供则不发送,变量可用 -->
    <send>' . DI_URL_PREFIX . '?danmu/send/{$id}</send>
    <!-- Amf的POST发送地址,优先gateway -->
    <gateway></gateway>
  </server>
</conf>';
	                file_put_contents($f, $c_conf);
	            }
	        }
	    }
	}
	
	
	
}