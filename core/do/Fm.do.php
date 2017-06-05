<?php
class FmDo extends DIDo {
    
    private $remoteCmdFilePre;//远程设备控制命令文件
    
    private $remoteCmd = array(
    	'type' => 0, //0-nothing,1-play,2-pause,3-next,4-prev,5-switch,6-favorite,7-increaseVolume,8-reduceVolume
        'value' => 0, //type=switch时，值为歌曲排序位置，从0开始
        'status' => 0, //0-未执行,1-已执行
    );
    
    protected function _init(){
        $this->remoteCmdFilePre = DI_CACHE_PATH.'fm_rm_cmd_';
    }
    
    function start(){
        $this->tpl();die;//先随机加载5sing的歌曲并播放
        
        //试验品：到时不会批量请求
        $m = supertable('Audio5sing');
        $r = $m->select(null, 'DISTINCT singerid') ?: array();
        $infos = array();
        foreach ($r as $rr) {
            $infos[] = Audio5sing::parseSinger2($rr->singerid);
            break;
        }
        dump($infos);
    }
    
    //随机获取一曲
    function loadOne(){
        $song = supertable('Audio5sing')->select('', '', 'rand()', 1) ?: array();
        empty($song) && putjsonp(-1, null, 'song is not found');
        putjsonp(0, $song[0]);
    }
    
    //该接口与audio/collectTopSongs类似。但这个可以定时向库中导入5sing单曲，当前仅提供给fm.js异步调用(当找不到歌曲时，也调用这个接口收集歌曲)
    function importMore(){
        $inGap1 = ($v=time() % ($gap=86400)) > 0 && $v < $gap/5; // 0~1/5
        $inGap2 = $v > $gap*2/5 && $v < $gap*3/5; // 2/5~3/5
        $inGap3 = $v > $gap*4/5 && $v < $gap; // 4/5~1
        $inGap = $inGap1 || $inGap2 || $inGap3;
        $collected = session_exists(DM_SESSION_ALRDY_COL_TOPSNG);
        
        if (true || $inGap && ! $collected) {
            session(DM_SESSION_ALRDY_COL_TOPSNG, true);
            $type = array_item(array('index','fc','bz'), rand(0, 2));
            $st = date('Y-m-d', rand(strtotime(date('2013-01-01')), time()));
            Audio5sing::collectTopSongs($type, $st);
        }
    }
    
    //移动端上传
    function upmob(){
        if ($_SERVER['REQUEST_METHOD'] != 'POST'){
            $this->stpl();
        }//END
        var_dump($_FILES);
    }
    
    //主控端(一般是移动设备)的操作面板
    function ctrlview($token){
        if (! $token) return;
        $this->token = $token;
        $this->stpl();
    }
    
    //移动设备(主控端)向本服务器发送控制命令
    function setCmd($token, $type = 0, $value = 0){
        if (! $token) return;
        $f = $this->remoteCmdFilePre.$token;
        $a = $this->remoteCmd;
        $a['type'] = (int)$type;
        $a['value'] = (int)$value;
        file_put_contents($f, serialize($a));
        putjsonp(0);
    }
    
    //PC浏览器(被控端)从本服务器获取所接收到的移动设备控制命令
    function getCmd($token, $lastIsExec = 0){
        if (! $token) return;
        $f = $this->remoteCmdFilePre.$token;
        if (! file_exists($f)) file_put_contents($f, serialize($this->remoteCmd));
        $c = file_get_contents($f);
        $a = unserialize($c);
        if ($lastIsExec) {
            $a['status'] = 1;
            file_put_contents($f, serialize($a));
        }
        putjsonp(0, $a);
    }
    
    //加入收藏，暂时用短信形式
    function favorite(){
        import('phone/dwSMS');
        $c = 'songname：'.arg('songname').'
singer：'.arg('singer').'
link：http://5sing.kugou.com/'.arg('type').'/'.arg('songid').'.html
file：'.arg('file');
        $sms = new dwSMS();
        $ret = $sms->send('15918716484', $c);
        putjsonp(0, compact('ret'));
    }

}