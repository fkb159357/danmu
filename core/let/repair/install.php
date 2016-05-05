<?php
//指定开发环境和线上环境不进行安装操作，直接跳到首页。配置详见Install.filter.php
$ret = call_user_func_array(array(new InstallFilter, 'in_ignore'), array());
if ($ret) {header("Location: " . DI_URL_PREFIX);die;/* 防止非浏览器模式下继续执行后续内容 */}

if (empty($_POST)):
?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>安装弹幕生成器 - LTRE LAB | larele.com | ltre.me | miku.us | ltre.cc</title>
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
    
    </head>
    <body>
        <!-- jQuery (necessary for Flat UI's JavaScript plugins) -->
        <script src="res/lib/flat-ui/js/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="res/lib/flat-ui/js/flat-ui.min.js"></script>
        
        <div class="container">
            <div class="row">
                <div class="rd1 bg-primary col-xs-6 col-xs-offset-3" style="text-align: center;margin-top: 20px;margin-bottom: 10px;">
                    安装弹幕生成器
                </div>
            </div>
            <div class="row">
                <form id="install-panel" class="rd3 bg-primary col-xs-6 col-xs-offset-3 horizontal" style="display: block;font-size: 18px;line-height: 18px;">
                    <!-- 
                    <div class="col-xs-4" style="height: 22px;line-height: 22px;text-align: right;">字段：</div>
                    <div class="col-xs-8" style="height: 22px;"><input type="text" class="form-control"></div>
                    -->
                    <div class="form-group">
                        <label for="host">数据库地址</label>
                        <input type="text" class="form-control form_field_class" id="host" placeholder="默认127.0.0.1" value="127.0.0.1">
                    </div>
                    <div class="form-group">
                        <label for="port">端口号</label>
                        <input type="text" class="form-control form_field_class" id="port" placeholder="默认3306" value="3306">
                    </div>
                    <div class="form-group">
                        <label for="db">数据库名</label>
                        <input type="text" class="form-control form_field_class" id="db" placeholder="指定新数据库名" value="superdanmu">
                    </div>
                    <div class="form-group">
                        <label for="user">数据用户名</label>
                        <input type="text" class="form-control form_field_class" id="user" value="root">
                    </div>
                    <div class="form-group">
                        <label for="pwd">数据库密码</label>
                        <input type="password" class="form-control form_field_class" id="pwd" value="">
                    </div>
                    <div class="form-group">
                        <label for="rooter">创始人账号</label>
                        <input type="text" class="form-control form_field_class" id="rooter" value="rooter">
                    </div>
                    <div class="form-group">
                        <label for="rooter_pwd">创始人密码</label>
                        <input type="password" class="form-control form_field_class" id="rooter_pwd" value="">
                    </div>
                	<button type="submit" class="btn btn-default pull-right" style="margin-bottom: 15px;">下一步</button>
                	<button id="startapp" type="button" class="hide btn btn-default pull-right" style="margin-bottom: 15px;" onclick="location.href=urlpath()">开始体验</button>
                    <button id="reinstall" type="button" class="hide btn btn-default pull-right" style="margin-bottom: 15px;" onclick="location.href=urlpath()+'?repair.install';">重新安装</button>
                </form>
            </div>
        </div>
        
        <!-- 脚本加载顺序：基础库=>公共业务库=>特定业务 -->
        <script src="res/lib/lib.js"></script>
        <script type="text/javascript">
        $('body').css({
            'font-family' : '微软雅黑',
            'background-color' : '#e2e2e2'
        });
        function gentip(msg){
        	$('.form_field_class').closest('div').css('display', 'none');
        	$('form').first().append($('<div class="form-group col-xs-6 col-xs-offset-3">' + msg + '！</div>'));
        }
        $('form').submit(function(){
			var j = collval('form_field_class');
	        var url = urlpath() + '?repair.install';
	        var msg = '';
	        $.post(url, j, function(json){
	        	console.log(json);
	        	if (1 == json.code) {
	        		msg += json.msg;
	        		if (2 == json.data.step) {
	        			$(':submit').css('display', 'none');
	        			$('#startapp').removeClass('hide');
	        		} else if (1 == json.data.step && true == json.data.schema_exists) {
	        			$('form').first().append($('<div class="hide form-group"><input type="text" class="form_field_class" id="confirm_select_db" value="1"></div>'));
	        			msg += '  如果进行下一步，将会覆盖名为' + json.data.schema + '的数据库，确定吗？';
	        		}
	        		console.log('消息：'+msg);
	        		gentip(msg);
	        	} else {
	        		$(':submit').css('display', 'none');
	        		$('#reinstall').removeClass('hide');
	        		gentip(json.msg);
	        	}
	        }, 'json');
	        return false;
        });
        </script>
    </body>
    
</html><?php die;endif;

//安装要分步骤：
//第一步：根据用户配置的参数将配置文件写好
//第二步：执行SQL导入脚本，安装，设置管理员


if (in_array(DI_URL_PREFIX, array(
    'http://danmu.me/',
    'http://danmu.me',
))) die('保护正在开发的源码(:p)');

$exceptions = array();
set_error_handler(function($error, $message, $file, $line) use (&$exceptions){
	$debug_backtrace = debug_backtrace();
	$exceptions = compact('error', 'message', 'file', 'line', 'debug_backtrace');
});

function runquery($sql, $h, &$querydebug=array()) {
	$rs = array();
    $sql = str_replace("\r", "\n", $sql);
    foreach (explode(";\n", trim($sql)) as $query) {
        $query = trim($query);
        if($query) {
            $rs[] = mysqli_query($h, $query);
            $querydebug[] = $query;
        }
    }
    return $rs;
}

//注意保护开发中的配置文件conf.xml,database.php,define.php
$f_database = DI_CONFIG_PATH . 'database.php';
$f_define = DI_CONFIG_PATH . 'define.php';
$f_conf = 'res/biz/danmu/player/conf.xml';
$host = !arg('host')?'127.0.0.1':arg('host');//仅指数据库host
$port = !arg('port')?3306:arg('port');
$db = !arg('db')?'superdanmu':arg('db');
$user = arg('user');
$pwd = arg('pwd');


//步骤判断
$stepfile = DM_INSTALL_STEP_FILE;
if (!file_exists($stepfile)) file_put_contents($stepfile, '0');
$step = file_get_contents($stepfile);
if (0 == $step) goto STEP1;
if (1 == $step) goto STEP2;
die;


//第一步
STEP1:
file_put_contents($stepfile, '1');


$c_database = "<?php\n
    class DIDBConfig {
        static \$driver = 'DIMySQL';
        static \$host = '$host';
        static \$port = '$port';
        static \$db = '$db';
        static \$user = '$user';
        static \$pwd = '$pwd';
        static \$table_prefix = 'dm_';
    }
";
$c_define = "<?php\n
    \$hostname = substr( (\$h = \$_SERVER['HTTP_HOST']), 0, (false !== (\$pos = strpos(\$h, ':')) ? \$pos : strlen(\$h)) );
    switch (\$hostname) {
        case '127.0.0.1':
        case 'localhost':
            break;
        case 'bbs.sise.com.cn':
    	    define('DI_DEBUG_MODE', false);
    	    define('DI_IO_RWFUNC_ENABLE', true);
    	    break;
        default:
            define('DI_DEBUG_MODE', true);
            define('DI_IO_RWFUNC_ENABLE', true);
    }
    define('DI_SMARTY_DEFAULT', false);
";
$c_conf = file_get_contents($f_conf);

file_put_contents($f_database, $c_database);
file_put_contents($f_define, $c_define);
file_put_contents($f_conf, str_replace('http://danmu.me/', DI_URL_PREFIX, $c_conf));//这里写死
putjson(1, array('step' => 1), '配置文件写入完成');


STEP2:
file_put_contents($stepfile, '2');

//后面是第二步
$e1 = serialize($exceptions);
$h = mysqli_connect($host.':'.$port, $user, $pwd);
$e2 = serialize($exceptions);
if ($e1 != $e2) {
	file_put_contents($stepfile, '0');//准备重新安装
	putjson(0, array_merge($exceptions, array('step'=>2, 'schema_exists'=>false)), '数据库连接错误');
}

$rs = mysqli_query($h, 'SHOW SCHEMAS');
$schema_exists = false;
while (@$r = mysqli_fetch_object($rs)) {
	$db == $r->Database && $schema_exists = true;
}

//询问是否选定该数据库
if ($schema_exists && 1!=arg('confirm_select_db')) {
	file_put_contents($stepfile, '1');//状态置为“第一步完成”
	putjson(1, array('step'=>1, 'schema_exists'=>true, 'schema'=>$db));
}

$sql = file_get_contents(DI_LET_PATH . 'repair/install.sql');
$create_stmt = $schema_exists ? '' : "CREATE SCHEMA `{$db}`;\n";
$sql = "{$create_stmt}USE `{$db}`;\n{$sql}";
$rooter = arg('rooter'); $rooter_pwd = sha1(arg('rooter_pwd'));
$sql .= "\nINSERT INTO dm_user(`passport`, `nickname`, `password`, `regtime`) VALUES('{$rooter}', 'Rooter', '{$rooter_pwd}', ".time().");";
$sql .= "\nINSERT INTO dm_rooter(`passport`) VALUES('{$rooter}');";
$rs = runquery($sql, $h, $querydebug);

$flag = true;
$querydebugmsg = array();
foreach ($rs as $r) {
	$r || $flag = false;
}

$code = $flag ? 1 : 0;
if ($flag) {
	putjson(1, array('rs'=>$rs,'step'=>2), '安装成功');
} else {
	file_put_contents($stepfile, '0');//准备重新安装
	putjson(0, array('rs'=>$rs,'step'=>2, 'querydebug'=>$querydebug), '安装出错');
}