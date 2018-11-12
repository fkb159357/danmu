<?php
/**
 * 与弹幕视频相关
 * @author 折木さん　<ltrele@gmail.com>
 * @link http://larele.com
 * @link http://miku.us
 * @link http://ltre.cc
 * @link http://ltre.me
 * @link http://ltre.xyz
 * @link http://xmiku.cc
 * @link http://emiku.cc
 */
class DanmuDo extends TemplateDo {
    
    //首页
    function start(){
        die('404 - HTTP Broken Shoes');

        import('net/ip');
        $ip = Api_ip::get_client_ip();
        //$ip = '119.129.211.107';//test
        $re='((?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))(?![\\d])';// IPv4 IP Address
        $isip = preg_match("/{$re}/is", $ip);
        if (! $isip) {
            $this->gen();//IP有误，放弃后续检测，直接加载页面
            exit;
        }
        
        $M = supertable('Tourist');
        $tourist = $M->find(compact('ip')) ?: array();
        //防止本地测试时一直都是在启动页不跳转
        if ($ip == '127.0.0.1') {
            false === $tourist ? $M->insert(array('ip'=>$ip,'vtime'=>time())) : $M->update(compact('ip'), array('vtime'=>time()));
        }
        
        $vtime = empty($tourist) ? false : $tourist['vtime'];
        if ($vtime >= strtotime(date('Y-m-d'))-1209600) {
            $this->gen();
        } else {
            $this->client_ip = $ip;//提供IP给danmu-start.php使用
            parent::tpl('danmu-start');//不使用danmu-tpl模板
        }
    }
    
    
    /**
     * 1、根据弹幕、视频地址生成UBB 
     * 2、将生成的视频名称，原始弹幕地址、原始视频地址、UBB代码、创建人、创建时间、皮肤（配置array映射）、播放器（配置array映射） 录入数据库。 
     * 3、请求gen()时，服务器返回处理状态（参数不正确、生成出错、生成成功） 
     * 4、客户端直接重载FLASH代码部分
     * @author ltre
     * @since 2014-7-10
     */
    function gen(){
        if (empty($_POST)) {
            $this->tpl();
        }
        
        foreach (array('vname', 'v_url', 'd_url', 'player', 'skin', 'fontsize') as $a) {
            $$a = isset($_POST[$a]) ? $_POST[$a] : '';
            $$a = htmlspecialchars($$a);
        }
        (int)$player || $player = 3;
        (int)$skin || $skin = 0;
        (int)$fontsize || $fontsize = 18;
        $topic = 0;
        if (session_exists(DM_SESSION_MY)) {
            $sessionMy = session(DM_SESSION_MY);
            $uid = $sessionMy['id'];
        } else {
            $uid = 0;
        }
        //$uid = session_exists(DM_SESSION_MY) ? session(DM_SESSION_MY)->id : 0;
        $cretime = time();
        $createip = getip();
        
        $tips = array();
        $flag = true;
        if (strlen(urlencode($vname)) / 3 > 100) {
            $flag = false;
            $tips[] = '标题名称过长';
        }
        // '/^http\:\/\/(.+\.)+.+(\/.+)+\.(flv|mp4|letv|hlv|avi)$/'
        // '/^http\:\/\/(.+\.)*.+(\/.+)+\.(flv|mp4|letv|hlv|avi)$/'
        if (!preg_match('/^http\:\/\/.+\.(flv|mp4|letv|hlv|avi)$/', $v_url)) {
            $flag = false;
            $tips[] = '视频地址格式有误，正确示例http://xx.xxx/xx.mp4|flv|letv|hlv|avi';
        }
        // '/^http\:\/\/(.+\.)+.+(\/.+)+\.(xml|json|ass)$/'
        // '/^http\:\/\/(.+\.)*.+(\/.+)+\.(xml|json|ass)$/'
        if (!empty($d_url) && !preg_match('/^http\:\/\/.+\.(xml|json|ass)$/', $d_url)) {
            $flag = false;
            $tips[] = '弹幕地址格式有误，正确示例http://xx.xxx/xx.xml|json|ass';
        }
        
        if (!empty($tips)) {
            putjson(0, null, join('；', $tips));
        }
        
        import('net/ip');
        $nameArr = array('某UP', '懒货UP', '二货UP', '闲人UP', '阿卡林', '印度阿三', '唐僧', '元首', '小黄', '德国BOY', '王尼玛', 'AC娘', '比利海灵顿', '脱裤哥', '100块小红帽', '瓜子哥', '井叉蜀黍', '劳资', '菊花的表哥', '金坷垃');
        $preName = $nameArr[rand(0, count($nameArr)-1)];
        ! $vname && $vname = $preName . ' (' . Api_ip::get_client_ip() . ') 于 [' . date('Y-m-d H:i:s') . '] 生成';
        $id = supertable('Danmu')->insert(compact('v_url', 'd_url', 'player', 'skin', 'vname', 'topic', 'uid', 'cretime', 'createip'));
        
        if (!is_numeric($id)) {
            putjson(0, null, '生成失败');
        }
        
        if (3 == $player) {
            $ret = Danmu::ubb3($id, $fontsize);
        } elseif (2 == $player) {
            $ret = Danmu::ubb2($id);
        } else {
            $ret = Danmu::ubb1($id, $fontsize);
        }
        
        if (false === $ret) {
            putjson(0, null, '数据格式错误，生成失败');
        } else {
            $ret['spareSwfFile'] = Danmu::players(2);//弹幕地址与swf地址无法联通时备用，详见danmu.gen() @ danmu.js
            putjson(1, $ret, '生成成功');
        }
    }
    
    
    /**
     * 播放页，需要参数-弹幕id
     * 当指定字体大小时，会自动更新弹幕地址到数据库
     * @author ltre
     * @since 2014-8-16
     */
    function play($id, $fontsize = 18){
        $id = intval($id);
        if (empty($id)) {
            dispatch(DI_PAGE_404);
        }
        $conds = compact('id');
        if (! User::isLogin()) $conds['hide'] = 0;//游客不能看隐藏内容
        $danmu = DIModelUtil::supertable('Danmu')->find($conds) ?: array();
        if (empty($danmu)) {
            dispatch(DI_PAGE_404);
        }
        
        $danmu['swf'] = preg_replace(array('/\[flash=\d{1,}\,\d{1,}\]/', '/\[\/flash\]/'), '', $danmu['ubb']);
        $args = func_get_args();
        if (empty($danmu['swf']) || isset($args[1])) {
            $fontsize < 5 && $fontsize = 18;//防止字体过小
            if (3 == $danmu->player) {
                $ret = Danmu::ubb3($id, $fontsize);
            } elseif (2 == $danmu->player) {
                $ret = Danmu::ubb2($id, $fontsize);
            } else {
                $ret = Danmu::ubb1($id, $fontsize);
            }
            
            if (false === $ret) {
                $danmu['swf'] = '';//数据格式错误，生成失败
            } else {
                $danmu['ubb'] = $ret['ubb'];
                $danmu['swf'] = $ret['swf'];
            }
        }
        
        $this->danmu = $danmu;
        $this->tpl();
    }
    
