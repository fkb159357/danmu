<?php
/**
 * 公共调用的接口，一般提供给外域
 */
class OpenDo extends DIDo {
    
    //获取客户端ip
    function client_ip(){
        import('net/ip');
        $ip = Api_ip::get_client_ip();
        putjsonp(1, compact('ip'));
    }
    
}