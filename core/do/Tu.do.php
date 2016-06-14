<?php
/**
 * 看标签对应的图列表（含分页）
 *      http://tu.miku.us/tu/getlist/页码/每页条数/页码长度?tags=标签
 * 获取单张图
 *      http://tu.miku.us/tu/get/图ID
 * 看标签对应的图列表（不分页）
 *      http://tu.miku.us/tu/get//标签
 * 查图对应的标签（接口）
 *      http://tu.miku.us/tu/getTagsByTabId/图ID
 */
class TuDo extends DIDo {
    
    //图首页
    function start(){
        $tu = supertable('Tu');
        $this->list = $tu->select('', '*, id tuId', '`id` DESC', array(1, 20, 10)) ?: array();
        $this->stpl();
    }
    
    //上传视图
    function toup($tuId = 0){
        //$this->stpl();
        echo '<html><head>';
        echo '<meta property="qc:admins" content="330620745651356537" />';
        echo '<style type="text/css">';
        echo 'input[type=text]{width:883px;} .inputTitle{width:80px;display:inline-block;text-align:right;}';
        echo '#allTags,#topTagsByLastFill,#topTagsByAllFill{width:883px;display:inline-block;}';
        echo '.btn { display: inline-block; padding: 6px 12px; margin-top: 5px; font-size: 14px; font-weight: 400; line-height: 1.42857143; text-align: center; white-space: nowrap; vertical-align: middle; -ms-touch-action: manipulation; touch-action: manipulation; cursor: pointer; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; background-image: none; border: 1px solid transparent; border-radius: 4px; }';
        echo '</style>';
        echo '<script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>';
        echo '<script>function toLogin(){ var A=window.open("/oauthqq/login", "TencentLogin", "width=450,height=320,menubar=0,scrollbars=1,resizable=1,status=1,titlebar=0,toolbar=0,location=1"); }</script>';
        echo '</head><body>';
        echo '<div style="text-align:center;"> <a href="javascript:toLogin()"><img src="http://qzonestyle.gtimg.cn/qzone/vas/opensns/res/img/Connect_logo_3.png"></a></div>';
        echo '<form method="post" action="./?tu/up/'.intval($tuId).'" target="hehe" enctype="multipart/form-data"><input type="file" name="tu"><input type="submit" value="上传"></form><iframe name="hehe" width="960" height="480"></iframe>';
        echo '<div><span class="inputTitle">UBB代码：</span><input id="ubb" type="text"><br><span class="inputTitle">HTML代码：</span><input id="html" type="text"><br><span class="inputTitle">URL：</span><input id="src" type="text"></div>';
        echo '<div><span class="inputTitle">打标签：</span><input id="tags" type="text"><input id="setTags" type="button" value="打上"></div>';
        echo '<hr/>';
        echo '<div><span class="inputTitle">极近候选：</span><div id="topTagsByLastFill"></div></div>';//根据当前填入的最尾部标签，给出的候选标签
        echo '<hr/>';
        echo '<div><span class="inputTitle">近似候选：</span><div id="topTagsByAllFill"></div></div>';//根据当前填入的所有标签，给出的候选标签
        echo '<hr/>';
        echo '<div><span class="inputTitle">全部候选：</span><div id="allTags"></div></div>';//所有标签
        $s = 'var src = (window.hehe.document.body.innerText.match(/\[url\]\s*\=\>\s*(http\:\/\/.+)\s*\[\w+\]/) || [,""])[1];';
        $s .= 'var ubbVal = "[img]" + src + "[/img]"; if($("#ubb").val()!=ubbVal) if(!src) $("#ubb").val("获取中.."); else $("#ubb").val(ubbVal);';
        $s .= 'var htmlVal = "<img src=\'" + src + "\'>"; if($("#html").val()!=htmlVal) if(!src) $("#html").val("获取中.."); else $("#html").val(htmlVal);';
        $s .= 'if($("#src").val()!=src) if(!src) $("#src").val("获取中.."); else $("#src").val(src);';
        echo "<script>$('form').submit(function(){ window.hehe.document.body.innerHTML=''; $('#ubb,#html,#src').each(function(i,e){e.value='获取中..';}); {$s} setInterval(function(){ {$s} }, 500); });</script>";
        echo '<script>$("#ubb,#html,#src").click(function(){$(this).select();});</script>';
        echo '<script>$("#setTags").click(function(evt){ var tuId = (window.hehe.document.body.innerText.match(/\[id\]\s*\=\>\s*(\d+)\s*\[\w+\]/) || [,""])[1]; var v = $("#tags").val()||""; $.post("?tu/setTags/"+tuId+"/"+v, function(j){console.log(j)}, "json"); });</script>';
        echo '<script>$("#tags").keyup(function(){ ';
            echo ' $("#topTagsByLastFill").html(""); var last=this.value.split(",").pop(); $.post("/?tu/getGroupsAndTopTagsByTag/"+encodeURIComponent(last), function(j){ $.each(j.data.topTags, function(i, e){ $("#topTagsByLastFill").append("<button class=\'btn allTagsOne\'>"+e.tag+"<font color=red>&nbsp;("+e.cnt+")</font>"+"</button>&nbsp;"); }); }, "json"); '; //取打标签框里的最后一个，获取候选
            echo ' $("#topTagsByAllFill").html(""); var all=this.value; $.post("/?tu/getGroupsAndTopTagsByTag/"+encodeURIComponent(all), function(j){ $.each(j.data.topTags, function(i, e){ $("#topTagsByAllFill").append("<button class=\'btn allTagsOne\'>"+e.tag+"<font color=red>&nbsp;("+e.cnt+")</font>"+"</button>&nbsp;"); }); }, "json"); '; //取打标签框里的所有，获取候选
        echo '});</script>';
        echo '</script>';
        echo '<script>$.getJSON("/?tu/getAllTags", function(j){ $.each(j.data.tags, function(i, tag){ $("#allTags").append("<button class=\'btn allTagsOne\'>"+tag+"</button>&nbsp;"); }); });</script>';
        echo '<script>$("body").on("click", ".allTagsOne", function(){ var rawArr=$("#tags").val().split(","); rawArr.push($(this).text()); $("#tags").val(rawArr.join(",").replace(/^\,/, "")); });</script>';
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
    
    //对图打标签
    function setTags($tuId = 0, $tags = ''){
        $tuId = (int) $tuId;
        if (! $tuId) putjson(-1, null, 'param err');
        import('libutil/xsshtml');
        xssFilter($tags);
        //$newTags = TuTag::setTags($tuId, $tags);
        $newTags = Tagged::setTags('tu', $tuId, $tags);
        putjson(0, compact('newTags'));
    }
    
    //获取所有标签
    function getAllTags($page = 1, $limit = 0){
        $limitArr = (0 === $limit) ? null : array(max($page,1), $limit, 10);
        $list = supertable('Tag')->select(array(), 'tag, pure_tag', null, $limitArr);
        $tags = array();
        foreach ($list as $v) {
            if (! in_array($v->tag, $tags)) $tags[] = $v->tag;
            if (! in_array($v->pure_tag, $tags)) $tags[] = $v->pure_tag;
        }
        putjson(0, compact('tags'));
    }
    
    //通用列表
    function getList($p = 1, $limit = 10, $scope = 10){
        $tu = supertable('Tu');
        $p = max(1, (int)$p);
        $this->limit = $limit = min((int)$limit, 20);
        $this->scope = $scope = (int)$scope == 0 ? 10 : (int)$scope;
        $tags = arg('tags');
        if (empty($tags)) {
            $this->elseParams = array();
            $this->list = $tu->select('', '*, id tuId', '`id` DESC', array($p, $limit, $scope)) ?: array();
            $this->page = $tu->page;
        } else { //进入标签搜索模式
            $useTagged = (bool)(arg('useTagged', false));//是否使用tagged表进行超深挖掘
            $taggedLayer = intval(arg('taggedLayer', 0));//超深挖掘层数，当useTagged=true时有效
            $this->elseParams = compact('tags', 'useTagged', 'taggedLayer');
            $tags = array_filter(array_unique(explode(',', preg_replace('/\s/', '', $tags))));
            $tuIds = Tagged::digTabIdsByTags('tu', $tags, 'union', true, 'all', $useTagged, $taggedLayer);
            //分页
            $this->page = supermodel()->pager($p, $limit, $scope, count($tuIds));
            $tuIds = array_slice($tuIds, ($p-1)*$limit, $limit);
            //具体数据
            $list = array();
            foreach ($tuIds as $id) $list[] = $tu->find(compact('id'), 'id tuId, filename, url');
            $this->list = $list;
        }
        $this->stpl();
    }
    
    //简易获取图
    function get($id = 0, $tags = ''){
        $tuObj = supertable('Tu');
        $tuTagObj = supertable('TuTag');
        if ($id) {
        	$tu = $tuObj->find(compact('id'));
        	@die("<img src='{$tu->url}' width='50%'>");
        } else { //支持简单、非深度挖掘
            $tuIds = Tagged::digTabIdsByTags('tu', explode(',', $tags), 'union', true, 'all');
            //具体数据
            $list = array();
            foreach ($tuIds as $id) $list[] = supertable('Tu')->find(compact('id'), 'id tuId, filename, url');
            $this->list = $list;
            /* $sql = "SELECT t.id tuId, t.filename, t.url
                    FROM {$tuObj->table} t, {$tuTagObj->table} tt 
                    WHERE t.id = tt.tu_id AND `tag` IN (:taglist)";
            $this->list = $tuObj->query($sql, array('taglist' => "{$tag}")); */
            @$this->stpl('tu-getlist');
        }
    }
    
    
    //根据数据ID，获取对应的标签集合
    function getTagsByTabId($tabId = 0){
        $tabId = (int) $tabId;
        if (empty($tabId)) {
            $tags = array();
        } else {
            $tags = Tagged::getTagsByTabId('tu', $tabId);
        }
        putjson(0, compact('tags'));
    }
    
    
    //根据输入的标签，获取历史的打标签组合，及频率从高到低排序的相关标签。方便选择
    function getGroupsAndTopTagsByTag($tags = ''){
        $tags = array_filter(array_unique(explode(',', preg_replace('/\s/', '', $tags))));
        $ret = Tagged::getGroupsAndTopTagsByTag('tu', $tags);
        putjson(0, $ret);
    }
    
    
    function del(){
        
    }
    
    
    //【数据处理】迁移处理1：从原tutag导入数据到tag【已验证此步骤没有问题】
    function importTuTag2tag(){
        $start = session(__CLASS__.__FUNCTION__.'start') ?: 1;
        $limit = 50;
        $list = supertable('TuTag')->query("SELECT `tag` FROM dm_tu_tag GROUP BY `tag` LIMIT {$start}, {$limit}") ?: array();
        $start = empty($list) ? 1 : ($start + $limit);
        echo "当前进度：{$start}<br>";
        session(__CLASS__.__FUNCTION__.'start', $start);
        //插入tag表(tag+raw_tag字段要唯一)
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
    
    
    //【数据处理】迁移处理2：从tutag导入数据到tagged【此步骤需要分多次请求】
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
        $limit = 150;
        $page = session(__CLASS__.__FUNCTION__.'page') ?: 1;
        $list = supertable('TuTag')->select(array(), '', null, array($page, $limit, 10));
        $pager = supertable('TuTag')->pager($page, $limit, 10, supertable('TuTag')->count(array()));
        echo "当前进度页码：{$page} / {$pager['total_page']} <br>";
        $page = ($page >= $pager['total_page']) ? 1 : ($page + 1); 
        session(__CLASS__.__FUNCTION__.'page', $page);
        //插入tagged表
        foreach ($list as $v) {
            foreach (array('tagMap', 'pureTagMap') as $mapName) {
                if (! isset(${$mapName}[$v->tag])) continue;
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
    
    
    //【已确认，完全没必要做，也不能这么做，否则会造成把意义上毫无关联的数据也关联在一起】【需求待定，可能没必要做，改为通过输入标签，获取关联的标签】【数据处理】根据现有tagged数据，对标签关系表数据进行录入
    function importTaggedDataToTagRelate(){
        /* $limit = 150;
        $page = session(__CLASS__.__FUNCTION__.'page') ?: 1;
        $list = supertable('Tagged')->select(array(), '', null, array($page, $limit, 10));
        $pages = supertable('Tagged')->page;
        echo "当前进度页码：{$page} / {$pages['total_page']} <br>";
        $page = ($page >= $pages['total_page']) ? 1 : ($page + 1);
        session(__CLASS__.__FUNCTION__.'page', $page);
        foreach ($list as $v) {
            
        } */
    }
    
}