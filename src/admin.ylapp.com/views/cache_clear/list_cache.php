<!--自增编号、类别名称、地址、是否启用、备注、添加时间-->
<style>
	#add_button { display: inline-block; padding: 4px 10px 6px; border-radius: 2px; background-color: #3d4d66; color: #fff; }
	#add_button:hover { text-decoration: none; color: #fff;}
	#catForm input { width: 250px;}
</style>
<div class="clearfix" style="margin-bottom:8px;" id="option_list_div">
	<!-- 搜索 -->
	<a href="javascript:;" id="add_button">添加类别</a>
	<!-- /搜索 -->
</div>
<table id="list_tb" class="ui-table">
	<col width="50px">
	<col span="2">
	<thead>
	<tr>
		<th>选择</th>
		<th>类别名称</th>
		<th>操作</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($contents as $c):?>
	<tr>
		<td><input type="checkbox" class="checkbox" name="ids[]" value="<?php echo $c['cid']?>" /></td>
		<td><?php echo $c['cat_name'];?></td>
		<td>
			<a href="javascript:;" onclick="clear_action('<?php echo $c['cid']?>');return false;">清除缓存</a>
			<a href="javascript:;" onclick="delete_action('<?php echo $c['cid']?>');return false;">删除</a>
			<a data-cid="<?php echo $c['cid'];?>" height="550" width="960" type="dialog" href="<?php echo site_url('cache_clear/ajax_list_log')?>">操作记录</a>
		</td>
	</tr>
	<?php endforeach;?>
	</tbody>

		<tfoot>
		<tr>
			<td colspan="3" style="text-align:left;padding:12px;">
				<label><input class="checkAll" type="checkbox" name="" />&nbsp;全选</label>
					<input id="btn_clear" callback="reload" title="批量清除缓存" type="button" value="批量清除缓存" class="ui-form-btnSearch" />
		<?php if (isset($list_count)):?>
				<div class="ui-paging floatR">
					<?php echo $pager;?>
				</div>
		<?php endif;?>
			</td>
		</tr>
		</tfoot>
</table>
<script type="text/javascript">
	$("#add_button").on("click", function() {
		artDialog({
			id: 'artFormDialog',
			title: '添加类别',
			okVal:'确定',
			height: 90,
			content: '<form action="" method="post" id="catForm">类别名称：<input name="cat_name" type="text" class="ui-form-text ui-form-textRed"><span class="tips" style="display:none; padding-left:10px; color:red"></span></form>',
			ok: function () {
				var url = window.location.href;
				var cat_name = $("input[name=cat_name]").val();
				if (cat_name == "") {
					$("input[name=cat_name]").focus();
					$("#catForm").find(".tips").html("请输入类别名称！").show();
					return false;
				} else {
					$.ajax({
						url:url,
						type: "post",
						data:{cat_name: cat_name},
						dataType:'json',
						success:function(data){
							if(data.state == true){
								alert(data.msg);
								window.location.reload();
							}else{
								alert(data.msg);
							}
						}
					});
				}
			},
			cancel: true,
			fixed: true,
			lock: true
		});
	});

	function clear_action(cid) {
		artDialog({id: 'artFormDialog'}).close();
		artDialog({
			id: 'artFormDialog',
			title: '是否清除缓存',
			width: 300,
			height: 100,
			okVal:'确定清除',
			content: '<p>是否清除缓存？</p>',
			ok: function () {
				var url = '<?php echo site_url('cache_clear/clear_action')?>';
				$.ajax({
					url:url,
					data:'cid='+cid,
					dataType:'json',
					success:function(back){
						if(back.type == 'SUCCESS'){
							alert(back.msg);
							window.location.reload();
						}else{
							alert(back.msg);
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

	function delete_action(cid) {
		artDialog({id: 'artFormDialog'}).close();
		artDialog({
			id: 'artFormDialog',
			title: '是否确定删除',
			width: 300,
			height: 100,
			okVal:'确定删除',
			content: '<p>是否确定删除</p>',
			ok: function () {
				var url = '<?php echo site_url('cache_clear/delete_catogery')?>';
				$.ajax({
					url:url,
					data:'cid='+cid,
					dataType:'json',
					success:function(back){
						if(back.type == 'SUCCESS'){
							alert(back.msg);
							window.location.reload();
						}else{
							artDialog({id: 'artFormDialog'}).close();
							art.dialog({
								id: 'artFormDialog',
								content: '当前类别下有缓存地址，无法删除',
								button: [
									{
										name: '查看',
										callback: function () {
											location.href = "<?php echo site_url('/cache_clear/cache?tag_type=4&cid=')?>"+cid;
											return false;
										},
										focus: true
									},
									{
										name: '关闭'
									}
								]
							});
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

	$('.checkAll').on('click', function() {//全选
        if ($(this).is(':checked')) {
            $('.checkbox').attr('checked', 'checked');
        } else {
            $('.checkbox').removeAttr('checked');
        }
    });
	$("#btn_clear").bind("click", function () {	//显示
		var $this = $(this);
		var ids = $("#list_tb input[type='checkbox'][class!='checkAll'][name^='ids']:checked").map(function () {
			return $(this).val();
		}).get();	//返回选中记录的id，以逗号连接
		if (ids.length < 1) {
			alert("请勾选需要操作的内容");
			return false;
		}
		$.post('<?php echo site_url('cache_clear/clear_action')?>', {'cid': ids}, function (rs) {
			AjaxFilter(rs, $this);
		});
	});
</script>