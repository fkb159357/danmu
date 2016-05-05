// JavaScript Document
//按每行5个输出视频缩略图以及对应的论坛ID信息
function show_Users(){
	//论坛ID号
	var ids = new Array("9151","4258","10985","9374","4906",
						"11313","7662","7866","1820","11161",
						"11215","8240","11986","3883","9540",
						"1359","11128","11542","10975","2394",
						"11184","3266","7855");
	//论坛昵称
	var name = new Array("Sinde安","酷鲨","天青月影","一方通行","清心寡欲",
						"寒han","Tes『S丶秋喵 』","折木ちゃん","長門有希","星痕_Kins",
						"小虾","yuyan550","cylism","祤々‖","郑生",
						"射命丸","jud","桂木貴ま","初音ミク","冰の戦尘",
						"死灵涂炭","Lancer","莫名微");
	//每行的子DIV的CSS类名后缀
	var style = new Array("left","middle","middle","middle","right");
	var gif_imgs = new Array(3,4,6,8,9,16,23);//gif文件名列表
	var img_name = 0;	//图片文件名（无后缀）
	var ext_name = null;	//图片后缀名

	for(var i=0;i<5;i++){
		//输出行块v_row
		document.write("<div class='v_row'>");
		//输出行块v_row中的5个子块
		for(var j=0;j<5;j++){
			img_name = 5 * i + j + 1;
			if(img_name>23)break;
			document.write("<div class='v_"+style[j]+"'>");
			//alert("返回值："+gif_imgs.indexOf(img_name)+"tmp="+img_name);
			if(-1 != gif_imgs.indexOf(img_name)) 
				ext_name=".gif";
			else
				ext_name=".jpg";
			document.write("<a style='text-decoration:none;' href='http://bbs.sise.com.cn/home.php?mod=space&uid="+ids[img_name-1]+"'>");
			//图片
			document.write("<img width='150px' class='tiny' id='tiny"+img_name+"'  src='../img/manquzhuangkuang/ids/"+img_name+ext_name+"' />");
			$("#tiny"+img_name).css({"margin":"10px 10px","border":"0"});
			//标题
			document.write("<div style='text-align:center;margin:20px 15px;line-height:25px;font-weight:bold;font-size:18px;color:#9966ff;'>");
			document.write(name[img_name-1]+"</a>");
			document.write("</div>");//==v_title
			document.write("</div>");//==v_style[j]
		}
		document.write("</div>");//==v_row
	}    
	//输出定高的空白行
	document.write("<div class='blankline'/>");
}

//验证表单
function check_form(){
	var bbs_id = $("#bbs_id").val();
	var bbs_name = $("#bbs_name").val();
	var suggest = $("#suggest").val();
//	alert(bbs_id);
//	alert(bbs_name);
//	alert(theme_format);
//	alert(attention);
//	alert(suggest);
	
	var flag = 0;
	if(bbs_id==""){
		$("#bbs_id_tip").css({"display":""});
		flag++;
	}else{
		$("#bbs_id_tip").css({"display":"none"});
	}
	if(bbs_name==""){
		if(flag==0)
			$("#bbs_name_tip").css({"margin-left":"330px"});
		$("#bbs_name_tip").css({"display":""});
	}else
		$("#bbs_name_tip").css({"display":"none"});
	if(suggest=="")
		$("#suggest_tip").css({"display":""});
	else
		$("#suggest_tip").css({"display":"none"});
	if(bbs_id==""||bbs_name==""||suggest=="")
		return false;
}



