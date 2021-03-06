
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
        <style type="text/css">
            #drop-area{
                width: 500px;
                height: 300px;
                text-align: center;
                line-height: 300px;
                font-size: 30px;
                font-weight: bolder;
                background-color: #dddddd;
                border: dotted 3px #999;
                cursor: pointer;
                position: relative;
            }
            #files-area>li>span{
                display: inline-block;
                margin-right: 20px;
            }
            #files-area>li>input.img-src{
                width: 500px;
                color: blue;
                display: block;
                margin-top: 5px;
                margin-bottom: 5px;
                cursor: pointer;
            }
            #paste-area{
                position: absolute;
                bottom: 0;
                right: 0;
                width: 100px;
                height: 30px;
                line-height: 30px;
                font-size: 10px;
                text-align: center;
                background-color: #eee;
                border-left: dotted 3px #bbb;
                border-top: dotted 3px #bbb;
                cursor: copy;
                z-index: 2;
            }
            #paste-area.paste-mode{
                width: inherit;
                height: inherit;
                line-height: inherit;
                font-size: inherit;
            }
        </style>
    </head>
    <body>
        <!-- 隐藏的file input -->
        <input id="file-input" type="file" style="display:none" multiple="multiple">
        <!-- 用于拖放上传，也可用于间接点击file input -->
        <div id="drop-area">
            点击此处或拖放文件上传
            <div id="paste-area">切换粘贴模式</div>
        </div>
        <ul id="files-area"></ul>

        <script>
            var dropArea = document.getElementById('drop-area');
            var filesArea = document.getElementById('files-area');
            var fileInput = document.getElementById('file-input');
            var pasteArea = document.getElementById('paste-area');

            function initDrop(callback){
                dropArea.addEventListener('dragenter', function(evt){
                    evt.stopPropagation();
                    evt.preventDefault();
                }, false);
                dropArea.addEventListener('dragover', function(evt){
                    evt.stopPropagation();
                    evt.preventDefault();
                }, false);
                dropArea.addEventListener('drop', function(evt){
                    filesArea.innerHTML = '';
                    evt.stopPropagation();
                    evt.preventDefault();
                    callback.call(this, evt);
                }, false);
            }

            initDrop(function(evt){
                var files = evt.dataTransfer.files;
                loopFiles(files, filesArea);
            });

            ~ function initPaste(){
                pasteArea.addEventListener('click', function(evt){
                    evt.stopPropagation();
                    evt.preventDefault();
                    if (this.className == 'paste-mode') {
                        this.innerText = '切换粘贴模式';
                        this.className = '';
                    } else {
                        this.innerText = '粘贴图片二进制数据到此处';
                        this.className = 'paste-mode';
                    }
                }, false);
                pasteArea.addEventListener('paste', function(evt){
                    filesArea.innerHTML = '';
                    var items = evt.clipboardData.items;
                    var files = [];
                    if (items.length >= 1) {
                        var file = evt.clipboardData.items[0].getAsFile();
                        file && files.push(file);
                    }
                    loopFiles(files, filesArea);
                }, false);
            }();

            ~ function initFileInput(){
                fileInput.addEventListener('change', function(evt){
                    filesArea.innerHTML = '';
                    var files = this.files;
                    loopFiles(files, filesArea);
                }, false);
                dropArea.addEventListener('click', function(evt){
                    if(!! document.all) {
                        fileInput.select();
                        document.execCommand("delete");
                    } else {
                        fileInput.value = '';
                    }
                    fileInput.click();
                }, false);
            }();

            function loopFiles(files, filesArea){
                if (files.length == 0) {
                    alert('找不到文件资源');
                    return false;
                }
                if (files.length > 15) {
                    alert('选择文件过多');
                    return false;
                }
                for (var i = 0; i < files.length; i ++) {
                    var file = files[i];
                    //if (-1 != ['image/jpeg', 'image/png', 'image/gif'].indexOf(file.type.toLowerCase())) {
                        var fileLi = renderFileBeforeUp(file, filesArea);
                        upFile(file, {
                            progressbar: fileLi.getElementsByClassName('up-progress')[0],
                            loading: fileLi.getElementsByClassName('up-loading')[0], 
                            srcBar: fileLi.getElementsByClassName('img-src')[0],
                            preview: fileLi.getElementsByClassName('img-preview')[0]
                        });
                    //}
                }
            }

            function renderFileBeforeUp(file, filesArea){
                var li = document.createElement('li');
                var span = document.createElement('span');
                span.innerText = file.name + ' [' + file.size + 'Bytes]';
                var progress = document.createElement('span');
                progress.innerText = '0%';
                progress.className = 'up-progress';
                var loading = document.createElement('img');
                loading.src = 'https://s1.dwstatic.com/duowanvideo/20170503/09/5103534.gif';
                loading.className = 'up-loading';
                loading.width = 20;
                loading.height = 20;
                var src = document.createElement('input');
                src.className = 'img-src';
                src.style.display = 'none';
                var preview = new Image();
                preview.className = 'img-preview';
                preview.style.display = 'none';
                preview.height = 80;
                li.appendChild(span);
                li.appendChild(loading);
                li.appendChild(progress);
                li.appendChild(src);
                li.appendChild(preview);
                filesArea.appendChild(li);
                return li;
            }

            function upFile(file, options){
                var xhr = new XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(evt){
                    if (evt.lengthComputable) {
                        var perc = Math.round(evt.loaded * 100 / evt.total);
                        if (perc < 100) {
                            options.progressbar.innerText = perc + '%';
                        }
                    }
                }, false);
                xhr.upload.addEventListener('load', function(evt){
                    //options.progressbar.innerText = '100%';
                    options.progressbar.innerText = '上传完毕，处理中...';
                }, false);
                xhr.addEventListener('load', function(evt){
                    var jsonText = xhr.responseText;
                    var j = JSON.parse(jsonText);
                    options.loading.style.display = 'none';
                    if (j.code != 0) {
                        options.progressbar.innerText = j.msg;
                        options.progressbar.style.color = 'red';
                    } else {
                        j.url = j.url.replace(/^https?(\:\/\/)/, 'https$1');
                        options.progressbar.innerText = '上传成功！';
                        options.srcBar.value = j.url
                        options.srcBar.style.display = 'block';
                        options.srcBar.onclick = function(){this.select();};
                        options.preview.src = j.url;
                        options.preview.style.display = '';
                    }
                }, false);
                xhr.addEventListener('error', function(error){
                    alert('error: ' + error);
                }, false);
                xhr.open('POST', 'https://ad.duowan.com/?r=test/otherUpload&hehe=1', true);
                var fd = new FormData();
                fd.append('filedata', file);
                xhr.withCredentials = true;
                xhr.send(fd);
            }
        </script>

        <!-- 兼容性检测 -->
        <script>
            var agMatch = navigator.userAgent.match(/chrome\/(\d+)\.|msie\s*(\d+)\.|firefox\/(\d+)\./i) || ['', 0, 0, 0];
            var checkBrowser = false;
            if (/chrome/i.test(agMatch[0]) && parseInt(agMatch[1]) >= 35) {
                checkBrowser = true;
            } else if (/msie/i.test(agMatch[0]) && parseInt(agMatch[2]) >= 10) {
                checkBrowser = true;
            } else if (/firefox/i.test(agMatch[0]) && parseInt(agMatch[3]) >= 40) {
                checkBrowser = true;
            }
            if (! checkBrowser) {
                document.write('Only support for Chrome 35+, MSIE 10+, Firefox 40+ !');
            }
        </script>
    </body>
</html>