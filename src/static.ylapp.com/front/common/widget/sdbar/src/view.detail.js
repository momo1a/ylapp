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