    /**
     * 更新弹幕信息，仅需要GET一个id，和POST一个参数数组
     * @author ltre
     * @since 2014-10-20
     */
    function mod(){
        $vs = array('id', 'vname', 'v_url', 'd_url', 'player', 'skin', 'fontsize');
        foreach ($vs as $v) {
            $$v = arg($v);
        }
        (int)$player || $player = 3;
        (int)$skin || $skin = 0;
        (int)$fontsize || $fontsize = 18;
        $modtime = time();
        
        $D = supertable('Danmu');
        $danmu = $D->find(compact('id')) ?: array();
        if (! $danmu) putjson(0, null, '内容不存在');
        if (!(@$my = User::isLogin())) putjson(0, null, '登录后才能修改');
        file_put_contents(DI_LOG_PATH.'debug.txt', var_export(array($danmu, $my), true));
        !! $danmu['uid'] && $danmu['uid'] != $my['id'] && putjson(0, null, '你只能修改自己的');
        
        $D->update(compact('id'), compact('vname', 'v_url', 'd_url', 'player', 'skin', 'modtime'));
        if (3 == $player) {
            $ret = Danmu::ubb3($id, $fontsize);
        } elseif (2 == $player) {
            $ret = Danmu::ubb2($id, $fontsize);
        } else {
            $ret = Danmu::ubb1($id, $fontsize);
        }
        
        if (false === $ret) {
            putjson(0, $ret, '修改失败');
        }
        
        putjson(1, $ret, '修改成功');
    }
    
