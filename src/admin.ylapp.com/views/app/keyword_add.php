<div style="width:500px">
  <form action="<?php echo site_url('app_content_manager/add')?>" method="post" class="window_form">	
	<table cellpadding="3" cellspacing="1" class="ui-table2" style="margin:20px 0 20px 0;">
		<tr>
			<th width="25%">关键字：</th>
			<td>
				<input type="text" class="ui-form-text ui-form-textRed"  name="keyword"  size="40"  data-rule="required" data-msg="请输入关键字">
			</td>
		</tr>
		<tr>
			<th>排序：</th>
			<td>
				<input type="text" class="ui-form-text ui-form-textRed"  name="sort_val"  size="40"  data-rule="required" data-msg="请输入0~99的数字">
			</td>
		</tr>
	</table>		
  </form>
</div>