<?php
/**
 * 对于需要登录的业务代码进行控制
 */
class LoginFilter implements DIFilter {
    
    //目前对tu/toup, tu/up, tu/setTags, tu/del进行限制
    public function doFilter() {
        $me = User::isLogin();
        if (! $me) {
            dispatch('user/loginView');
        }
    }
    
}