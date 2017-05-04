<?php
class Tu extends DIEntity {
    
    //获取重复的老图
    static protected function _getOld($file){
        if (! file_exists($file['tmp_name'])) return array();
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
            $client->setLimit(array('maxSize' => 5242880));//5MB
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
        $client->setLimit(array('maxSize' => 5242880));//5MB
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

    //通用信息获取
    static function getInfoById($tuId){
        $tuObj = supertable('Tu');
        $tu = $tuObj->find(array('id' => $tuId), 'id tuId, filename, fileext, mimetype, filesize, width, height, savefile, url');
        return $tu;
    }

    //获取未打标签的图
    static function getByNoTagged($limit = 20, $p = 1){
        $offset = ($p - 1) * $limit;
        $taggedObj = supertable('Tagged');
        $tuObj = supertable('Tu');
        $sql = 
            "SELECT id tuId, filename, fileext, mimetype, filesize, width, height, savefile, url 
            FROM {$tuObj->table} tu WHERE id NOT IN 
            ( SELECT tab_id FROM {$taggedObj->table} tgd WHERE tgd.tab_name = 'tu' GROUP BY tab_id )
            ORDER BY tuId DESC LIMIT {$offset}, {$limit}";
        $list = $tuObj->query($sql) ?: array();
        return $list;
        /*$taggedObj = supertable('Tagged');
        $tuObj = supertable('Tu');
        $sql = "SELECT distinct(tab_id) FROM {$taggedObj->table} tgd WHERE tgd.tab_name = 'tu' ORDER BY tab_id DESC";
        $list = $taggedObj->query($sql);
        $tgdTuIds = array();
        foreach ($list as $v) {
            $tgdTuIds[] = $v['tab_id'];
        }
        $sql = "SELECT id FROM {$tuObj->table} tu WHERE id ORDER BY id DESC";
        */
    }
    
}