<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-head">
				<h2 class="ui-box-tit">优质会员返现配置</h2>
			</div>
			<form class="onlineTime" type="ajax" action="<?php echo site_url('setting/premium_user'); ?>" method="post">
				<table cellspacing="0">
					<tbody>
						<tr>
							<td>
								优质会员返现加速：
								<input class="ui-form-text ui-form-textRed" style="width: 160px;" data-rule="number|range(0,21)" data-msg="只能输入数字|请输入大于等于0且小于等于21的正整数" type="text" value="<?php echo $accelerate_rebate_day;?>" name="accelerate_rebate_day" />天
								<span style="color:gray;">（修改后立即生效）</span>
								<span id="for_accelerate_rebate_day" class="error"></span>
								<input type="hidden" name="accelerate_rebate_day_remark" value="优质会员返现加速天数(即提前N天返现)" />
							</td>
						</tr>
						<tr>
							<td>
								返现金额≥
								<input class="ui-form-text ui-form-textRed" style="width: 160px;" data-rule="float|range(0,100000,3)" data-msg="只能输入正数|请输入0~100000之间的数字，保留两位小数" type="text" value="<?php echo $invalid_rebate_money;?>" name="invalid_rebate_money" />元时，返现加速天数无效(0表示无限制)
								<span style="color:gray;">（修改后立即生效）</span>
								<span id="for_invalid_rebate_money" class="error"></span>
								<input type="hidden" name="invalid_rebate_money_remark" value="返现金额≥该值时，返现加速天数无效(即返现时间无加速)，0表示无限制" />
							</td>
						</tr>
					</tbody>
				</table>
				<div class="onlineTime-ft">
					<input type="submit" class="ui-form-button ui-form-buttonBlue" value="保存" />
					<input type="hidden" name="save" value="yes" />
				</div>
			</form>
		</div>
	</div>
</div>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>