<div style="width:850px">
	<form action="<?php echo site_url('cash/edit_bath_send') ?>" method="post">
		<table cellpadding="3" cellspacing="1" class="ui-table2" style="margin:10px 0 10px 0;">
			<tr>
			<th></th>
				<td>
            <div style="margin: 0px 0px 0 -370px;">
<input type="checkbox" name="cash[is_reg_time]" value="1" <?php if($cash_info['is_reg_time']==1)echo "checked=checked";?>>注册时间 <input id="reg_start_time" data-maxdate="#F{$dp.$D('reg_end_time')}" class="ui-form-text ui-form-textGray ui-form-textDatetime" data-datefmt="yyyy-MM-dd HH:mm:ss" type="text" readonly data-rule="date"  value="<?php echo $cash_info['reg_start_time']==0?'':date('Y-m-d H:i:s',$cash_info['reg_start_time']);?>" name="cash[reg_start_time]">
					<input id="reg_end_time" data-mindate="#F{$dp.$D('reg_start_time')}" class="ui-form-text ui-form-textGray ui-form-textDatetime" data-datefmt="yyyy-MM-dd HH:mm:ss" type="text" readonly data-rule="date" value="<?php echo $cash_info['reg_end_time']==0?'':date('Y-m-d H:i:s',$cash_info['reg_end_time']);?>"  name="cash[reg_end_time]"><br/>
