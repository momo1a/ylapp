<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 userList">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div style="background-color: #eee;margin-left:10px;margin-top:10px;">
			   <span style="font-family:微软雅黑;font-size:13px;font-weight:bold;font-style:normal;text-decoration:none;color:#333333;">邀请好友记录</span>
			</div>
			<div class="ui-box-head" style="background-color:#fff">
				<form action="<?php echo site_url('user_invite/index/'.$page_size); ?>" method="get" rel="div#main-wrap">
				    <input id="startTime" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly  value="<?php echo $this->input->get_post('startTime');?>" name="startTime" data-datefmt="yyyy-MM-dd HH:mm" title="开始日期">
					<input id="endTime" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly value="<?php echo $this->input->get_post('endTime');?>"  name="endTime" data-datefmt="yyyy-MM-dd HH:mm" title="截止日期">
				    
				    <select name="state">
				        <option value="0">邀请状态</option>
				        <?php foreach(Zhs_invite_user_model::$status_arr as $state=>$val){?>
						<option value="<?php echo $state;?>" <?php if($state==$this->input->get_post('state') ):?>selected="selected"<?php endif;?>><?php echo $val;?></option>
						<?php }?>
					</select>
					
					<select name="search_key">
						<option value="beivuname" <?php if('beivuname'==$this->input->get_post('search_key')):?>selected="selected"<?php endif;?>>新注册用户</option>
						<option value="ivuname" <?php if('ivuname'==$this->input->get_post('search_key')):?>selected="selected"<?php endif;?>>邀请人</option>
						<option value="ivid" <?php if('ivid'==$this->input->get_post('search_key')):?>selected="selected"<?php endif;?>>邀请编号</option>
					</select>
					<input class="ui-form-text ui-form-textRed" name="search_value" value="<?php echo $this->input->get_post('search_value');?>" >
					<input id="submit" class="ui-form-btnSearch" type="submit" value="搜索">
					<input id="export" class="ui-form-btnSearch" type="submit" style="margin-left:50px;" value="导出列表">
					<a style="margin-left:10px;" class="ui-form-btnSearch" type="form" href="<?php echo site_url('user_invite/pay');?>" callback="reload">一键打款</a>
					<a style="margin: 6px 50px 0 0;float: right;" type="form" href="<?php echo site_url('user_invite/password');?>">设置操作码</a>
				</form>
			</div>
			<div class="ui-box-body">
				<table class="ui-table">
					<thead>
						<tr>
							<th width="10%">邀请编号</th>
							<th width="20%">新注册用户</th>
							<th width="15%">注册时间</th>
							<th width="15%">邀请人</th>
							<th width="10%">获得奖励</th>
							<th width="10%">邀请状态</th>
							<th width="15%">操作</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if(is_array($invite_user)):
							foreach ($invite_user as $k=>$v):
						?>
						<tr>
							<td><?php echo $v['ivid']; ?></td>
							<td><?php echo $v['beivuname']; ?></td>
							<td><?php echo date('Y-m-d H:i',$v['reg_time']); ?></td>
							<td><?php echo $v['ivuname']; ?></td>
							<td><?php echo Zhs_invite_user_model::show_commission($v['state'],$v['commission']);?></td>
							<td><?php echo Zhs_invite_user_model::show_state($v['state'],$v['reg_time'],$v['expiry_date']); ?></td>
							<td><a href="<?php echo site_url('user_invite/find_log')?>" data-ivid="<?php echo $v['ivid']; ?>" height="420" width="600" type="dialog">操作记录</a></td>
						</tr>
						<?php endforeach; endif;?>
					</tbody>
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
//搜索用户
$("#submit").click(function(){
	$("form:eq(0)").attr('rel="div#main-wrap"'); 
	$("form:eq(0)").attr("action", "<?php echo site_url('user_invite/index/')?>");
});
//导出用户
$("#export").click(function(){
	$("form:eq(0)").removeAttr('rel'); 
	$("form:eq(0)").attr("action", "<?php echo site_url('user_invite/export/')?>");
});
</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>