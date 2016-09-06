<?php
/**
 * 工具大全
 */
class ToolDo extends DIDo {
    
    private function _beforeCd4js($fun){
        if (! preg_match('/^https?:\/\/([\w.]*acggeek\.com|[\w.]*miku\.us|[\w.]*larele\.com|[\w.]*yooo\.moe)\//', @$_SERVER['HTTP_REFERER']?:'')) {
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


function ltreCrypt($str){
    $table = str_split('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@~!*()-_.\'');//@为%的替代符
    $v1 = str_replace('%', '@', urlencode($str));
    $v2 = array();
    foreach (str_split($v1) as $k => $v) {
        $rawPos = array_search($v, $table);
        $offset = floor(mt_rand(0, count($table)-$rawPos-1));
        $plusPos = $rawPos + $offset;
        $v2[] = $table[$offset];
        $v2[] = $table[$plusPos];
    }
    $v2 = implode('', array_reverse($v2));
    return $v2;
}


function ltreDeCrypt($str){
    $table = str_split('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@~!*()-_.\'');
    $rawList = array();
    $offsetList = array();
    foreach (array_reverse(str_split($str)) as $k => $v) {
        $pos = array_search($v, $table);
        if (intval(($k + 1) % 2) == 1) {
            $offsetList[] = $pos;
        } else {
            $rawPos = intval($pos - $offsetList[($k + 1) / 2 - 1]);
            @$rawList[] = $table[$rawPos];
        }
    }
    $raw = str_replace('@', '%', implode('', $rawList));
    return urldecode($raw);
}
