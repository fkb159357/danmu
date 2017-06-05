<div class="container-fluid" style="text-align: center; font-size: 14px;">
    <div class="row">
        <div class="col-xs-4">
            <div class="col-xs-2">编号</div>
            <div class="col-xs-4">访问IP</div>
            <div class="col-xs-6">访问时间</div>
        </div>
        <div class="col-xs-2">
            <div class="col-xs-6">UID</div>
            <div class="col-xs-6">异常</div>
        </div>
        <div class="col-xs-3">
            <div class="col-xs-4">国别</div>
            <div class="col-xs-4">省份(州)</div>
            <div class="col-xs-4">城市</div>
        </div>
        <div class="col-xs-3">
            <div class="col-xs-6">ISP供应商</div>
            <div class="col-xs-3">ip_desc</div>
            <div class="col-xs-3">ip_type</div>
        </div>
    </div>
    
    <?php foreach ($rootists as $v):?>
    <div class="row">
        <div class="col-xs-4">
            <div class="col-xs-2"><?php print $v['id']?></div>
            <div class="col-xs-4"><?php print $v['ip']?></div>
            <div class="col-xs-6"><?php print $v['vtime']?></div>
        </div>
        <div class="col-xs-2">
            <div class="col-xs-6"><?php print $v['uid']?></div>
            <div class="col-xs-6"><?php print $v['is_exception']?></div>
        </div>
        <div class="col-xs-3">
            <div class="col-xs-4"><?php print $v['country']?></div>
            <div class="col-xs-4"><?php print $v['province']?></div>
            <div class="col-xs-4"><?php print $v['city']?></div>
        </div>
        <div class="col-xs-3">
            <div class="col-xs-6"><?php print $v['isp']?></div>
            <div class="col-xs-3"><?php print $v['ip_desc']?></div>
            <div class="col-xs-3"><?php print $v['ip_type']?></div>
        </div>
    </div>
    <?php endforeach;?>
    
    <div class="row">
        <div class="col-xs-7 col-xs-offset-2">
            <?php import('bootstrap'); print bt_shell_page("root/rootist/{p}/{$persize}/{$pagescope}", $pager)?>
        </div>
    </div>
</div>