<?php

/**
 * 名为“Install”的过滤器，其作用域详见filtermap.php的配置
 */
class InstallFilter implements DIFilter {
    
    function in_ignore(){
        $ignore = array(
            'danmu.me',
            'fm.danmu.me',
            'tu.danmu.me',
            '192.168.1.200',
            '172.16.43.111',
            '180.76.128.66',
            'danmu.webdev.duowan.com',
            'danmu.sinaapp.com',
            'miku.us',
            'iio.ooo',
            'yooo.moe',
            'larele.com',
            'ltre.cc',
            'ltre.me',
            'emiku.cc',
            'xmiku.cc',
            'www.miku.us',
            'tu.miku.us',
            'fm.miku.us',
            'www.iio.ooo',
            'www.yooo.moe',
            'www.larele.com',
            'www.ltre.cc',
            'www.ltre.me',
            'www.emiku.cc',
            'www.xmiku.cc',
        );
        if (DIRuntime::hasIndex('install_ignore')) {
            DIRuntime::updItem('install_ignore', $ignore);
        } else {
            DIRuntime::addItem('install_ignore', $ignore);
        }
        return in_array(host_name(), $ignore);
    }
    
    function doFilter(){
        //指定开发环境和线上环境不进行安装操作
        if ($this->in_ignore()) return;
        
        $f = DM_INSTALL_STEP_FILE;
        if (!file_exists($f) || 2 != file_get_contents($f)) {
            header("Location: " . DI_URL_PREFIX . "?repair.install");
            die;
        }
    }
    
}