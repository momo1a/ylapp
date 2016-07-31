/**
 * 众划算详情页功能js
 * Author: 陆楚良
 * Date: 2015/01/08
 * QQ: 519998338
 */

shs.detail = (function(window, $, art, shs, goods, goods_info, Global, undefined){

	/* ---------------------------- 方法集合 Begin ---------------------------- */
	var fn = {};
	//弹出提示弹窗
	fn.note = function(msg) {
		return art.dialog({
			lock  : true,
			fixed : true,
			title : '温馨提示',
			content : msg
		});
	};

	// 没有标题的短暂提示
	fn.tips = function(content, time, initfunc, closefunc, icon) {
		var _icon;
		switch(icon.toUpperCase()){
			case "E":_icon = "error";break;
			case "S":_icon = "succeed";break;
			case "Q":_icon = "question";break;
			case "w":_icon = "warning";break;
			case "FSD":_icon = "face-sad";break;
			case "FSE":_icon = "face-smile";break;
			default:_icon = null;break;
		}
		return art.dialog({
			title : false,
			cancel : false,
			fixed : true,
			lock : true,
			icon: (_icon || null),
			init : (initfunc || null),
			close : (closefunc || null)
		}).content('<div style="padding: 0 1em;">' + content + '</div>').time(time || 1);
	};

	// 未认证手机号的弹窗提示
	fn.not_bind_mobile = function(){
		art.dialog({
			lock  : true,
			fixed : true,
			title : '温馨提示',
			content : '<p style="color:#FF3300;font-weight: bold;font-size:16px;">抢购失败</p>'
					 +'<p>您还未认证手机号码，暂时无法抢购。请认证手机号码以后再次尝试！^o^</p>'
					 +'<div style="margin-top:20px;" class="f-tac">'
					 +    '<a href="' + shs.site('buyer')+'bind/mobile" target="_blank" class="u-btn u-btn-cr" style="padding: 0.4em 1.3em;">马上去认证</a>'
					 + '</div>',
			init : function(){
				var dialog = this;
				dialog.DOM.content.find("a").click(function(){
					dialog.title("认证结果").content('<p>请在新打开的界面完成认证 , 是否认证成功 ?</p>'
													+'<div style="margin-top:20px;" class="f-tac">'
													+    '<a href="javascript:;" class="u-btn u-btn-cr J_continue" style="padding: 0.4em 1.8em;margin-right: 20px;">认证成功</a>'
													+    '<a href="'+shs.site('help')+'buyer/category/95/100/249" target="_blank" class="u-btn">认证遇到问题</a>'
													+'</div>');
					dialog.DOM.content.find(".J_continue").click(function(){dialog.close();location.reload()});
				});
			}
		});
	};

	// 用于判断会员购买记录有没有加载
	fn.UserBuyLog = function(){
		return $.type(UserBuyLog.__tourists__)!="function";
	};

	/* ---------------------------- 方法集合 End   ---------------------------- */





	var $ele 		= {};		// 存储节点
	var timer 		= {			// 存储Interval时钟id，注：仅可保存由setInterval创建的时钟id，不支持setTimeout
		// count_down: null		// 倒计时时钟，其实暂时也只有倒计时时钟
	};
	var state_data	= {		// 存储一些状态信息
		can_buy : false,	// 是否可以购买
		buy_err : '',		// 存储购买错误信息
		buy_verify:null,	// 验证码的验证方法，注：
													// 该方法是个函数或者null，接收一个字符串或者null参数(表示用户输入的验证码)，
													// return字符串时表示验证失败，返回的字符串就是错误信息，return true时表示验证成功。
													// 该方法不需要在init中设置初始值，因为它是如同验证码一样是一次渲染的，并非经常改变的变量
		buy_posting : false, 	// 是否正在抢购中
		is_buy 		: false,  	// 标记是否已经购买（此方法用于可能已经抢购成功了但是用户购买记录还没加载出来的解决方案，不需要在init中重写）
		buy_oid		: null		// 众划算抢购单号
	};
	var btn_buy_disable = 'z-buy-dis';		// 存储抢购按钮未激活状态样式
	var user 		    = shs.user.info();	// 存储用户信息
	

	var UserBuyLog 	= 					// 存储用户购买记录（初始值为“游客”信息）
		{	
			"__tourists__"		: function(){},		// 仅浏览器端使用，用来表示游客，用于解决会员购买记录的加载问题，
													// 使用个函数的原因是因为json数据不会返回函数，以免可能跟服务器冲突，
													// 判断方法：fn.UserBuyLog()，返回true为已经加载会员购买记录，false为未加载（游客身份）
			"all_order_num"		: "0",	// 抢购总量
			"wait_fill_num"		: "0",	// 待填写订单号
			"check_failure_num"	: "0",  // 订单号有误
			"wait_rebate_num"	: "0",	// 待返现
			"appeal_num"		: "0",	// 申诉中
			"rebate_num"		: "0",	// 返现数量
			"wait_check_num"	: "0",	// 待审核订单号
			"order_num"			: 0,	// 用户对当前商品购买的次数
			"season_no"			: 0,	// 用户最后购买此商品的是第几期
			"need_mobile_valid"	: false,// 后台是否开启手机验证
			"mobile_valid"		: 2,	// 用户当前的手机验证状态 2是未验证,1是已验证
			"is_captcha"		: 0 	// 是否需要验证码
			// 商家
			// "goods_num"			: "12",	// 所有活动
			// "online_num"		: "5",	// 正在进行中
			// "block_num"			: "2",	// 已屏蔽活动
			// "offline_num"		: "3",	// 已下架活动
			// "balancing_sum"		: "3"	// 结算中活动
		};		

	var initOne = function(){
		var isRun = false;
		return function(){
			if(isRun)return;	//一次性初始化方法，本函数仅能执行一次
			isRun = true;

			if ($.type(goods)!="object") {
				// 商品信息不存在
				alert('无法读取此商品信息！');
				location.href = shs.site('www');
				return false;
			}

			//设定一条时钟改变服务器当前时间(因非请求式获取，故而碍于网络问题，可能会有几秒的误差)
			var clock = Time.clock();
			var goods_nowtime = Number(goods.nowtime);
			setInterval(function() {goods.nowtime=goods_nowtime+clock()}, 1000);


			/*----------------------节点获取-----------------------*/
			//商品详细信息模块
			$ele.mInfo = $("#J_mInfo");
			//抢购按钮
			$ele.btn_buy= $ele.mInfo.find(".J_btn_buy");
			//倒计时模块标题
			$ele.countdown_tt  = $ele.mInfo.find(".J_countdown dt");
			//倒计时模块内容
			$ele.countdown_ct = $ele.mInfo.find(".J_countdown dd");
			//商品数量信息模块
			$ele.number = $ele.mInfo.find(".J_number");
			//验证码模块
			$ele.verify = $ele.mInfo.find(".J_verify");

			/*----------------------定值数据初始化-----------------------*/
			// 浏览次数
			$("#J_PV").html(goods.hits);
			//担保金
			$ele.mInfo.find(".J_bond").html(goods.paid_guaranty);
			//活动价 && 网购价 && 返现金 && 搜索奖励金 && 折扣
			$ele.mInfo.find(".J_cost_price").html(goods.cost_price);
			$ele.mInfo.find(".J_price").html(goods.price);
			$ele.mInfo.find(".J_single_rebate").html(goods.single_rebate);
			$ele.mInfo.find(".J_search_reward").html(goods.search_reward);
			$ele.mInfo.find(".J_discount").html(goods.discount);

			/*----------------------页面效果-----------------------*/
			// 画廊模块
			!function($){
				var $gallery = $("#J_gallery");
				var $pic 	 = $gallery.find(".J_pic");
				var $thumb 	 = $gallery.find(".J_thumb li");
				
				// 将图片缓存起来
				var imgTemp  = {};
				$thumb.each(function(i){
					if(i==0){
						// 第一张图直接从pic里获取
						$(this).data("img", $pic.find("img"));
					}else{
						var $thumbImg   = $(this).find("img");
						$(this).data("img", $('<img/>').attr("src",$thumbImg.data("src")).attr("alt", $thumbImg.attr("alt")));
					}
				});
				$thumb.hover(
					function(){
						$thumb.filter(".z-sel").removeClass("z-sel");
						$(this).addClass("z-hover z-sel");
						$pic.html("").append($(this).data("img"));
					},
					function(){
						$(this).removeClass("z-hover");
					}
				);
				// 屏蔽A链接跳转
				$thumb.find("a").click(function(){return false});
			}($);

			// 公告选项卡模块
			$("#J_notice").tab({
				eType : "mouseover",
				card  : ".J_menu a",
				panel : ".J_panel ul",
				curClass:"z-crt"
			});

			// 推荐模块
			!function($){
				var $t = $("#J_tuijian");
				var $c = $t.find(".J_change");
				var $i = $t.find(".J_bd").children();
				// 初始化
				$i.hide().slice(0,3).show();
				var index=0,unlock=1,length=$i.length;
				$c.click(function(){
					if(unlock){
						unlock = 1;
						index = index+3<length ? index+3 : 0;
						$i.filter(":visible").fadeOut(function(){
							$i.slice(index,index+3).fadeIn(function(){
								unlock=1;
							});
						});
					}
				});
			}($);

			// 抢购记录模块、晒单记录模块分页实现
			!function($){

				// 【分页模块】模板渲染
				var Private_pageTpl = function(now, total){
					if(total==1)return '';
					var html = '<div class="m-page f-tac">';
					if(now>1){
						html += '<a href="javascript:;" class="prev" data-page="'+(now-1)+'"><span class="arrow">&nbsp;</span>上一页</a>'
					}
					var list = [],i;
					if(total<8 || now<5){
						for(i=1;i<=(total<8?total:7); i++) list.push(i);
					}else{
						for(i=now-3; i<=now+3; i++) list.push(i);
					}
					if(list.length>=7 && list[0]>2){
						list = [1,list[0]==3?2:'...'].concat(list);
					}
					if(list.length>=7 && list[list.length-1]<total-1){
						list = list.concat([list[list.length-1]==total-2?total-1:"...",total]);
					}
					for(i=0;i<list.length;i++){
						html += (list[i]=="...") ? '<i>...</i>'
												 :'<a href="javascript:;"'+(list[i]==now?' class="z-crt"':'')+' data-page="'+list[i]+'">'+list[i]+'</a>';
					}
					if(now!=total){
						html += '<a href="javascript:;" class="next" data-page="'+(now+1)+'">下一页<span class="arrow">&nbsp;</span></a>';
					}
					html +='<span>共 '+total+' 页</span></div>';
					return html;
					// 模板如下：
					//	<div class="m-page f-tac">
					//		<a href="javascript:;" class="prev" data-page="7"><span class="arrow">&nbsp;</span>上一页</a>
					//		<a href="javascript:;" data-page="1">1</a>
					//		<i>...</i>
					//		<a href="javascript:;" data-page="4">4</a>
					//		<a href="javascript:;"" data-page="5">5</a>
					//		<a href="javascript:;" data-page="6">6</a>
					//		<a href="javascript:;" data-page="7">7</a>
					//		<a href="javascript:;" class="z-crt data-page="8">8</a>
					//		<a href="javascript:;" data-page="9">9</a>
					//		<a href="javascript:;" data-page="10">10</a>
					//		<a href="javascript:;" data-page="11">11</a>
					//		<a href="javascript:;" data-page="12">12</a>
					//		<i>...</i>
					//		<a href="javascript:;" data-page="5">20</a>
					//		<a href="javascript:;" class="next" data-page="9">下一页<span class="arrow">&nbsp;</span></a>
					//		<span>共 20 页</span>
					//	</div>
				};

				// 【谁抢到了】模板渲染
				var Private_dealTpl = function(data){
					var html = '<div class="deal f-cb">';
					for(var i=0;i<data.length;i++){
						html+='<a href="http://bbs.shikee.com/space-uid-'+data[i].uid+'.html" target="_blank">'
							+	(!data[i].is_refund ? '' : '<i class="u-icon u-icon-s">&nbsp;</i>')
							+	'<img src="'+data[i].avatar+'"/>'
							+	'<span>'+data[i].uname+'</span>'
							+'</a>';
					}
					html += '</div>';
					return html;
					// 模板如下
					//	<div class="deal f-cb">
					//			<a href="http://bbs.shikee.com/space-uid-2090547555.html" target="_blank">
					//			<i class="u-icon u-icon-s">&nbsp;</i>
					//			<img src="http://uc.shikee.com/avatar.php?uid=2090547555&amp;size=small">
					//			<span>陈雅馨</span>
					//		</a>
					//		<a href="http://bbs.shikee.com/space-uid-2090690916.html" target="_blank">
					//			<img src="http://uc.shikee.com/avatar.php?uid=2090690916&amp;size=small">
					//			<span>打伞的金鱼</span>
					//		</a>
					//	</div>
				};

				// 【买家晒单】模板渲染
				var Private_reportTpl = function(data){
					var html = '<ul class="report-item f-cb">';
					for(var i=0;i<data.length;i++){
						html+='<li>'
							+	'<a href="http://bbs.shikee.com/yesvalue.php?mod=showshop&amp;uid='+data[i].uid+'&amp;showshopid='+data[i].id+'" target="_blank" class="report-pic">'
							+		'<img src="'+data[i].img_url+'">'
							+	'</a>'
							+	'<div class="report-bd">'
							+		'<div class="f-cb">'
							+			'<a href="http://bbs.shikee.com/space-uid-'+data[i].uid+'.html" class="report-userFace" target="_blank">'
							+				'<img src="'+data[i].avatar+'">'
							+			'</a>'
							+			'<a class="report-userName" href="http://bbs.shikee.com/space-uid-'+data[i].uid+'.html" target="_blank">'+data[i].uname+'</a>'
							+			'<p class="report-date">'+data[i].posttime+'</p>'
							+		'</div>'
							+		'<div class="report-ct">'+data[i].words+'</div>'
							+	'</div>'
							+'</li>';
					}
					html += '</ul>';
					return html;
					// 模板如下
					// <ul class="report-item f-cb">
					// 		<li>
					// 			<a href="http://bbs.shikee.com/yesvalue.php?mod=showshop&amp;uid=2090660352&amp;showshopid=671802" target="_blank" class="report-pic">
					// 				<img src="http://img.zhonghuasuan.com/mall/20140625/1_215_161_201406251026429179.jpg">
					// 			</a>
					// 			<div class="report-bd">
					// 				<div class="f-cb">
					// 					<a href="http://bbs.shikee.com/space-uid-2090660352.html" class="report-userFace" target="_blank">
					// 						<img src="http://uc.shikee.com/avatar.php?uid=2090660352&amp;size=small">
					// 					</a>
					// 					<a class="report-userName" href="http://bbs.shikee.com/space-uid-2090660352.html" target="_blank">hesong</a>
					// 					<p class="report-date">2014年06月25日 10时</p>
					// 				</div>
					// 				<div class="report-ct">窗帘很漂亮，做工精致</div>
					// 			</div>
					// 		</li>
					// </ul>
				};




				var Private_$dealtab = $("#J_dealtab");
				var Private_DOM = {
					deal : Private_$dealtab.find(".J_deal"),
					report:Private_$dealtab.find(".J_report"),
					dealMenu:Private_$dealtab.find(".J_menu a").eq(0),
					reportMenu:Private_$dealtab.find(".J_menu a").eq(1)
				};

				return {
					init : function(){
						// 抢购记录选项卡模块
						Private_$dealtab.tab({
							eType : "click",
							card  : ".J_menu a",
							panel : ".J_panel",
							curClass:"z-crt"
						});
						Private_DOM.dealMenu.html("谁抢到了("+goods.fill_order_num+")");
						if(goods.fill_order_num>0){
							this.deal_show(1);
						}else{
							Private_DOM.deal.html("<p>暂无数据。</p>");
						}

						var self = this;
						Private_DOM.reportMenu.html("买家晒单("+goods.show_num+")").one("click",function(){
							if(goods.show_num>0){
								self.report_show(1);
							}else{
								Private_DOM.report.html("<p>暂时无人晒单~</p>");
							}
						});
					},
					// 谁抢到了
					deal_show:function(page){
						var self = this;
						Private_DOM.deal.html("<p>正在加载...</p>");
						$.post('/buyer/' + goods.gid + '/' + page, function(json){
							if(json.total>0){
								var $html = $(Private_dealTpl(json.list));
								var $page = $(Private_pageTpl(json.page.now, json.page.total));
								$page.find("a[data-page]").click(function(){$(this).hasClass(".z-crt") || self.deal_show($(this).data("page"))});
								Private_DOM.deal.html($html.add($page));
							}else{
								Private_DOM.deal.html("<p>暂无数据。</p>");
							}
						},"json");
					},
					// 买家晒单
					report_show : function(page){
						var self = this;
						Private_DOM.report.html("<p>正在加载...</p>");
						$.post('/show/' + goods.gid + '/' + page, function(json){
							if(json.total>0){
								var $html = $(Private_reportTpl(json.rows));
								var $page = $(Private_pageTpl(json.page.now, json.page.total));
								$page.find("a[data-page]").click(function(){$(this).hasClass(".z-crt") || self.deal_show($(this).data("page"))});
								Private_DOM.report.html($html.add($page));
							}else{
								Private_DOM.report.html("<p>暂时无人晒单~</p>");
							}
						},"json");
					}
				};

			}($).init();

			// 分享模块
			document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000);

			/*----------------------个人记录-----------------------*/
			
			if (user) {
				// 拼接获取用户信息URL
				var _userDataUrl = shs.site('detail') + 'user-data-';
				if (user.type == 1) {
					_userDataUrl += 'buyer?gid=' + goods.gid + '&callback=?';
				}else {
					_userDataUrl += 'seller?callback=?';
				}
				
				$.getJSON(_userDataUrl, function(data) {
					if(!data.error){
						var html = "";
						if(user.type==1){		// 买家
							var domain = shs.site('buyer');
							$('#J_notice').replaceWith('<div class="m-log">'
													+	'<h4 class="tt">我的订单提醒</h4>'
													+	'<ul class="ct">'
													+		'<li><a target="_blank" href="'+domain+'order/?s=1">待填写订单号:<em>'+data.wait_fill_num+'</em></a></li>'
													+		'<li><a target="_blank" href="'+domain+'order/?s=5">订单号有误:<em>'+data.check_failure_num+'</em></a></li>'
													+		'<li><a target="_blank" href="'+domain+'order/?s=3">待审核订单号:<em>'+data.wait_check_num+'</em></a></li>'
													+		'<li><a target="_blank" href="'+domain+'order/?s=4">待返现:<em>'+data.wait_rebate_num+'</em></a></li>'
													+		'<li><a target="_blank" href="'+domain+'order/?s=6">申诉中:<em>'+data.appeal_num+'</em></a></li>'
													+	'</ul>'
													+	'<p class="f-tac">'
													+		'<a href="'+domain+'" target="_blank" class="u-btn">个人中心</a>'
													+	'</p>'
													+'</div>');
	
						}else if(user.type==2){	// 商家
							var domain = shs.site('seller');
							$('#J_notice').replaceWith('<div class="m-log">'
													+	'<h4 class="tt">我的活动管理</h4>'
													+	'<ul class="ct">'
													+		'<li><a target="_blank" href="'+domain+'goods/goods_list?state=-1">所有活动:<em>'+data.goods_num+'</em></a></li>'
													+		'<li><a target="_blank" href="'+domain+'goods/goods_list?state=20">正在进行中:<em>'+data.online_num+'</em></a></li>'
													+		'<li><a target="_blank" href="'+domain+'goods/goods_list?state=21">已屏蔽活动:<em>'+data.block_num+'</em></a></li>'
													+		'<li><a target="_blank" href="'+domain+'goods/goods_list?state=22">已下架活动:<em>'+data.offline_num+'</em></a></li>'
													+		'<li><a target="_blank" href="'+domain+'goods/goods_list?state=31">结算中活动:<em>'+data.balancing_sum+'</em></a></li>'
													+	'</ul>'
													+	'<p class="f-tac">'
													+		'<a href="'+domain+'" target="_blank" class="u-btn">个人中心</a>'
													+	'</p>'
													+'</div>');
						}
						UserBuyLog = data;	    // 存储
						if(state_data.is_buy){	// 购买记录没加载出来就已经完成了抢购
							UserBuyLog.season_no = goods.season_no;					// 标记这期已购买
						}
						PD.init();			// 重新初始化
					}
				});
			}

			/*----------------------事件绑定-----------------------*/
			//抢购按钮事件
			$ele.btn_buy.click(function(){PD.buy();return false;});
		}
	}();




	// 状态初始化(this会是PD)
	var ST = {};

	//已支付待审核
	ST.S_3  = function(){
		$ele.btn_buy.html("已支付待审核");
		$ele.countdown_tt.html("活动时间");
		$ele.countdown_ct.html(Time.converter("{%d天}{%h时}{%i分}{%s秒}",goods.first_days*1000));
	};
	//即将上线
	ST.S_5  = function(){
		$ele.btn_buy.html("即将上线");
		if(goods.expect_online_time-goods.nowtime>=3600){//距离上线时间大于一个小时的时候，显示的是开抢时间
			$ele.countdown_tt.html("开抢时间");
			$ele.countdown_ct.html(Time.strftime("%M月%D日 %H:%I", goods.expect_online_time*1000));
			timer.count_down = setInterval(function() {		// 隐藏的倒计时，当距离上线时间小于一个小时时候初始化页面
				if(goods.expect_online_time-goods.nowtime<3600){
					clearInterval(timer.count_down);
					PD.init();
				}
			});
		}else{
			$ele.countdown_tt.html("开抢倒计时");
			timer.count_down = setInterval(function() {
				if (goods.starttime-goods.nowtime <= 0) {	//倒计时结束，状态变化为正在进行时
					clearInterval(timer.count_down);
					goods.state = 20;	//改商品状态为正在进行时
					goods.season_no = Number(goods.season_no)+1;	//商品场次增加1
					goods.endtime = goods.nowtime+goods.first_days;	//设置结束时间
					PD.init();	//初始化
					return;
				}
				$ele.countdown_ct.html(Time.converter("{%d天}{%h时}{%i分}{%s秒}", (goods.starttime-goods.nowtime)*1000));
			}, 1000);
			$ele.countdown_ct.html(Time.converter("{%d天}{%h时}{%i分}{%s秒}", (goods.starttime-goods.nowtime)*1000));
		}
	};
	//正在进行中
	ST.S_20 = function(){
		$ele.btn_buy.removeClass(btn_buy_disable).html('我要抢购');
		state_data.can_buy = true;
		//正在进行时的倒计时
		$ele.countdown_tt.html("剩余时间");
		if(goods.endtime - goods.nowtime > 0){
			timer.count_down = setInterval(function() {
				if (goods.endtime - goods.nowtime <= 0) {
					clearInterval(timer.count_down);
					$ele.countdown_ct.html('-');
					window.location.reload();
					return;
				}
				$ele.countdown_ct.html(Time.converter("{%d天}{%h时}{%i分}{%s秒}", (goods.endtime - goods.nowtime)*1000));
			}, 1000);
			$ele.countdown_ct.html(Time.converter("{%d天}{%h时}{%i分}{%s秒}", (goods.endtime - goods.nowtime)*1000));
		}
		// 判断当前用户是否可以抢购
		if(user && user.type == 1){
			var isCan1 = Number(goods.season_no) > Number(UserBuyLog.season_no),    		// 这一期是否没有购买过
				isCan2 = Number(UserBuyLog.order_num) < Number(goods.buy_limit);    // 购买次数是否未达到上限
			var isCan  = isCan1 && isCan2; 
			if(!isCan){				// 不可以购买
				$ele.btn_buy.addClass(btn_buy_disable);
				state_data.can_buy = false;
			}
			if(!isCan1){
				$ele.btn_buy.html("抢购成功");
				state_data.buy_err = "众划算商品名额有限，同一抢购时间段，同一款商品一个会员只有一次抢购机会，再看看其它众划算商品吧！";
			}
			if(!isCan2){
				state_data.buy_err = "抱歉，该商品限制最多可以抢购<em class=\"s-fc-hh\">"+goods.buy_limit+"</em>次，再看看其它众划算商品吧！";
			}

			// 是否需要验证码
			if(isCan && UserBuyLog.is_captcha){
				if($ele.verify.data("is_draw")!==true){
					$ele.verify.data("is_draw",true);
					// 验证码结构有两个特殊的要求：
					// 1、html必须包含J_captcha类名指向验证码输入框
					// 2、必须设置state_data.buy_verify验证方法，注：该方法是个函数或者null，接收一个字符串或者null参数(表示用户输入的验证码)，return字符串时表示验证失败，返回的字符串就是错误信息，return true时表示验证成功
					var tpl = '<img data-src="'+shs.site('detail')+'home/get_code/'+goods.gid+'" width="120" height="30" alt="点击刷新验证码" title="点击更换验证码"/>'
							+ '<span>验证码：</span>'
							+ '<input class="u-ipt J_captcha" maxlength="12" type="text" />'
							+ '<span class="tip J_tip">&nbsp;</span>';
					$ele.verify.html(tpl).show();
					// 点击图片切换验证码
					$ele.verify.find("img").click(function(){$(this).attr("src", $(this).data("src")+'?'+Math.floor(Math.random()*10e8))}).click();
					// 验证码的验证方法
					state_data.buy_verify = function(val){
						var lth = $.type(val)=="string" ? val.length : 0;
						if(lth==0){
							return "验证码不能为空";
						}else if(lth<4 || lth>6){
							return "验证码错误";
						}else{
							return true;
						}
					};
					// 监控输入框实时验证
					$ele.verify.find('.J_captcha').on('keyup blur', function(){
						var ret = state_data.buy_verify(this.value);
						$ele.verify.find(".J_tip").html( ret!==true ? ret : "&nbsp;");
					});
				}
			}else{
				$ele.verify.hide();
			}
		}
	};
	//还有机会
	ST.S_24 = function(){
		$ele.btn_buy.html("还有机会");
		$ele.number.html('<span>限量份数：<strong>' + goods.quantity + '</strong> 份</span><span>未下单人数：<strong>' + goods.wait_fill_num + '</strong> 份</span>');
	};

	// 其它状态（只需要显示信息不做额外判断的）
	ST.S_Other = (function(){
		var I = {
			1 : "未付款待审核",
			2 : "待审核付款中",
			4 : "发布修改退款中",
			10: "取消退款中",
			11: "已取消",
			12: "审核未通过退款中",
			13: "审核未通过",
			21: "已屏蔽",
			22: "活动结束"
		};
		return function(s){$.type(I[s])=="string" && $ele.btn_buy.html(I[s]);};
	})();




	var PD = {};
	//初始化方法
	PD.init = function(){
		if(initOne()===false)return;

		/*-----------------------清除Interval时钟-----------------------*/
		(function(t,i){
			for(i in t){
				t.hasOwnProperty(i) && $.type(t[i])=="number" && clearInterval(t[i]);
			}
		})(timer);


		/*----------------------缺省数据初始化-----------------------*/
		// 倒计时
		$ele.countdown_ct.html("-");
		// 剩余量，字体颜色高亮样式： s-fc-hh
		$ele.number.html('<span>限量份数：<strong>'+goods.quantity+'</strong> 份</span><span>剩余份数：<strong class="s-fc-hh">'+goods.remain_quantity+'</strong> 份</span>');
		// 抢购按钮状态
		$ele.btn_buy.addClass(btn_buy_disable).html("活动结束").show();

		/*----------------------缺省额外数据初始化-----------------------*/
		// 不可购买
		state_data.can_buy = false;
		// 清空错误信息
		state_data.buy_err = '';


		/*----------------------不同状态数据初始化-----------------------*/
		$.type(ST["S_"+goods.state])=="function" ? ST["S_"+goods.state].call(this) : ST.S_Other.call(this, goods.state);

		// 如果是抢购中状态
		state_data.buy_posting && $ele.btn_buy.html('抢购中..');
		return this;
	};

	// 抢购
	PD.buy = function(){
		// 判断是否可以抢购（正在提交抢购的话也阻止当前流程，让其等待post）
		if(!state_data.can_buy || state_data.buy_posting || state_data.is_buy){
			// 有错误提示
			//state_data.buy_err && fn.error(state_data.buy_err);
			state_data.buy_err && fn.tips(state_data.buy_err, 2, null, null, "e");
			return;
		}

		//判断是否登录
		if(!user){
			shs.user.login();
			return;
		}

		// 判断用户类型
		if (user.type != 1) {
			fn.note('<p>很抱歉，众划算商品抢购暂时只限买家参与，建议您注册买家帐号进行抢购！</p>'
					+ '<div style="margin-top:20px;" class="f-tac">'
					+ 	'<a href="' + shs.site('login') + 'logout/" class="u-btn u-btn-cr" style="margin-right:20px;">买家登录</a>'
					+ 	'<a href="' + shs.site('reg')   + 'buyer/" class="u-btn u-btn-cr">买家注册</a>'
					+ '</div>');
			return;
		}

		var captcha = '';
		// 如果已经取到用户购买记录（可能会因为网络问题没有取到购买记录，那么前端部分将忽略针对用户记录的验证以提高抢购速度与用户体验）
		if(fn.UserBuyLog()){
			// 获取验证码
			if(UserBuyLog.is_captcha){
				captcha = $ele.verify.find(".J_captcha").val();		// 获取验证码
				if($.type(state_data.buy_verify)=="function"){		// 如果验证规则是个函数，那么将执行验证
					var ret = state_data.buy_verify(captcha);		// 验证验证码是否符合规则
					if(ret!==true){
						// 验证码不符合规则
						fn.tips(ret, 2, null, function(){$ele.verify.find(".J_captcha").focus()}, "e");
						return;
					}
				}
			}
			// 如果管理员开启了手机验证，并且用户没有绑定手机号
			if(UserBuyLog.need_mobile_valid && UserBuyLog.mobile_valid==2){
				fn.not_bind_mobile();	// 弹出未认证手机号的弹窗提示
				return;
			}
		}
		// 标记正在提交数据
		state_data.buy_posting = true;
		$ele.btn_buy.html('抢购中..');

		$.jsonp({
			url : shs.site('trade') + '?gid=' + goods.gid + (captcha ? '&specialValiCode='+captcha : '') + '&'+Global.token +'&callback=?',
			error : function(xOptions, textStatus) {
				// 释放抢购
				state_data.buy_posting = false;
				$ele.btn_buy.html('我要抢购');
				if(textStatus=='timeout'){
					fn.tips("系统连接超时，请稍后重试。", 2, null, null, "e");
				}else{
					fn.tips("系统繁忙，请稍后重试！", 2, null, null, "e");
				}
			},
			success : function(data) {
				// 是否抢购
				state_data.buy_posting = false;
				$ele.btn_buy.html('我要抢购');
				// 抢购失败
				if (!data.success) {
					switch(data.info.errcode){
						case "BUY_REPEAT":
							fn.tips("众划算商品名额有限，同一抢购时间段，同一款商品每个会员只有一次抢购机会，再看看其它众划算商品吧！", 2, null, function(){
								UserBuyLog.season_no = goods.season_no;	// 标记这期已购买
								PD.init();
							}, "e");
							break;
						case "CAPTCHA_ERROR":
							fn.tips("验证码错误", 2, null, function(){$ele.verify.find(".J_captcha").focus()}, "e");
							break;
						case "NOT_CAPTCHA":
							// 缺少验证码，可能的情况就是：
							// 		1、页面正常加载，但是用户在其它地方存在非法操作的嫌疑；
							// 		2、用户在存在非法操作的嫌疑，但是UserBuyLog因网络问题未加载出来
							//      3、未登录的用也可能会引发此信息
							fn.tips("请输入验证码", 2, null, function(){
								if(fn.UserBuyLog()){
									// 如果已经加载了用户购买记录，则标记需要验证码并init，实现免刷新页面继续操作
									UserBuyLog.is_captcha = 1;
									PD.init();
									$ele.verify.find(".J_captcha").focus();
								}else{
									// 如果用户购买记录因网络问题还没加载出来，或者其它原因，无奈只好刷新页面了
									location.reload();
								}
							}, "e");
							break;
						case "WITHOUT_MOBILE_VALID":
							// 没有认证手机号，弹出认证引导窗
							fn.not_bind_mobile();
							break;
						default:
							// 其它原因
							fn.tips(data.info.errtxt, 2, null, null, "e");
					}
					return;
				}
				// 抢购成功
				state_data.is_buy = true;
				state_data.buy_oid= data.new_oid;	// 存储会员抢购成功后的众划算订单号
				UserBuyLog.order_num = Number(UserBuyLog.order_num)+1;	// 会员抢购数量增加1
				UserBuyLog.season_no = goods.season_no;					// 标记这期已购买
				PD.init();
				// 弹出抢购成功提示信息
				art.dialog({
					lock : true,
					fixed : true,
					title : '抢购成功',
					content : '<div class="m-buySuc">'
							+	'<div class="f-cb">' 
							+		'<h5 class="tt">√ 抢购成功，请确认以下优惠哦！</h5>'
							+		'<a class="f-fr" href="'+shs.site('help')+'buyer/category/1/5/178" target="_blank" title="下单遇到问题">下单遇到问题？</a>'
							+	'</div>'
							+	'<div class="ct f-cb">' 
							+		'<p>请注意以下事项：</p>'
							+		'<p>1、此为“<em class="s-fc-hh">搜索下单</em>”活动，请按照活动页面提示流程进行搜索下单。<a target="_blank" href="'+goods_info.what_is_search_buy_help+'">什么是搜索下单</a></p>' 
							+		'<p>2、网购价：<em class="s-fc-hh">' + goods_info.price + '</em>元，请在下单页面核对网购价是否一致。</p>' 
							+		'<p>3、返现金额：<em class="s-fc-hh">'+ goods.single_rebate +'</em>，完成交易后，将返还给您的返现金额。</p>' 
							+		'<p>注意：报名抢购后<em class="s-fc-hh">' + goods.auto_clear_time_min + '分钟内</em>不下单付款和返回<em class="s-fc-hh">填写订单号</em>，本次报名记录将自动取消。</p>'
							+	'</div>'

						 	+'</div>',
					cancel : true,
					cancelVal : '继续抢购，稍后再去下单',
					okVal : '抢购成功，现在马上去搜索下单',
					ok : function() {
						PD.save_no();
					}
				});
			}
		});
	};

	PD.save_no = function(){
		if(!state_data.buy_oid)return;
		var oid = state_data.buy_oid;
		var diy_form = '<form><table>'
					 + 		'<colgroup><col style="width:60px"><col style="width:350px"></colgroup>'
					 + 		'<tbody style="color:#797979;">'
					 + 			'<tr>'
					 + 				'<td>商品名称：</td>'
					 + 				'<td style="color: #06f">'+ goods_info.title +'</td>'
					 + 			'</tr>'
					 + 			'<tr>'
					 + 				'<td>网购价：</td>'
					 + 				'<td style="font-weight:bold;color:#000;">￥'+ goods.price +'</td>'
					 + 			'</tr>'
					 + 			'<tr>'
					 + 				'<td>订单编号：</td>'
					 + 				'<td><input type="text" name="trade_no" class="u-ipt J_trade_no" /><span class="J_tradeTip" style="color: #cc0000;"></span></td>'
					 + 			'</tr>'
					 + 			'<tr class="J_verify" style="display:none">'
					 + 				'<td>验证码：</td>'
					 + 				'<td style="padding: 4px 0;">'
					 +					'<img class="J_codeImg" data-src="'+ shs.site('buyer') + 'order/get_code/' +oid +'" src="" alt="验证码" title="点击刷新验证码" style="cursor:pointer" />'
					 +					'<input type="text" class="u-ipt J_code" name="vcode" style="width:8em;" />'
					 +					'<span class="J_codeTip" style="color: #cc0000;">&nbsp;</span>'
					 +				'</td>'
					 + 			'</tr>'
					 + 			'<tr>'
					 + 				'<td style="vertical-align: top;">温馨提示：</td>'
					 +				'<td style="color:#999;">'
					 +					'<p>1、请填写已付款的订单编号，若填入未付款单号，属于违规行为且将无法获得返现；<a style="color:#09f;" href="'+shs.site('help')+'buyer/category/66/125/16" target="_blank">填写的订单号规则？</a></p>'
					 +					'<p>2、若单号被审核有误，请在 ' + Global.order_auto_close_time_hour + ' 小时内进行申诉或修改，逾期将无法领回返现金额（建议平时经常登录网站查看站内信提醒哦！）<a style="color:#09f;display:block;" href="'+shs.site('help')+'buyer/category/63/81/124" target="_blank">如何获取订单编号？</a></p>'
					 +				'</td>'
					 + 			'</tr>'
					 + 		'</tbody>'
					 + '</table><input type="submit" style="display:none"/></form>';
		art.dialog({
			fixed : true,
			title : '填写单号',
			content : diy_form,
			init: function () {
				var dialog = this;
				var form  = this.DOM.content.find('form');
				var verify= form.find(".J_verify");
				var input = form.find('.J_trade_no');
				var noMsg = form.find('.J_tradeTip');
				var vcode = form.find(".J_code");
				var vcMsg = form.find('.J_codeTip');
				var vcImg = form.find('.J_codeImg');
				// 点击刷新验证码
				vcImg.click(function(){this.src = vcImg.attr('data-src') +'?'+ Math.floor( Math.random()*10e8 )});
				input.focus();
				form.submit(function(){
					var val   = $.trim( input.val() );
					var vcode_val = vcode.val();
					var data = {trade_no : val};
					vcMsg.html('');
					noMsg.html('');
					// 订单号只能包含数字或字母
					if ( val == '') {
						noMsg.html(' 请输入订单号');
						return false;
					} else if ( /[^\-0-9a-zA-Z]/.test(val) ) {
						input.val('');
						noMsg.html(' 订单号有误');
						return false;
					} else {
						noMsg.html('');
					}
					// 验证码只能是数字且非空
					if( verify.is(":visible") ){
						if( !$.trim( vcode_val ) ){
							vcMsg.html(' 验证码错误');
							vcImg.click();
							vcode.focus();
							return false;
						}else {
							vcMsg.html('');
							data.is_captcha = 1;
							data.vcode = vcode_val;
						}
					}

					input.prop('disabled', true);
					vcode.prop('disabled', true);
					noMsg.css('color', '#666').html(' 正在发送...');
					dialog.DOM.buttons.find('button:first').html('操作中...').attr('disabled', 'disabled').removeClass('aui_state_highlight');

					$.jsonp({
						url: shs.site('buyer')+'order/save_no/'+goods.gid+'/'+oid, 
						data: data,
						callbackParameter: "callback",
						success: function (dt) { 
							if (dt.success) {
								art.dialog({
									fixed : true,
									icon  : 'succeed',
									time  : 5,
									title : '填写单号成功',
									content : '单号填写成功！',
									ok : true
								});
								dialog.close();
								
								// 百度订单分析统计
								window._hmt && window._hmt.push(["_trackOrder", {
									"orderId": oid,			// 订单id
									"orderTotal": goods.price,			// 订单总金额
									"item": [ // 该订单包含的商品条目数组，其中每件商品的信息都是一个json对象
										{
											"skuId": goods.gid,					// 商品id
											"skuName": goods_info.title,		// 商品名称
											"category": "未知",					// 商品所属的类别
											"Price": goods.price,				// 商品金额
											"Quantity": 1						// 商品数量
										}
									]
								}]);
							} else {
								noMsg.css('color', '#c00').html('');;
								// 有验证码情况下，操作出错，则刷新验证码
								verify.is(":visible") && vcImg.click() && vcode.val('').focus();
								switch(dt.data){
									case "NO_BIND_TAOBAO":
										noMsg.html('未认证手机号码，<a href="'+shs.site('buyer')+'bind/mobile" target="_blank" style="color:#0066FF">现在去认证</a>');
										break;
									case "CAPTCHA_IS_NULL":
									case "CAPTCHA_ERROR":
										vcMsg.html( dt.data=="CAPTCHA_ERROR" ? "验证码错误！" : "请输入验证码！");
										if(!verify.is(":visible")){
											verify.show();
											vcImg.click();
											vcode.focus();
										}
										break;
									case "TRADE_NO_IS_NULL":
									case "TRADE_NO_ERROR":
										noMsg.html(dt.data=="TRADE_NO_ERROR" ? '订单号格式有误！' : '请填写订单号！');
										input.val('').focus();
										break;
									default:
										noMsg.html(dt.data);
										input.val('');
								}
								input.prop("disabled", false);
								vcode.prop('disabled', false);
								dialog.DOM.buttons.find('button:first').html('确定').addClass('aui_state_highlight').prop("disabled", false);
								window.open(shs.site('buyer')+'order/');
							}
						},
						error : function(xOptions, textStatus) {
							if(textStatus=='timeout'){
								fn.tips("系统连接超时，请稍后重试。", 2, null, function(){
									window.open(shs.site('buyer')+'order/');
								}, "e");
							}else{
								fn.tips("系统繁忙，请稍后重试！", 2, null, function(){
									window.open(shs.site('buyer')+'order/');
								}, "e");
							}
						}
					});
					return false;
				});
			},
			ok:function(){
				this.DOM.content.find('form').submit();
				return false;
			}
		});
	};

	return PD.init();
})(window, $, art, shs, goods, goods_info, Global);