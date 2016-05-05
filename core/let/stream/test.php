<?php
//http://php.net/manual/zh/book.stream.php

/**
 * 自定义协议————5sing://
 */

class Resource5sing {
    static $songs = array();    
}

class Stream5sing {
    private $pos;
    private $song;
    
    function stream_open($path, $mode, $options, &$opened_path) {
        $path = explode('/', ltrim($path, '5sing://'));
        $this->song = Audio5sing::parseSong2($path[1], $path[0]);
        $this->pos = 0;
        return true;
    }
    
    function stream_read($count) {
        $str = serialize($this->song);
        $this->pos += strlen($str);
        return $str;
    }
    function stream_write($song) {
        $this->song = $song;
        $this->pos += strlen(serialize($song));
        return 0;
    }
    function stream_tell() {
        return $this->pos;
    }
    function stream_eof() {
        return $this->pos > 0 ? true : false;
    }
    function stream_seek($offset, $whence) {
        $this->pos++;
        return true;
        switch ($whence) {
        	case SEEK_SET :
        	    if ($offset < strlen ( $this->song ) && $offset >= 0) {
        	        $this->position = $offset;
        	        return true;
        	    } else {
        	        return false;
        	    }
        	    break;
        
        	case SEEK_CUR :
        	    if ($offset >= 0) {
        	        $this->position += $offset;
        	        return true;
        	    } else {
        	        return false;
        	    }
        	    break;
        
        	case SEEK_END :
        	    if (strlen ( $this->song ) + $offset >= 0) {
        	        $this->position = strlen ( $this->song ) + $offset;
        	        return true;
        	    } else {
        	        return false;
        	    }
        	    break;
        
        	default :
        	    return false;
        }
    }
    
    function stream_stat(){
        dump(func_get_args());
    }
    
    function url_stat($path, $arg){
        return 317;
    }
    
}

//readfile("php://filter/read=string.toupper|string.rot13/resource=http://www.google.com");die;


stream_wrapper_register('5sing', 'Stream5sing');
$w = stream_get_wrappers();
dump($w);
$file = '5sing://fc/13643363';
$h = fopen($file, 'r');
// var_dump(stream_get_contents($h));
dump('操作前指针：'.ftell($h));
var_dump(fread($h, 100000));
dump('操作后指针：'.ftell($h));
fclose($h);