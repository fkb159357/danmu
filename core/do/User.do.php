<?php
class UserDo extends DIDo {

    
    //登录视图
    function loginView(){
        $defaultShell = DIRuntime::getItem('shell');//这里的默认值不一定该方法自身，有可能是上级调用点使用了dispatch()后跳转到这里，实际值为所在的路由值
        $sucb = arg('sucb') ?: ('user/loginview' != strtolower($defaultShell) ? $defaultShell : '');//回调去向：当前路由且非登录视图
        if ($sucb) {
            $i = 0;
            foreach ($GLOBALS['request_args'] as $k => $v) {
                '' !== $v && 'sucb' !== strtolower($k) && 'facb' !== strtolower($k) && $sucb .= ($i?'&':'')."{$k}={$v}";
                $i ++;
            }
        }
        $this->sucb = $sucb;//供login()使用的成功回调
        $this->facb = arg('facb') ?: 'user/loginview';//供login()使用的失败回调
        $this->tpl();
    }
    
    //注册视图
    function regView(){
        $this->tpl();
    }
    
    
    /**
     * 登录接口
     * @param string arg(passport) 登录账号
     * @param string arg(password)
     * @param string arg(output) json|tpl|return 
     * @param string arg(sucb) 成功回调指令,仅在output采用tpl时有效
     * @param string arg(facb) 失败回调指令,仅在output采用tpl时有效
     */
    function login(){
        $passport = arg('passport');
        $password = arg('password');
        $output = arg('output', 'tpl');
        $sucb = arg('sucb');
        $facb = arg('facb');
        $M = DIModelUtil::supertable('User');
        $u = $M->find(compact('passport'));
        
        if (is_object($u) && sha1($password) === $u->password) {
            session(DM_SESSION_MY, $u);
            'json' == $output && putjson(0, null, '登录成功');
            'tpl' == $output && redirect(url($sucb));
            if ('return' == $output) return true;
        } else {
            'json' == $output && putjson(-1, null, '登录失败');
            'tpl' == $output && putalert('登录失败', url($facb));
            if ('return' == $output) return false;
        }
    }
    
    
    /**
     * 注册接口
     * @param string arg(passport)
     * @param string arg(password)
     * @param string arg(valicode) 验证码
     * @param string arg(nickname) option
     * http://danmu.me/?user/reg&passport=abc&password=123&nickname=%E6%93%8D%E4%BD%A0%E5%A6%88&valicode=32791
     */
    function reg(){
        $passport = arg('passport');
        $password = sha1(arg('password'));
        $nickname = arg('nickname');
        $valicode = arg('valicode');
        $regtime = time();
        if (!$passport || !$password || !$nickname || strlen($passport)>20 || strlen($password)>50 || strlen($nickname) > 20) {
            superput(-3, null, '参数错误(空OR过长)');
        }
        if (!$valicode || !session_exists(DM_SESSION_REG_VALICODE) || session(DM_SESSION_REG_VALICODE) != $valicode) {
            superput(-4, null, '验证码错误');
        }

        $M = DIModelUtil::supertable('User');
        $exi = User::exists(compact('passport'), $M);
        if ($exi) {
            superput(-1, null, '该登录名已存在');
        } else {
            $ins = $M->insert(compact('passport', 'password', 'nickname', 'regtime'));
            session_remove(DM_SESSION_REG_VALICODE);
            if (!!$ins) {
                is_numeric($ins) && $ins > 0 && is_object($u = supertable('User')->find(array('id' => $ins))) && session(DM_SESSION_MY, $u);//注册成功后自动登录
                superput(0, null, '注册成功', array('redirect' => url_prefix()));
            } else {
                superput(-2, null, '注册失败');
            }
        }
    }

    
    /**
     * 生成验证码
     */
    function genValicode(){
        $code = '';
        for ($i = 0; $i < 5; $i++) { 
            $code .= rand(0, 9); 
        }
        session(DM_SESSION_REG_VALICODE, $code);
        header("Content-type: image/PNG");
        $im = Util::genValicode($code);
        imagepng($im);
        imagedestroy($im);
    }
    
    
    //退出操作，顺便清空验证码
    function logout(){
        session_remove(DM_SESSION_MY);
        session_remove(DM_SESSION_REG_VALICODE);
    }


}