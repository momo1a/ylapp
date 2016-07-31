<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>

<script type="text/javascript">
function recommend_sort(){
	var data = {};
	var v = $("input.sort_order").each(function(i,o){
		data[$(o).attr('name')] = $(o).val();
	});
	$.post(SITE_URL+"recommend/set_sort", data, function(rs){
		if(AjaxFilter(rs)){
			load('<?php echo site_url(uri_string());?>', $('div#RecommendList'), {listonly:'yes'})
		}
	},'json');
	return false;
}
</script>
<div class="ui-box ui-box2 show-order"><div class="ui-box-outer"><div class="ui-box-inner"> 

<div class="ui-box-body">
	<div id="RecommendList">
		<?php $this->load->view('recommend/show_list');?>
	</div>
	<div class="ui-box ui-box2" style="margin-top:20px;">
		<div class="ui-box-head">
			<form rel="div#searchList" method="get" action="<?php echo site_url('recommend/show_order')?>">
				<span>用户搜索:</span>
				<select id="type" name="search_key">
					<option value="uname">买家名称</option>
					<option value="uid">买家编号</option>
					<option value="title">活动标题</option>
					<option value="gid">活动编号</option>
				</select>
				<input class="ui-form-text ui-form-textRed" name="search_val"/>
				<input class="ui-form-btnSearch" type="submit" value="搜 索" />
				<input type="hidden" name="recommend_type" value="<?php echo $recommend_type?>" />
				<input type="hidden" name="uri_string" value="<?php echo uri_string()?>" />
				<input type="hidden" name="search_show" value="yes" />
			</form>
		</div>
		<div id="searchList" style="padding:15px;">
			<?php $this->load->view('recommend/search_show');?>
		</div>
	</div>
</div>


</div></div></div>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>