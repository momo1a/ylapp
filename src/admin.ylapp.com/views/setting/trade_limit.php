<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<br/>
<!-- 抢购限制设置表单 -->
<div class="ui-box">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-head">
				<h2 class="ui-box-tit"> 抢购限制设置：</h2>
			</div>
			<div style="height: 25px; border-top: 1px solid #CCCCCC; color: #00F; padding: 10px;">1.满足以下条件时才可以抢购商品!</div>
			<form class="onlineTime" type="ajax" action="<?php echo site_url('setting/trade_limit_setting'); ?>" method="post">
				<table cellspacing="0">
					<tbody>
              		<?php 
              		foreach ($trade_limit_config as $key => $value) {
              			
              		?>
						<tr>
							<td>
								<?php echo $value['remark']?>：
								<?php echo form_checkbox($key, 1, $value['value']);?>
							</td>
						</tr>
            		<?php }?>
					</tbody>
				</table>
				<div class="onlineTime-ft">
					<input type="submit" class="ui-form-button ui-form-buttonBlue" value="保存设置" />
					<input type="hidden" name="save" value="yes" />
				</div>
			</form>
		</div>
		
	</div>
</div>
<!-- End 抢购限制设置表单 -->
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>