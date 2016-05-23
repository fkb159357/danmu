<?php
class TuDo extends DIDo {
    
    //图首页
    function start(){
        $tu = supertable('Tu');
        $this->list = $tu->select('', '*, id tuId', '`id` DESC', array(1, 20, 10)) ?: array();
        $this->stpl();
    }
    
    //上传视图
    function toup($tuId = 0){
        die('系统升级中。。');
        //$this->stpl();
        echo '<html><head>';
        echo '<meta property="qc:admins" content="330620745651356537" />';
        echo '<style type="text/css">input[type=text]{width:600px;} .inputTitle{width:80px;display:inline-block;text-align:right;}</style>';
        echo '<script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>';
        echo '<script>function toLogin(){ var A=window.open("/oauthqq/login", "TencentLogin", "width=450,height=320,menubar=0,scrollbars=1,resizable=1,status=1,titlebar=0,toolbar=0,location=1"); }</script>';
        echo '</head><body>';
        echo '<div style="text-align:center;"> <a href="javascript:toLogin()"><img src="http://qzonestyle.gtimg.cn/qzone/vas/opensns/res/img/Connect_logo_3.png"></a></div>';
        echo '<form method="post" action="./?tu/up/'.intval($tuId).'" target="hehe" enctype="multipart/form-data"><input type="file" name="tu"><input type="submit" value="上传"></form><iframe name="hehe" width="960" height="480"></iframe>';
        echo '<div><span class="inputTitle">UBB代码：</span><input id="ubb" type="text"><br><span class="inputTitle">HTML代码：</span><input id="html" type="text"><br><span class="inputTitle">URL：</span><input id="src" type="text"></div>';
        echo '<div><span class="inputTitle">打标签：</span><input id="tags" type="text"><input id="setTags" type="button" value="打上"></div>';
        $s = 'var src = (window.hehe.document.body.innerText.match(/\[url\]\s*\=\>\s*(http\:\/\/.+)\s*\[\w+\]/) || [,""])[1];';
        $s .= 'var ubbVal = "[img]" + src + "[/img]"; if($("#ubb").val()!=ubbVal) if(!src) $("#ubb").val("获取中.."); else $("#ubb").val(ubbVal);';
        $s .= 'var htmlVal = "<img src=\'" + src + "\'>"; if($("#html").val()!=htmlVal) if(!src) $("#html").val("获取中.."); else $("#html").val(htmlVal);';
        $s .= 'if($("#src").val()!=src) if(!src) $("#src").val("获取中.."); else $("#src").val(src);';
        echo "<script>$('form').submit(function(){ window.hehe.document.body.innerHTML=''; $('#ubb,#html,#src').each(function(i,e){e.value='获取中..';}); {$s} setInterval(function(){ {$s} }, 500); });</script>";
        echo '<script>$("#ubb,#html,#src").click(function(){$(this).select();});</script>';
        echo '<script>$("#setTags").click(function(evt){ var tuId = (window.hehe.document.body.innerText.match(/\[id\]\s*\=\>\s*(\d+)\s*\[\w+\]/) || [,""])[1]; var v = $("#tags").val()||""; $.post("?tu/setTags/"+tuId+"/"+v, function(j){console.log(j)}, "json"); });</script>';
        echo '</body>';
    }
    
    //通过接口上传处理程序
    function up($tuId = 0){
        $file = $_FILES['tu'];
        if (! $file) putjson(1, null, 'no input file');
        $imgDirGroup = DI_DEBUG_MODE ? 'tu-miku-us-test/' : 'default/';
        if ($tuId) {
        	$ret = Tu::reSaveRecord($tuId, $file, $imgDirGroup);
        } else {
            $ret = Tu::saveRecord($file, $imgDirGroup);//测试时，请指定测试分组目录！
        }
        $code = $ret['code'] == 0 && $ret['id'] != 0 ? 0 : -1;
        //putjson($code, $ret);die;
        dump($ret);
    }
    
    function setTags($tuId = 0, $tags = ''){
        $tuId = (int) $tuId;
        if (! $tuId) putjson(-1, null, 'param err');
        import('libutil/xsshtml');
        xssFilter($tags);
        //$newTags = TuTag::setTags($tuId, $tags);
        $newTags = Tagged::setTags('tu', $tuId, $tags);
        putjson(0, compact('newTags'));
    }
    
    function getList($p = 1, $limit = 10, $scope = 10){
        $tu = supertable('Tu');
        $this->list = $tu->select('', '*, id tuId', '`id` DESC', array($p, $limit, $scope)) ?: array();
        $this->page = $tu->page;
        $this->limit = $limit;
        $this->scope = $scope;
        $this->stpl();
    }
    
