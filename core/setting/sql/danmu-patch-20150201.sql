-- Table structure for `dm_rootist` ROOTER访问记录
-- @since 2015-02-01
-- heng_mikuus 已同步
-- HP localhost 已同步
-- Office localhost 已同步
ALTER TABLE `dm_user`
MODIFY COLUMN `password` varchar(50) NOT NULL COMMENT '登录密码' AFTER `nickname`;
