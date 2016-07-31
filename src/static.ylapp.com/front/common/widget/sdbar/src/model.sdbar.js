var sdbarModel = CL.Model.extend({
    defaults:{
        // 会员中心数据
        member: null,
        // 会员信息(与shs.user.info不同，此处存储的是会员的uid、name、消息数量)
        info: null,
        // 资产数据（商家：余额，买家：余额+现金券）
        asset: null
    },
    initialize:function(){
        var self = this;
        shs.user.Event.bind("change", function(){
            self.load_info();
        });
        self.load_info();
    },
    /**
     * 加载会员信息（uid、消息数量）
     * 注：此方法会自动运行，无需手动去执行
     */
    load_info  : function(){
        var
            self = this,
            // 加true参数防止shs.user.Event事件中调用本方法而形成无穷回调
            user = shs.user.info(true)
        ;
        self.reset("info"); // 恢复默认值
        if(user){
            $.getJSON(shs.site('www')+'api/message?callback=?', function(response) {
                if (response.success && shs.user.info(true)) {
                    // 对网络延迟的不信任，重新再判断一次用户登录情况
                    self.set("info", {
                        name: user.name,
                        type: user.type,
                        url : shs.site(user.type==1 ? "buyer" : "seller"),
                        uid : response.data.uid,
                        UNREAD_MSG_NUM: response.data.UNREAD_MSG_NUM
                    });
                }
            });
        }
    },
    /**
     * 加载会员订单、活动信息
     * 注：此方法需要手动执行
     */
    load_member: function(){
        var
            self = this,
            request,
            ucenter,
            // 加true参数防止shs.user.Event事件中调用本方法而形成无穷回调
            user = shs.user.info(true)
        ;
        self.reset("member");
        if(user){
            if(user.type==1){
                request = shs.site('www')+'api/user_order_info?callback=?';
                ucenter = shs.site("buyer");
            }else{
                request = shs.site('www')+'api/seller_info?callback=?';
                ucenter = shs.site("seller");
            }
            $.getJSON(request, function(response) {
                if ( response.success ){
                    self.set("member", response.data);
                }
            });
        }
    },
    /**
     * 加载会员资产信息
     * 注：此方法需要手动执行
     */
    load_asset: function(){
        var
            self = this,
            user = shs.user.info(true)
        ;
        self.reset("asset");
        if(user){
            $.getJSON(shs.site('www')+'api/asset?callback=?', function(response){
                if ( response.success ){
                    self.set("asset", response.data);
                }
            });
        }
    }
});
