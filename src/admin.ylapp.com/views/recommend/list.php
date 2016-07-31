<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 appealList"><div class="ui-box-outer"><div class="ui-box-inner"> 

<div class="ui-box-head">频道管理</div>
<div class="ui-box-body ui-tab">
	<ul class="ui-tab-nav">
		<?php if(is_array($child_data)): $i=0; foreach ($child_data as $k=>$v):?>
		<li class="ui-tab-item<?php if(!$i):?> ui-tab-itemCurrent<?php endif;?>" <?php if($i):?>data-id="<?php echo $v['id']; ?>"<?php endif;?>><a href="javascipt:;" onclick="return false"><?php echo $v['name']?></a></li>
		<?php $i += 1; endforeach; endif;?>
	</ul><!-- /ui-tab-nav -->
	<div class="ui-tab-cont">
		<?php if(is_array($child_data)): $i=0; foreach ($child_data as $k=>$v): $type_id = $v['id'];?>
		<div id="category_list_<?php echo $type_id;?>" class="ui-tab-panel" <?php if($i):?>style="display:none;"<?php endif;?>>
			<?php $this->load->view('recommend/goods_common');?>
		</div><!-- /ui-tab-panel -->
		<?php $i += 1; endforeach; endif;?>
	</div><!-- /ui-tab-cont -->
</div>

</div></div></div>

<script type="text/javascript">
	/*选项卡功能*/
	$(function(){
		$(".ui-tab-item").click(function(){
			var $this = $(this);
			if($this.data()['type_id']){
				// 第一次加载内容
				load("<?php echo site_url($this->router->class.'/'.$this->router->method.'/'.$segment);?>", "div#appeal_list_"+$this.data()['type_id'], {type_id:$this.data()['type_id'], listonly:'yes'});
				$this.removeData('type_id');
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
	MyRule.amount=/^(\+|-)?\d+(\.\d{1,2})?$/;
</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>