<?php
//@todo 增加需求：删除某本书
class BookDo extends DIDo {

    function start(){
        putjson(0, ['book' => Book::getList(), 'bookPlus' => BookPlus::getList()]);
    }


    //read对应prepare
    function read($code, $p = 1){
        $data = BookPlus::read($code, $p);
        if ($data) {
            echo '<div style="width: 720px; overflow: auto; font-size: 28px !important;">';
            echo preg_replace('/\r\n|\r|\n/', '<br><br>', $data['s']);
            echo '</div>';
            $lastP = $data['p'] > 1 ? $data['p'] - 1 : 1;
            $nextP = $data['p'] + 1;
            $urlPrefix = url_prefix();
            echo "<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"{$urlPrefix}read/{$code}/{$lastP}\">上一页</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"{$urlPrefix}read/{$code}/{$nextP}\">下一页</a><br><br><br><br><br><br><br><br><br>";
        }
    }


    //read2对应prepare2
    function read2($code, $p = 1){
        $data = Book::read($code, $p);
        if ($data) {
            echo '<div style="width: 720px; overflow: auto; font-size: 28px;">';
            echo preg_replace('/\r\n|\r|\n/', '<br><br>', $data['s']);
            echo '</div>';
            $lastP = $data['p'] > 1 ? $data['p'] - 1 : 1;
            $nextP = $data['p'] + 1;
            echo "<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"{$urlPrefix}read/{$code}/{$lastP}\">上一页</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"{$urlPrefix}read/{$code}/{$nextP}\">下一页</a><br><br><br><br><br><br><br><br><br>";
        }
    }

    
    //http://book.miku.us/prepare?pageNum=0&url=http://res.miku.us/res/other/%E8%AE%A1%E7%AE%97%E6%9C%BA%E8%8B%B1%E8%AF%AD.txt&per=1024&name=%E8%AE%A1%E7%AE%97%E6%9C%BA%E8%8B%B1%E8%AF%AD
    function prepare(){
        ignore_user_abort();
        set_time_limit(0);
        $pageStart = arg('pageStart', 1); //保存的开始页（有个BUG，如果大于1，可能取到的字符串开头会有不完整的UTF-8字符，所以忽略该参数吧）
        $pageNum = arg('pageNum', 1); //保存的页数（传0则保存所有页码的分片）
        $per = arg('per', 4096); //每页多少字符
        $url = arg('url', 'http://res.miku.us/res/other/ditieguishi.txt');
        $name = arg('name', '地铁诡事');
        if ($url) {
            BookPlus::prepare($name, $url, $pageStart, $pageNum, $per);
        }
    }
    
    
    function prepare2(){
        ignore_user_abort();
        set_time_limit(0);
        $pageStart = arg('pageStart', 1); //保存的开始页（有个BUG，如果大于1，可能取到的字符串开头会有不完整的UTF-8字符，所以忽略该参数吧）
        $pageNum = arg('pageNum', 1); //保存的页数（传0则保存所有页码的分片）
        $per = arg('per', 4096); //每页多少字符
        $url = arg('url', 'http://res.miku.us/res/other/ditieguishi.txt');
        $name = arg('name', '地铁诡事');
        if ($url) {
            Book::prepare($name, $url, $pageStart, $pageNum, $per);
        }
    }

}