
-- Adminer 4.3.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE IF NOT EXISTS `danmu` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `danmu`;

CREATE TABLE `di_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='示例表，没什么用。。';


CREATE TABLE `dm_audio5sing` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `songid` varchar(20) DEFAULT NULL COMMENT '5sing单曲id',
  `songtype` varchar(10) DEFAULT NULL COMMENT '翻唱fc, 原唱yc',
  `songname` varchar(60) DEFAULT NULL,
  `file` varchar(100) DEFAULT NULL COMMENT '下载地址',
  `singerid` varchar(20) DEFAULT NULL,
  `singer` varchar(60) DEFAULT NULL COMMENT '昵称',
  `avatar` varchar(100) DEFAULT NULL COMMENT '头像',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `dm_danmu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topic` int(11) NOT NULL DEFAULT '0' COMMENT '所属专题，默认没有从属',
  `vname` varchar(100) DEFAULT NULL COMMENT '视频名称',
  `v_url` varchar(512) NOT NULL COMMENT '视频地址',
  `d_url` varchar(512) NOT NULL COMMENT '弹幕地址',
  `player` int(11) NOT NULL DEFAULT '0' COMMENT '播放器代号',
  `skin` int(11) NOT NULL DEFAULT '0' COMMENT '皮肤代号',
  `ubb` varchar(1024) DEFAULT NULL COMMENT '生成的UBB代码',
  `html` varchar(1024) DEFAULT NULL COMMENT '生成播放的html代码',
  `uid` int(11) DEFAULT '0' COMMENT '创建人',
  `cretime` int(11) DEFAULT NULL COMMENT '创建时间',
  `modtime` int(11) DEFAULT NULL COMMENT '修改时间',
  `createip` varchar(16) DEFAULT NULL COMMENT '创建IP',
  `hide` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='杂项配置';


CREATE TABLE `dm_rooter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `passport` varchar(20) NOT NULL COMMENT '通行证',
  `first_ip` varchar(16) NOT NULL DEFAULT '',
  `last_ip` varchar(255) NOT NULL DEFAULT '',
  `first_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `dm_rootist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(16) NOT NULL COMMENT '访问IP',
  `vtime` int(11) NOT NULL COMMENT '访问时间',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id，如果有则记录',
  `is_exception` tinyint(4) NOT NULL DEFAULT '0' COMMENT '异常访问记录(非法进入等原因)',
  `country` varchar(50) DEFAULT NULL COMMENT '国别',
  `province` varchar(50) DEFAULT NULL COMMENT '省份',
  `city` varchar(50) DEFAULT NULL COMMENT '城市',
  `isp` varchar(50) DEFAULT NULL COMMENT 'ISP提供商',
  `ip_desc` varchar(50) DEFAULT NULL COMMENT '新浪返回的IP描述',
  `ip_type` varchar(50) DEFAULT NULL COMMENT '新浪返回的IP类型',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE,
  KEY `ip` (`ip`) USING BTREE,
  KEY `vtime` (`vtime`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `dm_tag` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tag` varchar(64) NOT NULL COMMENT '标签',
  `pure_tag` varchar(64) NOT NULL COMMENT '去除干扰字符后的纯标签',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqtag` (`tag`,`pure_tag`) USING BTREE,
  KEY `tag` (`tag`) USING BTREE,
  KEY `pure_tag` (`pure_tag`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='通用标签表\r\ntag：未经去除特殊字符的标签，其本身可能也是个纯标签。\r\npure_tag：经过去除特殊字符后的纯标签。\r\ntag与纯标签呈多对多关系：tag本身可能会分解出多个pure_tag，pure_tag可能会找到所属的tag。\r\n由于没必要保存重复的组合，故tag与pure_tag组合唯一。\r\n';


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='通用标签标记表';


CREATE TABLE `dm_tag_relate` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` bigint(20) NOT NULL COMMENT '标签ID',
  `reltag_id` varchar(32) NOT NULL COMMENT '关联标签ID',
  `relation` tinyint(4) NOT NULL DEFAULT '0' COMMENT '关联关系：0-相似关系；1-父到子关系',
  `settime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `setuid` bigint(20) NOT NULL DEFAULT '0' COMMENT '设置者：0为系统自动关联',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqrel` (`tag_id`,`reltag_id`) USING BTREE,
  KEY `tag_id` (`tag_id`) USING BTREE,
  KEY `reltag_id` (`reltag_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='通用标签关系表';


CREATE TABLE `dm_topic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tname` varchar(30) NOT NULL COMMENT '专题名',
  `desc` varchar(512) NOT NULL COMMENT '描述',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '创建人',
  `time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `dm_tourist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(16) NOT NULL COMMENT '访问IP',
  `vtime` int(11) NOT NULL COMMENT '访问时间',
  `country` varchar(50) DEFAULT NULL COMMENT '国别',
  `province` varchar(50) DEFAULT NULL COMMENT '省份',
  `city` varchar(50) DEFAULT NULL COMMENT '城市',
  `isp` varchar(50) DEFAULT NULL COMMENT 'ISP提供商',
  `ip_desc` varchar(50) DEFAULT NULL COMMENT '新浪返回的IP描述',
  `ip_type` varchar(50) DEFAULT NULL COMMENT '新浪返回的IP类型',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `dm_tu` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(128) NOT NULL DEFAULT '' COMMENT '客户端文件命名',
  `filepath` varchar(320) NOT NULL DEFAULT '' COMMENT '过时，被fullfile替代',
  `fileext` varchar(20) NOT NULL DEFAULT '' COMMENT '文件扩展名',
  `mimetype` varchar(20) NOT NULL DEFAULT '' COMMENT '媒体类型',
  `filesize` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `width` int(10) unsigned NOT NULL DEFAULT '0',
  `height` int(10) unsigned NOT NULL DEFAULT '0',
  `savefile` varchar(128) NOT NULL DEFAULT '' COMMENT '实际存储文件名，如201508.jpg',
  `savedir` varchar(320) NOT NULL DEFAULT '' COMMENT '实际存储文件相对WEB根目录路径，如res/img/default',
  `fullfile` varchar(320) NOT NULL DEFAULT '' COMMENT '实际存储文件的文件全路径，如/home/wwwroot/res.miku.us/res/img/default/201508.jpg',
  `fulldir` varchar(320) NOT NULL DEFAULT '' COMMENT '实际存储文件的目录全路径，如/home/wwwroot/res.miku.us/res/img/default',
  `url` varchar(256) NOT NULL DEFAULT '',
  `sha1` varchar(50) NOT NULL DEFAULT '',
  `hide` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `clear` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否已被清除源图',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '上传时间',
  `upuid` int(11) NOT NULL DEFAULT '0' COMMENT '上传者',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `dm_tu_tag` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tu_id` bigint(20) NOT NULL,
  `tag` varchar(32) NOT NULL,
  `settime` int(11) NOT NULL COMMENT '打标签时间',
  `setuid` bigint(20) NOT NULL DEFAULT '0' COMMENT '打标签者',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tu_tag` (`tu_id`,`tag`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `dm_tu_tag_relate` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(32) NOT NULL,
  `relation` tinyint(4) NOT NULL DEFAULT '0' COMMENT '关联关系：0-无关联；1-桥接关系；2-相似关系；3-父节点为对方；4-子节点为对方',
  `relate_tag` varchar(32) NOT NULL,
  `settime` int(11) NOT NULL,
  `setuid` bigint(20) NOT NULL DEFAULT '0' COMMENT '设置者：0为系统自动关联',
  PRIMARY KEY (`id`),
  UNIQUE KEY `relate` (`tag`,`relation`,`relate_tag`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `dm_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `passport` varchar(20) NOT NULL COMMENT '登录名',
  `nickname` varchar(20) NOT NULL COMMENT '显示昵称',
  `password` varchar(50) NOT NULL COMMENT '登录密码',
  `forum_name` varchar(20) DEFAULT NULL COMMENT '所属论坛名',
  `forum_section` varchar(20) DEFAULT NULL COMMENT '所属板块名',
  `forum_id` int(11) DEFAULT NULL COMMENT '所属论坛id',
  `forum_sectionid` varchar(10) DEFAULT NULL COMMENT '所属板块id，如2-5表示“论坛id2版块id5”',
  `forum_url` varchar(25) DEFAULT NULL,
  `qq` varchar(15) DEFAULT NULL,
  `weibo` varchar(25) DEFAULT NULL COMMENT '微博昵称',
  `weibo_url` varchar(25) DEFAULT NULL COMMENT '微博主页URL',
  `regtime` int(11) NOT NULL COMMENT '注册时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2024-11-16 10:27:39
