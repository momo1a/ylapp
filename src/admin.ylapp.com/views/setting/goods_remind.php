<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box remindSetting">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
		
			<div class="ui-box-body">
				<div class="ui-box ui-box2 remindTime">
					<div class="ui-box-head">
						<h2 class="ui-box-tit">收藏提醒时间设置</h2>
					</div>
					<div class="ui-box-body">
						<form type="ajax" action="<?php echo site_url('setting/goods_remind'); ?>" method="post">
							<table cellspacing="0">
								<tbody>
									<tr>
										<td>众划算收藏提醒时间<em>“活动上线前”</em><input class="ui-form-text ui-form-textRed" type="text" value="<?php echo $goods_collect_online_remind_prefix_time;?>" name="goods_online_remind_prefix_time" data-rule="number|range(1,15)" data-msg="输入数值必须为1-15的阿拉伯数字|输入数值必须为1-15的阿拉伯数字" />分钟 <span id="for_goods_online_remind_prefix_time" class="error"></span>
											<input type="hidden" name="goods_online_remind_prefix_time_remark" value="众划算收藏提醒时间活动上线前多少分钟" />
										</td></tr>
									<tr>
										<td>众划算收藏提醒时间<em>“追加上线前”</em><input class="ui-form-text ui-form-textRed" type="text" value="<?php echo $goods_collect_addition_remind_prefix_time;?>" name="goods_addition_remind_prefix_time" data-rule="number|range(1,15)" data-msg="输入数值必须为1-15的阿拉伯数字|输入数值必须为1-15的阿拉伯数字"/>分钟  <span id="for_goods_addition_remind_prefix_time" class="error"></span>
											<input type="hidden" name="goods_addition_remind_prefix_time_remark" value="众划算收藏提醒时间追加上线前多少分钟" />
										</td></tr>
								</tbody>
							</table>
							<div class="remindTime-ft">
								<input type="submit" class="ui-form-button ui-form-buttonBlue" value="保存设置" />
								<input type="hidden" name="section" value="prefix" />
								<input type="hidden" name="save" value="yes" />
							</div>
						</form>
					</div>
				</div>
			</div>
		 
			<div class="ui-box ui-box2 remindTime2">
				<div class="ui-box-head">
					<h2 class="ui-box-tit">追加整点上线设置</h2>
				</div>
				<form type="ajax" action="<?php echo site_url('setting/goods_remind'); ?>" method="post">
				<div class="ui-box-body">
					<p>请选择要设置追加上线的整点:</p>
					<ul class="remindTime2-list clearfix">
						<?php for($i=1; $i<=24; $i++): ?>
						<?php $hour = str_pad($i, 2, '0', STR_PAD_LEFT).':00'; ?>
						<li><label><input type="checkbox" name="goods_remind_hours[]" value="<?php echo $hour;?>" <?php if(in_array($hour, $goods_remind_hours)): echo 'checked="checked"';endif;?>/>&nbsp;<?php echo $hour;?></label></li>
						<?php endfor;?>
					</ul><!-- / -->
					<div class="remindTime2-ft">
						<input type="submit" class="ui-form-button ui-form-buttonBlue" value="保存设置">
								<input type="hidden" name="section" value="hours" />
								<input type="hidden" name="save" value="yes" />
					</div>
				</div>
				</form>
			</div><!-- /ui-box -->
		</div>
	</div>
</div><!-- /ui-box -->

<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>