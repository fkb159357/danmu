<?php
/**
 * 全局记录登录信息（不论是否登录）
 */
class GlobalLoginInfoFilter implements DIFilter {
    
    //这里不作登录态判断，如有需要，详见LoginFilter
    public function doFilter() {
        $me = User::isLogin();
        DIRuntime::addItem(DM_RUNTIME_LOGIN_INFO, $me);
    }
    
}