    function get($id = 0, $tag = ''){
        $tuObj = supertable('Tu');
        $tuTagObj = supertable('TuTag');
        if ($id) {
        	$tu = $tuObj->find(compact('id'));
        	@die("<img src='{$tu->url}' width='50%'>");
        } else {
            $tuIds = Tagged::digTabIdsByTags('tu', explode(',', $tag), 'union', true, 'all');
            $list = array();
            foreach ($tuIds as $id) {
                $list[] = supertable('Tu')->find(compact('id'), 'id tuId, filename, url');
            }
            $this->list = $list;
            /* $sql = "SELECT t.id tuId, t.filename, t.url
                    FROM {$tuObj->table} t, {$tuTagObj->table} tt 
                    WHERE t.id = tt.tu_id AND `tag` IN (:taglist)";
            $this->list = $tuObj->query($sql, array('taglist' => "{$tag}")); */
            @$this->stpl('tu-getlist');
        }
    }
    
    function del(){
        
    }
    
    
    //迁移处理1：从原tutag导入数据到tag【已验证此步骤没有问题】
    function importTuTag2tag(){
        //插入tag表(tag+raw_tag字段要唯一)
        $list = supertable('TuTag')->query('select distinct `tag` from dm_tu_tag');
        foreach ($list as $v) {
            $data = array(
                'tag' => $v->tag,
                'pure_tag' => Tag::getPureTag($v->tag),
            );
            $find = supertable('Tag')->find($data);
            if (empty($find)) {
                supertable('Tag')->insert($data);
            }
        }
    }
    
    
    //迁移处理2：从tutag导入数据到tagged【此步骤可能会报too many connection错误】
    function importTuTag2tagged(){
        //作tag表MAP，tag=>[id1,id2]
        $tagMap = array();
        $pureTagMap = array();
        $list = supertable('Tag')->select();
        foreach ($list as $v) {
            $tagMap[$v->tag][] = $v->id;
            $pureTagMap[$v->pure_tag][] = $v->id;
        }
        //分段取TuTag源
        $page = session(__CLASS__.__FUNCTION__.'page') ?: 1;
        $list = supertable('TuTag')->select(array(), '', null, array($page, 10, 10));
        $pager = supertable('TuTag')->pager($page, 10, 10, supertable('TuTag')->count(array()));
        $page = ($page >= $pager['total_page']) ? 1 : ($page + 1); 
        session(__CLASS__.__FUNCTION__.'page', $page);
        echo "当前进度页码：{$page} / {$pager['total_page']} <br>";
        //插入tagged表
        foreach ($list as $v) {
            foreach (array('tagMap', 'pureTagMap') as $mapName) {
                $tagIdList = ${$mapName}[$v->tag];
                foreach (${$mapName}[$v->tag] as $vTagId) {
                    $data = array(
                        'tag_id' => $vTagId,
                        'tab_id' => $v->tu_id,
                        'tab_name' => 'tu',
                    );
                    $find = supertable('Tagged')->find($data);
                    if (empty($find)) {
                        supertable('Tagged')->insert($data);
                    }
                }
            }
        }
    }
    
    
    //【即将作废】处理标签数据，从dm_tu_tag转移到dm_tag, dm_tagged
    function dealTagData(){
        //插入tag表(tag+raw_tag字段要唯一)
        $list = supertable('TuTag')->query('select distinct `tag` from dm_tu_tag');
        foreach ($list as $v) {
            $data = array(
            	'tag' => $v->tag,
                'pure_tag' => Tag::getPureTag($v->tag),
            );
            try {
                supertable('Tag')->insert($data);
            } catch (Exception $e) {}
        }
        //作tag表MAP，tag=>[id1,id2]
        $tagMap = array();
        $pureTagMap = array();
        $list = supertable('Tag')->select();
        foreach ($list as $v) {
            $tagMap[$v->tag][] = $v->id;
            $pureTagMap[$v->pure_tag][] = $v->id;
        }
        //插入tagged表
        $list = supertable('TuTag')->select();
die;//至此处没问题，后面代码会造成连接数过多的问题
        foreach ($list as $v) {
            foreach (array('tagMap', 'pureTagMap') as $mapName) {
                $tagIdList = ${$mapName}[$v->tag];
                foreach (${$mapName}[$v->tag] as $vTagId) {
                    $data = array(
                        'tag_id' => $vTagId,
                        'tab_id' => $v->tu_id,
                        'tab_name' => 'tu',
                    );
                    dump($data);
                    try {
                        supertable('Tagged')->insert($data);
                    } catch (Exception $e) {
                    	var_dump($e);echo"<br>";
                    }
                }
            }
        }
        //打标签时，改为保存到tagged的方式
        //通过表情获取图时，改为tagged获取的方式
    }
    
}