<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 userList">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div style="background-color: #eee;margin-left:10px;margin-top:10px;">
			   <span style="font-family:微软雅黑;font-size:13px;font-weight:bold;font-style:normal;text-decoration:none;color:#333333;">奖励兑现记录</span>
			</div>
			<div class="ui-box-head" style="background-color:#fff">
				<form action="<?php echo site_url('user_invite/commission/'.$page_size); ?>" method="get" rel="div#main-wrap">
				    <input id="startTime" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly  value="<?php echo $this->input->get_post('startTime');?>" name="startTime" data-datefmt="yyyy-MM-dd HH:mm" title="开始日期">
					<input id="endTime" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly value="<?php echo $this->input->get_post('endTime');?>"  name="endTime" data-datefmt="yyyy-MM-dd HH:mm" title="截止日期">
					
					<select name="search_key">
						<option value="ivuname" <?php if('ivuname'==$this->input->get_post('search_key')):?>selected="selected"<?php endif;?>>邀请人</option>
						<option value="ivid" <?php if('ivid'==$this->input->get_post('search_key')):?>selected="selected"<?php endif;?>>邀请编号</option>
					</select>
					<input class="ui-form-text ui-form-textRed" name="search_value" value="<?php echo $this->input->get_post('search_value');?>" >
					<input id="submit" class="ui-form-btnSearch" type="submit" value="搜索">
					<span style="margin: 6px 100px 0 0;float: right;">已打款总金额：<a id="show_commission" onclick="show_sum_commission()" href="javascript:void(0);">显示</a></span>
				</form>
			</div>
			<div class="ui-box-body">
				<table class="ui-table">
					<thead>
						<tr>
							<th width="10%">邀请编号</th>
							<th width="15%">邀请人</th>
							<th width="10%">金额(元)</th>
							<th width="10%">打款时间</th>
							<th width="15%">邀请状态</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if(is_array($user_pay)):
							foreach ($user_pay as $k=>$v):
						?>
						<tr>
							<td><?php echo $v['ivid']; ?></td>
							<td><?php echo $v['ivuname']; ?></td>
							<td><?php echo $v['commission']; ?></td>
							<td><?php echo date('Y-m-d H:i',$v['dateline']); ?></td>
							<td><?php echo YL_invite_user_pay_model::$status_arr[$v['state']]; ?></td>
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
						
//显示已经打款总额
function show_sum_commission()
{
	$.ajax({
		url:"/user_invite/sum_commission/",
		type : "get",
		dataType:"json",
		error:function(){
			PopupTips('服务器繁忙，请重试', 'notice', 2000);return;
		},
		success:function(ret){
			if(ret.type=='ACCESS_DENY'){
				PopupTips('您无此操作权限', 'notice',3000);return;
			}else{
				$("#show_commission").html(ret.toFixed(2)+' 元');
			}
		}
	});
}

</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>