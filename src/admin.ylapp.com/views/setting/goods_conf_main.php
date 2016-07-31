<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>

<div class="ui-box ui-box2 appealList"><div class="ui-box-outer"><div class="ui-box-inner"> 

<div class="ui-box-head">活动发布设置</div>
<div class="ui-box-body ui-tab">
	<ul class="ui-tab-nav" id="top-tab-nav">

		<li class="ui-tab-item ui-tab-itemCurrent" data-type_id="general" data-url="<?php echo site_url('prompts/index');?>">
			<a href="javascipt:;" onclick="return false"><em style="color: #aaa;">通用项</em></a>
		</li>
		<li class="ui-tab-item" data-type_id="mpg" data-url="<?php echo site_url('setting/mpg');?>">
			<a href="javascipt:;" onclick="return false"><em style="color: #aaa;">名品馆</em></a>
		</li>
		<li class="ui-tab-item" data-type_id="yzcm" data-url="<?php echo site_url('setting/yzcm');?>">
			<a href="javascipt:;" onclick="return false"><em style="color: #aaa;">一站成名</em></a>
		</li>
		<li class="ui-tab-item" data-type_id="ssxd" data-url="<?php echo site_url('setting/search_buy');?>">
			<a href="javascipt:;" onclick="return false"><em style="color: #aaa;">搜索下单</em></a>
		</li>
        <li class="ui-tab-item" data-type_id="ssxd" data-url="<?php echo site_url('#');?>">
            <a href="javascipt:;" onclick="return false"><em style="color: #aaa;">众分期</em></a>
        </li>

	</ul><!-- /ui-tab-nav -->
	<div class="ui-tab-cont">
		<div id="goods_setting_general" class="ui-tab-panel"></div>
		<div id="goods_setting_mpg" class="ui-tab-panel" style="display:none;"></div>
		<div id="goods_setting_yzcm" class="ui-tab-panel" style="display:none;"></div>
		<div id="goods_setting_ssxd" class="ui-tab-panel" style="display:none;"></div>
		<!-- /ui-tab-panel -->
	</div><!-- /ui-tab-cont -->
</div>

</div></div></div>
<script type="text/javascript">
$(function(){
	$('#goods_setting_general').load('<?php echo site_url('prompts/index');?>');
	$("#top-tab-nav li").click(function(){
		var $this = $(this);
		// 取消批量全选的复选框
		$("input[type='checkbox'][name='appeal_ids[]']").attr('checked', false);
		if($this.data()['type_id']){
			// 第一次加载内容
			load($this.data()['url'], "div#goods_setting_"+$this.data()['type_id']);
			$this.removeData('type_id');
		}
		$this.addClass("ui-tab-itemCurrent").siblings($this.selector).removeClass("ui-tab-itemCurrent");
		var $panel = $this.closest(".ui-tab").find(".ui-tab-panel").eq($this.index());//获取对应的panel
		$panel.show().siblings(".ui-tab-panel").hide();
	});
});
</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>