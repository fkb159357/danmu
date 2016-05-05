-- 新增管理员表，以后再考虑增加各种分类的权限值、角色字段
-- @since 2015-03-07
-- Office localhost 已同步
-- hostker_fkb159357(miku.us) 已同步
-- HP localhost 已同步
CREATE TABLE `dm_rooter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `passport` varchar(20) NOT NULL COMMENT '通行证',
  `first_ip` varchar(16) NOT NULL DEFAULT '',
  `last_ip` varchar(255) NOT NULL DEFAULT '',
  `first_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;