    /**
     * 仅用一个关键字搜索弹幕，返回分页结果。
     * 没有关键字时，选取对象为全表
     * @param int $page 当前页码
     * @param int $persize 每页记录条数限额
     * @param int $pagescope 页码范围大小
     * @param string $output 结果输出方式
     *      tpl     模板输出
     *      json    json输出
     *      return  不输出，只取返回值
     * @param string arg(u) 值为my时，取自己列表
     * @author ltre
     * @since 2014-9-8
     */
    function search($keyword='%', $page=1, $persize=10, $pagescope=15, $output='tpl'){
        $conds = array(
        	'id' => "%{$keyword}%",
            'vname' => "%{$keyword}%",
        );
        
        $this->isme = false;//确认当前用户是否进入本人列表
        $elseconds = " 1=1 ";
        $mySession = User::isLogin();
        if (! $mySession) $elseconds .= " AND `hide` = 0 ";//游客不能看隐藏内容
        if ('my' == arg('u')) {
            if (! $mySession) {
                'tpl' == $output && dispatch('user/loginView');//未登录直接重新分派
                'json' == $output && putjson(0, null, '你还没打卡呢');
                if ('return' == $output) return false; 
            } else {
                $this->isme = true;//确认当前用户进入本人列表
                $elseconds .= " AND `uid` = '{$mySession['id']}' ";
            }
        }
        
        $D = DIModelUtil::supertable('Danmu');
        $count_sql = "SELECT COUNT(*) AS cnt FROM {$D->table}
            WHERE (`id` LIKE :id OR `vname` LIKE :vname) AND ({$elseconds})";
        $count_rs = $D->query($count_sql, $conds) ?: array();
        $count = !empty($count_rs) ? $count_rs[0]['cnt'] : false;
        
        $danmus = array();
        $pager = $D->pager(1, 10, 10, 0);
        if (!empty($count)) {
            $pager = $D->pager($page, $persize, $pagescope, $count);
            $limit = $pager['limit'];
            $offset = $pager['offset'];
            $presql = "SELECT * FROM {$D->table} 
                WHERE (`id` LIKE :id OR `vname` LIKE :vname) AND ({$elseconds})
                ORDER BY `cretime` DESC 
                LIMIT {$offset}, {$limit} ";
            $danmus = $D->query($presql, $conds) ?: array();
        }

        foreach ($danmus as &$d) {
            $d['link'] = DI_URL_PREFIX . 'av' . $d['id'];
            $d['firstword'] = Danmu::getFaceWord($d['vname']);
        }
 
        if ('return' == $output) {
            return compact('danmus', 'pager');
        } elseif ('json' == $output) {
            putjson(1, compact('danmus', 'pager'));
        } else {
            $this->danmus = $danmus;
            $this->pager = $pager;
            $this->keyword = $keyword;
            $this->persize = $persize;
            $this->pagescope = $pagescope;
            $this->tpl();
        }
    }
    
