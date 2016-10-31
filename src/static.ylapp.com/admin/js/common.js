$(function(){
    /*$('.modal-primary').on('hidden.bs.modal', function () {
        $('.modal-primary').text('');*/

    /*解决ckeditor在modal中恐惧不可编辑的问题*/
    $.fn.modal.Constructor.prototype.enforceFocus = function () {
        modal_this = this
        $(document).on('focusin.modal', function (e) {
            if (modal_this.$element[0] !== e.target && !modal_this.$element.has(e.target).length
                    // add whatever conditions you need here:
                &&
                !$(e.target.parentNode).hasClass('cke_dialog_ui_input_select') && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_text')) {
                modal_this.$element.focus()
            }
        })
    };
});
function initFileInput(ctrlName, uploadUrl,maxImageHeight,maxImageWidth) {
    var control = $('#' + ctrlName);

    control.fileinput({
        language: 'zh', //设置语言
        uploadUrl: uploadUrl, //上传的地址
        allowedFileExtensions : ['jpg', 'png','gif','jpeg'],//接收的文件后缀
        showUpload: false, //是否显示上传按钮
        showCaption: false,//是否显示标题
        uploadAsync: false, // 是否异步上传
        maxImageHeight:maxImageHeight, // 最大高度
        maxImageWidth:maxImageWidth,  // 最大宽度
        browseClass: "btn btn-default" //按钮样式
        //previewFileIcon: "<i class='glyphicon glyphicon-king'></i>"
    });
}

/**
 * 检查输入长度并提示
 * @param input
 * @param $min
 * @param $max
 */
function checkInputLength(input,formName,min,max){
    input = $.trim(input);
    if(input.length < min || input.length > max){
        alert(formName + '不能大于'+ max + '个字符，小于'+ min + '个字符');
        return false;
    }else{
        return true;
    }
}

/**
 * 获取图片尺寸
 * @param url
 * @param callback
 */
function getImageWidth(url,callback){
    var img = new Image();
    img.src = url;

    // 如果图片被缓存，则直接返回缓存数据
    if(img.complete){
        callback(img.width, img.height);
    }else{
        // 完全加载完毕的事件
        img.onload = function(){
            callback(img.width, img.height);
        }
    }

}


