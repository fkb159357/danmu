<?php

class BookDo extends DIDo {

    function start(){

    }


    function read($code, $p = 1){
        $data = Book::read($code, $p);
        var_dump($data);
        if ($data) {
            echo '<pre style="overflow: auto;">';
            echo $data['s'];
            echo '</pre>';
        }
    }


    function prepare(){
        ignore_user_abort();
        set_time_limit(0);
        $pageStart = arg('pageStart', 1); //保存的开始页（有个BUG，如果大于1，可能取到的字符串开头会有不完整的UTF-8字符，所以忽略该参数吧）
        $pageNum = arg('pageNum', 1); //保存的页数（传0则保存所有页码的分片）
        $per = arg('per', 4096); //每页多少字符
        $url = arg('url', 'http://res.miku.us/res/other/ditieguishi.txt');
        $name = '地铁诡事';
        if ($url) {
            Book::prepare($name, $url, $pageStart, $pageNum, $per);
        }
    }

}