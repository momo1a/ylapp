var sdbarController = CL.Controller.extend({
    initialize: function(){
        var self    = this;
        // 模型
        self.model  = new sdbarModel();
        // 视图
        self.sdbar  = new sdbarView ({$el   : $("body"), model : self.model});
        // 对话框视图
        self.dialog = new dialogView({$el   : self.sdbar.dom.top.find(".J_W_dialog")});
        // 详情视图
        self.detail = new detailView({$el   : self.sdbar.dom.top.find(".J_W_detail")});
        // 登录视图
        self.login  = new loginView({dialog : self.dialog});
        // 会员视图
        self.member = new memberView({
            model : self.model,
            detail: self.detail
        });
        // 资产视图
        self.asset = new assetView({
            model : self.model,
            detail: self.detail,
            dialog: self.dialog
        });
        // 工具条显示之前，关闭详情面板
        self.sdbar.Event.on("onBeforeShow", function(){
            self.detail.hide(true);
            self.dialog.hide();
        });
        // 详情模块展开的情况下，禁止隐藏
        self.sdbar.Event.on("onBeforeHide", function(){
            // 同时加入是否正在执行动画的判断
            return (!self.detail.is_run && !self.detail.is_show);
        });
        // 通过事件自动给工具菜单添加z-open样式
        self.dialog.Event.on("onAfterShow onAfterFollow", function(){
            self.dialog.is_show && self.sdbar.open_menu(self.dialog.follow());
        });
        // 对话框关闭后，取消工具菜单的z-open样式
        self.dialog.Event.on("onAfterHide", function(){
            self.sdbar.open_menu(null);
        });
        // 详情模块关闭后，取消工具菜单的z-crt样式，detail可以通过事件删除样式，但是却不能加样式，加样式还请在其“工具”的事件上单独添加
        self.detail.Event.on("onAfterHide", function(){
            self.sdbar.crt_menu(null);
        });
        // js模拟gif加载动画
        self.dialog.Event.on("onAfterShow onAfterHide", function(){self.sdbar.load_animate(self.dialog.is_show || self.detail.is_show)});
        self.detail.Event.on("onAfterShow onAfterHide", function(){self.sdbar.load_animate(self.dialog.is_show || self.detail.is_show)});

        // 事件委托监听
        self.sdbar.Event.on("Action", function(action, event){
            return self.Call(action, [event]);
        });
    },
    /**
     * Action方法的调用
     * @param  {String} action 操作名
     * @param  {Array}  args   可选，传递的参数
     * @return {Any}           返回的是对应Action的返回值，如果Action不存在的话，则不进行任何操作，返回undefined
     */
    Call: function(action, args){
        return this.Action[action] ? this.Action[action].apply(this, args||[]) : undefined;
    },
    /**
     * Action集合
     * @type {Object}
     */
    Action: {
        // 根据当前会员类型给当前点击的链接赋予会员中心地址
        myzhs : function(event){
            var user = shs.user.info();
            if(user){
                $(event.currentTarget).attr("href", shs.site(user.type==1 ? "buyer" : "seller"));
            }else{
                // 异常：跳转登录页
                $(event.currentTarget).attr("href", shs.site("login"));
            }
        },
        // 与上同理
        myasset: function(event){
            var user = shs.user.info(),
                url  = url = shs.site("login")
            ;
            if(user){
                if(user.type==1){
                    url = shs.site("buyer") + "cash";
                }else{
                    url = shs.site("seller");
                }
            }
            $(event.currentTarget).attr("href", url);
        },
        /**
         * 登录对话框
         * @param  {Object} event jQuery.event
         * @return {Boolean}      false
         */
        login : function(event){
            if(!shs.user.info()){
                var
                    self   = this,
                    crt    = event.currentTarget,
                    fetch  = self.login.fetch(),
                    dialog = self.dialog
                ;
                if(dialog.is_show && dialog.follow()===crt){
                    dialog.hide();
                }else{
                    if(dialog.content()!==fetch){
                        dialog.content(fetch);
                    }
                    dialog.follow(crt).show();
                }
            }
            return false;
        },
        /**
         * 我的众划算
         * @param  {Object} event jQuery.event
         * @return {Boolean}      false
         */
        member: function(event){
            var
                self = this,
                crt  = event.currentTarget
            ;
            if(!shs.user.info()){
                // 未登录
                return self.Call("login", [event]);
            }
            if(crt === self.sdbar.crt_menu()){
                self.detail.hide();
            }else{
                // 设置菜单current，detail视图不支持像dialog那样使用事件监听自动设置高亮，只能手动了
                self.sdbar.crt_menu(crt);
                self.detail.content(self.member.fetch()).show();
                self.model.get("member") || self.model.load_member();
            }
            return false;
        },
        /**
         * 我的资产
         * @param  {Object} event jQuery.event
         * @return {Boolean}      false
         */
        asset: function(event){
            var
                self = this,
                crt  = event.currentTarget,
                user = shs.user.info()
            ;
            if(!shs.user.info()){
                // 未登录
                return self.Call("login", [event]);
            }
            if(crt === self.sdbar.crt_menu()){
                self.detail.hide();
            }else{
                self.sdbar.crt_menu(crt);
                self.detail.content(self.asset.fetch()).show();
                self.model.get("asset") || self.model.load_asset();
            }
            return false;
        },
        /**
         * 二维码
         * @param  {Object} event jQuery.event
         * @return {Boolean}      false
         */
        qrcode: function(event){
            var
                tpl = '<div class="qrcode-dialog">'
                    +     '<a href="' + shs.site('www') + 'app/" target="_blank" title="android下载" class="android">android下载</a>'
                    +     '<a href="' + shs.site('www') + 'app/" target="_blank" title="ios下载" class="ios">ios下载</a>'
                    + '</div>',
                crt = event.currentTarget,
                dialog = this.dialog;
            ;
            if(dialog.is_show && dialog.follow()===crt){
                dialog.hide();
            }else{
                dialog.content()!==tpl && dialog.content(tpl);
                dialog.follow(crt).show();
            }
            return false;
        },
        /**
         * 返回顶部
         * @param  {Object} event jQuery.event
         * @return {Boolean}      false
         */
        backtotop: function(event){
            $("body,html").animate({scrollTop: 0});
            return false;
        },
        /**
         * 隐藏详情模块
         * @param  {Object} event jQuery.event
         * @return {Boolean}      false
         */
        hide_detail: function(event){
            this.detail.hide();
        }
    }
});
