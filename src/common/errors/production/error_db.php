<!DOCTYPE html><html>
	<head>
		<meta charset="utf-8" />
		<title>系统错误 - 众划算官方网站</title>
		<link href="<?php echo config_item('domain_static') . PACK_CSS_WWW; ?>" rel="stylesheet" />
		<link href="<?php echo config_item('domain_static'); ?>style/www/message.css" rel="stylesheet" />
		<meta http-equiv="refresh" content="3;url=<?php echo config_item('domain_www'); ?>">
	</head>
	<body>
		<?php include COMPATH.'views/front/header.php';?>
		<div class="error">
			<div class="error-503">
				<div class="error-503-top"><img src="<?php echo config_item('domain_static'); ?>images/error/error503_1.png" /></div>
				<?php if(ENVIRONMENT == 'testing')://测试环境输出语句错误信息  ?>
				<div class="reason503"><?php echo $message;?></div>
				<?php else:?>
				<?php log_message('error', $message);// 记录日志?>
				<div class="reason503">数据库连接失败</div>
				<?php endif;?>
				<ul class="errorTxt">
				<li>您可以：</li>
				<li>
					1.<a class="retunIndex" href="<?php echo config_item('domain_www'); ?>">返回首页</a>
					<a class="refresh" href="javascript:location.reload()">刷新</a>
				</li>
				<li>
					2.去其他地方逛逛：<a href="<?php echo config_item('domain_list'); ?>hot/">近期热卖</a> | <a href="<?php echo config_item('domain_www'); ?>yzcm/">一站成名</a> | <a href="<?php echo config_item('domain_list'); ?>">商品总汇</a>
				</li>
				</ul>
			</div>
		</div>
		<?php include COMPATH.'views/front/footer.php';?>
		<script type="text/javascript" src="<?php echo config_item('domain_static') . PACK_JS_WWW; ?>"></script>
	</body>
</html>