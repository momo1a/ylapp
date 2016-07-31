//===zfq首页焦点===
$(".J_zfq-slider").tab({
    eType: "mouseover",
    card: ".J_pointer a",
    panel: ".J_pic",
    curClass: "z-crt",
    extend: function () {
        var self = this;
        var show = self.show;
        var lock = false;
        var length = self.config.panel.length;
        var timer;
        // 溶解效果
        self.show = function (i) {
            if (!lock && i != self.index) {
                if (i >= length)i = 0;
                lock = true;
                show(i);
                self.config.panel.eq(self.lastIndex).css("position", "absolute").show().fadeOut(1000, function () {
                    $(this).css("position", "").hide();
                    lock = false;
                });
            }
            $(window).scroll();
        };
        timer = setInterval(function () {
            self.show(self.index + 1)
        }, 4000);
        $(".J_zfq-slider").hover(
            function () {
                clearInterval(timer);
            },
            function () {
                timer = setInterval(function () {
                    self.show(self.index + 1)
                }, 4000);
            }
        );
        self.show(0);
    }
});

//===懒加载===
$(".f-lazy").lazyload({
    threshold: 0,
    failure_limit: 20,
    effect: "fadeIn",
    load: function () {
        // 加载后去除加载图
        $(this).removeClass("f-lazy");
    }
});