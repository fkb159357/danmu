-- Table structure for `dm_tourist` 游客表
-- @since 2015-01-01
-- Office localhost 已同步
-- HP localhost 已同步
-- heng_mikuus 已同步
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
