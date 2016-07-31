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
