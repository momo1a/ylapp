/**
 * 买家中心订单列表页6月活动报名挂件
 * Date 	2014/06/18
 * Author	陆楚良
 */

$(function($){
	$.get('/order/no_activity',function(data){
		var site = (function(){
				var domain = location.host.replace(/(\w+\.)*(\w+\.com)/, '.$2');
				return function(site){return 'http://'+site+domain+'/';}
			})();

		var html  = '<div class="widget-signUp">'
				  + 	'<span class="widget-signUp-prize">6.20~6.30</span>'
				  + 	'<h4 class="widget-signUp-title">'
				  + 		'<a href="http://bbs.shikee.com/thread-608134-1-1.html" target="_blank" title="众划算感恩大回馈，30000现金大礼等你来拿！">众划算感恩大回馈，30000现金大礼等你来拿！</a>'
				  + 	'</h4>'
				  + 	(function(){
				  			switch(Number(data.Code)){
				  				case 100:
				  					return '<p class="widget-signUp-note">提交报名成功</p><a class="widget-signUp-button" data-code="100">参与详情</a>';
				  				case 1:
				  					return '<p class="widget-signUp-note">您已满足报名条件</p><a class="widget-signUp-button" data-code="1">我要参与</a>';
				  				case 2:
					   				return '<p class="widget-signUp-note">您还差：<i>￥</i><em>'+data.money+'</em></p>'
					   					  +'<a class="widget-signUp-button widget-signUp-button-disabled" data-code="2">我要参与</a>';
					   			default:
					   				return '<p class="widget-signUp-note">您的报名条件未符合，请查看活动规则。</p>';
				  			}
				    	})()
				  + '</div>';

		var $html = $(html);






		// 报名按钮
	    $html.find(".widget-signUp-button").click(function(){
	        if($(this).hasClass("widget-signUp-button-disabled"))return;
	        switch(Number($(this).data("code"))){
	        	//我要报名
	        	case 1:
		            art.dialog({
		                title: "抽奖报名",
		                lock : true,
		                padding: 0,
		                width: 375,
		                content: '<form class="widget-signUp-form">'
		                        +    '<dl>'
		                        +        '<dt>用户名：</dt>'
		                        +        '<dd>'+data.uname+'</dd>'
		                        +        '<input type="hidden" name="dosubmit" value="yes"/>'
		                        +    '</dl>'
		                        +   ' <dl>'
		                        +        '<dt>邮　箱：</dt>'
		                        +       ' <dd><input type="text" class="ui-form-text ui-form-textRed" name="email" value="'+data.email+'"></dd>'
		                        +    '</dl>'
		                        +    '<dl>'
		                        +        '<dt>手　机：</dt>'
		                        +        '<dd><input type="text" class="ui-form-text ui-form-textRed" name="mobile" value="'+data.mobile+'"></dd>'
		                        +'</dl>',
		                okVal: "提交",
		                cancel: true,
		                ok   : function(){this.DOM.content.find("form").submit(); return false;},
		                init : function(){
		                    var dialog = this;
		                    this.DOM.content.find("form").submit(function(){
		                        dialog.DOM.buttons.find('button:first').html('操作中...').attr('disabled', 'disabled').removeClass('aui_state_highlight');
		                        $.ajax({
		                            url: '/order/add_user_activity',
		                            data: $(this).serialize(),
		                            dataType: 'json',
		                            error: function(){
		                                alert("网络连接失败！");
		                                dialog.DOM.buttons.find('button:first').html('提交').removeAttr('disabled').addClass('aui_state_highlight');
		                            },
		                            success: function(ret){
		                                dialog.DOM.buttons.find('button:first').html('提交').removeAttr('disabled').addClass('aui_state_highlight');
		                                alert(ret.data);
		                                if(ret.success){
		                               	 	dialog.close();
		                                    location.reload();
		                                }
		                            }
		                        });
		                        return false;
		                    });
		                }
		            });
	        		break;

	        	//参与详情
	        	case 100:
		            art.dialog({
		                title: "提交详情",
		                lock : true,
		                padding: 0,
		                width: 375,
		                okVal: "确定",
		                ok   : true,
		                init : function(){
		                    var dialog = this;
		                    var load  = function(){
		                        dialog.content('<div style="text-align:center;padding:25px;"><img style="vertical-align: middle;margin-right:10px;" src="'+site('static')+'images/user/loading.gif"/><span>拼了小命加载中，请稍后...</span></div>');
		                        $.ajax({
		                            url: '/order/add_user_activity',
		                            data: {},
		                            dataType: 'json',
		                            error: function(){
		                                dialog.DOM.content.find("span").html('遇到了点小问题，<a href="javascript:;" style="color:#3266CC">点击此处重新加载</a>。').find('a').click(load);
		                            },
		                            success: function(data){
										if(!data.success){
											dialog.DOM.content.find("span").html(data.data+'，<a href="javascript:;" style="color:#3266CC">点击此处重新加载</a>。').find('a').click(load);
											return;
										}
		                            	var user_info = data.data.userdata;
		                                var html = '<form class="widget-signUp-form">'
		                                         +    '<dl>'
		                                         +        '<dt>用户名：</dt>'
		                                         +        '<dd>'+user_info.uname+'</dd>'
		                                         +    '</dl>'
		                                         +   ' <dl>'
		                                         +        '<dt>邮　箱：</dt>'
		                                         +       ' <dd>'+user_info.email+'</dd>'
		                                         +    '</dl>'
		                                         +    '<dl>'
		                                         +        '<dt>手　机：</dt>'
		                                         +        '<dd>'+user_info.mobile+'</dd>'
		                                         +   '</dl>'
		                                         +    '<dl>'
		                                         +        '<dt>提交时间：</dt>'
		                                         +        '<dd>'+user_info.dateline_str+'</dd>'
		                                         +   '</dl>'
		                                         +'</form>';
		                               dialog.content(html);
		                               dialog.DOM.content.find("form").submit(function(){dialog.close();return false});
		                            }
		                        });
		                    };load();
		                }
		            });
					break;
	        }
	    });

		$(".J_BoxHead").css("position","relative").append($html);

	},'json');

});