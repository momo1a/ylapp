/**
 * 订单列表页功能js
 */
Loader.use("frame","artDialog").run(function(){
    shs.order = function(order){
        "use strict";
        var undefined = void 0;
        var _order_auto_close_time_hour = order_auto_close_time_hour;
        var fn = {};
        fn.dialog = function(msg, title, icon, extend){
            // dialog(msg, extend)
            if($.type(title)=="object"){
                extend = title;
                title = undefined;
                icon  = undefined;
            }
            // dialog(msg, title, extend)
            if($.type(icon)=="object"){
                extend = icon;
                icon = undefined;
            }
            title = $.type(title)=="string" ? title : "温馨提示";
            icon  = $.type(icon )=="string" ? icon  : undefined;
            extend = $.type(extend)=="object" ? extend : {};
            return art.dialog({
                lock : true,
                fixed: true,
                id   : "dialog-order-fn",
                title: title,
                icon : icon,
                content: '<span class="s-fc-n">'+msg+'</span>',
                ok     : extend.ok,
                cancel : extend.cancel,
                close  : extend.close
            });
        };
        /**
         * 修改弹窗按钮状态
         * @param  {art.dialog} dialog  art.dialog对象
         * @param  {Boolean}    can_use 按钮是否可以使用
         * @return {art.dialog}
         */
        fn.dialog_btn_state = function(dialog, can_use){
            if (can_use) {
                dialog.DOM.buttons.find('button:first').html('确定').removeAttr('disabled').addClass('aui_state_highlight');
            } else {
                dialog.DOM.buttons.find('button:first').html('操作中...').attr('disabled', 'disabled').removeClass('aui_state_highlight');
            }
            return dialog;
        };
        /**
         * 获取order_data数据
         * @param  {Element|String|Number} oid  可以是.J_order_list下的任意子元素element对象或订单oid
         * @return {JSON}
         */
        order.order_data = function(oid){
            var $order;
            switch($.type(oid)){
                case "string":
                case "number":
                    $order = $("#J_oid_"+oid)
                    break;
                default:
                    $order = $(oid).closest(".J_order_list");
            }
            var order_data = $order.data("order_data");
            if(!order_data){
                var order_json = $order.find(".J_order_json").html();
                order_data = $.parseJSON(order_json);
                $order.data("order_data",order_data);
            }
            return order_data;
        };
        /**
         * 获取商品信息
         * @param {Number|String}   gid         商品id
         * @param {Function}        callback    回调，将接收到商品信息对象
         */
        order.goods_info = function(gid, callback){
            $.ajax({
                url : shs.site("buyer")+"order/goods_info/"+gid,
                dataType: "json",
                cache: true, /*缓存此条请求*/
                success: function(ret){
                    if(ret.success){
                        callback(ret.data);
                    }else{
                        fn.dialog("找不到此商品", "温馨提示", "error");
                    }
                },
                error: function(){
                    fn.dialog("服务器繁忙，请重试", "温馨提示", "error");
                }
            });
        };
        /**
         * 去下单
         * @param  {Object} order_data 订单数据对象
         */
        order.go_url = function(order_data){
            order.goods_info(order_data.gid, function(goods){
                var cont = '<span class="s-fc-n">请注意网购价格为: <em class="f-fwb s-fc-h">' + order_data.price + '</em> 元</span>';
                if(goods.qrcode_state){
                    cont = '<div style="width:400px;height:310px;" class="s-fc-n">'
                         +     '<p>下单前请确认网购价格为: <em class="f-fwb s-fc-h">' + order_data.price + '</em> 元，</p>'
                         +     '<p>并且此活动为二维码下单活动，请扫描二维码进入商家店铺购买（请勿用微信扫码）</p>'
                         +     '<p class="f-tac" style="margin-top: 10px;"><img src="'+ goods.qrcode_img +'" width="200" height="200"></p>'
                         + '</div>';
                }
                art.dialog({
                    lock : true,
                    fixed : true,
                    id:'dialog-order-go_url',
                    title : '商品下单提示',
                    content : cont,
                    okVal : goods.qrcode_state ? '确定' : '我知道了，现在去下单',
                    ok : function() {
                        !goods.qrcode_state && window.open(order_data.url);
                    },
                    cancel : !goods.qrcode_state
                });
            });
        };
        /**
         * 渲染填写单号弹出窗的html内容
         * @param  {Object}     order_data  订单数据对象
         * @param  {Function}   callback    回调
         */
        order._fill_trade_no_form_html = function(order_data, callback){
            order.goods_info(order_data.gid, function(goods){
                var html =  (function(){
                    if(goods.qrcode_state == 1){
                        return '<div class="t-fillTrade t-fillTrade-qrcode">'
                               +     '<div class="qrcode">'
                               +         '<img src="' + goods.qrcode_img + '" alt="二维码">'
                               +         '<p>该活动为二维码下单活动，请扫</p>'
                               +         '<p>描以上二维码进入商家店铺购买</p>'
                               +         '<p>（请勿用微信扫描）</p>'
                               +     '</div>';
                    }else{
                        return '<div class="t-fillTrade">';
                    }
                    })()
                    +     '<div class="mform">'
                    +         '<form>'
                    +             '<table>'
                    +                 '<tr class="small">'
                    +                     '<th>商品名称：</th>'
                    +                     '<td style="font-weight: bold;">'+ order_data.title +'</td>'
                    +                 '</tr>'
                    +                 '<tr class="small">'
                    +                     '<th>' + goods.source_name + '：</th>'
                    +                     '<td style="font-weight: bold;">￥'+ order_data.price +'</td>'
                    +                 '</tr>'
                    +                 '<tr>'
                    +                     '<th>订单编号：</th>'
                    +                     '<td>'
                    +                         '<input type="text" class="u-ipt" name="trade_no">'
                    +                         '<span class="s-fc-h J_tip_trade">&nbsp;</span>'
                    +                     '</td>'
                    +                 '</tr>'
                    +                 '<tr class="f-dn">'
                    +                     '<th>问题答案：</th>'
                    +                     '<td>'
                    +                         '<img class="code J_code" data-src="'+ shs.site('buyer', 'order/get_code/'+order_data.oid) +'" alt="验证码" title="点击刷新验证码">'
                    +                         '<input type="text" class="u-ipt" name="vcode" style="width: 75px;">'
                    +                         '<span class="s-fc-h J_tip_vcode">&nbsp;</span>'
                    +                     '</td>'
                    +                 '</tr>'
                    +             '</table>'
                    +         '</form>'
                    +         '<dl>'
                    +             '<dt>温馨提示：</dt>'
                    +             '<dd>'
                    +                 '<p>1、请填写已付款的订单编号，若填入未付款单号，属于违规行为且将无法获得返现；<a class="s-fc-a" href="http://help.zhonghuasuan.com/buyer/category/66/125/16" target="_blank">填写的订单号规则？</a></p>'
                    +                 '<p>2、若单号被审核有误，请在 '+_order_auto_close_time_hour+' 小时内进行申诉或修改，逾期将无法领回返现金;（建议平时经常登录网站查看站内信提醒哦！）<a class="s-fc-a" href="http://help.zhonghuasuan.com/buyer/category/63/81/124" target="_blank">如何获取订单编号？</a>'
                    +             '</dd>'
                    +         '</dl>'
                    +     '</div>'
                    + '</div>';
                callback(html);
            });

        };
        /**
         * 填写单号
         * @param  {Object} order_data 订单数据对象
         */
        order.fill_trade_no = function(order_data){
            order._fill_trade_no_form_html(order_data, function(form_html){
                art.dialog({
                    id    : 'dialog-order-fill_trade_no',
                    fixed : true,
                    lock  : true,
                    title : '填写单号',
                    content : form_html,
                    ok: function(){
                        this.DOM.content.find('form').submit();
                        return false;
                    },
                    init: function(){
                        var
                            dialog   = this,
                            $form    = dialog.DOM.content.find('form'),
                            $codeImg = $form.find(".J_code"),
                            $codeTr  = $codeImg.closest("tr"),
                            $trade   = $form.find("input[name=trade_no]"),
                            $vcode   = $form.find("input[name=vcode]"),
                            $tipTrade= $form.find(".J_tip_trade"),
                            $tipVcode= $form.find(".J_tip_vcode"),
                            is_captcha = 0
                        ;
                        // 获得焦点
                        $trade.focus();
                        // 点击刷新验证码
                        $codeImg.click(function(){
                            this.src = $(this).attr('data-src') +'?'+ Math.floor( Math.random()*10e8 );
                            $codeTr.removeClass("f-dn");    // 显示验证码输入框
                        });
                        $form.submit(function(){
                            if($form.data("post")){
                                return false;
                            }
                            var trade = $.trim($trade.val());
                            var vcode = $.trim($vcode.val());
                            $tipTrade.html('').removeClass("z-post");
                            $tipVcode.html('');

                            // 订单号只能包含数字或字母
                            if ( trade == '') {
                                $tipTrade.html('请填写订单号！');
                                $trade.focus();
                                return false;
                            } else if ( /[^\-0-9a-zA-Z]/.test(trade) ) {
                                $tipTrade.html('订单号格式有误！');
                                $trade.val('').focus();
                                return false;
                            }
                            // 验证码只能是数字且非空
                            if( is_captcha == 1 && !vcode ){
                                $tipVcode.html(' 验证码错误！');
                                $codeImg.click();
                                $vcode.focus();
                                return false;
                            }
                            // 锁住文本框
                            $trade.prop('disabled', true);
                            $vcode.prop('disabled', true);
                            $tipTrade.addClass("z-post").html("正在发送...");
                            // 锁住弹窗按钮
                            fn.dialog_btn_state(dialog, false);
                            // 表单上锁
                            $form.data("post", true);

                            var data = {trade_no : trade};
                            //判断是否需要传验证码参数
                            if( is_captcha ==1 ){
                                data.is_captcha = is_captcha;
                                data.vcode = vcode;
                            }
                            var complete = function(){
                                // 清除“正在发送...”文字
                                $tipTrade.html('').removeClass("z-post");
                                // 解锁文本框
                                $trade.prop('disabled', false);
                                $vcode.prop('disabled', false);
                                // 解锁弹窗按钮
                                fn.dialog_btn_state(dialog, true);
                                // 解锁表单
                                $form.data("post", false);
                            };
                            $.ajax({
                                url: shs.site('buyer', 'order/save_no/'+order_data.gid+'/'+order_data.oid),
                                data: data,
                                type: 'get',
                                dataType: 'json',
                                success: function (dt) {
                                    complete();
                                    if (dt.success) {
                                        fn.dialog('商家将在交易完成后审核返现。', '填写单号成功', 'succeed', {ok:true, close: function(){
                                            location.reload();
                                        }});
                                        dialog.close();
                                    } else {
                                        // 有验证码情况下，操作出错，则刷新验证码
                                        if(is_captcha == 1){
                                            $codeImg.click();
                                            $vcode.val('').focus();
                                        }
                                        switch(dt.data){
                                            case "NO_BIND_MOBILE":
                                                $tipTrade.html('未认证手机号码，<a href="'+shs.site('buyer')+'bind/mobile" target="_blank" class="s-fc-a">现在去认证</a>');
                                                break;
                                            case "CAPTCHA_IS_NULL":
                                                $tipVcode.html('请输入验证码！');
                                                // 无验证码情况下，填单过快，则刷新验证码
                                                is_captcha == 0 && $codeImg.click();
                                                is_captcha = 1;
                                                break;
                                            case "CAPTCHA_ERROR":
                                                $tipVcode.html('验证码错误！');
                                                // 无验证码情况下，填单过快，则刷新验证码
                                                is_captcha == 0 && $codeImg.click();
                                                is_captcha = 1;
                                                break;
                                            case "TRADE_NO_IS_NULL":
                                                $tipTrade.html('请填写订单号！');
                                                $trade.val('').focus();
                                                break;
                                            case "TRADE_NO_ERROR":
                                                $tipTrade.html('订单号格式有误！');
                                                $trade.val('').focus();
                                                break;
                                            default:
                                                $tipTrade.html(dt.data);
                                                $trade.val('');
                                        }
                                    }
                                },
                                error : function(xOptions, textStatus) {
                                    complete();
                                    if(textStatus=='timeout'){
                                        $tipTrade.html('系统连接超时，请稍后重试。');
                                    }else{
                                        $tipTrade.html('系统繁忙，请稍后重试！');
                                    }
                                }
                            });
                            return false;
                        });
                    }
                });
            });
        };
        /**
         * 修改单号
         * @param  {Object} order_data 订单数据对象
         */
        order.edit_trade_no = function(order_data){
            order._fill_trade_no_form_html(order_data, function(form_html){
                art.dialog({
                    id    : 'dialog-order-edit_trade_no',
                    fixed : true,
                    lock  : true,
                    title : '修改订单号',
                    content : form_html,
                    ok   : function(){
                        this.DOM.content.find('form').submit();
                        return false;
                    },
                    init : function(){
                        var
                            dialog   = this,
                            $form    = dialog.DOM.content.find('form'),
                            $codeImg = $form.find(".J_code"),
                            $codeTr  = $codeImg.closest("tr"),
                            $trade   = $form.find("input[name=trade_no]"),
                            $vcode   = $form.find("input[name=vcode]"),
                            $tipTrade= $form.find(".J_tip_trade"),
                            $tipVcode= $form.find(".J_tip_vcode"),
                            is_captcha = 0  // 其实修改单号没有验证码...
                        ;
                        // 获得焦点
                        $trade.focus();
                        $form.submit(function(){
                            if($form.data("post")){
                                return false;
                            }
                            var trade = $.trim($trade.val());
                            var vcode = $.trim($vcode.val());
                            $tipTrade.html('').removeClass("z-post");
                            $tipVcode.html('');

                            // 订单号只能包含数字或字母
                            if ( trade == '') {
                                $tipTrade.html('请填写订单号！');
                                $trade.focus();
                                return false;
                            } else if ( /[^\-0-9a-zA-Z]/.test(trade) ) {
                                $tipTrade.html('订单号格式有误！');
                                $trade.val('').focus();
                                return false;
                            }
                            // 验证码只能是数字且非空
                            // if( is_captcha == 1 && !vcode ){
                            //     $tipVcode.html(' 验证码错误！');
                            //     $codeImg.click();
                            //     $vcode.focus();
                            //     return false;
                            // }
                            // 锁住文本框
                            $trade.prop('disabled', true);
                            $vcode.prop('disabled', true);
                            $tipTrade.addClass("z-post").html("正在发送...");
                            // 锁住弹窗按钮
                            fn.dialog_btn_state(dialog, false);
                            // 表单上锁
                            $form.data("post", true);

                            // var data = {trade_no : trade};
                            //判断是否需要传验证码参数
                            // if( is_captcha ==1 ){
                            //     data.is_captcha = is_captcha;
                            //     data.vcode = vcode;
                            // }
                            var complete = function(){
                                // 清除“正在发送...”文字
                                $tipTrade.html('').removeClass("z-post");
                                // 解锁文本框
                                $trade.prop('disabled', false);
                                $vcode.prop('disabled', false);
                                // 解锁弹窗按钮
                                fn.dialog_btn_state(dialog, true);
                                // 解锁表单
                                $form.data("post", false);
                            };
                            $.ajax({
                                url: shs.site('buyer', '/order/user_edit_no/' + order_data.oid + '/' + trade),
                                // data: data,
                                type: 'get',
                                dataType: 'json',
                                success: function (dt) {
                                    complete();
                                    if (dt.success) {
                                        dialog.close();
                                        fn.dialog('商家将在交易完成后审核返现。', '修改单号成功', 'succeed', {ok:true, close: function(){
                                            location.reload();
                                        }});
                                    } else {
                                        switch(dt.data){
                                            case 'NO_BIND_MOBILE':
                                                $tipTrade.html('未认证手机号码，<a href="'+site('buyer')+'bind/mobile" target="_blank" class="s-fc-a">现在去认证</a>');
                                                break;
                                            case 'TRADE_NO_ERROR':
                                                $tipTrade.html('订单号格式有误！');
                                                $trade.val('').focus();
                                                break;
                                            default:
                                                $tipTrade.html(dt.data);
                                                $trade.val('').focus();
                                        }
                                    }
                                },
                                error : function(xOptions, textStatus) {
                                    complete();
                                    if(textStatus=='timeout'){
                                        $tipTrade.html('系统连接超时，请稍后重试。');
                                    }else{
                                        $tipTrade.html('系统繁忙，请稍后重试！');
                                    }
                                }
                            });
                            return false;
                        });
                    }
                });
            });
        };
        /**
         * 关闭订单
         * @param  {Object} order_data 订单数据对象
         */
        order.close = function(order_data){
            //订单在待填写单号情况下关闭订单
            var dialog = fn.dialog('关闭后将不能填写订单号，确定要关闭么？', "温馨提示", "question", {calcel:true, ok: function(){
                fn.dialog_btn_state(dialog, false);
                $.ajax({
                    url        : '/order/close/' + order_data.oid,
                    type    : 'GET',
                    dataType: 'json',
                    error    : function(){
                        dialog.close();
                        fn.dialog('网络连接失败', '错误提示', 'error', {ok:true});
                    },
                    success    : function(ret){
                        dialog.close();
                        var isSuccess = ret.success ? 1 : 0;
                        fn.dialog(ret.data, '温馨提示', ['error','succeed'][isSuccess], {ok:true, close: function(){
                            isSuccess && location.reload();
                        }});
                    }
                });
                return false;
            }});
        };
        /**
         * 查看日志
         * @param  {Object} order_data 订单数据对象
         */
        order.log = function(order_data){
            $.getJSON('/order/get_log/' + order_data.oid + '?_='+Math.random(), function(ret) {
                if (!ret.success) {
                    fn.dialog(ret.data, '温馨提示', 'error', {ok: true, close: function(){
                        location.reload();
                    }});
                    return;
                }
                var html = "";
                for (var i = 0, l = ret.data.length; i < l; i++) {
                    html += shs.template('<tr><td>${time}</td><td>${content}</td></tr>', ret.data[i]);
                }
                if (html.length) {
                    html = '<div style="width:523px;max-height:300px;overflow: auto;padding:20px 25px;" class="s-fc-n"><table class="m-table"><tr><th style="width:160px;">日期</th><th>内容</th></tr>' + html + '</table></div>';
                } else {
                    html = '<p>暂无记录信息。</p>';
                }
                art.dialog({
                    lock : true,
                    fixed : true,
                    title : '抢购记录',
                    padding: 0,
                    id:'dialog-order-log',
                    content : html,
                    ok : true
                });
             });
        };
        /**
         * 晒单
         * @param  {Object} order_data 订单数据对象
         */
        order.add_show = function(order_data){
            $.ajax({
                type : "post",
                url  : "/order/ajax_check_show/"+order_data.oid,
                dataType: 'json',
                success : function(ret) {
                    if (ret.bool) {
                        var html = '<div class="t-dialog-form s-fc-n">'
                                 +     '<form method="post" enctype="multipart/form-data">'
                                 +         '<dl class="f-cb">'
                                 +             '<dt>标题:</dt><dd>' + order_data.title + '</dd>'
                                 +         '</dl>'
                                 +         '<dl class="f-cb">'
                                 +             '<dt>照片:</dt>'
                                 +             '<dd>'
                                 +                 '<input type="file" name="img" class="imgipt" />'
                                 +                 '<span class="u-ipt f-ib f-toe J_imgShow">&nbsp;</span>&nbsp;'
                                 +                 '<a href="javascript:;" class="u-btn u-btn-s J_imgBtn">选择文件</a>'
                                 +             '</dd>'
                                 +         '</dl>'
                                 +         '<dl class="f-cb">'
                                 +             '<dt>评价:</dt><dd><textarea name="words" class="u-txa"></textarea></dd>'
                                 +         '</dl>'
                                 +         '<dl class="f-cb">'
                                 +             '<dt>&nbsp;</dt><dd class="s-fc-h J_tip">&nbsp;</dd>'
                                 +         '</dl>'
                                 +     '</form>'
                                 + '</div>';
                        art.dialog({
                            id   : "dialog-order-add_show",
                            lock : true,
                            fixed: true,
                            title: "我要晒单",
                            content: html,
                            ok   : function(){this.DOM.content.find('form').submit();return false;},
                            init : function(){
                                var dialog = this;
                                var $form  = dialog.DOM.content.find('form');
                                var $imgIpt= $form.find("input[name=img]");
                                var $imgShow=$form.find(".J_imgShow");
                                var $words = $form.find("textarea[name=words]");
                                var $tip   = $form.find(".J_tip");
                                $form.find(".J_imgBtn").click(function(){$imgIpt.click()});
                                $imgIpt.change(function(){$imgShow.html(this.value.match(/[^\\\/]*$/));});
                                $form.submit(function(){
                                    $tip.html("&nbsp;");
                                    var file = $imgIpt.val();
                                    if (!file || !file.match(/.jpg|.gif|.png|.bmp/i)) {
                                        $tip.html("请上传商品实拍照片！");
                                        return false;
                                    }
                                    var text = $words.val();
                                    if ($.trim(text) == '') {
                                        $tip.html('评价内容不能为空！');
                                        $words.val('').focus();
                                        return false;
                                    }
                                    fn.dialog_btn_state(dialog, false);
                                    $form.ajaxSubmit({
                                        type : "post",
                                        url  : shs.site("buyer") + "order/add_show/" + order_data.oid,
                                        dataType : 'json',
                                        error:function(ret){
                                            fn.dialog_btn_state(dialog, true);
                                            $tip.html('系统错误，请稍后重试。');
                                        },
                                        success : function(ret) {
                                            fn.dialog_btn_state(dialog, true);
                                            if (ret.success) {
                                                dialog.close();
                                                fn.dialog("晒单成功！", "温馨提示", "succeed", {ok: true, close: function(){
                                                    location.reload();
                                                }});
                                            }else{
                                                $tip.html(ret.data);
                                            }
                                        }
                                    });
                                    return false;
                                });
                            }
                        });
                    }else{
                        fn.dialog("亲，追加的商品再次抢购只需要晒单一次，划算金和折扣保持不变正常返现!", "温馨提示", "error");
                    }
                }
            });
        };
        /**
         * 申诉功能
         */
        order.appeal = function(ap){
            /**
             * 撤销申诉
             * @param  {Object} order_data 订单数据对象
             */
            ap.cancel = function(order_data){
                var dialog = fn.dialog('撤销申诉后抢购状态将恢复到申诉前的状态；确定要撤销申诉？', "温馨提示", "question", {calcel:true, ok: function(){
                    fn.dialog_btn_state(dialog, false);
                    $.ajax({
                        url     : '/order_appeal/cancel/'+order_data.oid+'/'+order_data.appeal_id,
                        type    : 'GET',
                        dataType: 'json',
                        error   : function(){
                            dialog.close();
                            fn.dialog('网络连接失败！', '温馨提示', 'errir', {ok:true});
                        },
                        success : function(ret){
                            dialog.close();
                            var isSuccess = ret.success ? 1 : 0;
                            fn.dialog(ret.data, '温馨提示', ['error','succeed'][isSuccess], {ok:true, close: function(){
                                isSuccess && location.reload();
                            }});
                        }
                    });
                    return false;
                }});
            };
            return ap;
        }({});
        /**
         * 特殊订单返现金额提示
         * @param  {Object}  order_data 订单数据对象
         * @param  {Element} follow     提示弹层跟随对象
         */
        order.adjust_tips = function(order_data, follow){
            $.each(art.dialog.list, function(){this.close()});
            var myDialog=art.dialog({
                title:false,
                width:350,
                height:50,
                padding:'0',
                follow:follow
            });
            $.ajax({
                url:'/order_appeal/show_adjust_tips',
                type:'get',
                data:{oid:order_data.oid},
                cache: true,
                dataType:'html',
                success:function(data){
                    myDialog.content(data)
                }
            });
        };

        // 事件委托绑定
        var Act = Frame.Action;
        var methods = ["go_url", "fill_trade_no", "edit_trade_no", "close", "log", "add_show", "appeal.cancel"];
        // 批量委托
        $.each(methods, function(i, method){
            Act[method] = function(){
                Function('shs.order.'+method+'(this)').call(order.order_data(this));
                return false;
            };
        });
        // adjust_tips方法与其他方法不一样，需要得到跟随的元素,所以得独立写
        Act.adjust_tips = function(){
            order.adjust_tips(order.order_data(this), this);
            return false;
        };
        return order;
    }({});
});
