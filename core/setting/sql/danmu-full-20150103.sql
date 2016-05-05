/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50520
Source Host           : localhost:3306
Source Database       : danmu

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2014-12-31 00:25:23
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `dm_danmu`
-- ----------------------------
DROP TABLE IF EXISTS `dm_danmu`;
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `dm_topic`
-- ----------------------------
DROP TABLE IF EXISTS `dm_topic`;
CREATE TABLE `dm_topic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tname` varchar(30) NOT NULL COMMENT '专题名',
  `desc` varchar(512) NOT NULL COMMENT '描述',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '创建人',
  `time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `dm_user`
-- ----------------------------
DROP TABLE IF EXISTS `dm_user`;
CREATE TABLE `dm_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `passport` varchar(20) NOT NULL COMMENT '登录名',
  `nickname` varchar(20) NOT NULL COMMENT '显示昵称',
  `password` varchar(30) NOT NULL COMMENT '登录密码',
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `dm_tourist` 游客表
-- ----------------------------
DROP TABLE IF EXISTS `dm_tourist`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `dm_rootist` ROOTER管理端访问记录
-- ----------------------------
DROP TABLE IF EXISTS `dm_rootist`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

