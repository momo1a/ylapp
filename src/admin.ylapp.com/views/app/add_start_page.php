<div style="width:500px">
	<form action="<?php echo site_url('app_content_manager/add_start_page');?>" method="post" enctype="multipart/form-data">
	<input type="hidden" name='addpost' value='addpost'/>
		<table cellpadding="3" cellspacing="1" class="ui-table2" style="margin:20px 0 20px 0;">
			<tr>
				<th width=25%>启动页标题：</th>
				<td>
					<input type="text" class="ui-form-text ui-form-textRed"  name="title"  size="40"  data-rule="required" data-msg="请输入标题">
                    <span id="for_title" style="width:auto;"></span>
				</td>
			</tr>
			<tr>
				<th width="100px">启动页图片：</th>
				<td>
					  <input type="file" value="浏览" name="images" data-rule="required" data-msg="请选择上传图片" />
                     <span id="for_images" style="width:auto;"></span>
				</td>
			</tr>			
		</table>
	</form>
</div>
