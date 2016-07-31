<div style="width:500px">
	<form action="<?php echo site_url('app_content_manager/add_advertisement');?>" method="post" enctype="multipart/form-data">
		<table cellpadding="3" cellspacing="1" class="ui-table2" style="margin:20px 0 20px 0;">
			<tr>
				<th width="100px">图片：</th>
				<td>
					  <input type="file" value="浏览" name="images" data-rule="required" data-msg="请址或选择上传图片" />
                     <span id="for_images" style="width:auto;"></span>
				</td>
			</tr>
			<tr>
				<th>标题：</th>
				<td>
					<input type="text" class="ui-form-text ui-form-textRed"  name="title"  size="40"  data-rule="required" data-msg="请输入标题">
                    <span id="for_title" style="width:auto;"></span>
				</td>
			</tr>
            <tr>
				<th>跳转类型：</th>
				<td>
					<select name="type" id="type">
                    <?php foreach($type as $k=>$v ):  ?>
					  <option value="<?php echo $k ?>"><?php echo $v ; ?></option>
                     <?php endforeach;  ?>
					</select>
				</td>
			</tr>
            
            	<tr id="goodsType"  style="display:none;">
							<th>类目：</th>
							<td>
								<select id="pid" name="pid">
									<option value="0">--请选择--</option>
								</select>
								<select id="cid" name="cid">
									<option value="0">--请选择--</option>
								</select>
								<input type="hidden" id="hidcid" value="0" />
                                <span id="for_pid" style="width:auto;"></span>
							</td>
						</tr>    
            <tbody id="value">
             <tr>
				<th>跳转值：</th>
				<td>
					<p>
					  <input type="text" class="ui-form-text ui-form-textRed"  name="value" size="40" >
					</p>
                    </td></tr>
                 <tr>
                 <td></td>
                 <td>   
				<p><span style="width:auto; color:#f00;" id="input_help"></span></p>
			</tr>
            </tbody>
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
				<th>排序：</th>
				<td>
					<input type="text" class="ui-form-text ui-form-textRed"  name="sort" size="10" >
				</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:center;padding-top:20px;">
                    <input type="hidden" value="<?php echo $differ; ?>" name="differ" />
					<input type="hidden" value="dopost" name="dopost" />
				</td>
			</tr>
		</table>
	</form>
</div>
<script>
// 输入跳转值的提示语
var input_map = {
		'1001' : '(输入url地址)',
		'1002' : '',
		'1003' : '',
		'1004' : '(输入活动id)',
		'1005' : '(输入专场id)',
		'1006' : '(输入活动标题)',
		'1007' : '',
		'1008' : '',
		'1010' : '(输入类目id)',
		'1011' : '(输入商家名称)'
};

$('#type').on('change',function(){ 
	 $changeval=$(this).find('option:selected').val();
	if( $changeval == '1001' || $changeval == '1006' || $changeval == '1004' || $changeval == '1005' || $changeval == '1011'){
	    $('#value').show();
	    $('#goodsType').hide();
	}else if($changeval == '1010'){
		$('#goodsType').show(); 
		$('#value').hide();
		var cid = $("#hidcid").val();
		var pid = $("#pid").val();
        initCategory(cid, pid);
	}else{
		$('#value').hide();
		$('#goodsType').hide();
	}
	$('#input_help').text(input_map[$changeval]);
});
var pid_arr = $.parseJSON('<?php if($pid_arr != ''){echo $pid_arr;}else{echo '[]';}?>');
var child_arr = $.parseJSON('<?php if($child_arr != ''){echo $child_arr;}else{echo '[]';}?>');
function initCategory(cid,pid){
	var pdata = "<option value='0'>--请选择--</option>";
	var cdata = pdata;
	for (var i in pid_arr){
	if(pid_arr[i].id != pid)
	    pdata += "<option value='" + pid_arr[i].id + "'>" + pid_arr[i].name + "</option>";
	else
     	pdata += "<option value='" + pid_arr[i].id + "' selected>" + pid_arr[i].name + "</option>";
	}
	$("#pid").html(pdata);
	if(pid > 0 && typeof child_arr['k_'+pid] != 'undefined'){
	if (child_arr['k_'+pid].length > 0){
	  var child_data = child_arr['k_'+pid];
	for (var i in child_data){
		if(child_data[i].id != cid)
			cdata += "<option value='" + child_data[i].id + "'>" + child_data[i].name + "</option>";
		else
			cdata += "<option value='" + child_data[i].id + "' selected>" + child_data[i].name + "</option>";
	    }
	  }
	}
	$("#cid").html(cdata);
}
$("#pid").change(function () {
var id = $("#pid").val();
var data = "<option value='0'>--请选择--</option>";
if(id!="0"){
	if (typeof child_arr['k_'+id] == 'object'){
		var child_data = child_arr['k_'+$("#pid").val()];
		for (var i in child_data){
			data += "<option value='" + child_data[i].id + "'>" + child_data[i].name + "</option>";
		}
	}
	$("#cid").html(data);
}else{
	$("#cid").html(data);
}
});
</script>