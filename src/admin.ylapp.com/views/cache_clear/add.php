<script type="text/javascript">
	$("#form_add").find("#url, #mark").on("blur", function(){
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
	$("#form_add").submit(function(){
		if ($("#url").val() == "" || $("#mark").val() == "") {
			$(this).find("#url, #mark").blur();
		} else if ($(".error").length > 0) {
			return false;
		} else {
			alert("添加缓存成功！");
			window.location = "<?php echo site_url('cache_clear/cache?tag_type=4') ?>"
		}
	});
</script>
<div style="width:660px">
	<form id="form_add" callback="reload" name="form_add" method="post" type="ajax" action="<?php echo site_url('cache_clear/cache?tag_type=2');?>">
		<table cellpadding="3" cellspacing="1" class="ui-table2" style="margin:20px 0 20px 0;">
			<tr>
				<th style="width: 150px">类别名称：</th>
				<td>
					<?php echo form_dropdown('cid',$select_data)?>
				</td>
			</tr>
			<tr>
				<th>缓存地址：</th>
				<td>
					<input type="text" id="url" class="ui-form-text ui-form-textRed"  name="url"  size="40" maxlength="500"   data-rule="required" data-msg="请输入地址">
					<span class="num_tip">&nbsp;</span>
					<span id="for_url" style="width:auto;"></span>
				</td>
			</tr>
			<tr>
				<th>是否启用：</th>
				<td>
					是  <input type="radio"  name="state" value="1" checked>
					否  <input type="radio"  name="state" value="0">
				</td>
			</tr>
			<tr>
				<th>备注：</th>
				<td>
					<textarea id="mark" name="mark" rows="50" cols="50" maxlength="500" data-rule="required" data-msg="请输入备注" class="ui-form-text ui-form-textRed" style="width: 435px; height: 134px;"></textarea>
					<span class="num_tip">&nbsp;</span>
					<span id="for_mark" style="width:auto;"></span>
				</td>
			</tr>
			<tfoot>
			<tr>
				<th></th>
				<td><input type="submit" value="添加缓存" class="ui-form-button ui-form-buttonBlue" /></td>
			</tr>
			</tfoot>
		</table>
	</form>

</div>
<script>
	$(function(){
		$(".num_tip").each(function(){
			var _this=$(this),
				maxl=_this.prev().attr("maxlength");
			_this.text("0/"+maxl);
		});
		$("#url,#mark").on("input keyup",function(){
			var $input=$(this);
			$input.on("input keyup",function(){
				var len=$input.val().length,
						init_size=$input.attr("maxlength");
				$input.next(".num_tip").text(len+"/"+init_size);
			});
		});
	});
</script>