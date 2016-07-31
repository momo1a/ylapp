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
                cancel: typeof callback === 'function' ? true : null
            });
        };
        return {
            /*
                初始化绑定事件
            */
            init:function(){
                var self = this;
                var frSel = $('.J_appeal_s').data('frame.form.select');
                frSel.Event.on("set_index", function(){
                    self.tipMsg(frSel.get_value());
                    self.clearRoder(frSel.get_value());
                });

                $('#appeal-add-form').submit(function(){
                    var ret = self.checkAppeal(frSel.get_value());
                    return ret;
                })

                $('.J_more_ipt').click(function() {
                    self.addAppeal();
                });
                $('.J_file_up').delegate('.J_del_ipt', 'click', function() {
                    self.delIpt.call($(this));
                });
                return self;
            },
            /*
                根据申诉类型显示提示语
            */
            tipMsg:function(v){
                var tipData = {
                        '0' : '上传与申诉内容对应的凭证，以便管理员核实处理哦！', // 选择申诉类型
                        '1' : '提供您的购买凭证，并在管理员处理后的 '+ obj.time +' 小时内修改正确单号哦！', // 申请修改单号
                        '2' : '申诉成功后，将关闭您本次抢购流程。', // 申请取消资格
                        '3' : '请提供正确的订单购买截图，以便管理员核实处理哦！', // 申请延长返现时间
                        '4' : '提供店铺价格不一致的凭证，以便管理员核实处理哦！', // 网购价有误
                        '6' : '提供正确的购买凭证，以便管理员核实恢复哦！', // 单号被审核有误
                        '7' : '上传与申诉内容对应的凭证，以便管理员核实处理哦！' // 其他
                    }
                $('.J_tip').html(tipData[v]);
            },
            /*
                申请取消资格
            */
            clearRoder:function(v){
                if (v == 2) {
                    $('.J_contact').hide();
                }else{
                    $('.J_contact').show();
                }
            },
            /*
                添加申诉凭证
            */
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
            checkAppeal:function(v){
                var reg = {
                    QQ:/^[1-9]\d{4,13}$/,
                    Mobile:/^1[3-8]\d{9}$/
                }
                var appeal_form    = $('#appeal-add-form');
                var appeal_reason  = $.trim(appeal_form.find('textarea[name=content]').val());
                var appeal_img     = appeal_form.find('input[name=img_1]').val();
                var contact_qq     = $.trim(appeal_form.find('input[name=contact_qq]').val());
                var contact_ww     = $.trim(appeal_form.find('input[name=contact_wangwang]').val());
                var contact_mobile = $.trim(appeal_form.find('input[name=contact_telephone]').val());

                if (v == 0) {
                    showMessage(null, '请选择申诉类型！', 'E');
                }else if (appeal_reason == '') {
                    showMessage(null, '请填写申诉理由！', 'E');
                }else if (v != 2 && contact_qq == '' && contact_ww == '' && contact_mobile == '') {
                    showMessage(null, '联系方式至少要填写一项!', 'E');
                }else if (v != 2 && contact_qq != '' && !reg.QQ.test(contact_qq)) {
                    showMessage(null, '填写联系QQ不正确!', 'E');
                }else if (v != 2 && contact_mobile != '' && !reg.Mobile.test(contact_mobile)) {
                    showMessage(null, '填写联系手机号不正确!', 'E');
                }else if (v != 2 && appeal_img == '') {
                    showMessage(null, '未选择凭证图片!', 'E');
                }else{
                    return true;
                }
                return false;
            }
        }
    }(jQuery).init();
});
