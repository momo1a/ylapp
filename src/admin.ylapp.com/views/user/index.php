<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 userList">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-head">
				<form action="<?php echo site_url('user/index/'.$segment); ?>" method="get" rel="div#main-wrap">
				<input name="url" type="hidden" value="<?php echo $_GET['url'];  ?>" />
				<input name="typeurl" type="hidden" value="<?php echo $_GET['typeurl'];  ?>" />
					<select name="shield_key" onchange="sh_key();">
						<option value="choose">会员状态</option>
						<option value="autoshield" <?php if('autoshield'==$_GET['shield_key']):?>selected="selected"<?php endif;?>>自动屏蔽</option>
						<option value="normal" <?php if('normal'==$_GET['shield_key']):?>selected="selected"<?php endif;?>>正常</option>
						<option value="checking" <?php if('checking'==$_GET['shield_key']):?>selected="selected"<?php endif;?>>调查中</option>
						<option value="shield" <?php if('shield'==$_GET['shield_key']):?>selected="selected"<?php endif;?>>屏蔽</option>
						<option value="lock" <?php if('lock'==$_GET['shield_key']):?>selected="selected"<?php endif;?>>封号</option>
					</select>

					<select name="shield_reason" onchange="sh_rea();">
						<option value="choose">屏蔽原因</option>
						<option value="ordererror" <?php if('ordererror'==$_GET['shield_reason']):?>selected="selected"<?php endif;?>>订单错误</option>
						<option value="buytimes" <?php if('buytimes'==$_GET['shield_reason']):?>selected="selected"<?php endif;?>>购买次数</option>
						<option value="refusetimes" <?php if('refusetimes'==$_GET['shield_reason']):?>selected="selected"<?php endif;?>>被申诉次数</option>
					</select>

					<select name="source" onchange="sh_reg_source();">
						<option value="choose">注册来源</option>
						<option value="3" <?php if('3'==$_GET['source']):?>selected="selected"<?php endif;?>>众划算</option>
						<option value="1" <?php if('1'==$_GET['source']):?>selected="selected"<?php endif;?>>试客联盟</option>
						<option value="2" <?php if('2'==$_GET['source']):?>selected="selected"<?php endif;?>>互联支付</option>
						<option value="0" <?php if('0'==$_GET['source']):?>selected="selected"<?php endif;?>> - </option>
					</select>

					<select name="premium" onchange="sh_premium();" >
						<option value="choose">会员身份</option>
						<option value="0" <?php if('0'==$_GET['premium']):?>selected="selected"<?php endif;?>>普通会员</option>
						<option value="1" <?php if('1'==$_GET['premium']):?>selected="selected"<?php endif;?>>优质会员</option>
					</select>

					<select name="search_key">
						<option value="uname" <?php if('uname'==$_GET['search_key']):?>selected="selected"<?php endif;?>>用户名称</option>
						<option value="mobile" <?php if('mobile'==$_GET['search_key']):?>selected="selected"<?php endif;?>>绑定手机</option>
						<option value="email" <?php if('email'==$_GET['search_key']):?>selected="selected"<?php endif;?>>绑定邮箱</option>
						<option value="bind_account" <?php if('bind_account'==$_GET['search_key']):?>selected="selected"<?php endif;?>>认证账户</option>
						<option value="uid" <?php if('uid'==$_GET['search_key']):?>selected="selected"<?php endif;?>>会员ID</option>
						<option value="regip" <?php if('regip'==$_GET['search_key']):?>selected="selected"<?php endif;?>>注册IP</option>
					</select>
					<input class="ui-form-text ui-form-textRed" name="search_value" value="<?php echo $_GET['search_value'];?>" >
					<input id="startTime" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly name="startTime" data-datefmt="yyyy-MM-dd HH:mm:ss" value="<?php if($startTime>0) echo  date('Y-m-d H:i:s',$startTime); ?>">
					<input id="endTime" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly name="endTime" data-datefmt="yyyy-MM-dd HH:mm:ss" value="<?php if($endTime>0) echo  date('Y-m-d H:i:s',$endTime); ?>">
					<input class="ui-form-btnSearch" type="button" onclick="btnSubmit();" value="搜索">
					<input class="ui-form-btnSearch" type="button" value="导出列表" style="margin-left:50px;" onclick="dosubmit.call(this)"/>


				</form>
			</div>
			<div class="ui-box-body">
				<table class="ui-table">
					<thead>
						<tr>
							<th width="60">选择 <input class="checkAll" type="checkbox" name="uids[]"></th>
							<th><?php echo $utype==2 ? '商家' : '买家'; ?>UID</th>
							<th><?php echo $utype==2 ? '商家' : '买家'; ?>名称</th>
							<th>绑定邮箱/手机</th>
							<th>注册来源</th>
							<th>会员状态</th>
							<th>注册时间/IP</th>
							<th>屏蔽原因</th>
							<th width="200">操作</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if(is_array($items)):
							foreach ($items as $k=>$v):
								$bind_info = user_has_binds($v['bind']);
						?>
						<tr>
							<td><input type="checkbox" name="uids[]" value="<?php echo $v['uid']; ?>" /></td>
							<td><?php echo $v['uid']; ?></td>
							<td>
							<?php if (isset($v['is_bind_taobao']) AND $v['is_bind_taobao']): echo '<img src="'.config_item('domain_static').'images/admin/bind_taobao.png">';endif;?>
							<?php if (isset($bind_info['qq'])): echo '<img src="'.config_item('domain_static').'images/admin/login_bind_qq.png">';endif;?>
							<?php if (isset($bind_info['weibo'])): echo '[WEIBO]';endif;?>
							<?php echo $v['uname']; ?><?php if($v['is_premium'] == 1){?><i class="u-quality">优质会员</i><?php }?>
							</td>
							<td><?php echo '<p>'.$v['email'].'</p><p>'.$v['mobile'].'</p>'; ?></td>
							<td><?php
							if($v['reg_source']==1){   echo '试客联盟';}
							elseif($v['reg_source']==2){   echo '互联支付';}
							elseif($v['reg_source']==3){   echo '众划算';}
							elseif($v['reg_source']==4){   echo '朋贝网';}
							elseif($v['reg_source']==5){   echo '淘发现';}
							else{ echo '-'; }
							?></td>
							<td><?php
								$str_state = '';
								$str_day = '';
								$color = user_stat_coror($v['is_lock']);
								if ($v['utype'] == 1) {
									$str_state =  isset($buyer_state[$v['is_lock']]) ? $buyer_state[$v['is_lock']] : '未知状态('.$v['is_lock'].')';
								}else{
									$str_state =  isset($seller_state[$v['is_lock']]) ? $seller_state[$v['is_lock']] : '未知状态('.$v['is_lock'].')';
								}
								echo '<p style="color:'.$color.' ">'.$str_state.'</p>';

								if(in_array($v['is_lock'], array(2,3,4)) ){
									echo '<p style="color:'.$color.' ">'.($v['lock_day'] > 0?'（'.$v['lock_day'].'天）':'永久').'</p>';
								}

							?>
							</td>
							<td><?php echo '<p>'.date('Y-m-d H:i:s',$v['dateline']).'</p><p>'.long2ip($v['regip']).'</p>'; ?></td>
							<td><?php echo $v['content'];?></td>
							<td class="ui-table-operate">
								<a href="<?php echo site_url('user/lock').'?uids='.$v['uid'].'&utype='.$utype?>" type="form" callback="reload">屏蔽/解屏</a>
								<a href="<?php echo site_url('user/user_detail/'.$v['uid'])?>" type="load" rel="div#main-wrap">详细信息</a>
								<a href="<?php echo site_url('user/user_lock_log/'.$v['uid'])?>" type="dialog">操作记录</a>
							</td>
						</tr>
						<?php endforeach; endif;?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="9">
								<a onclick="showLock(this)" callback="reload" class="ui-form-button ui-form-buttonBlue" href="javascript:;">屏蔽/解屏</a>
							</td>
						</tr>
					</tfoot>
				</table>
				<div class="ui-paging-center" style="margin-top:20px;">
					<div class="ui-paging"><?php echo $pager;?></div>
				</div>
			</div>
			<!-- /userList-body -->
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	$("input[type='checkbox'].checkAll").click(function(){
		var $this = $(this);
		$("input[type='checkbox'][name='"+$this.attr('name')+"']").attr('checked', $this.is(':checked'));
	});
});
function showLock(obj){
	var $obj = $(obj);
	var uids = $("input[type='checkbox'][class!='checkAll'][name^='uids']:checked").map(function(){
		return $(this).val();
	}).get().join(",");

	if(uids === '') {
		alert('请至少选择一条记录');
		return false
	}
	var url = '<?php echo site_url('user/lock?&utype='.$utype)?>'+'&uids='+uids;
	url = encodeURI(url);
	$obj.attr('href', url);
	$obj.attr('type', 'form');
	return true;
}
//自动屏蔽
function sh_key(){
	$('select[name="shield_reason"]').get(0).selectedIndex=0;
	$('input[name="search_value"]').val('');
	$('form').get(0).submit();
}
function sh_rea(){
	$('select[name="shield_key"]').get(0).selectedIndex=0;
	$('input[name="search_value"]').val('');
	$('form').get(0).submit();
}
function sh_reg_source(){
	$('form').get(0).submit();
}
//优质会员
function sh_premium(){
	$('form').get(0).submit();
}
//
function btnSubmit(){
	$('select[name="shield_key"]').get(0).selectedIndex=0;
	$('select[name="shield_reason"]').get(0).selectedIndex=0;
	$('form').get(0).submit();
}
function dosubmit(){
	 var parent = $(this).parent();
	 if(!parent.find(".ui-form-textDatetime").eq(0).val() || !parent.find(".ui-form-textDatetime").eq(1).val()){
		art.dialog({
			icon: "error",
			title: "温馨提示",
			content:"由于数据太多，必须选择起止时间！"
		});
		return;
	}
	 location.href='<?php echo site_url('user/export_user/'.$segment);?>?' +$(this).parent().serialize();
}
</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>
