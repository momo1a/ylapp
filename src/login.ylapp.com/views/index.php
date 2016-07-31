<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?php echo Template::title();?></title>
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
        #warnning { max-height: 65px; overflow: hidden; padding: 4px 22px 4px; border: 1px solid #FFAD77; color:#f00; word-wrap:break-word;  background: url(<?php echo config_item('domain_static'); ?>images/login/warnning.png) no-repeat 4px 7px #FFFFD0; _background: url(<?php echo config_item('domain_static'); ?>images/login/warnning_png8.png) no-repeat 4px 7px #FFFFD0;}
        #capslock { display:none; max-height: 65px; overflow: hidden; padding: 4px 22px 4px; border: 1px solid #FFAD77; color:#f00; word-wrap:break-word;  background: 4px 7px #FFFFD0; _background: no-repeat 4px 7px #FFFFD0;}

        .headerWrapper{ box-shadow:0 0 10px rgba(0,0,0,.3); z-index: 1000; position: relative;}
        #logo { width:250px; height:45px; overflow: hidden; *height: 0; *padding-top: 45px; *background: url(<?php echo config_item('domain_static'); ?>images/login/logo.png) no-repeat 0 0;}
        #logo:before { content: url(<?php echo config_item('domain_static'); ?>images/login/logo.png);}
        .header { padding-top:50px; overflow: hidden; padding-bottom: 20px; }
        .header .link{ padding-top: 2em; padding-right: 10px;}

        .mainWrapper{ width: 100%; overflow: hidden; background:url('<?php echo  $ad['img'] ?>') no-repeat 50% 0;}
        .main{ position: relative; height: 380px;}
        .gotoExperience { display: block; width: 100%; height: 100%;}
        .loginbar{ position: absolute; top: 20px; right: 10px; width: 250px; padding: 25px; color:#fff; background:rgba(0,0,0,0.4);filter:progid:DXImageTransform.Microsoft.Gradient(GradientType=0,StartColorStr='#66000000',EndColorStr='#66000000');}
        .loginbar a { text-decoration: none; color: rgb(226,226,226);}
        .loginbar a:hover { color: #fff;text-decoration: underline;}        
        .loginbar li{ margin-bottom: 10px; }
        .loginbar label{  line-height: 25px; cursor: pointer;}
        .input-checkbox{ vertical-align: middle;}
        #password, #username { padding: 6px 5px; width: 238px; border: 1px solid #E4E4E4; outline: none;}
        #password:focus, #username:focus {border-color:#FF818E; box-shadow: 0 0 4px rgba(204,0,0,.6);}
        .submit {display: block;height: 35px;line-height: 35px;width: 100%;font-size: 18px;background-size: cover;color: #f9f9f9;cursor: pointer;text-align: center;font-weight: normal;font-weight:bold\0;*font-weight:bold; border: 0;
            text-shadow: 1px 1px 0 rgba(0,0,0,0.2);
            background-color: #E22627;
            background-image: -webkit-gradient(linear,left top,left bottom,from(#F23C3D),to(#D51415));
            background-image: -moz-linear-gradient(top,#F23C3D,#D51415);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#F23C3D',endColorstr='#D51415');
            background-image: -o-linear-gradient(top,#F23C3D,#D51415);
            background-image: -ms-linear-gradient(top,#F23C3D 0,#D51415 100%);
            background-image: linear-gradient(top,#F23C3D,#D51415);
        }
        .submit:hover{
            background-color:#F54040;
            background-image:-webkit-gradient(linear,left top,left bottom,from(#F54040),to(#E22627));
            background-image:-moz-linear-gradient(top,#F54040,#E22627);
            filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#F54040',endColorstr='#E22627');
            background-image:-o-linear-gradient(top,#F54040,#E22627);
            background-image:-ms-linear-gradient(top,#F54040 0,#E22627 100%);
            background-image:linear-gradient(top,#F54040,#E22627);
        }
        .disabled, .disabled:hover { 
            text-shadow: 1px 1px 0 #fff; 
            color:#a0a0a0;
            background: #ccc; 
            filter:progid:DXImageTransform.Microsoft.gradient(enabled=false); 
            cursor: default;
        }

        #link2index{ display: block; width: 101px; position: absolute; left:5px; top: 148px; height: 24px; background-image: url(<?php echo config_item('domain_static'); ?>images/login/link2index.png); }

        .footer{ text-align: center; padding-top: 35px; background-color: #fafafa;}
        .footer a,.footer span{ margin-right: 10px;}
    </style>
    <link rel="shortcut icon" type="image/ico" href="<?php echo config_item('domain_static'); ?>images/favicon.ico">
    <?php change_to_minify("javascript/common/jquery/jquery-1.9.1.min.js"); ?>

    <script type="text/javascript">
    function __login() {
        this.objUserName	= null;
        this.objPassword	= null;
    	this.username		= '';
		this.username_pla	= '';
		this.password		= '';
		this.objMessage		= '';

		this.btn			= null;
		
		this.checkUrl		= '<?php echo site_url('tologin');?>';
		this.checkDataType	= 'json';

		this.ini();
	}
	
    __login.prototype = {
		ini: function() {
			this.objUserName	= $('#username');
    		this.objPassword	= $('#password');
			this.btn			= $('#submit');
			this.objMessage		= $('#warnning');

			if (this.objUserName.val().length != 0) {
				this.objPassword.focus();
			}

			// 大小写锁定提示
			this.capsLock();
			
			// IE10之下input没有placeholder功能
			this.placeholder();
		},
		showMessage: function(message, show) {
			var message = message || '';
			var show	= show === false ? show : true;

			if(this.objMessage.length == 0){
				this.objMessage = $("<li id='warnning'></li>");
				this.objMessage.prependTo(".loginForm ul").text(message);
			}else{
				this.objMessage.text(message);
			}
			
			if(show) this.objMessage.show();
			else this.objMessage.hide();
		},
		success: function(jsonData) {
			if (jsonData.state == 'SUCCESS') {
				// 登录成功直接跳转
				window.location.href = jsonData.url;
			}else if(jsonData.state == 'ISOLD' || jsonData.state == 'NO_ACTIVATE') {
				this.showMessage(jsonData.message);
				this.jump(jsonData.url);
			}else if (jsonData.state == 'WRONG_SIMPLE_PASSWORD'){
				this.showMessage('密码过于简单，请立即修改');
				this.jump("http://login.shikee.com/home/weak_password");
			}else {
				this.objPassword.val(''); // 默认都会设置密码文本框为空
				if (jsonData.state == 'NOT_FOUND') {
					this.objUserName.val('').focus(); // 给文本框焦点
				}else if (jsonData.state == 'WRONG_PASSWORD') {
					this.objPassword.focus(); // 给文本框焦点
				}
				this.showMessage(jsonData.message);
				this.resumeButton();
			}
		},
		error: function() {
			this.showMessage('服务器错误');
			this.resumeButton();
		},
		verification: function() {
			this.username		= $.trim(this.objUserName.val());
			this.username_pla	= $.trim(this.objUserName.attr('placeholder'));
			this.password		= $.trim(this.objPassword.val());
			
			if (this.username.length == 0 || this.username === this.username_pla) {
				this.objUserName.val('').focus(); // 给文本框焦点并设置为空
				this.showMessage('请输入账号');
				return false;
			}
			
			if (this.password.length == 0) {
				this.objPassword.val('').focus(); // 给文本框焦点并设置为空
				this.showMessage('请输入密码');
				return false;
			}
			
			return true;
		},
		post: function() {
			if( this.objMessage.length != 0 ) {
				this.showMessage('', false);
			};/*隐藏错误提示框*/
			var _this = this;
			
			if (this.verification()) {
				// 禁用按钮
				this.btn.addClass('disabled').val('登录中...').prop('disabled', true);
				var busy = setTimeout(function() {
					this.showMessage("服务器繁忙，请耐心等待...");
				}, 3000);
				
				$.ajax({
					url: this.checkUrl,
					data: $("form").serialize(),
					dataType: this.checkDataType,
					cache: false,
					type: 'POST',
					success: function(jsonData){_this.success(jsonData);},
					statusCode: {
						500: function() {
							_this.showMessage("服务器错误");
							_this.objPassword.val('').focus();
						},
						200: function() {
							clearInterval(busy);
						}
					},
					error: function(){_this.error();}
				});
			}
		},
		jump: function(url) {
			var num = 3, btn = this.btn, txt = '秒后自动跳转到相应地址...';
			
			btn.val(num + txt).attr('url', url);

            num -= 1;
            
            var countDown = setInterval(function() {
                if(num){
                	btn.val(num + txt);
                    num -= 1;
                }else{
                    countDown && clearInterval(countDown);
                    document.location.href = btn.attr('url');
                }
            }, 1000);
		},
		placeholder: function() {
			if( !("placeholder" in document.createElement("input")) ) {
				// 使用JQ获取，this.getAttribute在低级的IE下不起效果
                
				$("input[placeholder]").not("input[type=password]").focus(function() { 
	                if( this.value === $(this).attr("placeholder") ){
	                    this.value = "";
	                    this.style.color = "#000";
	                }
	            }).blur(function() {
	                if( this.value === "" ){
	                    this.value = $(this).attr("placeholder");
	                    this.style.color = "#a9a9a9";
	                }               
	            }).blur();
	        }
		},
		capsLock: function() {
			this.objPassword[0].onkeypress = function(event){
				var e = event||window.event,
				$tip = $('#capslock'),
				kc  =  e.keyCode || e.which, // 按键的keyCode
				isShift  =  e.shiftKey ||(kc  ==   16 ) || false ; // shift键是否按住
				if (((kc >=65&&kc<=90)&&!isShift)|| ((kc >=97&&kc<=122)&&isShift)){
					$tip.show();
				}
				else{
					$tip.hide();
				}
			};
		},
		resumeButton: function() {
			this.btn.removeClass('disabled').prop('disabled', false).val('登 录');
		}
	}
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
        <div class="w980 main">
            <a title="立即体验" class="gotoExperience" href="<?php echo $ad['link'];?>" target="_blank"></a>
            <div class="loginbar">
                <form class="loginForm" action="<?php echo site_url('tologin');?>?login=local" method="post">
                	<input type="hidden" name="errorMsg" value="<?php echo isset($result) ? $result : ''; ?>" />
                	<input type="hidden" name="to" value="<?php echo $to_url;?>" />
                	<?php if (isset($token)):?><input type="hidden" name="token" value="<?php echo $token;?>" /><?php endif;?>
                    <ul>
                        <?php if(isset($err_msg)):echo $err_msg;endif;?>
                        <li>
                            <label for="username" class="fz14">账号：</label>
                            <input type="text" id="username" autocomplete="off" tabindex="1" name="account" value="<?php echo $account ; ?>" placeholder="会员名/手机号码/邮箱" />
                        </li>
                        <li>
                            <label for="password" class="fz14">密码：</label>
                            <input type="password" autocomplete="off" id="password" tabindex="2" name="password" />
                        </li>
                        <li id="capslock">键盘大写锁定已打开，请注意大小写</li>
                        <li class="clearfix">
                            <label class="pull-left">
                                <input type="checkbox"  name="remember" value="1" checked="checked" tabindex="3" class="input-checkbox">
                                <span>记住登录名</span>
                            </label>
                            <a class="pull-right" href="<?php echo config_item('domain_shikee_usercenter') ;?>findpwd/"  target="_blank">忘记密码？</a>
                        </li>
                        <li>
                            <input id="submit" class="submit" type="submit" value="登  录" />
                        </li>
                        <li>
                            <p class="pull-left">
                            	<a href="<?php echo config_item('url_qq_login'); ?>"><img style="vertical-align: text-top;" src="<?php echo config_item('domain_static'); ?>images/www/qq.png" alt="QQ登录">QQ登录</a>
                            </p>
                            <p class="pull-right"><a href="<?php echo config_item('url_reg'); ?>" target="_blank">免费注册</a></p>
                        </li>
                    </ul>
                </form>
                <script type="text/javascript">
				
				var login = new __login();
                $("#submit").click(function() {
                	login.post();
					return false;
                });
                
    			$("#submit").prop("disabled", false);/*for firefox bug*/
               
                </script>
            </div>
        </div>
        

    </div>
    <div class="footer">
        <a href="<?php echo config_item('url_help'); ?>" target="_blank">帮助</a>
        <a href="http://www.zhonghuasuan.com/guide" target="_blank">新手引导</a>
        <a href="http://help.zhonghuasuan.com/feedback" target="_blank">意见反馈</a>
        Copyright © 2006-<?php echo date('Y');?>
        <a href="http://www.zhonghuasuan.com/"  target="_blank">zhonghuasuan.com</a>
        <a href="<?php echo config_item('legal_url');?>" target="_blank">法律声明</a>
        <span>版权所有</span>
        <a href="http://www.miibeian.gov.cn/" target="_blank">桂ICP备07009935号</a>
		<script src="http://s15.cnzz.com/stat.php?id=905537&web_id=905537" language="JavaScript"></script>
		<div style="display:none;"><script src="http://s96.cnzz.com/stat.php?id=5713574&web_id=5713574&show=pic" language="JavaScript"></script></div>
       	<script>
        !function(){
            var r = document.createElement("script");
            r.setAttribute("type","text/javascript");
            r.setAttribute("src","<?php echo $this->config->item('domain_static').PACK_JS_REFERRER; ?>");
            document.body.appendChild(r);
        }();
        </script>
    </div>
</body>
</html>
