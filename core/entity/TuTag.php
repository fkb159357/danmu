<?php
class TuTag extends DIEntity {
    
    /*
     * 即将作废
    */
    static protected function _saveNew($tuId, $tag){
        $tt = array(
        	'tu_id' => $tuId,
            'tag' => $tag,
            'settime' => time(),
            'setuid' => ($info = User::isLogin()) ? $info['id'] : 0,
        );
        try {
            $lastId = supertable('TuTag')->insert($tt);
        } catch (DIException $e) {
            $lastId = false;
        }
        return $lastId;
    }
    
    //返回新设置的标签
    /*
     * 即将作废
    */
    static function setTags($tuId, $tags){
        $results = array();
        $tags = array_filter(array_unique(explode(',', preg_replace('/\s*,\s*/', ',', trim($tags)))));
        foreach ($tags as $tag) {
            if (self::_saveNew($tuId, $tag)) {
                array_push($results, $tag);
            }
        }
        return $results;
    }
    
}