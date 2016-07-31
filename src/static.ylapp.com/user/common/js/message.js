$(function(){


/**
 * 确认
 * @param	{String}	消息内容
 * @param	{Function}	确定按钮回调函数
 * @param	{Function}	取消按钮回调函数
 */
art.confirm = function(content, yes, no) {
	return art.dialog({
		id : 'Confirm',
		title : '操作提示',
		icon : 'question',
		fixed : true,
		lock : true,
		opacity : .1,
		content : content,
		ok : function(here) {
			return yes.call(this, here);
		},
		cancel : function(here) {
			return no && no.call(this, here);
		}
	});
};
/**
 * 提示
 * @param	{String}	提示内容
 * @param	{Number}	显示时间 (默认1.5秒)
 * @param	{Function}	提示框初始化时执行
 * @param	{Function}	提示框关闭时执行
 * @param	{String}	icon图标，默认null
 */
art.note = function(content, initfunc, closefunc, icon) {
	var _icon;
	if (icon == 'E') {
		_icon = 'error';
	} else if (icon == 'S') {
		_icon = 'succeed';
	} else if (icon == 'Q') {
		_icon = 'question';
	} else if (icon == 'FSD') {
		_icon = 'face-sad';
	} else if (icon == 'FSE') {
		_icon = 'face-smile';
	} else if (icon == 'W') {
		_icon = 'warning';
	}else {
		_icon = null;
	}
	return art.dialog({
		id : 'Note',
		title : "温馨提示",
		fixed : true,
		lock : true,
		icon: (_icon || null),
		init : (initfunc || null),
		close : (closefunc || null)
	}).content('<div style="padding: 0 1em;">' + content + '</div>');
};




var Message = function($,art){
	// 设置弹窗按钮状态
	var set_btn_state = function(win, can_use) {
		if (can_use) {
			win.DOM.buttons.find('button:first').html('确定').removeAttr('disabled').addClass('aui_state_highlight');
		} else {
			win.DOM.buttons.find('button:first').html('操作中...').attr('disabled', 'disabled').removeClass('aui_state_highlight');
		}
	};

	// 存储全选复选框jQuery对象
	var Private_input;
	return {

		/**
		 * 一次性方法，用于事件绑定，本方法只会执行一次
		 * @param	{String}{Element}{jQuery.fn}	选择器，缺省.message-body
		 */
		initOne : (function(){
			var isFirst=true;
			return function($ps){
				if(!isFirst)return;
				isFirst = false;		// 锁
				var self = this;
				Private_input = $ps.find(".J_CheckAll").click( function(){self.checkAll(this)}).prop("checked",false);	// 全选（prop去火狐缓存）
				$ps.find(".J_CheckAll + a").click(function(){$(this).prev(":checkbox").click()});	// 全选
				$ps.find(".J_BatchRemove").click(function(){self.removeCheck()});					// 批量删除
				$ps.find(".J_SetReaded").click(function(){self.setReaded()});						// 标记为已读
			};
		})(),
		/**
		 * 初始化方法，用于事件绑定
		 * @param	{String}{Element}{jQuery.fn}	选择器，缺省.message-body
		 */
		init : function(parentSelector){
			var $ps = parentSelector ? $(parentSelector) : $(".message-body");
			var self = this;
			self.initOne($ps);
			$ps.find(".J_Remove").click(function(){self.remove($(this).closest('.J_MsgBox').data('id'))});	// 删除一条
			$ps.find(".J_ShowMsg,.J_MsgTitle").click(function(){self.show( $(this).closest('.J_MsgBox').data('id'))});	// 展开消息
			$ps.find(".J_HideMsg").click(function(){self.hide( $(this).closest('.J_MsgBox').data('id'))});	// 收起消息
			$ps.find(".J_NextPage").click(function(){self.nextPage()});	// 拉取下一页

			return this;	// 给外围闭包执行init专用
		},


		/**
		 * 消息全选、取消全选
		 * @param	{Element}{Boolean}	一个复选框的节点，或者布尔值，当为复选框对象时，会依据其checked属性作出对应操作
		 */
		checkAll:function(node){
			if(Object.prototype.toString.call(node)=="[object HTMLInputElement]"){
				$(".message-tab-cont ul li").find(":checkbox").prop("checked",$(node).prop("checked"));
			}
			else{
				$(".message-tab-cont ul li").find(":checkbox").prop("checked",node?true:false);
				Private_input.prop("checked", node?true:false);
			}
		},

		/**
		 * 删除一条或多条消息
		 * @param	{String}	消息id，多个id请用半角逗号“,”隔开
		 * @param	{String}	可缺省，询问提示文字
		 */
		remove:function(id,quest){
			var self = this;
			art.confirm(quest||"确认要删除此消息吗？",function(){
				var dialog = this;
				set_btn_state(dialog,false);
				$.ajax({
					type:'POST',
					url : Message.matchUrl("/message/remove/"),
					data: {id : id},
					dataType:"json",
					error:function(){
						set_btn_state(dialog,true);
						dialog.close();
						art.note("服务器繁忙，请稍后再试。",null,null,'E');
					},
					success:function(ret){
						set_btn_state(dialog,true);
						if (ret.success) {
							var ids = (id+'').split(',');
							var li = $(".J_MsgBox");
							for(var i=0;i<ids.length;i++){
								li.filter("[data-id="+ids[i]+"]").remove();
							}
							self.toPage(1);
							return;
						}
						art.note(ret.data,null,null,'E');
					}
				});
			});
		},

		/**
		 * 删除选中的消息
		 */
		removeCheck:function(){
			var ids = [];
			$('.message-tab-cont').find('li :checkbox:checked').each(function(){ids.push(this.value)});	
			if(ids.length<=0){
				art.note('您没有选择任何站内信！',null,null,'E');
				return false;
			}
			this.remove(ids.join(","),"您确定要删除所选的站内信吗?");
		},

		/**
		 * 将选中的消息标记为已读
		 */
		setReaded:function(){
			var ids=[],self=this;
			if($('.message-tab-cont').find('li :checkbox:checked').length<=0){
				art.note('您没有选择任何站内信！',null,null,'E');
				return false;
			}
			$('.message-noread').find('input:checked').each(function(){ids.push(this.value)});
			if(ids.length<=0){
				return false;
			}
			art.confirm("您确定要把所选的站内信标记为已读吗？",function(){
				var dialog = this;
				set_btn_state(dialog,false);
				$.ajax({
					type:'POST',
					url : Message.matchUrl("/message/read/"),
					data: {id : ids.join(",")},
					dataType:"json",
					error:function(){
						set_btn_state(dialog,true);
						dialog.close();
						art.note("服务器繁忙，请稍后再试。",null,null,'E');
					},
					success:function(ret){
						set_btn_state(dialog,true);
						dialog.close();
						if (ret.success) {
							var li = $(".J_MsgBox");
							for(var i=0;i<ids.length;i++){
								li.filter("[data-id="+ids[i]+"]").removeClass("message-noread").addClass('message-read').find(".message-icon").html("已读");
							}
							self.checkAll(false);
						}else{
							art.note(ret.data,null,null,'E');
						}
					}
				});
			});
		},

		/**
		 * 展开站内信
		 *  @param	{String}	消息ID
		 */
		show:function(id){
			var li = $(".J_MsgBox[data-id="+id+"]");
			li.addClass("message-show");			// 显示消息内容
			li.find(".J_HideMsg").show();			// 显示收起按钮
			li.find(".J_ShowMsg").hide();			// 隐藏展开按钮
			if(!li.hasClass('message-read')){
				$.get(Message.matchUrl('/message/read/'), {id:id}, function(ret){		// 向服务器发送一条请求标记已读
					ret = $.parseJSON(ret);
					if(ret.success){
						li.removeClass("message-noread").addClass("message-read");	// 添加样式，标记已读
						li.find(".message-icon").html("已读");
					}
				});
			}
		},

		/**
		 * 收起站内信
		 *  @param	{String}	消息ID
		 */
		hide:function(id){
			var li = $(".J_MsgBox[data-id="+id+"]");
			li.removeClass("message-show");			// 隐藏消息内容
			li.find(".J_HideMsg").hide();			// 隐藏收起按钮
			li.find(".J_ShowMsg").show();			// 显示展开按钮
		},


		/**
		 * 展开下一屏
		 */
		nextPage:function(){
			var self = this,
				$load = $('.J_Loading').show(),
				$next = $(".J_NextPage").hide(),
				$page = $next.data('p') || 1;
			$.get(Message.matchUrl('/message/next/' + ($page+1)), function(ret) {
				ret = $.trim(ret);
				if(ret){
					$load.hide();
					$next.data('p', $page+1).show();
					var html = $(ret);
					self.init(html);
					$('.message-tab-cont ul').append(html);
				}else{
					$('.message-more').html('<a href="javascript:void(0)">没有更多消息了</a>');
				}
			});
		},
		
		/**
		 * 到达指定页
		 */
		toPage:function(page){
			var self = this,
				$load = $('.J_Loading').show(),
				$next = $(".J_NextPage").hide(),
				$page = page || 1;
			$.get(Message.matchUrl('/message/next/' + $page), function(ret) {
				ret = $.trim(ret);
				if(ret){
					$load.hide();
					$next.data('p', $page+1).show();
					var html = $(ret);
					self.init(html);
					$('.message-tab-cont ul').html(html);
				}else{
					$('.message-more').html('<a href="javascript:void(0)">没有更多消息了</a>');
				}
			});
		},
		
		/**
		 * 匹配对应的请求地址
		 */
		matchUrl:function($uri){
			// $ver旧版路径
			$ver = /^(\/index.php)?\/old\//.test(location.pathname) ? '/old' : '';
			return $ver + $uri;
		}

	};
}(jQuery,art).init();


});