<input type="checkbox" name="cash[is_phone_reg]" <?php if($cash_info['is_phone_reg']==1)echo "checked=checked";?> value="1">手机注册<br/>
					<input type="checkbox" name="cash[is_last_order_time]" <?php if($cash_info['is_last_order_time']==1)echo "checked=checked";?> value="1">
					<input id="last_order_start_time" data-maxdate="#F{$dp.$D('last_order_end_time')}" class="ui-form-text ui-form-textGray ui-form-textDatetime" data-datefmt="yyyy-MM-dd HH:mm:ss" type="text" readonly data-rule="date"  value="<?php echo $cash_info['last_order_start_time']==0?'':date('Y-m-d H:i:s',$cash_info['last_order_start_time']);?>" name="cash[last_order_start_time]">≤最后一次抢购时间 ≤ <input id="last_order_end_time" data-mindate="#F{$dp.$D('last_order_start_time')}" class="ui-form-text ui-form-textGray ui-form-textDatetime" data-datefmt="yyyy-MM-dd HH:mm:ss" type="text" readonly data-rule="date"  value="<?php echo $cash_info['last_order_end_time']==0?'':date('Y-m-d H:i:s',$cash_info['last_order_end_time']);?>" name="cash[last_order_end_time]"><br/>
					
					<input type="checkbox" <?php if($cash_info['is_sum_price']==1)echo "checked=checked";?> name="cash[is_sum_price]" value="1">
					<input id="sum_price_start_time" data-maxdate="#F{$dp.$D('sum_price_end_time')}" class="ui-form-text ui-form-textGray ui-form-textDatetime" data-datefmt="yyyy-MM-dd HH:mm:ss" type="text" readonly  value="<?php echo $cash_info['sum_price_start_time']==0?'':date('Y-m-d H:i:s',$cash_info['sum_price_start_time']);?>" name="cash[sum_price_start_time]"> - 
					<input id="sum_price_end_time" data-mindate="#F{$dp.$D('sum_price_start_time')}" class="ui-form-text ui-form-textGray ui-form-textDatetime" data-datefmt="yyyy-MM-dd HH:mm:ss" type="text" readonly value="<?php echo $cash_info['sum_price_end_time']==0?'':date('Y-m-d H:i:s',$cash_info['sum_price_end_time']);?>"  name="cash[sum_price_end_time]">网购价总额  <select name="cash[sum_price_or]"><option value="1" <?php echo $cash_info['sum_price_or']==1?'selected="selected"':'';?>>大于</option><option value="0" <?php echo $cash_info['sum_price_or']==0?'selected="selected"':'';?>>小于</option></select> <input class="ui-form-text ui-form-textRed" size="10" name="cash[send_sum_price]" data-rule="number" value="<?php echo $cash_info['send_sum_price']==0?'':$cash_info['send_sum_price'];?>"/>元（仅统计抢购状态“已完成”的订单）<br/>
					
					<input type="checkbox" name="cash[is_sum_cost_price]" <?php if($cash_info['is_sum_cost_price']==1)echo "checked=checked";?> value="1">
					<input id="sum_cost_price_start_time" data-maxdate="#F{$dp.$D('sum_cost_price_end_time')}" class="ui-form-text ui-form-textGray ui-form-textDatetime" data-datefmt="yyyy-MM-dd HH:mm:ss" type="text" readonly  value="<?php echo $cash_info['sum_cost_price_start_time']==0?'':date('Y-m-d H:i:s',$cash_info['sum_cost_price_start_time']);?>" name="cash[sum_cost_price_start_time]"> - 
					<input id="sum_cost_price_end_time" data-mindate="#F{$dp.$D('sum_cost_price_start_time')}" class="ui-form-text ui-form-textGray ui-form-textDatetime" data-datefmt="yyyy-MM-dd HH:mm:ss" type="text" readonly value="<?php echo $cash_info['sum_cost_price_end_time']==0?'':date('Y-m-d H:i:s',$cash_info['sum_cost_price_end_time']);?>"  name="cash[sum_cost_price_end_time]">活动价总额  <select name="cash[sum_cost_price_or]"><option value="1" <?php echo $cash_info['sum_cost_price_or']==1?'selected="selected"':'';?>>大于</option><option value="0" <?php echo $cash_info['sum_cost_price_or']==0?'selected="selected"':'';?>>小于</option></select> 
					<input class="ui-form-text ui-form-textRed" size="10" name="cash[send_sum_cost_price]" data-rule="number" value="<?php echo $cash_info['send_sum_cost_price']==0?'':$cash_info['send_sum_cost_price'];?>"/>元（仅统计抢购状态“已完成”的订单）<br/>
					
					<input type="checkbox" name="cash[is_sum_rebate]" <?php if($cash_info['is_sum_rebate']==1)echo "checked=checked";?> value="1">
					<input id="sum_rebate_start_time" data-maxdate="#F{$dp.$D('sum_rebate_end_time')}" class="ui-form-text ui-form-textGray ui-form-textDatetime" data-datefmt="yyyy-MM-dd HH:mm:ss" type="text" readonly  value="<?php echo $cash_info['sum_rebate_start_time']==0?'':date('Y-m-d H:i:s',$cash_info['sum_rebate_start_time']);?>" name="cash[sum_rebate_start_time]"> - 
					<input id="sum_rebate_end_time" data-mindate="#F{$dp.$D('sum_rebate_start_time')}" class="ui-form-text ui-form-textGray ui-form-textDatetime" data-datefmt="yyyy-MM-dd HH:mm:ss" type="text" readonly value="<?php echo $cash_info['sum_rebate_end_time']==0?'':date('Y-m-d H:i:s',$cash_info['sum_rebate_end_time']);?>"  name="cash[sum_rebate_end_time]">已返现总额 <select name="cash[sum_rebate_or]"><option value="1" <?php echo $cash_info['sum_rebate_or']==1?'selected="selected"':'';?>>大于</option><option value="0" <?php echo $cash_info['sum_rebate_or']==0?'selected="selected"':'';?>>小于</option></select> 
					<input class="ui-form-text ui-form-textRed" size="10" name="cash[send_sum_rebate]" data-rule="number" value="<?php echo $cash_info['send_sum_rebate']==0?'':$cash_info['send_sum_rebate'];?>"/>元（仅统计抢购状态“已完成”的订单）<br/>
					<div style="text-align:center;padding-top:5px;color:red; margin-left: -115px;">
					提示：每张现金券类型，对应一种批量发放条件。（保存后，该现金券类型批量发放过的现金券“批量发放条件"会一起修改）
					</div>
              </div>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:center;padding-top:5px;">
					<input type="hidden" value="is_post" name="is_post" />
					<input type="hidden" value="<?php echo $cid?>" name="cid" />
				</td>
			</tr>
		</table>
	</form>
</div>