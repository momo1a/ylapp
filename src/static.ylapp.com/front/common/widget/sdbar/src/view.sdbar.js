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
