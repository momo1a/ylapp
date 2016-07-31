<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<br/>
<!-- 已结算活动查看时密钥编辑表单 -->
<div class="ui-box">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-head">
				<h2 class="ui-box-tit"> 已结算活动查看时密钥设置：</h2>
			</div>
			<form class="onlineTime" type="ajax" action="<?php echo site_url('setting/have_pay_key_setting'); ?>" method="post">
				<table cellspacing="0">
					<tbody>
              		<?php 
              		foreach ($have_pay_key_config as $key => $value) {
              			
              		?>
						<tr>
							<td>
                                    <?php echo $value['remark']?>：
                                    <input class="ui-form-text ui-form-textRed" style="width:160px;" type="<?php echo $value['html_type']?>"
                                           value="<?php echo $value['value'];?>" name="<?php echo $key?>" />
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
<!-- End 已结算活动查看时密钥编辑表单 -->
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>