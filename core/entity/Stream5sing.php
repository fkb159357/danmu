<?php
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
















/* class Stream5sing {
    
    private $song = null;
    
    //boolean stream_open ( string $path , string $mode , int $options , string $opened_path )
    function stream_open($path, $mode, $options, $opened_path) {
        $path = explode('/', ltrim($path, '5sing://'));
        $this->song = Audio5sing::parseSong2($path[1], $path[0]);
        dump($this->song);
        return $this->song ? true : false;
    }
    
    //string stream_read ( int $count )
    function stream_read($count=8192){
        return serialize($this->song);
    }
    
    //int stream_write ( string $data )
    function stream_write($data){
        
    }
    
    //boolean stream_eof ( void )
    function stream_eof(){
        
    }
    
    //int stream_tell ( void )
    function stream_tell(){
        
    }
    
    //boolean stream_seek ( int $offset , int $whence )
    function stream_seek($offset, $whence){
        
    }
    
    //boolean stream_flush ( void )
    function stream_flush(){
        
    }
    
    function stream_stat(){
        
    }
    
    //void stream_close ( void )
    function stream_close(){
        
    }
} */