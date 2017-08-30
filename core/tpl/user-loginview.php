<html>

    <head>

        <title>打卡 - LTRE LAB power by 折木 | iio.ooo | miku.us | larele.com | ltre.me </title>
        <link href="./res/lib/bootstrap3/css/bootstrap.min.css" rel="stylesheet">
        <link href="./res/lib/bootstarp-material-design/css/ripples.min.css" rel="stylesheet">
    	<link href="./res/lib/bootstarp-material-design/css/material-wfont.min.css" rel="stylesheet">

    </head>

    <body>

        <div class="container" style="margin-top: 35px;">
            <div class="row">
                <div class="col-xs-6 col-xs-offset-3">
                    <div class="well bs-component">
                        <form class="form-horizontal" method="post" action="/user/login">
                            <!-- <input type="hidden" name="user/login"> -->
                            <input type="hidden" name="sucb" value="<?php print $sucb?>">
                            <input type="hidden" name="facb" value="<?php print $facb?>">
                            <fieldset>
                                <legend align="right">打卡单</legend>
                                <div class="form-group">
                                    <label for="passport" class="col-lg-2 control-label">通行证</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="passport" name="passport" placeholder="出示你的保护费代号">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="col-lg-2 control-label">登入口令</label>
                                    <div class="col-lg-10">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="不记得了？就是你的银行卡密码">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox"> 我是老司机
                                            </label>
                                        </div>
                                        <br>
                                        <div class="togglebutton">
                                            <label>
                                                <input type="checkbox" checked="checked"> 求跟踪
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-10 col-lg-offset-2">
                                        <button id="form-reg" type="button" class="btn btn-default" onclick="javascript:location.href='?user/regview';">入 社</button>
                                        <button id="form-login" type="submit" class="btn btn-primary">打 卡！</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        
        <script src="./res/lib/flat-ui/js/jquery.min.js"></script>
        <script src="./res/lib/bootstrap3/js/bootstrap.min.js"></script>
    	<script src="./res/lib/bootstarp-material-design/js/ripples.min.js"></script>
	    <script src="./res/lib/bootstarp-material-design/js/material.min.js"></script>
        <script>
            $(document).ready(function() {
                $.material.init();
                $('body').css({
                    'font-family' : '微软雅黑',
                    'font-size' : '12px'
                });
            });
        </script>

    </body>

</html>