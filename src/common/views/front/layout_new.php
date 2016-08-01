<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/*
 | -------------------------------------------------------------------
 | 静态文件
 | -------------------------------------------------------------------
 |
 */
// META
Template::meta("renderer", "webkit");                       // 360浏览器默认使用极速模式
Template::meta("X-UA-Compatible", "IE=edge,chrome=1", true);// 针对安装了谷歌插件的ie浏览器下，让ie使用谷歌内核渲染
Template::meta("D-Width-Response","class=g-wrap");          // 页面宽度响应设置
Template::meta("YL-SYS-Version", SYS_VERSION.SYS_BUILD);   // 系统防缓存设置
// CSS文件包
Template::add_css(array(
    'common/css/reset.css',                     // 重置样式表
    'common/css/function.css',                  // 功能样式表
    'common/widget/topbar/css/style.css',       // topbar挂件专属样式表
    'front/common/widget/sdbar/css/style.css',  // sdbar挂件专属样式表
    'front/common/css/layout.css',              // 公共样式
));
// JS文件包
Template::add_js(array(
    'common/js/jquery.js',                      // jQ
    'common/js/shs.js',                         // 核心shs
    'common/js/CL_Loader.js',                   // 按需加载引擎
    'common/js/CL.js',                          // MVC开发框架
    'common/widget/topbar/widget.js',           // 顶部用户条挂件
    'common/js/template-native.js',             // art模板引擎，sdbar挂件需要，虽然挂件会自行按需加载，但打包加载可减少请求量
    'front/common/widget/sdbar/widget.js',      // 侧边用户条挂件
    'front/common/js/layout.js',                // layout所需要的js
), Template::POS_END);
/*
 | -------------------------------------------------------------------
 | view
 | -------------------------------------------------------------------
 |
 */
