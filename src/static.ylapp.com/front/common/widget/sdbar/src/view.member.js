var memberView = CL.View.extend({
    tpl : '<div class="detail-hd">'
        +     '<span class="close J_W_click" data-action="hide_detail" title="关闭">&gt;&gt;</span>'
        +     '<h4>'
        +         '<a href="#" target="_blank" class="J_W_click" data-action="myYL"><i class="ifont">&#xE006;</i>我的众划算</a>'
        +     '</h4>'
        + '</div>'
        + '<div class="detail-bd">'
        +     '<% if(!user || !data){ %>'
        +         '<span class="loading"><i class="J_W_loading">&nbsp;</i></span>'
        +     '<% }else if(user.type==1){ %>'
        +         '<% // 买家 %>'
        +         '<div class="minfo">'
        +             '<a href="#" target="_blank" class="J_W_click" data-action="myYL">'
        +                 '<img src="http://uc.shikee.com/avatar.php?uid=<%= user.uid %>&size=middle" alt="<%= user.name %>">'
        +             '<a>'
        +             '<p>您好，<a href="#" target="_blank" class="J_W_click" data-action="myYL"><%= user.name %></a></p>'
        +         '</div>'
        +         '<ul class="mnote fn-cb">'
        +             '<li>'
        +                 '<a href="<%= user.url %>order" target="_blank" class="icon icon-mydingdan">&nbsp;</a>'
        +                 '<a href="<%= user.url %>order" target="_blank">我的订单</a>'
        +             '</li>'
        +             '<li>'
        +                 '<a href="<%= user.url %>message" target="_blank" class="icon icon-mymessage J_W_msg_num">'
        +                     '<% if(+user.UNREAD_MSG_NUM>0){ %>'
        +                         '<em class="an-warn"><%= user.UNREAD_MSG_NUM %></em>'
        +                     '<% }else{ %>&nbsp;<% } %>'
        +                 '</a>'
        +                 '<a href="<%= user.url %>message" target="_blank">我的消息</a>'
        +             '</li>'
        +             '<li>'
        +                 '<a href="<%= user.url %>order/?s=1" target="_blank" class="icon icon-waitfill">'
        +                     '<% if(+data.WAIT_FILL_NUM>0){ %>'
        +                         '<em><%= data.WAIT_FILL_NUM %></em>'
        +                     '<% }else{ %>&nbsp;<% } %>'
        +                 '</a>'
        +                 '<a href="<%= user.url %>order/?s=1" target="_blank">待填写订单号</a>'
        +             '</li>'
        +             '<li>'
        +                 '<a href="<%= user.url %>order/?s=4" target="_blank" class="icon icon-waitrebate">'
        +                     '<% if(+data.WAIT_REBATE_NUM>0){ %>'
        +                         '<em><%= data.WAIT_REBATE_NUM %></em>'
        +                     '<% }else{ %>&nbsp;<% } %>'
        +                 '</a>'
        +                 '<a href="<%= user.url %>order/?s=4" target="_blank">待返现</a>'
        +             '</li>'
        +             '<li>'
        +                 '<a href="<%= user.url %>order/?s=5" target="_blank" class="icon icon-checkfailure">'
        +                     '<% if(+data.CHECK_FAILURE_NUM>0){ %>'
        +                         '<em><%= data.CHECK_FAILURE_NUM %></em>'
        +                     '<% }else{ %>&nbsp;<% } %>'
        +                 '</a>'
        +                 '<a href="<%= user.url %>order/?s=5" target="_blank">订单号有误</a>'
        +             '</li>'
        +             '<li>'
        +                 '<a href="<%= user.url %>order/?s=6" target="_blank" class="icon icon-appeal">'
        +                     '<% if(+data.APPEAL_NUM>0){ %>'
        +                         '<em><%= data.APPEAL_NUM %></em>'
        +                     '<% }else{ %>&nbsp;<% } %>'
        +                 '</a>'
        +                 '<a href="<%= user.url %>order/?s=6" target="_blank">申诉中</a>'
        +             '</li>'
        +         '</ul>'
        +     '<% }else{ %>'
        +         '<% // 商家 %>'
        +         '<div class="minfo">'
        +             '<a href="#" target="_blank" class="J_W_click" data-action="myYL">'
        +                 '<img src="http://uc.shikee.com/avatar.php?uid=<%= user.uid %>&size=middle" alt="<%= user.name %>">'
        +             '<a>'
        +             '<p>您好，<a href="#" target="_blank" class="J_W_click" data-action="myYL"><%= user.name %></a></p>'
        +         '</div>'
        +         '<ul class="mnote fn-cb">'
        +             '<li>'
        +                 '<a href="<%= user.url %>goods/goods_list" target="_blank" class="icon icon-myhuodong">&nbsp;</a>'
        +                 '<a href="<%= user.url %>goods/goods_list" target="_blank">我的活动</a>'
        +             '</li>'
        +             '<li>'
        +                 '<a href="<%= user.url %>message" target="_blank" class="icon icon-mymessage J_W_msg_num">'
        +                     '<% if(+user.UNREAD_MSG_NUM>0){ %>'
        +                         '<em class="an-warn"><%= user.UNREAD_MSG_NUM %></em>'
        +                     '<% }else{ %>&nbsp;<% } %>'
        +                 '</a>'
        +                 '<a href="<%= user.url %>message" target="_blank">我的消息</a>'
        +             '</li>'
        +             '<li>'
        +                 '<a href="<%= user.url %>goods/goods_list?state=3" target="_blank" class="icon icon-uncheck">'
        +                     '<% if(+data.UNCHECK_NUM>0){ %>'
        +                         '<em><%= data.UNCHECK_NUM %></em>'
        +                     '<% }else{ %>&nbsp;<% } %>'
        +                 '</a>'
        +                 '<a href="<%= user.url %>goods/goods_list?state=3" target="_blank">待审核的活动</a>'
        +             '</li>'
        +             '<li>'
        +                 '<a href="<%= user.url %>goods/goods_list?state=20" target="_blank" class="icon icon-online">'
        +                     '<% if(+data.ONLINE_NUM>0){ %>'
        +                         '<em><%= data.ONLINE_NUM %></em>'
        +                     '<% }else{ %>&nbsp;<% } %>'
        +                 '</a>'
        +                 '<a href="<%= user.url %>goods/goods_list?state=20" target="_blank">进行中的活动</a>'
        +             '</li>'
        +             '<li>'
        +                 '<a href="<%= user.url %>appeal/index/1" target="_blank" class="icon icon-needappeal">'
        +                     '<% if(+data.NEED_APPEAL_NUM>0){ %>'
        +                         '<em><%= data.NEED_APPEAL_NUM %></em>'
        +                     '<% }else{ %>&nbsp;<% } %>'
        +                 '</a>'
        +                 '<a href="<%= user.url %>appeal/index/1" target="_blank">收到的申诉</a>'
        +             '</li>'
        +         '</ul>'
        +     '<% } %>'
        + '</div>',
    initialize: function(){
        var self = this;
        self.dom = {
            top: $('<div class="member-detail"></div>')
        };
        self.tpl = template.compile(self.tpl);
        self.render();
        // 监听模板数据变化
        self.model.Event.on("change:member change:info", function(){
            self.render();
        });
        // 会员变更
        shs.user.Event.bind("change", function(user){
            self.model.reset("member");
            self.detail.content()===self.dom.top && self.detail.hide();
        });
    },
    /**
     * 取到内容
     * @return {jQuery} this.dom.top
     */
    fetch: function(){
        // 修复IE浏览器下节点被删除后丢失内容的问题
        this.dom.top.html() || this.render();
        return this.dom.top;
    },
    render: function(){
        this.dom.top.html(this.tpl({
            user: this.model.get("info"),
            data: this.model.get("member")
        }));
    }
});
