/**
 * 侧边用户条挂件
 * Author: 陆楚良
 *  Email: lu_chuliang@sina.com
 *     QQ: 874449204
 *  Build: 2015-12-16 19:01:06
 *
 *    依赖:
 *         reset.css
 *         function.css
 *         jQuery.js
 *         shs.js
 *         CL.js
 *         CL_Loader.js
 *         template-native.js   (不存在时自加载依赖)
 *         json2.js             (低版本浏览器下自加载依赖)
 *         jquery.inView.js     (页面存在楼层但是不存在该插件时自加载依赖)
 *         style.css            (当前挂件)
 */
var Widgets = Widgets || {};
!function(window, Widgets, undefined){
    var $      = window.jQuery;
    var shs    = window.shs;
    var CL     = window.CL;
    var Loader = window.CL_Loader;
    CL.$ = $;
    // 低版本浏览器直接忽略，iPhone和iPad会锁住界面导致链接无法正常使用，因此也忽略
    if($("html").hasClass("ie6") || /iphone|ipad/i.test(navigator.userAgent)){
        return;
    }
    // <include: model.sdbar.js> line: 31-120
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
    // <include: view.sdbar.js> line: 121-422
    var sdbarView = CL.View.extend({
        /**
         * 模板内容
         * @type {String}
         */
        tpl : '<div class="w-sdbar" style="width:0;overflow:hidden;">'
            +     '<div class="tool">'
            +         '<div class="menu J_W_menu">'
            +             '<a href="#" class="z-first J_W_member J_W_click" data-action="member">'
            +                 '<span class="ifont">&#xE001;</span>'
            +                 '<img class="member-face" src="about:blank"/>'
            +                 '<span class="tip">我的众划算<i>&nbsp;</i></span>'
            +             '</a>'
            +             '<a href="#" class="J_W_click" data-action="asset">'
            +                 '<span class="ifont">&#xE002;</span>'
            +                 '<span class="tip">我的资产<i>&nbsp;</i></span>'
            +             '</a>'
            +             '<a href="' + shs.site("www") + 'guide" target="_blank">'
            +                 '<span class="ifont">&#xE003;</span>'
            +                 '<span class="tip">新手引导<i>&nbsp;</i></span>'
            +             '</a>'
            +             '<a href="#" class="J_W_click" data-action="qrcode">'
            +                 '<span class="ifont">&#xE004;</span>'
            +                 '<span class="tip">二维码<i>&nbsp;</i></span>'
            +             '</a>'
            +             '<a href="' + shs.site("help") + 'feedback" target="_blank">'
            +                 '<span class="ifont">&#xE005;</span>'
            +                 '<span class="tip">建议反馈<i>&nbsp;</i></span>'
            +             '</a>'
            +             '<a href="#" class="btt J_W_click" data-action="backtotop">'
            +                 '<i class="arrow">&nbsp;</i>'
            +                 '<span>TOP</span>'
            +                 '<span class="tip">返回顶部<i>&nbsp;</i></span>'
            +             '</a>'
            +         '</div>'
            +         '<ol class="floor J_W_floor">'
            // +             '<li><span class="out">女装</span><span class="in">1F</span></li>'
            +         '</ol>'
            +     '</div>'
            +     '<cite class="J_W_dialog">&nbsp;</cite>'
            +     '<cite class="J_W_detail">&nbsp;</cite>'
            + '</div>',
        // 窗口对象
        $w  : $(window),
        $d  : $(document),
        /**
         * 初始化方法
         */
        initialize: function(){
            var self    = this;
            // 创建事件对象
            this.Event = CL.Event(this);
            // 节点存储
            self.dom = {};
            self.dom.top = $(self.Template(self.tpl, self.model.toJSON()));
            self.dom.menu= self.dom.top.find(".J_W_menu a");
            self.dom.btt = self.dom.menu.filter(".btt");
            self.dom.floor=self.dom.top.find(".J_W_floor");
            self.$el.append(self.dom.top);
    
            // 楼层高亮
            var $f  = $(".J_floor");
            var $bh = $("body,html");
            if($f.size()>0){
                // 按需加载jquery.inView.js
                var requires = $.fn.inView ? [] : [shs.static("common/js/jquery/jquery.inView.js")];
                Loader.add(requires).run(function(){
                    $f.each(function(i){
                        var $this = $(this);
                        var Out = $this.data("out") || (i+1)+'F';
                        var In  = $this.data("in")  || (i+1)+'F';
                        self.dom.floor.append('<li><span class="out">' + Out + '</span><span class="in">' + In + '</span></li>');
                    });
                    var $lis = self.dom.floor.find("li");
                    $lis.click(function(){
                        var offset = $f.eq($(this).index()).offset();
                        offset && $bh.animate({scrollTop:offset.top});
                    })
                    $f.inView(function(i, s){
                        $lis.eq(i).removeClass("z-crt z-inview");
                        if(s!=0){
                            // 0未显示，小于0为已显示但是不是主显，大于0则为主显示，详情查阅jquery.inView.js
                            if(s<0){
                                // 显示了但是不是主显
                                $lis.eq(i).addClass("z-inview");
                            }else{
                                // 显示了，是主显
                                $lis.eq(i).addClass("z-crt");
                            }
                        }
                    });
                });
            }else{
                self.dom.floor.hide();
            }
            // 垂直居中
            var th = self.dom.menu.size()*36+1+($f.size()>0 ? 80+$f.size()*35 : 0);
            self.$w.resize(function(){
                var mt = (self.$w.height()-th)/2;
                var fmt= 80;
                if(mt<0){
                    fmt = fmt+mt*2>0 ? fmt+mt*2 : 0;
                    mt  = 0;
                }
                self.dom.top.find(".J_W_menu").css("margin-top", mt);
                self.dom.floor.css("margin-top", fmt);
            }).resize();
    
    
            // 事件委托
            $.each("blur change click dblclick focus focusin focusout keydown keypress keyup load mousedown mouseenter mouseleave mousemove mouseout mouseover mouseup scroll select submit".split(" "), function(k,v){
                self.dom.top.on(v, ".J_W_"+v, function(event){
                    return self.Event.trigger("Action", [$(this).data("action"), event]);
                });
            });
    
            // 工具菜单中，会员登录以后显示对应会员的头像
            var mem = self.dom.menu.filter(".J_W_member");
            self.model.Event.on("change:info", function(info){
                if(info){
                    mem.find("img").attr("src", "http://uc.shikee.com/avatar.php?uid="+info.uid+"&size=middle")
                    .show()[info.UNREAD_MSG_NUM!=0 ? "addClass":"removeClass"]("an-warn");
                    mem.find("span:eq(0)").hide();
                }else{
                    mem.find("img").attr("src", "about:blank").hide();
                    mem.find("span:eq(0)").show();
                }
            });
    
            // 自动显隐
            var
                // 定时器id
                timer,
                $w   = self.$w,
                // 用于锁住自动显隐功能
                lock = false,
                // 存入变量，防止频繁计算创建过多的闭包
                show = function(){self.show()},
                auto = function(event){
                    clearTimeout(timer);
                    if(!lock){
                        var w = $w.width();
                        if(w>=self.doc_width()+70){
                            show();
                        }
                        else if(!self.is_show && event && event.clientX && event.clientX>=w-35){
                            timer = setTimeout(show, 400);
                        }else{
                            self.hide();
                        }
                    }
                }
            ;
            // 鼠标进入时锁定，阻止自动显隐功能
            self.dom.top.on("mouseenter mouseleave", function(event){
                if(event.type == "mouseenter"){
                    lock=true;
                    self.show();
                }else{
                    lock = false;
                    auto();
                }
            });
            // 监听窗口、鼠标变化
            $w.on("resize mousemove", auto);
            auto();
    
            // 返回顶部按钮自动显隐
            $w.scroll(function(){
                if(self.$d.scrollTop()>=$w.height()/2){
                    self.dom.btt.css("visibility", "visible");
                }else{
                    self.dom.btt.css("visibility", "hidden");
                }
            });
        },
        /**
         * 是否正在显示（注：此参数由show、hide进行维护，建议仅用于是否已显示判断，切勿修改）
         * @type {Boolean}
         */
        is_show : false,
        /**
         * 显示方法
         * @return {View}   this
         */
        show: function(){
            if(!this.is_show && this.Event.trigger("onBeforeShow")){
                var
                    self = this,
                    dom  = self.dom.top.stop(),
                    ow   = dom.css("width"),
                    nw   = dom.show().css({width:"", overflow: ""}).css("width")
                ;
                self.is_show = true;
                dom.css({width:ow, overflow: "hidden"}).show().animate({width:nw}, 400, function(){
                    dom.css({width:"", overflow: ""});
                    self.Event.trigger("onAfterShow");
                });
            }
            return this;
        },
        /**
         * 隐藏方法
         * @return {View}   this
         */
        hide: function(){
            if(this.is_show && this.Event.trigger("onBeforeHide")){
                var
                    self  = this,
                    dom   = self.dom.top.stop(),
                    nw    = dom.css("width")
                ;
                self.is_show = false;
                dom.css({width:nw, overflow: "hidden"}).animate({width:0}, 400, function(){
                    dom.hide();
                    self.Event.trigger("onAfterHide");
                });
            }
            return this;
        },
        /**
         * 获取当前页面宽度，对于响应式页面，需要配合页面的meta值，用于自动显隐判断，如果不设置，缺省值均为1000px
         * @return {Number}
         */
        _response: false,
        doc_width: function(){
            if(!this._response){
                // 从meta中查找设置的页面宽度或响应式宽度类名，没有则默认选用1000
                var dwr       = $.type(window['D-Width-Response'])=='string' ?  window['D-Width-Response'] : $('head meta[name=D-Width-Response]').attr('content') || '';
                var width     = dwr.match(/^.*width=(\d+[a-z%]*).*$/i);
                var className = dwr.match(/^.*class=([_\-0-9a-z]+).*$/i);
                if(width){
                    // 如果页面设置了宽度
                    this._response = $('<div></div>').css('width', width[1]).hide().appendTo('body');
                }else if(className){
                    // 如果页面设置了响应式宽度样式
                    this._response = $('<div></div>').addClass(className[1]).hide().appendTo('body');
                }else{
                    // 用户没有设置，使用缺省的1000作为宽度值
                    this._response = 1000;
                }
            }
            return $.type(this._response)=="number" ? this._response : this._response.width();
        },
        /**
         * 当前菜单
         * @param  {Element|jQuery} ele 可省，菜单中的a节点对象，可以是原生，也可以是jQuery选择器，使用null可取消菜单高亮，缺省时为获取当前高亮菜单的element对象
         * @return {View|Element}   this
         */
        crt_menu: function(ele){
            if(ele){
                this.dom.menu.removeClass("z-crt").filter(ele).addClass("z-crt");
                return this;
            }else if(ele===null){
                this.dom.menu.removeClass("z-crt");
                return this;
            }else{
                return this.dom.menu.filter(".z-crt").get(0);
            }
        },
        /**
         * 与crt_menu同理，本方法针对的是dialog
         * @param  {Element|jQuery} ele 可省，菜单中的a节点对象，可以是原生，也可以是jQuery选择器，使用null可取消菜单高亮，缺省时为获取当前高亮菜单的element对象
         * @return {View|Element}   this
         */
        open_menu: function(ele){
            if(ele){
                this.dom.menu.removeClass("z-open").filter(ele).addClass("z-open");
                return this;
            }else if(ele===null){
                this.dom.menu.removeClass("z-open");
                return this;
            }else{
                return this.dom.menu.filter(".z-open").get(0);
            }
        },
        /**
         * 动画帧与时钟，run_animate维护，请勿修改
         */
        _run_animate_f: 1,
        _run_animate_timer: 0,
        /**
         * 加载动画（效果：奔跑的众娃）
         * @return {View} this
         */
        load_animate: function(is_show){
            var self = this;
            if(is_show){
                if(!self._run_animate_timer){
                    self._run_animate_timer = setInterval(function(){
                        self.dom.top.find(".J_W_loading")[self._run_animate_f?"addClass":"removeClass"]("z-run");
                        self._run_animate_f = 1-self._run_animate_f;
                    }, 120);
                }
            }else{
                clearInterval(self._run_animate_timer);
                self._run_animate_timer = 0;
            }
            return this;
        }
    });
    // <include: view.dialog.js> line: 423-563
    var dialogView = CL.View.extend({
        /**
         * 模板内容
         * @type {String}
         */
        tpl : '<div class="dialog">'
            +     '<span class="dialog-arrow J_W_arrow">&nbsp;</span>'
            +     '<div class="dialog-doc J_W_doc">&nbsp;111111111111111111111111222222233334444555666&nbsp;&nbsp;&nbsp;&nbsp;</div>'
            + '</div>',
        $w  : $(window),
        /**
         * 初始化方法
         */
        initialize: function(){
            var self = this;
            // 创建事件对象
            self.Event = CL.Event(self);
            // 节点存储
            self.dom = {};
            self.dom.top   = $(self.tpl);
            self.dom.arrow = self.dom.top.find(".J_W_arrow");
            self.dom.doc   = self.dom.top.find(".J_W_doc");
            self.$el.replaceWith(self.dom.top);
            self.dom.top.mouseleave(function(){self.hide()});
            self.$w.resize(function(){
                self.set_pos();
            });
        },
        /**
         * 是否正在显示（注：此参数由show、hide进行维护，建议仅用于是否已显示判断，切勿修改）
         * @type {Boolean}
         */
        is_show: false,
        /**
         * 显示方法
         * @return {View}   this
         */
        show: function(){
            var self = this;
            if(!self.is_show && self.Event.trigger("onBeforeShow")){
                self.is_show = true;
                self.dom.top.show();
                self.Event.trigger("onAfterShow");
            }
            return self;
        },
        /**
         * 隐藏方法
         * @return {View}   this
         */
        hide: function(){
            var self = this;
            if(self.is_show && self.Event.trigger("onBeforeHide")){
                self.is_show = false;
                self.dom.top.hide();
                self.Event.trigger("onAfterHide");
            }
            return self;
        },
        /**
         * 用于存储当前的内容，content方法进行维护，切勿修改
         * @type {String}
         */
        _content: "",
        /**
         * 设置对话框内容
         * @param  {String|Element|jQuery}  content  可省，可以是字符串，也可以是原生element，还可以是jq选择器选中的对象，缺省状态下为获取当前的内容
         * @return {View}                   this
         */
        content: function(content){
            var
                self = this,
                ocontent = self._content
            ;
            if(content===undefined){
                return ocontent;
            }else{
                var ocontent = self._content;
                if(self.Event.trigger("onBeforeContent", [content, ocontent])){
                    self._content = content;
                    self.dom.doc.html(content);
                    self.Event.trigger("onAfterContent", [content, ocontent]);
                }
            }
            return self;
        },
        /**
         * 用于存储当前跟随的对象，follow方法进行维护，切勿修改
         * @type {String}
         */
        _follow: null,
        /**
         * 设置跟随对象
         * @param  {Element|jQuery} ele     可省，跟随的对象，可以是原生element也可以是jq选择器，当缺省时候返回当前的跟随对象
         * @return {View}           this
         */
        follow: function(ele){
            var
                self    = this,
                follow = self._follow
            ;
            if(ele===undefined){
                return follow;
            }else{
                if(self.Event.trigger("onBeforeFollow", [ele, follow])){
                    self._follow = ele;
                    self.set_pos();
                    self.Event.trigger("onAfterFollow", [ele, follow]);
                }
            }
            return self;
        },
        /**
         * 设置对话框的位置
         * @return {View} this
         */
        set_pos: function(){
            var self = this;
            if(self._follow){
                var
                    ft = $(self._follow).position().top,
                    th = self.dom.top.height(),
                    wh = self.$w.height(),
                    at = 10,
                    top= ft
                ;
                if(th+ft>wh){
                    top = wh-th;
                    at  = 10+ft-top
                }
                if(top<0){
                    top = 0;
                    at  = 10+ft;
                }
                self.dom.top.css("top", top);
                self.dom.arrow.css("top", at);
            }
            return self;
        }
    });
    // <include: view.detail.js> line: 564-710
    var detailView = CL.View.extend({
        /**
         * 模板内容
         * @type {String}
         */
        tpl : '<div class="detail" style="width:0;">'
            +     '<div class="detail-doc J_W_doc">&nbsp;</div>'
            +     '<div class="detail-doc f-fdn J_W_doc">&nbsp;</div>'
            + '</div>',
        /**
         * 初始化方法
         */
        initialize: function(){
            var self = this;
            // 创建事件对象
            self.Event = CL.Event(self);
            // 节点存储对象
            self.dom = {};
            self.dom.top = $(self.tpl);
            self.dom.doc = self.dom.top.find(".J_W_doc");
            self.$el.replaceWith(self.dom.top);
        },
        /**
         * 是否正在显示（注：此参数由show、hide进行维护，建议仅用于是否已显示判断，切勿修改）
         * @type {Boolean}
         */
        is_show: false,
        /**
         * 是否正在执行显隐动画，本视图不做任何判断限制，仅用于提供外部查询（建议仅用于是否已显示判断，切勿修改）
         * @type {Boolean}
         */
        is_run : false,
        /**
         * 显示方法
         * @param  {Boolean}    可选，当为参数true时候，没有任何过渡动画效果直接完成
         * @return {View}       this
         */
        show: function(instant){
            var
                self = this,
                $top  = self.dom.top
            ;
            if(!self.is_show && self.Event.trigger("onBeforeShow")){
                self.is_show = true;
                if(instant){
                    $top.stop().css("width", "").show();
                    self.Event.trigger("onAfterShow");
                }else{
                    var ow = $top.stop().css("width");
                    var nw = $top.css("width", "").width();
                    self.is_run = true;
                    $top.css("width", ow).show().animate({width: nw}, 400, function(){
                        self.is_run = false;
                        $top.css("width", "");
                        self.Event.trigger("onAfterShow");
                    });
                }
            }
            return self;
        },
        /**
         * 隐藏方法
         * @param  {Boolean}    可选，当为参数true时候，没有任何过渡动画效果直接完成
         * @return {View}       this
         */
        hide: function(instant){
            var
                self = this,
                $top  = self.dom.top
            ;
            if(self.is_show && self.Event.trigger("onBeforeHide")){
                self.is_show = false;
                if(instant){
                    $top.stop().css("width", 0).hide();
                    self.Event.trigger("onAfterHide");
                }else{
                    self.is_run = true;
                    $top.stop().animate({width: 0}, 400, function(){
                        self.is_run = false;
                        $top.hide();
                        self.Event.trigger("onAfterHide");
                    });
                }
            }
            return self;
        },
        /**
         * 用于存储当前详情模块的内容，content方法进行维护，切勿修改
         * @type {String}
         */
        _content: "",
        /**
         * 设置详情内容
         * @param  {String|Element|jQuery}  content  可省，可以是字符串，也可以是原生element，还可以是jq选择器选中的对象，缺省状态下为获取当前的内容
         * @return {View}                   this
         */
        content: function(content){
            var self = this;
            if(content===undefined){
                // 当不传递参数时候，返回其内容
                return self._content;
            }else{
                // 设置内容
                if(self.Event.trigger("onBeforeContent", [content, ocontent])){
                    var
                        ocontent = self._content,
                        $doc     = self.dom.doc.stop(),
                        $top     = self.dom.top,
                        $doc_0,
                        $doc_1
                    ;
                    self._content = content;
                    if($doc.eq(0).index()==0){
                        $doc_0 =$doc.eq(0);
                        $doc_1 =$doc.eq(1);
                    }else{
                        $doc_0 =$doc.eq(1);
                        $doc_1 =$doc.eq(0);
                    }
                    if(self.is_show){
                        var ow = $top.width();
                        $doc_1.html(content).show();
                        $doc_0.hide();
                        var nw = $top.css("width", "").width();
                        if(nw!=ow){
                            // 此处虽然不算显隐动画操作，但是因为动用的是top节点，因此也需要进行设置isRun
                            self.is_run = true;
                            $top.stop().css("width", ow).animate({width: nw}, 200, function(){
                                self.is_run = false;
                                $top.css("width", "");
                            });
                        }
                        $doc_0.show().animate({height:0}, 200, function(){
                            $doc_0.hide().css("height","").html('&nbsp;').appendTo($top);
                            self.Event.trigger("onAfterContent", [content, ocontent]);
                        });
                    }else{
                        $doc_1.html(content).show().prependTo($top);
                        $doc_0.hide().html('&nbsp;');
                        self.Event.trigger("onAfterContent", [content, ocontent]);
                    }
                }
                return self;
            }
        }
    });
    // <include: view.login.js> line: 711-732
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
    // <include: view.member.js> line: 733-878
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
    // <include: view.asset.js> line: 879-983
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
    // <include: controller.sdbar.js> line: 984-1190
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
            myYL : function(event){
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
    var requires = [];
    window.JSON || requires.push(shs.static("common/js/json2.js"));
    window.template || requires.push(shs.static("common/js/template-native.js"));
    Loader.add(requires).ready(function(){
        Widgets.sdbar = new sdbarController();
    });
}(window, Widgets);
