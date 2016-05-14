<?php
class Tag extends DIEntity {
    
    
    
    static function getPureTag($tag){
        $pureTag = preg_replace('/\\|-|\(|\)|\^|\$|\[|\]|\+|\*|\?|\{|\}|\.|\/|\=|\_|·|～|！|＠|＃|￥|％|…|＆|×|（|）|——|－|＋|＝《|》|【|】|｛|｝|、|，|。|｜/', '', $tag);
        return $pureTag;
    }
    
}