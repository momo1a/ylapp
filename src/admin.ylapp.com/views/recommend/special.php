<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box"><div class="ui-box-outer"><div class="ui-box-inner"> 

<div class="ui-box-body">
	<div class="ui-tab">
		<?php if(is_array($special_category) && count($special_category)):?>
		<ul class="ui-tab-nav">
			<?php foreach ($special_category as $k=>$v):?>
			<li class="ui-tab-item <?php if(!$k):?>ui-tab-itemCurrent<?php endif;?>" <?php if($k):?>data-cate_id="<?php echo $v['id']; ?>"<?php endif;?>><a href="javascript:void(0);"><?php echo $v['name'];?></a></li>
			<?php endforeach;?>
		</ul><!-- /ui-tab-nav -->
		<?php endif;?>
		<div class="ui-tab-cont">
			<?php foreach ($special_category as $k=>$v):?>
			<div id="special_<?php echo $v['id'];?>" class="ui-tab-panel" <?php if($k):?>style="display:none;"<?php endif;?>>
				<?php $this->load->view('recommend/special_list', array('cate_id'=>$v['id']));?>
			</div><!-- /ui-tab-panel -->
			<?php endforeach;?>
		</div><!-- /ui-tab-cont -->
	</div><!-- /ui-tab -->

</div><!-- /ui-box-body -->

</div></div></div>

<script type="text/javascript">
	/*选项卡功能*/
	$(function(){
		$(".ui-tab-item").click(function(){
			var $this = $(this);
			if($this.data()['cate_id']){
				// 第一次加载内容
				load("<?php echo site_url($this->router->class.'/'.$this->router->method.'/'.$segment);?>", "div#special_"+$this.data()['cate_id'], {cate_id:$this.data()['cate_id'], listonly:'yes'});
				$this.removeData('cate_id');
			}
			$this.addClass("ui-tab-itemCurrent").siblings($this.selector).removeClass("ui-tab-itemCurrent");
			var $panel = $this.closest(".ui-tab").find(".ui-tab-panel").eq($this.index());//获取对应的panel
			$panel.show().siblings(".ui-tab-panel").hide();
		});
	});
	function checked_type(e){
		var $this = $(e);
		$this.closest('form').attr('action',$this.data()['action']);
		$("div.extinput_box").hide();
		if('addtime' == $this.find('input').val()){
			$("div#addtimebox").show();
		}
		if('adjust_rebate' == $this.find('input').val()){
			$("div#adjust_rebate_box").show();
		}
		if('adjust_tradeno' == $this.find('input').val()){
			$("div#adjust_tradeno_box").show();
		}
	}
	function special_sort(uri,rel,cid){
		var data = {};
		$("input.sort_order", rel).each(function(i,o){
			data[$(o).attr('name')] = $(o).val();
		});
		$.post(SITE_URL+"recommend/set_sort", data, function(rs){
			if(AjaxFilter(rs)){
				load(uri, $(rel), {listonly:'item',cate_id:cid})
			}
		},'json');
		return false;
	}
</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>