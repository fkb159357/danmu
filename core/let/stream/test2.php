<?php
class SingerStream {
    
    private static $protocol = 'singer';
    private $pos;//指针，STEP以数组元素为准
    private $songs;//流的资源
    
    static function wrap(){
        stream_wrapper_register(self::$protocol, __CLASS__); 
    }
    static function unwrap(){
        stream_wrapper_unregister(self::$protocol);
    }
    function stream_open($path, $mode, $options, &$opened_path) {
        $path = explode('/', ltrim($path, self::$protocol."://"));
        $this->songs = Audio5sing::collectSingerSongsByType($path[0]);//这是某个具体的业务方法，不必看
        $this->pos = 0;
        return true;
    }
    function stream_read($count) {
        $len = count($this->songs);
        if ($this->pos < $len) {
            $str = serialize($this->songs[$this->pos]);
            $this->pos ++;
            return $str;
        } else {
            return false;
        }
    }
    function stream_write($song) {
        $this->songs[$this->pos] = $song;
        $this->pos ++;
        return 0;
    }
    function stream_tell() {
        return $this->pos;
    }
    function stream_eof() {
        return $this->pos >= count($this->songs) ? true : false;
    }
    function stream_seek($offset, $whence) {
        $this->pos++;
        return true;
        switch ($whence) {
        	case SEEK_SET :
        	    if ($offset < count ( $this->songs ) && $offset >= 0) {
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
        	    if (count ( $this->songs ) + $offset >= 0) {
        	        $this->pos = count ( $this->songs ) + $offset;
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


SingerStream::wrap();

$file = 'singer://395236';
$h = fopen($file, 'r');
dump('是否移动到尾部：'.(feof($h)?'是':'否'));
dump('操作前指针：'.ftell($h));
var_dump(fgets($h));
dump('操作后指针：'.ftell($h));
dump('是否移动到尾部：'.(feof($h)?'是':'否'));

SingerStream::unwrap();