<?php
class TagRelate extends DIEntity {
    
    /**
     * 通过dm_tag_ralte表获取更多关联的tag_id
     * @param array $tagIds 输入用于挖掘关联标签的标签ID集合
     * @param int|string|array<int> $relation 关系类型
     *      当relation='all'时，不限制关系类型；
     *      当relation=0时，选用相似关系；
     *      当relation=1时，选用父到子关系
     *      当relation为数组时，将拼接IN查询
     * @return array
     */
    static function digRelateTagIds(array $tagIds, $relation = 'all') {
        $sqlConds = array();
        //拼接relation条件
        $relationSql = " AND 1=1 ";
        if ($relation === 0) $relationSql .= " AND `relation` = 0 ";
        elseif ($relation === 1) $relationSql .= " AND `relation` = 1 ";
        elseif ($relation === 'all') $relationSql = "";
        elseif (is_array($relation) && count($relation) > 0) {
            $relIn = "";
            foreach ($relation as $k => $vRel) {
                $relIn .= ":vRel{$k},";
                $sqlConds["vRel{$k}"] = $vRel;
            }
            $relIn = rtrim($relIn, ',');
            $relationSql .= " AND relation IN ({$relIn})";
        }
        //拼接tag_id条件
        $tagIdsIn = "";
        foreach ($tagIds as $k => $vId) {
            $tagIdsIn .= ":tagid{$k},";
            $sqlConds["tagid{$k}"] = $vId;
        }
        $tagIdsIn = rtrim($tagIdsIn, ',');
        //拼接reltag_id条件
        $relTagIdsIn = "";
        foreach ($tagIds as $k => $vId) {
            $relTagIdsIn .= ":reltagid{$k},";
            $sqlConds["reltagid{$k}"] = $vId;
        }
        $relTagIdsIn = rtrim($relTagIdsIn, ',');
        //开始查询
        $tagRelObj = supertable('TagRelate');
        $sql = "SELECT tag_id, reltag_id FROM {$tagRelObj->table}
                WHERE ( tag_id IN ({$tagIdsIn}) OR reltag_id IN ({$relTagIdsIn}) ) {$relationSql} ";
        $list = $tagRelObj->query($sql, $sqlConds);
        //组装结果
        $resultTagIds = array();
        foreach ($list as $v) {
            $resultTagIds[] = $v->tag_id;
            $resultTagIds[] = $v->reltag_id;
        }
        return array_unique($resultTagIds);
    }
    
}