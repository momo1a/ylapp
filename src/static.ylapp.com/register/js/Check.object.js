var Check = {
	_lengthAt : function(strTemp){
	    var i, sum;
	    sum = 0;
	    for (i = 0; i < strTemp.length; i++) {
	        if ((strTemp.charCodeAt(i) >= 0) && (strTemp.charCodeAt(i) <= 255))
	            sum = sum + 1;
	        else
	            sum = sum + 2;
	    }
	    return sum
	},
	getMsg : function(s){
		var d={
			NAME_LENGTH_OUT      : "6-50个字符，1个汉字为2个字符。推荐使用中文用户名。",
			NAME_MORE_NUMBER     : "用户名中不能包含多个数字，推荐使用中文用户名。",
			NAME_ALL_NUM         : "不能为纯数字，推荐使用中文用户名。",
			NAME_CONTAIN_SPECIAL : "用户名支持中英文、数字、下划线，不支持除下划线的特殊字符。",
			NAME_ALL_UNDERLINE   : "用户名不能全为下划线。",
			NAME_EXIST_ALREADY   : "您注册的用户名已经被注册，请重新更换新的用户名。",
			NAME_FORBID_USE      : "您输入的用户名不允许使用，请重新输入",

			EMAIL_MUST_LONG		 : "您的电子邮箱过长了请换另一个,只能在100个字符以内。",
			EMAIL_FORMAT_WRONG	 : "您填写的不是一个有效的电子邮件地址，请输入一个有效的电子邮件地址。",
			EMAIL_EXIST_ALREADY  : "邮箱已经认证过，请更换邮箱重新认证。",
			EMAIL_SEND_FAIL      : "系统错误，邮件发送失败！",
			EMAIL_ACTIVE_ALREADY : "该用户邮箱已经激活成功！",

			MOBILE_FORMAT_WRONG	 : "您填写的不是一个有效的手机号码，请输入一个有效的手机号码。",
			MOBILE_EXIST_ALREADY : "该手机已经认证过，请更换手机重新认证。",
			MOBILE_SEND_FAIL     : "系统错误，手机发送失败！",

			PWD_IS_NULL 		 : "请设置密码。",
			PWD_FORMAT_WRONG	 : "密码为6-20个字符，请使用字母加数字或下划线组合密码。",
			PWD_ALL_LETTER		 : "密码不能为纯字母。",
			PWD_ALL_NUMBER		 : "密码不能为纯数字。",
			PWD_ALL_SYMBOL		 : "密码不能为纯符号。",
			PWD_NOT_SAME         : "您输入的密码不一致，请重新输入。",
			// PWD_ALINK_FOURSTRING : "密码不能包含4位及4位以上的重复数字或字母",
			// PWD_LINK_STRING		 : "密码不能包含4位及4位以上连续数字或字母",
			PWD_NOT_NAME         : "密码中不能包含用户名",
			
			ACTIVE_CODE_NULL	 : "激活码不能为空。",
			ACTIVE_CODE_WRONG	 : "激活码不正确。",
			ACTIVE_TYPE_WRONG    : "激活方式错误！",
			
			CAPTCHA_IS_NULL      : "请输入验证码,不区分大小写！",
			CAPTCHA_IS_WRONG     : "您的证验码填写不正确！",
			
			USER_TYPE_WRONG      : "注册的用户类型不对！",
			USER_STATUE_WRONG    : "激活帐号不存在或者不能激活！",
			USER_ACTIVE_ALREADY  : "该用户已经激活成功！",
			ACCOUNT_NOT_SAME     : "填写的激活帐号和已经发送的激活帐号不一致！",
			SEND_TIMES_OUT       : "获取激活码过于频繁，请稍后再试！",
			SYS_RUM_WRONG        : "系统错误，注册失败！",
			HLPAY_EMAIL_EXIST_ALREADY : "该邮箱已在互联支付使用，请更换其他的邮箱。",
			HLPAY_MOBILE_EXIST_ALREADY : "该手机号已在互联支付使用，请更换其他的手机号。",
			NET_ERROR			 : "网络连接失败！"
		};
		return d[s];
	}
};
//ajax请求验证
Check.ajax = function(data,callback){
	$.ajax({
	    url: '/reg/check/',
	    type: "post",
	    data:data,
	    dataType: "json",
	    error: function(){callback("NET_ERROR")},
	    success: function(data) {
	    	if(!data.success)
	    		callback(data.data.errcode);
	    	else
	    		callback(null);
	    }
	});
};
//用户名
Check.name = function(str,callback){
	if(this._lengthAt(str)<6 || this._lengthAt(str)>50)
		return callback("NAME_LENGTH_OUT");
	if(/\d{5}/.test(str))
		return callback("NAME_MORE_NUMBER");
	this.ajax({'username':str,'method':'check_user'}, callback);
};
//手机号
Check.mobile=function(str,callback){
	if(!/^1\d{10}$/.test(str))
		return callback("MOBILE_FORMAT_WRONG");
	this.ajax({'account':str,'method':'check_mobile'}, callback);
};
//邮箱
Check.email = function(str,callback){
	if(str.length>100)
		return callback("EMAIL_MUST_LONG");
	if(!/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(str))
		return callback("EMAIL_FORMAT_WRONG");
	this.ajax({'account':str,'method':'check_email'}, callback);
};
//密码
Check.password = function(str,callback){
	str = str.toLowerCase();
    if(!str)
        return callback("PWD_IS_NULL");
    if(/^[a-z]+$/.test(str))
        return callback("PWD_ALL_LETTER");
    if(/^\d+$/.test(str)) 
    	return callback("PWD_ALL_NUMBER");
    if(/^_+$/.test(str))
        return callback("PWD_ALL_SYMBOL");
    if(!/^[_0-9a-z]{6,20}$/.test(str))
        return callback("PWD_FORMAT_WRONG");
/*    // 不允许4个连续递增的数字或字母
    var arr = "0123|1234|2345|3456|4567|5678|6789|abcd|bcde|cdef|defg|efgh|fghi|ghij|hijk|ijkl|jklm|klmn|lmno|mnop|nopq|opqr|pqrs|qrst|rstu|stuv|tuvw|uvwx|vwxy|wxyz".split("|");
	for(var i=0;i<30;i++){
		if(str.indexOf(arr[i])>-1)
			return callback("PWD_LINK_STRING");
	}
    // 不允许4个连续相同数字或字母
    if(/([a-z]|\d)\1{3}/.test(str))
        return callback("PWD_ALINK_FOURSTRING");*/
    callback(null);
};
//验证码验证函数
Check.captcha = function(str,callback){
	if(!str)
		return callback("CAPTCHA_IS_NULL");
    if(str.length<4)
    	return callback("CAPTCHA_IS_WRONG");
    this.ajax({'valiCode':str,'method':'check_vcode'}, callback);
};

//激活码
Check.active_code = function(data,callback){
	if(!data.active_code)
		return callback("ACTIVE_CODE_NULL");
    if(data.active_code.length < 4 || data.active_code.length > 8)
    	return callback("ACTIVE_CODE_WRONG");
    this.ajax(data, callback);
};
