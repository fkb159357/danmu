-- 通用标签表
-- @since 2016-05-13
-- hostker 已同步
-- Office localhost 未同步
-- HP localhost 已同步
CREATE TABLE `dm_tag` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tag` varchar(64) NOT NULL COMMENT '标签',
  `pure_tag` varchar(64) NOT NULL COMMENT '去除干扰字符后的纯标签',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqtag` (`tag`,`pure_tag`) USING BTREE,
  KEY `tag` (`tag`) USING BTREE,
  KEY `pure_tag` (`pure_tag`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='通用标签表\r\ntag：未经去除特殊字符的标签，其本身可能也是个纯标签。\r\npure_tag：经过去除特殊字符后的纯标签。\r\ntag与纯标签呈多对多关系：tag本身可能会分解出多个pure_tag，pure_tag可能会找到所属的tag。\r\n由于没必要保存重复的组合，故tag与pure_tag组合唯一。\r\n';



-- 通用标签标记表
-- @since 2016-05-13
-- hostker 已同步
-- Office localhost 未同步
-- HP localhost 已同步
CREATE TABLE `dm_tagged` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tag_id` bigint(20) NOT NULL COMMENT '标签ID',
  `tab_id` bigint(20) NOT NULL COMMENT '被关联的表ID',
  `tab_name` varchar(64) NOT NULL COMMENT '被关联的表名（一般不记前缀）',
  `settime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '标记时间',
  `setuid` bigint(20) NOT NULL DEFAULT '0' COMMENT '操作者uid',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqtagged` (`tag_id`,`tab_id`,`tab_name`) USING BTREE,
  KEY `tag_id` (`tag_id`) USING BTREE,
  KEY `tab_id` (`tab_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='通用标签标记表';



-- 通用标签关系表
-- @since 2016-05-13
-- hostker 已同步
-- Office localhost 未同步
-- HP localhost 已同步
CREATE TABLE `dm_tag_relate` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` bigint(20) NOT NULL COMMENT '标签ID',
  `reltag_id` varchar(32) NOT NULL COMMENT '关联标签ID',
  `relation` tinyint(4) NOT NULL DEFAULT '0' COMMENT '关联关系：0-相似关系；1-父到子关系',
  `settime` int(11) NOT NULL,
  `setuid` bigint(20) NOT NULL DEFAULT '0' COMMENT '设置者：0为系统自动关联',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqrel` (`tag_id`,`reltag_id`) USING BTREE,
  KEY `tag_id` (`tag_id`) USING BTREE,
  KEY `reltag_id` (`reltag_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='通用标签关系表';

