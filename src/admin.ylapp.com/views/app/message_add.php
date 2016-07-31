<div style="width:600px">
	<form action="<?php echo site_url('app_message_push/add')?>" method="post" class="window_form">	
	<table cellpadding="3" cellspacing="1" class="ui-table2" style="margin:20px 0 20px 0;">
			<tr>
				<th width="20%">标题：</th>
				<td>
					<input type="text" class="ui-form-text ui-form-textRed"  name="title"  size="40"  data-rule="required" data-msg="请输入标题">
					<span id="for_title" style="float: none"></span>
				</td>
			</tr>
			<tr>
				<th>推送内容：</th>
				<td>
					<textarea style="width:360px;height:60px;" data-msg="请输入推送内容" data-rule="required" name="content"></textarea>
					<span id="for_content" style="float: none"></span>
				</td>
			</tr>
			<tr>
				<th>推送平台：</th>
				<td>
				<select name="client_type">
				<option value="<?php echo App_push_message_model::CLIENT_TYPE_ALL;?>">Android+iOS</option>
				<option value="<?php echo App_push_message_model::CLIENT_TYPE_IOS;?>">iOS</option>
				<option value="<?php echo App_push_message_model::CLIENT_TYPE_ANDROID;?>">Android</option>
				</select>
				</td>
			</tr>
			<tr>
				<th>自动推送时间：</th>
				<td>
					<input class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly  value="<?php echo $this->input->get_post('startTime');?>" name="push_time" data-datefmt="yyyy-MM-dd HH:mm:ss" title="开始推送时间" data-msg="请选择推送时间" data-rule="required">
					<span id="for_push_time" style="float: none"></span>
				</td>
			</tr>		
	</form>
</div>