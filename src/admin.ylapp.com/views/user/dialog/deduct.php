<div style="width:450px;">
	<form id="deductForm" action="<?php echo site_url('user/deduct')?>" method="post">
		<input name="deduct_submit" type="hidden" value="1">
		<input name="uid" type="hidden" value="<?php echo $seller_uid;?>">
        <input name="deposit_type" type="hidden" value="<?php echo $deposit_type;?>">
		<?php if($deduct_data['id'] > 0){?>
			<p style="padding: 10px 10px 0 10px;">上次扣款未成功，本次将继续上次扣款</p>
			<input name="continue_deduct_pay_id" type="hidden" value="<?php echo $deduct_data['id'];?>">
		<?php }?>
		<table cellpadding="3" cellspacing="1" class="ui-table2" style="margin:20px 0 20px 0;">
			<tr>
				<th width="150px">需扣除的诚信金额为：</th>
				<td>
					<input name="deduct_money"<?php if($deduct_data['id'] > 0){?> value="<?php echo $deduct_data['money'];?>" disabled="disabled"<?php }?> type="text" class="ui-form-text ui-form-textRed" data-rule="required|min(1)|max(<?php echo intval($deposit_info['money']);?>)" data-msg="*请输入需扣除的诚信金额|*需扣除的诚信金额最小值为1|*需扣除的诚信金额最大值为<?php echo $deposit_info['money'];?>">
					<p><span id="for_deduct_money" class="ui-table-statusR" style="font-weight: normal;"></span></p>
				</td>
			</tr>
			<tr>
				<th>商家诚信金余额为：</th>
				<td>
					<input id="last_deduct_money" type="text" class="ui-form-text ui-form-textRed" disabled="disabled" value="<?php echo $deposit_info['money'];?>">
				</td>
			</tr>
			<tr>
				<th>扣款理由：</th>
				<td>
					<textarea name="reason" class="ui-form-text" style="width: 250px;height: 60px" data-rule="required|minlength(20)|maxlength(100)" data-msg="*请输入扣款理由|*至少输入20个字符|*最多能输入100个字符"><?php if($deduct_data['id'] > 0){echo $deduct_data['remark'];}?></textarea>
					<p><span id="for_reason" class="ui-table-statusR" style="font-weight: normal;"></span></p>
				</td>
			</tr>
			<tr>
				<th>输入诚信金操作码：</th>
				<td>
					<input name="op_password" type="password" class="ui-form-text ui-form-textRed" data-rule="required|minlength(6)|maxlength(15)" data-msg="*请输入诚信金操作码|*操作码错误，请重新输入|*操作码错误，请重新输入">
					<p><span id="for_op_password" class="ui-table-statusR" style="font-weight: normal;"></span></p>
				</td>
			</tr>
		</table>
	</form>
	<script type="text/javascript">
		var user_deposit = '<?php echo intval($deposit_info['money']);?>';
		user_deposit = user_deposit == '' ? 0 : parseInt(user_deposit);
		var deduct_money_input = $('input[name=deduct_money]');
		$('input[name=deduct_money]').on('blur', function(){cac()});
		(function(){
			cac();
		})($);
		function cac(){
			var input_deposit = $('input[name=deduct_money]').val();
			if(input_deposit >= 1 && input_deposit <= user_deposit && user_deposit > 0){
				$('#last_deduct_money').val(user_deposit-input_deposit);
			}else{
				input_deposit =parseInt(input_deposit);
				input_deposit = isNaN(input_deposit) ? 0 : input_deposit;
				$('#last_deduct_money').val(input_deposit == 0 ? '<?php echo intval($deposit_info['money']);?>' : '0.00');
			}
		}
		(function($){
			/*
			 * 限制文本框字符
			 * $('input').limit(/[^0-9]/g)  //不允许输入非0-9的字符
			 * $('input').limit(/^[1-9][0-9]*$/, /^0|[^0-9]/g) //只允许输入大于0的正整数（第二参数是与第一参数相反的正则式）
			 */
			$.fn.limit = function(a,b){
				var f = function(){
					if(typeof b=="undefined"){
						a.test($(this).val()) && $(this).val($(this).val().replace(a ,""));
					}else{
						if(!a.test($(this).val())){
							var r = $(this).val().replace(b, "");
							(r!=$(this).val()) && $(this).val(r);
						}
					}
				};
				this.each(function(){
					if(this.tagName!="INPUT")return;
					if(this.addEventListener)
						this.addEventListener("input",f,false);
					else
						this.onpropertychange = f;
				});
			}
		})(jQuery); 
		$("input[name=deduct_money]").limit(/^[1-9][0-9]$/ , /^0|\..*|[^0-9]*/g);
	</script>
</div>