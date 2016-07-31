
	<form id="handleForm" action="" type="ajax" method="post" class="window_form" callback="reload">
    
        <div class="h">
			<h3>批量处理申诉</h3>
			<span>处理的申诉编号：</span>
			<div class="pingzhen clearfix">
				<textarea name="id" data-rule="required" data-msg="请填写处理申诉的id" style="width:360px;height:60px;"><?php  print_r($appeal_ids);?></textarea><br />
				<span id="for_id" style="width: auto;text-align:left;"></span>
			</div>
		</div>
		<div class="h">
			<h3>管理员处理申诉</h3>
			<span>处理结果：</span>
			<div class="pingzhen clearfix">
				<textarea name="result" data-rule="required" data-msg="请填写处理结束" style="width:360px;height:60px;"></textarea><br />
				<span id="for_result" style="width: auto;text-align:left;"></span>
			</div>
		</div>
		<div class="h">
			<span>处理类型：</span>
			<div id="action_type" style="display:block;float:left;width:360px;">
				<label data-action="<?php echo site_url('appeal/close');?>" onclick="checked_type(this)"><input type="radio" name="act_type" value="close" data-rule="required" data-msg="请选择处理类型" />恢复资格</label>&nbsp;
				<label data-action="<?php echo site_url('appeal/disqualification');?>" onclick="checked_type(this)"><input type="radio" name="act_type" value="cancel" />取消资格</label>&nbsp;
				<label data-action="<?php echo site_url('appeal/increase_deadline');?>" onclick="checked_type(this)"><input type="radio" name="act_type" value="addtime" />增加返现时间</label>&nbsp;
				<label data-action="<?php echo site_url('appeal/adjust_rebate');?>" onclick="checked_type(this)"><input type="radio" name="act_type" value="adjust_rebate"/>调整返现金额</label><br />
				<label data-action="<?php echo site_url('appeal/checkout');?>" onclick="checked_type(this)"><input type="radio" name="act_type" value="checkout" />直接返现</label>&nbsp;
				<label data-action="<?php echo site_url('appeal/tradeno_error');?>" onclick="checked_type(this)"><input type="radio" name="act_type" value="tradeno_error"/>订单号有误</label>
                <label data-action="<?php echo site_url('appeal/tradeno_correct');?>" onclick="checked_type(this)"><input type="radio" name="act_type" value="tradeno_correct"/>订单号正确</label>
				<label data-action="<?php echo site_url('appeal/adjust_tradeno');?>" onclick="checked_type(this)"><input type="radio" name="act_type" value="adjust_tradeno"/>填写订单号</label><br />
				<span id="for_act_type" style="width: auto;text-align:left;"></span>
			</div>
		</div>
		<div class="h extinput_box" id="addtimebox" style="display:none;">
			<span>增加天数：</span>
			<div class="pingzhen clearfix">
				<span style="width: auto;"><input size="3" style="display: inline;" name="days" prefix="return $('input[name=\'act_type\']:checked').val()=='addtime'" data-rule="required|number|range(1,21)" data-msg="请填写增加返现天数|只能填写数字|请填写1-21的范围"/> 天</span>
				<span id="for_days" style="width: auto;text-align:left;"></span>
			</div>
		</div>
		<div class="h extinput_box" id="adjust_rebate_box" style="display:none;">
			<span>调整金额：</span>
			<div class="pingzhen clearfix">
				<span style="width: auto;"><input width="172" height="25" style="display: inline;" name="amount" prefix="return $('input[name=\'act_type\']:checked').val()=='adjust_rebate'" data-rule="required|amount" data-msg="请填写调整金额|金额只能为'+'、'-'数字或两位小数"/> 元</span>
				<span id="for_amount" style="width: auto;text-align:left;"></span>
				<br /><span style="color:#949494; width:35em; text-align:left;">调整返现金额说明：正数的时候，是从商家担保金扣除相应金额返回给买家；负数的时候，是从买家返现金额中扣除相应金额返回给商家。</span>
			</div>
		</div>
		
		<div class="h extinput_box" id="adjust_tradeno_box" style="display:none;">
			<span>填写订单号：</span>
			<div class="pingzhen clearfix">
				<span style="width: auto;"><input size="35" style="display: inline;" name="tradeno" prefix="return $('input[name=\'act_type\']:checked').val()=='adjust_tradeno'" data-rule="required" data-msg="请填写订单号"/></span>
				<span id="for_tradeno" style="width: auto;text-align:left;"></span>
			</div>
		</div>
		<input type="hidden" value="yes" name="dosave" />
		
	</form>
