<?php

import('net/dwHttp');

class TokenSetup {
    var $map = [];
    var $pointer = null;
    var $invalidPointers = [];//无效的token指针存放
}


class ttp {

    var $tokenFile;

    function __construct(){
        $this->tokenFile = DI_DATA_PATH . 'cache/botTokens.setup';
    }


    function save(TokenSetup $setup) {
        file_put_contents($this->tokenFile, serialize($setup));
    }


    /**
     * @return TokenSetup
     */
    function getSetup(){
        if (! file_exists($this->tokenFile)) {
            return new TokenSetup();
        } else {
            $s = file_get_contents($this->tokenFile);
            return unserialize($s);
        }
    }


    //1正常
    function setToken($token, $status = 1){
        $setup = $this->getSetup();
        $map = &$setup->map;
        $check = true;
        foreach ($map as $id => $v) {
            if ($v['token'] == $token) {
                $map[$id]['status'] = $status;
                $check = false;
            }
        }
        if ($check) {
            $map[sha1(microtime(1).rand(0, 1000))] = [
                'token' => $token,
                'status' => $status,
            ];
        }
        $this->save($setup);
    }


    function delToken($id){
        $setup = $this->getSetup();
        $map = &$setup->map;
        if (key_exists($id, $map)) {
            unset($map[$id]);
        }
        $this->save($setup);
    }


    function getNext(){
        $setup = $this->getSetup();
        $keys = array_keys($setup->map);
        if (empty($setup->pointer)) {
            $setup->pointer = reset($keys);
        } else {
            $i = array_search($setup->pointer, $keys);
            if ($i === false || $i === null) {
                throw new Exception('current pointer is not found:'.$setup->pointer);
            }
            $nextI = $i + 1 == count($keys) ? 0 : ($i + 1);
            $setup->pointer = $keys[$nextI];
        }
        $this->save($setup);
        $next = $setup->map[$setup->pointer];
        if ($next['status'] == 1) return $next;
        if (count($setup->invalidPointers) == count($keys)) return null;
        if (! in_array($setup->pointer, $setup->invalidPointers)) {
            $setup->invalidPointers[] = $setup->pointer;
        }
        return $this->getNext();
    }


    function getNextToken(){
        $next = $this->getNext();
        if (null === $next) {
            throw new Exception('Next token can not be found');
        }
        return $next['token'];
    }


    function getNextPhone($start = 18300000050, $end = 18399999950, $step = 100){
        // $phoneFile = DI_DATA_PATH.'cache/trytgphones.json';
        // @$phoneList = json_decode(file_get_contents($phoneFile)?:'[]', 1);
        $r = rand(0, ceil(($end - $start) / $step));
        $phone = '+86' . ($start + $step * $r);
        return $phone;
    }


    function req($phone){
        $lockFile = DI_DATA_PATH.'cache/trytgphone.lock';
        @list($lock, $lastTime) = json_decode(file_get_contents($lockFile)?:'[0, 0]', 1);
        if ($lock && time() - $lastTime < 15) die('req locked!');//锁11秒
        file_put_contents($lockFile, json_encode([1, time()]));

        $token = $this->getNextToken();
        $api = "https://api.telegram.org/bot{$token}/sendContact";
        $h = new dwHttp;
        $ret = $h->post($api, [
            'chat_id' => 533702151,
            'phone_number' => $phone,
            'first_name' => 'hehe',
        ]);
        if (false === $ret) {
            $this->alert($phone, print_r(['msg' => '请求出错', 'data' => compact('token', 'phone', 'ret')], 1));
        } else {
            $response = json_decode($ret, 1);
            if (false === $response) {
                $this->alert($phone, print_r(['msg' => '响应结构不是JSON', 'data' => compact('token', 'phone', 'ret')], 1));
            } else {
                if (! $response['ok']) {
                    $this->alert($phone, print_r(['msg' => '请求并不OK', 'data' => compact('token', 'phone', 'response')], 1));
                } else {
                    if (isset($response['result']['contact']['user_id'])) {
                        $user_id = $response['result']['contact']['user_id'];
                        $this->alert($phone, print_r(['msg' => "已找到用户ID: {$user_id}", 'data' => compact('token', 'phone', 'response')], 1));
                    } else {
                        $this->alert($phone, print_r(['msg' => "未找到用户ID", 'data' => compact('token', 'phone', 'response')], 1));
                    }
                }
            }
        }

        file_put_contents($lockFile, json_encode([1, time()]));//暂时不立即解锁
        @dump(compact('token', 'phone', 'ret', 'response'));
    }


    function alert($phone, $msg){
        $token = $this->getNextToken();
        $api = "https://api.telegram.org/bot{$token}/sendMessage";
        $h = new dwHttp;
        $ret = $h->post($api, [
            'chat_id' => 533702151,
            'text' => $msg,
        ]);
        file_put_contents(DI_DATA_PATH.'cache/trytgphones.log', "{$phone}: {$msg}\r\n", FILE_APPEND);
    }


    static function test(){
        $ttp = new ttp;
        for ($i = 18300000050; $i < 18399999950; $i += 100) {
            $ttp->req("+86{$i}");
            sleep(6);
        }
    }

}

$a = arg('a');
$token = arg('token') ?: '';
$status = intval(arg('status')) ?: 1;
$id = arg('id') ?: '';
$phone = arg('phone') ?: '';
$ttp = new ttp;
switch ($a) {
    case 'setToken':
        if (empty($token)) die('wtf');
        $ttp->setToken($token, $status);
        echo 'writed';
        break;
    case 'delToken':
        if (empty($id)) die('wtf');
        $ttp->delToken($id);
        echo 'deleted';
        break;
    case 'req':
        if (empty($phone)) {
            $phone = $ttp->getNextPhone();
        }
        $ttp->req($phone);
        break;
    case 'dumpTokenSetup':
        die('nmb');//平时不给查
        dump($ttp->getSetup());
        break;
    default:
        echo 'default';
}