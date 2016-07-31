/**
 * 首页主脚本
 * by: 陆楚良
 */

!function(){
	var $list = $("#J_category .J_list li");
	var $sub  = $("#J_category .J_sublist");
	$list.each(function(i){$(this).data("sub", $sub.eq(i));});
	// 重新计算渲染子分类
	$sub.each(function(){
		var $this = $(this);
		var $a = $this.find("a");
		var $subul = $('<ul></ul>');
		$this.append($subul);
		$a.each(function(i){
			if(i!=0 && i%14<=0){
				$subul = $('<ul></ul>');
				$this.append($subul);
			}
			$subul.append($('<li></li>').html(this))

        });
    });
    var crt_sub,timer,clientX=null;
    $list.mousemove(function(event){
        clearTimeout(timer);
        var $this = $(this);
        var i = $this.data("sub");
        if(i!==crt_sub){
            if(clientX!==null && event.clientX > clientX){
                timer = setTimeout(function(){
                    $list.removeClass("z-crt");
                    $sub.hide();
                    $this.addClass("z-crt").data("sub").show();
				}, 200);
			}else{
				$list.removeClass("z-crt");
				$sub.hide();
				$this.addClass("z-crt").data("sub").show();
			}
		}
		clientX = event.clientX;
	}).mouseout(function(){
		clearTimeout(timer);
		timer = setTimeout(function(){
			$list.removeClass("z-crt");
			$sub.hide();
		}, 200);
	});
	$sub.parent().hover(
		function(){clearTimeout(timer)},
		function(){
			clearTimeout(timer);
			timer = setTimeout(function(){
				$list.removeClass("z-crt");
				$sub.hide();
			}, 200);
		}
	);
}();

$(".J_slider").tab({
	eType : "mouseover",
 	card  : ".J_pointer i",
	panel : ".J_pic li",
	curClass:"z-crt",
	extend: function(){
		var self = this;
		var show = self.show;
		var lock = false;
		var length = self.config.panel.length;
		var timer;
		// 溶解效果
		self.show = function(i){
			if(!lock && i!=self.index){
				if(i>=length)i=0;
				lock = true;
				show(i);
				self.config.panel.eq(self.lastIndex).css("position", "absolute").show().fadeOut(1000, function(){
					$(this).css("position","").hide();
					lock = false;
				});
			}
			$(window).scroll();
		};
		timer = setInterval(function(){self.show(self.index+1)}, 7000);
		$(".J_slider").hover(
			function(){clearInterval(timer);},
			function(){timer = setInterval(function(){self.show(self.index+1)}, 7000);}
		);
		self.show(0);
	}
});

 //标准选项卡：侧边小广告、公告、新品模块选项卡、产品模块选项卡
 $(".J_tab").tab({
 	eType: "mouseover",
 	card : ".J_card",
 	panel: ".J_panel",
 	curClass:"z-crt",
 	// 扩展，主要实现触发懒加载功能
 	extend: function(){
 		var show = this.show;
 		this.show= function(i){
 			show(i);
 			$(window).scroll();
 		};
 	}
 });

 $(".J_new").each(function(){
    var $this = $(this);
    var $more = $this.find(".J_more");
    $this.find(".J_card").mouseover(function(){
        if($(this).index()==0){
            $more.show();
        }else{
            $more.hide();
        }
    });
});
$('.J_tab2').each(function(){
    var $this = $(this);
    $(this).tab({
        eType: "mouseover",
        card : ".J_card li",
        panel: ".J_panel",
        curClass:"z-crt",
        extend:function(){
            var self = this;
            var show=this.show;
            var lcard = $this.find(".J_tabLeft li").slice(0, -1);
            this.show=function(i){
                lcard.removeClass("z-crt").eq(i).addClass("z-crt");
                show(i);
				$(window).scroll();
            };
            lcard.mouseover(function(){
                self.show($(this).index());
            });
        }
    });
});

$(".J_new .J_sbox").scrollBox({
	parentWidth: 800,			/*父元素宽度，使用小屏宽度，避免出现大屏时候元素不足而导致插件不处理*/
	width: 200,					/*单位宽度*/
	scrollWidth : function(){return this.dom.width()+1;},/*可选，滚动宽度，使用函数获取dom宽度可以兼容响应式变化*/
	contSelector: "ul",			/*内容选择器*/
	unitSelector: "li",			/*单元选择器*/
	prevSelector: ".J_prev",	/*可选，上一页选择器*/
	nextSelector: ".J_next",	/*可选，下一页选择器*/
	auto : 5000,				/*可选，自动轮播(毫秒，默认0表示不轮播)*/
	extend: function(status){	/*扩展接口*/
		if(status!="failed"){
			var self = this;
			var $pn  = self.dom.find(".J_prev,.J_next");
			// 鼠标经过模块后显示翻页按钮
			self.dom.hover(
				function(){$pn.show()},
				function(){$pn.hide()}
			);
			// 动画完成后执行的方法，用以触发懒加载响应
			self.done = function(){$(window).scroll()};
		}
	}
});

/* 异步加载达人模块 */
!function($rank){
 	$.getJSON("/total-rank", function(data){
        var tpl = '<li>'
                +     '<a class="pic" target="_blank" href="' + shs.url.bbs + 'space-uid-${uid}.html" title="查看达人：${uname}">'
                +         '<span><img src="' + shs.url.uc + 'avatar.php?uid=${uid}&size=middle"></span>'
                +         '<i class="floor">${i}</i>'
                +     '</a>'
                +     '<p>'
                +         '<a class="name" target="_blank" href="' + shs.url.bbs + 'space-uid-${uid}.html" title="查看达人：${uname}">${uname}</a>'
                +         '<span class="goods-sum">商品总数：${order_num}个</span>'
                +     '</p>'
                +     '<p>省钱：<span class="money"><i>￥</i>${rebate_money}</span></p>'
                + '</li>';
        var html = "";
		for(var i=0; i<data.length; i++){
			data[i].i = i+1;
			data[i].fc= i>2 ? "s-fcg" : "s-fcr";
			html += shs.template(tpl, data[i]);
		}
		$rank.find(".J_total").html(html);
 	});
	$.getJSON("/month-rank", function(data){
        var tpl = '<li>'
                +     '<a class="pic" target="_blank" href="' + shs.url.bbs + 'space-uid-${uid}.html" title="查看达人：${uname}">'
                +         '<span><img src="' + shs.url.uc + 'avatar.php?uid=${uid}&size=middle"></span>'
                +         '<i class="floor">${i}</i>'
                +     '</a>'
                +     '<p>'
                +         '<a class="name" target="_blank" href="' + shs.url.bbs + 'space-uid-${uid}.html" title="查看达人：${uname}">${uname}</a>'
                +         '<span class="goods-sum">商品总数：${order_num}个</span>'
                +     '</p>'
                +     '<p>省钱：<span class="money"><i>￥</i>${all_save}</span></p>'
                + '</li>';
        var html = "";
		for(var i=0; i<data.length; i++){
			data[i].i = i+1;
			html += shs.template(tpl, data[i]);
		}
		$rank.find(".J_month").html(html);
	});
}($("#J_rank"));




/*懒加载*/
$(".f-lazy").lazyload({
	threshold: 0,
	failure_limit: 20,
	effect: "fadeIn",
	load: function(){
		// 加载后去除加载图
		$(this).removeClass("f-lazy");
	}
});
$(function(){$(window).scroll()});
