-- @since 2014-12-30
-- Office localhost 已同步
-- heng_mikuus 已同步
-- HP localhost 已同步
ALTER TABLE `dm_danmu`
MODIFY COLUMN `vname`  varchar(100) NULL COMMENT '视频名称' ,
MODIFY COLUMN `player`  int(11) NOT NULL DEFAULT 0 COMMENT '播放器代号' ,
MODIFY COLUMN `skin`  int(11) NOT NULL DEFAULT 0 COMMENT '皮肤代号',
MODIFY COLUMN `ubb`  varchar(1024) NULL COMMENT '生成的UBB代码',
MODIFY COLUMN `html`  varchar(1024) NULL COMMENT '生成播放的html代码';
