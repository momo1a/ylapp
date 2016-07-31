Loader.use("frame", "artDialog").run(function(){
	'use strict';
    /**
     * 以字节为单位计算字符串长度
     * @param  {String} strTemp     传入的字符串
     * @param  {NUmber} bytes       可选，缺省2，一个汉字占用的字节数
     * @return {Number}
     */
	function lengthAt(strTemp, bytes){
	    var i, sum;
	    sum = 0;
        bytes = bytes || 2;
	    for (i = 0; i < strTemp.length; i++) {
	        if ((strTemp.charCodeAt(i) >= 0) && (strTemp.charCodeAt(i) <= 255))
	            sum = sum + 1;
	        else
	            sum = sum + bytes;
	    }
	    return sum;
	}
    /**
     * 倒计时方法
     * @param  {Number}   sec      单位：秒
     * @param  {Function} callback 回调
     * @param  {Number}   timer
     */
    function cd(sec, callback){
        var timer = setInterval((function run(){
            callback(sec);
            if(sec==0){
                clearInterval(timer);
            }else{
                sec--;
            }
            return run;
        })(), 1000);
        return timer;
    }
    var Bind = {
        _rel: '#!/email',
        change: function(rel){
            if(rel!==this._rel){
                this._rel = rel=='#!/phone' ? '#!/phone' : '#!/email';
                $(".J_form").hide().filter("[data-rel='" + this._rel + "']").show();
                if(this._rel=="#!/phone"){
                    Frame.Action.change_captcha();
                }
            }
        },
        check: { // 注：this对象为来自do_check中的param对象
            username: function(str,callback){
            	if(lengthAt(str)<6 || lengthAt(str)>50)
            		return callback("用户名限制6-50个字符，1个汉字为2个字符。推荐使用中文用户名。");
            	if(/\d{5}/.test(str))
            		return callback("用户名中不能包含多个数字，推荐使用中文用户名。");
                callback(null);
            },
            password : function(str,callback){
            	str = str.toLowerCase();
                if(!str)
                    return callback("请设置密码。");
                if(/^[a-z]+$/.test(str))
                    return callback("密码不能为纯字母。");
                if(/^\d+$/.test(str))
                	return callback("密码不能为纯数字。");
                if(/^_+$/.test(str))
                    return callback("密码不能为纯符号。");
                if(!/^[_0-9a-z]{6,20}$/.test(str))
                    return callback("密码限制6-20个字符，请使用字母加数字或下划线组合密码。");
                callback(null);
            },
            confirmPassword: function(str,callback){
                var password;
                if($.type(this)=="array"){
                    // password存在于param中，遍历取出password值
                    for(var i=0;i<this.length;i++){
                        if(this[i].name=="password"){
                            password = this[i].value;
                            break;
                        }
                    }
                }
                if(!str){
                    callback("请输入确认密码。");
                }else if(password && password!==str){
                    callback("两次密码不匹配。");
                }else{
                    callback(null);
                }
            },
            mobile : function(str,callback){
            	if(!/^1\d{10}$/.test(str))
            		return callback("您填写的不是一个有效的手机号码，请输入一个有效的手机号码。");
                callback(null);
            },
            email : function(str,callback){
            	if(str.length>100)
            		return callback("您的电子邮箱过长了请换另一个,只能在100个字符以内。");
            	if(!/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(str))
            		return callback("您填写的不是一个有效的电子邮件地址，请输入一个有效的电子邮件地址。");
                callback(null);
            },
            // 图形验证码
            captcha: function(str,callback){
            	if(!str)
            		return callback("请输入图形验证码,不区分大小写！");
                if(str.length<4)
                	return callback("您的图形验证验码填写不正确！");
                callback(null);
            },
            code: function(str,callback){
            	if(!str)
            		return callback("语音验证码不能为空。");
                if(str.length < 4 || str.length > 8)
                	return callback("语音验证码不正确。");
                callback(null);
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
        // 发送邮件验证码
        send_email: function(buttonElement){
            if($(buttonElement).hasClass("z-dis")){
                return;
            }
            var $btn   = $(buttonElement).addClass("z-dis");
            var $form  = $btn.closest("form");
            var $tip   = $form.find(".J_send_tip").html('').hide();
            var param  = [
                {name:"email", value:$form.find("input[name=email]").val()}
            ];
            Bind.do_check(param, function(error){
                if(error!==null){
                    $tip.html(error).show();
                    $btn.removeClass("z-dis");
                }else{
                    $.ajax({
                        url: shs.site("buyer") + 'login_bind/send_email',
                        type: 'POST',
                        data: param,
                        dataType: "json",
                        error:function(){
                        	$btn.val('重新发送验证码').removeClass("z-dis");
                            $tip.html("邮件发送失败！").show();
                        },
                        success:function(ret){
                			if (ret.state) {
                                // 倒计时
                                cd(60, function(s){
                                    if(s>0){
                                        $btn.val("邮件已发送("+s+")");
                                    }else{
                                        $btn.val("重新发送验证码").removeClass("z-dis");
                                    }
                                });
                			}else {
                                $tip.html(ret.message).show();
                                $btn.val("重新发送验证码").removeClass("z-dis");
                			}
                        }
                    });
                }
            });
        },
        // 发送语音验证码
        send_sound: function(buttonElement){
            if($(buttonElement).hasClass("z-dis")){
                return;
            }
            var $btn   = $(buttonElement).addClass("z-dis");
            var $form  = $btn.closest("form");
            var $tip   = $form.find(".J_send_tip").html('').hide();
            var param  = [
                {name:"mobile",  value:$form.find("input[name=mobile]").val()},
                {name:"captcha", value:$form.find("input[name=captcha]").val()}
            ];
            Bind.do_check(param, function(error){
                if(error!==null){
                    $tip.html(error).show();
                    $btn.removeClass("z-dis");
                }else{
                    $.ajax({
                        url: shs.site("buyer") + 'login-bind/send-sound-captcha',
                        type: 'POST',
                        data: param,
                        dataType: "json",
                        error:function(){
                        	$btn.val('重新发送语音验证码').removeClass("z-dis");
                            $tip.html("语音验证码发送失败！").show();
                        },
                        success:function(ret){
                			if (ret.state) {
                                // 倒计时
                                cd(60, function(s){
                                    if(s>0){
                                        $btn.val("语音验证码已发送("+s+")");
                                    }else{
                                        $btn.val("重新发送语音验证码").removeClass("z-dis");
                                    }
                                });
                			}else {
                                $tip.html(ret.message).show();
                                $btn.val("重新发送语音验证码").removeClass("z-dis");
                			}
                        }
                    });
                }
            });
        },
        /**
         * 绑定
         * @param  {Element} buttonElement 提交按钮的原生对象
         */
        bind: function(buttonElement){
            if($(buttonElement).hasClass("z-dis")){
                return;
            }
            var $btn   = $(buttonElement).addClass("z-dis");
            var $form  = $btn.closest("form");
            var params = $form.serializeArray();
            var $tip   = $form.find(".J_tip").html('').hide();
            Bind.do_check(params, function(error){
                if(error!==null){
                    $tip.html(error).show();
                    $btn.removeClass("z-dis");
                }else{
                    $.ajax({
                        url: $form.attr('action'),
                        data: params,
                        type: 'POST',
                        dataType: 'JSON',
                        error:function() {
                            $tip.html("服务器出错").show();
                            $btn.removeClass("z-dis");
                        },
                        success:function(ret) {
                            $btn.removeClass("z-dis");
                            if (ret.state) {
                                art.dialog({
                                    title: "温馨提示",
                                    content: "账号信息已完善，请重新登录",
                                    icon: "succeed",
                                    lock:true,
                                    time: 5,
                                    ok: true,
                                    close: function(){
                                        window.location.href = shs.site("login");
                                    }
                                });
                            }else {
                                $tip.html(ret.message).show();
                            }
                        }
                    });
                }
            });
        },
		/**
		 * 更换绑定QQ、解除绑定
		 */
		changeOrUnbind: function(type){
			var dict = {
				changeQQ: {title:"更换绑定QQ", action:shs.site("buyer")+"login_bind/modify/qq"},
				unbind: {title:"解除绑定", action:shs.site("buyer")+"login_bind/unbind/qq"}
			};
			art.dialog({
				title: dict[type].title,
				content: '<div class="t-form-dialog s-fc-n"><form action="'+dict[type].action+'">'
						+    '<p class="tip s-fc-h J_tip">&nbsp;</p>'
                        +    '<dl class="f-cb" style="margin-bottom: 15px;"><dt style="float:left;padding-right: 10px;width:80px;text-align: right;">众划算账号:</dt><dd><input type="text" class="u-ipt J_acc" name="account"></dd></dl>'
                        +    '<dl class="f-cb" style="margin-bottom: 15px;">'
                        +        '<dt style="float:left;padding-right: 10px;width:80px;text-align: right;">登录密码:</dt>'
                        +        '<dd>'
                        +            '<input type="password" class="u-ipt J_pwd" name="password">'
                        +            '<p class="f-tar"><a class="s-fc-a" href="http://ucenter.shikee.com/findpwd" target="_blank" >忘记密码？</a></p>'
                        +        '</dd>'
                        +    '</dl>'
                        +    '<p>为保碍您的账户信息，在更换绑定QQ时需要进行身份验证，感谢您的理解和支持。</p>'
                        +'</form></div>',
				lock: true,
				cancel: true,
				ok: function(){
					this.DOM.content.find("form").submit();
					return false;
				},
				init: function(){
					var
						$form    = this.DOM.content.find("form"),
						$account = $form.find(".J_acc"),
						$password= $form.find(".J_pwd"),
						$tip     = $form.find(".J_tip"),
						dialog   = this,
						lock     = false
					;
					$form.submit(function(){
						if(lock){
							return false;
						}
						lock = true;
						$tip.html("&nbsp;").hide();
						var account = $.trim($account.val());
						var password= $.trim($password.val());
						if(account==''){
							$tip.html("请输入账号").show();
							lock = false;
							return false;
						}
						if(password==''){
							$tip.html("请输入密码").show();
							lock = false;
							return false;
						}
						$.ajax({
							url : $form.attr("action"),
							data: {account:account,password:password},
							dataType:'JSON',
							type:'POST',
							complate: function(){
								lock = false;
							},
							success: function(ret){
								if (ret.state) {
									if (type == 'changeQQ') {
										dialog.DOM.content.html(
											'<div style="line-height:30px;">'
											+ 	'<p class="s-fn-n">正在前往授权页面...</p>'
											+ '</div>'
										);
										dialog.DOM.buttons.hide();
										window.location.href = shs.site("login") + "api/event/qq/updatebind";
									}else{
										dialog.close();
										art.dialog({
											title   : "解除绑定QQ",
											icon    : "succeed",
											lock	: true,
											content : '<div style="line-height:30px;">'
													+ 	'<p class="s-fn-n f-fs2">解除绑定成功</p>'
													+ 	'<p>下次可以使用“<em class="s-fc-h f-fwb">' + account + '</em>”登录众划算</p>'
													+ 	'<p>建议您：<a class="s-fc-a" href="' + shs.site("login") + 'api/event/qq/bind">绑定其它QQ</a></p>'
													+ '</div>',
											ok: true,
											close:function() {
												window.location.reload();
											}
										});
									}
								}else {
									$tip.html(ret.message).show();
								}
							}
						});
						return false;
					});
				}
			});
		}
    };
    // 切换绑定方式
    Frame.Action.change = function(event){
        location.hash = $(this).data("rel");
        Bind.change(location.hash);
    };
    // 发送邮件验证码
    Frame.Action.send_email = function(event){
        Bind.send_email(this);
    };
    // 发送语音验证码
    Frame.Action.send_sound = function(event){
        Bind.send_sound(this);
    };
    // 提交表单
    Frame.Action.bind = function(event){
        Bind.bind(this);
        return false;
    };
    // 解除绑定
    Frame.Action.unbind = function(event){
        Bind.changeOrUnbind("unbind");
        return false;
    };
    // 更换绑定QQ
    Frame.Action.changeQQ = function(event){
        Bind.changeOrUnbind("changeQQ");
        return false;
    };
    // 修改验证码
    Frame.Action.change_captcha = function(event){
        var $img = $(".J_captcha");
        $img.attr("src", $img.data("src") + "?v=" + Math.random());
    };
    // 根据哈希显示表单
    // 例：  http://xxx.xxx.com/#!/email
    // hash值为 #!/email 的url将会显示email绑定表单
    Bind.change(location.hash);
    $(window).on("hashchange",function(){
        Bind.change(location.hash);
    });
});
