<?php
class LogDo extends DIDo {
    
    var $colMap = array(
    	0 => array('req_time', '请求时间'),
        1 => array('cdn_type', 'CDN计费类型'),
        2 => array('client_ip', '客户端状态'),
        3 => array('cdn_status', 'CDN状态'),
        4 => array('upstatus', '上游状态'),
        5 => array('req_nsname', '被请求域名'),
        6 => array('cpu_duration', 'CPU时间(毫秒)'),
        7 => array('db_duration', '数据库查询时间(微秒)'),
        8 => array('method', '请求方法'),
        9 => array('req_uri', '请求URI'),
        10 => array('request_flux', '请求流量(Byte)'),
        11 => array('response_flux', '响应流量(Byte)'),
        12 => array('http_status', 'HTTP状态码'),
        13 => array('referer', '来源'),
        14 => array('user_agent', '客户端UA'),
    );
    
    var $cdnTypeMap = array(
    	0 => '中国大陆/欧美节点',
        1 => '香港/亚洲节点'
    );
    
    function start(){
        echo '<form enctype="multipart/form-data" method="post" action="./?log/upld"><input type="file" name="log"><input type="submit"></form>';
    }
    
    private function puttable($lines){
        echo '<div id="result"></div><table>';
        echo '<tr>';
        foreach ($this->colMap as $c) {
            echo "<th data-handle='{$c[0]}' title='{$c[0]}' style='border: solid 1px; max-width: 150px; padding: 0;'>{$c[1]}</th>";
        }
        echo '</tr>';
        foreach ($lines as $i => $l) {
            echo "<tr data-line='{$i}'>";
            foreach ($l as $k=>$ll){
                echo "<td data-col='{$k}' style='border: solid 1px; max-width: 200px; padding: 0; overflow: auto; text-align: center;'>{$ll}</td>";
            }
        echo '</tr>';
        }
        echo '</table>';
        echo '<script src="http://www.duowan.com/assets/js/jquery.js"></script><script src="http://assets.dwstatic.com/amkit/entry.js"></script><script src="./res/lib/underscore-1.7-min.js"></script>';
        echo
            '<script>
                var dl = $("[data-line]"); 
                var grid = {}; 
                dl.each(function(i, e){ 
                    var line = $(e).attr("data-line"); 
                    var tds = $(e).children("td");
                    grid[line] = {};
                    tds.each(function(j, f){
                        var index = $(f).attr("data-col");
                        grid[line][index] = $(f).text();
                    });
                });
                console.log(grid);
                $("[data-handle]").live("click", function(){ 
                    var handle = $(this).attr("data-handle");
                    var tmp = _.indexBy(grid, handle); 
                    console.log(tmp);
                    var html = "";
                    $.each(tmp, function(i, e){
                        $.each(e, function(j, f){
                            html += j + "：[" + f + "]";
                        });
                        html += "<br>";
                    });
                    $("#result").html(html).focus();
                });
                //_.indexBy(grid, )
            </script>';
    }
    
    function upld(){
        @$file = $_FILES['log']['tmp_name'];
        $file || putalert('。。。', './?log/start');
        $lines = $this->readlog($file);
        $this->puttable($lines);
    }
    
    private function readlog($logfile){
        $lines = array();
        $h = fopen($logfile, 'r');
        
        while (! feof($h)) {
            $line = fgets($h);
            $arr = explode(' ', $line);
            if (count($arr) < 14) continue;
            $line = array();
            for ($i = 0; $i < 14; $i ++) {
                $v = $arr[$i];
                switch ($i) {
                	case 0: $v = date('Y-m-d H:i:s', $v);break;
                	case 1: $v = $this->cdnTypeMap[$v];break;
                }
                $line[$this->colMap[$i][0]] = $v;
            }
            $len = count($arr);
            for ($i = 14;$i < $len; $i ++) {
                $line['user_agent'] .= $arr[$i] . ' ';
            }
            $lines[] = $line;
        }
        
        return $lines;
    }
    
}