?><!DOCTYPE html>
<!--[if lt IE 7]><html class="ie ie6"><![endif]-->
<!--[if IE 7]><html class="ie ie7"><![endif]-->
<!--[if IE 8]><html class="ie ie8"><![endif]-->
<!--[if IE 9]><html class="ie ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html>
<!--<![endif]-->
<head>
<meta charset="utf-8">
<?php Template::trigger_meta();?>
<title><?php echo Template::title(); ?></title>
<meta name="keywords" content="<?php echo Template::keywords();?>" />
<meta name="description" content="<?php echo Template::description();?>" />
<link rel="shortcut icon" type="image/ico" href="<?php echo config_item('domain_static'); ?>images/favicon.ico">
<?php Template::trigger_css();?>
<script>!function(e){if(!/\bie\b/i.test(e.className)){var a=navigator.userAgent.match(/\bMSIE (\d+)/i);e.className+=a?" ie ie"+a[1]:" noie"}}(document.getElementsByTagName("html")[0]);</script>
<?php Template::trigger_js(Template::POS_HEAD);?>
</head>
<body<?php echo Template::wide() ? ' class="z-doc-wide"' : '' ; ?>>
    <div class="g-doc" id="J_doc">
        <!-- 头部 grid Begin -->
        <div class="g-hd">
            <div class="g-tb">
                <!-- topbar widget -->
                <div class="g-wrap m-topbar" id="J_topbar">&nbsp;</div>
            </div>
            <!-- 顶部公告条  -->
            <script type="text/javascript" src="<?php echo config_item('domain_static').PACK_JS_WWW_TOP_NOTICE; ?>"></script>
            <div class="g-wrap">
                <!--Logo与搜索框 begin-->
                <div class="g-hdm f-cb">
                    <h2 class="m-logo">
                        <a href="<?php echo config_item('domain_www');?>" target="_top" title="众划算-担保式返现购物平台,全场包邮">
                            <img src="<?php echo config_item('domain_static');?>front/common/img/logo.png" alt="众划算"/>
                        </a>
                    </h2>
                    <div class="m-search" id="J_search">
                        <form class="f-cb" name="search" action="<?php echo config_item('domain_list');?>search" target="_top">
                            <div class="sel">
                                <p class="J_sel">
                                    <a href="#" onclick="return false" class="z-crt" data-type="goods">
                                        商品
                                    <span class="arrow">
                                        <i>&nbsp;</i>
                                    </span>
                                    </a>
                                    <a href="#" onclick="return false" data-type="shop">
                                        商家
                                    <span class="arrow">
                                        <i>&nbsp;</i>
                                    </span>
                                    </a>
                                </p>
                            </div>
                            <input class="J_type" type="hidden" name="type" value="<?php echo isset($search_type)?$search_type:'goods'; ?>">
                            <input type="text" class="ipt" name="key" value="<?php echo isset($search_key)?$search_key:''; ?>">
                            <input type="submit" class="btn" value="搜索">
                        </form>
                    </div>
                </div>
                <!--Logo与搜索框 End-->
                <!-- 导航模块 Begin -->
                <div class="f-cb">
                    <?php if(isset($col)&&$col === 'home'){ ?>
                    <div class="m-dorpdown"><p>全部商品分类&nbsp;<span class="arrow"><i>&nbsp;</i><b>&nbsp;</b></span></p></div>
                    <?php } ?>
                    <ul class="m-nav">
                        <?php if(isset($col)&&$col !== 'home'){ ?>
                            <li><a href="<?php echo config_item('domain_www'); ?>">首页</a></li>
                        <?php } ?>
                        <li<?php echo isset($col)&&$col === 'list' ? ' class="z-crt"' : '';?>>
                            <a href="<?php echo config_item('domain_list');?>">商品总汇</a>
                        </li>
                        <li<?php echo isset($col)&&$col === 'new' ? ' class="z-crt"' : '';?>>
                            <a href="<?php echo config_item('domain_list');?>new">最新上线</a>
                        </li>
                        <li<?php echo isset($col)&&$col === 'yzcm' ? ' class="z-crt"' : '';?>>
                            <i class="zk3">&nbsp;</i>
                            <a href="<?php echo config_item('domain_www');?>yzcm">一站成名</a>
                        </li>
                        <li<?php echo isset($col)&&$col === 'mpg' ? ' class="z-crt"' : '';?>>
                            <i class="zk5">&nbsp;</i>
                            <a href="<?php echo config_item('domain_www');?>mpg">名品特卖</a>
                        </li>
                        <li>
                            <a href="<?php echo config_item('trial_goods_url');?>" target="_blank" rel="nofollow">免费试用</a>
                        </li>
                        <li <?php echo isset($col)&&$col === 'shaidan' ? ' class="z-crt"' : '';?>>
                            <a href="<?php echo config_item('domain_www'); ?>show/" target="_blank"  rel="nofollow">买家晒单</a>
                        </li>
                        <li <?php echo isset($col)&&$col === 'yiban' ? ' class="z-crt"' : '';?>>
                            <a href="<?php echo config_item('domain_yiban'); ?>" target="_blank">服装特卖</a>
                        </li>
                        <li<?php echo isset($col)&&$col === 'zfq' ? ' class="z-crt"' : '';?>>
                            <a href="<?php echo config_item('domain_www');?>zfq">众分期</a>
                        </li>
                    </ul>
                    <div class="m-inv">
                        <a href="<?php echo config_item('cooperation'); ?>" title="众划算招商" target="_blank">众划算招商入口</a>
                    </div>
                </div>
                <!-- 导航模块 End -->
            </div>
        </div>
        <!-- 头部 grid End -->
        <!-- 主体 grid Begin -->
        <?php echo $__VIEW_CONTENT__;?>
        <!-- 主体 grid End -->
        <!-- 底部 grid Begin -->
        <div class="g-ft">
            <div class="g-wrap">
                <div class="g-fi">
                    <div class="m-cprt">
                        <?php if (isset($footer_links) AND is_array($footer_links)):?>
                        <p>友情链接：
                            <?php foreach ($footer_links as $link):?>
                            <a href="<?php echo $link['url']?>" target="_blank"><?php echo $link['title']?></a>&nbsp;&nbsp;
                            <?php endforeach;?>
                        </p>
                        <?php endif;?>
                        <div class="f-cb">
                            Copyright © 2006-<?php echo date('Y');?> zhonghuasuan.com&nbsp;&nbsp;
                            <a target="_blank" href="<?php echo config_item('legal_url');?>">法律声明</a>
                            &nbsp;&nbsp;&nbsp;版权所有:南宁一站网网络技术有限公司&nbsp;&nbsp;&nbsp; 地址:广西南宁市高新区高新大道62号光辉大厦6楼
                            <div class="f-fr"><span class="emall-icon"></span>mall@shikee.com</div>
                        </div>
                        <p>
                            桂B2-20110047&nbsp;&nbsp;&nbsp;
                            <a target="_blank" href="http://www.miibeian.gov.cn/">桂ICP备07009935号</a>
                            <script src="http://s15.cnzz.com/stat.php?id=905537&web_id=905537" language="JavaScript"></script>
                            &nbsp;&nbsp;&nbsp;
                            <a target="_blank" href="http://www.fastcache.com.cn/">本站由速网科技提供CDN加速</a>
                        </p>
                    </div>
                    <div class="m-credit f-cb">
                        <a class="police" target="_blank" href="http://www.gx.cyberpolice.cn/NewsCategory/lstNewCate.do" title="南宁网警" rel="nofollow" >
                            <img src="<?php echo config_item('domain_static')?>common/img/icon/50x50_police.png" alt="南宁网警">
                        </a>
                        <!--可信网站图片LOGO安装开始-->
                        <!--<script src="http://kxlogo.knet.cn/seallogo.dll?sn=e13111211010043388o1e9000000&size=3"></script>-->
                        <script>
                            document.write(
                                '<a class="kxlogo" target="_blank" href="https://ss.knet.cn/verifyseal.dll?sn=e13111211010043388o1e9000000&ct=df&a=1&pa='+Math.random()+'" title="可信网站" rel="nofollow">'
                                +     '<img src="http://rr.knet.cn/static/images/logo/cnnic.png" alt="可信网站" />'
                                + '</a>'
                            );
                        </script>
                        <!--可信网站图片LOGO安装结束-->
                    </div>
                </div>
            </div>
        </div>
        <!-- 底部 grid End -->
        <?php Template::trigger_js(Template::POS_END);?>
        <!-- 跟踪统计 -->
        <script>Loader.run("<?php echo config_item('domain_static').PACK_JS_REFERRER; ?>");</script>
    </div>
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