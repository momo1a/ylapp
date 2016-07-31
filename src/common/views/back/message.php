<?php 
	error_reporting(0);
	if (! $inajax){ ?>
	<?php $this->load->view('back/header');?>
	<link rel="stylesheet" type="text/css" href="<?php echo $domain_static; ?>style/www/message.css">
	<div class="message notLogin">
		<div class="title notLogin-title"><?php if(is_array($message)){$message = $message['msg'];}echo $message?></div>
		<div class="content notLogin-content">
			<?php if(isset($extraparam['redirect'])){?>
				<p>您可以继续进行以下操作，如果不操作，将在<strong id="totalSecond"><?php echo $extraparam['locationtime'] > 0 ? $extraparam['locationtime'] : 3;?></strong>秒后自动跳转到第一个页面。<p>
			<?php }else{?>
				<p>您可以继续进行以下操作。<p>
			<?php }?>
			<ul class="notLogin-content-choose" id="optionlinks">
				<?php if(isset($uid) && $uid){?>
					<li>· <a href="<?php echo site_url();?>"><?php echo $utype == 2 ? '返回商家中心' : '返回买家中心'?></a></li>
				<?php }else{?>
					<li>· <a href="<?php echo config_item('domain_www');?>">众划算首页</a></li>
				<?php }?>
				<?php if (count($actionurl)>0){ ?>
					<?php foreach ($actionurl as $key=>$val): ?>
					<li>· <a href="<?php echo $val['url']?>"><?php echo $val['name']?></a></li>
					<?php endforeach; ?>
				<?php }?>
				<?php if( ! $newwin){?>
					<?php if($extraparam['backurl'] != ''){?>
					<li>· <a href="<?php echo $extraparam['backurl'];?>">返回上一页</a></li>
					<?php }else{?>
					<li>· <a href="javascript:history.go(-1);">返回上一页</a></li>
					<?php }?>
				<?php }?>
			</ul>
		</div>
		<?php if (isset($extraparam['redirect'])): ?>
		<script language="javascript" type="text/javascript">
			var second = parseInt('<?php echo $extraparam['locationtime'] > 0 ? $extraparam['locationtime'] : 3;?>');
			setInterval("redirect()", 1000); //每1秒钟调用redirect()方法一次
			function redirect(){
				if (second < 0){
					var firstLink = $('#optionlinks').find('li:eq(0)').find('a');
					if(firstLink.attr('href') == 'javascript:history.go(-1);'){
						history.go(-1);
					}else{
						location.href = firstLink.attr('href');
					}
				}else{
					$("#totalSecond").text(second--);
				}
			}
		</script>
		<?php endif; ?>
	</div>
	<?php $this->load->view('back/footer');?>
<?php }else{?>
	<?php if(is_array($message)){?>
		<?php echo json_encode($message); ?>
	<?php }else{?>
		<?php echo $message; ?>
	<?php }?>
<?php }?>