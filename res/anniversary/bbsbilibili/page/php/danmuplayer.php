<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
/** TODO:
	 自带弹幕的播放器
 */
header("ContentType: text/html; charset=utf-8");
include_once './public.php';
$video=null;
$xml=null;
if(@""==($video = $_GET['video'])) return;
if(@""==($xml = $_GET['xml'])) return;
$vid = array(3,4,5,9,10,11,15,16,18);	//弹幕视频编号，从1开始
$real_vid = $vid[$video-1];
$xml = $vid[$xml-1];
$play = $danmu_swf."?file=".$url_prex."/".$real_vid."/".$real_vid.".flv&cfile=".$url_prex."/".$xml."/".$xml.".xml";
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
<!--
	#container{
	width: 960px;
	margin-right: auto;
	margin-left: auto;
	}
	#header{
	font-family: "微软雅黑";
	font-size: 18px;
	font-weight: bolder;
	height: 50px;
	width: 730px;
	line-height: 20px;
	text-align: center;
	margin-right: auto;
	margin-left: auto;
	margin-top: 20px;
	color: #666;
	}
	#content{
	height: 490px;
	width: 730px;
	margin-right: auto;
	margin-left: auto;
	border: 8px groove #999;
	}
	#footer{
	margin-top: 30px;
	margin-right: auto;
	margin-left: auto;
	width: 730px;
	text-align: center;
	letter-spacing: 5px;
	font-family: "微软雅黑";
	font-size: 18px;
	font-weight: bolder;
	line-height: 30px;
	}
-->
</style>
<title>自带弹幕的播放器</title>
</head>
<body bgcolor="#cccccc" background="../../img/global/bg.jpg">
	<div style="position:fixed"><a href="../zhoubiandanmu.html#v<?=$video ?>"><img border="0" src="../../img/global/back.png" /></a></div>
    <div id="container">
        <!-- 弹幕标题区域 -->
        <div id="header">
        	<?=$titles[$real_vid-1] ?>        
</div>
        <!-- 弹幕播放器区域 -->
        <div id="content">
            <embed height='490px' width='730px' 
                AllowScriptAccess='always' rel='noreferrer'
                type='application/x-shockwave-flash'
                allowfullscreen='true' quality='high'
                src="<?=$play ?>">
            </embed>
        </div>
        <!-- 弹幕说明区域 -->
        <div id="footer"><span>这里是描述内容这里是描述内容这里是描述内容这里是描述内容</span></div>
</div>
</body>
