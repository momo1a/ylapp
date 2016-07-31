<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<form class="onlineTime" type="ajax" action="<?php echo site_url('setting/online_time?action=online_time'); ?>" method="post">
				<table cellspacing="0">
					<tbody>
						<tr>
							<td>一、新活动每天上线时间为<input class="ui-form-text ui-form-textRed" type="text" value="<?php echo $goods_auto_online_time;?>" name="goods_auto_online_time" data-rule="number|range(1,24)" data-msg="只能输入数字|范围只能为1-24之间" />时 <span id="for_goods_auto_online_time" class="error"></span>
								<input type="hidden" name="goods_auto_online_time_remark" value="众划算每天上线时间" />
							</td>
						</tr>
						<tr>
							<td>二、抢购活动后，<em>“待填写订单号”</em>状态下<input class="ui-form-text ui-form-textRed" type="text" value="<?php echo $order_auto_clear_time_min;?>" name="order_auto_clear_time_min" data-rule="number|range(1,60)" data-msg="只能输入数字|范围只能为1-60之间"/>分钟后,自动清除名额。<span id="for_order_auto_clear_time_min" class="error"></span>
								<input type="hidden" name="order_auto_clear_time_min_remark" value="“待填写订单号”状态下X分钟后,自动清除报名信息,名额空出" />
							</td>
						</tr>
						<tr>
							<td>三、填写订单后，自动返现倒计时为<input class="ui-form-text ui-form-textRed" type="text" value="<?php echo $order_auto_checkout_time_day;?>" name="order_auto_checkout_time_day" data-rule="number|range(1,365)" data-msg="只能输入数字|范围只能为1-365之间" />天。 <span id="for_order_auto_checkout_time_day" class="error"></span>
								<input type="hidden" name="order_auto_checkout_time_day_remark" value="“已下单待返现”状态下X天,后自动返现" />
							</td>
						</tr>
						<tr>
							<td>四、抢购状态为<em>“订单号有误”</em>下，系统自动取消资格倒计时为<input class="ui-form-text ui-form-textRed" type="text" value="<?php echo $order_auto_close_time_day;?>" name="order_auto_close_time_day" data-rule="number|range(1,365)" data-msg="只能输入数字|范围只能为1-365之间" />天。<span id="for_order_auto_close_time_day" class="error"></span>
								<input type="hidden" name="order_auto_close_time_day_remark" value="商家审核买家的订单号为“订单号有误”后,如果买家X天未申诉也未修改订单号,则系统自动取消资格" />
							</td>
						</tr>
						<tr>
							<td>五、每天抢购次数大于<input class="ui-form-text ui-form-textRed" type="text" value="<?php echo $goods_today_buy_num;?>" name="goods_today_buy_num" data-rule="number|range(1,100)" data-msg="只能输入数字|范围只能为1-100之间" />次之后，再次抢购需要输入抢购验证码。<span style="color:gray;">（修改后第二天00:00后生效）</span><span id="for_goods_today_buy_num" class="error"></span>
								<input type="hidden" name="goods_today_buy_num_remark" value="用户每天抢购次数大于n，修改内容后，该功能第二天00：00起开始生效" />
							</td>
						</tr>
						<tr>
							<td>六、同一个抢购，抢购成功与填写订单号的时间间隔&lt;<input class="ui-form-text ui-form-textRed" type="text" value="<?php echo $order_fill_interval;?>" name="order_fill_interval" data-rule="number|range(1,60)" data-msg="只能输入数字|范围只能为1-60之间" />秒时，需要输入填单验证码。<span style="color:gray;">（修改后立即生效）</span><span id="for_order_fill_interval" class="error"></span>
								<input type="hidden" name="order_fill_interval_remark" value="同一个抢购，抢购成功与填写订单号的时间间隔秒数，修改后立即生效" />
							</td>
						</tr>
						<tr>
							<td>七、买家成功填写订单号以后，订单号在<input class="ui-form-text ui-form-textRed" type="text" value="<?php echo intval($order_hidden_time);?>" name="order_hidden_time" data-rule="number|range(0,72)" data-msg="只能输入数字|范围只能为1-72之间" />小时内处于匿名显示状态，0为无匿名显示时间。<span style="color:gray;">（修改后立即生效）</span><span id="for_order_hidden_time" class="error"></span>
								<input type="hidden" name="order_hidden_time_remark" value="所填订单号匿名显示时间，即填了单号以后多少个小时内订单号是匿名显示，0为无匿名显示时间" />
							</td>
						</tr>
						<tr>
							<td>八、发布/追加商品时间范围
								<input class="ui-form-text ui-form-textRed" type="text" value="<?php echo isset($goods_allow_day_min) ? intval($goods_allow_day_min) : '';?>" name="goods_allow_day_min" data-rule="number|range(3,14)" data-msg="最小天数只能输入数字|最小天数为3<=min<=14的整数" />-
								<input class="ui-form-text ui-form-textRed" type="text" value="<?php echo isset($goods_allow_day_max) ? intval($goods_allow_day_max) : '';?>" name="goods_allow_day_max" data-rule="number|range(4,15)" data-msg="最大天数只能输入数字|最大天数为4<=max<=15的整数" />天。<span style="color:gray;">（修改后立即生效）</span>
								<span id="for_goods_allow_day_min" class="error"></span>
								<span id="for_goods_allow_day_max" class="error"></span>
								<input type="hidden" name="goods_allow_day_min_remark" value="发布/追加商品时间最小天数" />
								<input type="hidden" name="goods_allow_day_max_remark" value="发布/追加商品时间最大天数" />
							</td>
						</tr>
					</tbody>
				</table>
				<div class="onlineTime-ft">
					<input type="submit" class="ui-form-button ui-form-buttonBlue" value="保存设置" />
					<input type="hidden" name="save" value="yes" />
				</div>
			</form>
		</div>
		
	</div>
