/**
 * 申诉列表页功能js
 */
Loader.use("frame","artDialog").run(function(){
    shs.appeal = function(appeal){
        "use strict";
        var undefined = void 0;
        var fn = {};
        fn.dialog = function(msg, title, icon, extend){
            // dialog(msg, extend)
            if($.type(title)=="object"){
                extend = title;
                title = undefined;
                icon  = undefined;
            }
            // dialog(msg, title, extend)
            if($.type(icon)=="object"){
                extend = icon;
                icon = undefined;
            }
            title = $.type(title)=="string" ? title : "温馨提示";
            icon  = $.type(icon )=="string" ? icon  : undefined;
            extend = $.type(extend)=="object" ? extend : {};
            return art.dialog({
                lock : true,
                fixed: true,
                id   : "dialog-order-fn",
                title: title,
                icon : icon,
                content: '<span class="s-fc-n">'+msg+'</span>',
                ok     : extend.ok,
                cancel : extend.cancel,
                close  : extend.close
            });
        };
        /**
         * 修改弹窗按钮状态
         * @param  {art.dialog} dialog  art.dialog对象
         * @param  {Boolean}    can_use 按钮是否可以使用
         * @return {art.dialog}
         */
        fn.dialog_btn_state = function(dialog, can_use){
            if (can_use) {
                dialog.DOM.buttons.find('button:first').html('确定').removeAttr('disabled').addClass('aui_state_highlight');
            } else {
                dialog.DOM.buttons.find('button:first').html('操作中...').attr('disabled', 'disabled').removeClass('aui_state_highlight');
            }
            return dialog;
        };

        /**
         * 取消申诉
         * @param  {Number} order_id  订单id
         * @param  {Number} appeal_id 申诉id
         */
        appeal.cancel = function(order_id, appeal_id){
            var dialog = fn.dialog('撤销审核后抢购状将恢复到申述前的状态；确定要撤销申诉？', "温馨提示", "question", {calcel:true, ok: function(){
                fn.dialog_btn_state(dialog, false);
                $.ajax({
                    url     : '/order_appeal/cancel/'+order_id+'/'+appeal_id,
                    type    : 'GET',
                    dataType: 'json',
                    error   : function(){
                        dialog.close();
                        fn.dialog('网络连接失败！', '温馨提示', 'errir', {ok:true});
                    },
                    success : function(ret){
                        dialog.close();
                        var isSuccess = ret.success ? 1 : 0;
                        fn.dialog(ret.data, '温馨提示', ['error','succeed'][isSuccess], {ok:true, close: function(){
                            isSuccess && location.reload();
                        }});
                    }
                });
            }});
        };
        // 事件绑定
        Frame.Action["appeal.cancel"] = function(event, order_id, appeal_id){appeal.cancel(order_id, appeal_id);  return false; };
        return appeal;
    }({});
});
