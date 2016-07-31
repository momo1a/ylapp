$(document).ready(function(){
	/*————————————————————帮助中心前端————————————————————*/

	/*——————意见反馈(提提意见)——————*/
	/*验证码切换*/
	$(".feedback .vcode").bind('click', function(){	//给加此类名的对象绑定点击事件
		var url = shs.site('help')+'feedback/get_code';	//定义请求url
		var rand_str = new Date().getTime()+'_'+Math.random();	//定义随机参数防止重复请求
		$(this).attr('src', url+'/'+rand_str);	//改变验证码地址
		$(".feedback .vcode_msg").text('');		//清除错误信息
	});
	
	//输入框验证状态
	var name_flag = false;
	var suggest_flag = false;
	var vcode_flag = false;
	
	//输入框时区焦点验证
	$(".feedback .name").bind('blur', function(){
		var name = $(".feedback input[name='name']").val();	//用户名称
		
		//非空验证
		var str_reg = /.+/;	//规则:非空
		
		var this_span = $(".feedback span[class='name_msg']");
		if(str_reg.test(name)){
			msg_timeout_clear(this_span)	//清除指定对象内的文本
			name_flag = true;
		}else{
			this_span.text('请输入您的称呼');
			name_flag = false;
			return false;
		}
	});
	$(".feedback .suggest").bind('blur', function(){
		var suggest = $(".feedback textarea[name='suggest']").val();	//建议
		
		//非空验证
		var str_reg = /.+/;	//规则:非空
		
		var this_span = $(".feedback span[class='suggest_msg']");
		if(str_reg.test(suggest)){
			msg_timeout_clear(this_span)	//清除指定对象内的文本
			suggest_flag = true;
		}else{
			this_span.text('请输入您的建议');
			suggest_flag = false;
			return false;
		}
	});
	$(".feedback .in_vcode").bind('blur', function(){
		var in_vcode = $(".feedback input[name='in_vcode']").val();	//获取输入框内容
		
		//非空验证
		var str_reg = /.+/;	//规则:非空
		
		var this_span = $(".feedback span[class='vcode_msg']");
		if(str_reg.test(in_vcode)){
			msg_timeout_clear(this_span)	//清除指定对象内的文本
			vcode_flag = true;
		}else{
			this_span.text('请输入验证码');
			vcode_flag = false;
			return false;
		}
	});
	/*提交验证码并发邮件*/
	$(".feedback input[name='submit']").bind('click',function(){	//提交按钮事件绑定
		$(".feedback .name").blur();
		$(".feedback .suggest").blur();
		$(".feedback .in_vcode").blur();
		//验证状态通过则提交
		if( name_flag && suggest_flag && vcode_flag ){
			//获取用户输入信息
			var name = $(".feedback input[name='name']").val();	//用户名称
			var contact = $(".feedback input[name='contact']").val();	//联系方式：QQ|手机|邮箱
			var suggest = $(".feedback textarea[name='suggest']").val();	//建议
			var in_vcode = $(".feedback input[name='in_vcode']").val();	//获取输入框内容
			
			//提交验证
			$.post(shs.site('help')+'feedback/callback_check_code', {'in_vcode':in_vcode, 'name':name, 'contact':contact, 'suggest':suggest}, function(data){
				$(".feedback .vcode_msg").text('');	//清空验证码提示
				// 失败
				if (data.type === 'ERROR') {
					if (data.msg == 'CAPTCHA_ERROR') {
						$(".feedback .vcode_msg").text('验证码错误');
					}else if(data.msg == 'EMAIL_FALSE') {
						/*弹窗插件 artDialog.js*/
						art.dialog({
							icon: 'error',	//图标：成功
							content: '邮件发送失败',	//内容：文字
							time: 5,	//定时：5秒
							ok:true,	//确定：返回ture
							close:function(){	//关闭：触发刷新
								$(".feedback input").val('');
								$(".feedback textarea").val('');
								window.location.reload();
							}
						});
					}
					return false;
				}
				//成功
				/*弹窗插件 artDialog.js*/
				art.dialog({
					icon: 'succeed',	//图标：成功
					content: '提交成功，谢谢您的支持',	//内容：文字
					time: 5,	//定时：5秒
					ok:true,	//确定：返回ture
					close:function(){	//关闭：触发刷新
						$(".feedback input").val('');
						$(".feedback textarea").val('');
						window.location.reload();
					}
				});
			});// End POST
			
		}
		
	});// The End
	/*——————/意见反馈——————*/
	
	
	/*字符数实时统计插件*/
	;(function($){
		$.fn.extend({
	    // 回调函数在字符串长度统计完成后触发，this指向应用该插件的DOM元素，实参是统计得到的字符串长度；
	    sumOfChars: function (options, callback) {    
	        var settings = $.extend({
	            eType: 'input',    // 事件类型  (ps：测试发现'input'事件在IE9下使用退格键删减内容时竟然不能触发！)
	            isByte: false,      // 统计的长度类型, true表示统计字节(一个汉字两个字节)长度; false表示统计字符长度; 
	            maxLength: false   // 限制输入长度，默认不限制
	        }, options || {});
	        // 当调用该插件时实参仅包含回调函数：
	        typeof arguments[0] === 'function' && (callback = options);
	        this.each(function(){
	                var self = $(this),
	                    type = settings.eType;
	                // 'on'是jQuery 1.7+ 才有的方法
	                self.on(type, _handler).triggerHandler(type);
	                type === 'input' && self.on('propertychange', function(){   // IE 8-
	                    // 如果发生改变的属性不是value就退出
	                    if(!window.event || window.event.propertyName !== 'value') return;    
	                    // 避免循环调用
	                    $(this).off('propertychange', arguments.callee);
	                    _handler.apply(this);
	                    $(this).on('propertychange', arguments.callee);
	                }).triggerHandler('propertychange');
	                settings.maxLength && self.on('keypress textInput textinput', function (e) {
	                    if( _count(this.value, settings.isByte) >= settings.maxLength)
	                    	e.preventDefault();
	                });
	        });
	        // 长度统计
	        function _count (str, b) {
			    return b? str.replace(/[^\x00-\xff]/g, "aa").length : str.length;
	        }
	        // 事件处理程序
	        function _handler (e) {
	                var num = _count(this.value, settings.isByte);
	                if( num > settings.maxLength){
	                	while(_count(this.value, settings.isByte)>settings.maxLength){
	                	 this.value = this.value.substr(0,this.value.length-1);
	                	}
	                	num = _count(this.value, settings.isByte);
	                }
	                typeof callback === 'function' && callback.apply(this, [num]);
	        }
	        return this;    // 返回jQuery对象以使其链式操作得以持续
	        }
	    });
	}(jQuery));
	// 字符数实时统计插件使用
	$('.name').sumOfChars({ maxLength: 50 }, function(n){
		$('#name_span').html(n + "/50");
	} );
	$('.contact').filter("input").sumOfChars({ maxLength: 50 }, function(n){
		$('#contact_span').html(n + "/50");
	} );
	$('.suggest').sumOfChars({ maxLength: 600 }, function(n){
		$('#suggest_span').html(n + "/600");
	} );
	
	
});	//End ready

/***
 * 提示语超时清除  
 * @param selector 选择器，提示语所在的位置
 * @returns
 */
function msg_timeout_clear(selector){
	selector.text('');
}

/***
 * 删除左右两端的空格  
 * @param str
 * @returns
 */
function trim(str){ //删除左右两端的空格  
	return str.replace(/(^\s*)|(\s*$)/g, "");
}