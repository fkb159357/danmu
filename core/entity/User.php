<?php
class User extends DIEntity {

    /**
     * 用户是否存在
     * @param array $conds
     */
    static function exists($conds, DIModel &$M = null){
    	$M || $M = DIModelUtil::supertable('User');
        $f = $M->find($conds) ?: array();
        return !!$f;
    }

    /**
     * 当前用户是否登录
     * @return object|bool 成功返回当前会话存储的user表对象
     */
    static function isLogin(){
        if (session_exists(DM_SESSION_MY) && is_array($my=session(DM_SESSION_MY))) {
            return $my;
        } else {
            return false;
        }
    }
    
}