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
                'setuid' => ($info = User::isLogin()) ? $info['id'] : 0,
            );
            $find = supertable('Tagged')->find($conds) ?: array();
            if (empty($find)) {
                $newTaggedIdList[] = supertable('Tagged')->insert($data);
            }
        }
        return $newTaggedIdList;
    }
    
    //设置标签，并返回新设置的标签
    static function setTags($tabName, $tabId, $tags){
        $results = array();
        $tags = array_filter(array_unique(explode(',', preg_replace('/\s*,\s*/', ',', trim($tags)))));
        foreach ($tags as $tag) {
            if (count(self::_saveNew($tabName, $tabId, $tag)) > 0) {
                array_push($results, $tag);
            }
        }
        return $results;
    }
    
    
    /**
     * 根据数据ID，获取其对应的标签
     */
    static function getTagsByTabId($tabName, $tabId){
        $tagObj = supertable('Tag');
        $taggedObj = supertable('Tagged');
        $tagDataList = $taggedObj->select(array('tab_id' => $tabId), 'tag_id') ?: array();
        $sql = "SELECT t.tag, t.pure_tag FROM {$taggedObj->table} tgd, {$tagObj->table} t 
                WHERE tgd.tag_id = t.id AND tgd.tab_id = :tabId AND tgd.tab_name = :tabName";
        $tagDataList = $taggedObj->query($sql, array('tabId' => $tabId, 'tabName' => $tabName));
        $result = array();
        foreach ($tagDataList as $v) {
            if (! in_array($v['tag'], $result)) $result[] = $v['tag'];
            if (! in_array($v['pure_tag'], $result)) $result[] = $v['pure_tag'];
        }
        return $result;
    }
    
    
    /**
     * 根据输入的标签，获取历史的打标签组合，及频率从高到低排序的相关标签。方便选择
     */
    static function getGroupsAndTopTagsByTag($tabName, array $tags){
        if (empty($tags)) return array('topTags' => array(), 'groups' => array());
        $tagObj = supertable('Tag');
        $taggedObj = supertable('Tagged');
        $tagCondSql = "1 = 0";
        $conds = array('tabName' => $tabName);
        foreach ($tags as $i => $tag) {
            $tagCondSql .= " OR t.tag LIKE :tag{$i} OR t.pure_tag LIKE :tag{$i}";
            $conds["tag{$i}"] = "{$tag}%";
        }
        $getTabIdsql =
            "SELECT tgd.tab_id FROM {$taggedObj->table} tgd, {$tagObj->table} t
            WHERE tgd.tag_id = t.id AND tgd.tab_name = :tabName
            AND ( {$tagCondSql} ) GROUP BY tab_id";
        //拿TOPS
        $topTagsSql =
            "SELECT COUNT(tgd.tab_id) `cnt`, t.pure_tag `tag` FROM
            {$taggedObj->table} AS tgd, ( {$getTabIdsql} ) AS tmp_table, {$tagObj->table} AS t
            WHERE tgd.tab_id = tmp_table.tab_id AND tgd.tag_id = t.id AND tgd.tab_name = :tabName
            GROUP BY t.pure_tag ORDER BY `cnt` DESC";
        $topTags = supermodel()->query($topTagsSql, $conds) ?: array();
        //拿历史组合
        $groupSql =
            "SELECT tgd.tag_id, tgd.tab_id, t.tag, t.pure_tag FROM
            {$taggedObj->table} AS tgd, ( {$getTabIdsql} ) AS tmp_table, {$tagObj->table} AS t
            WHERE tgd.tab_id = tmp_table.tab_id AND tgd.tag_id = t.id AND tgd.tab_name = :tabName";
        $tgdList = supermodel()->query($groupSql, $conds) ?: array();
        $groups = array();
        foreach ($tgdList as $v) {
            if (! isset($groups[$v['tab_id']])) $groups[$v['tab_id']] = array();
            if (! in_array($v['pure_tag'], $groups[$v['tab_id']])) $groups[$v['tab_id']][] = $v['pure_tag'];
        }
        return compact('topTags', 'groups');
    }
    
    
    /**
     * 根据tagged的标签与数据对应关系，挖掘潜在的tag_id
     * @param string $tabName 表名
     * @param array $tagIds 用于输入搜索的tag_id集合
     * @param int $maxLayerNum 最大挖掘层数，设置为0则挖掘全部
     */
    static function digTaggedTagIds($tabName, array $tagIds, $maxLayerNum = 0){
        static $layer = 1;//挖掘层数
        static $collect = array();//最终返回的集合值
        static $tagIdsArgHistory = array();//已用的tag_id参数，防止递归时重复传入
        $trace = debug_backtrace();
        if (0 != strcasecmp(__FUNCTION__, @$trace[1]['function'])) {
            $collect = $tagIdsArgHistory = array();//非递归模式，清空静态存储
        }
        $tagIdsArgHistory = array_merge($tagIdsArgHistory, $tagIds);
        $nextTagIdsArg = array();//递归调用的tagIds参数
        $taggedObj = supertable('Tagged');
        foreach ($tagIds as $tagId) {
            $dataIdlist = $taggedObj->select(array('tag_id' => $tagId, 'tab_name' => $tabName), 'tab_id') ?: array();
            foreach ($dataIdlist as $vData) {
                $tagIdList = $taggedObj->select(array('tab_id' => $vData['tab_id'], 'tab_name' => $tabName), 'tag_id') ?: array();
                foreach ($tagIdList as $vTag) {
                    $currTagId = (int) $vTag['tag_id'];
                    if (! in_array($currTagId, $collect)) {
                        $collect[] = $currTagId;
                        if (! in_array($currTagId, $tagIdsArgHistory)) {
                            $nextTagIdsArg[] = $currTagId;
                        }
                    }
                }
            }
        }
        if (! empty($nextTagIdsArg) && (0 == $maxLayerNum || $layer < $maxLayerNum)) {
            $layer ++;
            self::digTaggedTagIds($tabName, array_unique($nextTagIdsArg), $maxLayerNum);
        }
        sort($collect);
        return $collect;
    }
    
    
    /**
     * 根据tag和tag_relate表，尽可能多地挖掘相关标签，以获取目标数据
     * @param string $tabName 被关联的表名（一般不记前缀），如图片表dm_tu用“tu”
     * @param array $tags 输入用于挖掘的标签集合
     * @param string $mode 对挖掘到的相关标签，选择并集或交集的条件查询模式
     * @param bool $useRelate 是否使用标签关系表的数据来挖掘
     * @param int|string|array<int> $relation 关系类型，当$useRelate=true时，该参数有效。详见：TagRalate::digDeepRelateTagIds()
     * @param bool $useTaggedData 是否使用tagged表的数据来挖掘超深关联的标签（一般不启用，容易获取到无关的数据）
     * @param int $maxLayerNum 最大挖掘层数，设置为0则挖掘全部（仅在$useTaggedData=true时有效）
     * @return array 返回目标数据的ID集合
     */
    static function digTabIdsByTags($tabName, array $tags, $mode = 'union', $useRelate = true, $relation = 'all', $useTaggedData = false, $maxLayerNum = 0){
        $taggedObj = supertable('Tagged');
        //根据tag字符串，找到有关的tagId集合
        $tagIds = Tag::digTagIdsInSitu($tags);
        //根据tagged + tag_relate表数据，更深挖掘其他tagId集合
        if ($useRelate) {
            $moreTagIds = TagRelate::digDeepRelateTagIds($tagIds, $relation);
            $tagIds = array_unique(array_merge($tagIds, $moreTagIds));
        }
        if ($useTaggedData) {
            $moreTagIds = self::digTaggedTagIds($tabName, $tagIds, $maxLayerNum);
            $tagIds = array_unique(array_merge($tagIds, $moreTagIds));
        }
        //拼接条件
        $tagIdsLen = count($tagIds);
        if (0 == $tagIdsLen) return array();
        $sqlIdsIn = "";
        $conds = array('tabName' => $tabName);
        foreach ($tagIds as $k => $vId) {
            $sqlIdsIn .= ":tagid{$k},";
            $conds["tagid{$k}"] = $vId;
        }
        $sqlIdsIn = rtrim($sqlIdsIn, ',');
        //选择查询模式：并集 | 交集
        switch ($mode) {
        	case 'intersect': $havingSql = " HAVING COUNT(tgd.tab_id) = {$tagIdsLen} "; break;
        	case 'union':
        	default: $havingSql = '';
        }
        $sql = "SELECT tgd.tab_id FROM {$taggedObj->table} tgd
                WHERE tgd.tab_name = :tabName AND tgd.tag_id IN ({$sqlIdsIn})
                GROUP BY tgd.tab_id $havingSql
                ORDER BY tgd.tab_id DESC"; //于20170504加入此DESC语句，如有错误，则删除之
        $list = $taggedObj->query($sql, $conds) ?: array();
        //组合结果
        $tabIds = array();
        foreach ($list as $v) $tabIds[] = $v['tab_id'];
        return $tabIds;
    }


    //获取指定对象的打标签记录
    static function getHistory($tabName, array $option = array()){
        $page = (int)@$option['page'] ?: 1;
        $limit = (int)@$option['limit'] ?: 10;
        $scope = (int)@$option['scope'] ?: 10;
        $where = ['AND', ['tab_name', '=', $tabName]];
        if (@$option['uid']) $where[] = ['setuid', '=', (int)$option['uid']];
        if (@$option['tabId']) $where[] = ['tab_id', '=', (int)$option['tabId']];
        if (@$option['tagId']) $where[] = ['tag_id', '=', (int)$option['tagId']];
        $senior = new SeniorModel();
        $tgdObj = supertable('Tagged');
        $queryData = $senior->seniorSelect(array(
            'select' => 'tag_id tagId, tab_id tabId, settime, setuid',
            'from' => $tgdObj->table,
            'where' => $where,
            'orderBy' => 'settime DESC',
            'limit' => [$page, $limit, $scope],
            'pageable' => true,
        ));
        unset($queryData['debug']);
        return $queryData;
    }
    
}