<html>
<head>
<style type="text/css">
body{
    background-color: #eeeeee;
}
a{text-decoration: none;}


/* ----- div#box BEGIN ----- */
body {
    height: 100vh;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: box;
    display: flex;
    -webkit-box-align: center;
    -o-box-align: center;
    -ms-flex-align: center;
    -webkit-align-items: center;
    align-items: center;
}
#box {
    width: 100vw;
    height: 100vw;/*520px*/
    background: #eee url("res/biz/about/bg.png");
    -webkit-animation: scrollBg 15s linear infinite;
    -ms-animation: scrollBg 15s linear infinite;
    animation: scrollBg 15s linear infinite;
    background-size: 300px;
}
@-webkit-keyframes scrollBg {
    0% {
        background-position: 0 0;
    }
    100% {
        background-position: 0 -300px;
    }
}
@keyframes scrollBg {
    0% {
        background-position: 0 0;
    }
    100% {
        background-position: 0 -300px;
    }
}
/* ----- div#box END ----- */


</style>
</head>
<body ondblclick="location.href='./';" title="双击后回首页">
<div id="box"></div>
<div style="position: fixed; bottom: 0; right: 0;font-family: 黑体; font-size: 15px; text-align: right;">
    <div align="right">｛私について｝</div>
    <div>作者现用名：LTRE</div>
    <div><a href="res/anniversary/bbsbilibili">原弹幕社成员</a></div>
    <div style="color: #ccc;font-size: 10px;">HR BBS 曾用名 : 微晨的薄雾、安静的石板路、Hme、Plotter、撸大叔、折木同学、折木酱、折木さん</div>
    <div style="color: #ccc;font-size: 10px;">个人实验室{LTRE LAB}发掘中...可能使用的域名有：<a href="http://iio.ooo" target="_blank">iio.ooo</a> | <a href="http://miku.us" target="_blank">miku.us</a> | <a href="http://larele.com" target="_blank">larele.com</a> , 提供福利</div>
</div>

<!-- <script src="res/lib/flat-ui/js/jquery.min.js"></script> -->
<script src="res/lib/anime.js"></script>
<script type="text/javascript">
setInterval(function(){
    location.href = 'http://larele.com';
}, 2500);
</script>

</body>
</html>