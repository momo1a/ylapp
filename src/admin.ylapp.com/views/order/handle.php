<form id="editForm" class="window_form" type="ajax" action="<?php echo site_url('order/disqualification')?>" method="post"  >
	<div class="h">
		<span style="width:80px;font-weight: bold;padding-bottom:5px;">取消原因：</span>
		<div class="pingzhen clearfix">
			<textarea name="msg"  data-rule="required|maxlength(200)" data-msg="请输入取消原因最多200个字符"></textarea>
		</div>
	</div>
	<input type="hidden" name="oid" value="<?php echo $oid; ?>" />
</form>
<style type="text/css">
.window_form .clearfix span {
    float: none;
    text-align: left;
    width: auto;
}

</style>