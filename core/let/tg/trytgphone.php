<?php
// cd /root/tmp; nohup php trytgphone.php > trytgphone.nohup.out &
// ps aux | grep trytgphone
/**
 跑脚本：
    <?php
    $u = 'http://......./tg.trytgphone?a=req';
    while(1){
        if (time() > strtotime('2018-04-12 12:00:00')) die('done');
        echo file_get_contents($u);
        sleep(30);
    }

    @todo: 发现有问题，半小时内没发现retry_after，需要先停止脚本，检查错误。防止被封
 */

import('net/dwHttp');
import('debug/showTrace');

class TokenSetup {
    var $map = [];//{id => {token:a, status:b, unlockTime:c}}
    var $pointer = null;
    var $invalidPointers = [];//无效的token指针存放(该字段已作废，但先保留，以适应已存储的token配置文件)
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


    //status=1正常, unlock=0不强制解锁
    function setToken($token, $status = 1, $unlock = 0){
        $setup = $this->getSetup();
        $map = &$setup->map;
        $check = true;
        foreach ($map as $id => $v) {
            if ($v['token'] == $token) {
                $map[$id]['status'] = $status;
                $unlock AND $map[$id]['unlockTime'] = 0;
                $check = false;
            }
        }
        if ($check) {
            $map[sha1(microtime(1).rand(0, 1000))] = [
                'token' => $token,
                'status' => $status,
                'unlockTime' => 0,
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
        $next = &$setup->map[$setup->pointer];
        @$next['unlockTime'] = $next['unlockTime'] ?: 0;
        $next['status'] = time() > $next['unlockTime'] ? 1 : 0;
        $this->save($setup);
        if ($next['status'] == 1) return $next;
        $countInvalid = 0;
        foreach ($setup->map as $v) if ($v['status'] == 0) $countInvalid ++;
        if ($countInvalid == count($setup->map)) return null;
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


    //防止机器人被封
    function protectBot($token, $afterSeconds){
        $setup = $this->getSetup();
        foreach ($setup->map as $id => $v) {
            if ($v['token'] == $token) {
                $setup->map[$id]['status'] = 0;
                $setup->map[$id]['unlockTime'] = time() + $afterSeconds;
            }
        }
        $this->save($setup);
    }


    function req($phone){
        $lockFile = DI_DATA_PATH.'cache/trytgphone.lock';
        @list($lock, $lastTime) = json_decode(file_get_contents($lockFile)?:'[0, 0]', 1);
        if ($lock && time() - $lastTime < 15) die('req locked!');//锁15秒
        file_put_contents($lockFile, json_encode([1, time()]));

        $token = $this->getNextToken();
        $api = "https://api.telegram.org/bot{$token}/sendContact";
        $h = new dwHttp;
        $ret = $h->post($api, [
            'chat_id' => 533702151,//常用收信用户
            'phone_number' => $phone,
            'first_name' => 'hehe',
        ]);
        //debug
        // $ret = json_encode([
        //     'ok' => 1,
        //     'error_code' => 429,
        //     'parameters' => [
        //         'retry_afer' => 1200,
        //     ]
        // ]);
        if (false === $ret) {
            $this->alert($phone, print_r(['msg' => '请求出错', 'data' => compact('token', 'phone', 'ret')], 1));
        } else {
            $response = json_decode($ret, 1);
            if (false === $response) {
                $this->alert($phone, print_r(['msg' => '响应结构不是JSON', 'data' => compact('token', 'phone', 'ret')], 1));
            } else {
                if (! $response['ok']) {
                    if ($response['error_code'] == 429) {
                        //@todo: 这里需要进一步获取下一次可请求的时间，记录到配置文件中，以便自动解锁
                        $afterSeconds = $response['parameters']['retry_after'];
                        $this->protectBot($token, $afterSeconds);
                        $this->alert($phone, print_r(['msg' => '请求过于频繁，暂时将该token暂停', 'data' => compact('token', 'phone', 'response')], 1));
                    } else {
                        $this->alert($phone, print_r(['msg' => '请求并不OK', 'data' => compact('token', 'phone', 'response')], 1));
                    }
                } else {
                    if (isset($response['result']['contact']['user_id'])) {
                        $user_id = $response['result']['contact']['user_id'];
                        $this->alert($phone, print_r(['msg' => "已找到用户ID: {$user_id}", 'data' => compact('token', 'phone', 'response')], 1));
                        if (in_array($user_id, [515656720, 524008226, 476290631])) { //如果是狗屎骗子的号，则特殊通知
                            $this->alert($phone, print_r(['msg' => "已找到用户ID: {$user_id}", 'data' => compact('token', 'phone', 'response')], 1), 462394947);
                        }
                    } else {
                        $this->alert($phone, print_r(['msg' => "未找到用户ID", 'data' => compact('token', 'phone', 'response')], 1));
                    }
                }
            }
        }

        file_put_contents($lockFile, json_encode([1, time()]));//暂时不立即解锁
        @dump(compact('token', 'phone', 'ret', 'response'));
    }


    function alert($phone, $msg, $chat_id = 533702151){
        $token = $this->getNextToken();
        $api = "https://api.telegram.org/bot{$token}/sendMessage";
        $h = new dwHttp;
        $ret = $h->post($api, [
            'chat_id' => $chat_id,
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
$unlock = intval(arg('unlock')) ?: 0;//1-默认不解锁，1-强制解锁
$id = arg('id') ?: '';
$phone = arg('phone') ?: '';
$ttp = new ttp;
switch ($a) {
    case 'setToken':
        if (empty($token)) die('wtf');
        $ttp->setToken($token, $status, $unlock);
        echo 'writed';
        break;
    case 'delToken':
        if (empty($id)) die('wtf');
        $ttp->delToken($id);
        echo 'deleted';
        break;
    case 'req':
        if (empty($phone)) {
            $ps = [
                ['15800000086', '15899999986', 100],
                ['18300000050', '18399999950', 100],
            ];
            list($start, $end, $step) = $ps[rand(0, 1)];
            $phone = $ttp->getNextPhone($start, $end, $step);
        }
        $ttp->req($phone);
        break;
    case 'dumpTokenSetup':
        // die('nmb');//平时不给查
        dump($ttp->getSetup());
        break;
    default:
        echo 'default';
}

