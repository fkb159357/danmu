<?php
/**
 * 对于[单纯]需要登录的业务代码进行控制
 * 登录态从GlobalLoginInfoFilter中获取
 */
class LoginFilter implements DIFilter {
    
    //目前对posts/pub、posts/edit进行限制
    public function doFilter() {
        $me = DIRuntime::getItem(DM_RUNTIME_LOGIN_INFO);
        if (! $me) {
            dispatch('user/loginView');
        }
    }
    
}