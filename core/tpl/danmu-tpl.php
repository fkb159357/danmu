<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>弹幕生成器 - LTRE LAB power by 折木 | iio.ooo | miku.us | larele.com | ltre.me</title>
    <meta name="description" content="ACG弹幕生成器">
    <meta name="keywords" content="弹幕生成器,ACG弹幕生成器,Miku.us,Larele.com,Ltre.me,Ltre.cc,MukioPlayer,MukioPlayerPlus,Oreki,Ltre,Miku,Larele">
    <meta name="author" content="Ltre Oreki">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Loading Bootstrap -->
    <link href="res/lib/flat-ui/css/bootstrap.min.css" rel="stylesheet">
    <!-- Loading Flat UI -->
    <link href="res/lib/flat-ui/css/flat-ui.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="res/lib/favicon.ico">
    <!-- 加载自定义公共CSS库 -->
    <link href="res/lib/lib.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="./res/lib/flat-ui/js/html5shiv.js"></script>
      <script src="./res/lib/flat-ui/js/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    
    <nav class="container rd13 navbar navbar-inverse navbar-embossed navbar-fixed-top" role="navigation" style="background: rgba(47,65,84,0.5);">
      <div class="navbar-header">
        <a class="navbar-brand" href="./">弹幕社</a>
      </div>
      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
          <li <?php $navy_active('gen')?>><a href="./?danmu/gen">生成器</a></li>
          <li <?php print'my'==arg('u')?'class=active':'' ?>><a href="./?danmu/search&u=my" >我的</a></li>
          <li <?php 'my'!=arg('u')&&$navy_active('search')?>><a href="./?danmu/search">公共列表</a></li>
          <li><a href="./?fm">电台(空壳版)</a></li>
          <li <?php $navy_active('okasii')?>><a href="./?danmu/okasii" target="_blank">奇怪功能</a></li>
          <li><a href="./?repair.repair" target="_self">维护</a></li>
          <li><a href="./?about" target="_blank">｛｝</a></li>
        </ul>
        <form class="navbar-form navbar-right" role="search" onsubmit="Danmu.search();return false">
          <div class="form-group">
            <div class="input-group">
              <input class="form-control danmu-search-field" id="keyword" name="keyword" type="search" placeholder="要找啥？">
              <input class="hide danmu-search-field" id="page" name="page" type="hidden" placeholder="当前页">
              <input class="hide danmu-search-field" id="persize" name="persize" type="hidden" placeholder="每页条数">
              <input class="hide danmu-search-field" id="pagescope" name="pagescope" type="hidden" placeholder="页码范围">
              <span class="input-group-btn">
                <button type="button" class="btn" onclick="Danmu.search()"><span class="fui-search"></span></button>
              </span>
            </div>
          </div>
        </form>
      </div><!-- /.navbar-collapse -->
    </nav><!-- /navbar -->
    
    <?php include DI_TPL_PATH . $concrete . '.php';//不要使用import()，否则无法使用extract()生成的变量?>
    <!-- 存储共享信息 -->
    <div class="hide">
        <input id="client_ip" value="<?php print $client_ip?>" />
    </div>
    
    
    <!-- jQuery (necessary for Flat UI's JavaScript plugins) -->
    <script src="./res/lib/flat-ui/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="./res/lib/flat-ui/js/flat-ui.min.js"></script>
    
    <!-- 脚本加载顺序：基础库=>公共业务库=>特定业务 -->
    <script src="./res/lib/lib.js"></script>
    <script src="./res/biz/common/common.js"></script>
    <script src="./res/biz/danmu/danmu.js?v=20150905"></script>
    <script type="text/javascript">
    $('body').css({
        'font-family' : '微软雅黑',
        //'background-color' : '#e2e2e2'
        'background' : "url('res/biz/danmu/tpl-bg-light.jpg') repeat fixed"
    });
    $('.navbar-inverse .navbar-nav>.active>a, .navbar-inverse .navbar-nav>.active>a:hover, .navbar-inverse .navbar-nav>.active>a:focus').css('background-color', '#53606C');
    </script>

    <div style="position: fixed; bottom: 0; right: 0;background-color: rgba(0,0,0,0.3);"><a href="http://www.miitbeian.gov.cn/">粤ICP备15066774-2号</a></div>
    
    <!-- CNZZ STAT CODE -->
    <div style="position:fixed; bottom:0px; left:0px;font-size:12px;">
      <script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1254005818'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s4.cnzz.com/z_stat.php%3Fid%3D1254005818%26show%3Dpic1' type='text/javascript'%3E%3C/script%3E"));</script>
    </div>
    
    
  </body>
</html>