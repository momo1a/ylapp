//商家买家切换
$(".title-right").mouseenter(function(){$(this).stop().animate({right:-1})}).mouseleave(function(){$(this).stop().animate({right:-56})});

//验证码切换
$("img[data-src]").click(function(){
    $(this).attr("src",$(this).attr("data-src")+"?v="+Math.random());
}).click();
$(".foo-change").click(function(){$("img[data-src]").click();return false;});



!function($,Com,Check){

    //获取域名
    var site = (function(){
        var d = location.host.replace(/(\w+\.)*(\w+\.com)/, '.$2');
        return function(s) {
            return 'http://' + s + d + '/';
        };
    })(); 

    //错误弹窗
    var error = function(msg){
        art.dialog({
        	id   : "J_error_dialog",
            icon : "error",
            title: "温馨提示",
            content:msg
        });
    };


    // 状态码
    var ST = {
        SUCCESS : 0,
        ERROR   : 1,
        INFO    : 2
    };
    /**
     * 显示信息
     * @param  {String} msg    需要显示的信息
     * @param  {Number} status 状态码
     */
    var showTip = function(msg, status){
        msg = Check.getMsg(msg)||msg||"&nbsp;";
        var parent = $(this).closest("span").removeClass('form-tip-conf form-tip-error form-tip-info');
        var tip    = parent.closest("li").find("div[data-type=msg]").removeClass("blue red");
        switch(status){
            case ST.SUCCESS:
                parent.addClass('form-tip-conf');
                tip.addClass('blue');
                break;
            case ST.ERROR:
                parent.addClass('form-tip-error');
                tip.addClass('red');
                break;
            case ST.INFO:
                parent.addClass('form-tip-info');
                tip.addClass('blue');
                break
        }
        tip.html(msg);
    };

    // 标识转ID
    var code2id = {
        "NAME"   : "#name",
        "PWD"    : "#password",
        "CAPTCHA": "#foo",
        "EMAIL"  : "#email",
        "MOBILE" : "#mobile",
        "ACTIVE" : "#active_code"
    };

    // 表单验证顶部配置
    var Config = {
        // 验证失败时触发，this指向验证失败的【元素】
        onerror:function(msg){
            showTip.call(this, msg, ST.ERROR);
        },
        // 验证正确时触发，this指向验证正确的【元素】
        onsuccess:function(msg){
            showTip.call(this, msg, ST.SUCCESS);
        },
        // 锁定时触发，this指向【顶点】
        onlock:function(){
            $(this).data("islock", true);
            $(this).find(".pure-button").addClass("pure-button-disabled");
        },
        // 解锁时触发，this指向【顶点】
        onunlock:function(){
            $(this).data("islock", false);
            $(this).data("ispost") || $(this).find(".pure-button").removeClass("pure-button-disabled");
        }
    };

    var J_form = $(".J_form");
    // 锁定表单 && 验证插件创建 && 提交事件
    J_form.data("islock", true).CL_Valitator(Config).submit(function(){
    	var $this = $(this);
        if($this.data("islock") || $this.data("ispost")){
            return;
        }
        $this.data("ispost", true);
        var btn = $this.find(".pure-button");
        var btnVal = btn.val();
        btn.val(btn.data("postval")).addClass('pure-button-disabled');
        $.ajax({
            url  : "/reg/check/",
            data : $this.serialize(),
            type : "post",
            dataType : "json",
            complete:function(){
                $this.data("ispost", false);
                $this.data("islock") || btn.removeClass('pure-button-disabled');
                btn.val(btnVal);
            },
            error:function(){error("服务器繁忙，请稍候再试。");},
            success:function(ret){
                if( !ret.success ){
                    var code = ret.data.errcode.match(/^(.+?)_/)[1];
                    var id = code2id[code];
                    switch(code){
                        case "PWD":
                            if(ret.data.errcode=="PWD_NOT_SAME"){
                                id = "#repassword";
                            }
                        case "CAPTCHA":
                            $("img[data-src]").click();
                        case "NAME":
                        case "EMAIL":
                        case "MOBILE":
                        case "ACTIVE":
                        	// CL_Valitator(Selector, String)   该功能为自定义表单错误
                        	// 使用String方法强制转换成字符串可避免当其不为字符串时候引起的表单验证插件执行别的操作
                        	J_form.CL_Valitator(id, String(Check.getMsg(ret.data.errcode)||ret.data.errtxt));
                            break;
                        default:
		                	// 由于无法预知的错误来源，弹窗提示，并且表单不上锁
                            error(Check.getMsg(ret.data.errcode)||ret.data.errtxt);
                    }
                }else{
                    if(J_form.data("step")==1){
                        // 第一步骤
                        window.location.href = site('reg')+'active/';
                    }else{
                        // 第二步骤
                        window.location.href = site('reg')+'success/';
                    }
                }
            }
        });
        return false;
    });

    /*---------------------------- 表单验证开始 ----------------------------*/

    // 用户名
    J_form.CL_Valitator("#name", {
        success: "用户名可以注册，注册成功后将不能修改。",
        onfocus: function(){showTip.call(this, "6-50个字符，1个汉字为2个字符。推荐使用中文用户名。", ST.INFO)},
        rules: [{
            type: "callback",
            action:function(value, callback){
                Check.name(value, function(ret){
                    ret ? callback(false, ret) : callback(true);
                });
            }
        }]
    });

    // 密码
    J_form.CL_Valitator("#password", {
        success: "",
        onfocus: function(){showTip.call(this, "密码为6-20个字符，请使用字母加数字或下划线组合密码。", ST.INFO)},
        rules: [{
            type: "callback",
            action:function(value, callback){
                // $("#repassword").val() && value!=$("#repassword").val() && $("#repassword").blur();
                // CL_Valitator(Selector, Function) 第一个参数是个选择器，第二个参数是个函数。表示触发某个表单的验证
                $("#repassword").val() && J_form.CL_Valitator("#repassword", function(){});
                if($("#name").val() && value.toLowerCase().indexOf($("#name").val().toLowerCase()) > -1){
                    // 密码中不能包含用户名
                    callback(false, "PWD_NOT_NAME");
                }else{
                    Check.password(value, function(ret){
                        ret ? callback(false, ret) : callback(true);
                    });
                }
            }
        }]
    });

    // 确认密码
    J_form.CL_Valitator("#repassword", {
        success: "",
        onfocus: function(){showTip.call(this, "请再次输入刚才的密码。", ST.INFO)},
        rules: [{
            type: "callback",
            action:function(value, callback){
                var pwd = $("#password").val();
                Check.password(pwd, function(ret){
                    if(ret || $("#name").val() && pwd.toLowerCase().indexOf($("#name").val().toLowerCase()) > -1){
                        callback(false, "请先输入正确的密码");
                    }else if(!value || value!=pwd){
                        callback(false, "PWD_NOT_SAME");
                    }else{
                        callback(true);
                    }
                });
            }
        }]
    });

    // 验证码
    J_form.CL_Valitator("#foo", {
        success: "",
        onfocus: function(){showTip.call(this, "请输入验证码,不区分大小写！", ST.INFO)},
        rules: [{
            type: "callback",
            action:function(value, callback){
                Check.captcha(value, function(ret){
                    if(ret){
                        $("img[data-src]").click();
                        callback(false, ret);
                    }else{
                        callback(true);
                    }
                });
            }
        }]
    });

    // 用户使用协议复选框
    J_form.CL_Valitator("#cb", {
        success: "",
        rules: [{
            type: "callback",
            action:function(value, callback){
                if($(this).prop("checked")){
                    callback(true);
                }else{
                    callback(false);
                }
            }
        }]
    }).find("#cb").prop("checked",true).change(function(){$(this).blur()}).blur();

    // 邮箱
    J_form.CL_Valitator("#email", {
        success: "邮箱可以使用。",
        onfocus: function(){showTip.call(this, "登录账号为您经常使用的邮箱账号，建议使用163或QQ邮箱。", ST.INFO)},
        rules: [{
            type: "callback",
            action:function(value, callback){
                Check.email(value, function(ret){
                    ret ? callback(false, ret) : callback(true);
                });
            }
        }]
    });

    // 手机
    J_form.CL_Valitator("#mobile", {
        success: "您将收到0771-3186577或021-31234559的来电，免费接听获取验证码，请保持手机畅通！",
        onfocus: function(){showTip.call(this, "请填写认证的手机。", ST.INFO)},
        rules: [{
            type: "callback",
            action:function(value, callback){
                Check.mobile(value, function(ret){
                    ret ? callback(false, ret) : callback(true);
                });
            }
        }]
    });

    // 激活验证码
    J_form.CL_Valitator("#active_code", {
        success: "",
        onfocus: function(){
            var active_type = J_form.find("#active_type").val();
            var msg = {
                "1" : "登录账号为您经常使用的邮箱账号，建议使用163或QQ邮箱。",
                "2" : "请填写收到的语音验证码。"
            }[active_type];
            showTip.call(this, msg, ST.INFO)
        },
        rules: [{
            type: "callback",
            action:function(value, callback){
                Check.active_code({
                    'active_code'   : value,
                    'method'        : 'check_active_code',
                    'active_type'   : J_form.find("#active_type").val(),
                    'account'       : J_form.find('[name="account"]').val()
                }, function(ret){
                    ret ? callback(false, ret) : callback(true);
                });
            }
        }]
    });

    // 输入框提示 && 防表单缓存
    J_form.find(":text,:password").placeholder().each(function(){
        if($(this).val()){
            $(this).blur();
        }
    });
    /*---------------------------- 表单验证结束 ----------------------------*/


    /*---------------------------- 特殊表单功能 ----------------------------*/
    // 邮箱地址改变时更换邮件登录地址
    J_form.find("#email").change(function(){
        var url = Com.getEmailDomain(this.value);
        var ge  = $(".goto-mail");
        if(url && url==ge.data("url")){
        	ge.css("visibility","visible");
    	}else{
        	ge.css("visibility","hidden");
    	}
    });

    //发送验证码
    !function(){
        //发送验证码按钮
        var $send_btn = $(".send-btn");
        if($send_btn.length<=0)return;
        //发送验证码cd
        var cd = function(s,m){
            m = m||"已发送验证码";
            $send_btn.data("lock",true);
            var timer = setInterval((function cdr(){
                s--;
                if(s<=0){
                    $send_btn.html("获取验证码");
                    $send_btn.data("lock",false);
                    clearInterval(timer);
                }else{
                    $send_btn.html(m+"("+s+")");
                }
                return cdr;
            })(), 1000);
        };
        //初始化发送按钮cd
        cd( Number($('#time_out').val()) ,"请稍候");

        //点击发送
        $send_btn.click(function(){
            if($send_btn.data("lock")==true)return;
            $send_btn.html("正在发送..").data("lock",true);
            var gotourl = Com.getEmailDomain(J_form.find("#email").val()||"");
            $.ajax({
                url  : "/reg/check/",
                type : "post",
                data : {
                    'account'    : $('[name="account"]').val(),
                    'valiCode'   : $('[name="valiCode"]').val(),
                    'active_type': $('#active_type').val(),
                    'method'     : 'check_send_active'
                },
                dataType : "json",
                error:function(){error("服务器繁忙，请稍候再试。");$send_btn.html("获取验证码").data("lock",false);},
                success:function(ret){
                    $send_btn.html("获取验证码").data("lock",false);
                    if(ret.success){
                    	cd(60);
                        $(".goto-mail").data("url",  gotourl)
                                       .attr("href", gotourl||"javascript:;")
                                        .css("visibility", (gotourl?"visible":"hidden"));
                    }else{
                		var sec = (ret.data.left_sec && Number(ret.data.left_sec)>0) ? Number(ret.data.left_sec) : 0;
                    	sec && cd(sec, "请稍候");
                        var code = ret.data.errcode.match(/^(.+?)_/)[1];
                        var id = code2id[code];
                        switch(code){
                            case "CAPTCHA":
                                $("img[data-src]").click();
                            case "EMAIL":
                            case "MOBILE":
                            case "ACTIVE":
	                        	// CL_Valitator(Selector, String)   该功能为自定义表单错误
	                        	// 使用String方法强制转换成字符串可避免当其不为字符串时候引起的表单验证插件执行别的操作
	                        	J_form.CL_Valitator(id, String(Check.getMsg(ret.data.errcode)||ret.data.errtxt));
	                            break;
	                        default:
	                            error(Check.getMsg(ret.data.errcode)||ret.data.errtxt);
                        }
                    }
                }
            });
        });    
    }();

}(jQuery,Com,Check);