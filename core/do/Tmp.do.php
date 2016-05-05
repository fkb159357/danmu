<?php
//放置一些临时代码，有些改改就能临时用
class TmpDo extends DIDo {
    
    //用于远程导入数据，随时改，暂时不要删除
    function import_client(){
        return;//关闭
        
        $m = supertable('Audio5sing');
        $d = $m->query('select * from dm_audio5sing where id>354');
        foreach ($d as &$dd) {
            $dd = (array) $dd;
            unset($dd['id']);
        }
        
        import('net/dwHttp');
        $h = new dwHttp();
        
        die;//到达此处，会想Miku.us导入新数据，请谨慎执行
        $ret = $h->post('http://miku.us/?tmp/import_server', array('data' => serialize($d)), 50);
        $ret = unserialize($ret);
        var_dump($ret);
    }
    
    //接收import_client的数据
    function import_server(){
        $data = arg('data');
        $data = unserialize($data);
        $ids = array();
        foreach ($data as $d) {
            $ids[] = supertable('Audio5sing')->insert($d);
        }
        echo serialize($ids);
    }
    
}