<?php
/**
 * 主要提供给res/biz/common/root_common.js调用的接口
 * 一般是提供给ROOTER端多个页面共用的接口
 */
class RootCommonDo extends DIDo {
    
    //接受res/biz/common/root_common.js::RootCommon.report_ip()上报的IP信息
    function report_ip(){
        $j = (arg('j'));//$.get/post(url, json)方式传入的json在这里会自动转换为array
        if (!is_array($j)) {
            putjson(0, compact('j'), '参数格式错误');// --END
        }
        $ip = arg('client_ip');
        $ret = Rootist::report_ip($j, $ip);
        $is_exception = $ret['is_exception'];
        if (-1 == $ret['code']) {
            putjson(0, compact('ip', 'is_exception'), '获取不到正确IP');
        } elseif (-2 == $ret['code']) {
            putjson(1, compact('ip', 'is_exception'), 'IP上报过于频繁');
        } elseif (-3 == $ret['code']) {
            putjson(0, $ret['data'], 'IP上报失败');
        } elseif (0 == $ret['code']) {
            putjson(1, $ret['data'], 'IP上报成功');
        }
    }
    
}