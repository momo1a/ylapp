/**
 * 基于jQuery的监视元素垂直位置所在可视区域情况
 * Author: 陆楚良
 * Version: 1.0.2
 * Date: 2015/04/07
 * 
 * https://git.oschina.net/luchg/jquery.inView.js.git
 *
 * License: http://www.apache.org/licenses/LICENSE-2.0
 *
 * 本插件源自jquery.highlight.js衍生，以“更自由地开发”为宗旨
 * jquery.highlight.js: https://git.oschina.net/luchg/jquery.highlight.js.git
 */
!function(){
    function factory($){
        "use strict";
        /**
         * 基于jQuery的监视元素垂直位置所在可视区域情况
         * @method inView(callback, [limit], [relate]);
         * @method inView(callback, [relate]);
         * @param  {Function} callback 回调函数
         * @param  {Object}   limit    一个对象，可设置可视区的top和bottom距离
         * @param  {Boolean}  relate   设置是否是关联元素，缺省true，当设置为false时候，
         *                             表示选择器选中的元素不关相联，出现多个元素同时在可视区域时候，
         *                             不会自动判断哪个是主显示元素，s值为0或1、2、3、4
         * @return {selector}
         * @see  当选择器选中多个并使用关联时(默认是关联的)，会自动判断哪个元素是主显示，
         *       回调函数中this可接收到主元素的element，并且接收
         *       一个当前this在选择器中的索引位置参数和一个在可视区中的位置参数：
         *       callback(index, s);
         *       index为当前第几个元素
         *       s的值为：
         *           0未显示， 1露头， 2局部， 3包含， 4露尾
         *            关联情况下还有几种情况：
         *            -1露头但不是当前
         *            -2局部但不是当前
         *            -3包含但不是当前
         *            -4露尾但不是当前
         * @example
         *      $(".J_floor").inView(function(i){
         *          console.log(i);
         *      });
         */
        $.fn.inView = $.fn.inView || function(callback, limit, relate){
            if($.type(limit)=="boolean"){
                // inView(callback, relate);
                relate = limit;
                limit  = {};
            }
            var self = this;
            limit = $.extend({top:0,bottom:0}, limit||{});
            relate= relate===false ? false : true;
            var state = [];
            self.each(function(i){state[i] = null});
            var f = function(){
                var top = $(document).scrollTop()+limit.top;
                var bottom=$(document).scrollTop()+$(window).height()-limit.bottom;
                var list = [];
                self.each(function(){
                    var o = $(this).offset();
                    o = ($.type(o)=="object") ? o : {top:0};
                    list.push({top:o.top, bottom:o.top+$(this).height()});
                });
                var crt=!1;     // 当前主要显示的元素（元素关联时候需要）
                var bh =!1;     // 是否存在包含（元素关联时候需要）
                var L  = [];    // 0未显示， 1露头， 2局部， 3包含， 4露尾
                                // 关联情况下还有几种情况：
                                // -1露头但不是当前
                                // -2局部但不是当前
                                // -3包含但不是当前
                                // -4露尾但不是当前
                for(var i=0;i<list.length; i++){
                    if(list[i].top>=top && list[i].top<=bottom && list[i].bottom<=bottom){//包含
                        L[i] = 3;
                        if(bh===!1){
                            bh = i;
                            crt = i;
                        }
                    }else{
                        if     (list[i].top   >=top && list[i].top   < bottom){L[i] = 1; crt = i}// 露头
                        else if(list[i].bottom> top && list[i].bottom<=bottom){L[i] = 4; crt = i}// 露尾
                        else if(list[i].top   < top && list[i].bottom> bottom){L[i] = 2; crt = i}// 局部
                        else{L[i]=0}
                    }
                }
                if(relate && bh!==!1){
                    crt = bh;
                }
                self.each(function(i){
                    if(relate){
                        L[i] = (i===crt || L[i]==0) ? L[i] : -L[i];
                    }
                    if(L[i]!==state[i]){
                        state[i] = L[i];
                        callback.call(this, i, L[i]);
                    }
                });
            };
            $(window).scroll(f).resize(f);
            f();
            return this;    /*return this使其可以连贯操作*/
        };
        return $;
    }

    // RequireJS && SeaJS && GlightJS
    if (typeof define === "function") {
        define(function() {
            return factory;
        });
    // NodeJS
    } else if (typeof exports !== "undefined") {
        module.exports = factory;
    // Normal JavaScript
    } else {
        factory(jQuery);
    }
}();