Loader.use("frame").run(function(){
    "use strict";
    var undefined = void 0;
    var fn = {};
    /**
     * 错误提示
     * @param  {jQuery} $element 引发错误的form表单
     * @param  {String} msg      错误信息
     */
    fn.showError = function($element,msg){
        $element.css('display','inline-block').html(msg);
    };
    /**
     * 倒计时方法
     * @param  {Number}   cd       倒计时时间，单位：秒
     * @param  {Function} callback 在倒计时期间，每隔一秒被执行一次，将会接收到一个参数，此参数的值为剩余时间
     * @param  {Object}   textcont 可选，设置callback上下文this对象
     * @return {Number}   timer    时钟ID
     */
    fn.countdown = function (cd, callback, textcont) {
        var timer = setInterval(function () {
            callback.call(textcont, cd -= 1);
            if (cd <= 0) {
                clearInterval(timer);
            }
        }, 1000);
        callback.call(textcont, cd);
        return timer;
    };
    /**
     * ajax提交表单
     * @param  {Element}  formElement 表单对象
     * @param  {Function} callback    回调
     */
    fn.ajaxTodo = function(formElement, callback){
        var $form  = $(formElement);
        $.ajax({
            url  : $form.attr("action"),
            data : $form.serializeArray(),
            type : $form.attr("method") || "get",
            dataType:"json",
            error:function(){
                callback({
                    error: true,
                    data : {errtxt:"服务器繁忙，请重试"}
                });
            },
            success:function(ret){
                callback(ret);
            }
        });
    };

    var Bind = {
        step: function(hash){
            if(/^\#\!\/step\-.+$/.test(hash)){
                $(".J_step").hide().filter("[data-rel='"+hash+"']").show();
                location.hash = hash;
            }
        },
        check: { // 注：this对象为来自do_check中的param对象
            email_verify : function(str, callback){
                if(!$.trim(str)){
                    callback("请输入邮箱验证码");
                }else{
                    callback(null);
                }
            },
            phone: function(str, callback){
                if(!$.trim(str)){
                    callback("请输入手机号码");
                }else{
                    callback(null);
                }
            },
            captcha: function(str, callback){
                if(!$.trim(str)){
                    callback("请输入图片验证码");
                }else{
                    callback(null);
                }
            },
            verifycode: function(str, callback){
                if(!$.trim(str)){
                    callback("请输入手机验证码");
                }else{
                    callback(null);
                }
            }
        },
        // 遍历验证表单
        do_check: function(params, callback){
            var length = params.length;
            (function Each(index){
                if(index==length){
                    callback(null);
                }else{
                    if($.type(Bind.check[params[index].name])=="function"){
                        Bind.check[params[index].name].call(params, params[index].value, function(error){
                            if(error === null){
                                Each(index+=1);
                            }else{
                                callback(error);
                            }
                        });
                    }else{
                        Each(index+=1);
                    }
                }
            })(0);
        },
        /**
         * step-1、发送邮件验证码
         * action  /bind/check/
         * type    post
         * param   method       get_email_verify
         */
        sendEmailCode: function(formElement){
            var $form  = $(formElement);
            var $btn   = $form.find(":submit");
            var $error = $form.closest(".J_forms").find(".J_error");
            if(!$btn.hasClass("z-dis")){
                $btn.addClass('z-dis').val("正在发送验证码..");
                $error.hide();
                fn.ajaxTodo(formElement, function(ret){
                    var t="",i=0;
                    if(!ret.success){
                        if(ret.data.errcode=="SEND_TIMES_OUT"){
                            i = parseInt(ret.data.sec);
                            t="操作过于频繁";
                        }
                        fn.showError($error,ret.data.errtxt);
                    }else{
                        t="已发送验证码";
                        i=60;
                    }
                    fn.countdown(i, function(){
                        if(i==0){
                            $btn.val("获取邮箱验证码").removeClass('z-dis');
                        }else{
                            $btn.val(t+"("+(i--)+"s)");
                        }
                    });
                });
            }
        },
        /**
         * step-1、提交邮件验证
         * action  /bind/check/
         * type    post
         * param   method       check_email_verify
         * param   email_verify 邮件验证码
         */
        submitEmailVerify: function(formElement){
            var $form = $(formElement);
            var $btn  = $form.find(":submit");
            var $error= $form.closest(".J_forms").find(".J_error");
            if(!$btn.hasClass('z-dis')){
                $btn.addClass("z-dis");
                $error.hide();
                var params  = $form.serializeArray();
                Bind.do_check(params, function(error){
                    if(error!==null){
                        fn.showError($error, error);
                        $btn.removeClass("z-dis");
                    }else{
                        fn.ajaxTodo(formElement, function(ret){
                            $btn.removeClass("z-dis");
                            if(!ret.success){
                                fn.showError($error,ret.data.errtxt);
                            }else{
                                Bind.step("#!/step-2");
                            }
                        });
                    }
                });
            }
        },
        /**
         * step-2、发送语音验证码
         * action  /bind/check/
         * type    post
         * param   method       check_save_mobile
         * param   phone        手机号
         * param   captcha      图片验证码
         */
        sendSoundCode: function(formElement){
            var $form = $(formElement);
            var $btn  = $form.find(":submit");
            var $error= $form.closest(".J_forms").find(".J_error");
            if(!$btn.hasClass("z-dis")){
                $error.hide();
                var params = $form.serializeArray();
                Bind.do_check(params, function(error){
                    if(error!==null){
                        fn.showError($error, error);
                        $btn.removeClass("z-dis");
                    }else{
                        $btn.addClass('z-dis').val("正在发送验证码..");
                        fn.ajaxTodo(formElement, function(ret){
                            var t="",i=0;
                            if(!ret.success){
                                switch(ret.data.errcode){
                                    case "MOBILE_SEND_TIMES_OUT_SEC":
                                    case "MOBILE_SEND_TIMES_OUT_MIN":
                                        i = parseInt(ret.data.sec);
                                        t="操作过于频繁";
                                        break;
                                }
                                $form.find(".J_verify img").click();
                                fn.showError($error,ret.data.errtxt);
                            }else{
                                t="已发送验证码";
                                i=60;
                            }
                            fn.countdown(i, function(){
                                if(i==0){
                                    $btn.val("获取语音验证码").removeClass('z-dis');
                                }else{
                                    $btn.val(t+"("+(i--)+"s)");
                                }
                            });
                        });
                    }
                });
            }
        },
        /**
         * step-2、提交绑定手机
         * action  /bind/check/
         * type    post
         * param   method       check_save_mobile
         * param   phone        手机号
         * param   verifycode   语音验证码
         */
        submitSaveMobile: function(formElement){
            var $form = $(formElement);
            var $btn  = $form.find(":submit");
            var $error= $form.closest(".J_forms").find(".J_error");
            if(!$btn.hasClass('z-dis')){
                $btn.addClass("z-dis");
                $error.hide();
                $form.find("[name=phone]").val($(".J_phone").val());    // 将前一个表单的手机号输入框的值赋予到隐藏域
                var params  = $form.serializeArray();
                Bind.do_check(params, function(error){
                    if(error!==null){
                        fn.showError($error, error);
                        $btn.removeClass("z-dis");
                    }else{
                        fn.ajaxTodo(formElement, function(ret){
                            $btn.removeClass("z-dis");
                            if(!ret.success){
                                fn.showError($error,ret.data.errtxt);
                            }else{
                                Bind.step("#!/step-3");
                            }
                        });
                    }
                });
            }
        },
        /**
         * 修改手机：step-1、发送语音验证码
         * action  /bind/check/
         * type    post
         * param   method       modify_get_voice_verify
         */
        sendModifySoundCode: function(formElement){
            var $form = $(formElement);
            var $btn  = $form.find(":submit");
            var $error= $form.closest(".J_forms").find(".J_error");
            if(!$btn.hasClass("z-dis")){
                $error.hide();
                var params = $form.serializeArray();
                Bind.do_check(params, function(error){
                    if(error!==null){
                        fn.showError($error, error);
                        $btn.removeClass("z-dis");
                    }else{
                        $btn.addClass('z-dis').val("正在发送验证码..");
                        fn.ajaxTodo(formElement, function(ret){
                            var t="",i=0;
                            if(!ret.success){
                                switch(ret.data.errcode){
                                    case "MOBILE_SEND_TIMES_OUT_SEC":
                                    case "MOBILE_SEND_TIMES_OUT_MIN":
                                        i = parseInt(ret.data.sec);
                                        t="操作过于频繁";
                                        break;
                                    case "REMOVE_AUTH_ALREADY":
                                        ret.data.errtxt += '，请切换到<a href="/bind/safe">手机认证页</a>完成认证';
                                        break;
                                }
                                $form.find(".J_verify img").click();
                                fn.showError($error,ret.data.errtxt);
                            }else{
                                t="已发送验证码";
                                i=60;
                            }
                            fn.countdown(i, function(){
                                if(i==0){
                                    $btn.val("获取语音验证码").removeClass('z-dis');
                                }else{
                                    $btn.val(t+"("+(i--)+"s)");
                                }
                            });
                        });
                    }
                });
            }
        },
        /**
         * 修改手机：step-1、提交语音验证
         * action  /bind/check/
         * type    post
         * param   method       modify_check_voice_verify
         * param   verifycode   语音验证码
         */
        submitModifySoundVerify: function(formElement){
            var $form = $(formElement);
            var $btn  = $form.find(":submit");
            var $error= $form.closest(".J_forms").find(".J_error");
            if(!$btn.hasClass('z-dis')){
                $btn.addClass("z-dis");
                $error.hide();
                var params  = $form.serializeArray();
                Bind.do_check(params, function(error){
                    if(error!==null){
                        fn.showError($error, error);
                        $btn.removeClass("z-dis");
                    }else{
                        fn.ajaxTodo(formElement, function(ret){
                            $btn.removeClass("z-dis");
                            if(!ret.success){
                                fn.showError($error,ret.data.errtxt);
                            }else{
                                Bind.step("#!/step-2");
                            }
                        });
                    }
                });
            }
        },
        /**
         * 修改手机：step-1、发送邮件验证码
         * action  /bind/check/
         * type    post
         * param   method   modify_get_email_verify
         */
        sendModifyEmailCode: function(formElement){
            var $form  = $(formElement);
            var $btn   = $form.find(":submit");
            var $error = $form.closest(".J_forms").find(".J_error");
            if(!$btn.hasClass("z-dis")){
                $btn.addClass('z-dis').val("正在发送验证码..");
                $error.hide();
                fn.ajaxTodo(formElement, function(ret){
                    var t="",i=0;
                    if(!ret.success){
                        switch(ret.data.errcode){
                            case "SEND_TIMES_OUT":
                                i = parseInt(ret.data.sec);
                                t="操作过于频繁";
                                break;
                            case "REMOVE_AUTH_ALREADY":
                                ret.data.errtxt += '，请切换到<a class="s-fc-a" href="/bind/safe"> 手机认证页 </a>完成认证';
                                break;
                        }
                        fn.showError($error,ret.data.errtxt);
                    }else{
                        t="已发送验证码";
                        i=60;
                    }
                    fn.countdown(i, function(){
                        if(i==0){
                            $btn.val("获取邮箱验证码").removeClass('z-dis');
                        }else{
                            $btn.val(t+"("+(i--)+"s)");
                        }
                    });
                });
            }
        },
        /**
         * 修改手机：step-1、提交邮件验证
         * action  /bind/check/
         * type    post
         * param   method       modify_check_email_verify
         * param   email_verify 邮件验证码
         */
        submitModifyEmailVerify: function(formElement){
            Bind.submitModifySoundVerify(formElement);
        },
        /**
         * 修改手机：step-2、发送语音验证码
         * action  /bind/check/
         * type    post
         * param   method      modify2_get_voice_verify
         * param   phone       新手机号
         * param   captcha     图片验证码
         */
        sendModify2SoundCode: function(formElement){
            Bind.sendModifySoundCode(formElement);
        },
        /**
         * 修改手机：step2、提交修改手机
         * action  /bind/check/
         * type    post
         * param   method      check_save_modify_mobile
         * param   phone       新手机号
         * param   verifycode  手机验证码
         */
        submitModifySaveMobile: function(formElement){
            var $form = $(formElement);
            var $btn  = $form.find(":submit");
            var $error= $form.closest(".J_forms").find(".J_error");
            if(!$btn.hasClass('z-dis')){
                $btn.addClass("z-dis");
                $error.hide();
                $form.find("[name=phone]").val($(".J_phone").val());    // 将前一个表单的手机号输入框的值赋予到隐藏域
                var params  = $form.serializeArray();
                Bind.do_check(params, function(error){
                    if(error!==null){
                        fn.showError($error, error);
                        $btn.removeClass("z-dis");
                    }else{
                        fn.ajaxTodo(formElement, function(ret){
                            $btn.removeClass("z-dis");
                            if(!ret.success){
                                if(ret.data.errcode=="REMOVE_AUTH_ALREADY"){
                                    ret.data.errtxt+'，请切换到<a class="s-fc-a" href="/bind/safe">手机认证页</a>完成认证';
                                }
                                fn.showError($error,ret.data.errtxt);
                            }else{
                                Bind.step("#!/step-3");
                            }
                        });
                    }
                });
            }
        }
    };

    var Act = Frame.Action;
    Act.sendEmailCode     = function(event, form){Bind.sendEmailCode(form || this);     return false;};
    Act.submitEmailVerify = function(event, form){Bind.submitEmailVerify(form || this); return false;};
    Act.sendSoundCode     = function(event, form){Bind.sendSoundCode(form || this);     return false;};
    Act.submitSaveMobile  = function(event, form){Bind.submitSaveMobile(form || this);  return false;};
    Act.sendModifySoundCode      = function(event, form){Bind.sendModifySoundCode(form || this);     return false;};
    Act.submitModifySoundVerify  = function(event, form){Bind.submitModifySoundVerify(form || this); return false;};
    Act.sendModifyEmailCode      = function(event, form){Bind.sendModifyEmailCode(form || this);     return false;};
    Act.submitModifyEmailVerify  = function(event, form){Bind.submitModifyEmailVerify(form || this); return false;};
    Act.sendModify2SoundCode     = function(event, form){Bind.sendModify2SoundCode(form || this);    return false;};
    Act.submitModifySaveMobile   = function(event, form){Bind.submitModifySaveMobile(form || this);  return false;};
    Act.changeCaptcha  = function(event, img){
        var $img = img ? $(img) : $(this).closest(".J_verify").find("img");
        $img.attr("src", $img.data("src")+"?_="+Math.random());
    }
    // 切换绑定方式
    Act.step = function(event, rel){
        Bind.step(rel);
    };
    // 根据哈希显示表单
    // 例：  http://xxx.xxx.com/#!/step-1
    // hash值为 #!/step-1 的url将会显示step-1内容
    $(window).on("hashchange",function(){
        Bind.step(location.hash);
    });
    Bind.step("#!/step-"+$(".bcont").data("step"));
});
