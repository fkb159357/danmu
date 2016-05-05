<?php
//用途：eval, 贴图库封装，代码缓存
class EvalStream {
    
    private static $protocol = 'eval';
    static $contents = array();
    private $pos;
    private $path;
    
    static function wrap(){
        stream_wrapper_register(self::$protocol, __CLASS__);
    }
    
    static function unwrap(){
        stream_wrapper_unregister(self::$protocol);
    }
    
    function stream_open($path , $mode , $options , &$opened_path){
        $this->path = str_replace(self::$protocol.'://', '', $path);
        if (! is_string($s = &self::$contents[$this->path])) {
            $s = '';
        }
        $this->pos = 0;
        return true;
    }
    
    //function stream_close(){unset(self::$contents[$this->path]);}
    
    /* function stream_lock($operation){
        switch ($operation) {
        	case LOCK_EX: ;
        	case LOCK_UN: ;
        	case LOCK_SH: ;
        	case LOCK_NB: ;
        }
        return true;
    } */
    
    function stream_write($data){
        self::$contents[$this->path] .= $data;
        $this->pos += strlen($data);
        return strlen($data);
    }
    
    function stream_read($count){
        $this->pos += $count;
        return substr(self::$contents[$this->path], $this->pos, $count);
    }
    
    function stream_tell() {
        return $this->pos;
    }
    function stream_eof() {
        return $this->pos >= strlen(self::$contents[$this->path]) ? true : false;
    }
    function stream_seek($offset, $whence) {
        switch ($whence) {
        	case SEEK_SET :
        	    if ($offset < strlen ( self::$contents[$this->path] ) && $offset >= 0) {
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
        	    if (strlen ( self::$contents[$this->path] ) + $offset >= 0) {
        	        $this->pos = strlen ( self::$contents[$this->path] ) + $offset;
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
        return array();
    }
    
    function url_stat($path, $flags){
        return array();
    }
    
}

EvalStream::wrap();

$f = 'eval://hehe';
$h = fopen($f, 'r+');
fwrite($h, '<?php echo 123;');
$r = fread($h, 8192);
var_dump($r);
//var_dump(file_get_contents('eval://hehe'));//此处执行了30秒以上还未出结果
include $f;
EvalStream::unwrap();


//使用file_put_contents()插入变量




/* 

class base{
    public $obj = null;
    public function __construct($obj=null){
        $this->obj = $obj;
    }

    public function __call($method, $option){
        Event::emit(get_class($this->obj).'::''before'.$method, $option);

        if( method_exists($this->obj, 'update'.$method) ){
            call_user_func_array(array($this->obj, 'update'.$method), $option);
        }else{
            call_user_func_array(array($this->obj, $method), $option);
        }

        Event::emit(get_class($this->obj).'::''after'.$method, $option);
    }

    static function getStance(){

    }

    static function newStance(){
        $base = __CLASS__;
        $class = get_called_class();
        $obj = new $class();
        return new $base($obj);
    }
}
class myClass extends base{

    public function beforefunc1(){
        echo 'beforefunc1<br>';
    }

    public function afterfunc1(){
        echo 'afterfunc1<br>';
    }

    public function updatefunc2(){
        echo 'updatefunc2<br>';
    }

    public function func1(){
        echo 'func1<br>';
    }

    public function func2(){
        echo 'func2<br>';
    }
}

Event::on('*::before*', function(){


});

    Event::on('*::after*', function(){


    });

        $myclass = myClass::newStance();

        $myclass->func1();

        $myclass->func2();

        $obj = new myClass();
        $myclass-> */