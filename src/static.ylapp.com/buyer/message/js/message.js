$(function() {

    var Message = function($) {
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
            Loader.use('artDialog').run(function() {
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
            })
        };
        return {
            /*
                初始化绑定事件
            */
            init: function() {
                var self = this;
                $('.J_checkAll').click(function() {
                    self.checkBox();
                });
                $('.J_del_msg_all').click(function() {
                    self.delMsgCheck();
                });
                $('.J_mark').click(function() {
                    self.markMsg();
                });

                $('.J_next_page').click(function() {
                    self.moreMsg();
                });

                $('.J_read_msg').delegate('.J_del_msg', 'click', function() {
                    var id = $(this).closest('li.itme').data('id');
                    self.delMsg.call($(this), id);
                });

                $('.J_read_msg').delegate('.J_look', 'click', function() {
                    var id = $(this).closest('li.itme').data('id');
                    self.readMsg.call($(this), id);
                });

                return this;
            },
            /*
                全选
            */
            checkBox: function() {
                $('.J_read_msg').find('input:checkbox').prop('checked', $('.J_checkAll').prop('checked'));
            },
            /*
                删除单个消息
            */
            delMsg: function(id) {
                showMessage('温馨提示', '确认要删除所选的消息吗？', 'W', function() {
                    $.ajax({
                        url: '/message/remove/',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id: id
                        },
                        error: function() {
                            showMessage(null, '删除失败！请稍后再试！', 'E');
                        },
                        success: function(ret) {
                            if (ret.success) {
                                var ids = (id + '').split(',');
                                for (var i = 0; i < ids.length; i++) {
                                    $('li.itme[data-id=' + ids[i] + ']').remove();
                                };
                                window.location.reload();
                            } else {
                                showMessage(null, ret.data, 'E');
                            }
                        }
                    })
                })
            },
            /*
                
            /*
                删除选中消息
            */
            delMsgCheck: function() {
                var c = this.cd();
                if (c.len > 0) {
                    this.delMsg(c.v.join(','));
                } else {
                    showMessage(null, '请选择需要删除的消息！', 'W');
                }
            },
            /*
                读取消息
            */
            readMsg: function(id) {
                var p = this.closest('li.itme');
                if (!p.hasClass('inread')) {
                    this.html('∧ 收起');
                    p.addClass('inread');
                    if (!p.hasClass('read')) {
                        $.ajax({
                            url: '/message/read/',
                            type: 'GET',
                            dataType: 'json',
                            data: {
                                id: id
                            },
                            error: function() {
                                console.log('标记已读操作失败！')
                            },
                            success: function(ret) {
                                if (ret.success) {
                                    p.removeClass('no-read').addClass('read');
                                }
                            }
                        })
                    };
                } else {
                    p.removeClass('inread');
                    this.html('∨ 查看');
                }
            },
            /*
                标记已读
            */
            markMsg: function() {
                var l = $('li.itme').find('input:checked').length;
                var v = [];
                $('li.no-read').find('input:checked').each(function() {
                    v.push($(this).val());
                });
                if(v.length == 0 && l > 0){
                	showMessage(null, '所选不是未读的站内信！', 'E');
                }
                if (l > 0) {
                    showMessage(null, '您确定要把所选的站内信标记为已读吗？', 'W', function() {
                        $.ajax({
                            url: '/message/read/',
                            type: 'GET',
                            dataType: 'json',
                            data: {
                                id: v.join(',')
                            },
                            error: function() {
                                showMessage(null, '服务器繁忙，请稍后再试！', 'E');
                            },
                            success: function(ret) {
                                if (ret.success) {
                                    for (var i = 0; i < v.length; i++) {
                                        $('.J_read_msg').find('li[data-id=' + v[i] + ']').removeClass('no-read').addClass('read');
                                    };
                                    $('.J_read_msg').find('input').removeAttr('checked');
                                } else {
                                    showMessage(null, '操作失败，请稍候再试！', 'E');
                                }
                            }
                        })
                    })
                } else {
                    showMessage(null, '您没有选择任何站内信！', 'W');
                }
            },
            /*
                获取更多消息
            */
            moreMsg: function() {
                var self = this;
                var $next = $('.J_next_page');
                var p = $next.data('p') || 1;
                if ($next.hasClass('disabled')) return;

                $.ajax({
                    url: '/message/next/'+(p+1),
                    type: 'GET',
                    cache:false,
                    dataType: 'html',
                    error: function() {
                        showMessage(null, '服务器繁忙，请稍后再试！', 'E');
                    },
                    success: function(ret) {
                        ret = $.trim(ret);
                        if (ret) {
                            $next.data('p', p + 1);
                            $('.J_read_msg').append(ret);
                        } else {
                            $next.addClass('disabled').html('没有更多消息了')
                        }
                    }

                })
            },
            /*
                获取选中消息
            */
            cd: function() {
                var c = {};
                c.v = [];

                c.len = $('.J_read_msg').find('.itme input:checked').length;
                $('.J_read_msg').find('.itme input:checked').each(function() {
                    c.v.push($(this).val());
                });

                return c;
            }

        }

    }(jQuery).init();

})