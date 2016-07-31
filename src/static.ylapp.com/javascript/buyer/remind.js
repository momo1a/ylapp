$(function(){

	$('.remind table input[type=checkbox]').prop("checked", false);    // for firefox bug
	$('.remind-search input[type=text]').focus(); 
	$(".ui-table-checkAll input[type=checkbox]").checkAll(".remind tbody input[type=checkbox]");

	$('.remind-search form select').change(function(){
		$('.remind-search form').submit();
	});

});

var remind = {
	init: function () {

		$(function(){
			
			// 设置提醒
			$('[data-remind=edit]').click(function(){
				var val = $(this).closest('tr').find('input[type=checkbox]')[0].value;
				if( !$.isNumeric(val) )
					return;
				remind.edit(val);
			});
			$('[data-remind=editMultiple]').click(function(){

				var list = [];
				$('.remind tbody input[type=checkbox]').filter(':checked').each(function(){
					var val = this.value;
					if( !$.isNumeric(val) )    // 如果不是数字就跳到下一个循环
						return true;
					list.push(val);
				});
				list.length<1? alert('请选择要操作的内容，可以选择多个！') : remind.edit(list);

			});

			// 再次提醒
			$('[data-remind=again]').click(function(){
				var val = $(this).closest('tr').find('input[type=checkbox]')[0].value;
				if( !$.isNumeric(val) )
					return;
				remind.again(val);
			});

			// 取消提醒
			$('[data-remind=remove]').click(function(){
				var input = $(this).closest('tr').find('input[type=checkbox]');
				if ( input.prop('disabled') ) {
					alert('提醒未发送成功，无法删除，提醒次数已退还！');
					return false;
				}

				var val = input[0].value;
				if( !$.isNumeric(val) ) return;
				remind.remove(val);
			});
			$('[data-remind=removeMultiple]').click(function(){

				var list = [];
				$('.remind tbody input[type=checkbox]').filter(':checked').each(function(){
					var val = this.value;
					if( !$.isNumeric(val) )    // 如果不是数字就跳到下一个循环
						return true;
					list.push(val);
				});
				list.length<1? alert('请选择要操作的内容，可以选择多个！') : remind.remove(list);

			});

			// 同款推荐
			$('[data-recommend=true]').click(function(){

				var gid = $(this).attr("data-gid");
				var pid = $(this).attr("data-pid");
				var cid = $(this).attr("data-cid");
				var uid = $(this).attr("data-uid");
				if( !$.isNumeric(gid) || !$.isNumeric(pid) || !$.isNumeric(cid) || !$.isNumeric(uid))
					return;
				remind.recommend(gid,pid,cid,uid);		
				return false;
			});

		});

	},
	edit: function (val) {
		var isNumber = $.isNumeric(val);
		var isArray = $.isArray(val) && val.length>0;

		// 是否是 合法的数据：数字(或可以转换为数字的字符串) 或 非空数组
		if ( !(isNumber || isArray) ) return;

		art.dialog({
			lock : true,
			fixed : true,
			title : '设置提醒',
			init: function () {
				var dialog = this;
				$.get('/remind/bind', {id:[].concat(val).join(',')},function (d) {    // 获取用户已绑定的手机和邮箱
					var dt = $.parseJSON(d);
					if(!dt.success) return;
					
					var str = '?_' + parseInt( Math.random()*10e8, 10);
					var code_src  = '/remind/create_code'+str;    // 返回图片地址
					
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
								'<input class="ui-form-text ui-form-textRed" type="text" /><img src="' + code_src + '" alt="验证码" />&nbsp;(点击图片刷新)<span class="remind-error">验证码错误！</span></p>' +
						'</form></div>';
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

					// 验证
					email.find('input[type=text]').blur(function(){ 
						if( !email.find('input[type=radio]').prop('checked') ) 
							return;
						var err = email.find('.remind-error'),
						    val = $.trim(this.value);
						if (val === '') { 
							err.text('不能为空！').show(); 
							return;
						}
						if( !remind.validation.email(val) ) {
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
						if( !remind.validation.tel(val) ) {
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
						this.src =code_src;    // 返回图片地址
					});

					form.submit(function(){

						$.post('/remind/save', {
							id: [].concat(val).join(','),
							znMail: $('.remind-znMail input[type=checkbox]').prop('checked') ? 'yes' : 'no',
							email: email.find('input[type=radio]').prop('checked')? email.find('input[type=text]').val() : 'false',
							tel: tel.find('input[type=radio]').prop('checked')? tel.find('input[type=text]').val() : 'false',
							code: QRcode.find('input[type=text]').val(),
							run_type:'edit'
						}, function(dt){
							dt = $.parseJSON(dt);
							if(dt.success) {
								dialog.close();
								art.dialog({
									fixed: true,
									title: '开抢提醒',
									content: '<p>设置成功！</p><p>众划算将在开抢前通过站内信、手机短信或邮件的方式提醒您！</p>',
									ok: function () {
										location.reload();
										return true;
									}
								});
							} else if (dt.data.msg === 'QRcodeError') {    // 验证码错误
								QRcode.find('.remind-error').text('验证码错误！').show();
							} else {
								alert(dt.data.msg);
							}
						});

						return false;
					});

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
					result = remind.validation[selectEmail? 'email' : 'tel'](valiVal);
				}

				QRcode.find('input[type=text]').triggerHandler('blur');
				var QRcodeVal = $.trim( QRcode.find('input[type=text]').val() );
				if ( !result || QRcodeVal === '' ) { 
					return false;
				}

				// remind.validation.QRcode(QRcodeVal ,function(dt){    // 校验 ‘验证码’
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
	again: function (val) {
		if( !$.isNumeric(val) )
			return;
		$.get('/remind/again/', {
			id: val
		}, function (dt) {
			dt = $.parseJSON(dt);
			if (dt.success) {
				art.dialog({
					fixed : true,
					title : '设置开抢提醒',
					content: '<p>设置成功！</p><p>众划算将在开抢前通过站内信、手机短信或邮件的方式提醒您！</p>',
					ok : function () {
						location.reload();
						return true;
					}
				});
			} else {
				alert(dt.data.msg);
			}
		});
	},
	remove: function (val) {    // 单个直接传值，多个就传数组

		var isNumber = $.isNumeric(val);
		var isArray = $.isArray(val) && val.length>0;

		// 是否是 合法的数据：数字(或可以转换为数字的字符串) 或 非空数组
		if ( !(isNumber || isArray) ) return;
		
		art.dialog({
			lock : true,
        	fixed : true,
        	title: '取消提醒',
        	content: '取消提醒后您将不能第一时间收到抢购信息，您确定要删除么？<p style="color: #999">(提示：未发送的提醒，删除后提醒次数会自动退还)</p>',
        	ok: function () {

				$.get('/remind/del/', {
					id: [].concat(val).join(',') 
				}, function (dt) {
					dt = $.parseJSON(dt);
					if (dt.success) {
						location.reload();
						return;
					}
					alert(dt.data.msg);
				});

        		return true;
        	},
        	cancel: true
		});

	},
	validation: {
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

};

remind.recommend = function (gid,pid,cid,uid) {   // 同款推荐
    art.dialog({
    	lock : true,
        fixed : true,
        title : '同款推荐',      
        init: function () {

            var dialog = this;

            $.get('recommend', {  
                gid: gid,
                pid:pid,
                cid:cid,
                uid:uid
            }, function (dt) {

                dt = $.parseJSON(dt);
                if(dt.success) {

                    var html = '';

                    for (var i = 0, l = dt.data.length; i<l; i++) {

                        var goods = dt.data[i];
                        html += '<div class="recommendGoods">'+
                                  '<a class="recommendGoods-pic" href="'+ goods.url +'" titlt="'+ goods.title +'" target="_blank">'+
                                    '<img src="'+ goods.img +'" alt="商品图片" />'+
                                  '</a>'+
                                  '<a class="recommendGoods-tit" href="'+ goods.url +'" target="_blank">'+ goods.title +'</a>'+
                                  '<p class="recommendGoods-price">￥<em>'+ goods.cost_price +'</em></p>'+
                                '</div>';
                    }

                    html = '<div class="clearfix">'+ html +'</div>';
                    dialog.close();

                    art.dialog({
                    	lock: true,
                    	fixed: true,
                    	title: '同款推荐',
                    	init: function () {},
                    	content: html
                    });

                } else {
                    dialog.content(dt.data);
                }

            });

        }
    });

}

remind.init();