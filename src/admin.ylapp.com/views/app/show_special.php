<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 syslog"><div class="ui-box-outer"><div class="ui-box-inner"> 
<div class="ui-box-head syslog-head"><h2 class="ui-box-tit">专场管理</h2></div>
<div class="ui-box-body">
           <div style="width:800px; float:left;">
			<form action="<?php echo site_url('app_content_manager/show_special/');?>" rel="div#main-wrap"  method="post">  
           专场标题：<input name="title"  class="ui-form-text ui-form-textRed" type="text" value="<?php echo $title;?>" /> &nbsp;  &nbsp; 
           创建时间：<input class="ui-form-text ui-form-textGray ui-form-textDatetime" readonly  name="startTime" data-dateFmt="yyyy-MM-dd HH:mm:ss" value="<?php echo $this->input->get_post('startTime');?>"> -
					<input class="ui-form-text ui-form-textGray ui-form-textDatetime" readonly  name="endTime" data-dateFmt="yyyy-MM-dd HH:mm:ss"  value="<?php echo $this->input->get_post('endTime');?>">
					<input type="hidden" name="saveport" value="yes" />
					<input class="ui-form-btnSearch" type="submit" value="搜索" />
				</form>
             </div>
       <a callback="reload" type="form" width="580" height="300" data-showform="yes"  href="<?php echo site_url('app_content_manager/add_special');?>" style="display:block; width:50px; color:#fff; text-align:center; margin-bottom:15px; margin-right:15px;float: right;" class="ui-form-button ui-form-buttonBlue">新增</a>
	<table class="ui-table">
		<thead>
			<tr>
				<th>序号</th>
				<th>专场标题</th>
                <th>主打商品1</th>
                <th>主打商品2</th>
                <th>主打商品3</th>
                <th>专场广告图片</th>
                <th>创建时间</th>
                <th>状态</th>
                 <th>排序<br/> [
						<a href="javascript:;" class="ui-operate-button ui-operate-buttonEdit" onclick="adseditsort(this);return false;">编辑排序</a>
						<a style="display: none;" href="javascript:;" class="ui-operate-button ui-operate-buttonSave" onclick="setads_sort();return false;">保存排序</a>
					]</th>
                <th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php if (is_array($special)):?>
			<?php foreach ($special as $k=>$v):?>
			<tr>
				<td><?php echo $v['id'];?></td>
				<td><?php echo $v['title'];?></td>
                <td><a href="<?php echo $this->config->item('domain_detail').$v['gid1'].'.html';  ?>" target="_blank" title="<?php  if(isset($goods_title[$v['gid1']]))echo $goods_title[$v['gid1']] ?>"><img src="<?php if(isset($goods_img[$v['gid1']]))echo $goods_img[$v['gid1']] ?>" height="35" /><br/>活动标题：<?php  if(isset($goods_title[$v['gid1']]))echo $goods_title[$v['gid1']] ?></a></td>
                <td><a href="<?php echo $this->config->item('domain_detail').$v['gid2'].'.html';  ?>" target="_blank" title="<?php  if(isset($goods_title[$v['gid2']]))echo $goods_title[$v['gid2']] ?>"><img src="<?php if(isset($goods_img[$v['gid2']]))echo $goods_img[$v['gid2']] ?>" height="35" /><br/>活动标题：<?php  if(isset($goods_title[$v['gid2']]))echo $goods_title[$v['gid2']] ?></a></td>
                <td><a href="<?php echo $this->config->item('domain_detail').$v['gid3'].'.html';  ?>" target="_blank" title="<?php  if(isset($goods_title[$v['gid3']]))echo $goods_title[$v['gid3']] ?>"><img src="<?php if(isset($goods_img[$v['gid3']]))echo $goods_img[$v['gid3']] ?>" height="35" /><br/>活动标题：<?php  if(isset($goods_title[$v['gid3']]))echo $goods_title[$v['gid3']] ?></a></td>            
                <td><img src="<?php echo $v['img']; ?>" height="35" /></td>
				<td><?php  echo date('Y-m-d H:i:s ',$v['dateline']);  ?></td>
                <td><?php  if($v['enable']==1)echo '在用';else echo '停用' ;  ?></td>
                <td class="item-sort">
		   	<span><?php echo $v['sort'];?></span>
			<input size="2" class="ui-form-text ui-form-textRed sort_order" style="display:none; text-align:center;" type="text" name="id_<?php echo $v['id'];?>" value="<?php echo $v['sort'];?>" />
			</td>
				<td class="ui-table-operate">
                <a callback="reload" data-id="<?php echo $v['id']; ?>" height="400" width="600" type="form" href="<?php echo site_url('app_content_manager/edit_special'); ?>">编辑</a>
                 <a callback="reload" title="确认<?php if($v['enable']==1){echo '隐藏'; }else{ echo '显示';}?>该项？" type="confirm" href="<?php echo site_url('app_content_manager/hide_special');?>" data-id="<?php echo $v['id']; ?>"  data-enable="<?php echo $v['enable'];?>"><?php if($v['enable']==1){echo '隐藏'; }else{ echo '显示';}?></a>
              </td>
			</tr>
			<?php endforeach;?>
			<?php endif;?>
		</tbody>
        <tr><td colspan="10" class="ui-paging"><?php echo $pager; ?></td></tr>
	</table>
</div>

</div></div></div>
<script>
//排序修改
function setads_sort(){
	var data = {};
	var v = $("input.sort_order").each(function(i,o){
		data[$(o).attr('name')] = $(o).val();
	});
	$.post(SITE_URL+"app_content_manager/save_special_sort", data, function(rs){
		if(AjaxFilter(rs)){
			load('<?php echo site_url(uri_string());?>', $('div#main-wrap'), {listonly:'yes'})
		}
	},'json');
	return false;
}
function adseditsort(o){
	$(o).hide();
	$('.item-sort span').hide();
	$(o).siblings().show();
	$('.item-sort input').show();
}
</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>