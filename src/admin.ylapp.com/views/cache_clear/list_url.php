<style>
	.add_cache { display: inline-block; margin-left: 20px; padding: 3px 10px 4px; border-radius: 2px; background-color: #3d4d66; color: #fff; vertical-align: middle; }
	.add_cache:hover { text-decoration: none; color: #fff;}
</style>
<div class="clearfix" style="margin-bottom:8px;">
	<!-- 搜索 -->
筛选类别：<?php $select_data[0] = '全选'; ksort($select_data);echo form_dropdown('cid', $select_data, $cid,' id="select_action"');?>
<a href="<?php echo site_url('cache_clear/cache?tag_type=2') ?>" class="add_cache">添加缓存</a>
	<!-- /搜索 -->
</div>
<!--自增编号、类别名称、地址、是否启用、备注、添加时间-->
<table id="list_tb" class="ui-table">
	<thead>
	<tr>
		<th>编号</th>
		<th>类别名称</th>
		<th>地址</th>
		<th>是否启用</th>
		<th>备注</th>
		<th>添加时间</th>
		<th>操作</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($contents as $c):?>
	<tr>
		<!--<td><input type="checkbox" name="ids[]" value="<?php echo $c['id']?>" /></td>-->
		<td><?php echo $c['id']?></td>
		<td><?php echo $c['cat_name']?></td>
		<td><?php echo $c['url']?></td>
		<td><?php echo $c['state']?'是':'否';?></td>
		<td><?php echo $c['mark']?></td>
		<td><?php echo date('Y-m-d H:i:s',$c['addtime']);?></td>
		<td>
			<a callback="reload" data-id="<?php echo $c['id'];?>" height="300" width="400" type="form" href="<?php echo site_url('cache_clear/cache_edit/');?>">编辑</a>
			<a href="javascript:;"  onclick="delete_url(<?php echo $c['id']; ?>);return false;" >删除</a>
		</td>
	</tr>
	<?php endforeach;?>
	</tbody>
	<?php if (isset($list_count)):?>
		<tfoot>
		<tr>
			<td colspan="7" style="text-align:left;padding:12px;">
				<!-- 	<label><input class="checkAll" type="checkbox" name="" />&nbsp;全选</label>
					<input id="btn_block" callback="reload" type="button" value="屏蔽" class="ui-form-btnSearch" />
					<input id="btn_unblock" callback="reload" type="button" value="显示" class="ui-form-btnSearch" />
					<input id="btn_del" callback="reload" title="批量删除" type="button" value="删除" class="ui-form-btnSearch" />
					分页 -->
				<div class="ui-paging floatR">
					<?php echo $pager;?>
				</div>
			</td>
		</tr>
		</tfoot>
	<?php endif;?>
</table>
<script type="text/javascript">
	$("#select_action").change(function(){
		location.href ="<?php echo site_url("/cache_clear/cache?tag_type=4&cid=")?>"+$(this).val();
	});
	function delete_url(id) {
		artDialog({id: 'artFormDialog'}).close();
		artDialog({
			id: 'artFormDialog',
			title: '是否删除',
			width: 300,
			height: 100,
			okVal:'删除',
			content: '<p>是否删除？</p>',
			ok: function () {
				var url = '<?php echo site_url('cache_clear/delete_url')?>';
				$.ajax({
					url:url,
					data:'id='+id,
					dataType:'json',
					success:function(back){
						if(back.type == 'SUCCESS'){
							alert('删除成功');
							window.location.reload();
						}else{
							alert('删除失败');
						}
					}
				});
			},
			cancel: true,
			fixed: true,
			lock: true
		}).confirm();
		$('.aui_buttons button').eq(1).hide(); // 隐藏取消按钮
	}
</script>