// 详细页
// <script> page_detail.init(); </script>
// 在detail.php文件中的 "goodsDetail"元素 和 "shs_flow"元素之间

var page_detail = (function() {
	var oIntro = null;
	var oBuyer = oBuyer_navs = oBuyer_conts = null;
	var oTime = btn_buy = null;
    var tyArr=['1','4','5'];

	var text_state = {
		't1' : '未付款待审核',
		't2' : '待审核付款中',
		't3' : '已支付待审核',
		't4' : '发布修改退款中',
		't5' : '即将上线',
		't10' : '取消退款中',
		't11' : '已取消',
		't12' : '审核未通过退款中',
		't13' : '审核未通过',
		't20' : '我要抢购',
		't21' : '已屏蔽'
	};

	function error(msg) {
		art.dialog({
			lock : true,
			fixed : true,
			icon : 'error',
			title : '错误提示',
			content : msg,
			ok : true
		});
	}

	function format_date(ts) {
		function _pad(n, c) {
			n = n.toString();
			return n.length < c ? _pad('0' + n, c, '0') : n;
		}

		var dt = new Date(ts);

		return _pad(dt.getMonth() + 1, 2) + '月' + _pad(dt.getDate(), 2) + '日 ' + _pad(dt.getHours(), 2) + ':' + _pad(dt.getMinutes(), 2);
	}

	function count_down(sec) {
		var s = sec;
		var left_s = s % 60;
		var m = Math.floor(s / 60);
		var left_m = m % 60;
		var h = Math.floor(m / 60);
		var left_h = h % 24;
		var d = Math.floor(h / 24);

		var ret = [];
		d && ret.push('<em class="d">', d, '</em>天');
		left_h && ret.push('<em class="h">', time_pad(left_h), '</em>时');
		left_m && ret.push('<em class="m">', time_pad(left_m), '</em>分');
		left_s && ret.push('<em class="s">', time_pad(left_s), '</em>秒');

		return ret.join('');
	}

	function show_remain_time(sec) {
		// 不显示了
		oIntro.find(".goodsDetail-timeRemaining").hide();
		return;
		var timer = null;
		timer = setInterval(function() {
			sec -= 1;
			if (sec <= 0) {
				oTime.html('-');
				clearInterval(timer);
				location.reload();
				return;
			}
			oTime.html(count_down(sec));
		}, 1000);
		oTime.html(count_down(sec));
	}

	function show_online_time(start_sec,obj) {
		var timer = null;
		timer = setInterval(function() {
			var remain_sec = start_sec - goods.nowtime;
			if (remain_sec <= 0) {
				obj.html('0');
				clearInterval(timer);
				//location.reload();
				oIntro.find('.timeRemaining-tit').html("剩余时间 :");
				oIntro.find('.btn-buy').removeClass('btn-buy-disable').html('我要抢购');
				show_remain_time(goods.first_days);
				return;
			}
			obj.html(count_down(remain_sec));
		}, 1000);
	}

	function time_pad(s){return Number(s)>9?String(s):"0"+String(s);}

	//没有绑定手机时候提示的错误信息
	function not_bind_mobile(){
		art.dialog({
			lock  : true,
			fixed : true,
			title : '温馨提示',
			width : 350,
			content : '<p style="color:#FF3300;font-weight: bold;font-size:16px;">抢购失败</p><p>您还未认证手机号码，暂时无法抢购。请认证手机号码以后再次尝试！^o^</p>' + '<div style="margin-top:20px; text-align:center;">' + '<a href="' + shs.site('buyer')+'bind/mobile" target="_blank" style="display:inline-block; padding: 7px .9em; background-color:#c00; color:#fff; border-radius: 3px;">马上去认证</a></div>',
			init : function(){
				var dialog = this;
				dialog.DOM.content.find("a").click(function(){
					dialog.title("认证结果").content('<p>请在新打开的界面完成认证 , 是否认证成功 ?</p>' + '<div style="margin-top:20px; text-align:center;">' + '<a data-type="continue" href="javascript:;" style="display:inline-block; padding: 7px 2em; background-color:#c00; color:#fff; border-radius: 3px;">认证成功</a>　<a href="'+ shs.site('help') +'buyer/category/95/100/249" target="_blank" class="btn" style="padding: 6px .9em;">认证遇到问题</a></div>');
					dialog.DOM.content.find("[data-type=continue]").click(function(){dialog.close();location.reload()});
				});
			}
		});
	}

	// 这个函数主要作用是 “收集” 需要使用 userbuylog数据的 操作,当传入的参数是数据时则调用搜集到的所有操作；
	var getUserBuyLog = (function(){
		var queue = [],
			data  = null;
		return function(arg){
			if (typeof arg === 'function') {
				if (data && Object.prototype.toString.call(data) === '[object Object]') {
					arg(data);
				}
				else{
					queue[queue.length] = arg;
				}
			}
			else if (arg && Object.prototype.toString.call(arg) === '[object Object]') {
				data = arg;
	 			for (var i=0, l=queue.length; i<l; i++) {
	 				queue[i](arg);
	 			}
	 			queue = [];
			}
		}
	})();

	// 用于判断验证码
	var global_is_captcha = 0;

	return {
		init : function() {
			if (typeof(goods)!='object') {
				// 商品信息不存在
				alert('无法读取此商品信息！');
				location.href = shs.site('www');
				return;
			}
			var self = this;
			self.is_open();

			// 这个函数主要作用是 “收集” 需要使用 userbuylog数据的 操作,当传入的参数是数据时则调用搜集到的所有操作；
			// var getUserBuyLog = (function(){

			// 	var _set = [];

			// 	return function (arg) {

			// 		// 搜集
			// 		if (typeof arg === 'function') {
			// 			_set[_set.length] = arg;
			// 		}

			// 		// 调用所有操作
			// 		if (arg && Object.prototype.toString.call(arg) === '[object Object]') {
			// 			for (var i=0, l=_set.length; i<l; i++) {
			// 				_set[i](arg);
			// 			}
			// 		}

			// 	}

			// }());

			setInterval(function() {
				goods.nowtime += 1;
			}, 1000);

			// 初始化图片列表事件
			$('.goodsDetail-gallery ul li').mouseover(function() {
				var me = $(this);
				me.addClass('goodsDetail-gallery-cur').siblings().removeClass('goodsDetail-gallery-cur');
				var pic = me.attr('goods-img');
				$('.goodsDetail-showPic img')[0].src = pic;
			}).eq(0).mouseover();

			// 显示商品信息
			oIntro = $('.goodsDetail-info');

			// 是否还有机会
			var b_chance = goods.state == 24;

			if (b_chance) {
				oIntro.find('.goodsDetail-fenshu').html('<span>未下单人数：<strong>' + goods.wait_fill_num + '</strong> 份</span><span>限量份数：<strong>' + goods.quantity + '</strong> 份</span>');
			} else {
				oIntro.find('.goodsDetail-fenshu').html('<span>剩余份数：<strong class="font-highlight">' + goods.remain_quantity + '</strong> 份</span><span class="goodsDetail-fenshu-remain">限量份数：<strong>' + goods.quantity + '</strong> 份</span>');
			}

			// 浏览次数
			$('.goodsDetail-browse em').html(goods.hits);

			//担保金
			oIntro.find('.goodsDetail-safe em').html(goods.paid_guaranty);

			//活动价 && 网购价 && 返现金 && 折扣
			$('.goodsDetail-HXJ-num').html(goods.cost_price);
			var goodsDetail_XDJ = $('.goodsDetail-XDJ');
			goodsDetail_XDJ.find('li').eq(0).find('span').html(goods.source_name); // 商品来源
			goodsDetail_XDJ.find('li').eq(0).find('em').html("￥"+goods.price);
			goodsDetail_XDJ.find('li').eq(1).find('em').html("￥"+goods.single_rebate);
			goodsDetail_XDJ.find('li').eq(2).find('em').html(goods.discount);

			// 提醒统计
			oIntro.find('.remind-count').html('(<span class="font-highlight">' + goods.subscribe_count + '</span>人想要抢购)')[parseInt(goods.subscribe_count, 10) > 0? 'show' : 'hide']();
			var remain_time = goods.endtime - goods.nowtime;
			oTime = oIntro.find('.timeRemaining-cont');
			if (goods.state == 3) {
				// 干掉
				oIntro.find(".goodsDetail-timeRemaining").hide();
				// oIntro.find('.timeRemaining-tit').html("活动时间 :");
				// oTime.html(count_down(goods.first_days));
			} else if (goods.state == 5) {
				if(goods.expect_online_time-goods.nowtime>=3600){
					oIntro.find('.timeRemaining-tit').html("开抢时间 :");
					oTime.html(format_date(goods.expect_online_time*1000));
				} else{
					oIntro.find('.timeRemaining-tit').html("开抢倒计时 :");
					show_online_time(goods.starttime,oTime);
				}
			} else if (remain_time > 0 && goods.state == 20) {
				show_remain_time(remain_time);
			} else {
				// 干掉
				oIntro.find(".goodsDetail-timeRemaining").hide();
				//oTime.html('-');
			}

			// 购买按钮
			btn_buy = oIntro.find('.btn-buy');
			var btn_text = '';
			if (b_chance) {
				btn_text = '还有机会';
			} else if (text_state['t' + goods.state]) {
				btn_text = text_state['t' + goods.state];
			} else {
				btn_text = '活动结束';
			}

			// 特殊商品购买验证
			(function(){

				if (goods.state == 20) {    // 正在进行

					if ( goods.detail_captcha != 1 ){    // 非特殊商品
						getUserBuyLog(function(data){
							if(data.is_captcha!=1){
								$('.buy-verify').remove();
								return;
							}
							$('.buy-verify-img').click(function(){    // 点击刷新验证码，并显示
								this.src = this.getAttribute('data-src') +'?'+ Math.floor( Math.random()*10e8 );
								$('.buy-verify').show();
							});
//							$('.buy-verify-img').click(function(){    // 点击刷新验证码
//								this.src = this.getAttribute('data-src') +'?'+ Math.floor( Math.random()*10e8 );
//							}).click();
//							$('.buy-verify').show();
							global_is_captcha = data.is_captcha;
						});
						return;
					}
					$('.buy-verify').eq(1).remove();	//删除第二个验证码(防黄牛次数限制验证码)

					var setId;
					$('.buy-verify-val').on('keyup', function(){

						setId && clearTimeout(setId);
						$('.buy-verify-msg').text('').hide();

						var val = this.value;
						setId = setTimeout(function(){

							if(val === '') {
								$('.buy-verify-msg').text('验证码不能为空').show();

							} else if ( val.length < 4 || /[^0-9]/.test(val) ) {

								$('.buy-verify-msg').text('验证码错误').show();

							} else if ( val.length===4 && !(/[^0-9]/.test(val)) ) {
								$('.buy-verify-msg').text('').hide();
							}

						}, 800);

					});

					$('.buy-verify-img').click(function(){    // 点击刷新验证码
						this.src = this.getAttribute('data-src') +'?'+ Math.floor( Math.random()*10e8 );
					});

				} else {
					$('.buy-verify').remove();
				}
			}());

			if (goods.state == 20) {

				shs.user.info() && shs.user.info().type == 1 && getUserBuyLog(function(data){

					if ($.inArray(goods.type,tyArr) != -1) {    // 一站成名商品
						(data.order_num >= goods.buy_limit) && btn_buy.addClass('btn-buy-disable').data('msg', '尊敬的会员，您好，一站成名活动每个会员只限抢购'+goods.buy_limit+'份，当前活动您已抢购'+data.order_num+'份，再看看其它一站成名的商品吧！');
						(goods.season_no == data.season_no) && btn_buy.addClass('btn-buy-disable').data('msg', '众划算商品名额有限，同一抢购时间段，同一款商品一个会员只有一次抢购机会，再看看其它众划算商品吧！').text('抢购成功');

						if( data.order_num >= goods.buy_limit || goods.season_no == data.season_no ){
							global_is_captcha == 1 && $('.buy-verify').remove();
						}else{
							$('.buy-verify-img').click();
						}
					} else {    // 普通商品

						var isCan1 = Number(goods.season_no) > Number(data.season_no),    // 这一期是否已经购买过
							isCan2 = Number(data.order_num) < 3;    // 购买次数是否已达到上限
						var isCan = isCan1 && isCan2;

						isCan || btn_buy.addClass('btn-buy-disable');
						isCan1 || btn_buy.text('抢购成功').data('msg', '众划算商品名额有限，同一抢购时间段，同一款商品一个会员只有一次抢购机会，再看看其它众划算商品吧！');
						isCan2 || btn_buy.data('msg', '抱歉，同一款商品最多可以抢购三次，再看看其它众划算商品吧！');

						if( !isCan1 || !isCan2 ){
							global_is_captcha == 1 && $('.buy-verify').remove();
						}else{
							$('.buy-verify-img').click();
						}
					}
				});

			} else {
				btn_buy.addClass('btn-buy-disable');
			}

			btn_buy.click(function() {
				page_detail.buy($(this));
				return false;
			}).html(btn_text);

			// “提醒”按钮
			var btn_remind = oIntro.find('.remind-btn');
			if (goods.state==="5") {    // 即将上线
				btn_remind.text('开抢提醒');
				$('.goodsDetail-remind').show();
			} else if ( goods.state == 20 ) {    // 正在进行

				btn_remind.text('追加提醒');
				if (shs.user.info()) {

					getUserBuyLog(function(data){
						var order_num = data.order_num||0,
							season_no = data.season_no||0;
						//购买过一次一战成名商品后，不再可以购买，不显示追加提醒
						if($.inArray(goods.type,tyArr) != -1 && order_num>0){
							$('.goodsDetail-remind').hide();
						}
						// 这一期是否已经购买过 && 购买次数是否已达到上限
						else if( (goods.season_no > season_no) && (order_num < 3) ) {
							$('.goodsDetail-remind').hide();    // 当前可以购买
						} else if (order_num < 3) {
							$('.goodsDetail-remind').show();    // 当前不可以购买，下次倒是可以
						} else {
							$('.goodsDetail-remind').hide();
						}
					});

				} else {
					$('.goodsDetail-remind').hide();
				}

			} else if( goods.state == 22 ){    // 已下架

				btn_remind.text('追加提醒');
				if (shs.user.info()) {

					getUserBuyLog(function(data){
						var order_num = data.order_num||0;
						if($.inArray(goods.type,tyArr) != -1){
							//购买过一次一战成名商品后，不再可以购买，不显示追加提醒
							 $('.goodsDetail-remind')[order_num < 1? 'show' : 'hide']();
						}else{
							// 下次可以购买么
							$('.goodsDetail-remind')[order_num < 3? 'show' : 'hide']();
						}
					});

				} else {
					$('.goodsDetail-remind').show();
				}

			} else {
				btn_remind.text('不可提醒');
				$('.goodsDetail-remind').hide();
			}
			btn_remind.click(function(){
				page_detail.remind();
				return false;
			});

			oIntro.show();

			$(function() {
                //核对商品链接
				$('#J_view-goods').on('click', function () {
                    var user = shs.user.info();
                    if ( btn_buy.hasClass('btn-buy-disable') && user.type == 1) {
                        page_detail.check_goods_link();
                    }
				});
				// 公告面板选项卡功能
				$(".notice").tab({
					eType : "mouseover",
					card : ".notice-hd a",
					panel : ".notice-panel",
					curClass:"notice-curItem"
				});

				// 个人记录
				var user = shs.user.info();
				if (user && user.type == 1) {    // 买家

					$('.notice').replaceWith('<div class="log"><h3 class="log-tit">我的订单提醒</h3><div class="loading-data">loading...</div><p class="btn-center"><a class="btn" href="' + shs.site('buyer') + '" target="_blank">个人中心</a></p></div>');

					getUserBuyLog(function(data) {
						var html = [];
						//html.push('我已购买', data.order_num, '件众划算商品，其中：');
						html.push('<ul><li><a target="_blank" href="',shs.site('buyer'),'order/?s=1">待填写订单号:<em>', data.wait_fill_num, '</em></a></li>');
						html.push('<li><a target="_blank" href="',shs.site('buyer'),'order/?s=5">订单号有误:<em>', data.check_failure_num, '</em></a></li>');
						html.push('<li><a target="_blank" href="',shs.site('buyer'),'order/?s=3">待审核订单号:<em>', data.wait_check_num, '</em></a></li>');
						html.push('<li><a target="_blank" href="',shs.site('buyer'),'order/?s=4">待返现:<em>', data.wait_rebate_num, '</em></a></li>');
						html.push('<li><a target="_blank" href="',shs.site('buyer'),'order/?s=6">申诉中:<em>', data.appeal_num, '</em></a></li></ul>');
						//html.push(data.rebate_num, '件已返现');
						$('<div class="log-cont"></div>').html(html.join("")).replaceAll(".log .loading-data");

                        //抢购成功之后显示核对商品链接按钮
                        if(btn_buy.hasClass('btn-buy-disable') && data.order_num>0){
                            $('#J_view-goods').show();
                            //核对成功之后隐藏核对商品链接按钮
                            if(data.check_url_state == 0 || data.check_url_state == 2){
                                $('#J_view-goods').hide();
                            }
                        };
                        $('.btn-buy').click(function(){
                            if(!btn_buy.hasClass('btn-buy-disable')) {
                                setTimeout(function () {
                                    if ($('.btn-buy').text() == '抢购成功' || $('.btn-buy').text() == '抢购中..' || $('.btn-buy').text() == '还有机会') {
                                        $('#J_view-goods').show();
                                    }
                                }, 3000)
                            }
                        })
					});

				} else if (user && user.type == 2) {    // 商家

					$('.notice').replaceWith('<div class="log"><h3 class="log-tit">我的活动管理</h3><div class="loading-data">loading...</div><p class="btn-center"><a class="btn" href="' + shs.site('seller') + '" target="_blank">个人中心</a></p></div>');

					getUserBuyLog(function(data) {
						var html = [];
						html.push('<ul><li><a target="_blank" href="',shs.site('seller'),'goods/goods_list?state=-1">所有活动:<em>', data.goods_num, '</em></a></li>');
						html.push('<li><a target="_blank" href="',shs.site('seller'),'goods/goods_list?state=20">正在进行中:<em>', data.online_num, '</em></a></li>');
						html.push('<li><a target="_blank" href="',shs.site('seller'),'goods/goods_list?state=21">已屏蔽活动:<em>', data.block_num, '</em></a></li>');
						html.push('<li><a target="_blank" href="',shs.site('seller'),'goods/goods_list?state=22">已下架活动:<em>', data.offline_num, '</em></a></li>');
						html.push('<li><a target="_blank" href="',shs.site('seller'),'goods/goods_list?state=31">结算中活动:<em>', data.balancing_sum, '</em></a></li></ul>');

						$('<div class="log-cont"></div>').html(html.join("")).replaceAll(".log .loading-data");
					});

                    //隐藏商品链接核对
                    $('#J_view-goods').hide();

				}

				// 获取usebuylog数据
				(function(){

					if (!user) { return };    // 未登录

					var str = '';
					if (user.type == 1) {
						str = 'order';
					} else if(user.type == 2) {
						str = 'goods';
					} else {
						return;
					}

					// 拼接获取用户信息URL
					var _userDataUrl = shs.site('detail') + 'user-data-';
					if (user.type == 1) {
						_userDataUrl += 'buyer?gid=' + goods.gid + '&callback=?';
					}else {
						_userDataUrl += 'seller?callback=?';
					}

					$.getJSON(_userDataUrl, function(data) {
						getUserBuyLog(data);
					});

				}());

				// 初始化“买家列表”、“晒单列表”
				oBuyer = $('.goods-buyer');
				oBuyer_navs = oBuyer.find('.ui-tab-item a');
				oBuyer_conts = oBuyer.find('.ui-tab-panel');
				oBuyer_navs.eq(0).html('谁抢到了(' + goods.fill_order_num + ')');
				oBuyer_conts.eq(0).data('num', goods.join_num);
				oBuyer_navs.eq(1).html('买家晒单(' + goods.show_num + ')');
				oBuyer_conts.eq(1).data('num', goods.show_num);
				oBuyer_navs.click(function() {
					var el = $(this).parent(), pos = el.index();
					oBuyer_navs.parent().removeClass('ui-tab-itemCurrent');
					el.addClass('ui-tab-itemCurrent');
					oBuyer_conts.hide().eq(pos).show();
					pos ? page_detail.loadShow() : page_detail.loadBuyer();
				}).eq(0).click();

			   // 初始化"关联商品"
                page_detail.show_relative_goods();

			});

		},
		view : function() {
			if (goods_info.qrcode_state == 1) {
				page_detail.gotoCode();
			}else{
				window.open(goods_info.url);
			}
		},
		//页面定位到活动详情模块
		gotoCode : function(){
			$("html,body").animate({
		        scrollTop: $(".goods-main").offset().top
		    }, 1000);
		},
		//二维码下单前提示
		codeMsg : function(){
			var html = '<p style="font-size:14px;color:#f44444;">该活动为二维码下单活动，请扫描以下二维码进入商家店铺下单购买(请勿用微信扫码)</p><p style="text-align:center;margin-top:20px;"><img width="200" height="200" src="'+ goods_info.qrcode_img +'"></p>';
			art.dialog({
				lock : true,
				fixed : true,
				title : '下单前提示',
				content : html,
				ok : true
			})
		},
		// 邀请好友提示
		invite : function(){
			var link_data = $("#invite").data("link");
			if (!shs.user.info()) {
				shs.user.login();
				return;
			}else if(link_data){
				$.getJSON(shs.site('buyer')+'invite/create_link?callback=?&url='+window.location.href,function(e){
					if(e.success){
						$("#s_url").val(e.data.new_url);
					}
				});
				$(".share-box").removeClass("active");
			}else{
				$(".share-box").removeClass("active");
			}
		},
		is_open:function(){
			var title = '坑爹啊，原来淘宝买东西，可以上【众划算】拿返利省钱的，怎么没早点发现！';
			var pic = shs.site('static')+'user/buyer/images/share1.png';
			var link_data = $("#invite").data("link");
			$(".share-btn").on('click',function(){
				$(".share-box").addClass("active");
				if(!link_data){// 判断是否开通
					$('.share-btn').attr("target","_self");
					invite_open();
					return;
				} else {
					var url = $("#s_url").val();
					invite_share(title,url,pic);
				}
			})
			// 分享链接
			function invite_share(title,url,pic){
				var weibo = 'http://service.weibo.com/share/share.php'+'?url='+encodeURIComponent(url)+'&title='+encodeURIComponent(title)+'&pic'+encodeURIComponent(pic);
				var qzone = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey'+'?url='+encodeURIComponent(url)+'&title= &desc='+encodeURIComponent(title)+'&pics='+encodeURIComponent(pic)+'&summary=众划算版权所有';
				var pyou = 'http://connect.qq.com/widget/shareqq/index.html?url='+encodeURIComponent(url)+'&title=快来邀请好友，赚奖励吧！&desc='+encodeURIComponent(title)+'&pics='+encodeURIComponent(pic)+'&summary=众划算版权所有';
				$('.share-box').find('.weibo').attr('href',weibo);
				$('.share-box').find('.qzone').attr('href',qzone);
				$('.share-box').find('.pyou').attr('href',pyou);
			}
			// 未开通邀请弹窗
			function invite_open() {
				var open_url = $("#open_url").val()+'?url='+window.location.href;
				var cont = [];
				cont.push('<div class="not-open">');
				cont.push('<p><span class="ico"></span>您还未开通会员邀请功能，还不能参与会员邀请活动。</p>');
				cont.push('<a href="http://help.zhonghuasuan.com/buyer/category/153/154/262" target="_blank" class="how-to-open">如何开通？</a>');
				cont.push('</div>');
				art.dialog({
					lock : true,
					fixed : true,
					title : '操作提示',
					ok : function(){
						window.open(open_url);
						window.close();
					},
					okVal : "立即开通",
					content : cont.join('')
				});
			}
		},
		buy : function(btn) {
			// 判断是否可购买
			if ( btn.hasClass('btn-buy-disable') ) {
				goods.state == 20 && error(btn.data('msg'));
				return;
			}

			// 判断登录
			var user = shs.user.info();
			if (!user) {
				shs.user.login();
				return;
			}

			// 判断用户类型
			if (user.type != 1) {
				error('<p>很抱歉，众划算商品抢购暂时只限买家参与，建议您注册买家帐号进行抢购！</p>' + '<div style="margin-top:20px; text-align:center;">' + '<a href="' + shs.site('login') + '" style="display:inline-block; padding: .6em .9em; background-color:#c00; color:#fff; border-radius: 3px; margin-right:20px;">买家登录</a>' + '<a href="' + shs.site('reg') + 'buyer/" style="display:inline-block; padding: .6em .9em; background-color:#c00; color:#fff; border-radius: 3px;">买家注册</a></div>');
				return;
			}

			// 特殊商品购买验证
			var specialValidation = (function(){

				var bl = false;
				if (goods.state == 20) {    // 正在进行

					if ( goods.detail_captcha != 1 ){     // 非特殊商品
						return (bl=true);
					};

					var input = $('.buy-verify-val');
					var val = input.val();

					if(val === '') {
						$('.buy-verify-msg').text('验证码不能为空').show();
						bl = false;
					} else if ( val.length < 4 || /[^0-9]/.test(val) ) {
						$('.buy-verify-msg').text('验证码错误').show();
						bl = false;
					} else if ( val.length===4 && !(/[^0-9]/.test(val)) ) {
						$('.buy-verify-msg').text('').hide();
						bl = true;
					}


					bl || input.focus().triggerHandler('keyup');

					return bl;

				} else {
					$('.buy-verify').remove();
					return (bl=true);
				}
			}());
			if (!specialValidation) { return;}

			// 抢购验证码 非空|格式 验证 global_is_captcha
			var buy_vcode = $('.buy-verify-val');
			var buy_vcode_val = buy_vcode.val();
			var buy_vcMsg = $('.buy-verify-msg').html('');
			var buy_vcImg = $('.buy-verify-img');

			// 判断是否显示抢购验证码
			if( global_is_captcha == 1 ){

				if( !$.trim( buy_vcode_val ) ){
					buy_vcMsg.css('color', '#c00').html(' 验证码错误');
					buy_vcImg.click();
					buy_vcode.val('').focus();
					return false;
				}
			}

			if (btn.data('posting'))
				return;
			btn.data('posting', true).html('抢购中..');
			getUserBuyLog(function(userbuylog){

				// 如果管理员开启了手机验证，并且用户没有绑定手机号
				if(userbuylog.need_mobile_valid && userbuylog.mobile_valid==2){
					btn.data('posting', false).html('我要抢购');
					not_bind_mobile();
					return;
				}

				// 特殊商品购买验证码
				var	specialValiCode = $('.buy-verify').length < 1? '' : ('&specialValiCode=' + buy_vcode_val);
				global_is_captcha == 1 && buy_vcMsg.css('color', '#666').html(' 正在发送...');
				$.jsonp({
					url : shs.site('trade') + '?gid=' + goods.gid + specialValiCode + '&sign='+buy_sign+'&t='+buy_sign_time+'&callback=?',
					success : function(data) {
						global_is_captcha == 1 && buy_vcMsg.css('color', '#c00').html('');
						btn.data('posting', false).html('我要抢购');

						if (!data.success) {
							if (data.info.errcode == 'BUY_REPEAT') {
								error('众划算商品名额有限，同一抢购时间段，同一款商品每个会员只有一次抢购机会，再看看其它众划算商品吧！');
							} else if(data.info.errcode == 'CAPTCHA_ERROR') {
								buy_vcMsg.css('color', '#c00').html(' 验证码错误');
								buy_vcode.val('').focus();
								buy_vcImg.triggerHandler('click');
							} else if(data.info.errcode == 'NOT_CAPTCHA') {
								location.reload();
							} else if(data.info.errcode == 'WITHOUT_MOBILE_VALID'){
								not_bind_mobile();
							} else {
								error(data.info.errtxt);
							}
							return;
						}
						var default_tip = '众划算商品名额有限，同一抢购时间段，同一款商品每个会员只有一次抢购机会，再看看其它众划算商品吧！',
						yzcm_tip = '尊敬的会员，您好，一站成名活动每个会员每期只限抢购一份，当前活动您已抢购，再看看其它一站成名的商品吧！';
						btn.addClass('btn-buy-disable').text('抢购成功');

						btn.data('msg', ($.inArray(goods.type,tyArr) != -1 ? yzcm_tip : default_tip));

						var oid = data.new_oid;    // 提交 订单号 时需要同时提交这个oid
						// 抢购成功后的计时器，用于判断是否显示填单验证码
						var cur_time = (function(){
							var node = parseInt(new Date().getTime()/1000);
							return function(){
								return parseInt(new Date().getTime()/1000)-node;
							}
						})();

                        if(goods.type ==5){ //搜索下单
                            var _content = '<div id="windown-content" class="buySuccess">'
                                +	'<h3 class="buySuccess-tit">√ 抢购成功，请确认以下优惠哦！</h3>'
                                +	'<a class="problem" href="http://help.zhonghuasuan.com/buyer/category/1/5/178" target="_blank" title="下单遇到问题">下单遇到问题？</a>'
                                +	'<div class="buySuccess-cont">'
                                +		'<p>请注意以下事项：</p>'
                                +		'<p>1、此为“<em>搜索下单</em>”活动，请按活动页面提示按照流程进行下单   <a style="color:#06f" href="http://help.zhonghuasuan.com/buyer/category/149/150/231" target="_blank" >什么是搜索下单？</a></p>'
                                +		'<p>2、'+ goods_info.source_name +'：<em>' + goods_info.price + '</em>元，请在下单页面核对价格是否一致。</p>'
                                +		'<p>3、返　现：<em>'+ goods.single_rebate +'</em>元，完成交易后，将返还给您的金额。</p>'
                                +		'<p>注意：报名抢购后<em>' + goods.auto_clear_time_min + '分钟内</em>不下单付款和返回<em>填写订单号</em>，本次报名记录将自动取消。</p>'
                                +	'</div>'
                                +'</div>';
                            var okval='抢购成功，现在去下单',
                                cancel; //取消‘继续抢购，稍后再去下单’按钮
                        }else{
                            var _content = '<div id="windown-content" class="buySuccess">'
                                +	'<h3 class="buySuccess-tit">√ 抢购成功，请确认以下优惠哦！</h3>'
                                +	'<a class="problem" href="http://help.zhonghuasuan.com/buyer/category/1/5/178" target="_blank" title="下单遇到问题">下单遇到问题？</a>'
                                +	'<div class="buySuccess-cont">'
                                +		'<p>请注意以下事项：</p>'
                                +		'<p>1、'+ goods_info.source_name +'：<em>' + goods_info.price + '</em>元，请在下单页面核对价格是否一致。</p>'
                                +		'<p>2、返　现：<em>'+ goods.single_rebate +'</em>元，完成交易后，将返还给您的金额。</p>'
                                +		'<p>注意：报名抢购后<em>' + goods.auto_clear_time_min + '分钟内</em>不下单付款和返回<em>填写订单号</em>，本次报名记录将自动取消。</p>'
                                +	'</div>'
                                +'</div>';
                            var okval='抢购成功，现在马上去购买',
                                cancel = true;
                        };

						art.dialog({
							lock : true,
							fixed : true,
							title : '抢购成功',
							content : _content,
							init: function () {
								// 清空正“在发送...”，删除抢购验证码
								global_is_captcha == 1 && buy_vcMsg.css('color', '#c00').html('') && $('.buy-verify').remove();
							},
							okVal : okval,
							ok : function() {
								if(goods.type==5){
                                    page_detail.gotoCode();
                                    page_detail.check_goods_link(oid)
                                }else{
                                    page_detail.edit_order(oid)
                                }
                                this.close();
							},
							cancel : cancel,
							cancelVal : '继续抢购，稍后再去下单'
						});
					},
					error : function(xOptions, textStatus) {
						btn.data('posting', false).html('我要抢购');
						if(textStatus=='timeout'){
							error('系统连接超时，请稍后重试。');
						}else{
							error('系统繁忙，请稍后重试！');
						}
					}
				});
			});

		},
		remind: function () {    // 开抢提醒 || 追加提醒

			if ($('.goodsDetail-remind:hidden').length != 0) {
				return;
			}

			var sTime = parseInt(goods.starttime, 10),
			    nTime = parseInt(goods.nowtime, 10),
			    timeX;
			if (goods.state==5) {

				// 上线前 goods.goods_online_remind_prefix_time 分钟内不允许设置 开抢提醒
				timeX = (sTime - nTime) <= parseInt(goods.goods_online_remind_prefix_time, 10)*60;
				if (timeX) {

					art.dialog({
					    lock: true,
					    fixed: true,
					    title: '即将开抢',
					    content: '<p style="font-size: 14px; color: #666;">活动将在 <em style="color: #c00"; font-weight: 700;>'+ ( new Date(sTime*1000) ).toLocaleTimeString() +'点</em> 准时开始抢购，赶快做好准备哦！</p>',
					    ok: true
					});

					return;

				};
			}
			if (goods.state == 20 || goods.state == 22) {

				// 追加上线前 goods.goods_addition_remind_prefix_time 分钟内不允许设置 追加提醒
				timeX = (sTime - nTime) <= parseInt(goods.goods_addition_remind_prefix_time, 10)*60;
				if (sTime && timeX) {

					art.dialog({
					    lock: true,
					    fixed: true,
					    title: '即将开抢',
					    content: '<p style="font-size: 14px; color: #666;">活动将在 <em style="color: #c00; font-weight: 700;">'+ ( new Date(sTime*1000) ).toLocaleTimeString() +'点</em> 准时开始抢购，赶快做好准备哦！</p>',
					    ok: true
					});

					return;

				};
			}

			// 是否已登录
			var user = shs.user.info();
			if (!user) {
				shs.user.login();
				return;
			}

			// 商家不能设置提醒
			if (user.type != 1) {
				error('<p>很抱歉！众划算商品提醒功能只限买家使用，建议您注册买家帐号进行抢购！</p>' + '<div style="margin-top:20px; text-align:center;">' + '<a href="' + shs.site('login') + '" style="display:inline-block; padding: .6em .9em; background-color:#c00; color:#fff; border-radius: 3px; margin-right:20px;">买家登录</a>' + '<a href="' + shs.site('reg') + 'buyer/" style="display:inline-block; padding: .6em .9em; background-color:#c00; color:#fff; border-radius: 3px;">买家注册</a></div>');
				return;
			}

			// 验证
			var validation = {
				tel: function (num) {
					return /^1[3|4|5|8]\d{9}$/.test(num);
				},
				email: function (val) {
					return /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/.test(val);
				},

				// 预留。这次开发 验证码不是单独发送请求校验的，而是跟表单一起提交验证
				QRcode: function (val, callback) {

					$.get('/remind/QRcode/' + val, function (data) {
						typeof callback === 'function' && callback(data);
					});

				}
			}

			art.dialog({
				lock: true,
				fixed : true,
				title : '设置提醒',
				init: function () {
					var dialog = this;
					$.jsonp({
						url: shs.site('buyer')+'remind/bind',
						data: {
							username: user.name,
							gid: goods.gid,
							type: goods.state==5? 'online' : 'addition',
							check:'check'
						},
						callbackParameter: "callback",
						success: function (dt) {    // 获取用户已绑定的手机和邮箱
							if (!dt.success) {

								if (dt.data.msg == 'TOO_REMIND2') {
									dialog.close();
									error('抱歉，您的追加提醒设置次数达到了上限！');
								} else {
									dialog.close();
									error(dt.data.msg);
								}

								return;
							}
							if(dt.data.remind_repeat){
								dialog.close();
								error('该商品已经设置提醒了！');
								return;
							}
							if(dt.data.remaining < 1) {
								dialog.close();
								error('抱歉，您的提醒设置次数已经用完了！');
								return;
							}

							var html = '<div class="remind-dialog"><p>众划算将根据您的设置，在开抢前通过站内信、手机短信或邮件的方式提醒您团购消息。</p>'+
							'<form>'+
								'<p class="remind-znMail"><label><input type="checkbox" '+ ((dt.data.sys_msg == 1)? "checked" : "") +' />站内信提醒：</label>'+ $(".topbar-userName").text() +'</p>'+
								'<div class="remind-otherWays">'+
									'<p class="remind-otherWays-tit"><label><input type="checkbox" '+ ( (dt.data.email || dt.data.telNull)? "checked" : "") +' />其他提醒方式：</label></p>'+
									'<div class="remind-otherWays-cont">'+
										'<p class="remind-email">'+
											'<label><input type="radio" name="radioGroup" '+ (dt.data.email? "checked" : "") +' />邮箱提醒：</label>'+
											'<input class="ui-form-text ui-form-textRed" type="text" value="'+ (dt.data.email || "") +'" /><span class="remind-error">邮箱地址格式错误！</span></p>'+
										'<p class="remind-tel" style="color: #999;">'+
											'<label><input disabled="true" type="radio" name="radioGroup" '+ (dt.data.telNull? "checked" : "") +' />手机提醒：</label>'+
											'<input disabled="true" class="ui-form-text ui-form-textRed" type="text" value="'+ (dt.data.telNull || "即将开放，敬请期待") +'" /><span class="remind-error">手机号码格式错误！</span></p>'+
									'</div>'+
								'</div>'+
								'<p class="remind-code">'+
									'<label>验证码：</label>'+
									'<input class="ui-form-text ui-form-textRed" type="text" /><img src="' + shs.site('buyer')+'remind/create_code'+'?_' + parseInt( Math.random()*10e8, 10) + '" alt="验证码" />&nbsp;(点击图片刷新)<span class="remind-error">验证码错误！</span></p>' +
							'</form>'+
							'<p class="remind-remaining"><span>剩余提醒次数：<em>'+ dt.data.remaining +'</em><a target="_blank" href="http://help.shikee.com/guize/shs/shangpintixing/2013-11-23/2.html">( 如何获得提醒次数? )</a></span>'+'</p></div>';
							dialog.content(html);

							var form = dialog.DOM.content.find('form');
							var email = form.find('.remind-email'),
							    tel = form.find('.remind-tel'),
							    QRcode = form.find('.remind-code');

							// 交互
							$('.remind-otherWays-tit label').click(function(){
								var radio = email.find('input[type=radio]'),
									bl = $(this).find('input[type=checkbox]').eq(0).prop('checked');
								radio.each(function(){

									if(bl == true) {
										$(this).prop('disabled') || radio.prop('checked', true);
									} else {
										$(this).prop('checked', false);
									}

								});
							});
							email.find('label').add( tel.find('label') ).click(function(){
								var bl = $(this).find('input[type=radio]').prop('checked');
								$('.remind-otherWays-tit input[type=checkbox]').prop('checked', bl);
							});

							email.find('input[type=text]').blur(function(){
								if( !email.find('input[type=radio]').prop('checked') )
									return;
								var err = email.find('.remind-error'),
								    val = $.trim(this.value);
								if (val === '') {
									err.text('不能为空！').show();
									return;
								}
								if( !validation.email(val) ) {
									err.text('邮箱地址格式错误！').show();
									return;
								}
								err.hide();
							}).focus(function(){
								email.find('.remind-error').hide();
							});

							tel.find('input[type=text]').blur(function(){
								if( !tel.find('input[type=radio]').prop('checked') )
									return;
								var err = tel.find('.remind-error'),
								    val = $.trim(this.value);
								if (val === '') {
									err.text('不能为空！').show();
									return;
								}
								if( !validation.tel(val) ) {
									err.text('手机号码格式错误！').show();
									return;
								}
								err.hide();
							}).focus(function(){
								tel.find('.remind-error').hide();
							});

							QRcode.find('input[type=text]').blur(function(){
								var err = QRcode.find('.remind-error'),
									val = $.trim(this.value);
								if(val===''){
									err.text('不能为空！').show();
								}
							}).focus(function(){
								QRcode.find('.remind-error').hide();
							});

							QRcode.find('img').click(function () {
								var str = '?_' + parseInt( Math.random()*10e8, 10);
								this.src = shs.site('buyer')+'remind/create_code'+str;    // 返回图片地址
							});

							form.submit(function(){
								$.jsonp({
									url: shs.site('buyer')+'remind/save',
									data: {
										gid: goods.gid,
										znMail: $('.remind-znMail input[type=checkbox]').prop('checked') ? 'yes' : 'no',
										email: email.find('input[type=radio]').prop('checked')? email.find('input[type=text]').val() : 'false',
										tel: tel.find('input[type=radio]').prop('checked')? tel.find('input[type=text]').val() : 'false',
										code: QRcode.find('input[type=text]').val(),
										run_type: 'add',
										remind_type: goods.state==5? 'online' : 'addition'
									},
									callbackParameter: "callback",
									success: function(dt){
										if(dt.success) {
											dialog.close();
											art.dialog({
												fixed: true,
												title: '开抢提醒',
												content: '<div class="remind-success"><h3>设置成功！</h3><p>众划算将在开抢前通过站内信、手机短信或邮件的方式提醒您！</p><p class="remind-success-links"><span>您可以：</span><a target="_blank" href="'+ shs.site('buyer') +'/remind/">管理我的提醒</a></p></div>',
												ok: true
											});
										} else if (dt.data.msg === 'QRcodeError') {    // 验证码错误
											QRcode.find('.remind-error').text('验证码错误！').show();
										} else {
											alert(dt.data.msg);
										}
									},
									error : function(xOptions, textStatus) {
										if(textStatus=='timeout'){
											dialog.close();
											error('系统连接超时，请稍后重试。');
										}else{
											dialog.close();
											error('系统繁忙，请稍后重试！');
										}
									}
								});

								return false;
							});
						},
						error : function(xOptions, textStatus) {
							if(textStatus=='timeout'){
								dialog.close();
								error('系统连接超时，请稍后重试。');
							}else{
								dialog.close();
								error('系统繁忙，请稍后重试！');
							}
						}
					});
				},
				ok : function () {
					var form = this.DOM.content.find('form');
					var znMail = form.find('.remind-znMail'),
						email = form.find('.remind-email'),
					    tel = form.find('.remind-tel'),
					    QRcode = form.find('.remind-code');

					var selectEmail = email.find('input[type=radio]').prop('checked'),
					    selectTel = tel.find('input[type=radio]').prop('checked'),
					    selectZnMail = znMail.find('input[type=checkbox]').prop('checked');

					if( !(selectEmail || selectTel || selectZnMail) ) {
						alert('您至少需要选择一种提醒方式！');
						return false;
					}



					var valiVal = true;
					valiVal = selectEmail? $.trim( email.find('input[type=text]').val() ) : valiVal;
					valiVal = selectTel? $.trim( tel.find('input[type=text]').val() ) : valiVal;

					// 运行到这里 如果valiVal还是true的话 说明邮件和手机两种提醒方式都没有选择
					var result = true;
					if (valiVal !== true) {
						selectEmail && email.find('input[type=text]').triggerHandler('blur');
						selectTel && tel.find('input[type=text]').triggerHandler('blur');
						result = validation[selectEmail? 'email' : 'tel'](valiVal);
					}

					QRcode.find('input[type=text]').triggerHandler('blur');
					var QRcodeVal = $.trim( QRcode.find('input[type=text]').val() );
					if ( !result || QRcodeVal === '' ) {
						return false;
					}

					// validation.QRcode(QRcodeVal ,function(dt){    // 校验 ‘验证码’
					// 	if(dt.success){
					 		form.submit();    // 提交表单
					// 	}else {
					// 		QRcode.find('.remind-error').text(dt.data.msg).show();
					// 	}
					// });

					return false;
				},
				cancel: true
			});

		},
		loadBuyer : function(page) {
			var cont = oBuyer_conts.eq(0);
			if (cont.data('num') < 1) {
				cont.html('<p class="no-data">暂时无人下单~</p>');
				return;
			}
			if (cont.data('loading') || typeof (page) === 'undefined' && cont.data('loaded'))
				return;
			page = page > 0 ? page : 1;

			cont.data('loading', true);
			cont.html('<p class="loading-data">载入中...</p>');

			$.post('/buyer/' + goods.gid + '/' + page, function(data) {
				var data = $.parseJSON(data);
				var html = [];
				if (data.total > 0) {
					html.push('<div class="buyer-list clearfix" id="buyer-list">');
					for (var i in data.list) {
						var vo = data.list[i];
						html.push('<a href="http://bbs.shikee.com/space-uid-' + vo.uid + '.html" target="_blank">');
						vo.is_refund && html.push('<em></em>');
						html.push('<img src="' + vo.avatar + '"> <span>' + vo.uname + '</span> </a>');
					}
					data.pager && html.push('</div><div class="paging-center">', data.pager);
					html.push('</div>');
				} else {
					html.push('<p class="no-data">暂无数据。</p>');
				}
				cont.html(html.join(''));
				cont.data('loading', false);
				cont.data('loaded', true);
			});
		},
		loadShow : function(page) {
			var cont = oBuyer_conts.eq(1);
			if (cont.data('num') < 1) {
				cont.html('<p class="no-data">暂时无人晒单~</p>');
				return;
			}
			if (cont.data('loading') || typeof (page) === 'undefined' && cont.data('loaded'))
				return;
			page = page > 0 ? page : 1;

			cont.data('loading', true);
			cont.html('<p class="loading-data">载入中...</p>');

			$.post('/show/' + goods.gid + '/' + page, function(data) {
				var data = $.parseJSON(data);
				var html = [];
				if (data.total > 0) {
					html.push('<ul class="report-list clearfix">');
					for (var i in data.rows) {
						var vo = data.rows[i];
						html.push('<li>', '<a href="http://bbs.shikee.com/yesvalue.php?mod=showshop&uid=', vo.uid, '&showshopid=', vo.id, '" target="_blank" class="pic">');
						html.push('<img width="120" height="120" src="', vo.img_url, '"></a>');
						html.push('<div class="user"><a href="http://bbs.shikee.com/space-uid-', vo.uid, '.html" class="face" target="_blank">');
						html.push('<img src="', vo.avatar, '"></a>');
						html.push('<a class="name" href="http://bbs.shikee.com/space-uid-' + vo.uid + '.html" target="_blank">', vo.uname, '</a>');
						html.push('<p class="time">', vo.posttime, '</p>');
						html.push('<div class="words">', vo.words, '</div>', '</li>');
					}
					html.push('</ul>');
					data.pager && html.push('<div class="paging-center">', data.pager, '</div>');
				} else {
					html.push('<p class="no-data">暂无数据。</p>');
				}
				cont.html(html.join(''));
				cont.data('loading', false);
				cont.data('loaded', true);
			});
		},
		show_relative_goods : function() {
			goodsList = $( ".goods-sider .goods-show" );
			var switchover = $('[data-js="switchover"]'),
				goodsCont = $('.goods-sider-cont'),
				len = goodsList.length;
			if (len <= 3) {

				switchover.hide();

			} else if (len > 3) {

				goodsList.filter(':gt(2)').hide();
				switchover.click(function(){
					goodsCont.css({
						'height': goodsCont.height(),    //  防止其内元素切换时高度变化
						'overflow': 'hidden'
					});
					var v = goodsCont.find('.goods-show:visible');
					var h = goodsCont.find('.goods-show:hidden');
					v.fadeToggle(300,function(){h.show();});    // 显示|隐藏
				});

			}
		},

        edit_order:function(oid){
            //判断不是二维码下单就跳到指定商品原始页面
            if(goods_info.qrcode_state != 1){
                goods_info.url && window.open(goods_info.url);
            }
            var is_captcha = 0;
            var code_url = shs.site('buyer') + 'order/get_code/' +oid;
            var diy_form= (function(){
                    if(goods_info.qrcode_state == 1){
                        return '<div class="m-fillTrade m-fillTrade-qrcode">'
                            +     '<div class="qrcode">'
                            +         '<img src="' + goods_info.qrcode_img + '" alt="二维码">'
                            +         '<p>该活动为二维码下单活动，请扫</p>'
                            +         '<p>描以上二维码进入商家店铺购买</p>'
                            +         '<p>（请勿用微信扫描）</p>'
                            +     '</div>';
                    }else{
                        return '<div class="m-fillTrade">';
                    }
                })()
                +     '<div class="mform">'
                +         '<form>'
                +             '<table>'
                +                 '<tr class="small">'
                +                     '<th>商品名称：</th>'
                +                     '<td style="font-weight: bold;">'+ goods_info.title +'</td>'
                +                 '</tr>'
                +                 '<tr class="small">'
                +                     '<th>' + goods_info.source_name + '：</th>'
                +                     '<td style="font-weight: bold;">￥'+ goods.price +'</td>'
                +                 '</tr>'
                +                 '<tr>'
                +                     '<th>订单编号：</th>'
                +                     '<td>'
                +                         '<input type="text" class="ipt" name="trade_no">'
                +                         '<span class="tip J_tip_trade">&nbsp;</span>'
                +                     '</td>'
                +                 '</tr>'
                +                 '<tr class="z-dn">'
                +                     '<th>问题答案：</th>'
                +                     '<td>'
                +                         '<img class="code J_code" data-src="'+ code_url +'" alt="验证码" title="点击刷新验证码">'
                +                         '<input type="text" class="ipt" name="vcode" style="width: 75px;">'
                +                         '<span class="tip J_tip_vcode">&nbsp;</span>'
                +                     '</td>'
                +                 '</tr>'
                +             '</table>'
                +         '</form>'
                +         '<dl>'
                +             '<dt>温馨提示：</dt>'
                +             '<dd>'
                +                 '<p>1、请填写已付款的订单编号，若填入未付款单号，属于违规行为且将无法获得返现；<a href="http://help.zhonghuasuan.com/buyer/category/66/125/16" target="_blank">填写的订单号规则？</a></p>'
                +                 '<p>2、若单号被审核有误，请在 '+order_auto_close_time_hour+' 小时内进行申诉或修改，逾期将无法领回返现;（建议平时经常登录网站查看站内信提醒哦！）<a href="'+ goods_info.get_order_number_help +'" target="_blank">如何获取订单编号？</a>'
                +             '</dd>'
                +         '</dl>'
                +     '</div>'
                + '</div>';
            art.dialog({
                lock :true,
                fixed : true,
                title : '填写单号',
                content : diy_form,
                padding: 0,
                resize: false,
                ok: function(){
                    this.DOM.content.find('form').submit();
                    return false;
                },
                init: function(){
                    var
                        dialog   = this,
                        $form    = dialog.DOM.content.find('form'),
                        $codeImg = $form.find(".J_code"),
                        $codeTr  = $codeImg.closest("tr"),
                        $trade   = $form.find("input[name=trade_no]"),
                        $vcode   = $form.find("input[name=vcode]"),
                        $tipTrade= $form.find(".J_tip_trade"),
                        $tipVcode= $form.find(".J_tip_vcode")
                        ;
                    // 获得焦点
                    $trade.focus();
                    // 点击刷新验证码
                    $codeImg.click(function(){
                        this.src = $(this).attr('data-src') +'?'+ Math.floor( Math.random()*10e8 );
                        $codeTr.removeClass("z-dn");    // 显示验证码输入框
                    });
                    is_captcha == 1 && $codeImg.click();

                    $form.submit(function(){
                        if($form.data("post")){
                            return false;
                        }
                        var trade = $.trim($trade.val());
                        var vcode = $.trim($vcode.val());
                        $tipTrade.html('').removeClass("z-info");
                        $tipVcode.html('');

                        // 订单号只能包含数字或字母
                        if ( trade == '') {
                            $tipTrade.html('请填写订单号！');
                            $trade.focus();
                            return false;
                        } else if ( /[^\-0-9a-zA-Z]/.test(trade) ) {
                            $tipTrade.html('订单号格式有误！');
                            $trade.val('').focus();
                            return false;
                        }
                        // 验证码只能是数字且非空
                        if( is_captcha == 1 && !vcode ){
                            $tipVcode.html(' 验证码错误！');
                            $codeImg.click();
                            $vcode.focus();
                            return false;
                        }
                        // 锁住文本框
                        $trade.prop('disabled', true);
                        $vcode.prop('disabled', true);
                        $tipTrade.addClass("z-info").html("正在发送...");
                        // 锁住弹窗按钮
                        dialog.DOM.buttons.find('button:first').html('操作中...').attr('disabled', 'disabled').removeClass('aui_state_highlight');
                        // 表单上锁
                        $form.data("post", true);

                        var data = {trade_no : trade};
                        //判断是否需要传验证码参数
                        if( is_captcha ==1 ){
                            data.is_captcha = is_captcha;
                            data.vcode = vcode;
                        }
                        var complete = function(){
                            // 清除“正在发送...”文字
                            $tipTrade.html('').removeClass("z-info");
                            // 解锁文本框
                            $trade.prop('disabled', false);
                            $vcode.prop('disabled', false);
                            // 解锁弹窗按钮
                            dialog.DOM.buttons.find('button:first').html('确定').addClass('aui_state_highlight').prop("disabled", false);
                            // 解锁表单
                            $form.data("post", false);
                        };
                        $.jsonp({
                            url: shs.site('buyer')+'order/save_no/'+goods.gid+'/'+oid,
                            data: data,
                            callbackParameter: "callback",
                            success: function (dt) {
                                complete();
                                if (dt.success) {
                                    art.dialog({
                                        fixed : true,
                                        icon  : 'succeed',
                                        title : '填写单号成功',
                                        content : '<p style="line-height: 48px;font-size: 16px;font-family: Microsoft YaHei">填写单号成功！</p>',
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
                                    // 有验证码情况下，操作出错，则刷新验证码
                                    if(is_captcha == 1){
                                        $codeImg.click();
                                        $vcode.val('').focus();
                                    }
                                    switch(dt.data){
                                        case "NO_BIND_MOBILE":
                                            $tipTrade.html('未认证手机号码，<a href="'+shs.site('buyer')+'bind/mobile" target="_blank" style="color:#0066FF">现在去认证</a>');
                                            break;
                                        case "CAPTCHA_IS_NULL":
                                            $tipVcode.html('请输入验证码！');
                                            // 无验证码情况下，填单过快，则刷新验证码
                                            is_captcha == 0 && $codeImg.click();
                                            is_captcha = 1;
                                            break;
                                        case "CAPTCHA_ERROR":
                                            $tipVcode.html('验证码错误！');
                                            // 无验证码情况下，填单过快，则刷新验证码
                                            is_captcha == 0 && $codeImg.click();
                                            is_captcha = 1;
                                            break;
                                        case "TRADE_NO_IS_NULL":
                                            $tipTrade.html('请填写订单号！');
                                            $trade.val('').focus();
                                            break;
                                        case "TRADE_NO_ERROR":
                                            $tipTrade.html('订单号格式有误！');
                                            $trade.val('').focus();
                                            break;
                                        default:
                                            $tipTrade.html(dt.data);
                                            $trade.val('');
                                    }
                                    window.open(shs.site('buyer')+'order/');
                                }
                            },
                            error : function(xOptions, textStatus) {
                                complete();
                                if(textStatus=='timeout'){
                                    $tipTrade.html('系统连接超时，请稍后重试。');
                                }else{
                                    $tipTrade.html('系统繁忙，请稍后重试！');
                                }
                                window.open(shs.site('buyer')+'order/');
                            }
                        });
                        return false;
                    });
                }
            });
        },

		//核对搜索下单链接地址
		check_goods_link : function(oid){
			var str = '<div class="app-check-style"><form><p>请输入您找到的商品链接：</p><p><input id="shopLink" type="text"> <label id="shopLinkTip"></label></p></form></div>';
			var checkBlur= function(){
				var Http =/^http[s]?:\/\//,
                    $shopLink = $.trim($('#shopLink').val());
				if($shopLink == ''){
				    $('#shopLinkTip').text('请输入商品链接！')
				    return false;
				}else if(!Http.test($shopLink)) {
				    $('#shopLinkTip').text('您输入的链接不符合链接地址规范，请重新输入！')
				    return false;
				}else{
				    $('#shopLinkTip').text('')
				    return true;
				}
			};
            $(document).keypress(function(event){
                var e = event || window.event;
                if(e.keyCode == 13) {
                    $("#shopLink").blur()
                    $(".aui_state_highlight").click();
                    return false;
                }
            });
			art.dialog({
			    lock: true,
			    fixed: true,
			    title: '商品核对链接',
			    width: '450px',
			    content: str,
			    okVal : '核对',
			    ok : function(){
                    var dialog=this;
			        if(checkBlur()){
			            var shopLink = $('#shopLink').val();
			            $.jsonp({
			                url: shs.site('buyer')+'order/check_goods_url',
			                data:{
                            	gid:goods.gid,
                            	url:shopLink
                            },
			                callbackParameter: "callback",
			                success:function(data){
                                var oid=data.data.Oid;
                                if(data.success==true){
                                    art.dialog({
                                        lock: true,
                                        fixed: true,
                                        title: '温馨提示',
                                        content: '<p>下单地址已经匹配成功</p>',
                                        ok: function(){
                                            var dialog=this;
                                            dialog.close();
                                            $('#J_view-goods').hide();
                                            page_detail.edit_order(oid)
                                        }
                                    });
                                    dialog.close();
                                }else if (data.data.Code == -2) {
                                    if(parseInt(oid)>0){
                                        art.dialog({
                                            lock: true,
                                            fixed: true,
                                            title: '温馨提示',
                                            content: '<p>'+data.data.Message+'</p>',
                                            ok: function(){
                                                var dialog=this;
                                                dialog.close();
                                                $('#J_view-goods').hide();
                                                page_detail.edit_order(oid)
                                            }
                                        });
                                    }else{
                                        art.dialog({
                                            lock: true,
                                            fixed: true,
                                            title: '温馨提示',
                                            content: '<p>下单地址已经匹配成功,并且已经填写了订单号！</p>',
                                            ok: true
                                        });
                                    }
                                    dialog.close();
                                } else if (data.data.Code == -3) {
                                    $('#shopLinkTip').text('您找到的宝贝与商家提供的宝贝不符，请重新查找')
                                }else if(data.data.Code == -5){
                                    art.dialog({
                                        lock: true,
                                        fixed: true,
                                        title: '温馨提示',
                                        content: '<p>您的订单已被清除，无法核对链接！</p>',
                                        ok: true
                                    });
                                    dialog.close();
                                }
			                },
			                error:function(){
			                }
			            });
			        }
			        return false;
			    },
			    cancel : true,
			    cancelVal : '取消'
			});
		}
	};

})();
