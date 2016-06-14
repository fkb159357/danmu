<?php
/**
 * 参照__env.php建议，按己所需，重新定制特性
 */
$hostname = substr( ($h = $_SERVER['HTTP_HOST']), 0, (false !== ($pos = strpos($h, ':')) ? $pos : strlen($h)) );
switch ($hostname) {
    //以下使用本地
	case '127.0.0.1':
    case '192.168.3.5':
    case '172.16.42.222':
	case 'localhost':
	case 'danmu.me'://绑定了danmu目录，和127.0.0.1
	case 'fm.danmu.me'://绑定了danmu目录，和127.0.0.1
    case 'tu.danmu.me'://绑定了danmu目录，和127.0.0.1
	case 'danmu.webdev.duowan.com': //公司内网
	    {
	        define('DI_ROUTE_REWRITE', true);
	        break;
	    }

	//以下使用SAE不可写空间
	case 'danmu.sinaapp.com':
	    {
	        define('DI_ROUTE_REWRITE', true);
	        define('DI_DEBUG_MODE', false);
	        define('DI_IO_RWFUNC_ENABLE', false);
	        break;
	    }

	//以下使用可写空间(正式环境)
	case 'doinject.duapp.com'://BAE 2G空间
	case 'larele.com'://Hostker - fkb159357.host.smartgslb.com
	case 'www.larele.com'://Hostker - fkb159357.host.smartgslb.com
	case 'www.miku.us'://Hostker - fkb159357.host.smartgslb.com
	case 'miku.us'://Hostker - fkb159357.host.smartgslb.com
    case 'tu.miku.us'://Hostker - fkb159357.host.smartgslb.com
    case 'fm.miku.us':
	case 'www.iio.ooo'://Hostker - fkb159357.host.smartgslb.com
	case 'iio.ooo'://Hostker - fkb159357.host.smartgslb.com
    case 'www.yooo.moe'://Conoha - 133.130.96.131
	case 'yooo.moe'://Conoha - 133.130.96.131
    	{
    	    define('DI_DEBUG_MODE', false);
    	    define('DI_IO_RWFUNC_ENABLE', true);
    	    define('DI_ROUTE_REWRITE', true);
    	    break;
    	}
	default:die;//环境不明确，终止执行
}

define('DI_SMARTY_DEFAULT', false);//暂时所有环境不默认采用smarty
define('DI_SMARTY_LEFT_DELIMITER', '{`');
define('DI_SMARTY_RIGHT_DELIMITER', '`}');
define('DI_SESSION_PREFIX', 'dm_');
define('DI_PAGE_400', DI_TPL_PATH . '400-jinguanyu.php');
define('DI_PAGE_403', DI_TPL_PATH . '403-jinguanyu.php');
define('DI_PAGE_404', DI_TPL_PATH . '404-jinguanyu.php');
define('DI_PAGE_503', DI_TPL_PATH . '503-jinguanyu.php');


/**
 * 统一的复杂变量配置
 */
$GLOBALS = array_merge($GLOBALS, array(
    'api' => array(
        'imgUploadHost' => 'http://up.res.miku.us/cbupl/upimg.php',
    ),
));