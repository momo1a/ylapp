<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<?php change_to_minify("javascript/common/jquery/artDialog/skins/default.css"); ?>
<?php change_to_minify('javascript/common/jquery/artDialog/jquery.artDialog.js',false);?>
<?php change_to_minify("javascript/seller/goods.js"); ?>
<div class="ui-box ui-box2 paying">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-head paying-head">支付现金券担保金</div>
			<div class="paying-body" style="height:300px">
				<form id="paycashform" action="<?php echo $this->config->item('domain_hlpay_www'); ?>trade/pay" method="post">
					<input type="hidden" value="<?php echo $data['site'];?>" name="site" />
					<input type="hidden" value="<?php echo $data['uid'];?>" name="uid" />
					<input type="hidden" value="<?php echo $data['type'];?>" name="type" />
					<input type="hidden" value="<?php echo $data['title'];?>" name="title" />
					<input type="hidden" value="<?php echo $data['pno'];?>" name="pno" />
					<input type="hidden" value="<?php echo $data['money'];?>" name="money" />
					<input type="hidden" value="<?php echo $data['key'];?>" name="key" />
					<input type="hidden" value="<?php echo $data['backurl'];?>" name="backurl" />
					<input type="hidden" value="<?php echo $data['notifyurl'];?>" name="notifyurl" />
					<input type="hidden" value="<?php echo $data['temp1'];?>" name="temp1" />
					<input style="display:none;" class="ui-form-button ui-form-buttonRed" type="submit" value="确认支付">
				</form>
				<script>
				$(function(){
					goods.do_submit(
						'正在请求支付，请勿刷新浏览器...',
						300,//等待时间,单位:秒
						function(){$('#paycashform').submit();},
						function(){
							var html = '<div style="padding: 0 1em;">请求超时,您可以点击下面的链接进行重试!</div>';
							html += '<div style="padding: 0.5em 5em; text-align: center; font-size: 14px;">';
							html += '<a href="<?php echo site_url('cash/pay').'?id='.$id;?>">重&nbsp;试</a>';
							html += '</div>';
							this.content(html);
							return false;
						}
					);
				});
				</script>
				<p style="text-align:center;margin-top:100px">正在请求支付，请勿刷新浏览器...</p>
				<p style="text-align:center;margin-top:10px">浏览器超过5分钟未响应,您可以点击&nbsp;<a href="<?php echo site_url('cash/pay').'?id='.$id;?>">重&nbsp;试</a></p>
			</div>
		</div>
	</div>
</div>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>