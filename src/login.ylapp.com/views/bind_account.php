<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?php echo Template::title();?></title>
    <meta http-equiv="pragma" content="no-cache"/>
	<meta http-equiv="Cache-Control" content="no-store, must-revalidate"/>
	<meta http-equiv="expires" content="Wed, 26 Feb 1997 08:21:57 GMT"/>
	<meta http-equiv="expires" content="0">
    <style>
        body, dl, dt, dd, ul, li, input, img, h1, form { margin: 0; padding: 0; }
        img { border: none; }
        body { font: 12px/1.5 tahoma,\5b8b\4f53,arial; color: #999; background-color: #F8F8F8; }
        ul,ol{ list-style: none;}
        a { color: #999; text-decoration: none; }
        a:hover { color: #BD0A01; text-decoration: underline; }
        .w980{ width: 980px; margin: 0 auto;}
        .pull-left{ float: left;}
        .pull-right{ float: right;}
        .clearfix:after { content: '\20'; display: block; height: 0; clear: both; }
        .clearfix{ *zoom: 1; }
        .fz14{font-size: 14px;}

        input{vertical-align: middle;}

        .headerWrapper{ box-shadow:0 0 10px rgba(0,0,0,.3); z-index: 1000; position: relative;}
        #logo { width:250px; height:45px; overflow: hidden; *height: 0; *padding-top: 45px; *background: url(<?php echo config_item('domain_static'); ?>images/login/logo.png) no-repeat 0 0;}
        #logo:before { content: url(<?php echo config_item('domain_static'); ?>images/login/logo.png);}
        .header { padding-top:50px; overflow: hidden; padding-bottom: 20px; }
        .header .link{ padding-top: 2em; padding-right: 10px;}

        .mainWrapper{ width: 696px; overflow: hidden; margin: 0 auto;}

        .warnning { max-height: 65px; overflow: hidden; padding: 4px 22px 4px; border: 1px solid #FFAD77; color:#f00; word-wrap:break-word;  background: url(<?php echo config_item('domain_static'); ?>images/login/warnning.png) no-repeat 4px 7px #FFFFD0; _background: url(<?php echo config_item('domain_static'); ?>images/login/warnning_png8.png) no-repeat 4px 7px #FFFFD0;margin: 10px;display: none;}
        .login-face{cursor:pointer;height:120px;outline:medium none;position:relative;text-align:center;width:125px;}
        .login-face em{color:#505050;display:inline-block;font-size:14px;font-style:normal;height:20px;left:0;line-height:18px;position:absolute;text-align:center;top:0px;vertical-align:middle;width:100%;}
        .login-face img{height:78px;left:22px;position:absolute;top:35px;width:78px;border: 1px solid #E6E6E6; border-radius:3px;padding:2px;}
        .login-face .nick{color:#6F7479;display:inline-block;font-family:Tahoma,Verdana,Arial,sans-serif;height:50px;left:0;line-height:18px;overflow:hidden;position:absolute;text-align:center;top:125px;vertical-align:middle;width:100%;word-wrap:break-word;word-break:break-all;}
        .login-tip { width: 696px; font-size: 14px;font-weight:normal; margin-top: 55px;}
		.login-tip span{ color: #B7B7B7; font-weight: bold;}

		.login-left {width: 260px;height: 147px;margin-top: 60px;position: relative;background: url(<?php echo config_item('domain_static'); ?>images/login/arrow2.png) no-repeat; background-position: 185px 65px;float: left;
		}
		.login-right{float: right; width: 394px; margin-top: 47px;}
		.login-right table{display:none;width: 100%;}
		.login-right table tr td{ height: 38px;color: #969696; font-size: 14px;}
		.login-right table tr th em{color: #EB5353;}
		.login-right table tr td input {width: 180px; }
		.login-right table tr th{ text-align: right;padding-left: 5px; width: 80px;font-weight: normal;}
		.login-right .free-send{ border:1px solid #B0D7FC; width: 100px; height:23px;padding-bottom: 2px;margin-right:10px; color: #969696;font-size: 12px;border-radius: 3px; outline:none;border-color:#B0D7FC;box-shadow:0 0 8px #B0D7FC;-moz-box-shadow:0 0 8px #B0D7FC;-webkit-box-shadow:0 0 8px #B0D7FC;background-color: #fff;cursor: pointer;}
		.login-right .ui-form-button{ width: 74px; height: 32px; margin: 10px 0 23px 0;} 
		.login-right #quick-Register{ width: 20px;margin-left: 5px;} 
		.login-right p{color: #7B7B7B; width: 369px;height: 25px;font-size: 12px; }    
		.login-right p a{color: #D1541E;font-size: 12px; } 

        .container{padding: 5px;border:1px solid #F8F8F8;}
        .container-hasBorder{border:1px solid #E6E6E6;}
        .container-hasBorder table{display:block;}

        .radio-before{ width: 396px;height: 50px; float: right; font-size: 14px;color: #4E4E4E;}

		.footer{ text-align: center; padding-top: 35px; background-color: #fafafa;}
		.footer a,.footer span{ margin-right: 10px;} 


        input.ui-form-text:focus { outline: none; }
        .ui-form-text { min-width: 1em; height: 18px; line-height: 16px; padding: 5px; font-size: 12px; border:1px solid #d3d3d3;}
        .ui-form-textRed:focus { border-color:#FF818E; box-shadow: 0 0 3px rgba(204,0,0,.6); }
        .ui-form-button { padding: .35em 1.35em; font-size: 14px;  border-width: 0; border-radius: .3em; cursor: pointer; *overflow: visible;}
        .ui-form-buttonRed { background-color: #F1273A;  color: #fff; }
        .ui-form-buttonRed:hover { background-color: #BF1C2C;}
        .mobile-captcha {float: left;height:30px;}
        .login-right .update-mobile-captcha {font-size: 12px;}
        .get-sound-captcha-button {border:1px solid #B0D7FC; height:27px;padding-bottom: 2px;margin-right:16px; color: #969696;font-size: 12px;border-radius: 3px; outline:none;border-color:#B0D7FC;box-shadow:0 0 8px #B0D7FC;-moz-box-shadow:0 0 8px #B0D7FC;-webkit-box-shadow:0 0 8px #B0D7FC;background-color: #fff;cursor: pointer;}
    </style>
	<?php change_to_minify("javascript/common/jquery/jquery-1.9.1.min.js"); ?>
    <script type="text/javascript">
	document.onkeydown = function() {
		if (event.keyCode == 116) {
			alert('当前页面不支持刷新');
			event.keyCode = 0;
			event.cancelBubble = true;
			return false;
		}
    }
	var to_url = '<?php echo $to_url;?>';
    </script>
</head>
<body>

    <div class="headerWrapper">
       <div class="header w980 clearfix">
            <a href="<?php echo config_item('domain_www'); ?>">
                <h1 id="logo" class="pull-left">登陆-众划算</h1>
            </a>
            <span class="link pull-right">
                <a href="<?php echo config_item('domain_www'); ?>" target="_blank">首页</a> - 
                <a href="<?php echo config_item('url_reg'); ?>" target="_blank">注册</a> | 
                <a href="<?php echo config_item('url_help'); ?>" target="_blank">帮助中心</a>
            </span>
        </div> 
    </div>
    
    <div class="mainWrapper">
       <h1 class="login-tip clearfix"><span><?php echo $nickname;?></span>，您好！您已成功登录QQ帐号，现在您只需要绑定一个众划算帐号。</h1>
		<div class="login-left">
            <div class="login-face" >  
            		<em>您已登录了QQ帐号</em>
                    <img src="<?php echo $avatar;?>"> 
                    <span class="nick" id="nick_453555909"><?php echo $nickname;?></span>         
    	    </div>
    	</div>
    	<div class="login-right">
        	<!-- 已有众划算帐号表单 -->
            <div class="container container-hasBorder clearfix">
            	<form action="<?php echo site_url('bind/account')?>" method="post" id="quick-bind" >
                    <label style="color:#4E4E4E;"><input type="radio" data-type="choose" checked="checked" />已有众划算帐号</label>
            	    <div class="warnning"></div>
                    <table cellpadding="0" cellspacing="0" >
        				<tr>
        					<th>帐号：</th>
        					<td><input type="text" class="ui-form-text ui-form-textRed" name="account"></td>
        				</tr>
        				<tr>
        					<th>密码：</th>
        					<td><input type="password" class="ui-form-text ui-form-textRed" name="password"></td>
        				</tr>
        				
        				<tr>
        					<th>&nbsp;</th>
                            <td><input type="submit" value="绑定" class="ui-form-button ui-form-buttonRed"></td>
        				</tr>
            		</table>
            	</form>
            </div>

        	<!-- 快速注册表单 -->
            <div class="container clearfix">
            	<form action="<?php echo site_url('bind/register')?>" method="post" id="quick-reg" >
                    <label style="color:#4E4E4E;"><input type="radio" data-type="choose"/>快速注册</label>
                    <div class="warnning"></div>
            	    <table cellpadding="0" cellspacing="0" >
        				<tr>
        					<th><em>*</em>会员名：</th>
        					<td><input type="text" class="ui-form-text ui-form-textRed" name="username"></td>
        				</tr>
        				<tr>
        					<th><em>*</em>密码：</th>
        					<td><input type="password" class="ui-form-text ui-form-textRed" name="password"></td>
        				</tr>
        				<tr>
        					<th><em>*</em>确认密码：</th>
        					<td><input type="password" class="ui-form-text ui-form-textRed" name="confirm_password"></td>
        				</tr>
        				<tr>
        					<th><em>*</em>邮箱：</th>
        					<td ><input type="text" class="ui-form-text ui-form-textRed" name="email"></td>
        					<td><input class="free-send" type="button" value="免费发送验证码" /></td>
        				</tr>
        				<tr>
        				  	<th><em>*</em>验证码：</th>
        				  	<td><input type="text" class="ui-form-text ui-form-textRed" name="code"></td>
        				</tr>
        				<tr>
        					<th></th>
                            <td><input type="submit" value="绑定" class="ui-form-button ui-form-buttonRed"></td>
        				</tr>
            		</table>
            	</form>
            </div>
            
            <!-- 手机快速注册表单 -->
            <div class="container clearfix">
            	<form action="<?php echo site_url('bind/mobile-register')?>" method="post" id="mobile-register" >
                    <label style="color:#4E4E4E;"><input type="radio" data-type="choose" value="mobile"/>手机快速注册</label>
                    <div class="warnning"></div>
            	    <table cellpadding="0" cellspacing="0" >
        				<tr>
        					<th><em>*</em>会员名：</th>
        					<td><input type="text" class="ui-form-text ui-form-textRed" name="username"></td>
        				</tr>
        				<tr>
        					<th><em>*</em>密码：</th>
        					<td><input type="password" class="ui-form-text ui-form-textRed" name="password"></td>
        				</tr>
        				<tr>
        					<th><em>*</em>确认密码：</th>
        					<td><input type="password" class="ui-form-text ui-form-textRed" name="confirm_password"></td>
        				</tr>
        				<tr>
        					<th><em>*</em>手机号码：</th>
        					<td><input type="text" class="ui-form-text ui-form-textRed" name="mobile"></td>
        				</tr>
        				<tr>
        				  	<th><em>*</em>图形验证码：</th>
        				  	<td><input type="text" class="ui-form-text ui-form-textRed" name="captcha" style="width:80px;float:left;">
        				  		<img data-src="<?php echo site_url('api/captcha');?>" class="mobile-captcha J_mobileCaptcha" title="点击刷新图形验证码">
                                <a href="javascript:;" class="update-mobile-captcha J_upCaptcha">看不清？换一张</a>
        				</tr>
        				<tr>
        				  	<th></th>
        				  	<td><input id="get-sound-captcha" class="get-sound-captcha-button" type="button" value="获取语音验证码"></td>
        				</tr>
        				<tr>
        				  	<th><em>*</em>语音验证码：</th>
        				  	<td>
        				  		<input type="text" class="ui-form-text ui-form-textRed" name="code" style="width:80px;">
        				  	</td>
        				</tr>
                        <tr>
                            <th></th>
                            <td style="font-size:12px;">您将收到来自0771-3186577或021-31234559的来电，免费接听获取验证码，请保持手机畅通！</td>
                        </tr>
        				<tr>
        					<th></th>
                            <td><input type="submit" value="绑定" class="ui-form-button ui-form-buttonRed"></td>
        				</tr>
            		</table>
            	</form>
            </div>

            <div class="radio-before">
                <p>暂时不想注册，<a id="system-bind" href="javascript:;">直接进入众划算</a></p>
            </div>
   		</div>	
    </div>
<script type="text/javascript">
var openuserkey = '<?php echo $openuserkey;?>';

/**
 * 刷新验证码
 */
$(".J_mobileCaptcha,.J_upCaptcha").click(function(){
    $(".J_mobileCaptcha").attr("src", $(".J_mobileCaptcha").data("src")+ '?_' + Math.floor(Math.random()*10e8));
});

/*切换效果*/
$(".container input[data-type=choose]").click(function() {
	$(".warnning").hide();
	$(".container input[data-type=choose]").prop("checked", false);
	$(".container").removeClass("container-hasBorder");
	$(this).prop("checked", true).closest(".container").addClass("container-hasBorder");

	if ($(this).val() == 'mobile') {
		$(".J_mobileCaptcha").click();
	}
}).prop("checked", false).eq(0).prop("checked", true);//此行代码防止浏览器缓存单选框

$("#quick-bind").submit(function(event) {
    var $form = $(this),
		account = $form.find("input[name='account']").eq(0).val(),
		password = $form.find("input[name='password']").eq(0).val();

	if (account.length == 0) {
		$form.find(".warnning").show().html('请输入账号！');
		return false;
	}

	if (password.length == 0) {
		$form.find(".warnning").show().html('请输入密码！');
		return false;
	}
    $form.find(':submit').eq(0).attr('disabled', true);

    $form.find(".warnning").html('').hide();
    
    var data = {account:account,password:password,openuserkey:openuserkey};
    
    $.ajax({
        url: $form.attr('action'),
        data: data,
        type: 'POST',
        dataType: 'JSON',
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            var errorMessage = '服务器响应失败！';
            if (textStatus == 'parsererror') {
            	errorMessage = XMLHttpRequest.responseText;
            }
            $form.find(':submit').eq(0).attr('disabled', false);
            $form.find(".warnning").show().html(errorMessage);
        },
        success: function(ret) {
			if (ret.state) {
				$form.find(':submit').eq(0).val('绑定成功,正在为您跳转！').css({'background-color':'#390','width':'190px'});
				window.location.href = decodeURIComponent(to_url);
			}else if(ret.code == 'WRONG_SIMPLE_PASSWORD'){
                $form.find(':submit').eq(0).val('绑定的帐号密码过于简单，请立即修改').css({'background-color':'#390','width':'190px'});
                window.location.href = 'http://login.shikee.com/home/weak_password';
            }else {
				$form.find(':submit').eq(0).attr('disabled', false);
				$form.find(".warnning").html(ret.message).show();
			}
        }
    });
    
    return false;
});

$("#quick-reg").submit(function(event) {
    var $form = $(this),
    	objUsername	= $form.find("input[name='username']").eq(0),
    	username	= objUsername.val(),
    	objPassword = $form.find("input[name='password']").eq(0),
    	password	= objPassword.val(),
    	objConfirmPassword = $form.find("input[name='confirm_password']").eq(0),
    	confirmPassword = objConfirmPassword.val(),
    	objEmail	= $form.find("input[name='email']").eq(0),
    	email		= objEmail.val(),
    	objCode		= $form.find("input[name='code']").eq(0),
    	code		= objCode.val();

    var uLen = chackUserName(username);
    if(uLen < 6 || uLen > 50) {
		$form.find(".warnning").show().html('用户名为6-50个字符，1个汉字为2个字符，推荐使用中文用户名');
		objUsername.focus();
		return false;
	}else if(/\d{5}/.test(username)) {
		$form.find(".warnning").show().html("用户名中不能包含多个数字，推荐使用中文用户名");
		objUsername.focus();
		return false;
	}else if(!/^[a-z0-9\u4E00-\u9FA5_]+$/i.test(username)) {
		$form.find(".warnning").show().html("用户名支持中英文、数字、下划线，不支持除下划线外的特殊字符");
		objUsername.focus();
		return false;
	}

	if (password.length == 0) {
		$form.find(".warnning").show().html('请填写密码');
		objPassword.focus();
		return false;
	}else if(/^[a-zA-Z]+$/.test(password)) {
		$form.find(".warnning").show().html("密码不能为纯字母");
        objPassword.focus();
		return false;
	}else if(/^\d+$/.test(password)) {
		$form.find(".warnning").show().html("密码不能为纯数字");
    	objPassword.focus();
		return false;
	}if(/^_+$/.test(password)) {
		$form.find(".warnning").show().html("密码不能为纯符号");
        objPassword.focus();
		return false;
	}if(!/^[_0-9a-z]{6,20}$/i.test(password)) {
		$form.find(".warnning").show().html("密码为6-20个字符，请使用字母加数字或下划线组合密码");
        objPassword.focus();
		return false;
	}if(password.toLowerCase().indexOf(username.toLowerCase()) >= 0) {
        $form.find(".warnning").show().html("密码中不能包含用户名");
        objPassword.focus();
        return false;
    }


	if (confirmPassword.length == 0) {
		$form.find(".warnning").show().html('请再次输入密码');
		objConfirmPassword.focus();
		return false;
	}else if (password != confirmPassword){
		$form.find(".warnning").show().html('两次密码输入不一致');
		objConfirmPassword.focus();
		return false;
	}
	
	if (email.length == 0) {
    	$form.find('.warnning').show().html('请填写电子邮箱');
    	objEmail.focus();
    	return false;
    }else if( !isEmail(email)) {
    	$form.find('.warnning').show().html('无效的邮箱');
    	objEmail.focus();
    	return false;
    }
	
	if (code.length == 0) {
		$form.find(".warnning").show().html('请输入邮箱验证码,不区分大小写！');
		objCode.focus();
		return false;
	}else if (code.length < 4 || code.length > 8) {
		$form.find(".warnning").show().html('邮箱验证码为4~8个字符');
		objCode.focus();
		return false;
	}
	
    $form.find(':submit').eq(0).attr('disabled', true);
    $form.find(".warnning").html('').hide();
    
    $.ajax({
        url: $form.attr('action'),
        data: {username:username,password:password,email:email,code:code,openuserkey:openuserkey},
        type: 'POST',
        dataType: 'JSON',
        async: false,
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        	$form.find(':submit').eq(0).attr('disabled', false);
            var errorMessage = '服务器响应失败！';
            if (textStatus == 'parsererror') {
            	errorMessage = XMLHttpRequest.responseText;
            }
            alert(errorMessage);
        },
        success:function(ret) {
			if (ret.state) {
				$form.find(':submit').eq(0).val('绑定成功,正在为您跳转！').css({'background-color':'#390','width':'190px'});
				window.location.href = decodeURIComponent(to_url);
			}else {
				$form.find(':submit').eq(0).attr('disabled', false);
				$form.find(".warnning").html(ret.message).show();
			}
        }
    });
    return false;
}).find(".free-send").click(function() {
    $this = $(this);
    if($this.data("wait")) return;

    $form = $this.closest('form');

    var objEmail = $form.find("input[name='email']").eq(0),
    	email = objEmail.val();
	
    if (email.length == 0) {
    	$form.find('.warnning').show().html('邮箱不能为空！');
    	objEmail.focus();
    	return;
    }else if( !isEmail(email)) {
    	$form.find('.warnning').show().html('无效的邮箱！');
    	objEmail.focus();
    	return;
    }

    // 倒计时
    var cd = function(t, m){
        var timer = setInterval((function cdr() {
            $this.val(m.replace('{s}', t--));
            if(t<0) {
                $this.val('重新发送验证码').data('wait', false);
                clearInterval(timer);
            }
            return cdr;
        })(), 1000);
    };

    $form.find(".warnning").hide().html('');
    $this.val("请稍候..").data("wait", true);
    
    $.ajax({
        url: '<?php echo site_url('api/send_email')?>',
        type: 'POST',
        data: {email:email},
        dataType: 'JSON',
        error:function(){
        	$this.val('重新发送验证码').data('wait', false);
            $form.find(".warnning").show().html('邮件发送失败！');
        },
        success:function(ret){
			if (ret.state) {
	        	$this.val('验证码已发送');
	            /*限制一下频繁操作*/
                cd(60, '验证码已发送({s})');
			}else {
				$form.find('.warnning').show().html(ret.message);
                $this.val('重新发送验证码').data('wait', false);
			}
        }
    });
});
var isPost = false;
$('#system-bind').click(function(){
	$this = $(this);
	if (isPost) {
		alert('正在处理,请稍后...');
		return;
	}
	isPost = true;
	
	$.ajax({
        url: '<?php echo site_url('bind/fast');?>',
        type: 'POST',
        data: {openuserkey:openuserkey},
        dataType: 'JSON',
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            var errorMessage = '服务器响应失败！';
            if (textStatus == 'parsererror') {
            	errorMessage = XMLHttpRequest.responseText;
            }
            isPost = false; // 开放提交
            alert(errorMessage);
        },
        success: function(ret) {
			if (ret.state) {
				$('#system-bind').html('已自动生成账号,正在为您跳转！').css({'background-color':'#390',height:'100px',color:'#fff'});
				var _to_url = decodeURIComponent(to_url);
				window.location.href = _to_url;
			}else {
				isPost = false; // 开放提交
				alert(ret.message);
			}
        }
    });
});

/*-----------------手机注册----------------------*/


$("#mobile-register").submit(function(event) {
    var $form = $(this),
    	objUsername	= $form.find("input[name='username']").eq(0),
    	username	= objUsername.val(),
    	objPassword = $form.find("input[name='password']").eq(0),
    	password	= objPassword.val(),
    	objConfirmPassword = $form.find("input[name='confirm_password']").eq(0),
    	confirmPassword = objConfirmPassword.val(),
    	objMobile	= $form.find("input[name='mobile']").eq(0),
    	mobile		= objMobile.val(),
    	objCode		= $form.find("input[name='code']").eq(0),
    	code		= objCode.val();

    var uLen = chackUserName(username);
    if(uLen < 6 || uLen > 50) {
		$form.find(".warnning").show().html('用户名为6-50个字符，1个汉字为2个字符，推荐使用中文用户名');
		objUsername.focus();
		return false;
	}else if(/\d{5}/.test(username)) {
		$form.find(".warnning").show().html("用户名中不能包含多个数字，推荐使用中文用户名");
		objUsername.focus();
		return false;
	}else if(!/^[a-z0-9\u4E00-\u9FA5_]+$/i.test(username)) {
		$form.find(".warnning").show().html("用户名支持中英文、数字、下划线，不支持除下划线外的特殊字符");
		objUsername.focus();
		return false;
	}

	if (password.length == 0) {
		$form.find(".warnning").show().html('请填写密码');
		objPassword.focus();
		return false;
	}else if(/^[a-zA-Z]+$/.test(password)) {
		$form.find(".warnning").show().html("密码不能为纯字母");
        objPassword.focus();
		return false;
	}else if(/^\d+$/.test(password)) {
		$form.find(".warnning").show().html("密码不能为纯数字");
    	objPassword.focus();
		return false;
	}if(/^_+$/.test(password)) {
		$form.find(".warnning").show().html("密码不能为纯符号");
        objPassword.focus();
		return false;
	}if(!/^[_0-9a-z]{6,20}$/i.test(password)) {
		$form.find(".warnning").show().html("密码为6-20个字符，请使用字母加数字或下划线组合密码");
        objPassword.focus();
		return false;
	}if(password.toLowerCase().indexOf(username.toLowerCase()) >= 0) {
        $form.find(".warnning").show().html("密码中不能包含用户名");
        objPassword.focus();
        return false;
    }


	if (confirmPassword.length == 0) {
		$form.find(".warnning").show().html('请再次输入密码');
		objConfirmPassword.focus();
		return false;
	}else if (password != confirmPassword){
		$form.find(".warnning").show().html('两次密码输入不一致');
		objConfirmPassword.focus();
		return false;
	}
	
	if (mobile.length == 0) {
    	$form.find('.warnning').show().html('请填写手机号码');
    	objMobile.focus();
    	return false;
    }else if( !isMobile(mobile)) {
    	$form.find('.warnning').show().html('手机号码格式不正确，请重新输入');
    	objMobile.focus();
    	return false;
    }
	
	if (code.length == 0) {
		$form.find(".warnning").show().html('请输入语言验证码');
		objCode.focus();
		return false;
	}else if (code.length != 4) {
		$form.find(".warnning").show().html('语言验证码为4个字符');
		objCode.focus();
		return false;
	}
	
    $form.find(':submit').eq(0).attr('disabled', true);
    $form.find(".warnning").html('').hide();
    
    $.ajax({
        url: $form.attr('action'),
        data: {username:username,password:password,mobile:mobile,code:code,openuserkey:openuserkey},
        type: 'POST',
        dataType: 'JSON',
        async: false,
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        	$form.find(':submit').eq(0).attr('disabled', false);
            var errorMessage = '服务器响应失败！';
            if (textStatus == 'parsererror') {
            	errorMessage = XMLHttpRequest.responseText;
            }
            alert(errorMessage);
        },
        success:function(ret) {
			if (ret.state) {
				$form.find(':submit').eq(0).val('绑定成功,正在为您跳转！').css({'background-color':'#390','width':'190px'});
				window.location.href = decodeURIComponent(to_url);
			}else {
				$form.find(':submit').eq(0).attr('disabled', false);
				$form.find(".warnning").html(ret.message).show();
			}
        }
    });
    return false;
}).find('#get-sound-captcha').click(function(){
	var $this = $(this);
	
	if($this.data("wait")) return;

	var $form = $this.closest('form'),
		objCaptcha = $form.find("input[name='captcha']").eq(0),
		captcha = objCaptcha.val(),
		objMobile = $form.find("input[name='mobile']").eq(0),
		mobile = objMobile.val();

	if (mobile.length == 0) {
		$form.find('.warnning').show().html('手机号码不能为空！');
		objMobile.focus();
		return;
	}else if( !isMobile(mobile)) {
		$form.find('.warnning').show().html('手机号码格式不正确，请重新输入！');
		objMobile.focus();
		return;
	}

	// 图片验证码为空判断
	if (captcha.length == 0) {
		$form.find('.warnning').show().html('请输入图形验证码！');
		objCaptcha.focus();
		return;
	}
    // 倒计时
    var cd = function(t, m){
        var timer = setInterval((function cdr() {
            $this.val(m.replace('{s}', t--));
            if(t<0) {
                $this.val('重新发送语音验证码').data('wait', false);
                clearInterval(timer);
            }
            return cdr;
        })(), 1000);
    };

	$this.val("请稍候..").data("wait", true);
	$form.find(".warnning").html('').hide();
    
    $.ajax({
        url: '<?php echo site_url('bind/send-sound-captcha')?>',
        type: 'POST',
        data: {captcha:captcha,mobile:mobile,openuserkey:openuserkey},
        dataType: 'JSON',
        error:function(){
        	$this.val('重新发送语音验证码').data('wait', false);
            $form.find(".warnning").show().html('语音验证码发送失败！');
        },
        success:function(ret){
			if (ret.state) {
	        	$this.val('语音验证码已发送');
                /*限制一下频繁操作*/
                cd(60, '语音验证码已发送({s})');
			}else {
                $form.find('.warnning').show().html(ret.message);
				if (ret.code == 'IMGCAPTCHA_ERROR' || ret.code == 'SEND_NUM_EXCEED') {
					$(".J_mobileCaptcha").attr("src", $(".J_mobileCaptcha").data("src")+ '?_' + Math.floor(Math.random()*10e8)); // 刷新验证码
					objCaptcha.val('');
                    if(ret.code=="SEND_NUM_EXCEED"){
                        cd(Number(ret.data.SURPLUS_TIME), '请稍候({s})秒');
                    }else{
                        $this.val('重新发送语音验证码').data('wait', false);
                    }
				}else{
    				$this.val('重新发送语音验证码').data('wait', false);
                }
			}
        }
    });
});

function isEmail(email) { 
	var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
	return reg.test(email);
}

function isMobile(mobile) {
	return /^1[34578]\d{9}$/.test(mobile);
}

function chackUserName(strTemp){
    var i, sum;
    sum = 0;
    for (i = 0; i < strTemp.length; i++) {
        if ((strTemp.charCodeAt(i) >= 0) && (strTemp.charCodeAt(i) <= 255))
            sum = sum + 1;
        else
            sum = sum + 2;
    }
    return sum;
}
</script>
    <div class="footer">
        <a href="<?php echo config_item('url_help'); ?>" target="_blank">帮助</a>
        <a href="http://help.shikee.com//guize/shikexinshoushanglu/" target="_blank">新手向导</a>
        <a href="http://bbs.shikee.com/forum.php?mod=forumdisplay&amp;fid=96" target="_blank">意见反馈</a> 
        Copyright © 2006-<?php echo date('Y');?>
        <a href="http://www.zhonghuasuan.com/"  target="_blank">zhonghuasuan.com</a> 
        <a href="<?php echo config_item('legal_url');?>" target="_blank">法律声明</a>
        <span>版权所有</span>
        <a href="http://www.miibeian.gov.cn/" target="_blank">桂ICP备07009935号</a>
        <script src="http://s15.cnzz.com/stat.php?id=905537&web_id=905537" language="JavaScript"></script>
        <div style="display:none;"><script src="http://s96.cnzz.com/stat.php?id=5713574&web_id=5713574&show=pic" language="JavaScript"></script></div>
    </div>
</body>
</html>