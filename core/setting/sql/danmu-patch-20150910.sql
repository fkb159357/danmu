-- 图片表增加字段：sha1,hide,clear
-- @since 2015-09-10
-- hostker(miku.us) 已同步
-- Office localhost 已同步
-- HP localhost 已同步
ALTER TABLE `dm_tu`
ADD COLUMN `sha1`  varchar(50) NOT NULL DEFAULT '' AFTER `url`,
ADD COLUMN `hide`  tinyint NOT NULL DEFAULT 0 COMMENT '是否隐藏' AFTER `sha1`,
ADD COLUMN `clear`  tinyint NOT NULL DEFAULT 0 COMMENT '是否已被清除源图' AFTER `hide`;
