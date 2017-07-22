<?php
/**
 * BCE API使用
 */

import('net/netUtils');

class BceDo extends DIDo {

    private $authFile;

    private $appId;
    
    private $apiKey;

    private $secretKey;

    function _init(){
        $this->authFile = DI_DATA_PATH . 'bce.auth';
        if (strtolower(DI_DO_FUNC) != 'setauth') {
            $this->auth();
        }
    }


    //识别色情gif
    public function gifSexy(){
        
    }


    //语言处理基础技术 - 情感倾向分析（1000次/天免费）
    public function sentimentClassify(){
        $s = arg('s', '');
        import('bce/aipNlp/AipNlp');
        $aipNlp = new AipNlp($this->appId, $this->apiKey, $this->secretKey);
        $ret = $aipNlp->sentimentClassify($s);
        putjson(0, $ret);
    }


    //手动写入API验证信息
    public function setAuth(){
        if (isPost()) {
            $arg = arg('arg');
            if (is_array($arg) && count($arg) == 3) {
                $check = true;
                foreach ($arg as $v) {
                    if (! is_string($v)) {
                        $check = false;
                        break;
                    }
                }
                if ($check) {
                    file_put_contents($this->authFile, json_encode($arg));
                }
            }
        } else {
            $h = '
                <form action="/bce/setAuth" method="post">
                    <p>AppID: <input name="arg[]"></p>
                    <p>API Key: <input name="arg[]"></p>
                    <p>Secret Key: <input name="arg[]"></p>
                    <p><input type="submit"></p>
                </form>
            ';
            die($h);
        }
    }


    private function auth(){
        $j = file_exists($this->authFile) ? file_get_contents($this->authFile) : '[]';
        $infArr = json_decode($j, 1);
        if (empty($infArr)) {
            putjson(
                -1, null, 
                'require auth! <a href="/bce/setAuth" target="_blank">todo</a>'
            );
        }
        list ($this->appId, $this->apiKey, $this->secretKey) = array_slice($infArr, 0, 3);
    }




}