<?php

class BookPlus extends DIEntity {

    //准备资源：下载文本，保存分片对应的起始文件指针
    static function prepare($name, $url, $pageStart = 1, $pageNum = 1, $per = 1024){ //设置pageNum为0时，则一直读到文件尾部
        $code = sha1($name.$url.$per);
        $saveName = $code . '.txt';
        $file = self::_download($url, $saveName);
        $h = fopen($file, 'rb');
        $chunks = array();
        for ($p = $pageStart; ($pageNum == 0 ? true : ($p <= $pageNum)) && ! feof($h); $p ++) {
            $chunks[$p] = self::_getChunkInfo($h, $per);
        }
        self::_saveInfoMap($code, $file, $name, $url, $chunks);
        fclose($h);
    }


    //根据指定资源编码和页码，获取对应资源的对应分片
    static function read($code, $p = 1){
        $mapFile = DI_CACHE_PATH . 'bookplus.map';
        @$json = file_get_contents($mapFile) ?: '{}';
        $map = json_decode($json, 1);
        if (isset($map[$code])) {
            $data = $map[$code];
            list($offset, $limit) = $data['chunks'][$p] ?: [0, 4096];
            $s = self::_getChunk($data['file'], $offset, $limit);
            return array(
                's' => $s,
                'p' => $p,
                'offset' => $offset,
                'limit' => $limit,
                'name' => $data['name'],
            );
        }
        return false;
    }


    static function getList(){
        $mapFile = DI_CACHE_PATH . 'bookplus.map';
        @$json = file_get_contents($mapFile) ?: '{}';
        return json_decode($json, 1);
    }


    static private function _getChunkInfo($h, $limit){
        $offset = ftell($h);
        $s = fread($h, $limit);
        $len = strlen($s);
        $last3 = strlen($s) >= 3 ? substr($s, -3) : $s;
        $noAsciiCount = 0;
        for ($j = 0; $j < $len; $j ++) {
            $c = $s[$j];
            $isAscii = ord($c) >= 0 && ord($c) <= 127;
            if ($isAscii) {
                $noAsciiCount = 0;
            } else {
                $noAsciiCount ++;
                $noAsciiCount == 3 && $noAsciiCount = 0;
            }
        }
        //到达尾部后，检测最后三个字符是否存在无法拼成UTF-8的字符，有则舍弃之，并按所舍弃位数回退文件指针
        if (in_array($noAsciiCount, array(1, 2))) {
            $limit -= $noAsciiCount;
            fseek($h, - $noAsciiCount, SEEK_CUR);
        }
        return [$offset, $limit];
    }


    static private function _download($url, $saveName){
        $file = DI_CACHE_PATH . $saveName;
        if (file_exists($file)) {
            return $file;
        }
        $ret = file_get_contents($url);
        file_put_contents($file, $ret);
        return $file;
    }


    static private function _saveInfoMap($code, $file, $name, $url, $chunks){
        $mapFile = DI_CACHE_PATH . 'bookplus.map';
        $json = file_exists($mapFile) ? (file_get_contents($mapFile) ?: '{}') : '{}';
        $map = json_decode($json, 1);
        $map[$code] = array('file' => $file, 'name' => $name, 'url' => $url, 'chunks' => $chunks);
        file_put_contents($mapFile, json_encode($map, JSON_UNESCAPED_UNICODE));
    }


    static private function _getChunk($file, $offset, $limit){
        $h = fopen($file, 'rb');
        fseek($h, $offset, SEEK_SET);
        $s = fread($h, $limit);
        fclose($h);
        return $s;
    }


}