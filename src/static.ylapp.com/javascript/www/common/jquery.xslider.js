/**
 * @package Xslider - A slider plugin for jQuery
 * @version 1.0
 * @author xhowhy <http://x1989.com>
 **/
;(function($) {
	$.fn.Xslider = function(options) {
		var settings = {
			effect : 'scrollx', //效果  有scrollx|scrolly|fade|none
			speed : 600, //动画速度
			space : 5000, //时间间隔
			auto : true, //自动滚动
			trigger : 'mouseover', //触发事件 注意用mouseover代替hover
			content_box : '.contents', //内容容器id或class
			content_tag : 'li', //内容标签 默认为<li>
			switcher_box : '.switchers', //切换触发器id或class
			switcher_tag : 'li', //切换器标签 默认为<li>
			active_class : 'SwitchersActive', //当前切换器样式名称 不含"."
			prev : 'prev', //上一个幅箭头样式名称
			next : 'next', //下一个幅箭头样式名称
			rand : false //是否随机指定默认幻灯页
		};
		settings = $.extend({}, settings, options);
		var index = 1;
		var last_index = 0;
		var $content_box = $(this).find(settings.content_box), $contents = $content_box.find(settings.content_tag);
		var $switcher = $(this).find(settings.switcher_box), $switcher_tag = $switcher.find(settings.switcher_tag);
		if (settings.rand) {
			index = Math.floor(Math.random() * $contents.length);
			slide();
		}
		if (settings.effect == 'fade') {
			$.each($contents, function(k, v) {
				(k == 0) ? $(this).css({
					'position' : 'absolute',
					'z-index' : 9
				}) : $(this).css({
					'position' : 'absolute',
					'z-index' : 1,
					'opacity' : 0
				});
			});
		}
		function slide() {
			if (index >= $contents.length)
				index = 0;
			$switcher_tag.removeClass(settings.active_class).eq(index).addClass(settings.active_class);
			switch(settings.effect) {
				case 'scrollx':
					$content_box.width($contents.length * $contents.width());
					$content_box.stop().animate({
						left : -$contents.width() * index
					}, settings.speed);
					break;
				case 'scrolly':
					$contents.css({
						display : 'block'
					});
					$content_box.stop().animate({
						top : -$contents.height() * index + 'px'
					}, settings.speed);
					break;
				case 'fade':
					$contents.eq(last_index).stop().animate({
						'opacity' : 0
					}, settings.speed / 2).css('z-index', 1).end().eq(index).css('z-index', 9).stop().animate({
						'opacity' : 1
					}, settings.speed / 2)
					break;
				case 'none':
					$contents.hide().eq(index).show();
					break;
			}
			last_index = index;
			index++;
		};
		if (settings.auto)
			var Timer = setInterval(slide, settings.space);
		$switcher_tag.bind(settings.trigger, function() {
			_pause()
			index = $(this).index();
			slide();
			_continue()
		});
		$(this).find('.arrow').click(function(e) {
			_pause();
			if (!e)
				e = window.event;
			if (e.target.className == settings.prev) {
				if (!last_index)
					return;
				index = --last_index;
				slide();
			}
			if (e.target.className == settings.next) {
				slide();
			}
			_continue();
		});
		$content_box.hover(_pause, _continue);
		function _pause() {
			clearInterval(Timer);
		}

		function _continue() {
			if (settings.auto)
				Timer = setInterval(slide, settings.space);
		}

	}
})(jQuery);