<div style="width:600px">
	<form action="<?php echo site_url('app_content_manager/add_special');?>" method="post" enctype="multipart/form-data">
		<table cellpadding="3" cellspacing="1" class="ui-table2" style="margin:20px 0 20px 0;">
			<tr >
				<th  width="130px">专场标题：</th>
				<td>
					<input type="text" class="ui-form-text ui-form-textRed"  name="title"  size="40"  data-rule="required" data-msg="请输入标题">
                    <span id="for_title" style="width:auto;"></span>
				</td>
			</tr>
              <tr>
				<th width="130px">专场广告图片：</th>
				<td>
					  <input type="file" value="浏览" name="img" data-rule="required" data-msg="请址或选择上传图片" />
            <span id="for_img" style="width:auto;"></span> 
				</td>
			</tr>
            <tr>
				<th width="130px">主打商品1：</th>
				<td>
        <input type="text" class="ui-form-text ui-form-textRed"  name="gid1"  size="10"  data-rule="required" data-msg="请输入主打商品1">
                   <span id="for_gid1" style="width:auto;"></span>
				</td>
			</tr>
            <tr>
				<th width="130px">主打商品2：</th>
				<td>
                  <input type="text" class="ui-form-text ui-form-textRed"  name="gid2"  size="10"  data-rule="required" data-msg="请输入主打商品2">
                  <span id="for_gid2" style="width:auto;"></span>
				</td>
			</tr>
            <tr>
				<th width="130px">主打商品3：</th>
				<td>
                   <input type="text" class="ui-form-text ui-form-textRed"  name="gid3"  size="10"  data-rule="required" data-msg="请输入主打商品3">
               <span id="for_gid3" style="width:auto;"></span>
				</td>
			</tr>
            <tr>
				<th>状态：</th>
				<td>
					<select name="enable" id="enable">
					  <option value="1">在用</option>
					  <option value="2">停用</option>
					</select>
				</td>
			</tr>
			 <tr>
				<th>专场ID：</th>
				<td>
					<input type="text" class="ui-form-text ui-form-textRed"  name="special_id" size="10"  data-rule="required" data-msg="请输入专场ID">
                      <span id="for_special_id" style="width:auto;"></span>
				</td>
			</tr>
			<tr>
				<th>排序：</th>
				<td>
					<input type="text" class="ui-form-text ui-form-textRed"  name="sort" size="10" >
				</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:center;padding-top:20px;">
					<input type="hidden" value="addpost" name="addpost" />
				</td>
			</tr>
		</table>
	</form>
</div>