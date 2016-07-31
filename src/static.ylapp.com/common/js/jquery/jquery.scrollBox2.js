/**
 * 滚动轮播插件
 * by 陆楚良
 **/
!function(){
    function scrollBox($){
        if(!$.fn.scrollBox){
            $.fn.scrollBox = function(p){
                this.each(function(){
                    var config = {
                        // 所有配置支持使用函数赋予实时数据（为兼容部分响应式设计）
                        parentWidth:0,      // 父元素宽度
                        width:0,            // 单位宽度
                        prevSelector:"",    // 可选，上一页选择器
                        nextSelector:"",    // 可选，下一页选择器
                        scrollWidth:0,      // 可选，滚动宽度，默认值为parentWidth所设置的值
                        contSelector:"ul",  // 内容选择器
                        unitSelector:"li",  // 单元选择器
                        auto:0,             // 可选，自动轮播(毫秒，默认0表示不轮播)
                        autoFun:"next",     // 可选，轮播方向，prev、next两种，默认next
                        extend:null         // 扩展接口
                    }
                    config = $.extend(config,p);
                    config.scrollWidth = config.scrollWidth || config.parentWidth;
                    var d = {config:config};
                    var C = d.C = function (v){
                        return $.type(config[v])=="function" ? config[v].call(d) : config[v];
                    }
                    var $this= d.dom = $(this);
                    var cont = $this.find(C("contSelector"));
                    if ($this.find(C("unitSelector")).size() * C("width") <= C("parentWidth")){
                        cont.width(C("parentWidth"));    /*拉宽内容宽度*/
                        $.type(C("extend"))=="function" && C("extend").call(d, "failed");
                        return;    /*单位集合不足以满足父元素的宽度的话，将不处理滚动效果*/
                    }
                    var totalWidth = $this.find(C("unitSelector")).size() * C("width");
                    cont.append(cont.html() + cont.html());
                    cont.width(totalWidth*3);
                    var canDo = true;
                    d.done = function(){};
                    d.prev = function(){
                        if(!canDo)return;
                        canDo = false;
                        var left = parseInt(cont.css("margin-left"));
                        left = isNaN(left) ? -totalWidth : left;
                        if(left + C("scrollWidth") > 0){
                            left = left % totalWidth - totalWidth;
                            cont.css("margin-left", left);
                        }
                        cont.animate({"margin-left":left+C("scrollWidth")},function(){canDo=true;d.done()});
                    };
                    d.next = function(){
                        if(!canDo)return;
                        canDo = false;
                        var left = parseInt(cont.css("margin-left"));
                        left = isNaN(left)?-totalWidth:left;
                        if(left-C("scrollWidth")<-totalWidth*2){
                            left = left%totalWidth;
                            cont.css("margin-left",left);
                        }
                        cont.animate({"margin-left":left-C("scrollWidth")},function(){canDo=true;d.done()});
                    };
                    C("prevSelector") && $this.find(C("prevSelector")).click(function(){d.prev()});
                    C("nextSelector") && $this.find(C("nextSelector")).click(function(){d.next()});
                    $.type(C("auto"))=="number" && C("auto")>0 && setInterval(function(){d[C("autoFun")]()}, C("auto"));
                    $.type(C("extend"))=="function" && C("extend").call(d);
                });
                return this;
            }
        }
    }
    // RequireJS && SeaJS
    if(typeof define==="function"){
        define(function(){return scrollBox});
    // NodeJS
    }else if(typeof exports!=="undefined"){
        module.exports = scrollBox;
    }else{
        scrollBox(jQuery);
    }
}();
