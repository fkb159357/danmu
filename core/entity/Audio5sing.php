<?php
class Audio5sing extends DIEntity {
    
    /**
     * 解析5sing单曲，并入库，遇到已解析过的，则读库
     * 解析来源：http://static.5sing.kugou.com/release/song/v3.0.00/js/listen.min.js的var curSongObj = eval("(" + globals.base64.decode(globals.ticket) + ")");
     * 虽然地址统一用http://5sing.kugou.com/fc/xxxxx.html，但会存在两种播放页面。
     * 来源1：http://5sing.kugou.com/fc/13643363.html
     * 解析结果1：{"songID":13412807,"songType":"fc","songName":"\u771f\u4e09\u56fd\u68a6\u7cbe\u5173\u952e\u70b9\u6574\u5408\u7248","file":"http:\/\/data6.5sing.kgimg.com\/T1iHCbBXCT1R47IVrK.mp3","singerID":38934606,"singer":"\u6298\u6728\u3055\u3093","avatar":"http:\/\/img5.5sing.kgimg.com\/m\/T1_whTBCbT1RXrhCrK.jpg","collect":false}"
     * 来源2：http://5sing.kugou.com/fc/13367535.html
     * 解析结果2: {"songID":13367535,"songType":"fc","songName":"ALL MY LOVE\u3010HB\u3002\u6751\u6751\u3011","file":"http:\/\/data6.5sing.kgimg.com\/T10qhXBXYT1R47IVrK.mp3","singerID":6626976}
     * @param string $addr 单曲播放地址
     * @param int $force 1|0 - 是|否   -  是否强制解析，即使存在歌曲，也会进行。
     * @return array
     */
    static function parseSong($addr, $force = 0){
        $songid = self::getSongID($addr);
        $find = DIModelUtil::supertable('Audio5sing')->find(compact('songid'));//该值后面还会用到
        if (is_object($find) && self::checkSongValid($find->file) && ! $force) {
            unset($find->id);
            return (array)$find;
        }
        
        $flag = false;
        import('net/dwHttp');
        $h = new dwHttp;
        $ret = $h->get($addr);
        $pattern = '/\"ticket\"\s*\:\s*\"[a-zA-Z0-9=]{125,}\"\s*\,?\}?/';
        preg_match($pattern, $ret, $matches);
        if (! empty($matches)) {
            $ticket = preg_replace(array('/\"ticket\"\s*\:\s*\"/', '/\"\s*\,?\}?/'), '', $matches[0]);
        }
        if (isset($ticket) && !empty($ticket)) {
            $json = base64_decode($ticket);
            $obj = json_decode($json);
            if (is_object($obj)) {
                @$data = array(
                    'songid' => $obj->songID,
                    'songtype' => $obj->songType,//fc-翻唱，yc-原唱
                    'songname' => $obj->songName,
                    'file' => $obj->file,//下载地址
                    'singerid' => $obj->singerID,
                    'singer' => $obj->singer,//5sing用户昵称
                    'avatar' => $obj->avatar,//5sing用户头像(不一定有)
                );
                /* if ( !!$data['singerid'] && (!$data['singer'] || !$data['avatar']) ) {
                    $userinfo = self::parseSinger($data['singerid']);
                    $data = array_merge($data, $userinfo);
                } */
                if ( !!$data['singerid'] && (!$data['singer'] || !$data['avatar']) ) {
                    import('phpQuery/phpQuery');
                    phpQuery::newDocumentHTML($ret);
                    $data['singer'] = pq(".user.lt>a:eq(0)")->attr('title');
                    $data['avatar'] = pq(".user.lt>a>img:eq(0)")->attr('src');
                }
                if (is_object($find) && $find->file!=$data['file']) {
                    self::syncSong($data, (array)$find);
                } else {
                    DIModelUtil::supertable('Audio5sing')->insert($data);
                }
                $flag = true;
            }
        }

        return $flag ? $data : false;
    }
    
