<?php
class Tag extends DIEntity {
    
    /**
     * 插入新标签到库
     * @param string $tag 主标签
     * @param array $pureTags 由主标签处理特殊字符后，得到的一个或多个纯标签。纯标签用于智能查找。
     * @return array 所插入的ID集合
     */
    static function add($tag, array $pureTags = array()){
        $lastIdList = array();
        $pureTag = self::getPureTag($tag);
        foreach (array($pureTag) + $pureTags as $pt) {
            $data = array(
            	'tag' => $tag,
                'pure_tag' => $pt,
            );
            $find = supertable('Tag')->find($data);
            if (empty($find)) {
                $lastId = supertable('Tag')->insert($data);
            } else {
                $lastId = $find->id;
            }
            $lastIdList[] = $lastId;
        }
        return $lastIdList;
    }
    
    
    static function getPureTag($tag){
        $pureTag = preg_replace('/\\|-|\(|\)|\^|\$|\[|\]|\+|\*|\?|\{|\}|\.|\/|\=|\_|·|～|！|＠|＃|￥|％|…|＆|×|（|）|——|－|＋|＝《|》|【|】|｛|｝|、|，|。|｜/', '', $tag);
        return $pureTag;
    }
    
    
    /**
     * 根据tag集合，尽可能多得找到本表内有关的tagId集合
     */
    static function digTagIdsInSitu(array $tags){
        static $collect = array();//最终返回的集合值
        static $tagsArgHistory = array();//已用的tag参数，防止递归时重复传入
        $trace = debug_backtrace();
        if (0 != strcasecmp(__FUNCTION__, @$trace[1]['function'])) {
            $collect = $tagsArgHistory = array();//非递归模式，清空静态存储
        }
        $tagsArgHistory = array_merge($tagsArgHistory, $tags);
        $nextTagsArg = array();//递归调用的tags参数
        //开始挖掘
        $tagObj = supertable('Tag');
        $sql = "SELECT * FROM {$tagObj->table} WHERE `tag` = :tag OR pure_tag = :pure_tag";
        foreach ($tags as $tag) {
            $list = $tagObj->query($sql, array('tag' => $tag, 'pure_tag' => $tag));
            foreach ($list as $v) {
                $currId = (int) $v->id;
                if (! in_array($currId, $collect)) {
                    $collect[] = $currId;
                    if (! in_array($v->tag, $tagsArgHistory)) $nextTagsArg[] = $v->tag;
                    if (! in_array($v->pure_tag, $tagsArgHistory)) $nextTagsArg[] = $v->pure_tag;
                }
            }
        }
        if (! empty($nextTagsArg)) {
            //试图顺藤摸瓜
            self::digTagIdsInSitu(array_unique($nextTagsArg));
        }
        return $collect;
    }
    
    
}