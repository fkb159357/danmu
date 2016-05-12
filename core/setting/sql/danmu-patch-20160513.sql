-- 通用标签表
-- @since 2016-05-13
-- conoha 未同步
-- Office localhost 未同步
-- HP localhost 已同步
CREATE TABLE `dm_tag` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tag` varchar(64) NOT NULL COMMENT '标签',
  `raw_tag` varchar(64) NOT NULL COMMENT '去除干扰符号后的最简标签',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




-- 通用标签标记表
-- @since 2016-05-13
-- conoha 未同步
-- Office localhost 未同步
-- HP localhost 已同步
CREATE TABLE `dm_tagged` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tag_id` bigint(20) NOT NULL COMMENT '标签ID',
  `obj_id` bigint(20) NOT NULL COMMENT '被关联的表ID',
  `obj_name` varchar(64) NOT NULL COMMENT '被关联的表名（一般不记前缀）',
  `settime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '标记时间',
  `setuid` bigint(20) NOT NULL DEFAULT '0' COMMENT '操作者uid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




-- 通用标签关系表
-- @since 2016-05-13
-- conoha 未同步
-- Office localhost 未同步
-- HP localhost 已同步
CREATE TABLE `dm_tag_relate` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` bigint(20) NOT NULL COMMENT '标签ID',
  `reltag_id` varchar(32) NOT NULL COMMENT '关联标签ID',
  `relation` tinyint(4) NOT NULL DEFAULT '0' COMMENT '关联关系：0-相似关系；1-父到子关系',
  `settime` int(11) NOT NULL,
  `setuid` bigint(20) NOT NULL DEFAULT '0' COMMENT '设置者：0为系统自动关联',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


