<div class="footer">
	<div class="footer-bd">
		<div class="footer-info">
			<p class="footer-copyright">Copyright © 2006-<?php echo date('Y');?> zhonghuasuan.com&nbsp;&nbsp;&nbsp;<a href="<?php echo config_item('legal_url');?>" target="_blank">法律声明</a>版权所有:南宁一站网网络技术有限公司&nbsp;&nbsp;&nbsp;地址:广西南宁市高新区高新大道62号光辉大厦6楼</p>
			<p>
				<span>桂B2-20110047</span>&nbsp;&nbsp;&nbsp;
				<a href="http://www.miibeian.gov.cn"> 桂ICP备07009935号</a>&nbsp;&nbsp;&nbsp;
				<script src="http://s15.cnzz.com/stat.php?id=905537&web_id=905537" language="JavaScript"></script>&nbsp;&nbsp;&nbsp;
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
</div>
<?php if(isset($col) && !in_array($col, array('home','show','about'))){ 
    ?>
<div class="bind-remind-outer">
    <div class="bind-remind">
        <div class="bind-remind-inner">
            <p> 您还未认证手机，</p><p>无法抢购众划算。</p>
            <p><a href="<?php echo $domain_buyer ?>bind/mobile" target="_blank">去认证&gt;&gt;</a></p>
        </div> 
    </div>
</div>
<?php
}?>
<script>
!function(){
	var r = document.createElement("script");
	r.setAttribute("type","text/javascript");
	r.setAttribute("src","<?php echo config_item('domain_static').PACK_JS_REFERRER; ?>");
	document.body.appendChild(r);
}();
</script>