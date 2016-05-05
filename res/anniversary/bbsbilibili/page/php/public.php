<?php 
/**TODO:
 * 公共资源
 * 
 * 
  序号[从1开始]：
	弹幕： 3 4 5 9 10  11  15  16  18
	普通： 1 2 6 7  8   12  13  14  17 
 * 
 */

//项目目录对应的URL，需要根据实际情况手动配置
$APP_URL = "http://".$_SERVER['SERVER_ADDR'].":8080/hfs/DW_Websites/Design";
//资源地址前缀，视频地址示例（加"/1/1.flv"），弹幕地址示例（加"/1/1.xml"）
$url_prex = "http://".$_SERVER['SERVER_ADDR']."/BBS/%E7%BD%91%E9%A1%B5%E8%AE%BE%E8%AE%A1%E4%BD%9C%E4%B8%9A%E8%B5%84%E6%BA%90";   //"http://".$_SERVER['SERVER_NAME']."/BBS/%E7%BD%91%E9%A1%B5%E8%AE%BE%E8%AE%A1%E4%BD%9C%E4%B8%9A%E8%B5%84%E6%BA%90";
//皮肤1地址
$url_skin1 = $APP_URL."/img/swf/KsPlayer/grungetape.zip";
//皮肤2地址
$url_skin2 = $APP_URL."/img/swf/KsPlayer/nemesis.zip";
//弹幕播放器SWF地址
$danmu_swf = $APP_URL."/img/swf/MukioPlayer_no_list.swf";
//普通播放器SWF地址
$player_swf = $APP_URL."/img/swf/KsPlayer/Player.swf";



$titles = array(
		"「周年纪念」无论何时，永远在一起【氷菓＿さくら荘＿CLANNAD_中二病_andElse】"
		,"bad apple原版高清14401080"
		,"【鬼畜配音】猫和老鼠 海水玩具鸭"
		,"【ねぎトロ】巡音ルカと初音ミクが三年目の浮気をデュエット"
		,"【AMV】在这渺小的世界里"
		
		,"欢迎回到现实世界"
		,"初音ミクと巡音ルカ-Magnet"
		,"【氷菓】花如樱花 倾城如君【MAD】"
		,"【冰菓】十年后 我一定不会后悔现在度过的每一天"
		,"某科学的超加速世界S"
		
		,"【玉子市场】主任偏头痛"
		,"涼宮ハルヒの消失 【MAD】キョンの選択"
		,"A卡眼中的超电磁炮S"
		,"【轻音少女】相遇天使-U&i!"
		,"欢迎加入NHK~！第三年的见异思迁"
		
		,"【Worst End】我的妹妹不可能那么血腥"
		,"【MAD】樱花庄的宠物真白"
		,"【真-误解】 伪物语不可能这么血腥"); 



?>