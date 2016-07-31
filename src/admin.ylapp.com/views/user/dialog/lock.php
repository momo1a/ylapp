<div class="lock">
	<form action="<?php echo site_url('user/lock')?>" method="post">
		<table class="ui-table2">
			<tr>
				<th width="20%">原因：</th>
				<td>
					<textarea name="content" class="ui-form-text" style="width: 300px;height: 60px" data-rule="required|maxlength(200)" data-msg="*请输入原因|*字数最多不超过200个"></textarea>
					<p><span id="for_content" class="ui-table-statusR" style="font-weight: normal;"></span></p>
				</td>
			</tr>
			<tr>
				<th>状态：</th>
				<td>
					<label class="radio"><input type="radio" name="state" value="0" data-lock="0" <?php if($user['is_lock']==0) echo 'checked="checked" '; ?> data-describe="（所有功能正常使用）"  onclick=" checked_type(this)"/>正常</label>
			
					<?php if($utype == 1):?>
					<label class="radio"><input type="radio" name="state" value="2" data-lock="2" <?php if($user['is_lock']==2) echo 'checked="checked" '; ?> data-describe="（用户帐号被“屏蔽”，不能抢新活动）" onclick=" checked_type(this)" />屏蔽</label>
					<?php else:?>
					<label class="radio"><input type="radio" name="state" value="2" data-lock="2" <?php if($user['is_lock']==2) echo 'checked="checked" '; ?> data-describe="（不能发布新活动，但其他操作正常使用）" onclick=" checked_type(this)" />屏蔽(一般)</label>
					<label class="radio"><input type="radio" name="state" value="3" data-lock="3" <?php if($user['is_lock']==3) echo 'checked="checked" '; ?> data-describe="（不能发布新活动，并且待审核活动无法审核；审核时提示：“该商家目前屏蔽，无法上线”）" onclick=" checked_type(this)" />屏蔽(严重)</label></br>
					<label class="radio"><input type="radio" name="state" value="4" data-lock="4" <?php if($user['is_lock']==4) echo 'checked="checked" '; ?> data-describe="（不能发布新活动，新活动不能上线，商家不能审核订单和申诉）" onclick=" checked_type(this)" />屏蔽(很严重)</label> 
                    <label class="radio"><input type="radio" name="state" value="9" data-lock="9" <?php if($user['is_lock']==9) echo 'checked="checked" '; ?> data-describe="（商家被标记为特殊商家，则活动无法审核通过）" onclick=" checked_type(this)" />特殊商家</label>
					<?php endif;?>
					<label class="radio"><input type="radio" name="state" value="5" data-lock="5" <?php if($user['is_lock']==5) echo 'checked="checked" '; ?> data-describe="（帐号被封，无法登录）" onclick=" checked_type(this)" />封号</label>
                   
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><span id="lbl_message"></span></td>
			</tr>
			<tr id="day" style="display:none">
				<th>天数：</th>
				<td>
					<label class="radio"><input type="radio" name="day" value="7"  checked="checked" />7天</label>
					<label class="radio"><input type="radio" name="day" value="15" <?php if ($user['lock_day'] == 15) echo 'checked="checked"'?> />15天</label>
					<label class="radio"><input type="radio" name="day" value="30"  <?php if ($user['lock_day'] == 30) echo 'checked="checked"'?> />30天</label>
                          <label class="radio"><input type="radio" name="day" value="100"  <?php if (in_array($user['is_lock'],array(2,3,4)) && $user['release_lock_time'] == 0 && $user['lock_day'] == 0) echo 'checked="checked"'?> />永久</label>
				</td>
			</tr>
		</table>
		<input type="hidden" name="is_post" value="yes"/>
		<input type="hidden" name="uids" value="<?php echo $uids?>"/>
		<input type="hidden" name="utype" value="<?php echo $utype?>"/>
	</form>
</div>

<style>
.lock{width:450px;}
.radio{margin-left:10px;}
</style>
<script>

$(function(){
	show_message();
});

//加载时显示上次选择信息
function show_message(){
	var obj = $('.radio input[type=radio]:checked')[0];
	checked_type(obj);
}

//选择屏蔽操作提示信息
function checked_type(obj){
	var msg = $(obj).data('describe');
	var lock = $(obj).data('lock');
	$('#lbl_message').text(msg);
	if(lock==2 || lock==3 || lock==4){
		$('#day').show();
	}else{
		$('#day').hide();
	}
	return false;
}
</script>