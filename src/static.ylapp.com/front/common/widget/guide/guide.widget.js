/**
 * 新手引导挂件
 * Author: 陆楚良
 * Date: 2014/09/23
 * 效果：页面右下角出现一个浮动的连接块
 */
;var Widgets = function(mod, window, document, $, shs){
	if(shs.cookie("widget_guide_isclose")=="1" )return;
	/*--- 私有变量 Begin ---*/
	// html内容
	var Private_html= '<div class="widget-guide">'
					+ 	'<div class="widget-guide-fw">'
					+ 		'<a href="javascript:;" title="关闭" class="widget-guide-close"><span>×</span></a>'
					+ 		'<a href="${link}" target="_blank" title="${title}" class="widget-guide-link">'
					+			'<img src="${img}" alt="${title}" />'
					+		'</a>'
					+ 	'</div>'
					+ '</div>';
	// $(window)
	var Private_$w = $(window);
	// $(document)
	var Private_$d = $(document);
	// $("body, html")
	var Private_$b = $("body, html");

	/*--- 私有变量 End   ---*/



	// 挂件对象
	var Widget = {
		// 存储DOM专用对象
		DOM:{},
		init:function(data){
			/*--- DOM存储 Begin ---*/
			var D = Widget.DOM,W = D.widget = $(Private_html.replace(/\$\{link\}/g, data.link).replace(/\$\{img\}/g, data.img).replace(/\$\{title\}/g, data.title));
			D.fw  = W.find(".widget-guide-fw");
			D.close = W.find(".widget-guide-close");
			/*--- DOM存储 End   ---*/

			/*--- 事件绑定 Begin ---*/
			//关闭按钮
			D.close.click(function(){Widget.close()});
			/*--- 事件绑定 End   ---*/

			$(document.body).append(D.widget);
			Widget.show();
		},
		/**
		 * 显示
		 */
		show:function(){
			Widget.DOM.fw.show();
		},
		/**
		 * 隐藏
		 */
		close:function(){
			Widget.DOM.close.unbind("click");
			Widget.DOM.widget.remove();
			var d = new Date();
			shs.cookie("widget_guide_isclose", "1", {expires: new Date(d.getFullYear(),d.getMonth(),d.getDate()+1), domain: window.location.host.replace(/(\w+\.)*(\w+\.com)/, '.$2'), path: "/"});	// 写入cookie，一天内不再显示
			delete mod.guide;
		}
	};


	mod.guide = Widget;
	return mod;				// 返回全局挂件列表

}(window.Widgets||{}, window, document, jQuery, shs);