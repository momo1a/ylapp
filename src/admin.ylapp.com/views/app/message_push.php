<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 userList">
  <div class="ui-box-outer">
	 <div class="ui-box-inner">
	  	<div class="ui-box-head syslog-head"><h2 class="ui-box-tit">消息推送管理</h2></div>
	   	   <div class="ui-box-body">
			<form action="<?php echo site_url('app_message_push/message')?>" method="get"  rel="div#main-wrap">
					<span>标题：</span>         
                    <input class="ui-form-text ui-form-textRed" name="search_key" value="<?php echo $search_key?>"  style="margin-right:15px;">
                    <span>创建时间：</span>
                    <input id="startTime" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly  value="<?php echo $this->input->get_post('startTime');?>" name="startTime" data-datefmt="yyyy-MM-dd HH:mm:ss" title="开始日期"> -
					<input id="endTime" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly value="<?php echo $this->input->get_post('endTime');?>"  name="endTime" data-datefmt="yyyy-MM-dd HH:mm:ss" title="截止日期">					
					<input class="ui-form-btnSearch" type="submit" value="搜索" id='submit'>
					<a href="<?php echo site_url('app_message_push/add_message')?>" type="form" callback="reload" style="float: right;text-decoration : none;"><input class="ui-form-btnSearch"  type="button" value="新增" /></a>				
			</form>			
			<table class="ui-table" style="margin-top:15px; ">
					<thead>
						<tr>
							<th width="5%">序号</th>
							<th width="10%">标题</th>
							<th>推送内容</th>
							<th width="15%">创建时间</th>
							<th width="10%">推送平台</th>
							<th width="15%">推送时间</th>
							<th width="10%">状态</th>
							<th width="10%">操作</th>
						</tr>
					</thead>
					<tbody>
							<?php foreach ($all_message['list'] as $v) :?>
							<?php if ($v['client_type']==App_push_message_model::CLIENT_TYPE_ALL):?>
							<?php $v['client_type_str']="Android+iOS "; ?>
							<?php elseif($v['client_type']==App_push_message_model::CLIENT_TYPE_IOS):?>
							<?php $v['client_type_str']="iOS"; ?>
							<?php elseif($v['client_type']==App_push_message_model::CLIENT_TYPE_ANDROID):?>
							<?php $v['client_type_str']="Android"; ?>
							<?php endif;?>	
							<?php if ($v['push_state']==App_push_message_model::STATE_PUSH_STATE_WAIT):?>
							<?php $v['push_state_str']="未推送"; ?>
							<?php elseif($v['push_state']==App_push_message_model::STATE_PUSH_STATE_TIMING):?>
							<?php $v['push_state_str']="待推送(定时)"; ?>
							<?php elseif($v['push_state']==App_push_message_model::STATE_PUSH_STATE_SUCCESS):?>
							<?php $v['push_state_str']="已推送"; ?>
							<?php elseif($v['push_state']==App_push_message_model::STATE_PUSH_STATE_ERROR):?>
							<?php $v['push_state_str']="推送失败"; ?>
							<?php endif;?>	
						<tr>
							<td><?php echo $v['id']?></td>
							<td><?php echo $v['title']?></td>
							<td><?php echo $v['content']?><br/></td>
							<td><?php echo date('Y-m-d H:i:s',$v['dateline'])?></td>
							<td><?php echo $v['client_type_str']?></td>
							<td><?php echo date('Y-m-d H:i:s',$v['push_time'])?></td>
							<td><?php echo $v['push_state_str']?></td>
							<td class="ui-table-operate">
								<?php  if($v['push_state']==App_push_message_model::STATE_PUSH_STATE_WAIT):?>
								<a href="<?php echo site_url('app_message_push/push?id=' . $v['id'])?>"  type="confirm" width="300" height="120"  callback="reload" title="立即推送消息吗？">立即推送</a>
								<?php endif;?>
							</td>
						</tr>
					<?php endforeach;?>
					</tbody>
				</table>
				<div class="ui-paging-center" style="margin-top:20px;">
					<div class="ui-paging">
						<?php echo $pager?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>	
<script>
	/*限制搜索框字符数为30字符以内*/
	(function($){
		$.fn.limit = function(num){
			var f = function(){
				if(this.value.length>num){
					this.value = this.value.substr(0,30);
				}
			};
			this.each(function(){
				if(this.tagName!="INPUT")return;
				if(this.addEventListener)
					this.addEventListener("input",f,false);
				else
					this.onpropertychange = f;
			});
		}
	})(jQuery);
	$(".ui-form-text[name=search_key]").limit(30);
	$(".ui-form-text[name=search_key]").bind("keypress",function(){
	 	if(this.value.length==30){return false;}
	 });
	 
	 //导出活动
 $("#add").click(function(){

	$("form:eq(0)").attr('rel="div#main-wrap"');
	$("form:eq(0)").attr("action", "<?php echo site_url('app_message_push/add_message')?>").submit();
 });
 //搜索商品
  $("#submit").click(function(){
	$("form:eq(0)").attr('rel="div#main-wrap"'); 
	$("form:eq(0)").attr("action", "<?php echo site_url('app_message_push/message')?>").submit();
 });
</script>

<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>