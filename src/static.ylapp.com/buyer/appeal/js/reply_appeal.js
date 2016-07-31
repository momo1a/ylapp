Loader.use("frame", "artDialog").run(function(){
    var Appeal = function($){
        function showMessage(tit, msg, type, callback) {
            var icon;
            var tit = tit == null ? '温馨提示' : tit;
            switch (type) {
                case 'E':
                    icon = 'error';
                    break;
                case 'Q':
                    icon = 'question';
                    break;
                case 'S':
                    icon = 'succeed';
                    break;
                case 'W':
                    icon = 'warning';
            }
            art.dialog({
                title: tit,
                id: 'msg',
                lock: true,
                fixed: false,
                drag: false,
                icon: icon,
                content: msg,
                ok: function() {
                    if (typeof callback === 'function') {
                        callback();
                    } else {
                        return true;
                    }
                },
                // cancel: typeof callback === 'function' ? true : null
            });
        };
        return {
            /*
                初始化绑定事件
            */
            init:function(){
                var self = this;
                $(".J_submit").click(function(){
                    var form=$('#appeal-add-form'),
                        appeal_id=form.find('input[name=appealId]').val();
                    form.ajaxSubmit({
                        type: "post",
                        url: "/order_appeal/reply_post/" +appeal_id ,
                        dataType: 'json',
                        beforeSubmit:function(){
                            var ret = self.checkAppeal();
                            return ret;
                        },
                        success: function(ret) {
                            if (ret.success) {
                                showMessage('回应申诉成功', '回应申诉成功，请等待管理员处理。', 'S', function(){
                                    location.href='/order_appeal/index?t=1&s=id&v='+appeal_id;
                                })

                                //return;
                            }
                            showMessage(null, ret.data, 'W');
                        },
                        error:function(){
                            showMessage(null, '链接服务器失败，请稍后再试！', 'E');
                        }
                    });
                })

                $('.J_more_ipt').click(function() {
                    self.addAppeal();
                });
                $('.J_file_up').delegate('.J_del_ipt', 'click', function() {
                    self.delIpt.call($(this));
                });
                return self;
            },
            addAppeal:function(){
                var len = $('.J_file_up').find('.J_file_item').length;
                if ( len < 5) {
                    var html = '<span class="J_file_item"><input type="file" name="img_'+ (len+1) +'"><a class="J_del_ipt" href="javascript:;" >x删除</a></span>';
                    $('.J_file_up').append(html);
                    len == 4 ? $('.J_more_ipt').removeClass('s-fc-a').addClass('disabled') : '';
                };
            },
            /*
                删除上传input
            */
            delIpt:function(){
                this.closest('.J_file_item').remove();
                $('.J_more_ipt').removeClass('disabled').addClass('s-fc-a');
            },
            /*
                验证提交申诉内容
            */
            checkAppeal:function(){
                var appeal_form    = $('#appeal-add-form');
                var reply_content  = $.trim(appeal_form.find('textarea[name=content]').val());
                var reply_img     = appeal_form.find('input[name=img_1]').val();

                if (reply_content == '') {
                    showMessage(null, '请填写回应内容！', 'E');
                }else if (reply_img == '') {
                    showMessage(null, '未选择凭证图片!', 'E');
                }else{
                    return true;
                }
                return false;
            }
        }
    }(jQuery).init();
});
