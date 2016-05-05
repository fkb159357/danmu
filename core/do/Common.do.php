<?php
/**
 * 主要提供给res/biz/common/common.js调用的接口
 * 一般是提供给客户端多个页面共用的接口
 */
class CommonDo extends DIDo {
    
    //接受res/biz/common/common.js::Common.report_ip()上报的IP信息
    function report_ip(){
        $j = (arg('j'));//$.get/post(url, json)方式传入的json在这里会自动转换为array
        if (!is_array($j)) {
            putjson(0, compact('j'), '参数格式错误');// --END
        }
        $ip = arg('client_ip');
        $ret = Tourist::report_ip($j, $ip);
        if (-1 == $ret['code']) {
            putjson(0, compact('ip'), '获取不到正确IP');
        } elseif (-2 == $ret['code']) {
            putjson(1, $ret['data'], '今天已上报过');
        } elseif (0 == $ret['code']) {
            putjson(1, $ret['data'], '上报成功');
        }
    }
    
}