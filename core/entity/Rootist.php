<?php
/**
 * ROOTER端用户访问记录操作
 * @author Ltre
 */
class Rootist extends DIEntity {
    
    //上报所有访问的IP信息，同IP同UID默认五分钟内最高上报5次
    static function report_ip(array $j, $ip, $limit = 5, $gap = 300){
        //用户登录会话和异常状态检测(等用户系统完善后，这里就要修改)
        $uid = session_exists('uid') ? session('uid') : 0;
        $is_exception = intval(!!!$uid);
        
        $re='((?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))(?![\\d])';// IPv4 IP Address
        $isip = preg_match("/{$re}/is", $ip);
        if (!$isip) {
            //IP有误
            return array('code' => -1, 'is_exception' => $is_exception);
        }
        
        $M = DIModelUtil::supertable('Rootist');
        
        //同一个IP上报频率限制
        $needcheck = $M->select(compact('ip'), 'vtime,uid', 'vtime DESC', intval($limit));
        empty($needcheck) && $needcheck = array();
        $now = time();
        $start = $now - intval($gap);
        $realnum = 0;//记录同UID同IP的上报次数
        foreach ($needcheck as $nc) {
            if ($start <= intval($nc->vtime) && $uid == intval($nc->uid)) {
                $realnum ++;
            }
        }
        if ($realnum >= $limit) {
            //上报过于频繁
            return array('code' => -2, 'is_exception' => $is_exception);
        }
        
        //暂不检测是否有归属地信息[country,city等等]
        @$data = array(
            'ip' => $ip,
            'vtime' => time(),
            'uid' => $uid,
            'is_exception' => $is_exception,
            'country' => $j['country'],
            'province' => $j['province'],
            'city' => $j['city'],
            'isp' => $j['isp'],
            'ip_desc' => $j['desc'],
            'ip_type' => $j['type'],
        );
        
        //上报成功(0)/失败(-3)，附带异常状态检测信息
        $opt = $M->insert($data);
        $code = false !== $opt ? 0 : -3;
        return compact('code', 'data', 'is_exception');
    }
    
}