<div class="error-503">
	<div class="error-503-top">
		<img
			src="<?php echo config_item('domain_static'); ?>images/error/error503_1.png" />
	</div>
		<?php if(ENVIRONMENT == 'testing')://测试环境输出语句错误信息  ?>
		<div class="reason503">
		<p>Severity: <?php echo $severity; ?></p>
		<p>Message:  <?php echo $message; ?></p>
		<p>Filename: <?php echo $filepath; ?></p>
		<p>Line Number: <?php echo $line; ?></p>
	</div>
		<?php endif;?>
		<ul class="errorTxt">
		<li>您可以：</li>
		<li>1.<a class="retunIndex"
			href="<?php echo config_item('domain_www');?>">返回首页</a> <a
			class="refresh" href="javascript:location.reload()">刷新</a>
		</li>
		<li>2.去其他地方逛逛：<a href="<?php echo config_item('domain_list'); ?>hot/">近期热卖</a>
			| <a href="<?php echo config_item('domain_www'); ?>yzcm/">一站成名</a> |
			<a href="<?php echo config_item('domain_list'); ?>">商品总汇</a>
		</li>
	</ul>
</div>
