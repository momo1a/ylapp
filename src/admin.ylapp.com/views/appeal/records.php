<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<?php change_to_minify("javascript/common/jquery/simpleToolTip/style-simpletooltip-min.css"); ?>

<div class="ui-box ui-box2 records"><div class="ui-box-outer"><div class="ui-box-inner"> 
<div class="ui-box-head records-head">申诉管理</div>
<div class="ui-box-body ui-tab">
	<ul class="ui-tab-nav">
		<?php if(is_array($appeal_type)): $i=0; foreach ($appeal_type as $k=>$v):?>
		<li class="ui-tab-item<?php if(!$i):?> ui-tab-itemCurrent<?php endif;?>" <?php if($i):?>data-type_id="<?php echo $v['id']; ?>"<?php endif;?>>
			<?php if( $v['shield'] == Order_appeal_type_model::SHIELD_ON ):?>
			<a href="javascipt:;" onclick="return false"><em style="color: #aaa;"><?php echo $v['name'].'&nbsp;'; echo $v['count'];?></em></a>
			<?php else:?>
			<a href="javascipt:;" onclick="return false"><?php echo $v['name']; if ($v['count']>0):echo '<em style="color:red;">&nbsp;'.$v['count'].'</em>'; else: echo '<em style="color:#009900;">&nbsp;0</em>'; endif;?></a>
			<?php endif;?>
		</li>
		<?php $i += 1; endforeach; endif;?>
	</ul><!-- /ui-tab-nav -->
	<div class="ui-tab-cont">
		<?php if(is_array($appeal_type)): $i=0; foreach ($appeal_type as $k=>$v): $type_id = $v['id'];?>
		<div id="appeal_records_<?php echo $type_id;?>" class="ui-tab-panel" <?php if($i):?>style="display:none;"<?php endif;?>>
			<?php $this->load->view('appeal/records_rows');?>
		</div><!-- /ui-tab-panel -->
		<?php $i += 1; endforeach; endif;?>
	</div><!-- /ui-tab-cont -->
</div>
</div></div></div>
<script>
	/*选项卡功能*/
	$(function(){
		$(".ui-tab-item").click(function(){
			var $this = $(this);
			if($this.data()['type_id']){
				// 第一次加载内容
				load("<?php echo site_url($this->router->class.'/'.$this->router->method.'/'.$utype_str)?>", "div#appeal_records_"+$this.data()['type_id'], {type_id:$this.data()['type_id'], listonly:'yes'});
				$this.removeData('type_id');
			}
			$this.addClass("ui-tab-itemCurrent").siblings($this.selector).removeClass("ui-tab-itemCurrent");
			var $panel = $this.closest(".ui-tab").find(".ui-tab-panel").eq($this.index());//获取对应的panel
			$panel.show().siblings(".ui-tab-panel").hide();
		});
	});

</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>