// JavaScript Document

/**
图片格子区域4*4
*/

function show_imgs(){
	for(var i=1;i<=16;i++){
			document.write("<img class='tiny' id='tiny"+i+"' width='25%' height='25%' src='../img/first/4x4/"+i+".jpg' />");
			if(parseInt(i%4)==0)document.write("<br/>");
	}    
	
	var x = 0;
	//鼠标进入小图
	$("img.tiny").mouseover(function(){
		x=parseInt(Math.random()*(16-1))+1;
		$(this).attr("src","../img/first/4x4/"+x+".jpg");	//小图切换
		//$("body").css("background","url('img/large/"+x+".jpg')");//切换背景
	});
	//鼠标离开小图
	$("img.tiny").mouseout(function(){
		x=parseInt(Math.random()*(16-1))+1;
		//$("#tiny"+x).attr("src","");	//小图随机消失
		$(this).attr("src","../img/first/4x4/"+x+".jpg");	//小图切换
	});	
}




/************零散代码****************/
function demo(){
	$("#demo").css({"display":"none"});
}

$("#demo").click(function(){
	$("#demo").remove();
	alert("呵呵");
});



