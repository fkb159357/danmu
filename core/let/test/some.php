<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>弹幕生成器 - LTRE LAB | larele.com | ltre.me | miku.us | ltre.cc</title>
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
      <script src="res/lib/flat-ui/js/html5shiv.js"></script>
      <script src="res/lib/flat-ui/js/respond.min.js"></script>
    <![endif]-->
    
    <!-- jQuery (necessary for Flat UI's JavaScript plugins) -->
    <script src="res/lib/flat-ui/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="res/lib/flat-ui/js/flat-ui.min.js"></script>
  </head>
  <body>

    <!-- 头图区域 -->
    <div class="container well">
    <div class="row">
        <div class="col-xs-8 form-blank" style="height: 360px; background-color: aqua;"></div>
        <div class="col-xs-4" style="height: 360px; background-color: black;">
            <div class="row form-blank" style="height: 180px; background-color: blue;"></div>
            <div class="row form-blank" style="height: 180px; background-color: fuchsia;"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-4 form-blank" style="height: 180px; background-color: gray;"></div>
        <div class="col-xs-4 form-blank" style="height: 180px; background-color: green;"></div>
        <div class="col-xs-4 form-blank" style="height: 180px; background-color: lime;"></div>
    </div>
    </div>
  
    <!-- 表单填充模板，填入.form-blank -->
    <div id="superFormsTpl">
        <div class="container superForms hide">
            <form action="http://上传图片URL" class="uploadImgForm">
                <div class="form-group">
                    <label>选择图片</label>
                    <input type="file" name="url">
                </div>
            </form>
            <form action="http://提交推荐URL" class="submitTuijianForm hide">
                <div class="form-group">
                    <label>VID</label>
                    <input type="text" class="form-control" name="vid" placeholder="视频ID">
                </div>
                <input type="hidden" name="url">
                <button type="submit" class="btn btn-default">提交推荐</button>
            </form>
        </div>
    </div>

    <script type="text/javascript">
    $(function(){
        var b = $('.form-blank');
        b.html($('#superFormsTpl').html()).children('.superForms').removeClass('hide');
        
    });
    </script>
    
    <!-- jinguanyu -->
    <script src="http://miku.us/res/lib/anime.js"></script>
    
  </body>
</html>