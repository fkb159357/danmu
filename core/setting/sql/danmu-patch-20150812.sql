-- 新增图片表、图片标签表、图片标签关联表
-- @since 2015-08-12
-- hostker_fkb159357(miku.us) 已同步
-- Office localhost 已同步
-- HP localhost 已同步
DROP TABLE IF EXISTS `dm_tu`;
CREATE TABLE `dm_tu` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(128) NOT NULL,
  `filepath` varchar(320) NOT NULL,
  `filetype` varchar(20) NOT NULL,
  `filesize` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `url` varchar(256) NOT NULL,
  `uptime` int(11) NOT NULL COMMENT '上传时间',
  `upuid` int(11) NOT NULL DEFAULT '0' COMMENT '上传者',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `dm_tu_tag`;
CREATE TABLE `dm_tu_tag` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tu_id` bigint(20) NOT NULL,
  `tag` varchar(32) NOT NULL,
  `settime` int(11) NOT NULL COMMENT '打标签时间',
  `setuid` bigint(20) NOT NULL DEFAULT '0' COMMENT '打标签者',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tu_tag` (`tu_id`,`tag`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `dm_tu_tag_relate`;
CREATE TABLE `dm_tu_tag_relate` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(32) NOT NULL,
  `relation` tinyint(4) NOT NULL DEFAULT '0' COMMENT '关联关系：0-无关联；1-桥接关系；2-相似关系；3-父节点为对方；4-子节点为对方',
  `relate_tag` varchar(32) NOT NULL,
  `settime` int(11) NOT NULL,
  `setuid` bigint(20) NOT NULL DEFAULT '0' COMMENT '设置者：0为系统自动关联',
  PRIMARY KEY (`id`),
  UNIQUE KEY `relate` (`tag`,`relation`,`relate_tag`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

