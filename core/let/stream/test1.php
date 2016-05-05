<?php
class SongStream {
    
    private $pos;//指针
    private $song;//流的资源
    
    static function wrap(){
        stream_wrapper_register('song', __CLASS__); 
    }
    
    static function unwrap(){
        stream_wrapper_unregister('song');
    }
    
    function stream_open($path, $mode, $options, &$opened_path) {
        $path = explode('/', ltrim($path, 'song://'));
        $this->song = Audio5sing::parseSong2($path[1], $path[0]);//这是某个具体的业务方法，不必看
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
        switch ($whence) {
        	case SEEK_SET :
        	    if ($offset < strlen ( $this->song ) && $offset >= 0) {
        	        $this->pos = $offset;
        	        return true;
        	    } else {
        	        return false;
        	    }
        	    break;
    
        	case SEEK_CUR :
        	    if ($offset >= 0) {
        	        $this->pos += $offset;
        	        return true;
        	    } else {
        	        return false;
        	    }
        	    break;
    
        	case SEEK_END :
        	    if (strlen ( $this->song ) + $offset >= 0) {
        	        $this->pos = strlen ( $this->song ) + $offset;
        	        return true;
        	    } else {
        	        return false;
        	    }
        	    break;
    
        	default :
        	    return false;
        }
    }
    
}




SongStream::wrap();

$file = 'song://fc/13643363';
$h = fopen($file, 'r');
dump('是否移动到尾部：'.(feof($h)?'是':'否'));
dump('操作前指针：'.ftell($h));
var_dump(fgets($h));
dump('操作后指针：'.ftell($h));
dump('是否移动到尾部：'.(feof($h)?'是':'否'));

SongStream::unwrap();