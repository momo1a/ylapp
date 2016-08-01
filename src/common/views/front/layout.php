<?php

// CSS文件包
Template::add_css(array(
    'style/www/common/base.css',
    'style/www/common/topbar.css',
    'style/www/common/header.css',
    'style/www/common/footer.css',
));

?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="YL-SYS-Version" content="<?php echo SYS_VERSION.SYS_BUILD;?>">
<?php if (isset($_head_metas)) : ?>
	<?php foreach($_head_metas as $meta):?>
	<meta <?php echo $meta['http_equiv'] ? "http-equiv" : "name";?>="<?php echo $meta['name'];?>" content="<?php echo $meta['content'];?>">
	<?php endforeach;?>
<?php endif;?>
<title><?php echo Template::title(); ?></title>
<meta name="keywords" content="<?php echo Template::keywords();?>" />
<meta name="description" content="<?php echo Template::description();?>" />
<link rel="shortcut icon" type="image/ico" href="<?php echo config_item('domain_static'); ?>images/favicon.ico">
<?php Template::trigger_css();?>
<?php Template::trigger_js(Template::POS_HEAD);?>
</head>
<body>
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
			<?php if(AuthUser::type() != 2){?>
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
						<dd><a href="<?php echo config_item('domain_www'); ?>show/" rel="nofollow">买家晒单</a></dd>
					</dl>
					<dl class="clearfix">
						<dt>社区</dt>
						<dd><a target="_blank" href="<?php echo config_item('domain_shikee_bbs'); ?>" rel="nofollow">社区</a></dd>
					</dl>
					<dl class="clearfix">
						<dt>网站服务</dt>
						<dd><a target="_blank" href="<?php echo config_item('domain_www');?>guide" rel="nofollow">新手引导</a></dd>
						<dd><a target="_blank" href="<?php echo config_item('url_help'); ?>" rel="nofollow">帮助中心</a></dd>
						<dd><a target="_blank" href="http://e.zhonghuasuan.com/">招商专区</a></dd>
						<dd><a target="_blank" href="<?php echo config_item('domain_www'); ?>about/" rel="nofollow">关于我们</a></dd>
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
<!-- 公告 -->
<script type="text/javascript" src="<?php echo config_item('domain_static').PACK_JS_WWW_TOP_NOTICE;?>"></script>
<div class="header">
	<div class="header-main">
		<div class="header-logo">
			<a title="众划算-担保式返现购物平台,全场包邮" href="<?php echo config_item('domain_www');?>">众划算,担保式返现购物平台,全场包邮。</a>
		</div>
		<form class="header-search" name="search" action="<?php echo config_item('domain_list');?>search" target="_top">
			<ul class="header-search-type" id="js_header-search-type">
			    <?php if(isset($search_type) && in_array($search_type, array('shop', 'seller'))):?>
				<li class="header-search-type-selected" data-searchType="shop">商家</li>
				<li data-searchType="goods">商品</li>
			    <?php else:?>
				<li class="header-search-type-selected" data-searchType="goods">商品</li>
				<li data-searchType="shop">商家</li>
			    <?php endif;?>
			</ul>
			<input class="header-search-txt" type="text" name="key" value="<?php echo isset($search_key)?$search_key:''; ?>" />
			<input class="header-search-btn" type="submit" title="搜索" value=" " />
			<input type="hidden" name="type" value="<?php echo isset($search_type)?$search_type:'goods'; ?>" />
		</form>
	</div><!-- /header-main -->
	<div class="header-nav">
		<ul>
			<li<?php echo isset($col)&&$col === 'home' ? ' class="header-nav-current"' : '';?>>
				<a href="<?php echo config_item('domain_www');?>">首页</a>
			</li>
			<li<?php echo isset($col)&&$col === 'list' ? ' class="header-nav-current"' : '';?>>
				<a href="<?php echo config_item('domain_list');?>">商品总汇</a>
			</li>
			<li<?php echo isset($col)&&$col === 'new' ? ' class="header-nav-current"' : '';?>>
				<a href="<?php echo config_item('domain_list');?>new">最新上线</a>
			</li>
            <li<?php echo $this->router->class === 'yzcm' ? ' class="header-nav-current"' : '';?>>
				<i class="zk3">&nbsp;</i>
				<a href="<?php echo config_item('domain_www');?>yzcm">一站成名</a>
            </li>
            <li<?php echo $this->router->class === 'mpg' ? ' class="header-nav-current"' : '';?>>
				<i class="zk5">&nbsp;</i>
				<a href="<?php echo config_item('domain_www');?>mpg">名品特卖</a>
            </li>
            <li>
				<a href="<?php echo config_item('trial_goods_url');?>" target="_blank" rel="nofollow" >免费试用</a>
			</li>
			<li>
				<a href="<?php echo config_item('domain_www'); ?>show/" target="_blank" rel="nofollow" >买家晒单</a>
			</li>
			<li <?php echo isset($col)&&$col === 'yiban' ? ' class="z-crt"' : '';?>>
				<a href="<?php echo config_item('domain_yiban'); ?>" target="_blank">服装特卖</a>
			</li>

		</ul>
	</div><!-- /header-nav -->
</div><!-- /header -->
<!-- content -->
<?php echo $__VIEW_CONTENT__;?>
<!-- /content -->
<?php if(isset($col) && !in_array($col, array('home','show','about'))){
	?>
<div class="bind-remind-outer">
    <div class="bind-remind">
        <div class="bind-remind-inner"> <p> 您还未认证手机，</p><p>无法抢购众划算。</p>
            <p><a href="<?php echo config_item('domain_buyer');?>bind/mobile" target="_blank">去认证&gt;&gt;</a></p>
        </div>
    </div>
</div>
<?php
}?>
<?php //Template::html_hooks(Template::POS_END);?>
<?php Template::trigger_js(Template::POS_END);?>
<div class="footer">
	<div class="footer-bd">
		<div class="footer-info">
			<?php if (isset($footer_links) AND is_array($footer_links)):?>
			<p class="friendlyLink"><span>友情链接:&nbsp;</span>
			<?php foreach ($footer_links as $link):?>
			<a href="<?php echo $link['url']?>" target="_blank"><?php echo $link['title']?></a>
			<?php endforeach;?>
			</p>
			<?php endif;?>
			<p class="footer-copyright">Copyright © 2006-<?php echo date('Y');?> zhonghuasuan.com&nbsp;&nbsp;&nbsp;<a href="<?php echo config_item('legal_url');?>" target="_blank">法律声明</a>版权所有:南宁一站网网络技术有限公司&nbsp;&nbsp;&nbsp;地址:广西南宁市高新区高新大道62号光辉大厦6楼</p>
			<p>
				<span>桂B2-20110047</span>&nbsp;&nbsp;&nbsp;
				<a href="http://www.miibeian.gov.cn"> 桂ICP备07009935号</a>&nbsp;&nbsp;&nbsp;
				<script src="http://s15.cnzz.com/stat.php?id=905537&web_id=905537" language="JavaScript"></script>
				&nbsp;&nbsp;&nbsp;
				<a target="_blank" href="http://www.fastcache.com.cn/">本站由速网科技提供CDN加速</a>
				<div style="display:none;">
					<!-- 站长统计 -->
					<script src="http://s96.cnzz.com/stat.php?id=5713574&web_id=5713574&show=pic" language="JavaScript"></script>
				</div>
			</p>
		</div><!-- /footer-info -->
		<div class="footer-service">
			<!-- <p>客服中心电话：0771-3186577</p> -->
			<p class="footer-email"><span></span>mall@shikee.com</p>
		</div>
	</div>
    <div class="footer-ft">
        <a class="nanningPolice" target="_blank" href="http://www.gx.cyberpolice.cn/NewsCategory/lstNewCate.do" title="南宁网警" rel="nofollow" ></a>
        <!--可信网站图片LOGO安装开始-->
        <script src="http://kxlogo.knet.cn/seallogo.dll?sn=e13111211010043388o1e9000000&size=3"></script>
        <!--可信网站图片LOGO安装结束-->
    </div>
</div><!-- /footer -->
<div style="display:none;">
	<!-- 百度统计 -->
	<script type="text/javascript">
	var _hmt = _hmt || [];
	(function() {
		var hm = document.createElement("script");
		hm.src = "//hm.baidu.com/hm.js?24c3cf36b0a16747cd2c0ca3bbe6cffd";
		var s = document.getElementsByTagName("script")[0];
		s.parentNode.insertBefore(hm, s);
	})();
    </script>
    <!-- 百度统计 end-->
	<script type="text/javascript">
	!function(){
		var r = document.createElement("script");
		r.setAttribute("type","text/javascript");
		r.setAttribute("src","<?php echo config_item('domain_static').PACK_JS_REFERRER; ?>");
		document.body.appendChild(r);
	}();
	</script>
</div>
</body>
</html>
