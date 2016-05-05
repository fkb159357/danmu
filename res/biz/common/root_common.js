/**
 * 只存放后台管理页面多个场景需要调用的过程
 * 注意：这里不存放js库，也不能涉及页面中非共用的UI空间
 * 注意：这里只存放后台管理的公共代码
 */
RootCommon = {};

/**
 * 上报IP信息
 * @since 2015-1-2
 * @author Ltre
 * @param string ip 不传值则默认使用新浪的IP识别
 */
RootCommon.report_ip = function(ip){
    // 响应：var remote_ip_info = {"ret":1,"start":-1,"end":-1,"country":"\u4e2d\u56fd","province":"\u6e56\u5357","city":"\u90b5\u9633","district":"","isp":"","type":"","desc":""}
    var url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js' + ((undefined != ip) ? ('&ip=' + ip) : '');
    $.get(url, function(){
        if (!is_json(remote_ip_info)) {
            return false;
        }
        var j = remote_ip_info;

        if (-1 == j.ret) {
            j.type = 'inner netword';
            j.desc = '内网地址';
        }
        var tip = 'ret      = ' + j.ret     + '\r\n'
                + 'start    = ' + j.end     + '\r\n'
                + 'end      = ' + j.end     + '\r\n'
                + 'country  = ' + j.country + '\r\n'
                + 'province = ' + j.province+ '\r\n'
                + 'city     = ' + j.city    + '\r\n'
                + 'district = ' + j.district+ '\r\n'
                + 'isp      = ' + j.isp     + '\r\n'
                + 'type     = ' + j.type    + '\r\n'
                + 'desc     = ' + j.desc    + '\r\n';
        console.log(tip);
        $.post('./?rootcommon/report_ip', {'j':j, 'client_ip':ip}, function(jn){
            console.log(jn);//测试上报返回
        }, 'json');
    }, 'script');
    
};