function ImageValidata(imgSrc,imgDesc,width,height){
    if(imgSrc == undefined){
        alert(imgDesc + '请上传正确类型的文件');
        return false;
    }
    getImageWidth(imgSrc,function(w,h){
        if(w > width || h > width){
            alert('图片大小宽不能超过'+ width + 'px，高不能超过'+ height +'px');
            return false;
        }
    });

    return true;
}
/*
var MyRule = {
    required: /\S+/,
    email: /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i,
    url: /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i,
    date: /^\d{4}-\d{1,2}-\d{1,2}(\s+\d{1,2}:\d{1,2}:\d{1,2})?$/,
    number: /^\d+$/,
    float: /^\d+(\.\d+)*$/,
    chinese: /^[\u4E00-\u9FA5\uF900-\uFA2D]+$/,
    mobile: /^(13|14|15|18)[0-9]{9}$/,
    outspecial: /^[\u4e00-\u9fa5A-Za-z0-9_]+$/,
    money: /^([1-9][0-9]*([\.]\d{1,2})?|0\.[1-9][0-9]?)$/
};
$(function(){
    $(document).ajaxStart(function(){
        ShowLoading();
    }).ajaxStop(function(){
        HideLoading();
    }).ajaxError(function(event, request, settings){
        HideLoading();
        artDialog({
            id: 'dialog',
            title: '请求错误',
            content: '<div id="dialog">'+request.responseText+'</div>',
            width: 500,
            height: 260,
            padding: 0,
            lock: true
        }).show();
    });
    $("body").delegate("div#js-sidebar dl.menu-item a:not([noajax]),ul#tabs li a","click",function(){
        var $this = $(this);
        load($this.attr('href'), "div#main-wrap", $this.data());
        return false;
    }).delegate("a[type='post']","click",function(){
        var $this = $(this);
        $.post($this.attr('href'), $this.data(), function(rs){
            AjaxFilter(rs, $this);
        });
        return false;
    })
    $("div#main-wrap").delegate("a[type='form']","click",function(){
        var $this = $(this);
        var href = $this.attr('href'), rel = $this.attr('rel'), text = '';
        if(rel){
            text = $(rel).html();
            if(!text){return false;}
            $.each($this.data(), function(i,n){
                text = text.replace(new RegExp('{'+i+'}', 'g'), n);
            });
            text = text.replace(/\{\w+\}/g, '');
        }else{
            $.ajax({
                async:false,
                url:href,
                data:$this.data(),
                success:function(rs){
                    if(!AjaxFilter(rs)){
                        return;
                    }
                    text = rs;
                }
            });
        }
        if(!text || text.length<1){
            return false;
        }
        var title = $this.attr('title')||$this.text();
        artDialog({
            id: 'artFormDialog',
            title: title,
            content: '<div id="artFormDialog">'+text+'</div>',
            width: $this.attr('width'),
            height: $this.attr('height'),
            padding: 0,
            background: '#000',
            opacity: 0.35,
            lock: true,
            ok: function(){
                var $form = $("#artFormDialog form", '.aui_content'), $flag = true;
                $form.ajaxSubmit({dataType:'json',beforeSubmit:function(formData, jqForm, options){
                    $flag = FormValidate($form);
                    return $flag;
                },success:function(rs){
                    AjaxFilter(rs, $this);
                }});
                return $flag;
            },
            cancel: true
        });
        return false;
    }).delegate("a[type='dialog']","click",function(){
        var $this = $(this);
        $.get($this.attr('href'), $this.data(), function(rs){
            if(!AjaxFilter(rs)){
                return;
            }
            var title = $this.attr('title')||$this.text();
            var width = $this.attr("width"), height = $this.attr("height");
            artDialog({
                id: 'dialog',
                title: title,
                content: '<div id="dialog" style="width:'+width+'px;height:'+height+'px;overflow:auto;">'+rs+'</div>',
                width: width,
                height: height,
                padding: 0,
                background: '#000',
                opacity: 0.35,
                lock: true
            }).show();
        });
        return false;
    }).delegate("a[type='confirm']","click",function(){
        var $this = $(this);
        if(confirm($this.attr("title"))){
            $.post($this.attr('href'), $this.data(), function(rs){
                AjaxFilter(rs, $this);
            });
        }
        return false;
    }).delegate("a[type='confirm|form']","click",function(){
        var $this = $(this),href = $this.attr('href'),text = '';
        if(confirm($this.attr("title"))){
            $.ajax({
                async:false,
                url:href,
                data:$this.data(),
                success:function(rs){
                    if(!AjaxFilter(rs)){
                        return;
                    }
                    text = rs;
                }
            });
            if(!text || text.length<1){
                return false;
            }
            artDialog({
                id: 'artFormDialog',
                title: $this.text(),
                content: '<div id="artFormDialog">'+text+'</div>',
                width: $this.attr('width'),
                height: $this.attr('height'),
                padding: 0,
                background: '#000',
                opacity: 0.35,
                lock: true,
                ok: function(){
                    var $form = $("#artFormDialog form", '.aui_content'), $flag = true;
                    $form.ajaxSubmit({dataType:'json',beforeSubmit:function(formData, jqForm, options){
                        $flag = FormValidate($form);
                        return $flag;
                    },success:function(rs){
                        AjaxFilter(rs, $this);
                    }});
                    return $flag;
                },
                cancel: true
            });
            return false;
        }
        return false;
    }).delegate("a[type='load'][rel]","click",function(){
        var $this = $(this);
        load($(this).attr("href"), $this.attr('rel'), $this.data());
        return false;
    }).delegate("form[rel]", "submit", function(){
        var $this = $(this);
        load($this.attr("action"), $this.attr('rel'), $this.serializeArray());
        return false;
    }).delegate("form[type='ajax']", "submit", function(){
        var $form = $(this);
        $form.ajaxSubmit({dataType:'json',beforeSubmit:function(formData, jqForm, options){
            if(!FormValidate($form)){
                return false;
            }
        },success:function(rs){
            AjaxFilter(rs, $form);
        }});
        return false;
    }).delegate("a[type='tips']","click",function(){
        var $this = $(this),_this=this;
        $($this).ajaxStart(function(){
            HideLoading();
        });

        $.get($this.attr('href'), $this.data(), function(rs){
            if(!AjaxFilter(rs)){
                return;
            }
            art.dialog({
                title:false,
                width:350,
                height:50,
                background: '#000',
                padding:'0',
                lock:true,
                follow: _this
            }).content(rs);
        });
        return false;
    });
});
*/
/**
 * 表单验证
 * @param $form
 *//*

function FormValidate($form)
{
    var flag = true;
    $("input[data-rule][data-msg]", $form).add("select[data-rule][data-msg]", $form).add("textarea[data-rule][data-msg]", $form).each(function(i){
        var $input = $(this), preflag = true, prefix = $input.attr('prefix');
        //修复当表单name包含中括号时的Bug，类name="cash[cname]"
        var form_name = $input.attr('name').replace(/\[(.+?)\]/,"_$1_");
        var msg_label = $("span#for_"+form_name, $form);
        if(!msg_label.length){
            form_name = $input.attr('msgname');
            msg_label = $("span#for_"+form_name, $form);
            if(!msg_label.length){
                msg_label =$('<span id="for_'+form_name+'"></span>');
                msg_label.insertAfter($input);
            }
        }
        $input.focus(function(){
            msg_label.hide();
        });
        var type = $input.attr("type"), value = $input.val();
        if(type === "radio" || type === "checkbox"){
            value = $("input[name='" + $input.attr("name") + "']:checked").val();
            $("input[name='" + $input.attr("name") + "']").focus(function(){
                msg_label.hide();
            });
        }
        if(typeof value === "string"){
            value = $.trim(value.replace(/\r/g, ""));
        }else if(typeof value === "undefined"){
            value = '';
        }
        if(typeof prefix === "string"){
            // 验证当前$input的前置条件
            if(prefix == 'noempty'){
                preflag = Boolean(value.length>0);
            }else if(/^[a-zA-Z]\w*$/.test(prefix)){
                preflag = Boolean(eval(prefix + "()"));
            }else{
                preflag = Boolean(eval("(function(){" + prefix + "})();"));
            }
        }
        if(!preflag){ return true;}
        var data = $input.data(), rule = data.rule.split('|'), msg = data.msg.split('|');
        $.each(rule, function(j,k){
            var $flag = true;
            // 函数验证
            if(/^(min|max|range|minlength|maxlength|lengthlange|equalto|iszero|adjustrebate)\(.+\)$/.test(k)){
                var funcExp = new RegExp(/([a-z0-9_\-\.]+)/ig);
                var param = k.match(funcExp);
                var func = param.shift();
                switch(func){
                    case 'min':
                        var minval = parseFloat(param.shift());
                        if(parseFloat(value)<minval){ $flag = false;}
                        break;
                    case 'max':
                        var maxval = parseFloat(param.shift());
                        if(parseFloat(value)>maxval){ $flag = false;}
                        break;
                    case 'range':
                        var minval = parseFloat(param.shift());
                        var maxval = parseFloat(param.shift());
                        // num 小数位数
                        var num = parseInt(param.shift()) || 0, _flg = 0;

                        value = parseFloat(value);
                        if(num > 0){
                            if(Number(value).toFixed(num) != value){
                                _flg++;
                            }
                        }
                        if(value<minval||value>maxval){
                            _flg++;
                        }
                        if(_flg > 0)$flag = false;

                        break;
                    case 'minlength':
                        var minlen = parseInt(param.shift());
                        if(value.length<minlen){ $flag = false;}
                        break;
                    case 'maxlength':
                        var maxlen = parseInt(param.shift());
                        if(value.length>maxlen){ $flag = false;}
                        break;
                    case 'lengthlange':
                        var minlen = parseInt(param.shift());
                        var maxlen = parseInt(param.shift());
                        if(value.length<minlen||value.length>maxlen){ $flag = false;}
                        break;
                    case 'equalto':
                        var ele = param.shift();
                        if(value != $("#"+ele).val()){ $flag = false;}
                        break;
                    case 'iszero':// 是否为0
                        if (value == 0) { $flag = false; }
                        break;
                    case 'adjustrebate': // 调整返现金
                        var minlen = parseFloat(param.shift());
                        var maxlen = parseFloat(param.shift());
                        var adjust = parseFloat(param.shift());

                        var _num = parseFloat(value.replace(/(\+|-)/, ''));
                        if (value<0) {
                            num = adjust-_num;
                        }else {
                            num = adjust+_num;
                        }

                        if (num<minlen || num>maxlen) {
                            $flag = false;
                        }
                        break;
                }
            }else{ // 表达式验证
                var crule = MyRule[k] || k;
                var reg = new RegExp(crule);
                if(!reg.test(value)){
                    $flag = false;
                }
            }
            if(!$flag){
                flag = false;
                msg_label.text(msg[j]).css({color:"red"}).show();
                return false;
            }
        });
    });
    // 执行其它验证方法
    var beforesubmit = $form.attr('beforesubmit');
    if(typeof beforesubmit === "string" && flag){
        if(/^[a-zA-Z]\w*$/.test(beforesubmit)){
            flag = Boolean(eval(beforesubmit + "()"));
        }else{
            flag = Boolean(eval("(function(){" + beforesubmit + "})();"));
        }
    }
    return flag;
}

*/
/**
 * 提示窗口
 * @param tips 提示内容
 * @param type 提示类型 notice,error,right
 * @param timeout 自动关闭时间,以毫秒为单位
 *//*

function PopupTips(tips, type, timeout) {
    var time = timeout/1000 || 1;
    var icon = '';
    switch(type){
        case 'notice':
            icon = 'warning';
            break;
        case 'error':
            icon = 'error';
            break;
        case 'success':
        case 'right':
            icon = 'succeed';
            break;
    }
    artDialog({
        id: 'tips',
        title: false,
        content: tips,
        cancel: false,
        fixed: true,
        lock: true,
        icon: icon
    }).time(time).show();
}
*/
/**
 * 过虑返回值
 * @param rs
 * @returns {Boolean}
 *//*

function AjaxFilter(rs,el){
    var r = true;
    switch(rs.type){
        case 'NO_LOGIN':
            PopupTips('未登录或登录超时', 'notice', rs.timeout);
            setTimeout("window.top.location.href='"+rs.login_url+"'", rs.timeout);
            r = false;
            break;
        case 'ACCESS_DENY':
            PopupTips('您无此操作权限', 'notice', 3000);
            r = false;
            break;
        case 'ERROR':
            PopupTips(rs.msg, 'error', 3000);
            r = false;
            break;
        case 'SUCCESS':
            PopupTips(rs.msg, 'right', 1500);
            r = true;
            break;
        default:
            r = true;
    }
    if(r){
        var callback = el ? el.attr('callback') : false;
        if(callback){
            if(/^[a-zA-Z]\w*$/.test(callback)){
                eval(callback + "()");
            }else{
                eval("(function(){" + callback + "})();");
            }
        }
    }
    return r;
}
function ShowLoading(msg) {
    var cont = '<span><img style="vertical-align:middle;margin-right:0.5em;" src="' + STATIC_URL + '/images/loading.gif"/>' + (msg || '处理中,请稍候...') + '</span>';
    artDialog({
        id: 'loading',
        title: false,
        cancel: false,
        fixed: true,
        lock: true,
        background: '#000',
        opacity: 0.35,
        close: function () {
            this.hide();
            return false;
        }
    }).content(cont).show();
}
function HideLoading() {
    artDialog({
        id: 'loading'
    }).close();
}
function load(url, rel, data)
{
    if(!url){
        return false;
    }
    var rel = rel || 'div#main-wrap';
    if(typeof data !== 'object'){
        data = {};
    }
    $.get(url, data, function(rs){
        if(!AjaxFilter(rs)){
            return;
        }
        $(rel).data('data', data).data('url', url).html(rs);
        if('div#main-wrap' == rel){
            History.pushState({rand:Math.random(),rel:rel,content:JSON.stringify(rs)}, "管理员中心", url);
        }
    });
}
function reload(rel)
{
    var rel = rel || 'div#main-wrap';
    load($(rel).data()['url'], rel, $(rel).data()['data']);
}
*/
