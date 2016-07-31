/**
 * 滚动轮播插件
 * by 陆楚良
 **/
(function($){
	$.fn.scrollBox = function(p){
		var d = {
			parentWidth:0,	/*父元素宽度*/
			width:0,		/*单位宽度*/
			prevSelector:"",/*可选，上一页选择器*/
			nextSelector:"",/*可选，下一页选择器*/
			scrollWidth:0,  /*可选，滚动宽度，默认值为parentWidth所设置的值*/ 
			contSelector:"ul",	/*内容选择器*/
			unitSelector:"li",	/*单元选择器*/
			auto:0,			/*可选，自动轮播(毫秒，默认0表示不轮播)*/
			autoFun:"next",  /*可选，轮播方向，prev、next两种，默认next*/
			extend:null		/*扩展接口*/
		}
		d = $.extend(d,p);
		d.scrollWidth = d.scrollWidth ? d.scrollWidth : d.parentWidth;
		var cont = this.find(d.contSelector);
		if (this.find(d.unitSelector).length*d.width<=d.parentWidth){
			cont.width(d.parentWidth);	/*拉宽内容宽度*/
			typeof d.extend=="function" && d.extend("failed");
			return this;	/*单位集合不足以满足父元素的宽度的话，将不处理滚动效果*/
		}
		cont.append(cont.html()+cont.html());
		var totalWidth = this.find(d.unitSelector).length*d.width/3;
		var canDo = true;
		d.done = function(){}
		d.prev = function(){
			if(!canDo)return;
			canDo = false;
			var left = parseInt(cont.css("margin-left"));
			left = isNaN(left)?-totalWidth:left;
			if(left+d.scrollWidth>0){
				left = left%totalWidth-totalWidth;
				cont.css("margin-left",left);
			}
			cont.animate({"margin-left":left+d.scrollWidth},function(){canDo=true;d.done()});
		}
		d.next = function(){
			if(!canDo)return;
			canDo = false;
			var left = parseInt(cont.css("margin-left"));
			left = isNaN(left)?-totalWidth:left;
			if(left-d.scrollWidth<-totalWidth*2){
				left = left%totalWidth;
				cont.css("margin-left",left);
			}
			cont.animate({"margin-left":left-d.scrollWidth},function(){canDo=true;d.done()});
		}
		cont.width(totalWidth*3);
		d.prevSelector && this.find(d.prevSelector).click(d.prev);
		d.nextSelector && this.find(d.nextSelector).click(d.next);
		typeof d.auto=="number" && d.auto>0 && setInterval(d[d.autoFun], d.auto);
		typeof d.extend=="function" && d.extend(d);
		return this;
	}
})(jQuery);