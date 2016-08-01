$(function(){
    var Invite = function($, v){
        function showMessage(tit, msg, type, callback, okv) {
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
                    okVal: okv || '确定',
                    cancel: typeof callback === 'function' ? true : null
                });
            })
        };

        return {
            /*
                初始化绑定事件
            */
            init:function(){
                var self = this;
                self.copyLink();

                $('#J_show').click(function() {
                    $(this).html($(this).data('price'));
                });

                $('.J_protocol').click(function() {
                    self.inviteOpen();
                });

                $('#J_more').click(function() {
                    self.moreLink();
                });

                self.inviteShare('J_shortcut', self.shareCont.tit1, v.shareUrl, self.shareCont.pic1);
                self.inviteShare('J_share_price', self.shareCont.tit2, self.shareCont.url, self.shareCont.pic2);

                return this;
            },
            /*
                分享内容
            */
            shareCont:{
                tit1:'坑爹啊，原来淘宝买东西，可以上【众划算】拿返利省钱的，怎么没早点发现！',
                tit2:'【众划算】优质商品特价，超便宜，才1折，在这里网购省了好多钱！突然发现还能赚奖励金~早餐能多吃两个土豪茶叶蛋了！已赚奖励'+$('#J_show').data('price')+'元~！ ',
                pic1:v.staticUrl+'buyer/invite/img/share1.png',
                pic2:v.staticUrl+'buyer/invite/img/share2.png',
                url:'http://help.zhonghuasuan.com/buyer/category/153/154/261'
            },
            /*
                复制邀请链接
            */
            copyLink:function(){
                var f = {
                    content: encodeURIComponent($('#J_txt_area').val()),
                        uri: v.staticUrl+'buyer/invite/img/blank.png'
                };
                var p = {
                    wmode: "transparent",
                    allowScriptAccess: "always"
                };
                var swf = v.staticUrl+'buyer/invite/js/clipboard.swf';
                swfobject.embedSWF(swf, 'forLoadSwf', '80', '25', '9.0.0', null, f, p);
            },
            /*
                快捷邀请
            */
            inviteShare:function(obj, title, url, pic){
                var weibo = 'http://service.weibo.com/share/share.php'+'?url='+encodeURIComponent(url)+'&title='+encodeURIComponent(title)+'&pic'+encodeURIComponent(pic);
                var qzone = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey'+'?url='+encodeURIComponent(url)+'&title= &desc='+encodeURIComponent(title)+'&pics='+encodeURIComponent(pic)+'&summary=众划算版权所有';
                var pyou = 'http://connect.qq.com/widget/shareqq/index.html?url='+encodeURIComponent(url)+'&title=快来邀请好友，赚奖励吧！&desc='+encodeURIComponent(title)+'&pics='+encodeURIComponent(pic)+'&summary=众划算版权所有';
                $('.'+obj).find('.weibo').attr('href',weibo);
                $('.'+obj).find('.qzone').attr('href',qzone);
                $('.'+obj).find('.pyou').attr('href',pyou);
            },
            /*
              生成其他邀请链接
            */
            moreLink:function(){
                var str = ''
                    +'<div class="art-invite"><span class="art-tit">页面地址:</span>'
                    +'<input class="u-ipt" id="J_newurl" placeholder="请输入众划算页面地址" />'
                    +'<input type="button" id="J_sub" value="生成邀请链接" class="u-btn">'
                    +'<span class="art-error s-fc-h"></span></div>';

                var self = this;
                Loader.use('artDialog').run(function(){
                    art.dialog({
                        lock:true,
                        drag:false,
                        fixed:false,
                        title:'操作提示',
                        content:str,
                        init:function(){
                            $('#J_newurl').placeholder();
                            var $this = this;
                            $('#J_sub').click(function() {
                                $('.art-error').hide();
                                var reg = new RegExp('^http:\/\/[a-z]+'+ v.reg +'');
                                var url = $('#J_newurl').val();
                                var YL = url.split('.');
                                if (url == '') {
                                    $('.art-error').css('display','block').html('请输入需要生成的链接地址');
                                }else if(reg.test(url) == false){
                                    $('.art-error').css('display','block').html('您输入的地址不属于众划算页面的地址');
                                }else if(url.indexOf('invite')>0){
                                    $('.art-error').css('display','block').html('不能分享此链接');
                                }else{
                                    $.ajax({
                                        url: '/invite/create_link',
                                        type: 'POST',
                                        dataType: 'json',
                                        data:{url:url},
                                        success:function(ret){
                                            if (ret.success) {
                                                var text = self.shareCont.tit1+'我的邀请链接:'+ret.data.new_url;
                                                $('#J_txt_area').val(text);
                                                self.inviteShare('J_shortcut',self.shareCont.tit1,ret.data.new_url,self.shareCont.pic1);
                                                $this.close();
                                                showMessage(null, '生成其他邀请链接，操作成功！', 'S');
                                            }else{
                                                if (ret.data.code == -1 ) {
                                                    $this.content('请认证手机/邮箱后再次尝试');
                                                }else if(ret.data.code == -2){
                                                    $this.content('请输入正确的链接地址！');
                                                }else{
                                                    $this.content('生成邀请链接失败。请稍后再试');
                                                }
                                            }
                                        },
                                        error:function(){
                                            $this.content('生成邀请链接失败。请稍后再试');
                                        }
                                    })
                                }
                            });
                        },
                    });
                })
            },
            /*
                开通邀请好友
            */
            inviteOpen:function(){
                var p = $('.protocol').find('input:checked').val();
                var self = this;
                if (p == 1) {
                    $.ajax({
                        url: '/invite/create_link',
                        type: 'POST',
                        dataType: 'json',
                        error:function(){
                            showMessage(null, '服务器繁忙，请稍后再试', 'W');
                        },
                        success:function(ret){
                            if (ret.success) {
                                $('#J_txt_area').val(self.shareCont.tit1+ret.data.new_url);
                                self.inviteShare('J_shortcut', self.shareCont.tit1, ret.data.new_url, self.shareCont.pic1);
                                $('.J_show_link').show();
                                $('.protocol').hide();

                            }else{
                                if(ret.data.code == -1) {
                                    showMessage(null, '请认证手机/邮箱后再次尝试', 'W', function(){
                                        window.location.href ='/bind/safe';
                                    },'马上验证');
                                }else{
                                    showMessage(null, '获取链接失败，请刷新后重试！', 'E');
                                }
                            }
                        }
                    })
                }else{
                    showMessage(null, '请勾选《邀请好友协议》', 'W');
                }
            }
        }
    }(jQuery, vObj).init();
})
