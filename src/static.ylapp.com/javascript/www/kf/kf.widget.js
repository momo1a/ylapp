(function(window,document,$){
	var addStyle = function(str){
		var css = document.getElementById('CL-Loader-inline-css');
		if (!css) {
			css = document.createElement('style');
			css.type = 'text/css';
			css.id = 'CL-Loader-inline-css';
			document.getElementsByTagName('head')[0].appendChild(css);
		}
		if (css.styleSheet) {
			css.styleSheet.cssText = css.styleSheet.cssText + str;
		} else {
			css.appendChild(document.createTextNode(str));
		}
	};

	var style = '#Widget-tempKF{ overflow:hidden; width: 60px; height: 242px; position: fixed; right: 0px; bottom: 170px; z-index:100; _position: absolute; _top: expression(eval(document.documentElement.scrollTop+document.documentElement.clientHeight-412));}'
			  + '.Widget-tempKF-icon,.Widget-tempKF-close{background: url('+shs.site("static")+'javascript/www/kf/kf.png) 0 0 no-repeat;}'
			  + '.Widget-tempKF-icon{ position:absolute; top:10px; right:10px; z-index:2; cursor: pointer; display:block; width:48px; height:58px; border: 1px solid #E2E2E2; background-color: #fff; background-position: -1px center; }'
			  + '.Widget-tempKF-icon:hover{ background-position: -51px center; }'
			  + '.Widget-tempKF-close{ cursor: pointer; position:absolute; top:-10px; right:-10px; width:20px; height:20px; background-position:-105px -18px;}'
			  + '.Widget-tempKF-body{ width: 130px; height: 230px; border: 1px solid #E2E2E2; position: absolute; z-index:1; right:10px; top:10px; background-color: #fff; }'
			  + '.Widget-tempKF-body h4{ color: #403C3D; font-weight: normal; font-size: 14px; font-family: "Microsoft YaHei"; border-bottom: 1px solid #E2E2E2; padding: 10px 0; text-align: center; }'
			  + '.Widget-tempKF-body dl{ margin: 0 20px; }'
			  + '.Widget-tempKF-body dt{ padding-top: 10px; }'
			  + '.Widget-tempKF-body dd{ padding-top: 5px; }';

	var html  = '<div id="Widget-tempKF">'
			  + 	'<a class="Widget-tempKF-icon" href="javascript:;" title="点击展开客服">&nbsp;</a>'
			  + 	'<div class="Widget-tempKF-body">'
			  + 		'<span class="Widget-tempKF-close" title="点击收起客服"></span>'
			  + 		'<h4>众划算客服</h4>'
			  + 		'<dl>'
			  + 			'<dt>在线旺旺</dt>'
			  + 			'<dd><a target="_blank" href="http://www.taobao.com/webww/ww.php?ver=3&touid=%E6%98%AF%E5%88%92%E7%AE%97008&siteid=cntaobao&status=1&charset=utf-8" ><img border="0" src="http://amos.alicdn.com/online.aw?v=2&uid=%E6%98%AF%E5%88%92%E7%AE%97008&site=cntaobao&s=1&charset=utf-8" alt="点击这里给我发消息" /></a></dd>'
			  + 			'<dd><a target="_blank" href="http://www.taobao.com/webww/ww.php?ver=3&touid=%E6%98%AF%E5%88%92%E7%AE%97009&siteid=cntaobao&status=1&charset=utf-8" ><img border="0" src="http://amos.alicdn.com/online.aw?v=2&uid=%E6%98%AF%E5%88%92%E7%AE%97009&site=cntaobao&s=1&charset=utf-8" alt="点击这里给我发消息" /></a></dd>'
			  + 		'</dl>'
			  + 		'<dl>'
			  + 			'<dt>在线QQ</dt>'
			  + 			'<dd><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1650079408&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:1650079408:51" alt="点击这里给我发消息" title="点击这里给我发消息"/></a></dd>'
			  + 			'<dd><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2825611179&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:2825611179:51" alt="点击这里给我发消息" title="点击这里给我发消息"/></a></dd>'
			  + 		'</dl>'
			  + 	'</div>'
			  + '</div>';

	addStyle(style);
	var $html = $(html);
	var $icon = $html.find('.Widget-tempKF-icon');
	var $body = $html.find('.Widget-tempKF-body');
	var lock  = true;
	$icon.click(function(){
		if(lock)return;
		lock = true;
		$html.css({width:60});
		$icon.css({right:10}).animate({right:-50},200,function(){
			$icon.hide();
			$html.css({width:142});
			$body.css({right:-132}).show().animate({right:10},300,function(){
				$body.css("overflow","visible");	/*IE6 BUG*/
				lock=false;
			});
		});
	});
	$body.find(".Widget-tempKF-close").click(function(){
		if(lock)return;
		lock = true;
		$html.css({width:142});
		$body.stop().animate({right:-132},200,function(){
			$body.hide();
			$html.css({width:60});
			$icon.css({right:-50}).show().animate({right:10},300,function(){
				lock=false;
			});
		});
	});

	$(document.body).append($html);
	$icon.css({right:-50}).hide();
	$html.css({width:142});
	$body.css({right:-132}).show().animate({right:10},500,function(){
		$body.css("overflow","visible");	/*IE6 BUG*/
		lock=false;
	});
})(window,document,jQuery);