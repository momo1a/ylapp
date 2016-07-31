<?php
// 这个是针对show_message()
if (!isset($uid))
{
	// 重构0.2兼容写法,在重构0.2版本将取出get_user方法，使用User代替
	if (function_exists('get_user'))
	{
		$user = get_user();
		$uid = $user['id'];
		$uname = $user['name'];
		$utype = $user['type'];
	}
	elseif (class_exists('AuthUser', FALSE))
	{
		$uid = AuthUser::id();
		$uname = AuthUser::account();
		$utype = AuthUser::type();
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php echo $title; ?></title>
		<link rel="shortcut icon" type="image/ico" href="<?php echo $domain_static; ?>images/favicon.ico">
		<link href="<?php echo $domain_static; ?>style/user/common/base.css<?php echo '?v='.SYS_VERSION.SYS_BUILD;?>" rel="stylesheet" />
        <link href="<?php echo $domain_static; ?>style/user/common/ui.css<?php echo '?v='.SYS_VERSION.SYS_BUILD;?>" rel="stylesheet" />
        <link href="<?php echo $domain_static; ?>style/user/common/topbar.css<?php echo '?v='.SYS_VERSION.SYS_BUILD;?>" rel="stylesheet" />
        <link href="<?php echo $domain_static; ?>style/user/common/header.css<?php echo '?v='.SYS_VERSION.SYS_BUILD;?>" rel="stylesheet" />
        <link href="<?php echo $domain_static; ?>style/user/common/footer.css<?php echo '?v='.SYS_VERSION.SYS_BUILD;?>" rel="stylesheet" />
        <link href="<?php echo $domain_static; ?>style/user/common/usermenu.css<?php echo '?v='.SYS_VERSION.SYS_BUILD;?>" rel="stylesheet" />
        <link href="<?php echo $domain_static; ?>style/user/frame.css<?php echo '?v='.SYS_VERSION.SYS_BUILD;?>" rel="stylesheet" />
        <?php
        if(isset($add_css) && $add_css){
            foreach($add_css as $css)
                echo '<link href="'.$css.'?v='.SYS_VERSION.SYS_BUILD.'" rel="stylesheet" />';
        }
        ?>
        <script src="<?php echo $domain_static; ?>javascript/common/jquery/jquery-1.9.1.min.js" type="text/javascript"></script>
        <script src="<?php echo $domain_static; ?>javascript/common/jquery/jquery.form.min.js" type="text/javascript"></script>
        <?php
        if(isset($add_js) && $add_js){
            foreach($add_js as $js)
                echo '<script src="'.$js.'?v='.SYS_VERSION.SYS_BUILD.'" type="text/javascript"></script>';
        }
        ?>
        <script>
        $(function(){

            (function(){
                /* 搜索状态 */
                var form = $('form.header-search');
                var input = form.find(':text[name=key]');
                $("#js_header-search-type").hover(function() {
                    var baba = $(this);
                    baba.addClass("header-search-typeHover");
                    baba.find("li").click(function() {
                        var className = "header-search-type-selected";
                        var searchType = $(this).attr("data-searchType");
                        $(this).prependTo(baba).addClass(className).siblings("li").removeClass(className);
                        form.find("input[name=type]").val(searchType);
                        input.focus();
                    });

                }, function() {
                    $(this).removeClass("header-search-typeHover");
                });
                var searchT = form.find('input[name=type]').val();
                $("#js_header-search-type li").eq(searchT == 'shop' ? 0 : 1).click();
                input.focus();
                form.submit(function(){
                    var val = $.trim(input.val());
                    if(!val){
                        input.val('').focus();
                        return false;
                    }
                });
            })();//立即运行
            
            //为所有 .ui-form-textDatetime元素 赋予时间选择功能
            $(document.body).on("click",".ui-form-textDatetime",function(evt){

                    var startTime = function(){ 
                             WdatePicker({
                                dateFmt: formElm.data("datefmt") || 'yyyy-MM-dd',
                                maxDate:'#F{$dp.$D(\'dateTo\')}'
                             });
                        },
                        endTime = function(){ 
                             WdatePicker({
                                dateFmt: toElm.data("datefmt") || 'yyyy-MM-dd',
                                minDate:'#F{$dp.$D(\'dateForm\')}'
                             });
                        },
                        fnTime = function(){
                             WdatePicker({
                                dateFmt: dt.data("datefmt") || 'yyyy-MM-dd' 
                             });
                        };

                    var dt = $(this).closest("form").find('.ui-form-textDatetime');

                    if ( dt.length === 2 ){
                        var formElm = dt.eq(0).attr('id','dateForm');
                        var toElm =  dt.eq(1).attr('id','dateTo');
                        formElm.unbind("click", startTime).bind("click", startTime);
                        toElm.unbind("click", endTime).bind("click", endTime);
                    } else {
                        dt.unbind("click", fnTime).bind("click", fnTime);
                    }

                    evt.target.click();
            });

        });
        </script>	
    </head>
	<body>
		<div class="topbar">
			<?php $domain_msg = $this->config->item($utype == 2 ? 'domain_seller' : 'domain_buyer');?>
			<div class="topbar-wrap">
				<p class="topbar-user">
					<?php if($uid){?>
                    您好,<?php echo isset($uallow_perfect_account)?$uallow_perfect_account:FALSE ? '<a class="topbar-user-name" href="/login_bind/qq">请完善资料' : '<a class="topbar-user-name" href="'.$domain_msg.'">'.$uname; ?></a>
                    <span class="topbar-tipMsg">您有未读提醒<a style="margin:0" href="<?php echo $domain_msg;?>message">(<em>0</em>)</a>条</span>
                    <a href="<?php echo config_item('url_logout').'?to='.config_item('domain_www'); ?>">退出</a>
                    <?php }else{?>
                    您尚未登录！请先<a class="topbar-user-name" href="<?php echo config_item('url_login').'?to='.urlencode('http://'.$this->input->server('HTTP_HOST', TRUE).$this->input->server('REQUEST_URI', TRUE)); ?>">登录</a>
                    <?php }?>
				</p>
				<div class="topbar-nav">
                    <?php if($utype != 2){?>
                    <a href="<?php echo config_item('domain_special'); ?>invite/" target="_blank">邀请好友<span class="yuan-bg">奖<b>20</b>元</span></a>
                    <?php }?>
                    <a href="<?php echo config_item('domain_www'); ?>" target="_blank" class="topbar-nav-index">众划算首页</a>
                    <a href="<?php echo config_item('domain_shikee_www'); ?>" target="_blank">试客联盟</a>
                    <a href="<?php echo config_item('domain_hlpay_www'); ?>" target="_blank">互联支付</a>
                    <a href="<?php echo config_item('url_help'); ?>" target="_blank"  >帮助中心</a>
				</div><!-- /topbar-nav -->
			</div>
			<?php if($uid){?>
			<script>
				var flash_t = 20,msgflash;
				function m_flash(num){
					var c_l = window.flash_t % 2 == 0 ? '#F5F5F5' : '#1BB974';
					$('.topbar-user .topbar-tipMsg a em').html(num).css({'color':c_l,'font-weight':700});
					window.flash_t--;
					window.flash_t <= 0 && clearInterval(window.msgflash);
				}
				function _showMsg(obj){
					if(typeof obj != 'object' || obj == ''){
						return;
					}
					if(parseInt(obj['m_unr']) > 0){
						window.msgflash = setInterval("window.m_flash("+obj.m_unr+")", 200);
					}
				}
				$(function(){
					var scriptElm = document.createElement("script");
					scriptElm.src = '<?php echo $domain_msg;?>msg/?sync=1&callback=_showMsg';
					document.getElementsByTagName("head")[0].appendChild(scriptElm);
				});
			</script>
			<?php }?>
		</div><!-- /topbar -->

		<div class="header">
			<div class="header-main">
				<a class="header-logo" title="个人中心" href="<?php echo $domain_msg;?>">
					<?php 
						if( ! in_array($utype, array(1,2)) && preg_match("/^(seller|buyer)\.[a-z0-9]+\.com$/", $_SERVER['HTTP_HOST'], $matchs)){
							$utype = $matchs[1] == 'seller' ? 2 : 1;
						}
					?>
					<img src="<?php echo $domain_static; ?>images/user/<?php if($utype == 2){?>sellerhomelogo.png<?php }elseif($utype == 1){?>buyerhomelogo.png<?php }?>" alt="众划算">
				</a>
                <form target="_blank" class="header-search" name="search" action="<?php echo config_item('domain_list'); ?>search/" target="_top">
                    <ul class="header-search-type" id="js_header-search-type">
                        <li class="header-search-type-selected" data-searchType="goods">商品</li>
                        <li data-searchType="shop">商家</li>
                    </ul>
                    <input class="header-search-txt" name="key" value="" />
                    <input class="header-search-btn" type="submit" title="搜索" value=" " />
                    <input type="hidden" name="type" value="goods" />
                </form>

            </div><!-- /header-main -->
			<div class="header-nav">
                <ul>
                    <li><a class="header-nav-current" href="<?php echo $domain_msg;?>">我的众划算</a></li>
                </ul>
			</div><!-- /header-nav -->
		</div><!-- /header -->