    /**
     * 解析单曲的第二种方法
     * 通过移动端的WebView抓取
     */
    static function parseSong2($songid, $songtype = 'fc'){
        $find = DIModelUtil::supertable('Audio5sing')->find(compact('songid'));//该值后面还会用到
        if (is_object($find) && self::checkSongValid($find->file)) {
            unset($find->id);
            return (array)$find;
        }
        
        $str = "http://5sing.kugou.com/m/detail/$songtype-$songid-1.html";
        $curl = function ($url) {
            $curl = curl_init();
            curl_setopt($curl,CURLOPT_URL,$url);
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,10);
            curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_0 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8A293 Safari/6531.22.7");
            $src = curl_exec($curl);
            curl_close($curl);
            return $src;
        };
        $src = $curl($str, "");

        import('phpQuery/phpQuery');
        phpQuery::newDocumentHTML($src);
        $text_title = pq('title:eq(0)')->text();
        $arr_title = explode('-', $text_title);
        $songname = trim($arr_title[0]);
        $singer = trim($arr_title[1]);
        $avatar = pq('.m_head>img:eq(0)')->attr('src');
        
        preg_match('/m\/space\/\d+\.html/', $src, $matches);
        !empty($matches) && preg_match('/\d+/', $matches[0], $matches);
        $singerid = !empty($matches) ? $matches[0] : false;

        preg_match('|"href","(.*)"|U', $src, $mp3);
        $mp3= str_replace('data','img',$mp3);
        $file = !empty($mp3) ? $mp3[1] : false;

        $data = compact(
            'songid', 'songtype', 'songname', 'file',
            'singerid', 'singer', 'avatar'
        );

        if (is_object($find) && $find->file!=$data['file']) {
            self::syncSong($data, (array)$find);
        } else {
            DIModelUtil::supertable('Audio5sing')->insert($data);
        }

