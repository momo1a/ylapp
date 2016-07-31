<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');};?>
<style>

	/*推送按钮样式*/
	.help-table-push a { display: inline-block; padding:2px 5px; border-radius: 3px; margin-top: 5px; white-space: nowrap; color:#fff; background-color: #AAAAAA; } /*用到td标签上去*/
	.help-table-push a:hover { color:#fff; text-decoration: none; background-color: #AAAAAA; }
</style>
<script type="text/javascript">

/*——更新按钮——，更新当前用户类型下的所影响的所有静态缓存页*/
$('.update_html_cache').bind('click', function(){
	$.post('<?php echo site_url('help/callback_clear_html_cache')?>', function(rs){
		AjaxFilter(rs, $(this));	//过滤服务器返回的内容
	});
});

//二级联动菜单——公共函数
function check_select(selector_child, pid){
	$(selector_child).html("<option value='0'>请选择分类</option>");	//重置子分类列表内容
	if(pid < 1){ return false; }	//主类id<1则不提交请求
	//异步获取子分类html
	$.post("<?php echo site_url('help/callback_child_cate');?>", { "type":<?php echo $type;?>, "pid":pid },  function(data){
		$(selector_child).html("<option value='0'>请选择分类</option>" + data);
	});
}

$(document).ready(function(){
	/*点击当前页签不再重复请求*/
	$(".ui-tab-nav li[id!='help_edit'] a").bind("click", function(){	//所有页签中，除了 id 为 help_edit 的都加上点击事件
		if($(this).attr('data-selected')==='yes'){	//判断点击的页签链接是否已经加载，不是则可以链接
			return false;
		}
	});
	
});

/*----- 功能简单的选项卡插件 -----*/
$(function(){
	$(".ui-tab-item").click(function(){
		var $this = $(this);
		
		$this.addClass("ui-tab-itemCurrent").siblings($this.selector).removeClass("ui-tab-itemCurrent");
		var $panel = $this.closest(".ui-tab").find(".ui-tab-panel").eq($this.index());//获取对应的panel
		$panel.show().siblings(".ui-tab-panel").hide();
	});

	
});
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

/*字符数实时统计插件*/
;(function($){
	$.fn.extend({
    // 回调函数在字符串长度统计完成后触发，this指向应用该插件的DOM元素，实参是统计得到的字符串长度；
    sumOfChars: function (options, callback) {    
        var settings = $.extend({
            eType: 'input',    // 事件类型  (ps：测试发现'input'事件在IE9下使用退格键删减内容时竟然不能触发！)
            isByte: false,      // 统计的长度类型, true表示统计字节(一个汉字两个字节)长度; false表示统计字符长度; 
            maxLength: false   // 限制输入长度，默认不限制
        }, options || {});
        // 当调用该插件时实参仅包含回调函数：
        typeof arguments[0] === 'function' && (callback = options);
        this.each(function(){
                var self = $(this),
                    type = settings.eType;
                // 'on'是jQuery 1.7+ 才有的方法
                self.on(type, _handler).triggerHandler(type);
                type === 'input' && self.on('propertychange', function(){   // IE 8-
                    // 如果发生改变的属性不是value就退出
                    if(!window.event || window.event.propertyName !== 'value') return;    
                    // 避免循环调用
                    $(this).off('propertychange', arguments.callee);
                    _handler.apply(this);
                    $(this).on('propertychange', arguments.callee);
                }).triggerHandler('propertychange');
                settings.maxLength && self.on('keypress textInput textinput', function (e) {
                    if( _count(this.value, settings.isByte) >= settings.maxLength)
                    	e.preventDefault();
                });
        });
        // 长度统计
        function _count (str, b) {
		    return b? str.replace(/[^\x00-\xff]/g, "aa").length : str.length;
        }
        // 事件处理程序
        function _handler (e) {
                var num = _count(this.value, settings.isByte);
                if( num > settings.maxLength){
                	while(_count(this.value, settings.isByte)>settings.maxLength){
                	 this.value = this.value.substr(0,this.value.length-1);
                	}
                	num = _count(this.value, settings.isByte);
                }
                typeof callback === 'function' && callback.apply(this, [num]);
        }
        return this;    // 返回jQuery对象以使其链式操作得以持续
        }
    });
}(jQuery));

// 字符数实时统计插件使用
$('.elm').sumOfChars({ maxLength: 50 }, function(n){
	$('#title_count').html("&nbsp;" + n + "/50");
} );

</script>

<div class="ui-box ui-box2"><div class="ui-box-outer"><div class="ui-box-inner"> 

<div class="ui-box-head"><span class="ui-box-tit">帮助列表</span></div>

<div class="ui-box-body">
	<div class="ui-tab">
		
		<ul class="ui-tab-nav">
			<li id="help_list" class="ui-tab-item <?php if($tag_type===1): echo 'ui-tab-itemCurrent'; endif;?>"><a href="<?php echo site_url('help/listing/'.$type_url)?>" data-selected="<?php if($tag_type===1): echo $selected; endif;?>">帮助列表</a></li>
			<li id="help_add" class="ui-tab-item <?php if($tag_type===2): echo 'ui-tab-itemCurrent'; endif;?>" <?php if($tag_type===3):?>style="display: none;"<?php endif;?>><a href="<?php echo site_url('help/listing/'.$type_url.'?tag_type=2')?>" data-selected="<?php if($tag_type===2): echo $selected; endif;?>">添加帮助</a></li>
			<li id="help_edit" class="ui-tab-item <?php if($tag_type===3): echo 'ui-tab-itemCurrent'; endif;?>" <?php if($tag_type!==3):?>style="display: none;"<?php endif;?>><a href="javascript:void(0);" data-selected="<?php if($tag_type===3): echo $selected; endif;?>">编辑帮助</a></li>
			<li id="help_usual" class="ui-tab-item <?php if($tag_type===4): echo 'ui-tab-itemCurrent'; endif;?>"><a href="<?php echo site_url('help/listing/'.$type_url.'?tag_type=4')?>" data-selected="<?php if($tag_type===4): echo $selected; endif;?>">常见问题</a></li>
		</ul>
		<div class="ui-tab-cont">
		
		<?php if($tag_type === 1):?>
			<!-- 帮助列表 -->
			<div id="div_list" class="ui-tab-panel" >
			<?php $this->load->view('help/list_rows');?>
			</div><!-- /ui-tab-panel -->
			<!-- /帮助列表 -->
		<?php elseif ($tag_type === 2):?>
			<!-- 添加帮助 -->
			<div id="div_add" class="ui-tab-panel">
			<?php $this->load->view('help/list_add');?>
			</div><!-- /ui-tab-panel -->
			<!-- /添加帮助 -->
		<?php elseif ($tag_type === 3):?>	
			<!-- 编辑帮助 -->
			<div id="div_edit" class="ui-tab-panel">
			<?php $this->load->view('help/list_edit');?>
			</div><!-- /ui-tab-panel -->
			<!-- /编辑帮助 -->
		<?php elseif ($tag_type === 4):?>
			<!-- 常见问题 -->
			<div id="div_question" class="ui-tab-panel">
			<?php $this->load->view('help/list_question');?>
			</div><!-- /ui-tab-panel -->
			<!-- /常见问题 -->
		<?php endif;?>
		</div><!-- /ui-tab-cont -->
		
	</div>
</div>

</div></div></div>


<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');};?>