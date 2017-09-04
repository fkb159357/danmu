<html>

    <head>

        <title>入社 - LTRE LAB</title>
        <link href="./res/lib/bootstrap3/css/bootstrap.min.css" rel="stylesheet">
        <link href="./res/lib/bootstarp-material-design/css/ripples.min.css" rel="stylesheet">
    	<link href="./res/lib/bootstarp-material-design/css/material-wfont.min.css" rel="stylesheet">

    </head>

    <body>

        <div class="container" style="margin-top: 35px;">
            <div class="row">
                <div class="col-xs-6 col-xs-offset-3">
                    <div class="well bs-component">
                        <form class="form-horizontal" method="post" action="/user/reg">
                            <!-- <input type="hidden" name="x" value="user/reg"> -->
                            <input type="hidden" name="puttype" value="alert">
                            <fieldset>
                                <legend align="right">入社单</legend>
                                <div class="form-group">
                                    <label for="passport" class="col-lg-2 control-label">新建通行证</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="passport" name="passport" placeholder="英文|数字">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nickname" class="col-lg-2 control-label">绰号叫啥</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="nickname" name="nickname" placeholder="我是富土康三号流水线的张全蛋~">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="col-lg-2 control-label">登入口令</label>
                                    <div class="col-lg-10">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="登入口令">
                                        <input type="password" class="form-control" id="repassword" placeholder="确认口令">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox"> 我是老司机
                                            </label>
                                        </div>
                                        <br>
                                        <div class="togglebutton">
                                            <label>
                                                <input type="checkbox"> 求超越
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="textArea" class="col-lg-2 control-label">口头禅</label>
                                    <div class="col-lg-10">
                                        <textarea class="form-control" rows="3" id="textArea" placeholder="楼上的蛋我都有摸过"></textarea>
                                        <span class="help-block">比如你的QQ密码，银行卡密码啥啥啥的.</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">入社担保</label>
                                    <div class="col-lg-10">
                                        <div class="radio radio-primary">
                                            <label>
                                                <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked="">
                                                                                                我已经交450了
                                            </label>
                                        </div>
                                        <div class="radio radio-primary">
                                            <label>
                                                <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                                                                                                我是小学生
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="valicode" class="col-lg-2 control-label">验证码</label>
                                    <div class="col-lg-10">
                                        <img src="./?user/genValicode" width="90" height="30" onclick="this.src='./?user/genValicode&v='+Math.floor(new Date().getTime()/1000);">
                                        <br>
                                        <input type="text" class="form-control" id="valicode" name="valicode" placeholder="你是机器人吗">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-10 col-lg-offset-2">
                                        <button type="button" class="btn btn-default" onclick="location.href='./';return false;">算 了</button>
                                        <button type="submit" class="btn btn-primary">入 坑！</button>
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