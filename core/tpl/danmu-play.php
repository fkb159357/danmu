<div class="container" style="margin-top: 70px;z-index: 0;">
    
    <tpl name="danmu-play"></tpl>
    <img id="jinguanyu" alt="x" src="res/biz/danmu/jinguanyu.gif" style="position: fixed;left: 100px; top: 100px;z-index: 1;">
    
    <div class="row">
        <div id="danmu-play-container" class="container">
                <div class="col-xs-12 rdtop" style="background-color: #13202C;height: 540px;">
                    <div id="play-tip" class="col-xs-12 hide" style="background-color: #00BC9C;margin-top: 25px; height: 490px;">
                        <div>播放不了</div>
                    </div>
                    <!-- name="wmode" value="transparent"兼容IE的FLASH层优先级设置 wmode="transparent"兼容FIREFOX的FLASH层优先级设置 -->
                    <embed id="danmuzone-replace" class="col-xs-12" name="wmode" value="transparent" wmode="transparent" style="margin-top: 25px; height: 490px;z-index: 0;" 
                        AllowScriptAccess='always' rel='noreferrer'
                        type='application/x-shockwave-flash'
                        allowfullscreen='true' quality='high'
                        src="<?php print $danmu->swf?>">
                    </embed>
                </div>
                <div class="col-xs-12 rdbottom" style="background-color: #F5F5F5;height: 60px;">
                    <div id="show-danmu-modal" class="col-xs-offset-6 circle28" style="background-color: #13202C;margin-top: 18px;cursor: pointer;"></div>
                </div>
        </div>
    </div>
    
    <!-- 遮罩层中修改信息 -->
    <tpl name="danmu-mod"></tpl>
    <div id="danmu-mod-modal" class="hide col-xs-3">
    	<div id="danmu-mod-close" style="position: absolute;top: 0px;right: 0px;width: 36px;height: 36px;line-height: 36px;text-align: center;font-family: 微软雅黑;font-size: 36px;font-weight: bold;cursor: pointer;">X</div>
        <div class="form-group">
            <label for="v_url">视频URL</label>
            <input id="v_url" name="v_url" type="text" class="form-control danmu-gen-field" placeholder="http://**.flv|mp4|hlv.." value="<?php print $danmu->v_url?>">
        </div>
        <div class="form-group">
            <label for="d_url">弹幕URL</label>
            <input id="d_url" name="d_url" type="text" class="form-control danmu-gen-field" placeholder="http://*****.xml" value="<?php print $danmu->d_url?>">
        </div>
        <div class="form-group">
            <label for="vname">备注名称(可选)</label>
            <input id="vname" name="vname" type="text" class="form-control danmu-gen-field" placeholder="没名谁也保不了你~~" value="<?php print $danmu->vname?>">
        </div>
        <div class="form-group">
            <label for="player">选择播放器</label>
            <select id="player" name="player" class="form-control danmu-gen-field">
              <?php $player_selected = function ($value) use ($danmu){if($danmu->player==$value)print 'selected="selected"';}?>
              <option value="3" <?php $player_selected(3)?>>B站超级弹幕(敬请期待)</option>
              <option value="1" <?php $player_selected(1)?>>MukioPlayer</option>
              <option value="2" <?php $player_selected(2)?>>MukioPlayer_old</option>
              <option value="3" <?php $player_selected(3)?>>MukioPlayerPlus</option>
            </select>
        </div>
        <div class="form-group">
            <label for="skin">选择皮肤</label>
            <select id="skin" name="skin" class="form-control danmu-gen-field">
              <?php $skin_selected = function ($value) use ($danmu){if($danmu->skin==$value)print 'selected="selected"';}?>
              <option value="1" <?php $skin_selected(1)?>>nemesis.zip</option>
              <option value="2" <?php $skin_selected(2)?>>darkrv5.zip</option>
              <option value="3" <?php $skin_selected(3)?>>grungetape.zip</option>
              <option value="4" <?php $skin_selected(4)?>>nikitaskin.zip</option>
              <option value="5" <?php $skin_selected(5)?>>plexi.zip</option>
              <option value="6" <?php $skin_selected(6)?>>miku.zip</option>
              <option value="7" <?php $skin_selected(7)?>>nikitaskin.zip</option>
              <option value="8" <?php $skin_selected(8)?>>TokyoGhost.zip</option>
            </select>
        </div>
        <div class="form-group">
            <label for="fontsize">字体大小</label>
            <select id="fontsize" name="fontsize" class="form-control danmu-gen-field">
                  <?php 
                  for($i=5;$i<=80;$i++):
                  $selected = 18==$i?'selected="selected"':'';
                  print "<option value='$i' $selected >$i</option>";
                  endfor;
                  ?>
            </select>
        </div>
        <input id="id" value="<?php print $danmu->id?>" type="hidden" class="danmu-gen-field">
        <button id="danmu-mod-submit" class="btn btn-inverse btn-embossed pull-right">变更</button>
    </div>
    
</div>
