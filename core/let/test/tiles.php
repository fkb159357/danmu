<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Larele</title>
	
	<link href="./res/lib/bootstrap3/css/bootstrap.min.css" rel="stylesheet">
	<link href="./res/lib/bootstarp-material-design/css/ripples.min.css" rel="stylesheet">
	<link href="./res/lib/bootstarp-material-design/css/material-wfont.min.css" rel="stylesheet">
	
	<script src="./res/lib/flat-ui/js/jquery.min.js"></script>
	<script src="./res/lib/underscore-1.7-min.js"></script>
	<script src="./res/lib/momentjs/moment.min.js"></script>
	<script src="./res/lib/momentjs/moment-with-locales.min.js"></script>
	<script src="./res/lib/di.js"></script>
</head>
<body>

	<div id="a" class="container" style="margin-top: 20px;"></div>
    <script>
    $(function(){

        var imgs = ['./res/biz/shell/bg.jpg', './res/biz/shell/login_bg_rev.jpg']
        
        function getAvatars(){
            return $.get('./?audio/randAvatars/300', function(j){
                $.each(j.data, function(i, e){
                    imgs.push(e.avatar)
                })
            }, 'jsonp')
        }
        
        function tiles(){
    	    var e = '<div class="well col-xs-1 diy-a"></div>'
    	    for (var i = 0; i < 300; i ++) {
    		    $('#a').append(e)
    		    var diyA = $('#a').children('.diy-a:last')
    		    var diyAW = diyA.css('width')
    		    var pos = parseInt(Math.random()*2)
    		    var fontColor = pos ? 'white' : 'black'
    		    diyA.css({
    		        'height': diyAW,
    		        'background-position': '100% 100%',
    		        'background-image': "url('" + imgs[pos] + "')",
    		        'color': fontColor,
    		        'font-size': parseInt(diyAW.replace('px',''))*30/100+'px',
    		        'text-align': 'center',
    		        'line-height': parseInt(diyAW.replace('px',''))*2/3+'px',
    		        'cursor': 'pointer'
    		    }).text(i+1)
    	    }
        }
        
        function tileMissing(){
            $('#a').children('.diy-a').bind('click', function(){
                $(this).fadeOut('fast')
            })
        }

        function tileMousemove(){
            $('#a>.diy-a').each(function(i, e){
                var rawImg = $(e).css('background-image')
                $(e).mouseover(function(){
                    $(this).css({
                        'background-image': "url('" + imgs[i+2] + "')"
                    })
                })
                $(e).mouseout(function(){
                    $(this).css({
                        'background-image': rawImg
                    })
                })
            })
        }
        
        ! function(){
            tiles()
            tileMissing()
            getAvatars().then(function(){
                tileMousemove()
            }, function(){})
        }()
        
    })
    </script>

	<script src="./res/lib/bootstrap3/js/bootstrap.min.js"></script>
	<script src="./res/lib/bootstarp-material-design/js/ripples.min.js"></script>
	<script src="./res/lib/bootstarp-material-design/js/material.min.js"></script>
	<script>
	    $(document).ready(function() {
	        $.material.init();
	        $('body').css({
	            'font-family' : '微软雅黑',
	            'font-size' : '18px'
	        });
	    });
	</script>
</body>
</html>