    /**
     * 删除弹幕，返回json
     * @author ltre
     * @since 2014-10-12
     */
    function del($id){
        $id = intval($id);
        if (empty($id)) {
            dispatch(DI_PAGE_404);
        }
        
        if (!(@$my = User::isLogin())) putjson(0, null, '登录后才能删除');
        $danmu = supertable('Danmu')->find(compact('id')) ?: array();
        if (! $danmu) putjson(0, null, '内容不存在');
        $danmu['uid'] != $my['id'] && putjson(0, null, '你只能删除自己的');
        
        
        $opt = DIModelUtil::supertable('Danmu')->delete(compact('id'));
        if (false === $opt) {
            putjson(0, null, '删除失败');
        }
        
        putjson(1, compact('opt'), '删除成功');
    }
    
    /**
     * 加载弹幕
     * 链接在conf.xml中配置http://server/?danmu/load/{$id}
     * swf的id传入形如“1/18”即可
     * @author ltre
     * @since 2014-12-30
     */
    function load($id, $fontsize = 18){
        $d = DIModelUtil::supertable('Danmu')->find(compact('id')) ?: array();
        if (empty($d['d_url']) || !preg_match('/^http\:\/\/.+\.(xml|json|ass)$/', $d['d_url'])) {
            $flag = false;
            $d['d_url'] = 'http://comment.bilibili.com/73345.xml';
        }
        
        $d_local = preg_match('/(127\.0\.0\.\d{1,3}|localhost)+/', $d['d_url']);
        $d_172_192 = preg_match('/(172\.\d{1,3}\.\d{1,3}\.\d{1,3}|192\.168\.\d{1,3}\.\d{1,3})+/', $d['d_url']);
        $host_local = preg_match('/127\.0\.0\.\d{1,3}|localhost/', $_SERVER['HTTP_HOST']);
        $host_172_192 = preg_match('/172\.\d{1,3}\.\d{1,3}\.\d{1,3}|192\.168\.\d{1,3}\.\d{1,3}/', $_SERVER['HTTP_HOST']);
        if ($d_local&&!$host_local || $d_172_192&&!$host_local&&!$host_172_192) {
            header("Location: {$d['d_url']}");
            exit;
        }

        $xml = Danmu::curlxml($d['d_url']);
        $xml = Danmu::modFontsize($xml, $fontsize);
        
        header('Content-type:text/xml; charset=uft-8');
        exit($xml);
    }
    
    //发送弹幕，需要DI式GET一个id和常规POST[message, color, size, stime, mode, user]
    function send($id){
        
    }
    
    /**
     * 奇怪功能
     */
    function okasii(){
        let('shell/shell');exit;
        
        $map = array(
            array('http://iio.ooo/' , '据说这个域名艹艹哒...'),
            array('http://miku.us/' , '好像去了某个地方，没看到米库会很失望吧..'),
            array('http://larele.com/' , '好像去了某个地方，以后这里会变成个人...'),
            array('http://ltre.me/' , '好像去了某个地方，那里可能还没被开发过'),
            array(url_prefix() . 'res/anniversary/bbsbilibili/index.html' , '2013年的纪念版(((界面没人要的'),
            array('http://www.kusha.biz/' , '这里是...'),
            array('http://www.bilibilijj.com/', '听说你喜欢扒B站的资源？'),
        );
        $p = rand(0, count($map)-1);
        $h = $map[$p][0];
        $a = $map[$p][1];
        putalert($a, $h);
        /* $n = array('miku.us', 'larele.com');
        $h = 'http://' . $n[rand(0, 1)];
        header("Location: $h"); */
    }
    
    //动态加载公共模板内容
    protected function tpl($tpl_name=null, $return=false){
        //设置导航栏活动状态(保存到DIBase的_lambda数组)
        $d = debug_backtrace();
        $f = $d[1]['function'];
        $this->navy_active = function($func) use ($f){
        	print $func == $f ? 'class="active"' : '';
        };
        
        //存储IP到模板
        import('net/ip');
        $this->client_ip = Api_ip::get_client_ip();
        parent::tpl();
    }
    
}