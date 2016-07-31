// JavaScript Document


/*----- 功能简单的选项卡插件 -----*/
;(function($){ 
$.fn.tab = function(options){
	var settings = $.extend({
		card: ".tab-nav-item",
		panel: ".tab-panel",
		highlight: "tab-nav-curItem",
		eType: "click",
		speed: 0
	}, options);
	this.each(function(){
		    var self = $(this);
			var card = self.find(settings.card),
				panel = self.find(settings.panel),
				className = settings.highlight,
				speed = settings.speed;
			card[settings.eType](function(){
				var me = $(this);
				card.removeClass(className);
				me.addClass(className);
				panel.hide(speed).eq(me.index()).show(speed);
			});
	});
	return this;
};
})(jQuery);


/*----- 菜单折叠 -----*/
	$(".menu-hasSub dt").click(function(){
		var me = $(this);
		var fa = $(this).closest(".menu-hasSub");
//		$(this).addClass("menu-item-curPage");
		var classname = "menu-hasSubClose";
		if( fa.hasClass(classname) ){
			fa.find("dd").hide();
			fa.removeClass(classname);
			$(this).removeClass("menu-hasSubHighlight");
		}else{
			fa.find("dd").show();
			fa.addClass(classname);
			$(".menu-hasSub dt").removeClass("menu-hasSubHighlight");
			$(this).addClass("menu-hasSubHighlight");
		}
	});

/*----- 文本占位符 -----*/
$(function(){
		/* placeholder shim */
		if( !("placeholder" in document.createElement("input")) ){
			$("input[placeholder]").focus(function(){ 
				var phd = this.getAttribute("placeholder");
				if( this.value===phd ){
					this.value="";
					this.style.color="#000";
				}
			}).blur(function(){
				var phd = this.getAttribute("placeholder");
				if( this.value==="" ){
					this.value=phd;
					this.style.color="#a9a9a9";
				}
			}).blur();
		}
});


/*----- 智能浮动效果 -----*/
/* 浏览器判断 */
var isIE = /MSIE/.test(navigator.userAgent);
var ie6 = (isIE && !window.XMLHttpRequest);
var ie7 = (isIE && document.documentMode && document.documentMode == 7);
var ie8 = (isIE && document.documentMode && document.documentMode > 7);

/* 初始化浮动模块位置 */
function initFloat(d){
	var obj = d;
	var sw = $(window).width();
	var sh = $(window).height();
	var objHeight = obj.outerHeight();
	if(sw > 1060){
		obj.css({"right":parseInt((sw - 1060) / 2 - 32) + "px"});
	}else{
		obj.css({"right":0});
	}
	
	if(sh > objHeight){
		obj.css({"bottom":parseInt(sh / 3 - objHeight) + "px"});
	}else{
		obj.css({"bottom":0});
	}
}

/* 浮动功能模块，IE6环境下执行 */
function floatFunc(d){
	var ft = d;
	var bh = $(window).height();
	var st = $(document).scrollTop();
	var fth = ft.outerHeight(true);
	
	ft.css({"position":"absolute","bottom":null,"top":parseInt(bh - (fth + 30)) + st + "px"});
}

/* 返回顶部 */
function backToTop(o){
	var obj = o;
	o.click(function(){
		$("body,html").stop().animate({scrollTop: 0},"normal");
	});
}

$(document).ready(function(){
	initFloat($("#hc-float"));
	backToTop($(".to-top"));
	$(window).scroll(function(){
		if($(document).scrollTop() > 100){
			$("#hc-float").fadeIn();
		}else{
			$("#hc-float").fadeOut();
		}
		if(ie6){
			floatFunc($("#hc-float"));
		}
	});
});