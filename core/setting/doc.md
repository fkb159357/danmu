5sing单曲接口1(四种调用方式，支持jsonp)
http://miku.us/?audio/parse5sing/{$songID}/{$songType}
http://miku.us/?x=audio/parse5sing/{$songID}/{$songType}
http://miku.us/?audio/parse5sing&addr=播放地址
http://miku.us/?x=audio/parse5sing&addr=播放地址
参数说明：
前两种直接替换{$xxx}部分为具体值即可，
$songID是单曲id，songType是歌曲类型（可选参数），5sing的有fc 翻唱、 yc原创、。。。
例如：
1、http://miku.us/?audio/parse5sing&addr=http%3a%2f%2f5sing.kugou.com%2ffc%2f13755262.html
2、http://miku.us/?audio/parse5sing/13755262
3、http://miku.us/?audio/parse5sing/13755262/fc

发现后面接口都不太好解，5sing那个也藏得很深，用jsonp加载的。。
5sing源码里的listen.min.js看起来像是开源社区的库，其实就是暗藏音乐加载的代码，
还居然设置mimetype为非js的防止chrome端调试。


5sing单曲接口二：http://miku.us/?audio/parse5sing2  使用方法同接口1