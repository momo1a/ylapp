function dt(ts) {
	var dt = new Date(ts * 1000);
	return dt.getFullYear() + "-" + (dt.getMonth() + 1) + "-" + dt.getDate() + " " + dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
}

var order = (function() {
	function get_order(oid) {
		for (var i = 0, l = order_data.length; i < l; i++) {
			if (order_data[i].oid == oid) {
				return order_data[i];
			}
		}
		return false;
	}

	function set_btn_state(win, can_use) {
		if (can_use) {
			win.DOM.buttons.find('button:first').html('确定').removeAttr('disabled').addClass('aui_state_highlight');
		} else {
			win.DOM.buttons.find('button:first').html('操作中...').attr('disabled', 'disabled').removeClass('aui_state_highlight');
		}
	}

	var domain = location.host.replace(/(\w+\.)*(\w+\.com)/, '.$2');
	function site(site){return'http://'+site+domain+'/';}

	// 计时器，用于判断是否显示验证码
	var cur_time = (function(){
		var node = parseInt(new Date().getTime()/1000);
		return function(){
			return parseInt(new Date().getTime()/1000)-node;
		}
	})();

	return {
		go_url : function(oid) {
			var this_order = get_order(oid);
			var cont = '请注意网购价格为:<em style="font-weight: 700; color:#c00; margin:0 5px; font-size: 16px;">' + this_order.price + '</em>元';
			var okv = '我知道了，现在去下单';
			$.ajax({
				url  : '/order/goods_info/'+this_order.gid,
				type: 'GET',
				cache: true,
				dataType: 'json',
				success:function(data){
					if (data.success && data.data.qrcode_state == 1) {
						cont = '<p>下单前请确认网购价格为:<em style="font-weight: 700; color:#c00; margin:0 5px; font-size: 16px;">' + this_order.price + '</em>元，并且此活动为二维码下单活动，请扫描二维码进入商家店铺购买（请勿用微信扫码）</p><p style="text-align: center;margin-top: 10px;"><img src="'+ data.data.qrcode_img +'" width="200" height="200"></p>';
						okv = '确定';
					};
					art.dialog({
						lock : true,
						fixed : true,
						id:'go_url',
						title : '商品下单提示',
						content : cont,
						okVal : okv,
						ok : function() {
							if (data.data.qrcode_state == 1) {
								return true;
							}else{
								window.open(this_order.url);
							}
						},
						cancel : true
					});
				},
				error:function(){
					art.dialog({
						lock  : true,
						fixed : true,
						icon  : 'error',
						title : '错误提示',
						content : '网络连接失败！',
						ok : true
					});
					qrcode.error = 1;
					return qrcode;
				}
			})
		},
		fill_trade_no : function(oid, code_url, in_time, order_fill_interval, get_order_number_help) {
			var this_order = get_order(oid);

			// 根据控制显示验证码的参数判断表单是否需要验证码
			var is_captcha = 0;
			$.ajax({
				url		: '/order/goods_info/'+this_order.gid,
				type	: 'GET',
				cache   : true,
				dataType: 'json',
				success	: function(ret){
					if(!ret.success){
						return art.dialog({
							lock : true,
							fixed: true,
							icon : 'error',
							title: '温馨提示',
							content: '找不到此商品',
							ok   : true
						});
					}
					var linkUrl=ret.data.get_order_number_help;
					var diy_form = '<form><table>'
						+ 		'<colgroup><col style="width:60px"><col style="width:350px"></colgroup>'
						+ 		'<tbody style="color:#797979;">'
						+ 			'<tr>'
						+ 				'<td>商品名称：</td>'
						+ 				'<td style="color: #06f">'+ this_order.title +'</td>'
						+ 			'</tr>'
						+ 			'<tr>'
						+ 				'<td>' + ret.data.source_name + '：</td>'
						+ 				'<td style="font-weight:bold;color:#000;">￥'+ this_order.price +'</td>'
						+ 			'</tr>'
						+ 			'<tr>'
						+ 				'<td>订单编号：</td>'
						+ 				'<td><input type="text" name="trade_no" class="ui-form-text ui-form-textRed" /><span class="trade-no-msg" style="color: #cc0000;"></span></td>'
						+ 			'</tr>'
						+ 			'<tr class="order-verify" style="display:none">'
						+ 				'<td>问题答案：</td>'
						+ 				'<td style="padding: 4px 0;">'
						+					'<img class="buy-verify-img" data-src="'+ code_url +'" src="" alt="点击刷新验证码" style="cursor:pointer" />'
						+					'<input type="text" class="ui-form-text ui-form-textRed" name="vcode" style="width:8em;" />'
						+					'<span class="buy-verify-msg" style="color: #cc0000;">'
						+				'</td>'
						+ 			'</tr>'
						+ 			'<tr>'
						+ 				'<td style="vertical-align: top;">温馨提示：</td>'
						+				'<td style="color:#999;">'
						+					'<p>1、请填写已付款的订单编号，若填入未付款单号，属于违规行为且将无法获得返现；<a style="color:#09f;" href="http://help.zhonghuasuan.com/buyer/category/66/125/16" target="_blank">填写的订单号规则？</a></p>'
						+					'<p>2、若单号被审核有误，请在 '+order_auto_close_time_hour+' 小时内进行申诉或修改，逾期将无法领回返现（建议平时经常登录网站查看站内信提醒哦！）<a style="color:#09f;display:block;" href="http://help.zhonghuasuan.com/buyer/category/63/81/124" target="_blank">如何获取订单编号？</a></p>'
						+				'</td>'
						+ 			'</tr>'
						+ 		'</tbody>'
						+ '</table></form>';
					art.dialog({
						lock	: true,
						fixed	: true,
						id		: 'fill_trade_no',
						title	: '填写单号',
						content	: diy_form,
						init	: function(){
							var dialog = this;
							var form  = this.DOM.content.find('form');
							var trade = form.find('input[name=trade_no]');
							var noMsg = form.find('.trade-no-msg').html('');
							var vcode = form.find("input[name='vcode']");
							var vcMsg = form.find('.buy-verify-msg').html('');
							var vcImg = form.find('.buy-verify-img');

							// 点击刷新验证码
							$('.buy-verify-img').click(function(){
								this.src = this.getAttribute('data-src') +'?'+ Math.floor( Math.random()*10e8 );
								$('.order-verify').show();
							});

							form.submit(function(){
								var trade_no_val   = $.trim( trade.val() );
								var vcode_val = vcode.val();
								var data = {trade_no:trade_no_val};
								noMsg.css('color', '#c00').html('');

								if ( trade_no_val=='' ) {
									noMsg.html(' 请输入订单号');
									trade.val('').focus();
									return false;
								}else if ( /[^\-0-9a-zA-Z]/.test( trade_no_val ) ) {
									noMsg.html('订单号有误');
									trade.val('').focus();
									return false;
								}else {
									noMsg.html('');
								}

								if( is_captcha == 1 ){
									data.is_captcha = is_captcha;
									data.vcode = vcode_val;
									vcMsg.html('');
									if( !$.trim( vcode_val ) ){
										vcMsg.html(' 验证码错误');
										vcImg.click();
										vcode.focus();
										return false;
									}else {
										vcMsg.html('');
									}
								}

								set_btn_state(dialog, false);
								noMsg.css('color', '#666').html(" 正在发送...");

								$.getJSON(order.matchUrl('/order/save_no/' + this_order.gid + '/' + oid), data, function(ret) {
									set_btn_state(dialog, true);

									if (ret.success) {
										dialog.close();
										art.dialog({
											lock : true,
											fixed : true,
											title : '填写单号成功',
											icon:'succeed',
											content : '<p style="line-height: 48px;font-size:16px;font-family: Microsoft YaHei">商家将在交易完成后审核返现。</p>',
											ok : function() {
												location.reload();
											}
										});
									} else {
										noMsg.html('').css('color', '#c00');
										// 有验证码情况下，操作出错，则刷新验证码
										is_captcha == 1 && vcImg.click() && vcode.val('').focus();

										switch(ret.data){
											case 'CAPTCHA_IS_NULL':
												vcMsg.html(' 请输入验证码！');
												// 无验证码情况下，填单过快，则刷新验证码
												is_captcha == 0 && vcImg.click();
												is_captcha = 1;
												break;
											case 'CAPTCHA_ERROR':
												vcMsg.html(' 验证码错误！');
												// 无验证码情况下，填单过快，则刷新验证码
												is_captcha == 0 && vcImg.click();
												is_captcha = 1;
												break;
											case 'TRADE_NO_IS_NULL':
												noMsg.html(' 请输入订单号！');
												trade.val('').focus();
												break;
											case 'TRADE_NO_ERROR':
												noMsg.html(' 订单号格式有误！');
												trade.val('').focus();
												break;
											case 'NO_BIND_MOBILE':
												noMsg.html('未认证手机号码，<a href="'+site('buyer')+'bind/mobile" target="_blank" style="color:#0066FF">现在去认证</a>');
												break;
											default:
												trade.val('');
												noMsg.html(' '+ret.data);
										}
									}
								});

								return false;
							});

							trade.focus();
						},
						ok		: function(){
							var form = this.DOM.content.find('form').submit();
							return false;
						},
						cancel : true
					});
				}
			});
		},
		appeal_reply : function(id){
			$.get(order.matchUrl('/order_appeal/reply/' + id), function(ret) {
				art.dialog({
					fixed : true,
					title : '回应申诉',
					drag : true,
					content : '弹窗内容',
					lock : true,
					id:'appeal_reply',
					padding : 0,
					content : ret,
					okVal:'提交',
					cancel:true,
					cancelVal:'关闭',
					init : function() {
						var self = this;
						this.DOM.content.find('form').submit(function(){self._click('提交');return false;});
					},
					ok : function() {
						var win = this;
						var appeal_id = $('#appeal_id').val();
						var form = this.DOM.content.find('form');
						var text = form.find('textarea').val();
						if ($.trim(text) == '') {
							alert('回应内容不能为空！');
							form.find('textarea').val('').focus();
							return false;
						}
						if ( text.length > 500){
							alert('回应内容不能超过500个字！');
							form.find('textarea').focus();
							return false;
						}

						if($("input[name^='img_']").length == 0 || $("input[name='img_1']").val() == false ){
							alert('请提交凭证图片！');
							return false;
						}

						set_btn_state(win, false);
						form.ajaxSubmit({
							type : "post",
							url : order.matchUrl("/order_appeal/reply_post/" + appeal_id),
							dataType : 'json',
							success : function(ret) {
								set_btn_state(win, true);
								if (ret.success) {
									alert('回应申诉成功，请等待管理员处理。');
									win.close();
									location.reload();
									return;
								}
								alert(ret.data);
							}
						});
						return false;
					}
				});
			});
		},
		view_appeal : function(appeal_id) {
			$.get(order.matchUrl('/order_appeal/read/' + appeal_id), function(ret) {
				art.dialog({
					title : '查看申诉',
					drag : true,
					lock : true,
					id:'view_appeal',
					padding : 0,
					content : ret,
					ok : true
				});
			});
		},
		add_show : function(oid) {
			var this_order = get_order(oid);
			$.ajax({
				type : "post",
				url:"/order/ajax_check_show/"+oid,
				dataType : 'json',
				success : function(ret) {
					if (ret.bool) {
						var html = [];
						html.push('<form class="woyao-show" method="post" enctype="multipart/form-data"><table>');
						html.push('<tr><th>标题:</th><td>', this_order.title, '</td></tr>');
						html.push('<tr><th>照片:</th><td><input type="file" name="img" /></td></tr>');
						html.push('<tr><th>评价:</td><td><textarea name="words"></textarea></td></tr>');
						html.push('</table></form>');
						art.dialog({
							lock : true,
							fixed : true,
							title : '我要晒单',
							id:'add_show',
							content : html.join(''),
							init : function() {
								var win = this;
								var form = this.DOM.content.find('form');
								form.submit(function() {
									var file = form.find('input[name=img]').val();
									if (!file || !file.match(/.jpg|.gif|.png|.bmp/i)) {
										alert('请上传商品实拍照片！');
										form.find('input[name=img]').focus();
										return false;
									}
									var text = form.find('textarea').val();
									if ($.trim(text) == '') {
										alert('评价内容不能为空！');
										form.find('textarea').val('').focus();
										return false;
									}

									set_btn_state(win, false);
									form.ajaxSubmit({
										type : "post",
										url : order.matchUrl("/order/add_show/" + oid),
										dataType : 'json',
										error:function(ret){
											set_btn_state(win, true);
											alert('系统错误，请稍后重试。');
										},
										success : function(ret) {
											set_btn_state(win, true);
											if (ret.success) {
												alert('晒单成功！');
												win.close();
												location.reload();
												return;
											}
											alert(ret.data);
										}
									});
									return false;
								});
							},
							ok : function() {
								this.DOM.content.find('form').submit();
								return false;
							}
						});
					}
					else{
						alert("亲，追加的商品再次抢购只需要晒单一次，返现和折扣保持不变正常返现!")
					}
				}
			});

		},
		view_log : function(oid) {
			var this_order = get_order(oid);
			$.get(order.matchUrl('/order/get_log/' + oid), function(ret) {
				ret = eval('(' + ret + ')');
				if (!ret.success) {
					alert(ret.data);
					location.reload();
					return;
				}

				var html = [];
				for (var i = 0, l = ret.data.length; i < l; i++) {
					html.push('<tr><td>' + ret.data[i].time + '</td><td>' + ret.data[i].content + '</td></tr>');
				}
				if (html.length) {
					html = '<div class="operate-log"><table class="ui-table"><col style="width:160px;" /><col style="width:30em;" /><tr><th>日期</th><th>内容</th></tr>' + html.join('') + '</table></div>';
				} else {
					html = '<p class="no-data">暂无记录信息。</p>';
				}

				art.dialog({
					lock : true,
					fixed : true,
					title : '抢购记录',
					id:'view_log',
					content : html,
					ok : true
				});
			});
		},
		close : function(oid){
			//订单在待填写单号情况下关闭订单
			art.dialog({
				lock : true,
				fixed : true,
				icon  : 'question',
				title : '关闭订单提示',
				content : '关闭后将不能填写订单号，确定要关闭么？',
				cancel:true,
				ok : function(){
					var dialog = this;
					set_btn_state(dialog, false);
					$.ajax({
						url		: order.matchUrl('/order/close/' + oid),
						type	: 'GET',
						dataType: 'json',
						error	: function(){
							dialog.close();
							art.dialog({
								lock : true,
								fixed : true,
								icon  : 'error',
								title : '错误提示',
								content : '网络连接失败！',
								ok : true
							});
						},
						success	: function(ret){
							dialog.close();
							isSuccess = ret.success ? 1 : 0;
							art.dialog({
								lock : true,
								fixed : true,
								icon  : ['error','succeed'][isSuccess],
								title : '订单关闭'+['失败','成功'][isSuccess],
								content : ret.data,
								ok : true,
								close:function(){
									if(isSuccess){
										location.reload();
									}
								}
							});
						}
					});
					return false;
				}
			});
		},
		user_edit_trade_no :function(oid){
			// 修改单号
			var this_order = get_order(oid);
			$.ajax({
				url     : '/order/goods_info/'+this_order.gid,
				type	: 'GET',
				cache   : true,
				dataType: 'json',
				success	: function(ret){
					var linkUrl=ret.data.get_order_number_help;
					var diy_form = '<form><table>'
						+ 		'<colgroup><col style="width:60px"><col style="width:350px"></colgroup>'
						+ 		'<tbody style="color:#797979;">'
						+ 			'<tr>'
						+ 				'<td>商品名称：</td>'
						+ 				'<td style="color: #06f">'+ this_order.title +'</td>'
						+ 			'</tr>'
						+ 			'<tr>'
						+ 				'<td>' + ret.data.source_name + '：</td>'
						+ 				'<td style="font-weight:bold;color:#000;">￥'+ this_order.price +'</td>'
						+ 			'</tr>'
						+ 			'<tr>'
						+ 				'<td>订单编号：</td>'
						+ 				'<td><input type="text" name="trade_no" class="ui-form-text ui-form-textRed" /><span class="trade-no-msg" style="color: #cc0000;"></span></td>'
						+ 			'</tr>'
						+ 			'<tr>'
						+ 				'<td style="vertical-align: top;">温馨提示：</td>'
						+				'<td style="color:#999;">'
						+					'<p>1、请填写已付款的订单编号，若填入未付款单号，属于违规行为且将无法获得返现；<a style="color:#09f;" href="http://help.zhonghuasuan.com/buyer/category/66/125/16" target="_blank">填写的订单号规则？</a></p>'
						+					'<p>2、若单号被审核有误，请在 '+order_auto_close_time_hour+' 小时内进行申诉或修改，逾期将无法领回返现（建议平时经常登录网站查看站内信提醒哦！）<a style="color:#09f;display:block;" href="http://help.zhonghuasuan.com/buyer/category/63/81/124" target="_blank">如何获取订单编号？</a></p>'
						+				'</td>'
						+ 			'</tr>'
						+ 		'</tbody>'
						+ '</table></form>';

					art.dialog({
						lock : true,
						fixed : true,
						title : '修改订单号',
						id:'user_edit_trade_no',
						content : diy_form,
						init : function() {
							var win = this;
							var form = this.DOM.content.find('form');
							var trade_no = form.find(':text');
							var span = form.find('.trade-no-msg').hide();

							trade_no.focusin(function(){span.html('').hide();});
							form.submit(function() {
								var no = $.trim(trade_no.val());
								if (!no) {
									trade_no.val('').focus();
									return false;
								}
								if (/[^\-0-9a-zA-Z]/.test( no )){
									span.show().html('订单号有误');
									return false;
								}

								set_btn_state(win, false);
								$.getJSON(order.matchUrl('/order/user_edit_no/' + oid + '/' + no), function(ret) {
									set_btn_state(win, true);
									if (ret.success) {
										win.close();
										art.dialog({
											lock : true,
											fixed : true,
											icon  : 'succeed',
											title : '修改单号成功',
											content : '商家将在交易完成后审核返现。',
											ok : function() {
												location.reload();
											}
										});
									} else {
										switch(ret.data){
											case 'TRADE_NO_ERROR':
												span.show().html('订单号格式有误！');
												break;
											case 'NO_BIND_MOBILE':
												span.show().html('未认证手机号码，<a href="'+site('buyer')+'bind/mobile" target="_blank" style="color:#0066FF">现在去认证</a>');
												break;
											default:
												span.html(ret.data).show();
										}
									}
								});

								return false;
							});
							trade_no.focus();
						},
						ok : function() {
							var form = this.DOM.content.find('form').submit();
							return false;
						},
						cancel : true
					});
				}
			});
		},
		appeal_cancel : function(oid,appeal_id){
			//撤销申诉
			art.dialog({
				lock : true,
				fixed : true,
				icon  : 'question',
				title : '撤销申诉提示',
				content : '撤销申诉后抢购状态将恢复到申诉前的状态；确定要撤销申诉？',
				cancel:true,
				ok : function(){
					var dialog = this;
					set_btn_state(dialog, false);
					$.ajax({
						url		: order.matchUrl('/order_appeal/cancel/'+oid+'/'+appeal_id),
						type	: 'GET',
						dataType: 'json',
						error	: function(){
							dialog.close();
							art.dialog({
								lock  : true,
								fixed : true,
								icon  : 'error',
								title : '错误提示',
								content : '网络连接失败！',
								ok : true
							});
						},
						success	: function(ret){
							dialog.close();
							isSuccess = ret.success ? 1 : 0;
							art.dialog({
								lock  : true,
								fixed : true,
								icon  : ['error','succeed'][isSuccess],
								title : '申诉撤销'+['失败','成功'][isSuccess],
								content : ret.data,
								ok : true,
								close:function(){
									if(isSuccess){
										location.reload();
									}
								}
							});
						}
					});
					return false;
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
})();

// 订单倒计时
$(function() {
	function count_down(sec) {
		if(sec<=0) return '-';
		var s = sec;
		var left_s = s % 60;
		var m = Math.floor(s / 60);
		var left_m = m % 60;
		var h = Math.floor(m / 60);
		var left_h = h % 24;
		var d = Math.floor(h / 24);

		var ret = [];
		d && ret.push('<span class="d">', d, '</span>天');
		left_h && ret.push('<span class="h">', left_h, '</span>时');
		left_m && ret.push('<span class="m">', left_m, '</span>分');
		left_s && ret.push('<span class="s">', left_s, '</span>秒');

		return ret.join('');
	}

	now += 3;
	$('.orderForm-statusTip em').each(function() {
		this.sec = parseInt($(this).attr('time'), 10);
		this.innerHTML = count_down(this.sec - now);
	});
	setInterval(function() {++now;
		$('.orderForm-statusTip em').each(function() {
			this.innerHTML = count_down(this.sec - now);
		});
	}, 1000);
});
