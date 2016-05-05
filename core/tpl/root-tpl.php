<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>ROOTER</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Loading Bootstrap -->
    <link href="res/lib/flat-ui/css/bootstrap.min.css" rel="stylesheet">
    <!-- Loading Flat UI -->
    <link href="res/lib/flat-ui/css/flat-ui.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="res/img/favicon.ico">
    <!-- 加载公共-CSS -->
    <link href="res/lib/lib.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="res/lib/flat-ui/js/html5shiv.js"></script>
      <script src="res/lib/flat-ui/js/respond.min.js"></script>
    <![endif]-->
    
    <!-- 自定义CSS -->
    <style type="text/css">
    
    </style>
  </head>
  <body>
    
    
    <?php include DI_TPL_PATH . $concrete . '.php';//不要使用import()，否则无法使用extract()生成的变量?>
    <!-- 存储共享信息 -->
    <div class="hide">
        <input id="client_ip" value="<?php print $client_ip?>" />
    </div>
    
    
    <!-- jQuery (necessary for Flat UI's JavaScript plugins) -->
    <script src="res/lib/flat-ui/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="res/lib/flat-ui/js/flat-ui.min.js"></script>
    <!-- 注意脚本加载顺序：基础库=>公共业务库=>特定业务 -->
    <script src="res/lib/lib.js"></script>
    <script src="res/biz/common/root_common.js"></script>
    <script src="res/biz/root/root.js"></script>
    <script type="text/javascript">
    $('body').css({
        'font-family' : '微软雅黑',
        'background-color' : '#e2e2e2'
    });
    </script>

  </body>
</html>