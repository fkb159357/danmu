/**
 * url
callback
data
success
fail
time
 * @param options
 */
function jsonp(options) {
    options = options || {};
    if (!options.url || !options.callback) {
        throw new Error("参数不合法");
    }

    // 创建 script 标签并加入到页面中
    var callbackName = ('jsonp_' + Math.random()).replace(".", "");
    var oHead = document.getElementsByTagName('head')[0];
    options.data[options.callback] = callbackName;
    var params = formatParams(options.data);
    var oS = document.createElement('script');
    oHead.appendChild(oS);

    // 创建jsonp回调函数
    window[callbackName] = function(json) {
        oHead.removeChild(oS);
        clearTimeout(oS.timer);
        window[callbackName] = null;
        options.success && options.success(json);
    };

    // 发送请求
    oS.src = options.url + '?' + params;

    // 超时处理
    if (options.time) {
        oS.timer = setTimeout(function() {
            window[callbackName] = null;
            oHead.removeChild(oS);
            options.fail && options.fail({
                message : "超时"
            });
        }, time);
    }
};

// 格式化参数
function formatParams(data) {
    var arr = [];
    for ( var name in data) {
        arr.push(encodeURIComponent(name) + '=' + encodeURIComponent(data[i]));
    }
    return arr.join('&');
}

//收集值，返回json。ids是id属性的数组，可选，不传值则从.form-field控件收集
function collval(form_field_class, ids) {
    var j = {};
    if (ids && ids.length > 0) {
        for ( var i in ids) {
            var id = ids[i];
            j[id] = $('#' + id).val();
        }
    } else {
        $('.' + form_field_class).each(function(i, e) {
            var id = $(e).attr('id');
            j[id] = $('#' + id).val();
        });
    }
    return j;
}

// 收集值，返回DI框架专用的GET参数
function collgets(form_field_class, ids) {
    var g = '';
    if (ids && ids.length > 0) {
        for (var i in ids) {
            var id = ids[i];
            g += '/' + encodeURIComponent($('#' + id).val());
        }
    } else {
        $('.' + form_field_class).each(function(i, e) {
            var id = $(e).attr('id');
            g += '/' + encodeURIComponent($('#' + id).val());
        });
    }
    return g;
}

/**
 * 判断是否为json对象 ***
 * @param obj:
 *            对象（可以是jq取到对象）
 * @return isjson: 是否是json对象 true/false
 */
function is_json(obj) {
    var isjson = typeof (obj) == "object"
        && Object.prototype.toString.call(obj).toLowerCase() == "[object object]"
        && !obj.length;
    return isjson;
}

//当前访问URL问号之前的部分(包含协议前缀)
function urlpath() {
    var protocol = location.protocol;
    var hostname = location.hostname;
    var pathname = location.pathname;
    var port = location.port;
    return protocol + '//' + hostname + (80 != port && '' != port ? (':' + port) : '') + pathname;
}

//创建提示框
function center_modal(msg, w, h, z_index, bg, border){
    if (! msg) return;
    w || (w=300);
    h || (h=200);
    z_index || (z_index = 1);
    bg || (bg = 'rgba(222,222,222,0.5)');
    border || (border = 'black 2px solid');
    
    var id = 'center_modal_' + Math.floor(Math.random() * 1000);
    $('body').append('<div id="' + id + '"><div id="' + id + '_close" style="position: absolute;top: 0px;right: 0px;width: 12px;height: 12px;line-height: 12px;text-align: center;font-family: 微软雅黑;font-size: 12px;font-weight: bold;cursor: pointer;">X</div><div id="' + id + '_tip" style="text-align: center; font-size: 15px;"></div></div>');
    var modal = $('#' + id);
    var close = $('#' + id + '_close');
    var tip = $('#' + id + '_tip');
    modal.css({
        'position': 'fixed',
        'z-index': z_index,
        'top': (document.body.clientHeight/2 - h/2) + 'px',
        'left': (document.body.clientWidth/2 - w/2) + 'px',
        'height': h+'px',
        'width': w+'px',
        'border': border,
        'background': bg
    }).fadeIn('fast');
    tip.html(msg);
    close.css('z-index', z_index+1).click(function(){
        modal.fadeOut('fast');
        modal.remove();
    });
}


//将UI组件放置到指定区域正中央，必须确保selector的position为relative
function put_in_center(selector, html, x, y){
    if (! window.jQuery) return;
    var $ = window.jQuery;
    if (! selector) return;
    x || (x = 0);
    y || (y = 0);
    $(selector).each(function(i, e){
       if ('relative' != $(e).css('position')) return;
       $(e).html(html);
       var c = $(e).children('*:eq(0)');
       var cw = c.css('width'), ch = c.css('height');
       var ew = $(e).css('width'), eh = $(e).css('height');
       var left = 0, top = 0;
       cw = cw.substring(0, cw.indexOf('px'));
       ch = ch.substring(0, ch.indexOf('px'));
       ew = ew.substring(0, ew.indexOf('px'));
       eh = eh.substring(0, eh.indexOf('px'));
       left = x || ((ew-cw)/2 + 'px');
       top = y || ((eh-ch)/2 + 'px');
       c.css({
           position: 'absolute',
           left: left,
           top: top
       });
    });
}

function getCookie(c_name){
　　if (document.cookie.length>0){
　　　　c_start=document.cookie.indexOf(c_name + "=");
　　　　if (c_start!=-1){ 
　　　　　　c_start=c_start + c_name.length+1;
　　　　　　c_end=document.cookie.indexOf(";",c_start);
　　　　　　if (c_end==-1) c_end=document.cookie.length;　　
　　　　　　return unescape(document.cookie.substring(c_start,c_end));
　　　　} 
　　}
　　return ""
}

//setCookie('username','Darren',30)
function setCookie(c_name, value, expiredays){
    var exdate=new Date();
    exdate.setDate(exdate.getDate() + expiredays);
    document.cookie=c_name+ "=" + escape(value) + ((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
}