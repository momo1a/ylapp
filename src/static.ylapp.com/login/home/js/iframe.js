/**
 * 小窗登录功能js
 * Author: 陆楚良
 */
!function(undefined){
    /**
     * 倒计时方法
     * @param  {Number}   sec      单位：秒(实际效果可能会有轻微误差)
     * @param  {Function} callback 回调方法，会接收到一个剩余时间的单位，大概每秒会被执行一次，当倒计时结束后将不再被执行
     * @param  {Object}   context  可省，可设置callback内的上下文this对象
     */
    function cd(sec, callback, context){
        var timer = setInterval((function r(){
            if(sec < 0){
                clearInterval(timer);
            }else{
                callback.call(context, sec--);
            }
            return r;
        })(), 1000);
    }
    var Login = ({
        init: function(){
            var self = this;
            self.dom = {};
            self.dom.form =  $("#J_login");
            self.dom.tip  =  self.dom.form.find(".J_tip");
            self.dom.btn  =  self.dom.form.find(".J_submit");
            // 事件绑定
            self.dom.form.submit(function(){
                self.do_submit();
                return false;
            });
            delete self.init;
            return self;
        },
        /**
         * 验证是否是弹窗
         * @return {Boolean}
         */
        is_dialog: function(){
            return !!this.dom.form.data("dialog");
        },
        /**
         * 提示信息
         * @param  {String} msg [description]
         */
        tip  : function(msg){
            this.dom.tip.html(msg)[msg ? "show" : "hide"]();
        },
        /**
         * 设置、获取锁定状态
         * @param  {Boolean} status true上锁，false解锁，当不传任何参数时候为获取锁定状态
         */
        lock : function(status){
            if(status===undefined){
                return this.dom.btn.hasClass("z-dis");
            }else{
                this.dom.btn[status ? "addClass" : "removeClass"]("z-dis");
                return status;
            }
        },
        /**
         * 设置按钮value
         * @param  {String} val
         */
        btn_val: function(val){
            this.dom.btn.val(val);
        },
        /**
         * 恢复原始的按钮文字
         */
        btn_reset: function(){
            this.btn_val("登录");
        },
        /**
         * 验证用户输入
         * @return {Boolean} true为验证通过，false为不通过
         */
        check: function(){
            var $ipt_account = this.dom.form.find("input[name=account]");
            var $ipt_password= this.dom.form.find("input[name=password]");
            if(!$.trim($ipt_account.val())){
                this.tip("请输入您的账号信息。");
                $ipt_account.focus();
                return false;
            }
            if(!$.trim($ipt_password.val())){
                this.tip("请输入您的密码信息。");
                $ipt_password.focus();
                return false;
            }
            return true;
        },
        /**
         * 登录
         */
        do_submit: function(){
            var self = this;
            if(!self.lock() && self.check()){
                self.tip("");
                self.lock(true);
                self.btn_val("登录中...");
                $.ajax({
                    url: self.dom.form.attr("action"),
                    data: self.dom.form.serialize(),
                    dataType: "json",
                    type: self.dom.form.attr("method") || "GET",
                    success: function(json){self.response(json)},
                    error: function(){
                        self.tip("网络连接失败。");
                        self.lock(false);
                        self.btn_reset();
                    }
                });
            }
        },
        /**
         * 处理响应
         * @param  {Object} json ajax返回的json数据对象
         */
        response: function(json){
            this.btn_val("请稍候...");
            switch(json.state){
                case "SUCCESS":
                    // 弹窗下跳转页面（实际上是刷新当前页，因为jump取到的是父页面的url）
                    this.is_dialog() && this.jump(shs.query("jump"));
                    break;
                case "WRONG_SIMPLE_PASSWORD":
                    this.tip("密码过于简单,请立即修改");
                    // cd的第三参数指定了this，所以回调中的this为Login对象
                    cd(3, function(s){
                        this.btn_val("请稍候("+s+")...");
                        if(s==0){
                            this.jump(shs.url.weak_password);
                        }
                    }, this);
                    break;
                case "NO_ACTIVATE":
                case "ISOLD":
                    this.tip(json.message);
                    cd(3, function(s){
                        this.btn_val("请稍候("+s+")...");
                        if(s==0){
                            this.jump(json.url);
                        }
                    }, this);
                    break;
                default:
                    this.tip(json.message);
                    this.lock(false);
                    this.btn_val("登录");
                    break;
            }
        },
        /**
         * 跳转页面
         * @param  {String} url
         */
        jump: function(url){
            window.top.location.href = url;
        }
    }).init();
}();
