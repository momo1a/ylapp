<form id="cancelForm" action="<?php echo site_url($this->router->class.'/'.$this->router->method)?>" method="post">
	<div class="h" style="padding: 20px 20px 0 20px;">
		您确定要取消会员(<?php echo $order['buyer_uname']?>)的抢购资格吗？
	</div>
	<div class="h" style="padding: 20px;">
		<label> * 理由：<input name="reason" class="ui-form-text" data-rule="required" data-msg="请输入取消资格的原因" /></label>
	</div>
	<input type="hidden" name="submit" value="1" />
	<input type="hidden" name="oid" value="<?php echo $oid;?>" />
</form>