<?php
class OauthQqDo extends DIDo {
    
    function login(){
        $appId = '101297702';
        $appKey = '775dd5e72f5cacbc684544ebaf5eb5ed';
        $callback = "http://{$_SERVER['HTTP_HOST']}/oauthqq/login";
        
        //Step1：获取Authorization Code
        $code = arg('code');
        if (empty($code)) {
            session('state', sha1(uniqid(rand(), true)));
            $dialogUrl = "https://graph.qq.com/oauth2.0/authorize?".http_build_query(array(
            	'response_type' => 'code',
                'client_id' => $appId,
                'redirect_uri' => $callback,
                'state' => session('state'),
            ));
            echo("<script> top.location.href='{$dialogUrl}'</script>");
        }
        //Step2：通过Authorization Code获取Access Token
        if (arg('state') == session('state')) {
            $tokenUrl = "https://graph.qq.com/oauth2.0/token?".http_build_query(array(
            	'grant_type' => 'authorization_code',
                'client_id' => $appId,
                'redirect_uri' => $callback,
                'client_secret' => $appKey,
                'code' => $code,
            ));
            import('net/dwHttp');
            $http = new dwHttp();
            $ret = $http->get($tokenUrl);
            if (strpos($ret, "callback") !== false) {
                $lpos = strpos($ret, "(");
                $rpos = strrpos($ret, ")");
                $ret  = substr($ret, $lpos + 1, $rpos - $lpos -1);
                $msg = json_decode($ret);
                if (isset($msg->error)) {
                    echo "<h3>error:</h3>" . $msg->error;
                    echo "<h3>msg  :</h3>" . $msg->error_description;
                    exit;
                }
            }
            //Step3：使用Access Token来获取用户的OpenID
            $params = array();
            parse_str($ret, $params);
            $graph_url = "https://graph.qq.com/oauth2.0/me?access_token={$params['access_token']}";
            $str  = file_get_contents($graph_url);
            if (strpos($str, "callback") !== false) {
                $lpos = strpos($str, "(");
                $rpos = strrpos($str, ")");
                $str  = substr($str, $lpos + 1, $rpos - $lpos -1);
            }
            $user = json_decode($str);
            if (isset($user->error)) {
                echo "<h3>error:</h3>" . $user->error;
                echo "<h3>msg  :</h3>" . $user->error_description;
                exit;
            }
            echo("Hello " . $user->openid);
            //保存access token和openID
            session('oauth', array($params['access_token'], $user->openid));
        }
    }
    
    function getUserInfo(){
        $appId = '101297702';
        $oauth = session('oauth');
        @$accessToken = $oauth[0];
        @$openId = $oauth[1];
        $url = 'https://graph.qq.com/user/get_user_info?'.http_build_query(array(
        	'access_token' => $accessToken,
            'oauth_consumer_key' => $appId,
            'openid' => $openId,
        ));
        import('net/dwHttp');
        $http = new dwHttp();
        $ret = $http->get($url);
        session('qqUserInfo', $ret);
    }
    
}