        return (!!$songname&&!!$file&&!!$singerid) ? $data : false;
    }
    
    /**
     * 解析5sing用户信息
     * 【！有些页面无法抓取，暂停使用(可能是会员页面抓取失败)】
     * @param string $singerID
     */
    static function parseSinger($singerid){
        $url = "http://5sing.kugou.com/{$singerid}/default.html";
        $singer = $avatar = '';
    
        import('net/dwHttp');
        $h = new dwHttp();
        $ret = $h->get($url);
        $pattern = '/var\s*OwnerNickName\s*\=\s*(\'|\").*(\'|\")\s*\;/';
        preg_match($pattern, $ret, $matches);
        if (!empty($matches)) {
            $singer = preg_replace(array('/var\s*OwnerNickName\s*\=\s*(\'|\")/', '/(\'|\")\s*\;/'), '', $matches[0]);
        }
    
        import('phpQuery/phpQuery');
        phpQuery::newDocumentHTML($ret);
        $avatar = pq('.banner.banner_yyr>div>dl>dt>span>img:eq(0)')->attr('src');

        return compact('singerid' ,'singer', 'avatar');
    }
    
    /**
     * 解析5sing用户信息（方法二）
     * 通过移动端WebView抓取
     * 待办：保存抓取结果以缓存
     */
    static function parseSinger2($singerid){
        if (! $singerid) return false;
        
        $url = "http://5sing.kugou.com/m/space/{$singerid}.html";
        import('net/dwHttp');
        $h = new dwHttp();
        $ret = $h->get($url);
        
        import('phpQuery/phpQuery');
        phpQuery::newDocumentHTML($ret);
        $singer = pq('#nickname')->text();
        $avatar = pq('.m_head>img:eq(0)')->attr('src');
        
        return !!$singer ? compact('singerid', 'singer', 'avatar') : false;
    }
    
    /**
     * 收集作者的最新歌曲
     */
    static function collectSingerNewSongs($singerid){
        
    }
    
    /**
     * 收集作者的最热歌曲
     */
    static function collectSingerHotSongs($singerid){
        
    }
    
    /**
     * 收集榜单歌曲
     * @param string $type index|fc|bz 原创|翻唱|伴奏
     * @param string $st date('Y-m-d')
     */
    static function collectTopSongs($type = 'fc', $st = null){
        import('net/dwHttp');
        'yc' == $type && $type = 'index';
        !! $st ? $st = "?st={$st}" : '';
        $url = "http://5sing.kugou.com/top/{$type}.html{$st}";
        $h = new dwHttp();
        $ret = $h->get($url, 12);
        
        $pattern = '/http\:\/\/5sing\.kugou\.com\/(yc|fc|bz)\/\d+\.html/';
        preg_match_all($pattern, $ret, $matches);
        $links = array();
        foreach ($matches as $m) {
            foreach ($m as $mm) {
                if (preg_match($pattern, $mm)) 
                    $links[] = $mm;
            }
        }
        $links = array_unique($links);
        shuffle($links);
        
        set_time_limit(0);
        ignore_user_abort();
        foreach ($links as $l) {
            self::parseSong($l);
        }
    }
    
    /**
     * 按分类收集作者的歌曲
     * 通过移动端WebView抓取
     * http://5sing.kugou.com/m/space/mysongs/{$singerid}-{$songtype}-{$分页页码}.html
     */
    static function collectSingerSongsByType($singerid, $songtype = 'fc', $pagepos = 1){
        $url = "http://5sing.kugou.com/m/space/mysongs/{$singerid}-{$songtype}-{$pagepos}.html";
        
        import('net/dwHttp');
        $h = new dwHttp();
        $ret = $h->get($url);
        
        import('phpQuery/phpQuery');
        phpQuery::newDocumentHTML($ret);
        $M = DIModelUtil::supertable('Audio5sing');
        $songs = array();
        pq('.list_con.list_items>li>a')->each(function($e) use(&$songs, $M){
        	$songlink = pq($e)->attr('href');
        	$str = preg_replace(array('/(http\:\/\/)?/', '/\/m\/detail\//', '/\.html/'), '', $songlink);
        	$arr = explode('-', $str);
        	@$songtype = $arr[0];
        	@$songid = $arr[1];
        	if (!!$songtype && !!$songid) {
        	    $find = $M->find(compact('songid'));
        	    if (is_object($find)) {
        	        $songs[] = $find;
        	    } else {
            	    $info = Audio5sing::parseSong2($songid, $songtype);
            	    //尽量确保歌曲链接有效
            	    if (!!$info && !Audio5sing::checkSongValid($info['file'])) {
            	        $addr = "http://5sing.kugou.com/{$info['songtype']}/{$info['songid']}.html";
            	        $info2 = Audio5sing::parseSong($addr);
            	        $info['file'] = $info2['file'];
            	    }
            	    if (!empty($info)) { unset($info['id']); $songs[] = $info; }
        	    }
        	}
        });
        return $songs;
    }
    
    /**
     * 根据5sing播放地址获取songID
     * 如取http://5sing.kugou.com/fc/13350042.html中的13350042
     * @param string $addr
     * @return string
     */
    static function getSongID($addr){
        preg_match('/(fc|yc|bz)\/\d+\.html/', $addr, $matches);
        if (! empty($matches)) {
            $id = preg_replace(array('/(fc|yc|bz)\//', '/\.html/'), '', $matches[0]);
            return $id;
        }
        return false;
    }
    
    //检测单曲信息是否有效
    static public function checkSongValid($url){
        @$info = get_headers($url, 1);
        $isAudio = false !== stripos($info['Content-Type'], 'audio');
        return ($isAudio && $info['Content-Length'] > 0);
    }
    
    //同步库内单曲信息, 两个array参数：$new无id，$raw有id。被parseSong()调用
    static private function syncSong($new, $raw, DIModel $M = null){
        $flag_file = self::checkSongValid($new['file']) && $new['file']==$raw['file'];
        $flag_songtype = $new['songtype']!='' && $new['songtype']==$raw['songtype'];
        $flag_songname = $new['songname']!='' && $new['songname']==$raw['songname'];
        $flag_singerid = $new['singerid']!='' && $new['singerid']==$raw['singerid'];
        $flag_singer = $new['singer']!='' && $new['singer']==$raw['singer'];
        $flag_avatar = $new['avatar']!='' && $new['avatar']==$raw['avatar'];
        if (!$flag_file || !$flag_songtype || !$flag_songname || !$flag_singerid || !$flag_singer || !$flag_avatar) {
            empty($M) && $M = DIModelUtil::supertable('Audio5sing');
            $M->update(array('songid'=>$new['songid']), $new);
        }
    }
    
}