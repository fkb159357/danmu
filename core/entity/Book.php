<?php

class Book extends DIEntity {

    static function prepare($name, $url, $pageStart = 1, $pageNum = 1, $per = 1024){ //设置pageNum为0时，则一直读到文件尾部
        $saveFolder = sha1($url);
        $saveName = $saveFolder . '.txt';
        $file = self::_download($url, $saveName);
        $h = fopen($file, 'rb');
        self::_saveInfoMap($file, $saveFolder, $name, $url);
        self::_createChunkFolder($file, $saveFolder);
        for ($p = $pageStart; ($pageNum == 0 ? true : ($p <= $pageNum)) && ! feof($h); $p ++) {
            $s = self::_getChunkStr($h, $per);
            self::_saveChunk($saveFolder, $p, $s);
        }
        fclose($h);
    }


    static function read($code, $p = 1){
        $mapFile = DI_CACHE_PATH . 'books.map';
        $json = file_get_contents($mapFile) ?: '{}';
        $map = json_decode($json, 1);
        if (isset($map[$code])) {
            $data = $map[$code];
            $file = DI_CACHE_PATH . "{$data['folder']}/{$p}.txt";
            $s = file_get_contents($file);
            return array(
                's' => $s,
                'p' => $p,
                'name' => $data['name'],
            );
        }
        return false;
    }


    static private function _getChunkStr($h, $limit){
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
            $s = substr($s, 0, strlen($s) - $noAsciiCount);
            fseek($h, - $noAsciiCount, SEEK_CUR);
        }
        return $s;
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


    static private function _saveInfoMap($file, $folder, $name, $url){
        $mapFile = DI_CACHE_PATH . 'books.map';
        $json = file_exists($mapFile) ? (file_get_contents($mapFile) ?: '{}') : '{}';
        $map = json_decode($json, 1);
        $map[$folder] = array('file' => $file, 'folder' => $folder, 'name' => $name, 'url' => $url);
        file_put_contents($mapFile, json_encode($map, JSON_UNESCAPED_UNICODE));
    }


    static private function _createChunkFolder($file, $folder){
        $folder = DI_CACHE_PATH . $folder;
        if (! file_exists($folder)) mkdir($folder, 0777);
    }


    static private function _saveChunk($folder, $p, $s){
        $file = DI_CACHE_PATH . "{$folder}/{$p}.txt";
        $hW = fopen($file, 'wb');
        fwrite($hW, $s);
        fclose($hW);
    }

}