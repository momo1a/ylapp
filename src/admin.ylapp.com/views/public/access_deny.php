<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理员中心</title>
<?php change_to_minify("style/admin/base.css,style/admin/ui.css"); ?>
<style>
	.ui-box { width: 800px; margin: 50px auto;}
	.ui-box-inner { padding:20px 15px; }
	.tit { text-align: center; font-size: 18px; font-weight: 700;}
	.cont { margin-top: 30px; text-align: center;}
</style>
</head>
<body>

	<div class="ui-box ui-box2">
		<div class="ui-box-outer">
			<div class="ui-box-inner"> 
					<h2 class="tit"><?php echo $msg;?></h2>
					<div class="cont">
						<a href="<?php echo $this->config->item('domain_www');?>" class="ui-form-button ui-form-buttonBlue">返回首页</a>
						<a style="margin-left:20px;" href="<?php echo $this->config->item('url_logout');?>" class="ui-form-button ui-form-buttonBlue">切换用户</a>
					</div>
			</div>
		</div>
	</div>

</body>
</html>