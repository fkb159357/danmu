-- ----------------------------
-- 5sing单曲库
-- @since 2015-01-26
-- HP localhost 已同步
-- Office localhost 已同步
-- heng_mikuus 已同步
-- ----------------------------
DROP TABLE IF EXISTS `dm_audio5sing`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;