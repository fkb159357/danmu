<?php

//逗号分隔的字符串转换为数组
function commaToArray($str, $delElemSpace = true){
    if ($delElemSpace) { //默认删除每个分割元素中间的空白符
        $str = preg_replace('/\s/', '', $str);
    }
    $arr = array_values(array_unique(array_filter(preg_split('/,|，/', $str))));
    array_walk($arr, function(&$v, $k){
        $v = trim($v);
    });
    return $arr;
}