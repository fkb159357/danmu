// JavaScript Document
/**
	shouye() ~~~ ziyuanxiazai()负责处理导航区移入事件
*/
function shouye(){
//	window.open("./first.html","main");
	$("#first_menu").mouseover(function(){
		this.src="../img/navy/first.png";
	});
	$("#first_menu").mouseout(function(){
		this.src="../img/navy/first-as.png";
	});
}

function jingpinjianji(){
//	window.open("./jingpinjianji.html","main");
	$(".img_menu:eq(0)").mouseover(function(){
		this.src="../img/navy/jingpinjianji.png";
	});
	$(".img_menu:eq(0)").mouseout(function(){
		this.src="../img/navy/jingpinjianji-as.png";
	});
}

function zhoubiandanmu(){
//	window.open("./zhoubiandanmu.html","main");
	$(".img_menu:eq(1)").mouseover(function(){
		this.src="../img/navy/zhoubiandanmu.png";
	});
	$(".img_menu:eq(1)").mouseout(function(){
		this.src="../img/navy/zhoubiandanmu-as.png";
	});	
}

function manqugaikuang(){
//	window.open("./manqugaikuang.html","main");
	$(".img_menu:eq(2)").mouseover(function(){
		this.src="../img/navy/manqugaikuang.png";
	});
	$(".img_menu:eq(2)").mouseout(function(){
		this.src="../img/navy/manqugaikuang-as.png";
	});	
}

function ziyuanxiazai(){
//	window.open("./ziyuanxiazai.html","main");
	$(".img_menu:eq(3)").mouseover(function(){
		this.src="../img/navy/ziyuanxiazai.png";
	});
	$(".img_menu:eq(3)").mouseout(function(){
		this.src="../img/navy/ziyuanxiazai-as.png";
	});	
}

