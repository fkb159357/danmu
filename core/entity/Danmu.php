<?php
/**
 * 存放弹幕所需的配置
 * @author 折木さん　<ltrele@gmail.com>
 * @link http://larele.com
 * @link http://miku.us
 * @link http://ltre.cc
 * @link http://ltre.me
 * @link http://xmiku.cc
 * @link http://emiku.cc
 * @link http://xmiku.cc
 */
class Danmu extends DIEntity {
    
    static function players($n = 1){
        $p = DI_URL_PREFIX . 'res/biz/danmu/';
        $map = array(
            1 => $p . 'player/MukioPlayer.swf',//支持皮肤但可能不支持弹幕
            2 => $p . 'player/MukioPlayer_old.swf',//支持弹幕但可能不支持皮肤
            3 => $p . 'player/MukioPlayerPlus.swf', //2MB的播放器
        );
        return $map[$n];
    }
    
    
    static function skins($n = 1){
        $p = DI_URL_PREFIX . 'res/biz/danmu/';
        $map = array(
            1 => $p . 'skin/nemesis.zip',
        	2 => $p . 'skin/darkrv5.zip',
            3 => $p . 'skin/grungetape.zip',
            4 => $p . 'skin/nikitaskin.zip',
            5 => $p . 'skin/plexi.zip',
            6 => $p . 'skin/miku.zip',
            7 => $p . 'skin/nikitaskin.zip',
            8 => $p . 'skin/TokyoGhost.zip',
        );
        return $map[$n];
    }
    
    
    /**
     * MukioPlayerPlus
     * 生成UBB时自动更新UBB字段
     * @author ltre
     * @since 2014-12-30
     */
    static function ubb3($id, $fontsize = 18, $width = 730, $height = 462, $auto_update = true){
        $danmu = DIModelUtil::supertable('Danmu');
        $d = $danmu->find(compact('id'));
        if (empty($d)) return false;
        if (empty($d->v_url)) return false;
        if (empty($d->d_url)) return false;
        (int) $d->skin || $d->skin= 1;
        
        $player_url = self::players(3);
        $skin_url = self::skins($d->skin);
        
        $swf = "{$player_url}?id={$id}/{$fontsize}&file={$d->v_url}&skin={$skin_url}&autostart=false";
        $tran = array("[" => "%5B", "]" => "%5D");
        foreach ($tran as $k => $t) {
            $swf = str_replace($k, $t, $swf);
        }
        $ubb = "[flash=$width,$height]{$swf}[/flash]";
        
        if ($auto_update) { /*是否自动更新*/
            $danmu->update(compact('id'), compact('ubb'));
        }
        
        return compact('ubb', 'swf');
    }
    
    
    /**
     * MukioPlayer_old
     * 生成UBB时自动更新UBB字段
     * @author ltre
     * @since 2014-12-30
     */
    static function ubb2($id, $width = 730, $height = 462){
        $danmu = DIModelUtil::supertable('Danmu');
        $d = $danmu->find(compact('id'));
        if (empty($d)) return false;
        if (empty($d->v_url)) return false;
        if (empty($d->d_url)) return false;
    
        $player_url = self::players(2);
    
        $swf = "{$player_url}?cfile={$d->d_url}&file={$d->v_url}";
        $tran = array("[" => "%5B", "]" => "%5D");
        foreach ($tran as $k => $t) {
            $swf = str_replace($k, $t, $swf);
        }
        $ubb = "[flash=$width,$height]{$swf}[/flash]";
        $danmu->update(compact('id'), compact('ubb'));

        return compact('ubb', 'swf');
    }
    
    
    /**
     * MukioPlayer
     * 生成UBB时自动更新UBB字段
     * @author ltre
     * @since 2014-12-30
     */
    static function ubb1($id, $fontsize = 18, $width = 730, $height = 462){
        $danmu = DIModelUtil::supertable('Danmu');
        $d = $danmu->find(compact('id'));
        if (empty($d)) return false;
        if (empty($d->v_url)) return false;
        if (empty($d->d_url)) return false;
        (int) $d->skin || $d->skin= 1;
    
        $player_url = self::players(1);
        $skin_url = self::skins($d->skin);
    
        //$swf = "{$player_url}?id={$id}/{$fontsize}&file={$d->v_url}&skin={$skin_url}&autostart=false";
        $swf = "{$player_url}?cfile={$d->d_url}&file={$d->v_url}&skin={$skin_url}&autostart=false";
        $tran = array("[" => "%5B", "]" => "%5D");
        foreach ($tran as $k => $t) {
            $swf = str_replace($k, $t, $swf);
        }
        $ubb = "[flash=$width,$height]{$swf}[/flash]";
        $danmu->update(compact('id'), compact('ubb'));
    
        return compact('ubb', 'swf');
    }
    
    
    //取标题中的一个代表字，靠前优先
    static function getFaceWord($str){
        $n = array(' ', '[', '【', '！', '!', '?', '？', '#', '~', '（', '(', '）', ')', '】', ']', '“', '”', '<', '>', '《', '》', '「', '」');
        $len = mb_strlen($str, 'utf-8');
        $i = 0;
        do {
            $current = mb_substr($str, $i, 1, 'utf-8');
            $invaid = in_array($current, $n);
            $islast = $i + 1 == $len;
            if ($invaid && $islast) {
                $current = 'N';
            } elseif (!$invaid) {
                break;
            }
            $i ++;
        } while (!$islast);
        
        return !isset($current) || empty($current) ? 'N' : $current;
    }
    
    
    //【这段代码可共用】CURL一个远程XML（可能个别text/xml类型的输出是GZIP的），兼容HFS输出的application/oct-stream类型和常规的text/xml类型
    static function curlxml($xmlurl){
        $contentType = array_item(get_headers($xmlurl, 1), 'Content-Type');
        $isTextXML = 0 === strcasecmp('text/xml', $contentType);

        if (function_exists('file_get_contents') && !$isTextXML) {
            $xml = file_get_contents($xmlurl);
        } elseif (function_exists('curl_init')) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $xmlurl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//不输出
            if ($isTextXML) {
                curl_setopt($curl, CURLOPT_ENCODING, "gzip");
            }
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_HTTPGET, 0);
            $xml = curl_exec($curl);
            curl_close($curl);
        } else {
            $msg = "Ooops!!! The PHP plugin for CURL functions cannot be found!!";
            throw new DIException($msg);
        }
        
        return $xml;
    }
    
    
    //修改弹幕字体大小,返回xml字符串
    static function modFontsize($xml, $fontsize = 18){
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->loadXML($xml);
        $i = $dom->getElementsByTagName('i');
        foreach ($i->item(0)->childNodes as $k => $node) {
            $type = $node->nodeType;
            $tagName = '';
            if (XML_ELEMENT_NODE == $node->nodeType && 'd' == $node->tagName) {
                $p = $node->attributes->getNamedItem('p')->nodeValue;
                $p = explode(',', $p);
                $p[2] = $fontsize;//字体
                $p = join(',', $p);
                $node->setAttribute('p', $p);
                //var_dump(compact('k', 'node', 'type', 'p'));//test
            }
        }
        return $dom->saveXML();
    }
    
}