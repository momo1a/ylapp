<div class="topbar">
	<div class="topbar-wrap">
		<p class="login-info">
			<span>您好,欢迎来到众划算！</span>
			<a class="topbar-QQlogin" href="<?php echo config_item('url_qq_login'); ?>">
				<img alt="QQ登录" src="<?php echo config_item('domain_static').'images/www/qq.png'; ?>"/>QQ登录</a>
			<i>|</i><a class="topbar-login" href="<?php echo config_item('url_login'); ?>" rel="nofollow" >登录</a>
			<i>|</i><a class="topbar-register" href="<?php echo config_item('url_reg'); ?>"  rel="nofollow" >免费注册</a>
		</p>
		<div class="topbar-nav">
			<?php if(!isset($u_type) || $u_type != 2){?>
			<a href="<?php echo config_item('domain_special'); ?>invite/" target="_blank">邀请好友<span class="yuan-bg">奖<b>20</b>元</span></a>
			<?php }?>
			<div class="topbar-navMenu">
				<span class="topbar-navMenu-title">关注我们<i></i></span>
				<div class="topbar-navMenu-cont">
					<span class="topbar-navMenu-contArrow"><i></i><b></b></span>
					<a class="topbar-att-weibo" target="_blank" href="http://weibo.com/ylapp" rel="nofollow" >新浪微博</a>
					<a target="_blank" href="http://list.qq.com/cgi-bin/qf_invite?id=12ae0780913afb80d9253856d368c7472788f78621bd5c77" rel="nofollow" >订阅邮件</a>
					<span>微信关注</span>
					<span class="topbar-att-qrcode"><i></i></span>
				</div>
			</div>
			<a href="<?php echo config_item('domain_shikee_www'); ?>" target="_blank">试客联盟</a>
			<a href="<?php echo config_item('domain_hlpay_www'); ?>" target="_blank" rel="nofollow" >互联支付</a>
            <a href="<?php echo config_item('url_help'); ?>" target="_blank"  >帮助中心</a>
                <div class="topbar-navMenu">
				<span class="topbar-navMenu-title">网站导航<i></i></span>
				<div class="topbar-navMenu-cont site-map">
					<span class="topbar-navMenu-contArrow"><i></i><b></b></span>
					<dl class="clearfix">
						<dt>网站热点</dt>
						<dd><a href="<?php echo config_item('domain_list'); ?>new/">最新上线</a></dd>
						<dd><a href="<?php echo config_item('domain_www'); ?>yzcm/">一站成名</a></dd>
						<dd><a href="<?php echo config_item('domain_www'); ?>mpg/">名品馆</a></dd>
						<dd><a href="<?php echo config_item('domain_list'); ?>">商品总汇</a></dd>
						<dd><a href="<?php echo config_item('domain_www'); ?>show/"  rel="nofollow" >买家晒单</a></dd>
					</dl>
					<dl class="clearfix">
						<dt>社区</dt>
						<dd><a target="_blank" href="<?php echo config_item('domain_shikee_bbs'); ?>"  rel="nofollow" >社区</a></dd>
					</dl>
					<dl class="clearfix">
						<dt>网站服务</dt>
						<dd><a target="_blank" href="<?php echo $domain_www;?>guide" rel="nofollow" >新手引导</a></dd>
						<dd><a target="_blank" href="<?php echo config_item('url_help'); ?>" rel="nofollow">帮助中心</a></dd>
						<dd><a target="_blank" href="http://e.zhonghuasuan.com/">招商专区</a></dd>
						<dd><a target="_blank" href="<?php echo config_item('domain_www');?>about/">关于我们</a></dd>
					</dl>
					<dl class="clearfix" style="border:none">
						<dt>合作网站</dt>
						<dd><a target="_blank" href="<?php echo config_item('domain_shikee_www'); ?>">试客联盟</a></dd>
					</dl>
				</div>
			</div>
            
		</div>
	</div>
</div><!-- /topbar -->
<script type="text/javascript" src="<?php echo config_item('domain_static').PACK_JS_WWW_TOP_NOTICE; ?>"></script>
<div class="header">
	<div class="header-main">
		<div class="header-logo">
			<a title="众划算-担保式返现购物平台,全场包邮" href="<?php echo config_item('domain_www'); ?>">众划算,担保式返现购物平台,全场包邮。</a>
		</div>
		<form class="header-search" name="search" action="<?php echo config_item('domain_list'); ?>search/" target="_top">
			<ul class="header-search-type" id="js_header-search-type">
			    <?php 
			    if(isset($search_type) && in_array($search_type, array('shop', 'seller'))){
			        echo '<li class="header-search-type-selected" data-searchType="shop">商家</li>
			        <li data-searchType="goods">商品</li>';
			    }else{
                    echo '<li class="header-search-type-selected" data-searchType="goods">商品</li>
                <li data-searchType="shop">商家</li>';
			    }
			    ?>
			</ul>
			<input class="header-search-txt" type="text" name="key" value="<?php echo isset($search_key)?$search_key:''; ?>" />
			<input class="header-search-btn" type="submit" title="搜索" value=" " />
			<input type="hidden" name="type" value="<?php echo isset($search_type)?$search_type:'goods'; ?>" />
		</form>
	</div><!-- /header-main -->
	<div class="header-nav">
		<ul>
			<li<?php echo isset($col)&&$col === 'home' ? ' class="header-nav-current"' : ''; ?>>
				<a href="<?php echo config_item('domain_www'); ?>">首页</a>
			</li>
			<li<?php echo isset($col)&&in_array($col, array('default','old')) ? ' class="header-nav-current"' : ''; ?>>
				<a href="<?php echo config_item('domain_list'); ?>">商品总汇</a>
			</li>
			<li<?php echo isset($col)&&$col === 'new' ? ' class="header-nav-current"' : ''; ?>>
				<a href="<?php echo config_item('domain_list'); ?>new/">最新上线</a>
			</li>
            <li<?php echo isset($col)&&$col === 'yzcm' ? ' class="header-nav-current"' : ''; ?>>
                <a href="<?php echo config_item('domain_www'); ?>yzcm/">一站成名</a>
            </li>
            <li<?php echo isset($col)&&$col === 'mpg' ? ' class="header-nav-current"' : ''; ?>>
                <a href="<?php echo config_item('domain_www'); ?>mpg/">名品特卖</a>
            </li>
			<li>
				<a href="<?php echo config_item('trial_goods_url');?>" target="_blank" rel="nofollow" >免费试用</a>
			</li>
			<li>
				<a href="<?php echo config_item('domain_www'); ?>show/" target="_blank" rel="nofollow" >买家晒单</a>
			</li>

		</ul>
	</div><!-- /header-nav -->
</div><!-- /header -->
