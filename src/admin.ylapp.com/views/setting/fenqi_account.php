<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<br/>
<div class="ui-box">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-head">
				<h2 class="ui-box-tit">分期付款账号<span>(以"逗号"隔开输入多个付款账号)</span></h2>
			</div>
			<form class="onlineTime" type="ajax" action="<?php echo site_url('setting/fenqi_account'); ?>" method="post">
				<table cellspacing="0" width="100%">
					<tbody>
					<tr>
						<td>
							<textarea id="fenqi_pay_account" style="width:99%;height:120px;padding:4px;" name="fenqi_pay_account" data-rule="required" data-msg="请输入分期付款账号"><?php echo isset($fenqi_pay_account)?$fenqi_pay_account:'';?></textarea>
						</td>
					</tr>
					</tbody>
				</table>
				<div class="onlineTime-ft">
					<input type="submit" class="ui-form-button ui-form-buttonBlue" value="保存设置" />
					<input type="hidden" name="save" value="yes" />
					<input type="hidden" name="section" value="pay_account" />
				</div>
			</form>
		</div>
	</div>
</div>
<br/><br/>
<div class="ui-box">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-head">
				<h2 class="ui-box-tit">分期活动属性默认设置</h2>
			</div>
			<form class="onlineTime" type="ajax" action="<?php echo site_url('setting/fenqi_account'); ?>" method="post">
				<table cellspacing="0" width="100%">
					<tbody>
					<tr>
						<td>
						<span>兑换券赠送比例</span>&nbsp;
						<input class="input" name="fenqi_exchange_scale" value="<?php echo $fenqi_exchange_scale;?>" style="width:80px; padding:2px 3px;"data-rule="required|number" data-msg="请输入兑换券赠送比例|必需是整数">
						<span>%</span><span style="margin-left:80px;">注：赠送给会员的对还款数额</span>
						</td>
					</tr>
					</tbody>
				</table>
				<div class="onlineTime-ft">
					<input type="submit" class="ui-form-button ui-form-buttonBlue" value="保存设置" />
					<input type="hidden" name="save" value="yes" />
					<input type="hidden" name="section" value="exchange_scale" />
				</div>
			</form>
		</div>
	</div>
</div>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>