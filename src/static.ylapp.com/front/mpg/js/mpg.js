/*焦点图轮换导航条动态居中*/
var $ctrl=$(".u-pointer"),
    ctrlW=$ctrl.width();
$ctrl.css("margin-left",-(ctrlW-10)/2+"px");
$(".J_slider").tab({
    eType: "click",
    card : ".J_pointer a",
    panel: ".J_pics li",
    curClass:"z-crt",
    // 扩展
    extend: function(){
        var self = this;
        var show = self.show;
        var lock = false;
        var length = self.config.panel.length;
        var timer;
        var focusDOM = $("#J_focus")[0];
        var A = new Animate(2000);

        function color(hex){
            if(!hex){
                return [255, 255, 255];
            }
            hex = hex.replace("#", "").toLowerCase();
            if(hex.indexOf("rgb")>-1){
                return hex.match(/[0-9]+/g);
            }
            var l = (hex.length===3) ? 1 : 2;
            return [parseInt(hex.substr(0, l), 16) , parseInt(hex.substr(l, l), 16) , parseInt(hex.substr(2*l, l), 16)];
        }

        focusDOM.style.backgroundColor = self.config.panel.eq(self.index).data("color");

        // 溶解效果
        self.show = function(i){
            // 手动实现图片懒加载
            var $img = self.config.panel.eq(i).find("img");
            if($img.data("original")){
                $img.attr("src", $img.data("original"));
                $img.data("original", null);
            }
            if(!lock && i!=self.index){
                if(i>=length)i=0;
                lock = true;
                show(i);
                $(window).scroll();
                // 颜色渐变动画的实现 - 无奈jQ不支持，只好用Animate.js引擎了
                var obgc = color(focusDOM.style.backgroundColor);
                var nbgc = color(self.config.panel.eq(self.index).data("color"));
                A.stop().run(function(x){
                    var i=0;
                    focusDOM.style.backgroundColor = "rgb(0,0,0)".replace(/[0-9]+/g, function(s){
                        var o = Number(obgc[i]);
                        var n = Number(nbgc[i]);
                        i++;
                        return o+parseInt((n-o)*x);
                    });
                });
                self.config.panel.eq(self.lastIndex).css("position", "absolute").show().fadeOut(2000, function(){
                    $(this).css("position","").hide();
                    lock = false;
                });
            }
        }
        timer = setInterval(function(){self.show(self.index+1)}, 7000);
        $(".J_slider").hover(
            function(){clearInterval(timer);},
            function(){timer = setInterval(function(){self.show(self.index+1)}, 7000);}
        );
        self.show(0);
    }
});

/*右侧导航滑动*/
! function(win, doc, $) {
    var t = 589, // 页面顶最小距离
        b = 257, // 页面底最小距离
        s = 150, // 相对窗口顶距离
        tr = null,
        $b = $(".J_sd"), // 跟随块选择器
        bh = $b.find(".J_doc").height(), // 主体块高度
        f = function() {
            clearTimeout(tr);
            tr = setTimeout(function() {
                var tp = s + $(doc).scrollTop();
                var ht = $(doc).height() - b;
                tp = (tp < t) ? t : (tp + bh > ht ? ht - bh : tp);
                $b.animate({
                    top: tp
                });
            }, 300);
        };
    f();
    $(win).scroll(f);
}(window, document, $);
