Danmu = {};



Danmu.gen = function(form_field_class) {
    var paramJson = collval('danmu-gen-field');
    var url = './?danmu/gen';
    $.post(url, paramJson, function(feed) {
        if (0 == feed.code) {
            alert(feed.msg);
            return;
        }
        
        console.log(paramJson);
        var d_local = /(127\.0\.0\.\d{1,3}|localhost)+/.test(paramJson.d_url);
        var d_172_192 = /(172\.\d{1,3}\.\d{1,3}\.\d{1,3}|192\.168\.\d{1,3}\.\d{1,3})+/.test(paramJson.d_url);
        var host_local = /127\.0\.0\.\d{1,3}|localhost/.test(location.host);
        var host_172_192 = /172\.\d{1,3}\.\d{1,3}\.\d{1,3}|192\.168\.\d{1,3}\.\d{1,3}/.test(location.host);
        console.log([d_local, d_172_192, host_local, host_172_192]);
        if (d_local&&!host_local || d_172_192&&!host_local&&!host_172_192) {
            alert('弹幕服务器无法与xml地址连通，将使用备用方案生成');
            var src = feed.data.spareSwfFile + '?cfile=' + paramJson.d_url + '&file=' + paramJson.v_url;
            var ubb = '[flash=730,462]' + src + '[/flash]';
        } else {
            var src = feed.data.swf;
            var ubb = feed.data.ubb;
        }
        
        //判断服务器自身可以成功请求弹幕文件，是则交给服务器处理弹幕，否则直接交给前端js生成UBB。
        $('#danmuzone').hide();
        $('#danmuzone-replace').attr('src', src).removeClass('hide');
        $('#clipboard').text(ubb).removeClass('hide');
        $('#clipboard').focus(function(){$(this).select();$(this).unbind('focus');});
    }, 'json');
};



Danmu.search = function() {
    var gets = collgets('danmu-search-field');
    var url = './?danmu/search' + gets;
    location.href = url;
};



(Danmu.delbutton = function(obj, flag){
    //选择初始化过程
    if (undefined == obj || undefined == flag) {
        $('.danmu-line')
            .mouseover(function(){Danmu.delbutton(this, 1);})
            .mouseout(function(){Danmu.delbutton(this, 0);});
        return;
    }
    //选择绑定过程
    var span = $(obj).find('.danmu-del-button').first();
    flag ? span.removeClass('hide') : span.addClass('hide');
    if ('0' == span.attr('bindclick')) {
        $(span).closest('div').click(function(e){
            if (！confirm('确定删除?')) return false;
            var url = null;
            e.stopPropagation();
            url = './?danmu/del/'+span.attr('data-id')
            $.get(url, function(json){
                var flag = true;
                if (1 == json.code) {
                    var danmuline = span.closest('.danmu-line')
                    danmuline.fadeOut('slow');
                    setTimeout(function(){danmuline.remove();if(0==danmuline.length)location.reload();}, 200);
                } else {
                    alert(json.msg);
                }
            }, 'json');
        });
        span.attr('bindclick', '1');
    }
})();



(Danmu.mod = function(){
    if ('danmu-mod' != $('tpl').eq(1).attr('name')) {
        return;//check the tpl while loaded
    }
    var show_modal = function(){
        var border = 10;
        var right = 65;
        var top = 65;
        var w = document.body.clientWidth - (right + border) * 2;
        var h = document.body.clientHeight - (top + border) * 2;
        var modal = $('#danmu-mod-modal');
        modal.css({
            'position' : 'fixed',
            'top' : top*1.1 + 'px',
            'right' : right + 'px',
            'z-index': 2,
            'height' : h*1.23 + 'px',
            'border' : 'black ' + border + 'px solid',
            'background' : 'rgba(150,150,150,0.5)'
        }).removeClass('hide').fadeIn('fast')
            .children('.form-group').css('margin-bottom', '-1px')
            .children('input,select').css({'background':'rgba(1,1,1,0.85)', 'color':'#999999'});
        $('#danmu-mod-close').css('z-index', '20').click(function(){ modal.fadeOut('fast'); $('#danmu-play-container').removeClass('col-xs-9');});
    };
    
    
    //点击后弹出编辑层，并将播放器推到左边
    $('#show-danmu-modal').click(function(e){
        e.stopPropagation();
        $('#danmu-play-container').addClass('col-xs-9');
        show_modal();
    }).click();//此处先处理成自动点击
    
    $('#danmu-mod-submit').click(function(){
        var url = './?danmu/mod';
        var json = collval('danmu-gen-field');
        $.post(url, json, function(feed) {
            if (0 == feed.code) {
                alert(feed.msg);
                return;
            }
            //判断服务器自身可以成功请求弹幕文件，是则交给服务器处理弹幕，否则直接交给前端js生成UBB。
            var src = feed.data.swf;
            $('#danmuzone-replace').attr('src', src);
            center_modal('<textarea id="danmu-mod-ubbcode" style="width:290px;height:180px;margin-top:15px;">' + feed.data.ubb + '</textarea>', 300, 200, 5, 'rgba(255,255,255,0.8)');
            $('#danmu-mod-ubbcode').focus(function(){$(this).select();});
        }, 'json');
    });
})();



(Danmu.play = function(){
    if ('danmu-play' != $('tpl').eq(0).attr('name')) {
        return;//check the tpl while loaded
    }
    var d = $('#danmuzone-replace');
    var swf = d.attr('src');
    if (!swf) {
        d.hide();
        $('#play-tip').css('display', '');
    }
    
    //【待定】封装该算法，并复用该算法在各处生成多个金馆鱼（或点击某个金馆鱼时，在新随机位置复制一个，越点越多）。金馆鱼的移动要平稳
    var j = $('#jinguanyu');
    var width = 1200;
    var height = 600;
    var wstart = 250;
    var hstart = 150;
    var wnext = 0;
    var hnext = 0;
    var step = 1;
    var speed = 20;
    setInterval(function(){
        //console.log('我在移动');
        //j.css({'left':Math.random()*1300+'px', 'top':Math.random()*600+'px'});
        step = Math.random()*10; //step = 1;
        speed = Math.random()*500 + 100; //speed = 1;
        wnext = j.css('left'); wnext=wnext.substring(0,wnext.length-2); Math.round(Math.random()*2-0.5)==0?(wnext-=(-step)):(wnext-=step);
        hnext = j.css('top'); hnext=hnext.substring(0,hnext.length-2); Math.round(Math.random()*2-0.5)==0?(hnext-=(-step)):(hnext-=step);
        if (wnext < 50) wnext = 60; if (hnext < 50) hnext = 60;
        if (hnext > height) hnext = 1150; if (hnext > height) hnext = 550;
        j.css({'left':wnext+'px', 'top':hnext+'px'});
    }, speed);
})();



//需要访问时执行的
(Danmu.init = function(){
    //上报IP
    var client_ip = $('#client_ip').val();
    Common.report_ip(client_ip);
    //检测浏览器类型
    
})();