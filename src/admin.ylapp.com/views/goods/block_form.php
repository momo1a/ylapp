<form id="editForm" class="window_form" action="<?php echo site_url($this->router->class.'/'.$this->router->method)?>" method="post">
	<div class="h">
		<span style="width:130px;font-weight: bold;padding-bottom:5px;">屏蔽/解除屏蔽原因：</span>
		<div class="pingzhen clearfix">
			<textarea name="reason" style="width:360px;height:80px;" data-rule="required|maxlength(200)" data-msg="请输入屏蔽/解除屏蔽原因|屏蔽/解除屏蔽原因原因最多200个字符"></textarea>
		</div>
	</div>
	<input type="hidden" name="dosave" value="yes" />
	<input type="hidden" name="gid" value="<?php echo $gid;?>" />
</form>
<style type="text/css">
.window_form .clearfix span {
    float: none;
    text-align: left;
    width: auto;
}

</style>