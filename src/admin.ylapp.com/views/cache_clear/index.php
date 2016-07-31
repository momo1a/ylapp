<?php if (!$this->input->is_ajax_request()) {
	$this->load->view('public/wrap_top');
} ?>
	<div class="ui-box ui-box2">
		<div class="ui-box-outer">
			<div class="ui-box-inner">
				<div class="ui-box-head"><span class="ui-box-tit">缓存管理</span></div>
				<div class="ui-box-body">
					<div class="ui-tab">
						<ul class="ui-tab-nav">
							<li id="help_list" class="ui-tab-item <?php if ($tag_type === 1): echo 'ui-tab-itemCurrent'; endif; ?>">
								<a href="<?php echo site_url('cache_clear/cache?tag_type=1') ?>" data-selected="<?php if ($tag_type === 1): echo $selected; endif; ?>">类别管理</a>
							</li>
							<li id="help_add" class="ui-tab-item <?php if ($tag_type === 4): echo 'ui-tab-itemCurrent'; endif; ?>">
								<a href="<?php echo site_url('cache_clear/cache?tag_type=4') ?>" data-selected="<?php if ($tag_type === 4): echo $selected; endif; ?>">地址管理</a>
							</li>
							<li id="help_add" class="ui-tab-item <?php if ($tag_type === 3): echo 'ui-tab-itemCurrent'; endif; ?>">
								<a href="<?php echo site_url('cache_clear/cache?tag_type=3') ?>" data-selected="<?php if ($tag_type === 3): echo $selected; endif; ?>">清除日志</a>
							</li>
						</ul>
						<div class="ui-tab-cont">
							<?php if ($tag_type === 1): ?>
								<!-- 缓存类别列表 -->
								<div id="div_list" class="ui-tab-panel">
									<?php $this->load->view('cache_clear/list_cache'); ?>
								</div><!-- /ui-tab-panel -->
								<!-- /缓存类别列表 -->
							<?php elseif ($tag_type === 2): ?>
								<!-- 添加缓存 -->
								<div id="div_add" class="ui-tab-panel">
									<?php $this->load->view('cache_clear/add'); ?>
								</div><!-- /ui-tab-panel -->
								<!-- /添加缓存 -->
							<?php elseif ($tag_type === 3): ?>
								<!-- 处理缓存日志 -->
								<div id="div_edit" class="ui-tab-panel">
									<?php $this->load->view('cache_clear/list_log'); ?>
								</div><!-- /ui-tab-panel -->
								<!-- /处理缓存日志 -->
							<?php elseif ($tag_type === 4): ?>
								<!-- 类目缓存地址列表 -->
								<div id="div_edit" class="ui-tab-panel">
									<?php $this->load->view('cache_clear/list_url'); ?>
								</div><!-- /ui-tab-panel -->
								<!-- /类目缓存地址列表 -->
							<?php endif; ?>
						</div>
						<!-- /ui-tab-cont -->
					</div>
				</div>
			</div>
		</div>
	</div>

<?php if (!$this->input->is_ajax_request()) {
	$this->load->view('public/wrap_foot');
} ?>
<script type="text/javascript">
	/*----- 全选插件 -----*/
	(function($) {
		$.fn.checkAll = function(checkbox) {/*参数：匹配需要被选中的checkbox的选择器;*/
			var $cAll = this.eq(0), $cBox = $(checkbox);
			$cAll.click(function() {
				$cBox.prop("checked", $cAll.prop("checked"));
			});
			$cBox.click(function() {
				var len = $cBox.length, trueLen = $cBox.filter(":checked").length;
				$cAll.prop("checked", len === trueLen);
			});
		}
	})(jQuery);
	/*应用全选插件*/
	$(".checkAll").each(function(){
		var self = $(this);
		var elm = $( "tbody input[type=checkbox]", self.closest(".ui-tab-panel") );
		self.checkAll(elm);
	});
</script>