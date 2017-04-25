
<div id="area" style="width:600px; height:600px; background-color:#cccccc" contenteditable>
    <img id="preview" width="600">
</div>
<img id="load" src="http://ad.duowan.com/static/img/loading.gif" style="display:none;">

<script>
//粘贴图片文件
var area = document.getElementById('area');
var preview = document.getElementById('preview');
var load = document.getElementById('load');
function sendFile(file, area, preview){
    loading(true);
    var xhr = new XMLHttpRequest();
    var fd = new FormData();
    fd.append('img', file);
    xhr.open('POST', 'http://video.duowan.com/?r=test/upImg', true);
    xhr.onload = function(){
        var jsonText = xhr.responseText;
        var j = JSON.parse(jsonText);
        var ht = "";
        for (var i in j) ht += '<p>' + i + '：' + j[i] + '</p>';
        area.appendChild(document.createElement('p')).innerHTML = ht;
        preview.src = j.url;
        loading(false);
    };
    xhr.send(fd);
}
function loading(is){
    if (is) {
        load.style.display = '';
        area.style.display = 'none';
    } else {
        load.style.display = 'none';
        area.style.display = '';
    }
}
area.ondrop = function(evt){
    var files = evt.dataTransfer.files;
    if (files.length >= 1) {
        sendFile(files[0], area, preview);
    }
    return false;
}
area.onpaste = function(evt){
    var items = evt.clipboardData.items;
    if (items.length >= 1) {
        var file = evt.clipboardData.items[0].getAsFile();
        sendFile(file, area, preview);
    }
};
//粘贴图
</script>
