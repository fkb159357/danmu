if [ ! -d "/home/wwwroot" ]; then
    mkdir /home/wwwroot;
fi;
if [ ! -d "/home/wwwbackup" ]; then
    mkdir /home/wwwbackup;
fi;
zip -r /home/wwwbackup/danmu.zip /home/wwwroot/danmu/*


if [ ! -d "/home/wwwsrc" ]; then
    mkdir /home/wwwsrc;
fi;

if [ ! -d "/home/wwwsrc/danmu" ]; then
    cd /home/wwwsrc;
    git clone https://github.com/fkb159357/danmu.git;
else
    cd /home/wwwsrc/danmu;
    git pull;
fi

if [ ! -d "/home/wwwroot/danmu" ]; then
    mkdir /home/wwwroot/danmu;
fi

if [ ! -d "/home/wwwroot/danmu/core" ]; then
    mkdir /home/wwwroot/danmu/core;
fi

if [ ! -d "/home/wwwroot/danmu/core/data" ]; then
    mkdir /home/wwwroot/danmu/core/data;
fi

# 可能不需要（去掉也不会出错）
if [ ! -d "/home/wwwroot/danmu/core/data/cache" ]; then
    mkdir /home/wwwroot/danmu/core/data/cache;
fi

# 可能不需要（去掉也不会出错）
if [ ! -d "/home/wwwroot/danmu/core/data/log" ]; then
    mkdir /home/wwwroot/danmu/core/data/log;
fi


mv /home/wwwroot/danmu /home/wwwroot/danmu.trash;
cp /home/wwwsrc/danmu -r /home/wwwroot/danmu;
rm /home/wwwroot/danmu/.git -rf
rm /home/wwwroot/danmu/core/data -rf
cp -r /home/wwwroot/danmu.trash/core/data /home/wwwroot/danmu/core/
chmod -R 767 /home/wwwroot/danmu/core/data
chmod -R 767 /home/wwwroot/danmu/core/let # 如需支持LET，则保留
chmod -R 767 /home/wwwroot/danmu/res/biz/danmu/player # 弹幕播放器特有，业务相关
chmod +x /home/wwwroot/danmu/core/setting/gitpull.sh;
rm -f -r /home/wwwroot/danmu.trash;

cd /home/wwwroot

service nginx restart
service php-fpm reload
