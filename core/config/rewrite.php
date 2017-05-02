<?php
class DIRouteRewrite {
    
    /**
     * 自定义路由重写规则
     * 书写原则，特殊在前，通用在后
     * 详见：
     *      DIRoute::rewrite() @ __route.php
     */
    static $rulesMap = array(
        '://fm.danmu.me' => 'fm/start', //二级域名要优先于顶级域名
        '://fm.danmu.me/<X>' => 'fm/<X>',
        '://tu.danmu.me' => 'tu/start',
        '://tu.danmu.me/get/<nums>' => 'tu/get/<nums>',
        '://tu.danmu.me/last' => 'tu/getlist/1/1/1',
        '://tu.danmu.me/<X>' => 'tu/<X>',
        '://tool.danmu.me' => 'tool/start',
        '://tool.danmu.me/<X>' => 'tool/<X>',
        '://fm.miku.us' => 'fm/start',
        '://fm.miku.us/<X>' => 'fm/<X>',
        '://tu.miku.us' => 'tu/start',
        '://tu.miku.us/get/<nums>' => 'tu/get/<nums>',
        '://tu.miku.us/last' => 'tu/getlist/1/1/1',
        '://tu.miku.us/<X>' => 'tu/<X>',
        '://tool.miku.us' => 'tool/start',
        '://tool.miku.us/<X>' => 'tool/<X>',
        '://danmu.me' => 'danmu/start',//注意：这几行对域名的配置会将DIUrlShell::$_default_shell覆盖
        '://larele.com' => 'shell.shell',
        '://www.yooo.moe' => 'fm/start',
        '://yooo.moe' => 'fm/start',
        
        'robots.txt' => 'seo/robots',
        'sitemap.xml' => 'seo/sitemap',
        
        'fm' => 'fm/start',
        'tu' => 'tu/start',
        'about' => 'about',
        's' => 'shell.shell',
        's.html' => 'shell.shell',
        'av<nums>' => 'danmu/play/<nums>',
        
        '<D>' => '<D>/start',
    	
        '<D>/<F>' => '<D>/<F>',
        '<D>/<F>/<G>' => '<D>/<F>/<G>',
        '<D>/<F>/<G>/<H>' => '<D>/<F>/<G>/<H>',
        '<D>-<F>' => '<D>/<F>',
        '<D>-<F>-<G>' => '<D>/<F>/<G>',
        '<D>-<F>-<G>-<H>' => '<D>/<F>/<G>/<H>',
        '<A>.<B>' => '<A>.<B>',
        '<A>.<B>.<C>' => '<A>.<B>.<C>',
    );
    
    /**
     * 不需要重写的
     * 左侧为相对于脚本目录的URI
     * 右侧表示重写失败时是否终止程序
     * 这些规则不受常量DI_KILL_ON_FAIL_REWRITE影响
     */
    static $withoutMap = array(
        'index.php' => false,
        'index.html' => false,
        'index.htm' => false,
        'favicon.ico' => true,
    );
    
}