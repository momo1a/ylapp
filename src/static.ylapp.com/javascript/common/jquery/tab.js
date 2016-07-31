/*
使用示例：
$(".ui-tab").tab({
	eType:"mouseover",
	card:".ui-tab-nav-item",
	curClass:"ui-tab-nav-curItem",
	panel:".ui-tab-panel"
});
*/
;(function($){ /*功能简单的选项卡插件*/
$.fn.tab = function(options){
	var settings = {
		eType:"click",
		card:".tab-nav-item",
		panel:".tab-panel",
		curClass:"tab-nav-curItem",
		speed:0
	};
	options = $.extend(settings,options);
	this.each(function(){
			var card = $(options.card,this),
				panel = $(options.panel,this),
				className = options.curClass,
				speed = options.speed;
			card[options.eType](function(){
				var me = $(this);
				card.removeClass(className).filter(this).addClass(className);
				panel.hide(speed).eq(me.index()).show(speed);
			});
	});
	return this;
};
})(jQuery);

//推荐的html结构
// <div class="ui-tab">
// 	<ul class="ui-tab-nav">
// 		<li class="ui-tab-nav-item ui-tab-nav-curItem">card1</li>
// 		<li class="ui-tab-nav-item">card2</li>
// 		<li class="ui-tab-nav-item">card3</li>
// 	</ul>
// 	<div class="ui-tab-cont">
// 		<div class="ui-tab-panel" style="display:block;">panel1</div>
// 		<div class="ui-tab-panel" style="display:none;">panel2</div>
// 		<div class="ui-tab-panel" style="display:none;">panel3</div>
// 	</div>
// </div>