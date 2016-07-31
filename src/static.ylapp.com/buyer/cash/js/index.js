Loader.use("frame", "artDialog").run(function(){
    /**
     * 拥有方向属性的事件绑定（建议仅用于mouseenter、mouseleave两个事件）
     * Author: 陆楚良
     * Version: 1.0.0
     * Date: 2014-05-06
     **/
    (function($){
        $.fn.direction = function(bind, callback){
            this.bind(bind, function(e){
                var $this = $(this),
                        w = $this.width(),
                        h = $this.height(),
                        s = $this.offset(),
                        x = (e.pageX - s.left - (w / 2)) * (w > h ? (h / w) : 1),
                        y = (e.pageY - s.top  - (h / 2)) * (h > w ? (w / h) : 1);
                e.direction = Math.round((((Math.atan2(y, x) * (180 / Math.PI)) + 180) / 90) + 3) % 4;
                callback.call(this,e);
            });
        };
    })(jQuery);

    // 切入切出效果
    $(".box").direction("mouseenter mouseleave", function(e){
        var operate = $(this).find(".operate").stop();
        if(e.type == 'mouseenter'){
            switch(e.direction){
                case 0: operate.css({top:"-100%",left:0}).animate({top:0}, 250);  break;
                case 1: operate.css({top:0, left:"100%"}).animate({left:0}, 250); break;
                case 2: operate.css({top:"100%", left:0}).animate({top:0}, 250);  break;
                case 3: operate.css({top:0,left:"-100%"}).animate({left:0}, 250); break;
            }
        }else{
            switch(e.direction){
                case 0: operate.animate({top:"-100%",left:0}, 250); break;
                case 1: operate.animate({top:0, left:"100%"}, 250); break;
                case 2: operate.animate({top:"100%", left:0}, 250); break;
                case 3: operate.animate({top:0,left:"-100%"}, 250); break;
            }
        }
    });
    !function($){

        var load = function(url,data,callback){
            art.dialog({
                title: "操作提示",
                lock : true,
                padding: 0,
                width: 330,
                init : function(){
                    var dialog = this;
                    var l = function(){
                        dialog.content('<div style="text-align:center;padding:25px;" class="s-fc-n"><img style="vertical-align: middle;margin-right:10px;" src="'+shs.site('static','common/img/icon/60x60_loading.gif')+'"/><span>拼了小命加载中，请稍后...</span></div>');
                        $.ajax({
                            url: url,
                            data: data,
                            type: "post",
                            dataType: "json",
                            error: function(){
                                dialog.DOM.content.find("span").html('遇到了点小问题，<a href="javascript:;" class="s-fc-a">点击此处重新加载</a>。').find('a').click(l);
                            },
                            success: function(data){
                                dialog.close();
                                callback(data);
                            }
                        });
                    };l();
                }
            });
        };
        var showMsg = function(icon,content){
            art.dialog({
                title: "温馨提示",
                icon: icon,
                lock:true,
                content: content,
                ok:true
            });
        };

        // 立即兑换
        $(".J_CanExchange .btn").click(function(){
            var price = $(this).closest(".J_CanExchange").find(".price em").html();
            $this = $(this);
            if($this.data("msg")){	//缓存，防止重复的提交
                showMsg("succeed", $this.data("msg"));
            }else{
                load(shs.site('buyer','cash/cash_exchange'),{'cid':$this.attr('data-cid')},function(ret){
                    if(ret.success){
                        showMsg("succeed",'<p style="color: #1bb974;">现金券兑换成功！</p><p>众划算将会在两天内把<em class="" style="font-family:\\5FAE\\8F6F\\96C5\\9ED1;color: #ff0000">￥'+price+'</em>打到您互联支付账户</p>');
                        $this.data("msg", '<p style="color: #1bb974;">现金券兑换成功！</p><p>众划算将会在两天内把<em class="" style="font-family:\\5FAE\\8F6F\\96C5\\9ED1;color: #ff0000">￥'+price+'</em>打到您互联支付账户</p>');
                        $this.closest(".box").removeClass("J_CanExchange").addClass("z-wait");
                        $this.closest(".box").find(".btn").html("等待打款");
                    }else{
                        if( ret.data=='-1' ){ //未完善资料
                            showMsg("warning", '<p class="s-fc-h">您还【未完善资料】，完善后才可以使用现金券</p><p><a href="/login_bind/qq/" target="_blank" style="color:#0066FF;font-family:\\5FAE\\8F6F\\96C5\\9ED1;">马上去完善</a></p>');
                        }else if( ret.data=='-4' ){ //已过期
                            showMsg("warning", '<p class="s-fc-h">现金券已过期，无法使用</p><p>建议您，经常关注现金券的有效时间</p>');
                            $this.closest(".box").removeClass("J_CanExchange").addClass("z-exp");
                            $this.closest(".box").find(".btn").html("已过期");
                        }else if( ret.data=='-5' ){ //已作废
                            showMsg("warning", '<p class="s-fc-h">现金券已被管理员作废处理，无法使用</p>');
                            $this.closest(".box").removeClass("J_CanExchange").addClass("z-obs");
                            $this.closest(".box").find(".btn").html("已作废");
                        }else{
                            showMsg("warning",ret.data);
                        }
                    }
                });
            }
        });

        // 已作废-查看原因
        $(".z-obs  .btn").click(function(){
            $this = $(this);
            if($this.data("msg")){	//缓存，防止重复的提交
                showMsg("warning", $this.data("msg"));
            }else{
                load(shs.site('buyer','cash/show_destroy'),{'cid':$this.attr('data-cid')},function(ret){
                    showMsg("warning", '<p class="s-fc-h">现金券已被管理员作废处理，无法使用</p><p>'+ret.data+'</p>');
                    $this.data("msg", '<p class="s-fc-h">现金券已被管理员作废处理，无法使用</p><p>'+ret.data+'</p>');
                });
            }
        });

        // 待打款
        $(".z-wait .btn").click(function(){
            var price = $(this).closest(".z-wait").find(".price em").html();
            showMsg("succeed", '<p style="color: #1bb974;">现金券兑换成功！</p><p>众划算将会在两天内把<em class="" style="font-family:\\5FAE\\8F6F\\96C5\\9ED1">￥'+price+'</em>打到您互联支付账户</p>');
        });

        //已兑现
        $(".z-over .btn").click(function(){
            showMsg("warning", '<p class="s-fc-h">现金券已使用</p><p>提示：同一张现金券，只能使用一次</p>');
        });
        //已过期
        $(".z-exp .btn").click(function(){
            showMsg("warning", '<p class="s-fc-h">现金券已过期，无法使用</p><p>建议您，经常关注现金券的有效时间</p>');
        });

        // 兑换码换现金券
        $(".J_ExchangeCode2Voucher").click(function(){

            art.dialog({
                width: 314,
                lock: true,
                title: "兑换码换现金券",
                content: '<form class="t-dform">'
                +	'<dl>'
                +		'<dt><span>*</span>兑换码：</dt>'
                +		'<dd>'
                +			'<input type="text" class="u-ipt J_Code" name="cdkey"/>'
                +			'<p class="J_ShowError s-fc-h"></p>'
                +		'</dd>'
                +	'</dl>'
                +	'<dl>'
                +		'<dt>&nbsp;</dt>'
                +		'<dd>'
                +			'<input type="submit" class="u-btn" value="确定" />'
                +			'<a href="' + shs.site('buyer','cash/cdkey') + '" style="margin-left:15px;">兑换码使用记录</a>'
                +		'</dd>'
                +	'<dl>'
                +'</form>',
                init: function(){
                    var dialog = this,
                            $form = this.DOM.content.find(".t-dform"),
                            $code = $form.find(".J_Code"),
                            $show = $form.find(".J_ShowError");
                    $form.submit(function(){
                        if(!$code.val()){
                            $show.html("请输入兑换码");
                            return false;
                        }
                        if($code.val().length!=15){
                            $show.html("您输入的兑换码不正确");
                            return false;
                        }
                        $show.html("");
                        $.ajax({
                            url: shs.site('buyer','cash/change_cdkey'),
                            type:"post",
                            dataType:"json",
                            data: $form.serialize(),
                            error: function(){
                                $show.html("服务器繁忙，请稍后再试");
                            },
                            success: function(ret){
                                if(ret.success){
                                    dialog.close();
                                    showMsg("succeed",'<form class="t-dform"><p style="color: #1bb974;">现金券兑换成功！</p><p><a href="' + shs.site('buyer','cash/cdkey') + '">兑换码使用记录</a></p></form>');
                                }else{
                                    $show.html(ret.data);
                                }
                            }
                        });

                        return false;
                    });
                }
            })

        });
    }(jQuery);
});
