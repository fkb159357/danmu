<div class="container" style="margin-top: 70px;">
    
    <!-- 优先显示该部分 -->
    <div class="row">
        <!-- 弹幕预览 -->
        <div class="col-xs-9">
            <div class="row col-xs-12">
                <div class="col-xs-12 rdtop" style="background-color: #13202C;height: 540px;">
                    <div id="danmuzone" class="col-xs-12" style="background: url('res/biz/danmu/danmuzone-bg-light.jpg') repeat fixed;margin-top: 25px; height: 490px;/* background-color: #00BC9C; */">
                    </div>
                    <embed id="danmuzone-replace" class="col-xs-12 hide" style="margin-top: 25px; height: 490px;" 
                        AllowScriptAccess='always' rel='noreferrer'
                        type='application/x-shockwave-flash'
                        allowfullscreen='true' quality='high'
                        src="http://miku.us/BBS/danmu/MukioPlayer_no_list.swf?file=http://larele.com/BBS/danmu/%E5%AE%8C%E5%85%A8%E5%90%AC%E4%B8%8D%E6%87%82%E8%80%81%E5%85%AC%E5%9C%A8%E8%AF%B4%E4%BB%80%E4%B9%88/%E3%80%90%E5%AE%8C%E6%95%B4OP%E3%80%91%E3%80%8C%E5%85%B3%E4%BA%8E%E5%AE%8C%E5%85%A8%E5%90%AC%E4%B8%8D%E6%87%82%E8%80%81%E5%85%AC%E5%9C%A8%E8%AF%B4%E4%BB%80%E4%B9%88%E7%9A%84%E4%BA%8B%E3%80%8D%E8%8A%B1%E6%A0%B7%E7%A7%80%E6%81%A9%E7%88%B1/2579347-1.flv&cfile=http://ltre.me:8888/BBS/danmu/%E5%AE%8C%E5%85%A8%E5%90%AC%E4%B8%8D%E6%87%82%E8%80%81%E5%85%AC%E5%9C%A8%E8%AF%B4%E4%BB%80%E4%B9%88/%E3%80%90%E5%AE%8C%E6%95%B4OP%E3%80%91%E3%80%8C%E5%85%B3%E4%BA%8E%E5%AE%8C%E5%85%A8%E5%90%AC%E4%B8%8D%E6%87%82%E8%80%81%E5%85%AC%E5%9C%A8%E8%AF%B4%E4%BB%80%E4%B9%88%E7%9A%84%E4%BA%8B%E3%80%8D%E8%8A%B1%E6%A0%B7%E7%A7%80%E6%81%A9%E7%88%B1/2579347-1.xml">
                    </embed>
                </div>
                <div class="col-xs-12 rdbottom" style="background-color: #F5F5F5;height: 60px;">
                    <div class="col-xs-offset-6 circle28" style="background-color: #13202C;margin-top: 18px;cursor: pointer;" onclick="Danmu.gen()"></div>
                </div>
            </div>
        </div>
        
        <!-- 弹幕信息提交 -->
        <div class="col-xs-3">
            <form role="form" style="margin-top: 5px;">
              <div class="form-group">
                <label for="v_url">视频URL</label>
                <input id="v_url" name="v_url" type="text" class="form-control danmu-gen-field" placeholder="http://**.flv|mp4|hlv.."> <!-- value="http://localhost:8888/BBS/danmu/%E6%9F%90%E7%A7%91%E5%AD%A6/%E3%80%90%E9%BB%91%E5%AD%90%E3%80%91%E6%88%91%E6%89%8D%E4%B8%8D%E6%98%AF%E6%8F%89%E8%84%B8%E5%85%9A/%E3%80%90%E9%BB%91%E5%AD%90%E3%80%91%E6%88%91%E6%89%8D%E4%B8%8D%E6%98%AF%E6%8F%89%E8%84%B8%E5%85%9A.flv" -->
              </div>
              <div class="form-group">
                <label for="d_url">弹幕URL</label>
                <input id="d_url" name="d_url" type="text" class="form-control danmu-gen-field" placeholder="http://*****.xml"> <!-- value="http://comment.bilibili.com/73345.xml" -->
              </div>
              <div class="form-group">
                <label for="vname">备注名称(可选)</label>
                <input id="vname" name="vname" type="text" class="form-control danmu-gen-field" placeholder="没名谁也不保(包)不了你了~~"> <!-- value="【黑子】我才不是揉脸党" -->
              </div>
              <div class="form-group">
                <label for="player">选择播放器</label>
                <select id="player" name="player" class="form-control danmu-gen-field">
                  <option value="1">MukioPlayer</option>
                  <option value="2">MukioPlayer_old</option>
                  <option value="3" selected="selected">MukioPlayerPlus</option>
                  <option value="3">B站超级弹幕(敬请期待)</option>
                </select>
              </div>
              <div class="form-group">
                <label for="skin">选择皮肤</label>
                <select id="skin" name="skin" class="form-control danmu-gen-field">
                  <option value="1">nemesis.zip</option>
                  <option value="2">darkrv5.zip</option>
                  <option value="3">grungetape.zip</option>
                  <option value="4">nikitaskin.zip</option>
                  <option value="5">plexi.zip</option>
                  <option value="6">miku.zip</option>
                  <option value="7">nikitaskin.zip</option>
                  <option value="8">TokyoGhost.zip</option>
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
              <div class="form-group hide">
                  <button type="button" class="btn btn-inverse btn-embossed pull-right col-xs-5" onclick="Danmu.gen()">生成</button>
              </div>
            </form>
            <div style="/* margin-top: 80px; */">
                <textarea id="clipboard" class="form-control hide" rows="5"></textarea>
                <button type="button" class="btn btn-success btn-embossed pull-right col-xs-5 hide" onclick="">复制</button>
            </div>
        </div>
        
    </div>
    
    <!-- 无扰预览（初始隐藏） -->
    <div class="row hide">
        <div class="col-xs-10 col-xs-offset-1">
            <div class="login">
            	<div class="login-screen">
            	
            	</div>
            </div>
        </div>
    </div>
    
</div>

<style type="text/css">
.form-group>input,.form-group>select{ color: rgb(15, 15, 15); background: rgba(190, 190, 190, 0.45098); } /*background: rgba(190, 190, 190, 0.85098);*/
</style>