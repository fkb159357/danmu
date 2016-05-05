/**
 * 和$wsp对象有关
 */
define("player/single", ["globals"], function(require, exports, module){
    var $wsp = {}, globals = require("globals");
    $wsp.version = "2.0.0";
    $wsp.plugins = {
        silverlight: { version: [4, 0], types: ['wma', 'mp3'] },
        flash: { version: [9, 0], types: ['mp3'] }
    };
    $wsp.enabledPlugins = [];
    $wsp.pluginMediaElements = {};
    $wsp.htmlMediaElement = null;
    if (typeof Audio != "undefined") {
        $wsp.htmlMediaElement = new Audio();
        $wsp.htmlMediaElement.preload = "load";
        $wsp.htmlMediaElement.autoplay = true;
    }
    else {
        $wsp.htmlMediaElement = {
            canPlayType: function (type) {
                return "";
            }
        };
    }
    $wsp.htmlMediaElement.isInited = false;
    $wsp.utility = {
        addEventListener: function (obj, eventName, fun) {
            if (window.addEventListener) {
                obj.addEventListener(eventName, fun, false);
            }
            else {
                obj.attachEvent(eventName, fun);
            }
        },
        secondsToTimeCode: function (time, forceHours) {
            var hours = Math.floor(time / 3600) % 24, minutes = Math.floor(time / 60) % 60, seconds = Math.floor(time % 60);
            return ((forceHours || hours > 0) ? (hours < 10 ? '0' + hours : hours) + ':' : '')
                + (minutes < 10 ? '0' + minutes : minutes) + ':'
                + (seconds < 10 ? '0' + seconds : seconds);
        },
        escapeHTML: function (s) {
            return s.toString().split('&').join('&amp;').split('<').join('&lt;').split('"').join('&quot;');
        },
        addPlugin: function (p, pluginName, mimeType, activeX, axDetect) {
            var version = [0, 0, 0], description, i, ax;
            var nav = window.navigator;
            if (typeof (window.navigator.plugins) != 'undefined' && typeof window.navigator.plugins[pluginName] == 'object') {
                description = window.navigator.plugins[pluginName].description;
                if (description && !(typeof window.navigator.mimeTypes != 'undefined' && window.navigator.mimeTypes[mimeType] && !window.navigator.mimeTypes[mimeType].enabledPlugin)) {
                    version = description.replace(pluginName, '').replace(/^\s+/, '').replace(/\sr/gi, '.').split('.');
                    for (i = 0; i < version.length; i++) {
                        version[i] = parseInt(version[i].match(/\d+/), 10);
                    }
                }
            }
            else if (typeof (window.ActiveXObject) != 'undefined') {
                try {
                    ax = new ActiveXObject(activeX);
                    if (ax) {
                        version = axDetect(ax);
                    }
                }
                catch (e) { }
            }
            if ($wsp.plugins[p]) {
                if (version[0] >= $wsp.plugins[p].version[0])
                    $wsp.enabledPlugins.push({ name: p, version: version, types: $wsp.plugins[p].types });
            }
        },
        absolutizeUrl: function (url) {
            var el = document.createElement('div');
            el.innerHTML = '<a href="' + $wsp.utility.escapeHTML(url) + '">x</a>';
            return el.firstChild.href;
        },
        isSong: function (url) {
            if (typeof (url) == "string") {
                var i = url.lastIndexOf(".mp3");
                var j = url.lastIndexOf(".wma");
                if ((i > 0 && url.substring(i) == ".mp3") || (j > 0 && url.substring(j) == ".wma"))
                    return true;
            }
            return false;
        },
        getFile: function (item) {
            var file = null;
            if (typeof (item) == "string" && $wsp.utility.isSong(item)) {
                file = item;
            }
            else if (typeof (item) == "object") {
                $.each(item, function (k, v) {
                    if ($wsp.utility.isSong(v))
                        file = v;
                });
            }
            return file;
        },
        getVolumeFromCookie: function () {
            return parseFloat(globals.cookies.get("wsp_volume"));
        },
        setVolumeToCookie: function (volume) {
            globals.cookies.set("wsp_volume", volume, globals.urlPrefix.host, "/", 356 * 24 * 3600 * 1000);
        },
        getIsMutedFromCookie: function () {
            var v = parseInt(globals.cookies.get("wsp_ismuted"));
            return v == 1;
        },
        setIsMutedToCookie: function (ismuted) {
            globals.cookies.set("wsp_ismuted", ismuted ? "1" : "0", globals.urlPrefix.host, "/", 356 * 24 * 3600 * 1000);
        }
    };
    $wsp.utility.addPlugin('flash', 'Shockwave Flash', 'application/x-shockwave-flash', 'ShockwaveFlash.ShockwaveFlash', function (ax) {
        var version = [], d = ax.GetVariable("$version");
        if (d) {
            d = d.split(" ")[1].split(",");
            version = [parseInt(d[0], 10), parseInt(d[1], 10), parseInt(d[2], 10)];
        }
        return version;
    });
    $wsp.utility.addPlugin('silverlight', 'Silverlight Plug-In', 'application/x-silverlight-2', 'AgControl.AgControl', function (ax) {
        var v = [0, 0, 0, 0],
            loopMatch = function (ax, v, i, n) {
                while (ax.isVersionSupported(v[0] + "." + v[1] + "." + v[2] + "." + v[3])) {
                    v[i] += n;
                }
                v[i] -= n;
            };
        loopMatch(ax, v, 0, 1);
        loopMatch(ax, v, 1, 1);
        loopMatch(ax, v, 2, 10000);
        loopMatch(ax, v, 2, 1000);
        loopMatch(ax, v, 2, 100);
        loopMatch(ax, v, 2, 10);
        loopMatch(ax, v, 2, 1);
        loopMatch(ax, v, 3, 1);
        return v;
    });
    $wsp.mediaHelper = {
        mediaMimeType: {
            "mp3": ['audio/mp3', 'audio/mpeg'],
            "wma": ['audio/x-ms-wma', 'audio/wma']
        },
        initPlugin: function (id) {
            var media = $wsp.pluginMediaElements[id];
            if (media) {
                if (media.pluginType == "flash")
                    media.pluginApi = media.pluginElement = document.getElementById(id);
                else if (media.pluginType == "silverlight") {
                    media.pluginElement = document.getElementById(id);
                    media.pluginApi = media.pluginElement.Content.API;
                }
                if (media.pluginApi != null)
                    media.successToInitPlugin(media);
            }
        },
        canAutoplay: function (media) {
            if (typeof media.pluginId == "undefined") {
                var ua = window.navigator.userAgent.toLowerCase();
                var isMobile = ua.match(/ipad/) || ua.match(/iphone/);
                return !isMobile;
            }
            return true;
        },
        createPlugin: function (source, startVolume, timeRate, pluginPath, pluginFiles, success) {
            var ext = source.substring(source.lastIndexOf(".") + 1);
            var sp = {};
            var hasPlus = false;
            $.each($wsp.enabledPlugins, function (i, item) {
                if ($.inArray(ext, item.types) >= 0) {
                    sp[item.name] = 1;
                    hasPlus = true;
                }
            });
            var pId = "wsp_player_" + new Date().getTime();
            var mimeTypes = $wsp.mediaHelper.mediaMimeType[ext];
            var canplay = false;
            $.each(mimeTypes, function (i, item) {
                if ($wsp.htmlMediaElement.canPlayType(item).replace(/no/, '') !== '') {
                    canplay = true;
                }
            });
            if(hasPlus){
                var isSafari = $.browser.safari && window.navigator.appVersion.toLowerCase().indexOf("chrome") == -1;
                if (sp["flash"] && ( !isSafari || !canplay))
                    $wsp.pluginMediaElements[pId] = new $wsp.pluginMedia(pId, "flash", source, startVolume, timeRate, pluginPath, pluginFiles);
                else if (sp["silverlight"] && !canplay)
                    $wsp.pluginMediaElements[pId] = new $wsp.pluginMedia(pId, "silverlight", source, startVolume, timeRate, pluginPath, pluginFiles);
                else
                    $wsp.pluginMediaElements[pId] = null;
                if($wsp.pluginMediaElements[pId] != null){
                    if (typeof success == "function") {
                        $wsp.pluginMediaElements[pId].successToInitPlugin = success;
                    }
                    else
                        $wsp.pluginMediaElements[pId].successToInitPlugin = function () { };
                    return $wsp.pluginMediaElements[pId];
                }
            }
            if (canplay) {
                if (!$wsp.htmlMediaElement.isInited) {
                    var obj = {
                        setCurrentTime: function (time) {
                            this.currentTime = time;
                        },

                        setMuted: function (muted) {
                            this.muted = muted;
                        },

                        setVolume: function (volume) {
                            this.volume = volume;
                        },

                        stop: function () {
                            this.pause();
                            this.currentTime = 0;
                        },

                        setSrc: function (url) {
                            if (typeof url == 'string')
                                this.src = url;
                        }
                    };
                    for (var o in obj){
                        try{
                            $wsp.htmlMediaElement[o] = obj[o];
                        }
                        catch(ex){
                                                                             "console" in window &&!!console.log && console.log(ex);
                        }
                    }
                    $wsp.htmlMediaElement.isInited = true;
                    success($wsp.htmlMediaElement);
                }
                $wsp.htmlMediaElement.setVolume(startVolume);
                $wsp.htmlMediaElement.setSrc(source);
                $wsp.htmlMediaElement.load();
                return $wsp.htmlMediaElement;
            }
            return null;
        },
        fireEvent: function (id, eventName, values) {
            var media = $wsp.pluginMediaElements[id];
            var e = { type: eventName, target: media };
            if (media) {
                for (var v in values) {
                    try{
                        media[v] = values[v];
                        e[v] = values[v];
                    }
                    catch(ex){
                                                                         "console" in window &&!!console.log && console.log(ex);
                    }
                }
                e.buffered = e.target.buffered = {
                    start: function (index) {
                    },
                    end: function (index) {
                        return e.bufferedTime || 0;
                    },
                    length: 1
                };
                media.dispatchEvent(e.type, e);
            }
        },
        createContainer: function (pluginId) {
            var pcId = pluginId + "_container";
            return $('<div id="' + pcId + '" style="width:1px; height:1px; line-height:0; font-size:0; overflow:hidden; position:absolute;top:-9999px; left:-9999px;"></div>').appendTo("body");
        },
        destory: function (media) {
            if (media) {
                media.pause();
                if (typeof media.pluginId == "undefined") {
                    media.setSrc("");
                }
                else {
                    var obj = document.getElementById(media.pluginId);
                    var pc = $(obj).parent();
                    if (obj && (obj.nodeName == "OBJECT" || obj.nodeName == "EMBED")) {
                        if ($.browser.msie) {
                            obj.style.display = "none";
                            (function () {
                                if (obj.readyState == 4) {
                                    if (obj) {
                                        for (var i in obj) {
                                            try{
                                                if (typeof obj[i] == "function") {
                                                    obj[i] = null;
                                                }
                                            }
                                            catch(ex){
                                                "console" in window &&!!console.log && console.log(ex);
                                            }
                                        }
                                        obj.parentNode.removeChild(obj);
                                        pc.remove();
                                    }
                                }
                                else {
                                    setTimeout(arguments.callee, 10);
                                }
                            })();
                        }
                        else {
                            obj.parentNode.removeChild(obj);
                            pc.remove();
                        }
                    }
                    delete $wsp.pluginMediaElements[media.pluginId];
                }
            }
        }
    };
    $wsp.pluginMedia = function (pluginId, pluginType, source, startVolume, timeRate, pluginPath, pluginFiles) {
        this.pluginId = pluginId;
        this.pluginType = pluginType;
        this.autoplay = false;
        this.pluginApi = null;
        this.pluginElement = null;
        this.paused = true;
        this.src = null;
        this.volume = startVolume;
        this.muted = false;
        this.currentTime = 0;
        this.buffered = {
            start: function (index) {
                return 0;
            },
            end: function (index) {
                return 0;
            },
            length: 1
        };
        this.duration = 0;
        this.events = {};
        var THIS = this;
        this.play = function () {
            if (this.pluginApi != null) {
                this.paused = false;
                try{
                    this.pluginApi.playMedia();
                }
                catch(exp){

                }
            }
            return this;
        };
        this.pause = function () {
            if (this.pluginApi != null) {
                this.paused = true;
                try{
                    this.pluginApi.pauseMedia();
                }
                catch(exp){

                }
            }
            return this;
        };
        this.setSrc = function (url) {
            if (typeof url == 'string') {
                this.src = $wsp.utility.absolutizeUrl(url);
                this.pluginApi.setSrc($wsp.utility.absolutizeUrl(url));
            }
            return this;
        };
        this.setCurrentTime = function (time) {
            if (this.pluginApi != null) {
                this.pluginApi.setCurrentTime(time);
                this.currentTime = time;
            }
            return this;
        };
        this.setVolume = function (volume) {
            if (this.pluginApi != null) {
                this.pluginApi.setVolume(volume);
                this.volume = volume;
            }
            return this;
        };
        this.setMuted = function (muted) {
            if (this.pluginApi != null) {
                this.pluginApi.setMuted(muted);
                this.muted = muted;
            }
            return this;
        };

        this.addEventListener = function (eventName, callback) {
            this.events[eventName] = callback;
            return this;
        };
        this.dispatchEvent = function (eventName) {
            var args, callbacks = this.events[eventName];
            if (typeof (callbacks) == "function") {
                args = Array.prototype.slice.call(arguments, 1);
                if (args.length > 0)
                    callbacks(args[0]);
            }
            return this;
        };
        var html = "";
        var initVars = [
            'id=' + this.pluginId,
            'autoplay=' + ((this.autoplay) ? "true" : "false"),
            'startvolume=' + startVolume,
            'timerate=' + timeRate
        ];
        source = (source !== null) ? $wsp.utility.absolutizeUrl(source) : '';
        if (source) {
            if (this.pluginType == 'flash')
                initVars.push('file=' + encodeURI(source));
            else
                initVars.push('file=' + source);
        }
        switch (this.pluginType) {
            case "silverlight":
                html = '<object data="data:application/x-silverlight-2," type="application/x-silverlight-2" id="' + this.pluginId + '" name="' + this.pluginId + '" >' +
                    '<param name="initParams" value="' + initVars.join(',') + '" />' +
                    '<param name="autoUpgrade" value="true" />' +
                    '<param name="enableHtmlAccess" value="true" />' +
                    '<param name="source" value="' + pluginPath + pluginFiles["silverlight"] + '?' + new Date().getTime() + '" />' +
                    '</object>';
                break;
            case "flash":
                if ($.browser.msie) {
                    html = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" ' + 'id="' + this.pluginId + '" name="' + this.pluginId + '">' +
                        '<param name="movie" value="' + pluginPath + pluginFiles["flash"] + '?' + new Date().getTime() + '" />' +
                        '<param name="flashvars" value="' + initVars.join('&amp;') + '" />' +
                        '<param name="allowScriptAccess" value="always" />' +
                        '</object>';
                }
                else {
                    html = '<embed id="' + this.pluginId + '" name="' + this.pluginId + '" ' +
                        'allowScriptAccess="always" ' +
                        'type="application/x-shockwave-flash" pluginspage="//www.macromedia.com/go/getflashplayer" ' +
                        'src="' + pluginPath + pluginFiles["flash"] + '?' + new Date().getTime() + '" ' +
                        'flashvars="' + initVars.join('&') + '"></embed>';
                }
                break;
        }
        $wsp.mediaHelper.createContainer(pluginId).appendTo("body").html(html);
    };
    $wsp.mediaElement = function (playerBoxId, options) {
        var boxId = $(playerBoxId);
        var THIS = this;
        THIS.defaults = {
            startVolume: 0.8,
            autoplay: true,
            timeRate: 200,
            song: {},
            isSyncVolume: true,
            pluginPath: globals.urlPrefix.static + "js/wsingplayer/",
            pluginFiles: { silverlight: "slPlayer.xap", flash: "fplayer.swf" },
            loading: boxId.find(".wsp_loading"),
            stats: {
                enable: false,
                url: globals.urlPrefix.stat + "stat.ashx?Command=Listen&callback=?",
                interval: 10000,
                source: 0,
                referer: document.referrer,
                currentPage: window.location.href
            },
            control: {
                play: boxId.find(".wsp_c_play"),
                pause: boxId.find(".wsp_c_pause"),
                playStateChanged: function (media) {
                    if (media && media.paused) {
                        THIS.defaults.control.play.show();
                        THIS.defaults.control.pause.hide();
                    }
                    else {
                        THIS.defaults.control.play.hide();
                        THIS.defaults.control.pause.show();
                    }
                },
                cannotAutoplay: function () {
                    THIS.defaults.control.play.show();
                    THIS.defaults.control.pause.hide();
                }
            },
            progressBar: {
                current: boxId.find(".wsp_p_current"),
                buffered: boxId.find(".wsp_p_buffered"),
                total: boxId.find(".wsp_p_total"),
                drag: boxId.find(".wsp_p_drag"),
                isDrag: false
            },
            volume: {
                mute: boxId.find(".wsp_v_mute"),
                mutedClass: "wsp_v_mute_sel",
                current: boxId.find(".wsp_v_current"),
                total: boxId.find(".wsp_v_total"),
                drag: boxId.find(".wsp_v_drag"),
                muteStateChanged: function (isMuted) { }
            },
            currentTimeLabel: boxId.find(".wsp_currentTime"),
            TotalTimeLabel: boxId.find(".wsp_totaltime"),
            ResidueTimeLabel: boxId.find(".wsp_residueTime"),
            AllTimeLabel: boxId.find(".wsp_allTime"),
            FormatAllTime: function(current, total){
                var str =  $wsp.utility.secondsToTimeCode(current) + "/" + $wsp.utility.secondsToTimeCode(total);
                return str;
            },
            errorToPlay: function (player) {
            },
            unsupport: function (player) {
            },
            ended:function(){},
            customInit: function (player, media) { }
        };
        THIS.defaults = $.extend(true, {}, THIS.defaults, options);
        var player = null,
            playListItemTemplate = { "songID": NaN, "songType": null, "file": null, "songName": null, "singerID": NaN, "singer": null, "avatar": null };
        var songConverter = function(song){
            if(typeof song == "string" && $wsp.utility.isSong(song)){
                return { "file": song };
            }
            else if(typeof song == "object" && !!song.file && $wsp.utility.isSong(song.file)){
                return $.extend({}, playListItemTemplate, song);
            }
            return false;
        };
        var ismuted = false;
        if (THIS.defaults.isSyncVolume) {
            var cookieVolume = $wsp.utility.getVolumeFromCookie();
            if (isNaN(cookieVolume))
                $wsp.utility.setVolumeToCookie(THIS.defaults.startVolume);
            else
                THIS.defaults.startVolume = cookieVolume;
            ismuted = $wsp.utility.getIsMutedFromCookie();
        }
        if (THIS.defaults.startVolume < 0 || THIS.defaults.startVolume > 1) {
            THIS.defaults.startVolume = 1;
            $wsp.utility.setVolumeToCookie(THIS.defaults.startVolume);
        }
        THIS.getIsMuted = function(){
            return player != null &&  player.muted;
        };
        THIS.setTimeLabels = function(current, total, residue, allTime){
            if(!!current)
                THIS.defaults.currentTimeLabel = current;
            if(!!total)
                THIS.defaults.TotalTimeLabel = total;
            if(!!residue)
                THIS.defaults.ResidueTimeLabel = residue;
            if(!!allTime)
                THIS.defaults.AllTimeLabel = allTime;
        };
        THIS.getCurrentTime = function () {
            if (player)
                return player.currentTime;
            else
                return 0;
        };
        THIS.setCurrentTime = function(pos){
            if(!!THIS.song){
                var totalW =  THIS.defaults.progressBar.total.width();
                pos = parseInt(pos);
                if(isNaN(pos) || pos < 0)
                    pos = 0;
                else if(pos > totalW)
                    pos = totalW;
                THIS.defaults.progressBar.current.width(pos);
                if (player) {
                    var position = pos * player.duration / totalW;
                    player.setCurrentTime(position);
                    if (player.paused)
                        player.play();
                }
            }
        };
        THIS.setVolume = function(pos){
            pos = parseInt(pos);
            if(isNaN(pos) || pos < 0) pos = 0;
            THIS.defaults.volume.current.width(pos);
            THIS.defaults.volume.drag.css("left", pos);
            ismuted = false;
            if (player)
                player.muted = false;
            if (THIS.defaults.volume.mutedClass)
                THIS.defaults.volume.mute.removeClass(THIS.defaults.volume.mutedClass);
            if (player) {
                var val = pos / $(THIS.defaults.volume.total).width();
                if(val > 1 )
                    val = 1;
                else if(val <= 0)
                    val = 0;
                player.setVolume(val);
                THIS.defaults.startVolume = val;
                if (THIS.defaults.isSyncVolume) {
                    $wsp.utility.setVolumeToCookie(THIS.defaults.startVolume);
                    $wsp.utility.setIsMutedToCookie(player.muted);
                }
            }
        };
        THIS.playMedia = function () {
            if (player != null && THIS.playList.length > 0)
                player.play();
        };
        THIS.pauseMedia = function () {
            if (player != null)
                player.pause();
        };
        var prevTime = 0;
        var listenStats = {
            listener: null,
            execute: function (isEnd) {
                if (!!THIS.song) {
                    var currentTime = THIS.getCurrentTime();
                    isEnd = !!isEnd;
                    if(isEnd){
                        currentTime = player.duration;
                        if(!!listenStats.listener) {
                            clearInterval(listenStats.listener);
                            listenStats.listener = null;
                        }
                    }
                    if (currentTime > prevTime) {
                        prevTime = currentTime;
                        var _params = {
                            songID: parseInt(THIS.song.songID),
                            songType: THIS.song.songType,
                            singerID: THIS.song.singerID,
                            source: THIS.defaults.stats.source,
                            referer: THIS.defaults.stats.referer,
                            currentPage: THIS.defaults.stats.currentPage,
                            currentTime: currentTime,
                            totalTime: !!player ? player.duration : 0,
                            isEnd: isEnd
                        };
                        prevTime = isEnd ? 0 : prevTime;
                        if (!isNaN(_params.songID) && _params.songType != null && !isNaN(_params.singerID)) {
                            $.getJSON(THIS.defaults.stats.url, _params, function (result) { });
                        }
                    }
                }
            },
            start: function(){
                if (!listenStats.listener && THIS.defaults.stats.enable)
                    listenStats.listener = setInterval(listenStats.execute, THIS.defaults.stats.interval);
            },
            reset: function(){
                if(!!listenStats.listener){
                    prevTime = 0;
                    clearInterval(listenStats.listener);
                    listenStats.listener = null;
                }
            }

        };
        var logStats = {
            listener: null,
            start: function(){
                if(!logStats.listener && !!globals && !!globals.logListen){
                    logStats.listener = setTimeout(function(){
                        if (!!THIS.song) {
                            globals.logListen(THIS.song.songID, THIS.song.songType, THIS.song.singerID, globals.currentUserId);
                        }
                    }, 15000);
                }
            },
            reset: function(){
                if(!!logStats.listener){
                    clearTimeout(logStats.listener);
                    logStats.listener = null;
                }
            }
        };
        THIS.play = function (song) {
            song = songConverter(song);
            if (!!song) {
                if (player) {
                    THIS.pauseMedia();
                    $wsp.mediaHelper.destory(player);
                    player = null;
                }
                if(!THIS.song || song.file != THIS.song.file){
                    logStats.reset();
                    listenStats.reset();
                }
                THIS.song = song;
                THIS.defaults.currentTimeLabel.html("00:00");
                THIS.defaults.TotalTimeLabel.html("00:00");
                THIS.defaults.ResidueTimeLabel.html("00:00");
                THIS.defaults.AllTimeLabel.html(THIS.defaults.FormatAllTime(0, 0));
                THIS.defaults.progressBar.current.width(0);
                THIS.defaults.progressBar.buffered.width(0);
                var file = THIS.song.file;
                if (file) {
                    if (player != null) {
                        player.pause();
                        player.setSrc(file);
                        player.play();
                    }
                    else {
                        player = $wsp.mediaHelper.createPlugin(file, THIS.defaults.startVolume, THIS.defaults.timeRate, THIS.defaults.pluginPath, THIS.defaults.pluginFiles, function (media) {
                            media.addEventListener("loadstart", function (e) {
                                THIS.defaults.loading.show();
                            });
                            media.addEventListener("loadedmetadata", function (e) {
                                if (e.target.duration > 0)
                                    THIS.defaults.TotalTimeLabel.html($wsp.utility.secondsToTimeCode(e.target.duration));
                                THIS.defaults.loading.hide();
                            });
                            media.addEventListener("canplay", function (e) {
                                if (THIS.defaults.autoplay && $wsp.mediaHelper.canAutoplay(media) && e.target.paused) {
                                    media.play();
                                }
                            });
                            media.addEventListener("canplaythrough", function (e) {
                                THIS.defaults.progressBar.buffered.width(THIS.defaults.progressBar.total.width());
                            });
                            media.addEventListener("progress", function (e) {
                            });
                            media.addEventListener("timeupdate", function (e) {
                                var w = parseInt(THIS.defaults.progressBar.total.width() * e.target.currentTime / e.target.duration);
                                if (!isNaN(w)){
                                    THIS.defaults.progressBar.current.width(w);
                                    if(!THIS.defaults.progressBar.isDrag)
                                        THIS.defaults.progressBar.drag.css("left", w);
                                }
                                THIS.defaults.currentTimeLabel.html($wsp.utility.secondsToTimeCode(e.target.currentTime));
                                if (e.target.duration > 0){
                                    if(THIS.defaults.TotalTimeLabel.html() == "00:00")
                                        THIS.defaults.TotalTimeLabel.html($wsp.utility.secondsToTimeCode(e.target.duration));
                                    THIS.defaults.ResidueTimeLabel.html($wsp.utility.secondsToTimeCode(e.target.duration - e.target.currentTime));
                                }
                                THIS.defaults.AllTimeLabel.html(THIS.defaults.FormatAllTime(e.target.currentTime, e.target.duration > 0 ? e.target.duration : 0));
                                try {
                                    if (e.target.buffered && e.target.buffered.length > 0) {
                                        var maxw = THIS.defaults.progressBar.total.width();
                                        w = parseInt(maxw * e.target.buffered.end(0) / e.target.duration);
                                        if (!isNaN(w))
                                            THIS.defaults.progressBar.buffered.width(w);
                                    }
                                }
                                catch (ex) {
                                }
                            });
                            media.addEventListener("loadeddata", function (e) {
                            });
                            media.addEventListener("error", function (e) {
                                if ($wsp.utility.isSong(e.target.src) || !!e.target.pluginId)
                                    THIS.defaults.errorToPlay(THIS);
                            });
                            media.addEventListener("play", function (e) {
                                THIS.defaults.control.playStateChanged(player);
                                THIS.defaults.loading.hide();
                                logStats.start();
                                listenStats.start();
                                if (player)
                                    player.setMuted(ismuted);
                            });
                            media.addEventListener("playing", function (e) {
                                THIS.defaults.control.playStateChanged(player);
                                THIS.defaults.loading.hide();
                            });
                            media.addEventListener("pause", function (e) {
                                THIS.defaults.control.playStateChanged(player);
                                THIS.defaults.loading.hide();
                            });
                            media.addEventListener("ended", function (e) {
                                listenStats.execute(true);
                                if(typeof THIS.defaults.ended == "function")
                                    THIS.defaults.ended();
                                e.target.play();
                            });
                        });
                    }
                    if (player == null || typeof player == undefined)
                        THIS.defaults.unsupport(THIS);
                }
                else
                    THIS.defaults.errorToPlay(THIS);
            }
            return THIS;
        };
        var init = function () {
            THIS.bindEvents();
            if (ismuted) {
                if (THIS.defaults.volume.mutedClass)
                    THIS.defaults.volume.mute.removeClass(THIS.defaults.volume.mutedClass).addClass(THIS.defaults.volume.mutedClass);
                THIS.defaults.volume.current.width(0);
                THIS.defaults.volume.drag.css("left", 0);
            }
            else{
                var volumeVal = THIS.defaults.startVolume * THIS.defaults.volume.total.width();
                THIS.setVolume(volumeVal);
            }
            if (typeof (THIS.defaults.volume.muteStateChanged) == "function")
                THIS.defaults.volume.muteStateChanged(ismuted);
            if (typeof (THIS.defaults.customInit) == "function")
                THIS.defaults.customInit(THIS, player);
            THIS.play(THIS.defaults.song);
            if (THIS.defaults.autoplay)
                THIS.defaults.loading.show();
            if (player) {
                if (!THIS.defaults.autoplay || !$wsp.mediaHelper.canAutoplay(player)) {
                    THIS.defaults.control.cannotAutoplay();
                    THIS.defaults.loading.hide();
                }
            }
            return THIS;
        };
        THIS.bindEvents = function(){
            THIS.defaults.control.play.unbind("click.wsp").bind("click.wsp", function () {
                if (player && !!THIS.song)
                    player.play();
            });
            THIS.defaults.control.pause.unbind("click.wsp").bind("click.wsp", function () {
                if (player)
                    player.pause();
            });
            THIS.defaults.volume.mute.unbind("click.wsp").bind("click.wsp", function () {
                if (player) {
                    player.setMuted(!player.muted);
                    ismuted = player.muted;
                    if (THIS.defaults.isSyncVolume) {
                        $wsp.utility.setIsMutedToCookie(ismuted);
                    }
                    if (THIS.defaults.volume.mutedClass) {
                        if (ismuted && !$(this).hasClass(THIS.defaults.volume.mutedClass)) {
                            $(this).addClass(THIS.defaults.volume.mutedClass);
                            THIS.defaults.volume.current.width(0);
                            THIS.defaults.volume.drag.css("left", 0);
                        }
                        else if (!ismuted) {
                            $(this).removeClass(THIS.defaults.volume.mutedClass);
                            THIS.setVolume(THIS.defaults.startVolume * THIS.defaults.volume.total.width());
                        }
                    }
                    if (typeof (THIS.defaults.volume.muteStateChanged) == "function")
                        THIS.defaults.volume.muteStateChanged(player.muted);
                }
            });
            THIS.defaults.progressBar.total.unbind("click.wsp").bind("click.wsp", function (e) {
                if(!!THIS.song){
                    var left = $(this).offset().left;
                    var x = e.pageX;
                    THIS.defaults.progressBar.current.width(x - left);
                    THIS.defaults.progressBar.drag.css("left", x - left);
                    if (player) {
                        var position = (x - left) * player.duration / $(this).width();
                        player.setCurrentTime(position);
                        if (player.paused)
                            player.play();
                    }
                }
            });
            THIS.defaults.volume.total.unbind("click.wsp").bind("click.wsp", function (e) {
                var left = $(this).offset().left;
                var x = e.pageX;
                var pos = x - left;
                THIS.setVolume(pos);
            });
            THIS.defaults.progressBar.current.unbind("selectstart.wsp").bind("selectstart.wsp", function(){ return false; });
            THIS.defaults.progressBar.buffered.unbind("selectstart.wsp").bind("selectstart.wsp", function(){ return false; });
            THIS.defaults.progressBar.total.unbind("selectstart.wsp").bind("selectstart.wsp", function(){ return false; });
            THIS.defaults.progressBar.drag.unbind("selectstart.wsp").bind("selectstart.wsp", function(){ return false; });
            THIS.defaults.progressBar.drag.unbind("mousedown.wsp").bind("mousedown.wsp", function () {
                var self = $(this), dv1 = $(".wsp_p_total");
                var minX = dv1.offset().left, maxX = minX + dv1.width(), w = self.width();
                THIS.defaults.progressBar.isDrag = true;
                $(document).unbind("mousemove.wsp_pos").bind("mousemove.wsp_pos", function (e) {
                    var cx = e.pageX;
                    if (cx > maxX)
                        self.css("left", maxX - w);
                    else if (cx < minX)
                        self.css("left", 0);
                    else {
                        self.css("left", cx - w - minX);
                    }
                });
            });
            THIS.defaults.volume.current.unbind("selectstart.wsp").bind("selectstart.wsp", function(){ return false; });
            THIS.defaults.volume.total.unbind("selectstart.wsp").bind("selectstart.wsp", function(){ return false; });
            THIS.defaults.volume.drag.unbind("selectstart.wsp").bind("selectstart.wsp", function(){ return false; });
            THIS.defaults.volume.drag.unbind("mousedown.wsp").bind("mousedown.wsp", function () {
                var self = $(this), dv1 = $(".wsp_v_total");
                var minX = dv1.offset().left, maxX = minX + dv1.width(), w = self.width();
                THIS.defaults.volume.isDrag = true;
                $(document).unbind("mousemove.wsp_volume").bind("mousemove.wsp_volume", function (e) {
                    var cx = e.pageX;
                    if (cx > maxX)
                        self.css("left", maxX - w);
                    else if (cx < minX)
                        self.css("left", 0);
                    else {
                        self.css("left", cx - w - minX);
                    }
                });
            });
            $(document).unbind("mouseup.wsp").bind("mouseup.wsp", function () {
                var pos = 0;
                if(THIS.defaults.progressBar.isDrag){
                    $(document).unbind("mousemove.wsp_pos");
                    pos = parseInt(THIS.defaults.progressBar.drag.css("left"));
                    THIS.setCurrentTime(pos);
                    THIS.defaults.progressBar.isDrag = false;
                }
                if(THIS.defaults.volume.isDrag){
                    var total = THIS.defaults.volume.total.width();
                    $(document).unbind("mousemove.wsp_volume");
                    pos = parseInt(THIS.defaults.volume.drag.css("left"));
                    if(pos > total) pos = total;
                    THIS.setVolume(pos);
                    pos = pos - THIS.defaults.volume.drag.width();
                    if(pos < 0) pos = 0;
                    THIS.defaults.volume.drag.css("left", pos);
                    THIS.defaults.volume.isDrag = false;
                }
            });
        };
        return init();
    };
    window.wSingPlayer = window.$wsp = $wsp;
    module.exports = $wsp;
});