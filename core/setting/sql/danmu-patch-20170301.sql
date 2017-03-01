-- 杂项配置表
-- @since 2017-03-01
-- HP localhost 未同步
-- Office localhost 已同步
-- CONOHA 已同步
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
