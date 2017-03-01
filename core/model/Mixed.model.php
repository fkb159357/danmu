<?php
import('store/dwCache');

/**
 * 杂项配置
 */
class MixedModel extends DIModel {
   
    public function set($name, $content, $note = '', $valid = 1){
        $item = (array) $this->find(array('name' => $name));
        $data = array(
            'name' => $name,
            'content' => json_encode($content),
            'note' => $note,
            'create_ip' => getip(),
            'create_time' => time(),
            'update_ip' => getip(),
            'update_time' => time(),
            'create_user' => '',
            'update_user' => '',
            'valid' => $valid,
        );
        if (empty($item)) {
            unset($data['update_ip'], $data['update_time'], $data['update_user']);
            return $this->insert($data);
        } else {
            unset($data['create_ip'], $data['create_time'], $data['create_user']);
            $success = false !== $this->update(compact('name'), $data);
            $success AND dw_cache()->delete(__CLASS__.sha1($name));
            return $success;
        }
    }

    
    public function setValid($name, $valid){
        $valid = (int) $valid;
        $success = false !== $this->update(compact('name'), compact('valid'));
        $success AND dw_cache()->delete(__CLASS__.sha1($name));
        return $success;
    }
    
    
    public function get($name){
        $item = $this->getData($name);
        if (null === $item) return null;
        return $item['content'];
    }
    
    
    public function getData($name){
        $cache = dw_cache()->get(__CLASS__.sha1($name));
        if ($cache) return $cache;
        
        $item = (array) $this->find(array('name' => $name, 'valid' => 1));
        
        if (empty($item)) return null;
        $item['content'] = json_decode($item['content'], 1);
        
        dw_cache()->set(__CLASS__.sha1($name), $item);
        return $item;
    }
   
}
/*
CREATE TABLE `dm_mixed` (
  `mid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '代号,用于标识配置项',
  `content` longtext NOT NULL COMMENT '配置值(多个值存储时需序列化)',
  `note` varchar(64) NOT NULL DEFAULT '' COMMENT '注释',
  `create_ip` varchar(15) NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_ip` varchar(15) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `create_user` varchar(32) NOT NULL DEFAULT '' COMMENT '创建人',
  `update_user` varchar(32) NOT NULL DEFAULT '' COMMENT '修改人',
  `valid` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否有效',
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='杂项配置';
*/