<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <title>ACG Random FM - LTRE LAB</title>
    <meta name="description" content="ACG随机电台">
    <meta name="keywords" content="ACG随机电台,FM,Ltre Oreki">
    <meta name="author" content="Ltre Oreki">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="res/lib/favicon.ico">
    <!-- Loading Bootstrap -->
    <!--<link href="res/lib/bootstrap3/css/bootstrap.min.css" rel="stylesheet">-->
	<link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <!-- 加载Material Design -->
    <!--<link href="res/lib/bootstarp-material-design/css/ripples.min.css" rel="stylesheet">-->
	<link href="//cdn.bootcss.com/bootstrap-material-design/0.2.2/css/ripples.min.css" rel="stylesheet">
    <!--<link href="res/lib/bootstarp-material-design/css/material-wfont.min.css" rel="stylesheet">-->
	<link href="//cdn.bootcss.com/bootstrap-material-design/0.2.2/css/material-wfont.min.css" rel="stylesheet">
    <!-- 加载自定义公共CSS库 -->
    <link href="res/lib/lib.css?v=20150513" rel="stylesheet">
  </head>
  
  <body style="background: url('res/biz/fm/fm-bg-light.jpg') repeat fixed;">
    
    <!-- 正式的播放区域 -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-7 container" id="play-main" style="position: relative; height: 500px; background: rgba(200,190,175,0.13);"><!-- rgba(100,200,150,0.1) -->
                <div style="width: 200px; height: 200px; border-radius: 100px !important; background-color:#777; position: absolute;">
                    <div onclick="FM.pause()" style="width: 160px; height: 160px; border-radius: 80px !important; margin: 20px auto; background-color: #bbb; cursor: pointer;"></div>
                </div>
            </div>
            <div class="col-xs-5 container" id="play-list" style="position: relative; height: 500px; overflow:auto; color: #ffffff;background: url('res/biz/fm/fm-bg.jpg') repeat fixed;">
                <div class="row hide" style="margin-top: 10px;">
                    <div class="col-xs-5">作者</div>
                    <div class="col-xs-5">名前</div>
                    <div class="col-xs-2">時長</div>
                </div>
                <!-- 模板名：tpl-history -->
                <div class="row tpl-history hide" id="tpl-history" data-history-pos="-1" style="margin-top: 10px;">
                    <div class="col-xs-2 fm-avatar"><img width="65" height="65"/></div>
                    <div class="col-xs-3 fm-singer"></div>
                    <div class="col-xs-5 fm-songname"></div>
                    <div class="col-xs-1 fm-duration"></div>
                    <div class="col-xs-1 fm-share"><i class="mdi-action-launch"></i></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- 临时代码，待转移 -->
    <audio></audio>
    <tpl name="loadOne"></tpl>
    <div id="song-control-nav" class="container-fluid" style="margin-top: 25px;">
        <div class="row-fluid"><div class="current-song col-xs-8 col-xs-offset-2" style="text-align: center;"><marquee scrollamount="22">ACG - FM - 简易版</marquee></div></div>
        <div class="row-fluid"><div class="col-xs-12">&nbsp;</div></div>
        <div class="row-fluid">
            <div class="col-xs-2 col-xs-offset-3"><i class="prev-song mdi-av-fast-rewind"></i></div>
            <div class="col-xs-2"><i class="song-pause mdi-av-play-circle-outline"></i><i class="song-pause mdi-av-pause-circle-outline hide"></i></div>
            <div class="col-xs-2"><i class="next-song mdi-av-fast-forward"></i></div>
        </div>
    </div>
    
    <!-- 扫码加特技 -->
    <div style="position:fixed; top:0px; left:0px; font-size:12px;">
        <button id="enable-duang">启动特技</button>
        <button id="duang" style="display: none;">扫码加特技！</button>
        <button id="submit-new-song">提交5sing单曲地址收录</button>
    </div>
    <div id="qrcode" style="position: fixed; z-index: 20; top: 50px; left: 0px; display: none;"></div>
    
    <script src="res/lib/flat-ui/js/jquery.min.js"></script>
    <!--<script src="res/lib/bootstrap3/js/bootstrap.min.js"></script>-->
	<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <!--<script src="res/lib/bootstarp-material-design/js/ripples.min.js"></script>-->
	<script src="//cdn.bootcss.com/bootstrap-material-design/0.2.2/js/ripples.min.js"></script>
    <!--<script src="res/lib/bootstarp-material-design/js/material.min.js"></script>-->
	<script src="//cdn.bootcss.com/bootstrap-material-design/0.2.2/js/material.min.js"></script>
    <script src="http://cdn.bootcss.com/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
    <script src="http://cdn.bootcss.com/socket.io/1.3.5/socket.io.min.js"></script>
    <!-- 脚本加载顺序：基础库=>公共业务库=>特定业务 -->
    <script src="res/lib/lib.js?v=20150513"></script>
    <script src="res/biz/common/common.js?v=20150513"></script>
    <script src="res/biz/fm/fm.js?v=20150730"></script>
    <!-- jinguanyu -->
    <!-- <script src="res/lib/anime.js?v=20150513"></script> -->

    <script>
        $(document).ready(function() {
            $.material.init();
            $('body').css({
                'font-family' : '微软雅黑',
                'font-size' : '15px'
            });
            $('i').css({
                'cursor' : 'pointer'
            });
            $('#song-control-nav *').css({
                'line-height' : '36px',
                'font-size' : '36px'
            });
            $('#song-control-nav>.row-fluid>div').css({
                'text-align' : 'center'
            });
        });
    </script>

    <!-- CNZZ STAT CODE -->
    <div style="position:fixed; bottom:0px; left:0px;font-size:12px;">
      <script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1254005818'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s4.cnzz.com/z_stat.php%3Fid%3D1254005818%26show%3Dpic1' type='text/javascript'%3E%3C/script%3E"));</script>
    </div>

  </body>
</html>