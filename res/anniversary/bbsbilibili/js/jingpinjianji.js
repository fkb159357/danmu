// JavaScript Document
//按每行三个输出视频缩略图
function show_videos(){
	
	var titles = new Array(
		"「周年纪念」无论何时，永远在一起【氷菓＿さくら荘＿CLANNAD_中二病_andElse】"
		,"bad apple原版高清14401080"
		,"欢迎回到现实世界"
		,"初音ミクと巡音ルカ-Magnet"
		,"【氷菓】花如樱花 倾城如君【MAD】"
		,"涼宮ハルヒの消失 【MAD】キョンの選択"
		,"A卡眼中的超电磁炮S"
		,"【轻音少女】相遇天使-U&amp;i!"
		,"【MAD】樱花庄的宠物真白");
	//请求弹幕播放页面的URL前缀
	var php_url_prefix = "../page/php/currentplayer.php";
	
	var arr = new Array("left","middle","right");
	var tmp = 0;
	for(var i=0;i<3;i++){
		document.write("<div class='v_row'>");
		for(var j=0;j<3;j++){
			tmp = 3 * i + j + 1;
			document.write("<a name='v"+tmp+"' href='"+php_url_prefix+"?video="+tmp+"&xml="+tmp+"'>");
			document.write("<div class='v_"+arr[j]+"'>");
			document.write("<img class='tiny' id='tiny"+tmp+"'  src='../img/jingpinjianji/3x3/"+tmp+".jpg' />");
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