</div><!-- /ui-box -->
<br/>
<!--设置新品上线分场-->
<div class="ui-box">
  <div class="ui-box-outer">
	<div class="ui-box-inner">
	 <div class="ui-box-head">
		<h2 class="ui-box-tit">最新上线分场设置 </h2>
		</div>
          <form type='ajax' action="<?php echo site_url('setting/online_time?action=new_parvial_field'); ?>" method="post">
          <div class="ui-box-body">
              <p>请选择需要分场的整点(<font color="#FF0000">1≤勾选数量≤5</font>):</p>
              <ul class="remindTime2-list clearfix">
                  <?php for($i=1; $i<=24; $i++): ?>
                  <?php $hour = str_pad($i, 2, '0', STR_PAD_LEFT).':00';  
                        $goods_new_parvial_field_not=$goods_new_parvial_field_not ? $goods_new_parvial_field_not:array();
                  ?>
                  <li><label><input type="checkbox" name="goods_new_parvial_field_not[]" value="<?php echo $hour;?>" <?php if(in_array($hour, $goods_new_parvial_field_not)): echo 'checked="checked"';endif;?>/>&nbsp;<?php echo $hour;?></label></li>
                  <?php endfor;?>
              </ul><!-- / -->
              <div class="remindTime2-ft">
                  <input type="submit" class="ui-form-button ui-form-buttonBlue" value="保存设置">
                  <input type="hidden" name="goods_new_parvial_field_not_remark" value="用于存储未生效的最新上线分场场次时间修改" />
                  <input type="hidden" name="save" value="yes" />
                  (<font color="#FF0000">修改后,第二天 0:00 生效</font>)
              </div>
          </div>
          </form>
      </div>
    </div>
</div>
<br/>
<!-- 邮箱配置表单 -->
<div class="ui-box">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-head">
				<h2 class="ui-box-tit">官方邮箱配置：</h2>
			</div>
			<form class="onlineTime" type="ajax" action="<?php echo site_url('setting/online_time?action=mail'); ?>" method="post">
				<table cellspacing="0">
					<tbody>
						<tr>
							<td>
								smtp地址：<input class="ui-form-text ui-form-textRed" style="width:160px;" type="text" value="<?php echo $smtp_host;?>" name="smtp_host" /> <span id="for_smtp_host" class="error"></span>
								<input type="hidden" name="smtp_host_remark" value="smtp地址" />
							</td>
						</tr>
						<tr>
							<td>
								官方邮箱：<input class="ui-form-text ui-form-textRed" style="width:160px;" type="text" value="<?php echo $smtp_user;?>" name="smtp_user" /> <span id="for_smtp_user" class="error"></span>
								<input type="hidden" name="smtp_user_remark" value="官方邮箱" />
							</td>
						</tr>
						<tr>
							<td>
								邮箱密码：<input class="ui-form-text ui-form-textRed" style="width:160px;" type="password" value="<?php echo $smtp_pass;?>" name="smtp_pass" /> <span id="for_smtp_pass" class="error"></span>
								<input type="hidden" name="smtp_pass_remark" value="邮箱密码" />
							</td>
						</tr>
						<tr>
						<td>免费试用链接：<input class="ui-form-text ui-form-textRed" style="width:160px;" type="text" value="<?php echo $trial_goods_url;?>" name="trial_goods_url" /> <span id="for_trial_goods_url" class="error"></span>
							<input type="hidden" name="trial_goods_url_remark" value="免费试用链接地址" />
						</td></tr>
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
<!-- End 邮箱配置表单 -->
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>