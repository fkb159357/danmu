<?php
class TuInject extends DIInject {
    
    //上传接口限定条件
    function beforeUp(){
        //上传锁
        if (! session(DM_SESSION_TOUP_UNLOCK)) {
            //die('[INJECT MSG] tu ku wei hu zhong!');
        }
        //默认最大0.5MB
        $size = (int) (((float)session(DM_SESSION_TOUP_SIZE) ?: 0.5) * 1024 * 1024);
        if ($_FILES['tu']['error'] == UPLOAD_ERR_OK && $size < $_FILES['tu']['size']) {
            if (arg('getJson') == 1) {
                putjson(-99, null, "[INJECT MSG] limit {$size} Bytes!");
            } else {
                die("[INJECT MSG] limit {$size} Bytes!");
            }
        }
    }
    
}