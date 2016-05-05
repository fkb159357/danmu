<?php
/**
 * 与音乐播放、地址解析等相关
 * @author Ltre<ltrele@gmail.com>
 * @since 2015-1-15
 */
class AudioDo extends DIDo {

    /**
     * 解析5sing单曲，并入库，遇到已解析过的，则读库
     * @param string $songId 单曲ID
     * @param string $songType 类型 fc,yc,bz
     * @param string arg(addr) 若指定播放页地址，则忽略songID和songType
     * @param int arg(force) 1|0 - 是|否   -  是否强制解析，即使存在歌曲，也会进行。
     */
    function parse5sing($songId = null, $songType = 'fc'){
        $addr = null===$songId ? arg('addr') : "http://5sing.kugou.com/{$songType}/{$songId}.html";
        $info = null===$addr ? false : Audio5sing::parseSong($addr);
        if (is_array($info)) {
            putjsonp(0, $info, '获取成功');
        } else {
            putjsonp(-1, null, '获取失败');
        }
    }
    
    /**
     * 解析5sing单曲方法二
     * @param string $songId 单曲ID
     * @param string $songType 类型 fc,yc,bz
     * @param string arg(addr) 若指定播放页地址，则忽略songID和songType
     */
    function parse5sing2($songId = null, $songType = 'fc'){
        $addr = arg('addr');
        preg_match("/com\/(.*)\/(.*).html/",$addr,$matches);
        if (!!$addr && !empty($matches)) {
            $songType = $matches[1];
            $songId = $matches[2];
        }
        $info = (!!$songId && !!$songType) ? Audio5sing::parseSong2($songId, $songType) : false;
        if (is_array($info)) {
            putjsonp(0, $info, '获取成功');
        } else {
            putjsonp(-1, null, '获取失败');
        }
    }
    
    /**
     * 解析作者信息
     * @param string $singerid
     */
    function parseSinger($singerid) {
        $info = Audio5sing::parseSinger2($singerid);
        if (empty($info)) {
            putjsonp(-1, null, '获取失败');
        } else {
            putjsonp(0, $info, '获取成功');
        }
    }
    
    /**
     * 收集作者的歌曲
     * @param string $singerid
     * @param string $songType
     * @param int $pagepos
     */
    function collectUserSongs($singerid, $songType = 'fc', $pagepos = 1){
        $songs = Audio5sing::collectSingerSongsByType($singerid, $songType, $pagepos);
        if (empty($songs)) {
            putjsonp(-1, null, '获取失败');
        } else {
            putjsonp(0, $songs, '获取成功');
        }
    }
    
    /**
     * 收集榜单歌曲
     * @param string $type index|fc|bz 原创|翻唱|伴奏
     * @param string $st date('Y-m-d')
     */
    function collectTopSongs($type = '', $st = ''){
        $type || $type = array_item(array('index','fc','bz'), rand(0, 2));
        $st || $st = date('Y-m-d', rand(strtotime(date('2013-01-01')), time()));
        Audio5sing::collectTopSongs($type, $st);
    }
    
    /**
     * 通过关键字爬取歌曲列表
     * 
     */
    function search5sing(){
        
    }
    
    //随机取多个头像[要求不能重复]
    function randAvatars($num = 100, &$map = array(), $layer = 0, $maxLayer = 5){
        $map || $map = array();
        $sql = "SELECT singerid, singer, avatar FROM `dm_audio5sing` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `dm_audio5sing`)-(SELECT MIN(id) FROM `dm_audio5sing`))+(SELECT MIN(id) FROM `dm_audio5sing`)) AS id) AS t2 WHERE t1.id >= t2.id LIMIT {$num}";
        $rs = supermodel()->query($sql);
        foreach ($rs as $r) {
            $map[$r->singerid] = $r;
        }
        if (count($map) < $num && $layer < $maxLayer) {
            $this->randAvatars($num, $map, $layer + 1, $maxLayer);
        } else {
            //dump($map);echo '条数：'.count($map).'  层数：'.$layer;//确保取到100条，不够的除外
            $gap = count($map) - $num;
            if ($gap > 0) {
                foreach ($map as $k => $m) {
                    unset($map[$k]); 
                    if (-- $gap == 0) break;
                }
            }
            putjsonp(0, $map);
        }
    }
    
}