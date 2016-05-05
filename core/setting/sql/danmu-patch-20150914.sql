-- 图片表规范存储相关的字段，使用savefile, savedir, fullfile, fulldir
-- @since 2015-09-14
-- hostker(miku.us) 已同步
-- Office localhost 已同步
-- HP localhost 已同步
ALTER TABLE `dm_tu`
MODIFY COLUMN `filename`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '客户端文件命名' AFTER `id`,
MODIFY COLUMN `filepath`  varchar(320) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '过时，被fullfile替代' AFTER `filename`,
MODIFY COLUMN `fileext`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文件扩展名' AFTER `filepath`,
MODIFY COLUMN `mimetype`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '媒体类型' AFTER `fileext`,
MODIFY COLUMN `url`  varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `height`,
MODIFY COLUMN `uptime`  int(11) NOT NULL DEFAULT 0 COMMENT '上传时间' AFTER `clear`,
ADD COLUMN `savefile`  varchar(128) NOT NULL DEFAULT '' COMMENT '实际存储文件名，如201508.jpg' AFTER `height`,
ADD COLUMN `savedir`  varchar(320) NOT NULL DEFAULT '' COMMENT '实际存储文件相对WEB根目录路径，如res/img/default' AFTER `savefile`,
ADD COLUMN `fullfile`  varchar(320) NOT NULL DEFAULT '' COMMENT '实际存储文件的文件全路径，如/home/wwwroot/res.miku.us/res/img/default/201508.jpg' AFTER `savedir`,
ADD COLUMN `fulldir`  varchar(320) NOT NULL DEFAULT '' COMMENT '实际存储文件的目录全路径，如/home/wwwroot/res.miku.us/res/img/default' AFTER `fullfile`;
