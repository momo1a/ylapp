var loginView = CL.View.extend({
    tpl : '<iframe class="login-dialog J_W_login" src="' + shs.site("login") + 'iframe" frameborder="0"></iframe>',
    initialize: function(){
        var self = this;
        self.dom = {
            top: $(self.tpl)
        };
        // 当会员登录以后，
        shs.user.Event.bind("login", function(){
            self.dialog.content()===self.dom.top && self.dialog.content("").hide();
            self.dom.top = $(self.tpl);// 销毁重新创建，作用：防缓存
        });
    },
    /**
     * 取到内容
     * @return {jQuery} this.dom.top
     */
    fetch: function(){
        return this.dom.top;
    }
});
