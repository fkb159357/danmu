var Root = {};

Root.tourist = function(){
    alert('this is tourist');
};


//需要访问时执行的
(Root.init = function(){
    var client_ip = $('#client_ip').val();
    RootCommon.report_ip(client_ip);
})();