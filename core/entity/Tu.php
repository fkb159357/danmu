<?php
class Tu extends DIEntity {
    
    //获取重复的老图
    static protected function _getOld($file){
        $sha1 = @sha1_file($file['tmp_name']);
        $tu = supertable('Tu')->find(compact('sha1'));
        return $tu;
    }
    
    //插入新图数据
    static protected function _save($upRet, $tuId = 0){
        $tu = array();
        if (0 == $upRet['code']) {
            $tu = array(
                'filename' => $upRet['fileName'],
                'fileext' => $upRet['fileExt'],
                'mimetype' => $upRet['mimeType'],
                'filesize' => $upRet['fileSize'],
                'width' => $upRet['width'],
                'height' => $upRet['height'],
                'savefile' => $upRet['saveFile'],
                'savedir' => $upRet['saveDir'],
                'fullfile' => $upRet['fullFile'],
                'fulldir' => $upRet['fullDir'],
                'url' => $upRet['url'],
                'sha1' => $upRet['sha1'],
                'hide' => 0,
                'clear' => 0,
                'uptime' => time(),
                'upuid' => ($info = User::isLogin()) ? $info->id : 0,
            );
            if ($tuId) {
                $tu['id'] = $tuId;
                supertable('Tu')->update(array('id' => $tuId), $tu);
            } else {
                $tu['id'] = supertable('Tu')->insert($tu);
            }
        }
        return $tu;
    }
    
    //上传表单域的文件，并保存记录
    static function saveRecord($file, $imgDirGroup = ''){
        $oldTu = self::_getOld($file);
        if (! empty($oldTu)) {
            $lastId = $oldTu->id;
            $tu = (array) $oldTu;
            $isOld = true;
        } else {
            import('file/ImgUpload');
            $client = new ImgUploadClient($imgDirGroup);
            $client->setLimit(array('maxSize' => 4194304));//4MB
            $upRet = $client->up($file);
            $tu = self::_save($upRet);
            $isOld = false;
        }
        
        unset($tu['filepath']);//即将废除该字段
        unset($tu['savefile'], $tu['savedir'], $tu['fullfile'], $tu['fulldir']);
        @$ret = array_merge($tu, array(
        	'code' => $upRet['code'] ?: 0,
            'msg' => $upRet['msg'] ?: 'already uploaded',
        ));
        return $ret;
    }
    
    //重新上传表单域的文件，并保存记录(暂时不管旧文件的删除问题，以后写程序统一删除图片服务器中没有备案的图片即可)
    static function reSaveRecord($tuId, $file, $imgDirGroup = ''){
        import('file/ImgUpload');
        $client = new ImgUploadClient($imgDirGroup);
        $client->setLimit(array('maxSize' => 4194304));//4MB
        $upRet = $client->up($file);
        $tu = self::_save($upRet, $tuId);
        
        unset($tu['filepath']);//即将废除该字段
        unset($tu['savefile'], $tu['savedir'], $tu['fullfile'], $tu['fulldir']);
        @$ret = array_merge($tu, array(
            'code' => $upRet['code'] ?: 0,
            'msg' => $upRet['msg'] ?: 'already uploaded',
        ));
        return $ret;
    }
    
    //将图源删除到回收站目录，并标记删除图片
    static function recycleSource(){
        
    }
    
    //将图源从回收站目录彻底删除，并删除数据库记录
    static function delSource(){
        
    }
    
}