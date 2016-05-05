
<div class="container" style="margin-top: 70px;">
    
    <div class="row">
        <div class="col-xs-12 rd24" style="padding-top:10px; background-color: #eeeeee;">
            <div class="col-xs-12 hide">
                <span>全部</span>
                <span>|</span>
                <span>只看我的</span>
            </div>
        
            <?php foreach ($danmus as $d): ?>
            <div class="danmu-line" style="cursor: pointer;" onclick="location.href='<?php print $d->link ?>'">
                <?php $alfa = floatval(rand(0,100)) / 100 ?>
                <div class="col-xs-1 rd24" style="min-width: 86px;line-height: 86px;font-size: 65px;text-align: center;background-color: rgba(11,2,3,<?php echo $alfa?>);">
                    <?php print $d->firstword ?>
                </div>
                <div class="col-xs-8" style="height: 86px;position: relative;/* background-color: green;*/">
                    <?php $vnameIsNull = is_int(mb_strpos($d->vname, ') 于 ['))&&is_int(mb_strpos($d->vname, '] 生成'))?>
                    <?php $mouseOverContent = 'this.innerHTML="' . ($vnameIsNull ? '<font color=gray>这个UP酱没留下啥，反正你们不懂~~</font>' : $d->vname) . '";' ?>
                    <?php $mouseOutContent = 'this.innerHTML="' . $d->vname . '";'?>
                    <?php $mouseEvent = " onmouseover='{$mouseOverContent}' onmouseout='{$mouseOutContent}' "?>
                    <span <?php print $mouseEvent?>  style="position: absolute;font-size: 24px;left:70px;bottom: 0px;">
                    <?php print $d->vname?>
                    </span>
                </div>
                <div class="col-xs-1" style="height: 86px;position: relative;">
                    <span style="position: absolute;right: 0px;bottom: 0px;font-size: 15px;">
                    <?php print date('j M Y', $d->cretime)?>
                    </span>
                </div>
                <div class="col-xs-1" style="height: 86px;position: relative;text-align:center;">
                    <span class="hide danmu-del-button" title="点击后就直接删除了" style="position: absolute;font-size: 36px;bottom: -10px;font-weight: bolder;" bindclick="0" data-id="<?php print $d->id?>">X</span>
                </div>
                <div class="col-xs-12" style="height: 10px;">&nbsp;</div>
            </div>
            <?php endforeach;?>
            
            <div class="col-xs-12">
                <?php import('bootstrap'); print bt_shell_page("danmu/search/{$keyword}/{p}/{$persize}/{$pagescope}", $pager, '{p}', $isme?array('u'=>'my'):array())?>
            </div>
        </div>
    </div>
    
</div>