<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 checkoutList"><div class="ui-box-outer"><div class="ui-box-inner"> 

<div class="ui-box-head">
	<h3>结算记录</h3>
</div>
<div class="ui-box-body" style="padding:15px 0 0 15px;">
	<h3>活动标题：<?php echo $goods['title'];?></h5>
	<?php if(isset($goods_checkout['trade_time']) && $goods_checkout['trade_time']>0):?>
	结算记录：于<?php echo date("Y-m-d H:i:s",$goods_checkout['trade_time']);?>结算<?php echo $goods_checkout['num'];?>份，返还担保金<?php echo $goods_checkout['guaranty'];?>元，返还服务费<?php echo $goods_checkout['fee'];?>元<?php if($goods['type'] == Goods_model::TYPE_SEARCH_BUY){?>，返还搜索奖励金<?php echo $goods_checkout['search_reward'];?>元。<?php }else{?>。<?php }?>
	<?php endif;?>
</div>
<div class="ui-box-body ui-tab">
	<ul class="ui-tab-nav">
		<li class="ui-tab-item ui-tab-itemCurrent"><a href="javascipt:;" onclick="return false">返现(<?php echo $count;?>)</a></li>
		<li class="ui-tab-item" data-type_id="2"><a href="javascipt:;" onclick="return false">单笔结算(<?php echo $checkout_count;?>)</a></li>
	</ul><!-- /ui-tab-nav -->
	<div class="ui-tab-cont">
		<div id="checkout_list_1" class="ui-tab-panel" >
			<?php $this->load->view('goods/balance_log');?>
		</div><!-- /ui-tab-panel -->
		<div id="checkout_list_2" class="ui-tab-panel" style="display:none;">
			<?php $this->load->view('goods/balance_log', array('logs'=>array(),'pager'=>''));?>
		</div><!-- /ui-tab-panel -->
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
				load("<?php echo site_url($this->router->class.'/'.$this->router->method.'/'.$segment);?>", "div#checkout_list_"+$this.data()['type_id'], {type:$this.data()['type_id'], listonly:'yes'});
				$this.removeData('type_id');
			}
			$this.addClass("ui-tab-itemCurrent").siblings($this.selector).removeClass("ui-tab-itemCurrent");
			var $panel = $this.closest(".ui-tab").find(".ui-tab-panel").eq($this.index());//获取对应的panel
			$panel.show().siblings(".ui-tab-panel").hide();
		});
	});
</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>