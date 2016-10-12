<?php
/**
 * 配置数据库基本信息
 * 【！！强烈建议！！】
 * 不要将本文件中的外网数据库信息提交
 */

$hostname = substr( ($h = $_SERVER['HTTP_HOST']), 0, (false !== ($pos = strpos($h, ':')) ? $pos : strlen($h)) );
if (in_array($hostname, array(
    '127.0.0.1',
    '192.168.3.5',
    'localhost',
    'danmu.me',//绑定了danmu目录，和127.0.0.1
    'fm.danmu.me',//绑定了danmu目录，和127.0.0.1,线上使用iio.ooo, yooo.moe
    'tu.danmu.me',//绑定了danmu目录，和127.0.0.1
    'tool.danmu.me',//绑定了danmu目录，和127.0.0.1
))){
	
    class DIDBConfig {
        static $driver = 'DIMySQL';//驱动类
        static $host = '127.0.0.1';
        static $port = 3306;
        static $db = 'danmu';
        static $user = 'root';
        static $pwd = 'ltre';
        static $table_prefix = 'dm_';//表前缀
    }
    class DIMMCConfig {
        static $domain = 'danmu';
        static $host = '127.0.0.1';
        static $port = 11211;
    }
    
} elseif (in_array($hostname, array(
	'danmu.webdev.duowan.com',
    '172.16.42.222',
))) {
    
    class DIDBConfig {
        static $driver = 'DIMySQL';//驱动类
        static $host = '172.26.42.222';
        static $port = 3306;
        static $db = 'danmu';
        static $user = 'danmu';
        static $pwd = 'danmu';
        static $table_prefix = 'dm_';//表前缀
    }
    class DIMMCConfig {
        static $domain = 'danmu';
        static $host;
        static $port;
    }
    
} elseif (in_array($hostname, array(
	'danmu.sinaapp.com'
))) {
    
    class DIDBConfig {
        static $driver = 'DIMySQL';//驱动类
        static $host = SAE_MYSQL_HOST_M;
        static $port = SAE_MYSQL_PORT;
        static $db = SAE_MYSQL_DB;
        static $user = SAE_MYSQL_USER;
        static $pwd = SAE_MYSQL_PASS;
        static $table_prefix = 'dm_';//表前缀
    }
    class DIMMCConfig {
        static $domain = 'danmu';
        static $host;
        static $port;
    }
    
} else if (in_array($hostname, array(
    'miku.us', // 2015-2-2从heng_host转移到Hostker(danmu)
    'www.miku.us',
    'tu.miku.us',
    'fm.miku.us',
    'tool.miku.us',
    'iio.ooo', //2015-3-10在Hostker注册,运行于Hostker(danmu)
    'www.iio.ooo',
    'yooo.moe',
    'www.yooo.moe',
))) {
    
    /* class DIDBConfig {
        static $driver = 'DIMySQL';//驱动类
        static $host = 'localhost';
        static $port = 3306;
        static $db = MYSQL_DATABASE;
        static $user = MYSQL_USERNAME;
        static $pwd = MYSQL_PASSWORD;
        static $table_prefix = 'dm_';//表前缀
    }
    class DIMMCConfig {
        static $domain = 'danmu';
        static $host = 'khaki.hostker.net';
        static $port = '31511';
    } */
    class DIDBConfig {
        static $driver = 'DIMySQL';//驱动类
        static $host = '127.0.0.1';
        static $port = 3306;
        static $db = 'danmu';
        static $user = 'danmu';
        static $pwd = '356a192b7913b04c54574d18c28d46e6395428ab';
        static $table_prefix = 'dm_';//表前缀
    }
    class DIMMCConfig {
        static $domain = 'danmu';
        static $host = '127.0.0.1';
        static $port = '11211';
    }

} else {
    
    die;//环境不明确，终止
    
}