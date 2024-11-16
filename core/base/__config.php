<?php

/* config目录相关，一般用于配置规则的解析和匹配，要注意规则内是否启用了严格模式 */
//废弃？
/**
 * 2024答：并没有废弃。
 * 因config目录中，想要使用__lib.php中定义的函数，但__lib.php加载时机 比 "config/*" 晚。
 * 而__config.php 比 __lib.php 迟加载，所以可以利用此处，写入一些更加灵活、更偏向业务层的配置。
 */
class DIConfig {
	
}



//redis --begin
define('REDIS_SOCKET', '/tmp/redis.sock');//优先使用unixsocket
define('REDIS_HOST', '127.0.0.1');
define('REDIS_PORT', '6379');
define('REDIS_AUTH', ltreDeCrypt('jaHCZX8521*~.8o0PFQEzbNh.K_zPxa0'));
//redis --end
