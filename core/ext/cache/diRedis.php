<?php
class diRedis extends Redis {
    /**
     * 该构造方法用于初始化与 Redis 服务器的连接。
     * 按以下顺序尝试连接方式：
     * 1. 使用传入的Unix socket文件路径和可选的认证密码。如：new diRedis('/tmp/redis.sock', null, 'fdsjfoisd')
     * 2. 使用传入的主机、端口和可选的认证密码。如：new diRedis('localhost', 6379, 'fdsjfoisd')
     * 3. 使用默认的 Unix socket 文件路径和可选的认证密码。
     * 4. 使用默认的主机、端口和可选的认证密码。
     *
     * @param string|null $arg1 Unix socket 文件路径或主机。
     * @param int|null $arg2 端口号（如果使用主机和端口）。
     * @param string|null $auth 认证密码（可选）。
     */
    public function __construct($arg1 = null, $arg2 = null, $arg3 = null) {
        try {
            if (!is_null($arg1) && is_readable($arg1)) {
                // 传入的 unixsocket 文件路径
                parent::connect($arg1);
                is_null($arg3) OR parent::auth($arg3);
            } elseif (!is_null($arg1) && !is_null($arg2)) {
                // 传入的主机、端口和可选的认证密码
                parent::connect($arg1, $arg2);
                is_null($arg3) OR parent::auth($arg3);
            } elseif (is_readable(REDIS_SOCKET)) {
                // 使用默认的 unixsocket 文件路径
                parent::connect(REDIS_SOCKET);
                is_null(REDIS_AUTH) OR parent::auth(REDIS_AUTH);
            } else {
                // 使用默认的主机、端口和可选的认证密码
                parent::connect(REDIS_HOST, REDIS_PORT);
                is_null(REDIS_AUTH) OR parent::auth(REDIS_AUTH);
            }
        } catch (RedisException $e) {
            // 处理连接异常
            error_log("Redis 连接失败: " . $e->getMessage());
            throw $e;
        }
    }


    /**
     * 环保方式创建一个实例
     * 参数传入方式同: __construct()
     * @param string|null $arg1 Unix socket 文件路径或主机。
     * @param int|null $arg2 端口号（如果使用主机和端口）。
     * @param string|null $auth 认证密码（可选）。
     * @return diRedis
     */
    static function inst($arg1 = null, $arg2 = null, $auth = null){
        $k = sha1(serialize(func_get_args()));
        static $objs = [];
        if (! isset($objs[$k])) {
            $objs[$k] = new self($k);
        }
        return $objs[$k];
    }

}