<div style="width:50px">
	<form action="<?php echo site_url('cash/cancel_pay') ?>" method="post" style="margin:15px 0 15px 0;">
		<input type="hidden" value="is_post" name="is_post" />
		<input name="id" type="hidden" value="<?php echo $id;?>" />
		<table class="ui-table2">
			
				<th width="80px">撤销付款？</th>
				
			
		</table>
	</form>
</div>