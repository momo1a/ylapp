/**
 * @function：
 * @extends jquery.1.10.2
 * @fileOverview 全屏Banner插件 - 更改懒人图库下载插件
 * @author Surging
 * @email surging2@qq.com
 * @version 0.1
 * @date 14-2-28
 * Copyright (c) 2010-2014   一站网络集团
 * @example
 *
 */
;
(function ($) {
    $.fn.SkFullBanner = function (settings) {
        var defaults = {
            defDirection: 'left', //默认滚动方向
            speed: 1500, //间隔时间(ms)
            animateTime: 500, //移动速度(ms)
            imgs: $('li.fuck'),//图片盒子
            hoverBox: $('.fuck-wrap'), //鼠标移入停止滚动的盒子
            leftBtn: $('.fuck-prev'), //左按钮
            rightBtn: $('.fuck-next'), //右按钮
            betweenSpace: 1, //两图片间的间隔(px)
            width: 1000 //图片宽度(px)
        };
        settings = $.extend({}, defaults, settings);

        var config = {
                direction: settings.defDirection != 'right',
                _imgLen: settings.imgs.length
            }
            , i = 0, _betSpace = settings.betweenSpace * 2
            , getNextIndex = function (y) {
                return i + y >= config._imgLen ? i + y - config._imgLen : i + y;
            }
            , getPrevIndex = function (y) {
                return i - y < 0 ? config._imgLen + i - y : i - y;
            }
            , silde = function (d) {
                settings.imgs.eq((d ? getPrevIndex(2) : getNextIndex(2))).css('left', (d ? (settings.width - _betSpace) * 2 + 'px' : '-' + (settings.width - _betSpace) * 2 + 'px'));
                settings.imgs.animate({
                    'left': (d ? '-' : '+') + '=' + (settings.width - _betSpace) + 'px'
                }, settings.animateTime);
                i = d ? getPrevIndex(1) : getNextIndex(1);
            }
            , s = setInterval(function () {
                silde(config.direction);
            }, settings.speed);

        return this.each(function () {
            settings.imgs.eq(i).css('left', 0).end().eq(i + 1).css('left', '-' + (settings.width - _betSpace) + 'px').end().eq(i - 1).css('left', (settings.width - _betSpace) + 'px');
            settings.hoverBox.add(settings.leftBtn).add(settings.rightBtn).hover(function () {
                clearInterval(s);
            }, function () {
                s = setInterval(function () {
                    silde(config.direction);
                }, settings.speed);
            });
            settings.leftBtn.click(function () {
                if ($(':animated').length === 0) {
                    silde(false);
                }
            });
            settings.rightBtn.click(function () {
                if ($(':animated').length === 0) {
                    silde(true);
                }
            });
        });
    }
})(jQuery);