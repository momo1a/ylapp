<?php
if (!$this->input->is_ajax_request()) {
	$this->load->view('public/wrap_top');
}
?>
<?php

function user_name_color($uid, $yzcm, $mpg) {
	// 商家名称字体颜色
	$style = '';
	if (isset($yzcm[$uid])) {
		if ($yzcm[$uid]['deposit_type'] == 1) {
			if ($yzcm[$uid]['state'] == 2) {
				$style .=' color:#289728; ';
			}
		}
	}
	if (isset($mpg[$uid])) {
		if ($mpg[$uid]['deposit_type'] == 2) {
			if ($mpg[$uid]['state'] == 2) {
				$style .=' font-weight:bold; ';
			}
		}
	}
	return $style;
}
?>
<div class="ui-box ui-box2">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-head">
				<form rel="div#main-wrap" action="<?php echo site_url($this->router->class . '/' . $this->router->method); ?>" method="get"> 
					<?php echo form_dropdown('search_key', $this->search_map, $this->input->get_post('search_key'), 'id="search_key"'); ?>
					<input name="search_value"  class="ui-form-text ui-form-textRed" type="text" value="<?php echo $this->input->get_post('search_value'); ?>" />
					<button type="submit" class="ui-form-btnSearch"  id="sgoods">搜 索</button>
				</form>

			</div>
			<div class="ui-box-body">
				<h3>1.【隐藏/显示】只针对 <span style="color:red">已结算</span> 活动,且影响范围只在搜索页面与详情页面</h3>
				<h3>2.【隐藏/显示】设置成功后，前台商品详情页立即生效，搜索页面最迟10分钟后生效</h3>
				<table class="ui-table" id="alway_show_list">
					<thead>
						<tr>
							<th style="width:6%;">请选择</th>
							<th style="width:4%;">编号</th>
							<th style="width:28%;">活动标题</th>
							<th style="width:27%;">商家名称/商家编号</th>
							<th style="width:8%;">活动状态</th>
							<th style="width:8%;">活动类型</th>
							<th style="width:10%;">前台显示状态</th>
							<th style="width:10%;">操作</th>
						</tr>
					</thead>
					<tbody>
						<?php if (is_array($items)): ?>
							<?php
							foreach ($items as $k => $v):
								if ($v['state'] == 32) {
									$seed = $v['uid'];
								} else {
									$seed = $v['dateline'];
								}
								$goods_link = create_fuzz_link($v['gid'], $v['state'], $seed);
								$alway_show_str='已隐藏';
								$change_action='显示';
								if ($v['alway_show'] == 1) {
									$alway_show_str='显示';
									$change_action='隐藏';
								}
								?>
								<tr>
									<td><input type="checkbox" name="gids[]" value="<?php echo $v['gid']; ?>" /></td>
									<td><?php echo $v['gid']; ?></td>
									<td><a href="<?php echo $goods_link; ?>" target="_blank"><?php echo $v['title']; ?></a></td>
									<td><span style=" <?php echo user_name_color($v['uid'], $yzcm, $mpg); ?>"><?php echo $v['uname']; ?></span>/<?php echo $v['uid']; ?></td>
									<td>
										<?php echo $goods_util->get_status($v['state']); ?>
									</td>
									<td>
										<?php echo $goods_util->get_goods_type($this->goods_types_map, $v['type']); ?>
									</td>
									<td>
										<?php echo $alway_show_str?>
									</td>
									<td class="ui-table-operate">
										<a href="<?php echo site_url('goods/change_alway_show')?>" 
											 type="confirm" callback="reload" title="确定要<?php echo  $change_action?>该活动吗？" 
											 data-id="<?php echo $v['gid'].'-'.$v['alway_show']?>" class="ui-operate-button">
												 <?php echo  $change_action?>
										</a>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="13" style="text-align:left;padding:12px;">
								<label><input type="checkbox" />&nbsp;全选/取消</label>
								<input id="btn_block" callback="reload" type="button" value="批量隐藏" class="ui-form-btnSearch" />
								<input id="btn_unblock" callback="reload" type="button" value="批量显示" class="ui-form-btnSearch" />
								<div class="ui-paging floatR"><?php echo $pager; ?></div></td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#btn_block").bind("click",function(){	//屏蔽
		var $this = $(this);
		var gids = $("#alway_show_list input[type='checkbox'][class!='checkAll'][name^='gids']:checked").map(function(){
			return $(this).val();
		}).get().join(",");	//返回选中记录的gid，以逗号连接

		if(gids.length<1){ PopupTips("请选择一个活动", 'notice', 1000); return false; }
		art.dialog({
			lick   : true,
			icon   : "question",
			title  : "操作提示",
			content: "确定要隐藏所选的活动？",
			cancel : true,
			ok:function(){
				$.post('<?php echo site_url('goods/change_alway_show')?>', {'gids':gids, 'to_change':2}, function(rs){
					AjaxFilter(rs, $this);	//过滤服务器返回的内容
				});
			}
		});
	});
	$("#btn_unblock").bind("click",function(){	//显示
		var $this = $(this);
		var gids = $("#alway_show_list input[type='checkbox'][class!='checkAll'][name^='gids']:checked").map(function(){
			return $(this).val();
		}).get().join(",");	//返回选中记录的gid，以逗号连接

		if(gids.length<1){ PopupTips("请选择一个活动", 'notice', 1000); return false; }
		art.dialog({
			lick   : true,
			icon   : "question",
			title  : "操作提示",
			content: "确定要显示所选的活动？",
			cancel : true,
			ok:function(){
				$.post('<?php echo site_url('goods/change_alway_show')?>', {'gids':gids, 'to_change':1}, function(rs){
					AjaxFilter(rs, $this);	//过滤服务器返回的内容
				});
			}
		});
	});
	
	/*要求jQuery版本在1.6以上*/
	;
	(function ($) {
		$.fn.checkAll = function (checkbox) { /*参数：匹配需要被选中的checkbox的选择器;*/
			var $cAll = this.eq(0),
							$cBox = $(checkbox);
			$cAll.click(function () {
				$cBox.prop("checked", $cAll.prop("checked"));
			});
			$cBox.click(function () {
				var len = $cBox.length,
								trueLen = $cBox.filter(":checked").length;
				$cAll.prop("checked", len === trueLen);
			});
		}
	})(jQuery);
	// 全选功能
	$(function () {
		$('tfoot input[type=checkbox]').checkAll('tbody input[type=checkbox]');
	});
	//搜索商品
	$("#sgoods").click(function () {
		$("form:eq(0)").attr('rel="div#main-wrap"');
		$("form:eq(0)").attr("action", "<?php echo site_url($this->router->class . '/' . $this->router->method); ?>").submit();
	});
</script>
<?php
if (!$this->input->is_ajax_request()) {
	$this->load->view('public/wrap_foot');
}?>