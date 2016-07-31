;var Widgets = function(mod, shs, $){
    "use strict";
    var Widget = {
        render: function(user){
            return '<div class="w-topbar">'
                   +    (function(){
                       if(user){
                          var glitter = user.UNREAD_MSG_NUM > 0 ? ' an-glitter' : '';
                           return '<p class="member">'
                                  +     '<span>您好，</span>'
                                  +     '<a target="_top" class="name" href="' + user.url + '" rel="nofollow">' + user.name + '</a>'
                                  +     '<span class="fn-ml10">您有未读提醒 (<a href="' + user.url + 'message/" target="_top" class="msg'+ glitter +'"><em>' + user.UNREAD_MSG_NUM + '</em></a>) 条</span>'
                                  +     '<a target="_top" class="fn-ml10" href="' + shs.site('login') + 'logout/?to=' + encodeURIComponent(shs.site('login')) + '">退出登录</a>'
                                  + '</p>';
                       }else{
                           return  '<p class="member">'
                                  +     '<span>您好，欢迎来到众划算</span>'
                                  +     '<a target="_top" class="fn-ml10" href="' + shs.site('login') + 'api/event/qq/login" rel="nofollow">'
                                  +         '<i class="iconQQ">&nbsp;</i>QQ登录'
                                  +     '</a>'
                                  +     '<i class="fn-ml10">|</i><a target="_top" class="fn-ml10" href="' + shs.site('login') + '" rel="nofollow">登录</a>'
                                  +     '<i class="fn-ml10">|</i><a target="_top" class="fn-ml10" href="' + shs.site('reg'  ) + '" rel="nofollow">免费注册</a>'
                                  + '</p>';
                       }
                   })()
                   +     '<div class="nav">'
                           // 当前页如果不是www站点的话，显示众划算首页链接
                   +         (shs.site()=='www' ? '' : '<a target="_blank" href="' + shs.site('www' ) + '" class="item s-fc-h">众划算首页</a>')
                   +         (user ? '<a target="_top" href="' + user.url + '" class="item">我的众划算</a>' : '')
                   +         ((!user || user.type!=2) ? '<a href="'+ shs.site('special') +'invite/" class="item"  target="_blank">邀请好友<span class="yuan-bg">奖<b>20</b>元</span></a>' : '')
                   +         '<dl class="item snav j-w-snav">'
                   +             '<dt class="snav-tt">'
                   +                 '<a target="_self" href="javascript:;">下载APP</a>'
                   +                 '<i class="arrow">&nbsp;</i>'
                   +                 '<span><i>&nbsp;</i><b>&nbsp;</b></span>'
                   +             '</dt>'
                   +             '<dd class="snav-bd app">'
                   +     			'<a target="_blank" href="' + shs.site('www') + 'app/" title="android下载" class="android">android下载</a>'
                   +     			'<a target="_blank" href="' + shs.site('www') + 'app/" title="ios下载" class="ios">ios下载</a>'
                   +             '</dd>'
                   +         '</dl>'
                   +         '<a target="_blank" href="' + shs.url.shikee         + '" class="item">试客联盟</a>'
                   +         '<a target="_blank" href="' + shs.url.hulianpay      + '" class="item">互联支付</a>'
                   +         '<a target="_blank" href="' + shs.site('help') + '" class="item">帮助中心</a>'
                   +         '<dl class="item snav j-w-snav">'
                   +             '<dt class="snav-tt">'
                   +                 '<a target="_self" href="javascript:;">网站导航</a>'
                   +                 '<i class="arrow">&nbsp;</i>'
                   +                 '<span><i>&nbsp;</i><b>&nbsp;</b></span>'
                   +             '</dt>'
                   +             '<dd class="snav-bd sitemap">'
                   +                 '<dl class="first">'
                   +                     '<dt>网站热点</dt>'
                   +                     '<dd class="f-cb">'
                   +                         '<a target="_self" href="' + shs.site('list') + 'new/">最新上线</a>'
                   +                         '<a target="_self" href="' + shs.site('www' ) + 'yzcm/">一站成名</a>'
                   +                         '<a target="_self" href="' + shs.site('www' ) + 'mpg/">名品馆</a>'
                   +                         '<a target="_self" href="' + shs.site('list') + '">商品总汇</a>'
                   +                         '<a target="_self" href="' + shs.site('www' ) + 'show/" rel="nofollow">买家晒单</a>'
                   +                     '</dd>'
                   +                 '</dl>'
                   +                 '<dl>'
                   +                     '<dt>社区</dt>'
                   +                     '<dd class="f-cb"><a target="_blank" href="' + shs.url.bbs + '" rel="nofollow">社区</a></dd>'
                   +                 '</dl>'
                   +                 '<dl>'
                   +                     '<dt>网站服务</dt>'
                   +                     '<dd class="f-cb">'
                   +                         '<a target="_blank" href="' + shs.site('www' ) + 'guide" rel="nofollow">新手引导</a>'
                   +                         '<a target="_blank" href="' + shs.site('help') + '" rel="nofollow">帮助中心</a>'
                   +                         '<a target="_blank" href="' + shs.site('e'   ) + '">招商专区</a>'
                   +                         '<a target="_blank" href="' + shs.site('www' ) + 'about/" rel="nofollow">关于我们</a>'
                   +                     '</dd>'
                   +                 '</dl>'
                   +                 '<dl>'
                   +                     '<dt>合作网站</dt>'
                   +                     '<dd class="f-cb"><a target="_blank" href="' + shs.url.shikee + '">试客联盟</a></dd>'
                   +                 '</dl>'
                   +             '</dd>'
                   +         '</dl>'
                   +     '</div>'
                   + '</div>';
        },
        /**
         * 加载会员信息（uid、消息数量）
         */
        load_info  : function(){
            // 加true参数防止shs.user.Event事件中调用本方法而形成无穷回调
            var user = shs.user.info(true);
            if(user){
                Widget.$el.html(Widget.render({
                    name: user.name,
                    type: user.type,
                    url : shs.site(user.type==1 ? "buyer" : "seller"),
                    uid : null,
                    UNREAD_MSG_NUM: 0
                }));
                $.getJSON(shs.site('www')+'api/message?callback=?', function(response) {
                    if (response.success && shs.user.info(true)) {
                        // 对网络延迟的不信任，重新再判断一次用户登录情况
                        Widget.$el.html(Widget.render({
                            name: user.name,
                            type: user.type,
                            url : shs.site(user.type==1 ? "buyer" : "seller"),
                            uid : response.data.uid,
                            UNREAD_MSG_NUM: response.data.UNREAD_MSG_NUM
                        }));
                    }
                });
            }else{
                Widget.$el.html(Widget.render());
            }
        }
    };
    var init = function(){
        Widget.$el = $('#J_topbar');
        /* 子导航效果 */
        Widget.$el.on("mouseenter", ".j-w-snav", function(){$(this).addClass('z-snav-hover')});
        Widget.$el.on("mouseleave", ".j-w-snav", function(){$(this).removeClass('z-snav-hover')});
        shs.user.Event.bind("change", Widget.load_info);
        Widget.load_info();
        return Widget;
    };


    mod.topbar = init();
    return mod;
}(window.Widgets||{}, shs, jQuery);
