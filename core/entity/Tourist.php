<?php
/**
 * 游客记录操作
 * @author Ltre
 */
class Tourist extends DIEntity {

    //上报当天首次访问时的IP信息
    static function report_ip(array $j, $ip){
        $re='((?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))(?![\\d])';// IPv4 IP Address
        $isip = preg_match("/{$re}/is", $ip);
        if (!$isip) {
            return array('code' => -1);//IP有误
        }
    
        $M = DIModelUtil::supertable('Tourist');
        $tourist = $M->find(compact('ip')) ?: array();
        $vtime = empty($tourist) ? false : $tourist['vtime'];
    
        if ($vtime >= strtotime(date('Y-m-d'))) {
            //今天已上报过
            return array('code' => -2, 'data' => compact('ip', 'vtime'));
        } else {
            @$data = array(
                'ip' => $ip,
                'vtime' => time(),
                'country' => $j['country'],
                'province' => $j['province'],
                'city' => $j['city'],
                'isp' => $j['isp'],
                'ip_desc' => $j['desc'],
                'ip_type' => $j['type'],
            );
    
            //记录当天该IP首次访问的时间
            if (false === $vtime) {
                $opt = $M->insert($data);
            } else {
                $opt = $M->update(compact('ip'), $data);
            }
            //上报成功
            $code = false !== $opt ? 0 : -3;
            return compact('code', 'data');
        }
    }
    
}