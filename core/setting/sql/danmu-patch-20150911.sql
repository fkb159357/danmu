-- 弹幕视频表增加字段：hide
-- @since 2015-09-11
-- hostker(miku.us) 已同步
-- Office localhost 已同步
-- HP localhost 已同步
ALTER TABLE `dm_danmu`
ADD COLUMN `hide`  tinyint NOT NULL DEFAULT 0 COMMENT '是否隐藏' AFTER `createip`;
