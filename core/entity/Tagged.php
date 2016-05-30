<?php
/**
 * 适用业务：通用标签标记与查找
 */
class Tagged extends DIEntity {
    
    static protected function _saveNew($tabName, $tabId, $tag){
        $newTaggedIdList = array();
        $tagIdList = Tag::add($tag);
        foreach ($tagIdList as $vTagId) {
            $conds = array(
            	'tab_name' => $tabName,
                'tab_id' => $tabId,
            	'tag_id' => $vTagId,
            );
            $data = $conds + array(
                'setuid' => ($info = User::isLogin()) ? $info->id : 0,
            );
            $find = supertable('Tagged')->find($conds);
            if (empty($find)) {
                $newTaggedIdList[] = supertable('Tagged')->insert($data);
            }
        }
        return $newTaggedIdList;
    }
    
    //返回新设置的标签
    static function setTags($tabName, $tabId, $tags){
        $results = array();
        $tags = array_filter(array_unique(explode(',', preg_replace('/\s/', '', $tags))));
        foreach ($tags as $tag) {
            if (count(self::_saveNew($tabName, $tabId, $tag)) > 0) {
                array_push($results, $tag);
            }
        }
        return $results;
    }
    
    
    /**
     * 根据tag和tag_relate表，尽可能多地挖掘相关标签，以获取目标数据
     * @param string $tab_name 被关联的表名（一般不记前缀），如图片表dm_tu用“tu”
     * @param array $tags 输入用于挖掘的标签集合
     * @param string $mode 对挖掘到的相关标签，选择并集或交集的条件查询模式
     * @param bool $useRelate 是否使用标签关系表的数据来挖掘
     * @param int|string|array<int> $relation 关系类型，当$useRelate=true时，该参数有效。详见：TagRalate::digRelateTagIds()
     * @return array 返回目标数据的ID集合
     */
    static function digTabIdsByTags($tab_name, array $tags, $mode = 'union', $useRelate = true, $relation = 'all'){
        $taggedObj = supertable('Tagged');
        //根据tag字符串，找到有关的tagId集合
        $tagIds = Tag::digRelateTagIds($tags);
        //根据tag_relate表数据，更深挖掘其他tagId集合
        if ($useRelate) {
            $moreTagIds = TagRelate::digDeepRelateTagIds($tagIds, $relation);
            $tagIds = array_unique(array_merge($tagIds, $moreTagIds));
        }
        //拼接条件
        $tagIdsLen = count($tagIds);
        if (0 == $tagIdsLen) return array();
        $sqlIdsIn = "";
        $idsConds = array();
        foreach ($tagIds as $k => $vId) {
            $sqlIdsIn .= ":tagid{$k},";
            $idsConds["tagid{$k}"] = $vId;
        }
        $sqlIdsIn = rtrim($sqlIdsIn, ',');
        //选择查询模式：并集 | 交集
        switch ($mode) {
        	case 'intersect': $havingSql = " HAVING COUNT(tgd.tab_id) = {$tagIdsLen} "; break;
        	case 'union':
        	default: $havingSql = '';
        }
        $sql = "SELECT tgd.tab_id FROM {$taggedObj->table} tgd
                WHERE tgd.tab_name = 'tu' AND tgd.tag_id IN ({$sqlIdsIn})
                GROUP BY tgd.tab_id $havingSql";
        $list = $taggedObj->query($sql, $idsConds);
        //组合结果
        $tabIds = array();
        foreach ($list as $v) $tabIds[] = $v->tab_id;
        return $tabIds;
    }
    
}