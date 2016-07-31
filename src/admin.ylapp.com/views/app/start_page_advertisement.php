<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 syslog"><div class="ui-box-outer"><div class="ui-box-inner"> 
<div class="ui-box-head syslog-head"><h2 class="ui-box-tit">启动页管理</h2></div>
<div class="ui-box-body">
       <a callback="reload" type="form" width="580" height="300" data-showform="yes" href="<?php echo site_url('app_content_manager/add_start_page');?>" style="display:block; width:50px; color:#fff; text-align:center; margin-bottom:15px; margin-right:15px;float: right;" class="ui-form-button ui-form-buttonBlue">新增</a>
	<table class="ui-table">
		<thead>
			<tr>
				<th width=10%>序号</th>
                <th width=20%>图片</th>				
				<th width=20%>标题</th>
                <th width=25%>创建时间</th>
                <th width=10%>状态</th>
                <th width=15%>操作</th>
			</tr>
		</thead>
		<tbody class="ui-table-operate">
			<?php if (is_array($data['list'])):?>
			<?php foreach ($data['list'] as $k=>$v):?>
			<tr>
				<td><?php echo $v['id'];?></td>
				<td><img src="<?php echo $v['images'] ?>" height="35" /></td>
				<td><?php echo $v['title'];?></td>
				<td><?php echo date('Y-m-d H:i:s ',$v['dateline']);  ?></td>
                <td><?php  if($v['enable']==1)echo '有效';else echo '失效' ;  ?></td>
				<td>
                 <a callback="reload" title="确认<?php if($v['enable']==1){echo '停用'; }else{ echo '启用';}?>该项？" type="confirm" href="<?php echo site_url('app_content_manager/hide_advertisement');?>" data-id="<?php echo $v['id']; ?>"  data-enable="<?php echo $v['enable'];?>"><?php if($v['enable']==1){echo '停用'; }else{ echo '启用';}?></a>
              </td>
			</tr>
			<?php endforeach;?>
			<?php endif;?>
		</tbody>
        <tr><td colspan="9" class="ui-paging"><?php echo $pager; ?></td></tr>
	</table>
</div>

</div></div></div>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>