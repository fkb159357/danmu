<?php
/**
 * 工具大全
 */
class ToolDo extends DIDo {
    
    function start(){
        echo 'this is tool/start';
    }
    
    function test(){
        echo 'this is tool/test';
    }
    
    private function _beforeCd4js($fun){
        if (! preg_match('/^https?:\/\/([\w.]*acggeek\.com|[\w.]*miku\.us|[\w.]*larele\.com|[\w.]*yooo\.moe|[\w.]*acggeek.dev|[\w.]*danmu.me|[\w.]*larele.me)\//', @$_SERVER['HTTP_REFERER']?:'')) {
            putjsonp(-1);//非法请求 -1
        }
        $now = time();
        $ckKey = sha1($fun);
        if (! isset($_COOKIE[$ckKey])) {
            setCookieLive($ckKey, ltreCrypt($now));
            putjsonp(1);
        }
        $ckOldTime = (int) ltreDeCrypt($_COOKIE[$ckKey]);
        if ($now - $ckOldTime > 10) {
            putjsonp(-2);//非法请求 -2
        }
        removeCookieLive($ckKey);
    }
    
    //crypt for js
    function c4js(){
        $this->_beforeCd4js(__FUNCTION__);
        putjsonp(2, ltreCrypt(arg('str')));
    }
    
    //decrypt for js
    function d4js(){
        $this->_beforeCd4js(__FUNCTION__);
        putjsonp(2, ltreDeCrypt(arg('str')));
    }

    //自用加解密工具
    function coder(){
        $this->stpl();
    }
    
    //自用O0o JS代码生成器。需要安装nodejs模块 optimist
    function O0oJS(){
        if (arg('js')) {
            $js = arg('js');
            $jsf = DI_CACHE_PATH.sha1(microtime(1)).'.js';
            file_put_contents($jsf, $js);
            $bin = ltreDeCrypt("~zta'1_)OlDbi4z6_1MzUtKAPlQA'3Mo00'lYjHF!2Het1xjIfXifd!2VxSSX9)qJH!2C9MnZrYxnbTe97'9a0Ifj9!PM7qo_7_F-N'1AmDcOlN2VgGE~1-ERy(pmk'9)Dm8*CZkpn!2z7u0.0OkxdHpJn'1Vtk6SrVgYW~1LiVxGix6PjVpZtXizx_7xjEi~Dn6@lAy.8");
            $jscode = trim(shell_exec('node '.$bin.' --codefile='.$jsf));
            putjsonp(0, ['code' => 'node '.$bin.' --codefile='.$jsf]);
            $code = '<script src="//res.miku.us/res/js/O0o-runtime2.prod.js?v='.date('YmdHis').'"></script><script>'.$jscode.'</script>';
            @unlink($jsf);
            putjsonp(0, compact('code'));
        } else {
            $this->stpl();
        }
    }

}


/**
    <script src="https://cdn.jsdelivr.net/jquery/1.9.1/jquery-1.9.1.min.js"></script>
    <script>
    var url = 'http://tu.danmu.me/tool/c4js';
    $.get(url, {str:'测试内容'}, function(j){
        if (j.code == 1) {
            $.get(url, {str:'测试内容'}, function(j){
                if (j.code == 2) {
                    console.log(j);
                }
            }, 'jsonp');
        }
    }, 'jsonp');
    </script>
 */



function setCookieLive($name, $value='', $expire = 0, $path = '', $domain='', $secure=false, $httponly=false)
{
    $_COOKIE[$name] = $value;
    return setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
}

function removeCookieLive($name)
{
    unset($_COOKIE[$name]);
    return setcookie($name, NULL, -1);
}

