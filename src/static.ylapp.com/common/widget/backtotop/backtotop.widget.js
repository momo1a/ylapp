/**
 * 返回顶部挂件
 * Author: 陆楚良
 * Date: 2015-03-31
 * 效果：当页面下移以后，出现一个返回顶部的悬浮按钮
 */

/*
隐藏功能：
     由于技术的变革，有的页面尺寸并不一定是1000，而且响应式页面也越来越流行，因此特意增加宽度响应功能
     
     在页面head中加入如下代码即可让该挂件适应1200px宽度的页面
     <meta name="D-Width-Response" content="width=1200px">
     如果是响应式页面，可以使用如下代码，挂件会引用wrap(可自定义样式名)样式作为宽度控制，
         只要该样式是响应式的，那么挂件就是响应的，但需注意，该样式最好仅包含width属性，以免发生布局错乱
     <meta name="D-Width-Response" content="class=wrap">

     如果上述方法由于结构局限问题不能使用，也可使用下面的方法：（全局变量优先级高于meta元标签）
     <script>window["D-Width-Response"] = "width=1200px";</script>
 */
;var Widgets = function(mod, window, document, $, L){
    'use strict';
    /*--- 私有变量 Begin ---*/

    // 内嵌式样式文本
    var Private_css = '.widget-backtotop{${width}height:0;line-height:0;margin:0 auto;}'
                    + '.widget-backtotop-fw{${width}position:fixed;_position:absolute;height:0;bottom:0;z-index:0;_top:expression(eval(document.documentElement.scrollTop+document.documentElement.clientHeight));}'
                    + '.widget-backtotop-fw a{'
                    +       'width:50px;height:50px;overflow:hidden;position:absolute;right:-80px;top:0;display:none;line-height:0;'
                    +       'background-color:#ADACA9;'
                    +       'background-repeat:no-repeat;'
                    +       'background-position:center center;'
                    + '}'
                    + '.widget-backtotop-fw a:hover{background-color:#8B8A86;}'
                    + '.widget-backtotop-fw a span{position:absolute;left:50%;line-height:0;overflow:hidden;cursor:pointer;}'
                    + '.widget-backtotop-rect1{background-color:#fff;width:25px;height:2px;top:10px;margin-left:-13px;}'
                    + '.widget-backtotop-polygon{width:0;height:0;border-color:transparent transparent #ffffff transparent;border-style:dashed dashed solid dashed;border-width:0 13px 13px 12px;top:15px;margin-left:-13px;}'
                     + '.widget-backtotop-rect2{background-color:#fff;width:11px;height:12px;top:28px;margin-left:-6px;}'
;

    // html内容
    var Private_html= '<div class="widget-backtotop${class}">'
                    +   '<div class="widget-backtotop-fw${class}">'
                    +       '<a href="javascript:;" title="回到顶部">'
                    +           '<span class="widget-backtotop-rect1">&nbsp;</span>'
                    +           '<span class="widget-backtotop-polygon">&nbsp;</span>'
                    +           '<span class="widget-backtotop-rect2">&nbsp;</span>'
                    +       '</a>'
                    +   '</div>'
                    + '</div>';
    // $(window)
    var Private_$w;
    // $(document)
    var Private_$d;
    // $("body, html")
    var Private_$b;
    // 是否显示
    var Private_isShow = false;
    // 存储窗口高度
    var Private_w_height;
    // 存储窗口尺寸变化监控方法
    var Private_resize = function(){
        Private_w_height = Private_$w.height();
        Private_scroll();
    };
    // 存储窗口滚动监控方法
    var Private_scroll = function(){
        Private_$d.scrollTop()>Private_$w.height() ? Widget.show() : Widget.hide();
    };

    /*--- 私有变量 End   ---*/



    // 初始化方法
    var init = function(){
        /*--- 私有变量初始化 Begin ---*/
        // $(window)
        Private_$w = $(window);
        // $(document)
        Private_$d = $(document);
        // $("body, html")
        Private_$b = $("body, html");
        // 存储窗口高度
        Private_w_height = Private_$w.height();

        // 从meta中查找设置的页面宽度或响应式宽度类名，没有则默认选用1000
        var dwr       = $.type(window["D-Width-Response"])=="string" ?  window["D-Width-Response"] : $("head meta[name=D-Width-Response]").attr("content") || "";
        var width     = dwr.match(/^.*width=(\d+[a-z%]*).*$/i);
        var className = dwr.match(/^.*class=([_\-0-9a-z]+).*$/i);
        if(width){
            // 如果页面设置了宽度
            Private_html= Private_html.replace(/\$\{class\}/g, "");
            Private_css = Private_css.replace(/\$\{width\}/g, "width:"+width[1]+";");
        }else if(className){
            // 如果页面设置了响应式宽度样式
            Private_html= Private_html.replace(/\$\{class\}/g, " "+className[1]);
            Private_css = Private_css.replace(/\$\{width\}/g, "");
        }else{
            // 默认使用1000px宽度
            Private_html= Private_html.replace(/\$\{class\}/g, "");
            Private_css = Private_css.replace(/\$\{width\}/g, "width:1000px;");
        }
        
        /*--- 私有变量初始化 End ---*/

        /*--- DOM存储 Begin ---*/
        L.style(Private_css);
        var D = Widget.DOM,W = D.widget = $(Private_html);
        D.fw  = W.find(".widget-backtotop-fw");
        D.btn = W.find(".widget-backtotop-fw a");
        /*--- DOM存储 End   ---*/

        /*--- 事件绑定 Begin ---*/
        // 监听浏览器尺寸变化与滚动条滚动
        Private_$w.resize(Private_resize).scroll(Private_scroll).scroll();
        //返回顶部方法绑定
        D.btn.click(function(){Widget.toTop()});
        /*--- 事件绑定 End   ---*/

        $(document.body).append(D.widget);
        return Widget;
    };

    // 挂件对象
    var Widget = {
        // 存储DOM专用对象
        DOM:{},
        /**
         * 返回顶部执行方法
         */
        toTop:function(){
            Private_$b.animate({scrollTop:0});
        },
        /**
         * 显示
         */
        show:function(){
            if(Private_isShow)return;
            Private_isShow = true;
            Widget.DOM.btn.stop().show().animate({top:-160});
        },
        /**
         * 隐藏
         */
        hide:function(){
            if(!Private_isShow)return;
            Private_isShow=false;
            Widget.DOM.btn.stop().show().animate({top:0}, function(){
                $(this).hide();
            });
        }
    };


    $(function(){mod.backtotop = init()});  // 初始化挂件并加入全局挂件列表
    return mod;             // 返回全局挂件列表

}(window.Widgets||{}, window, document, jQuery, CL_Loader);