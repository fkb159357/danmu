// JavaScript Document
//按每行三个输出视频缩略图
function show_videos(){
	
	var titles = new Array(
		"【鬼畜配音】猫和老鼠 海水玩具鸭"
		,"【ねぎトロ】巡音ルカと初音ミクが三年目の浮気をデュエット"
		,"【AMV】在这渺小的世界里"
		,"【冰菓】十年后 我一定不会后悔现在度过的每一天"
		,"某科学的超加速世界S"
		,"【玉子市场】主任偏头痛"
		,"欢迎加入NHK~！第三年的见异思迁"
		,"【Worst End】我的妹妹不可能那么血腥"
		,"【真-误解】 伪物语不可能这么血腥");
	//请求弹幕播放页面的URL前缀
	var php_url_prefix = "../page/php/danmuplayer.php";
	
	var arr = new Array("left","middle","right");
	var tmp = 0;
	for(var i=0;i<3;i++){
		document.write("<div class='v_row'>");
		for(var j=0;j<3;j++){
			tmp = 3 * i + j + 1;
			document.write("<a name='v"+tmp+"' href='"+php_url_prefix+"?video="+tmp+"&xml="+tmp+"'>");
			document.write("<div class='v_"+arr[j]+"'>");
			document.write("<img class='tiny' id='tiny"+tmp+"'  src='../img/zhoubiandanmu/3x3/"+tmp+".jpg' />");
			$("#tiny"+tmp).css({"width":"270px","margin":"15px 15px"});
			document.write("<div style='text-align:left;margin:5px 20px;line-height:23px;font-weight:bold;font-size:18px;color:#9966ff;'>");
			document.write(titles[tmp-1]);
			document.write("</div>");//==v_title
			document.write("</div>");//==v_arr[j]
			document.write("</a>");
		}
		document.write("</div>");//==v_row
	}    
	document.write("<div class='blankline'/>");
}