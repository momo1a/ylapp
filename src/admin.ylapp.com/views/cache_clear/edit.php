<script type="text/javascript">
	$("#editForm").find("#cat_name, #url, #mark").on("blur", function(){
		//类别名称校验
		if ($(this).is("#cat_name")) {
			if ($(this).val() == "") {
				$("#for_cat_name").show().html("请输入类别名称").css("color", "red").addClass("error");
			} else {
				$("#for_cat_name").hide().removeClass("error");
			}
		};

		//地址校验
		if ($(this).is("#url")) {
			if ($(this).val() == "") {
				$("#for_url").show().html("请输入地址").css("color", "red").addClass("error");
			} else {
				var url = "<?php echo site_url('cache_clear/ajax_check_url/'); ?>";
				var url_val = $(this).val();
				$.ajax({
					url: url,
		            type: 'POST',
		            dataType: 'json',
		            data: {url:url_val},
		            success: function(data){
		            	if (data.state == true) {
		            		$("#for_url").hide().removeClass("error");
		            	} else {
		            		$("#for_url").show().html(data.msg).css("color", "red").addClass("error");
		            	}
		            }
				});
			}
		};
		//校验备注
		if ($(this).is("#mark")) {
			if ($(this).val() == "") {
				$("#for_mark").html("请输入备注").css("color", "red").addClass("error");
			} else {
				$("#for_mark").hide().removeClass("error");
			}
		};
	});
	$("#editForm").submit(function(){
		alert($(".error").length);
		if ($(".error").length > 0) {
			return false;
		} else {
			return true;
		}
	});
</script>
<div style="width:580px">
	<form id="editForm" class="window_form" method="post" action="<?php echo site_url('cache_clear/cache_edit/'.$id);?>">
		<table cellpadding="3" cellspacing="1" class="ui-table2" style="margin:20px 0 20px 0;">
			<tr>
				<th width="20%">类别名称：</th>
				<td>
					<?php echo form_dropdown('cid',$select_data,$default_id)?>
					<span id="for_cat_name" style="width:auto; float:none;"></span>
				</td>
			</tr>
			<tr>
				<th>地址：</th>
				<td>
					<input type="text" id="url" class="ui-form-text ui-form-textRed"  name="url"  size="40" maxlength="500"  value="<?php echo $url;?>" data-rule="required" data-msg="请输入地址">
					<span class="num_tip" style="float:none">&nbsp;</span>
					<span id="for_url" style="width:auto; float:none;"></span>
				</td>
			</tr>
			<tr>
				<th>是否启用：</th>
				<td>
					是  <input type="radio"  name="state" value="1" <?php if($state) echo 'checked="checked"';?>>
					否  <input type="radio"  name="state" value="0" <?php if(!$state) echo 'checked="checked"';?>>
				</td>
			</tr>
			<tr>
				<th>备注：</th>
				<td>
					<input type="text" id="mark" class="ui-form-text ui-form-textRed"  value="<?php echo $mark;?>"  name="mark"  size="40" maxlength="500"   data-rule="required" data-msg="请输入备注">
					<span class="num_tip" style="float:none">&nbsp;</span>
					<span id="for_mark" style="width:auto; float:none;"></span>
				</td>
			</tr>
		</table>
	</form>
</div>
<script>
	$(function(){
		$(".num_tip").each(function(){
			var _this=$(this),
			maxl = _this.prev().attr("maxlength");
			len = _this.prev().attr("value").length;
			_this.text(len +"/"+maxl);
		});
		$("#cat_name,#url,#mark").on("input keyup",function(){
			var $input=$(this);
			var len=$input.val().length,
			init_size=$input.attr("maxlength");
			$input.next(".num_tip").text(len+"/"+init_size);
			$input.on("input keyup",function(){
			});
		});
	});
</script>