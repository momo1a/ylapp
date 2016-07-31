<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<?php $type_str = array(1=>'未存款',2=>'已存入',3=>'申请退还', 4=>'已退还');?>
<div class="ui-box ui-box2 userList">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<form action="<?php echo site_url('user/index/yzcm')?>" method="get"  rel="div#main-wrap">
				<div class="ui-box-head">
                        <select name="deposit_type" onchange="$(this).closest('form').get(0).submit()">
							<option value="0" <?php if($deposit_type == 0) echo 'selected="selected"'?>>诚信金类型</option>
							<option value="1" <?php if($deposit_type == 1) echo 'selected="selected"'?>>一站成名</option>
							<option value="2" <?php if($deposit_type == 2) echo 'selected="selected"'?>>名品馆</option>
						</select>
						<select name="search_state" onchange="$(this).closest('form').get(0).submit()">
							<option value="0" <?php if($state == 0) echo 'selected="selected"'?>>诚信金状态</option>
							<option value="2" <?php if($state == 2) echo 'selected="selected"'?>>已存入</option>
							<option value="3" <?php if($state == 3) echo 'selected="selected"'?>>申请退还</option>
							<option value="4" <?php if($state == 4) echo 'selected="selected"'?>>已退还</option>
						</select>
						<select name="search_key_type" style="width:107px">
							<option value="uid"  <?php if($key_type == 'uid') echo 'selected="selected"'?> >商家ID</option>
							<option value="uname"  <?php if($key_type == 'uname') echo 'selected="selected"'?>>商家名称</option>
							<option value="email_mobile"  <?php if($key_type == 'email_mobile') echo 'selected="selected"'?>>商家邮箱/手机</option>
						</select>
                        <input class="ui-form-text ui-form-textRed" name="search_key" value="<?php echo $key?>" >
                        <input id="startTime" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly  value="<?php echo $this->input->get_post('startTime');?>" name="startTime" data-datefmt="yyyy-MM-dd HH:mm:ss" title="开始日期">
					<input id="endTime" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly value="<?php echo $this->input->get_post('endTime');?>"  name="endTime" data-datefmt="yyyy-MM-dd HH:mm:ss" title="截止日期">
						
						<input class="ui-form-btnSearch" type="submit" value="搜索" id='submit'>
						<!-- <input class="ui-form-btnSearch" type="button" value="设置保证金操作码" style="margin: 6px 7px 0 0;float: right;" > -->           <input class="ui-form-btnSearch" type="submit" value="导出列表" style="margin-left:50px;" id="export">
						<a href="<?php echo site_url('user/password')?>" type="form"  style="margin: 6px 7px 0 0;float: right;" >设置诚信金操作码</a>
				</div>

			</form>
			<div class="ui-box-body">
				<table class="ui-table">
					<thead>
						<tr>
							<th>商家uid</th>
							<th>商家名称</th>
							<th>存入金额/元</th>
							<th>诚信金状态</th>
                            <th>存入时间</th>
                            <th>绑定邮箱</th>
                            <th>绑定手机</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($users['rows'] as  $item) { ?>
						<tr>
							<td><?php echo $item['uid']?></td>
							<td><?php echo $item['uname']?></td>
							<td><?php echo intval($item['money'])?><br/>(<?php if ($item['deposit_type']==2)echo '【名品馆】';else echo '一站成名'; ?>)</td>
							<td style="color: <?php echo $item['money_color']?>"><?php echo $type_str[$item['state']]?></td>
                            <td><?php echo date('Y-m-d H:i:s',$item['deposit_time'])?></td>
                            <td><?php echo $item['email']?></td>
                            <td><?php echo $item['mobile']?></td>
							<td class="ui-table-operate">
								<?php if($item['state'] != 4) {?>
								<a href="<?php echo site_url('user/refund')?>"  type="form" width="300" height="120"  data-uid="<?php echo $item['uid']?>" data-deposit_type="<?php echo $item['deposit_type']?>" callback="reload">退还诚信金</a><br>
								<a href="<?php echo site_url('user/deduct')?>"  type="form" width="450" data-uid="<?php echo $item['uid'];?>"   data-deposit_type="<?php echo $item['deposit_type']?>" callback="reload" >扣除诚信金</a><br>
								<?php }?>
								<a href="<?php echo site_url('user/remark')?>" type="form" width="450" height="320" data-uid="<?php echo $item['uid']?>"  data-deposit_type="<?php echo $item['deposit_type']?>">备注</a><br/>
                                <a href="<?php echo site_url('user/detail')?>"  type="dialog" width="600" height="420"  data-uid="<?php echo $item['uid']?>"  data-deposit_type="<?php echo $item['deposit_type']?>">操作记录</a>
							</td>
						</tr>
						<?php }?>
					</tbody>
				</table>
				<div class="ui-paging-center" style="margin-top:20px;">
					<div class="ui-paging">
						<?php echo $pager?>
					</div>
				</div>
			</div>
			<!-- /userList-body -->
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
 $("#export").click(function(){
	 if($("#startTime").val()=='' || $("#endTime").val()==''){
		alert('由于数据太多请务必选择起止时间!');
		return false ;
		}
	$("form:eq(0)").removeAttr('rel'); 
	$("form:eq(0)").attr("action", "<?php echo site_url('user/export')?>").submit();
 });
 //搜索商品
  $("#submit").click(function(){
	$("form:eq(0)").attr('rel="div#main-wrap"'); 
	$("form:eq(0)").attr("action", "<?php echo site_url('user/index/yzcm')?>").submit();
 });
</script>

<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>