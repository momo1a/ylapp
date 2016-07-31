<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>

<div class="ui-box autosheildSetting">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-body">
				<div class="ui-box ui-box2 autoShield">
					<div class="ui-box-head">
						<h2 class="ui-box-tit">自动屏蔽设置</h2>
					</div>
					<div class="ui-box-body">
						<form type="ajax" action="<?php echo site_url('setting/auto_shield'); ?>" method="post">
							<table cellspacing="0">
								<tbody>
								<tr>一、说明：当用户满足以下条件之一时，系统将对用户的帐号进行自动屏蔽。帐号被自动屏蔽后将无法抢购新活动。</tr>
								<tr>
									<td>1、连续7天内被审核<em>“订单号有误”</em>次数&gt;
									<input class="ui-form-text ui-form-textRed" name="order" type="text" value="<?php echo $order;?>" data-rule="number|range(0,100)" data-msg="输入数值必须为0-100的阿拉伯数字|输入数值必须为0-100的阿拉伯数字" />次。
									<span id="for_order" class="error"></span>
									</td>
								</tr>
								<tr>
									<td>2、1小时内抢购商品次数&gt;
									<input class="ui-form-text ui-form-textRed" name="purchase" type="text" value="<?php echo $purchase;?>" data-rule="number|range(0,1000)" data-msg="输入数值必须为0-1000的阿拉伯数字|输入数值必须为0-1000的阿拉伯数字"/>次。
									<span id="for_purchase" class="error"></span>
									</td>
								</tr>
								<tr>
									<td>3、买家账户连续7天内<em>被申述</em>次数&gt;
									<input class="ui-form-text ui-form-textRed" name="appeal" type="text" value="<?php echo $appeal;?>" data-rule="number|range(0,100)" data-msg="输入数值必须为0-100的阿拉伯数字|输入数值必须为0-100的阿拉伯数字"/>次。
									<span id="for_appeal" class="error"></span>
									</td>
								</tr>
								</tbody>
							</table>
							<table cellspacing="0" width="100%">
							     <tr>二、被自动屏蔽的帐号，大于
							     <input class="ui-form-text ui-form-textRed" name="lock" type="text" value="<?php echo $lock;?>" data-rule="number|range(0,1000)" data-msg="输入数值必须为0-1000的阿拉伯数字|输入数值必须为0-1000的阿拉伯数字"/>天不处理的，将被系统自动封号。
							     <span id="for_lock" class="error"></span>
							     </tr>
							</table>
							<div class="autoShield-ft">
								<input type="submit" class="ui-form-button ui-form-buttonBlue" value="保存设置" />
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- /ui-box -->

<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>