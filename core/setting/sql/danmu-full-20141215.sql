/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50520
Source Host           : localhost:3306
Source Database       : danmu

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2014-12-15 04:50:15
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `dm_danmu`
-- ----------------------------
DROP TABLE IF EXISTS `dm_danmu`;
CREATE TABLE `dm_danmu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topic` int(11) NOT NULL DEFAULT '0' COMMENT '所属专题，默认没有从属',
  `vname` varchar(100) NOT NULL COMMENT '视频名称',
  `v_url` varchar(512) NOT NULL COMMENT '视频地址',
  `d_url` varchar(512) NOT NULL COMMENT '弹幕地址',
  `player` int(11) NOT NULL COMMENT '播放器代号',
  `skin` int(11) DEFAULT NULL COMMENT '皮肤代号',
  `ubb` varchar(1024) NOT NULL COMMENT '生成的UBB代码',
  `html` varchar(1024) NOT NULL COMMENT '生成播放的html代码',
  `uid` int(11) DEFAULT '0' COMMENT '创建人',
  `cretime` int(11) DEFAULT NULL COMMENT '创建时间',
  `modtime` int(11) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dm_danmu
-- ----------------------------

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
-- Records of dm_topic
-- ----------------------------

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
-- Records of dm_user
-- ----------------------------