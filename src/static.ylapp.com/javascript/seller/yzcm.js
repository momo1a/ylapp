/**
 * 短暂提示
 * @param	{String}	提示内容
 * @param	{Number}	显示时间 (默认1.5秒)
 * @param	{function}	提示框初始化时执行
 * @param	{function}	提示框关闭时执行
 * @param	{String}	icon图标，默认null
 */
artDialog.tips = function(content, time, initfunc, closefunc, icon) {
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
	return artDialog({
		id : 'Tips',
		title : false,
		cancel : false,
		fixed : true,
		lock : true,
		icon: (_icon || null),
		init : (initfunc || null),
		close : (closefunc || null)
	}).content('<div style="padding: 0 1em;">' + content + '</div>').time(time || 1);
};
var yzcm = {
	/**
	 * 发布商品淘宝客选择否时提示
	 */
	dodeposit : function(form, fail_reason_url) {
		var _fail_reason_url = typeof fail_reason_url == 'string' ? fail_reason_url : '';
		var _form = $(form);
		if(!$(".yzcm-agree").get(0).checked){
            $(".yzcm-error").show();
            return false;
        }else{
            $(".yzcm-error").hide();
            art.dialog({
                title:"支付结果",
                id: "yzcm_payment_tip",
                content:'<ul class="yzcm-dialog">'
                            +'<li>请在新打开的界面完成支付 , 是否支付成功 ?</li>'
                            +'<li>'
                                +'<a class="ui-form-button ui-form-buttonBlue" onclick="art.dialog.list.yzcm_payment_tip.close();location.reload();" >支付成功</a>　　'
                                +'<a class="ui-form-button ui-form-buttonGray" onclick="art.dialog.list.yzcm_payment_tip.close()" target="_blank" href="'+_fail_reason_url+'">支付失败</a>'
                            +'</li>'
                        +'</ul>',
                lock:true
            });
        }
	},
	refund:function(o){
		var target_btn = $(o);
		var dia   = art.dialog({
            title  : "申请退款",
            content: "诚信金退还后，您也可以正常发布【一站成名】活动。",
            icon   : "question",
            lock   : true,
            cancel : true,
            button : [
                {
                    name: "确认退款",
                    focus: true,
                    callback: function(){
                    	target_btn.val("申请退款中").attr('disabled', 'disabled');
                    	target_btn.removeAttr('onclick');
            			var _tips_dialog = yzcm.do_submit('正在操作，请稍后...', 120, function() {
            				$.post('/yzcm/undeposit/', {doundeposit:1,inajax:1},function(ret){
            					_tips_dialog.close();
            					ret = $.parseJSON(ret);
            					if(ret.success){
            						artDialog.tips(ret.data, 3, null,null, 'S');
									location.reload();
            					}else{
            						artDialog.tips(ret.data, 3, null, target_btn.removeAttr('disabled'), 'W');
            					}
            				});
            			}, function() {
            				return true;
            			},'FSE');
                        artDialog.tips('申请已成功提交，退款操作1-2个工作日完成!', 3);
                        dia.close();
                    }
                }
            ]
        });
	},
	undo_refund:function(){
		$.ajax({
			url : '/yzcm/undo_yzcm_refund/',
			data: {undodeposit:1,inajax:1},
			dataType: 'json',
			error:function(){ artDialog.tips('撤销申请失败，请重试!', 5);},
			success:function(ret){
				if(ret.success){
					artDialog.tips(ret.data, 5, null,null, 'S');
					location.reload();
				}
				else{
					artDialog.tips(ret.data, 3, null,null, 'E');
				}
			}
		});
	},
	do_submit : function(msg, time, initfunc, closefunc, icon) {
		return artDialog.tips(msg, time, initfunc, closefunc, icon);
	}
}; 