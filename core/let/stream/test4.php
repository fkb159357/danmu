<?php
class EvalStream {
    var $position;
    var $varname;
     
    function stream_open($path, $mode, $options, &$opened_path)
    {
        $url = parse_url($path);
        $this->varname = $url["host"];
        $this->position = 0;

        return true;
    }

    function stream_read($count)
    {
        $ret = substr($GLOBALS[$this->varname], $this->position, $count);
        $this->position += strlen($ret);
        return $ret;
    }

    function stream_write($data)
    {
        $left = substr($GLOBALS[$this->varname], 0, $this->position);
        $right = substr($GLOBALS[$this->varname], $this->position + strlen($data));
        $GLOBALS[$this->varname] = $left . $data . $right;
        $this->position += strlen($data);
        return strlen($data);
    }

    function stream_tell()
    {
        return $this->position;
    }

    function stream_eof()
    {
        return $this->position >= strlen($GLOBALS[$this->varname]);
    }

    function stream_seek($offset, $whence)
    {
        switch($whence) {
        	case SEEK_SET:
        	    if ($offset < strlen($GLOBALS[$this->varname]) && $offset >= 0) {
        	        $this->position = $offset;
        	        return true;
        	    } else {
        	        return false;
        	    }
        	    break;

        	case SEEK_CUR:
        	    if ($offset >= 0) {
        	        $this->position += $offset;
        	        return true;
        	    } else {
        	        return false;
        	    }
        	    break;

        	case SEEK_END:
        	    if (strlen($GLOBALS[$this->varname]) + $offset >= 0) {
        	        $this->position = strlen($GLOBALS[$this->varname]) + $offset;
        	        return true;
        	    } else {
        	        return false;
        	    }
        	    break;

        	default:
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

function testEval($code){
    stream_register_wrapper("var", "EvalStream") or die("Failed to register protocol");
    global $myvar; $myvar = "";
    file_put_contents('var://myvar', '<?php '.$code);
    include 'var://myvar';
}





testEval('echo "这是PHP代码"; /* dump(get_declared_classes()); */ ');