<div style="width:700px">
	<form action="<?php echo site_url('cash/add_cash_type/') ?>" method="post">
		<table cellpadding="3" cellspacing="1" class="ui-table2" style="margin:10px 0 10px 0;">
			<tr>
				<th width="150px">现金券类型:</th>
				<td>
					<input class="ui-form-text ui-form-textRed" size="50" name="cash[cname]" data-rule="required|minlength(2)|maxlength(25)|outspecial" data-msg="*请输入现金券类型|*至少输入2个字|*最多能输入25个字|不能包含特殊字符" />
					<span id="for_cash_cname_">最多25个字，不能输入特殊字符</span>
				</td>
			</tr>
			<tr>
				<th width="150px">标题:</th>
				<td>
					<input class="ui-form-text ui-form-textRed" size="20" name="cash[ctitle]" data-rule="required|minlength(2)|maxlength(10)|outspecial" data-msg="*请输入现金券标题|*至少输入2个字|*最多能输入10个字|不能包含特殊字符" />
					<span id="for_cash_ctitle_">最多10个字，不能输入特殊字符（用户前台显示）
					</span>
				</td>
			</tr>
			<tr>
				<th>面额:</th>
				<td>
					<input class="ui-form-text ui-form-textRed" size="10" name="cash[cprice]" data-rule="required|float" data-msg="*请输入现金券面额|*请输入正确的面额值"/> 元  <span id="for_cash_cprice_" style="color: red; display: inline;"></span>
				</td>
			</tr>
			<tr><th>使用条件:</th></tr>
			<tr>
			<th></th>
			<td>
            <div class="J_CashCheckbox">
					<p style="padding: 3px 0;"><input type="checkbox" name="cash[not_limit]" value="1">不需要使用条件</p>
					<p style="padding: 3px 0;">
						<input type="checkbox" name="cash[is_time_limit]" value="1">抢购时间属于<input id="cash_time_limit_start_time" class="ui-form-text ui-form-textGray ui-form-textDatetime" data-datefmt="yyyy-MM-dd HH:mm:ss" data-maxdate="#F{$dp.$D('cash_time_limit_end_time')}" type="text" readonly data-rule="date"  value="" name="cash[time_limit_start_time]">
						<input id="cash_time_limit_end_time" class="ui-form-text ui-form-textGray ui-form-textDatetime" data-datefmt="yyyy-MM-dd HH:mm:ss" data-mindate="#F{$dp.$D('cash_time_limit_start_time')}" type="text" readonly data-rule="date" value=""  name="cash[time_limit_end_time]">
					</p>
					<p style="padding: 3px 0;"><input type="checkbox" name="cash[is_phone]" value="1">使用客户端抢购</p>
					<p style="padding: 3px 0;">
						<input type="checkbox" name="cash[is_category]" value="1">商品限制为 
						<select name="cash[category_id]">
						<?php if(is_array($goods_category)): foreach ($goods_category as  $item):?>
	                		<option value="<?php echo $item['id'];?>"><?php echo $item['name'];?></option>
						<?php endforeach; endif;?>
			            </select>
			        </p>
					<p style="padding: 3px 0;">
						<input type="checkbox" name="cash[is_sum_price]" value="1">网购价总额满 <input class="ui-form-text ui-form-textRed" size="10" name="cash[sum_price]"/>元（抢购状态“已完成”时统计）
					</p>
					<p style="padding: 3px 0;">
						<input type="checkbox" name="cash[is_sum_cost_price]" value="1">活动价总额满 <input class="ui-form-text ui-form-textRed" size="10" name="cash[sum_cost_price]"/>元（抢购状态“已完成”时统计）
					</p>
					<p style="padding: 3px 0;">
						<input type="checkbox"  name="cash[is_sum_rebate]" value="1">已返现总额满 <input class="ui-form-text ui-form-textRed" size="10" name="cash[sum_rebate]"/>元（抢购状态“已完成”时统计）
					</p>
              </div>
			</td>
			</tr>
			<tr>
				<th>有效期:</th>
				<td>
			<input id="cash_valid_start_time" class="ui-form-text ui-form-textGray ui-form-textDatetime" data-datefmt="yyyy-MM-dd HH:mm:ss" data-maxdate="#F{$dp.$D('cash_valid_end_time')}" type="text" readonly  value="" name="cash[valid_start_time]" data-rule="required|date" data-msg="请选择起始时间|格式错误">
			<input id="cash_valid_end_time" class="ui-form-text ui-form-textGray ui-form-textDatetime" data-datefmt="yyyy-MM-dd HH:mm:ss" data-mindate="#F{$dp.$D('cash_valid_start_time')}" type="text" readonly value=""  name="cash[valid_end_time]" data-rule="required|date" data-msg="请选择结束时间|格式错误">
				</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:center;padding-top:20px;">
					<input type="hidden" value="is_post" name="is_post" />
				</td>
			</tr>
		</table>
	</form>
</div>
<script>
!function(){
	var $cc   = $(".J_CashCheckbox :checkbox"),
		$eq0  = $cc.eq(0),
		$neq0 = $cc.slice(1),
		$last3= $cc.slice(-3);
	$eq0.click(function() {
		if(this.checked){
			$neq0.prop('checked', false).prop('disabled', true);
		}else{
			$neq0.prop('disabled', false);
		}
	});
	$neq0.click(function(){
		var p=false;
		$neq0.each(function(){
			if(this.checked){
				p=true;
			}
		});
		$eq0.prop('checked', false).prop('disabled', p);
	});
	$last3.click(function(){
		if(this.checked){
			$last3.not(this).prop('checked', false).prop('disabled', true);
		}else{
			$last3.prop('disabled', false);
		}
	})
}();
</script>