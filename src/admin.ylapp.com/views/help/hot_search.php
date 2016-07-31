<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<style>
	/*推送按钮样式*/
	.help-table-push a { display: inline-block; padding:2px 5px; border-radius: 3px; margin-top: 5px; white-space: nowrap; color:#fff; background-color: #AAAAAA; } /*用到td标签上去*/
	.help-table-push a:hover { color:#fff; text-decoration: none; background-color: #AAAAAA; }
</style>
<script type="text/javascript">
$(document).ready(function(){
	/*——更新按钮——，更新当前用户类型下的所影响的所有静态缓存页*/
	$('.update_html_cache').bind('click', function(){
		$.post('<?php echo site_url('help/callback_clear_html_cache')?>', function(rs){
			AjaxFilter(rs, $(this));	//过滤服务器返回的内容
		});
	});
});
</script>

<div class="ui-box ui-box2 appealList"><div class="ui-box-outer"><div class="ui-box-inner"> 

<div class="ui-box-head"><span class="ui-box-tit">热门搜索管理</span></div>
<div class="ui-box-body">
	<table class="ui-table2">
		<col span="1" style="width:6em;" /><col span="1" />
		<tr>
			<th style="vertical-align: top;">热门搜索:</th>
			<td>
				<select id="searchHotKeywords" name="" multiple="multiple" style="min-height:120px;min-width:120px;">
					<?php foreach ($hot_keywords as $c):?>
					<option value="<?php echo $c['id']?>"><?php echo $c['keyword']?></option>
					<?php endforeach;?>
				</select>
				<p style="margin-top:8px;"> 
					<input callback="reload" type="button" name="" value="添加" class="ui-form-btnSearch" id="addHotKey" />
					<input callback="reload" type="button" name="" value="删除" class="ui-form-btnSearch" id="delHotKey" />
					<input type="button" name="" value="更新" class="ui-form-button ui-form-buttonBlue update_html_cache" />
				</p>
			</td>
		</tr>
	</table>
	<table class="ui-table">
		<thead>
			<tr>
				<th>编号</th>
				<th>关键字</th>
				<th>搜索次数</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($contents as $c):?>
			<tr>
				<td><?php echo $c['id']?></td>
				<td class="js-hotKey"><?php echo $c['keyword']?></td>
				<td><?php echo $c['hit']?></td>
				<?php if ($c['is_push']==='1'):?>
				<td class="help-table-push" style="cursor: default;"><a>已推送</a></td>
				<?php else :?>
				<td class="ui-table-operate"><a callback="reload" id="<?php echo $c['id']?>" class="js-addHotKey" href="javascript:void(0);">添加到热门搜索</a>&nbsp;&nbsp;<a href="<?php echo site_url('help/delete_search/'.$c['id'])?>" type="confirm" title="确认删除该项？" callback="reload">删除</a></td>
				<?php endif;?>
			</tr>
			<?php endforeach;?>
		</tbody>
		<tfoot>
			<tr>	
				<?php if (count($contents)):?>
				<td colspan="4">
					<div class="ui-paging-center">
						<div class="ui-paging">
							<!-- 分页 -->
							<?php echo $pager;?>
						</div>
					</div>
				</td>
				<?php else :?>
				<td colspan="4">无记录</td>
				<?php endif;?>
			</tr>
		</tfoot>
	</table>
</div>

</div></div></div>
<script>
/*----- 热门关键字操作 -----*/
//学神仙 用用面向对象的js
var hotKeywords = (function() {
	return {
		remove: function(selector) {   // 删除关键字
			$(selector).remove();
		},
		add: function(elm) {    // 添加关键字
            $("#searchHotKeywords").prepend( $(elm) );
		}
	}
}());
//撤销推送的关键字
$("#delHotKey").click(function() {
	$this = $(this);
    var ids = $("#searchHotKeywords option:selected").map(function(){
		return $(this).val();
	}).get().join(",");	//返回选中记录的id，以逗号连接
	
    if(ids.length){
    	$.post('<?php echo site_url('help/hot_delete')?>', {'ids':ids}, function(rs){
    		AjaxFilter(rs, $this);	//过滤服务器返回的内容
    	});
    }else {
    	alert("你总得选一个吧，亲");
    }
    
});
//添加并推送热门关键字
$("#addHotKey").click(function() {
	$this = $(this);
	if(keyword = prompt("请输入关键字","")){
		$.post('<?php echo site_url('help/hot_add')?>', {'keyword':keyword, 'type':<?php echo $type?>}, function(rs){
			AjaxFilter(rs, $this);	//过滤服务器返回的内容
		});
	}
	
});

$(".js-addHotKey").click(function(){
	$this = $(this);
	var id = $this.attr("id");
	
	$.post('<?php echo site_url('help/hot_push')?>', {'id':id}, function(rs){
		AjaxFilter(rs, $this);	//过滤服务器返回的内容
	});
});

</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>