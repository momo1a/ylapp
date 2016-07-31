var assetView = CL.View.extend({
    tpl : '<div class="detail-hd">'
        +     '<span class="close J_W_click" data-action="hide_detail" title="关闭">&gt;&gt;</span>'
        +     '<h4><a href="#" target="_blank" class="J_W_click" data-action="myasset"><i class="ifont">&#xE007;</i>我的资产</a></h4>'
        + '</div>'
        + '<div class="detail-bd J_W_detail_bd">'
        +     '<% if(!user || !data){ %>'
        +         '<span class="loading"><i class="J_W_loading">&nbsp;</i></span>'
        +     '<% }else{ %>'
        +         '<% if(user.type==1){ %>'
        +             '<% // 买家 %>'
        +             '<div class="balances J_W_balances">'
        +                 '<p class="f-dn">可用余额：<strong><%= data.balances %></strong><span>(元)</span><a href="#" target="_self" class="ifont" title="隐藏余额">&#xE008;</a></p>'
        +                 '<p class="mask">可用余额：<span>&nbsp;</span><a href="#" target="_self" class="ifont" title="显示余额">&#xE009;</a></p>'
        +             '</div>'
        +             '<div class="cash">'
        +                 '<div class="hd"><h5 class="tt">我的现金券</h5></div>'
        +                 '<div class="bd">'
        +                     '<% if(!data.cash || data.cash.length==0) { %>'
        +                         '<div class="none">'
        +                             '<i>&nbsp;</i>'
        +                             '<p>亲，您还没有领取任何现金券哦！</p>'
        +                         '</div>'
        +                     '<% }else{ %>'
        +                         '<ul>'
        +                             '<% for(var i=0;i<data.cash.length;i++){ %>'
        +                                 '<li class="pattern pattern-<%= data.cash[i].cprice %>">'
        +                                     '<div class="mz"><span>￥</span><strong><%= data.cash[i].cprice %></strong></div>'
        +                                     '<div class="note">'
        +                                         '<h6><a href="#" target="_blank" class="J_W_click" data-action="myasset"><%= data.cash[i].ctitle %></a></h6>'
        +                                         '<dl class="f-cb">'
        +                                             '<dt>有效日期</dt>'
        +                                             '<dd>'
        +                                                 '<p><%= data.cash[i].valid_start_time %></p>'
        +                                                 '<p><%= data.cash[i].valid_end_time %></p>'
        +                                             '</dd>'
        +                                         '</dl>'
        +                                     '</div>'
        +                                 '</li>'
        +                             '<% } %>'
        +                         '</ul>'
        +                         '<% if(data.is_more){ %>'
        +                             '<a href="#" class="more J_W_click" target="_blank" data-action="myasset">查看全部 &gt;</a>'
        +                         '<% } %>'
        +                     '<% } %>'
        +                 '</div>'
        +             '</div>'
        +         '<% }else{ %>'
        +             '<% // 商家 %>'
        +             '<i class="balances-icon">&nbsp;</i>'
        +             '<div class="balances J_W_balances">'
        +                 '<p class="f-dn">可用余额：<strong><%= data.balances %></strong><span>(元)</span><a href="#" target="_self" class="ifont" title="隐藏余额">&#xE008;</a></p>'
        +                 '<p class="mask">可用余额：<span>&nbsp;</span><a href="#" target="_self" class="ifont" title="显示余额">&#xE009;</a></p>'
        +             '</div>'
        +         '<% } %>'
        +     '<% } %>'
        + '</div>',
    initialize: function(){
        var self = this;
        self.dom = {
            top : $('<div class="asset-detail"></div>')
        };
        self.tpl = template.compile(self.tpl);
        // 监听模板数据变化
        self.model.Event.on("change:asset", function(){
            self.render();
        });
        self.render();
        // 会员变更
        shs.user.Event.bind("change", function(user){
            self.model.reset("asset");
            self.detail.content()===self.dom.top  && self.detail.hide();
        });
    },
    /**
     * 渲染
     */
    render: function(){
        this.dom.top.html(this.tpl({
            user: shs.user.info(),
            data: this.model.get("asset")
        }));
        // 点击显示余额
        var balances = this.dom.top.find(".J_W_balances p");
        if(balances.find("a").size()){
            balances.find("a").each(function(){
                // 使用原生的方法绑定事件，可避免dom被删除后再恢复导致事件丢失的问题
                this.onclick = function(){
                    balances.toggle();
                    return false;
                };
            });
        }
    },
    /**
     * 获取视图内容节点
     * @return {jQuery}
     */
    fetch: function(){
        // 修复IE浏览器下节点被删除后丢失内容的问题
        this.dom.top.html() || this.render();
        return this.dom.top;
    }
});
