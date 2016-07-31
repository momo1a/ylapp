var Widgets = function(mod, window, document, ua, $, shs){
	if(shs.cookie("widget_remind_isclose")=="1")return;
	/*--- 私有变量 Begin ---*/

	// html内容
	var Private_html= '<div class="widget-remind">'
					+ 	'<div class="widget-remind-body">'
					+ 		'<a class="widget-remind-close" href="javascript:;" title="关闭">关闭</a>'
					+ 		'<div class="widget-remind-content">'
					+ 			'<span class="widget-remind-info"></span>'
					+ 			'<a class="widget-remind-btn" target="_blank" href="'+shs.site('list')+'new/">快去抢</a>'
					+ 			'<span class="widget-remind-qrcode"></span>'
					+ 		'</div>'
					+ 	'</div>'
					+ '</div>';

	// 是否是ie6
	var Private_ie6= $("html").hasClass("ie6")?!0:ua.indexOf("MSIE")>0?Number(ua.match(/MSIE *([0-9\.]+);/i)[1])<7:!1;
	// $(window)
	var Private_$w = $(window);
	// $(document)
	var Private_$d = $(document);
	// 是否显示
	var Private_isShow = false;
	// 是否关闭
	var Private_isClose= false;
	// 存储窗口滚动监控方法
	var Private_scroll = null;

	/*--- 私有变量 End   ---*/

	// 初始化方法
	var init = function(){
		/*--- DOM存储 Begin ---*/
		var D = Widget.DOM,W = D.widget = $(Private_html);
		D.body  = W.find(".widget-remind-body");
		D.close = W.find(".widget-remind-close");
		/*--- DOM存储 End   ---*/

		/*--- 私有变量初始化 Begin ---*/
		Private_scroll = function(){!Private_isClose && Private_$d.scrollTop()>0 ? Widget.show() : Widget.hide()}; // 监控窗口滚动，当滚到顶部时候隐藏
		/*--- 私有变量初始化 End   ---*/

		/*--- 事件绑定 Begin ---*/
		Private_$w.scroll(Private_scroll).scroll(); // 监控窗口滚动，当滚到顶部时候隐藏
		D.close.click(function(){Widget.close()}); 	// 关闭按钮
		/*--- 事件绑定 End   ---*/

		$(document.body).append(D.widget);
		return Widget;
	};

	// 挂件对象
	var Widget = {
		// 存储DOM专用对象
		DOM:{},
		/**
		 * 显示
		 */
		show:function(){
			if(Private_isClose)return;
			Private_ie6 && Widget.DOM.widget.css('top', (Private_$d.scrollTop()+Private_$w.height())+"px");
			if(Private_isShow)return;Private_isShow=true;
			Widget.DOM.body.stop().show().animate({top:-90});
		},
		/**
		 * 隐藏
		 */
		hide:function(){
			if(!Private_isShow)return;Private_isShow=false;
			var B = Widget.DOM.body;
			B.stop().animate({top:0}, function(){B.hide()});
		},
		/**
		 * 关闭，当关闭以后，show和hide方法都会失效
		 */
		close:function(){
			Private_isClose=true;
			Widget.DOM.body.stop().animate({top:0}, function(){Widget.DOM.widget.remove()});
			Private_$w.unbind('scroll', Private_scroll);
			shs.cookie("widget_remind_isclose", "1", {expires: 1});	// 写入cookie，一天内不再显示
			delete mod.remind;
		}
	};


	mod.remind = init();	// 初始化挂件并加入全局挂件列表
	return mod;				// 返回全局挂件列表

}(window.Widgets||{}, window, document, navigator.userAgent, jQuery, shs);