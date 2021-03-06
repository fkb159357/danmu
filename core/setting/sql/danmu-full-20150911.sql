-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 09 月 10 日 02:36
-- 服务器版本: 5.5.16
-- PHP 版本: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `danmu`
--

-- --------------------------------------------------------

--
-- 表的结构 `dm_audio5sing`
--

DROP TABLE IF EXISTS `dm_audio5sing`;
CREATE TABLE IF NOT EXISTS `dm_audio5sing` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `songid` varchar(20) DEFAULT NULL COMMENT '5sing单曲id',
  `songtype` varchar(10) DEFAULT NULL COMMENT '翻唱fc, 原唱yc',
  `songname` varchar(60) DEFAULT NULL,
  `file` varchar(100) DEFAULT NULL COMMENT '下载地址',
  `singerid` varchar(20) DEFAULT NULL,
  `singer` varchar(60) DEFAULT NULL COMMENT '昵称',
  `avatar` varchar(100) DEFAULT NULL COMMENT '头像',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- 表的结构 `dm_danmu`
--

DROP TABLE IF EXISTS `dm_danmu`;
CREATE TABLE IF NOT EXISTS `dm_danmu` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- 表的结构 `dm_rooter`
--

DROP TABLE IF EXISTS `dm_rooter`;
CREATE TABLE IF NOT EXISTS `dm_rooter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `passport` varchar(20) NOT NULL COMMENT '通行证',
  `first_ip` varchar(16) NOT NULL DEFAULT '',
  `last_ip` varchar(255) NOT NULL DEFAULT '',
  `first_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- 表的结构 `dm_rootist`
--

DROP TABLE IF EXISTS `dm_rootist`;
CREATE TABLE IF NOT EXISTS `dm_rootist` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- 表的结构 `dm_topic`
--

DROP TABLE IF EXISTS `dm_topic`;
CREATE TABLE IF NOT EXISTS `dm_topic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tname` varchar(30) NOT NULL COMMENT '专题名',
  `desc` varchar(512) NOT NULL COMMENT '描述',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '创建人',
  `time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- 表的结构 `dm_tourist`
--

DROP TABLE IF EXISTS `dm_tourist`;
CREATE TABLE IF NOT EXISTS `dm_tourist` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- 表的结构 `dm_tu`
--

DROP TABLE IF EXISTS `dm_tu`;
CREATE TABLE IF NOT EXISTS `dm_tu` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(128) NOT NULL,
  `filepath` varchar(320) NOT NULL,
  `fileext` varchar(20) NOT NULL,
  `mimetype` varchar(20) NOT NULL,
  `filesize` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `width` int(10) unsigned NOT NULL DEFAULT '0',
  `height` int(10) unsigned NOT NULL DEFAULT '0',
  `url` varchar(256) NOT NULL,
  `uptime` int(11) NOT NULL COMMENT '上传时间',
  `upuid` int(11) NOT NULL DEFAULT '0' COMMENT '上传者',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- 表的结构 `dm_tu_tag`
--

DROP TABLE IF EXISTS `dm_tu_tag`;
CREATE TABLE IF NOT EXISTS `dm_tu_tag` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tu_id` bigint(20) NOT NULL,
  `tag` varchar(32) NOT NULL,
  `settime` int(11) NOT NULL COMMENT '打标签时间',
  `setuid` bigint(20) NOT NULL DEFAULT '0' COMMENT '打标签者',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tu_tag` (`tu_id`,`tag`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- 表的结构 `dm_tu_tag_relate`
--

DROP TABLE IF EXISTS `dm_tu_tag_relate`;
CREATE TABLE IF NOT EXISTS `dm_tu_tag_relate` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(32) NOT NULL,
  `relation` tinyint(4) NOT NULL DEFAULT '0' COMMENT '关联关系：0-无关联；1-桥接关系；2-相似关系；3-父节点为对方；4-子节点为对方',
  `relate_tag` varchar(32) NOT NULL,
  `settime` int(11) NOT NULL,
  `setuid` bigint(20) NOT NULL DEFAULT '0' COMMENT '设置者：0为系统自动关联',
  PRIMARY KEY (`id`),
  UNIQUE KEY `relate` (`tag`,`relation`,`relate_tag`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- 表的结构 `dm_user`
--

DROP TABLE IF EXISTS `dm_user`;
CREATE TABLE IF NOT EXISTS `dm_user` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
