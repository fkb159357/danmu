-- Table structure for `dm_rootist` ROOTER访问记录
-- @since 2015-01-01
-- Office localhost 已同步
-- heng_mikuus 已同步
-- HP localhost 已同步
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

