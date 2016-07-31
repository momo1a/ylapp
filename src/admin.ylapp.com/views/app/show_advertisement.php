<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 syslog"><div class="ui-box-outer"><div class="ui-box-inner"> 
<div class="ui-box-head syslog-head"><h2 class="ui-box-tit"><?php if($differ==1) echo 'Banner管理';else echo '首页快捷入口'; ?> </h2></div>
<div class="ui-box-body">
           <div style="width:800px; float:left;">
			<form action="<?php echo site_url('app_content_manager/show_advertisement/'.$segment);?>" rel="div#main-wrap"  method="post">  
           标题：<input name="title"  class="ui-form-text ui-form-textRed" type="text" value="<?php echo $this->input->get_post('title');?>" /> &nbsp;  &nbsp; 
           创建时间：<input class="ui-form-text ui-form-textGray ui-form-textDatetime" readonly  name="startTime" data-dateFmt="yyyy-MM-dd HH:mm:ss" value="<?php echo $this->input->get_post('startTime');?>"> -
					<input class="ui-form-text ui-form-textGray ui-form-textDatetime" readonly  name="endTime" data-dateFmt="yyyy-MM-dd HH:mm:ss"  value="<?php echo $this->input->get_post('endTime');?>">
					<input type="hidden" name="saveport" value="yes" />
					<input class="ui-form-btnSearch" type="submit" value="搜索" />
				</form>
             </div>
       <a callback="reload" type="form" width="580" height="300" data-showform="yes" data-differ="<?php echo $differ;  ?>" href="<?php echo site_url('app_content_manager/add_advertisement');?>" style="display:block; width:50px; color:#fff; text-align:center; margin-bottom:15px; margin-right:15px;float: right;" class="ui-form-button ui-form-buttonBlue">新增</a>
	<table class="ui-table">
		<thead>
			<tr>
				<th>序号</th>
				<th>标题</th>
                <th>图片</th>
                <th>跳转类型</th>
				<th>跳转值</th>
                <th>状态</th>
                <th>创建时间</th>
                 <th>排序<br/> [
						<a href="javascript:;" class="ui-operate-button ui-operate-buttonEdit" onclick="adseditsort(this);return false;">编辑排序</a>
						<a style="display: none;" href="javascript:;" class="ui-operate-button ui-operate-buttonSave" onclick="setads_sort();return false;">保存排序</a>
					]</th>
                <th>操作</th>
			</tr>
		</thead>
		<tbody class="ui-table-operate">
			<?php if (is_array($bannerlist)):?>
			<?php foreach ($bannerlist as $k=>$v):?>
			<tr>
				<td><?php echo $v['id'];?></td>
				<td><?php echo $v['title'];?></td>
                <td><img src="<?php echo $v['images'] ?>" height="35" /></td>
				<td><?php echo $type[$v['type']];?></td>
                <td><?php  echo $v['value'];?></td>
                <td><?php  if($v['enable']==1)echo '在用';else echo '停用' ;  ?></td>
                <td><?php  echo date('Y-m-d H:i:s ',$v['dateline']);  ?></td>
                <td class="item-sort">
		   	<span><?php echo $v['sort'];?></span>
			<input size="2" class="ui-form-text ui-form-textRed sort_order" style="display:none; text-align:center;" type="text" name="id_<?php echo $v['id'];?>" value="<?php echo $v['sort'];?>" />
			</td>
				<td>
                <a callback="reload" data-id="<?php echo $v['id']; ?>" height="400" width="600" type="form" href="<?php echo site_url('app_content_manager/edit_advertisement'); ?>">编辑</a>
                 <a callback="reload" title="确认<?php if($v['enable']==1){echo '隐藏'; }else{ echo '显示';}?>该项？" type="confirm" href="<?php echo site_url('app_content_manager/hide_advertisement');?>" data-id="<?php echo $v['id']; ?>"  data-enable="<?php echo $v['enable'];?>"><?php if($v['enable']==1){echo '隐藏'; }else{ echo '显示';}?></a>
              </td>
			</tr>
			<?php endforeach;?>
			<?php endif;?>
		</tbody>
        <tr><td colspan="9" class="ui-paging"><?php echo $pager; ?></td></tr>
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
	$.post(SITE_URL+"app_content_manager/save_adv_sort", data, function(rs){
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