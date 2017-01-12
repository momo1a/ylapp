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

function Bilivalidate(imgSrc,imgDesc,width,height){
    if(imgSrc == undefined){
        alert(imgDesc + '请上传正确类型的文件');
        return false;
    }
    getImageWidth(imgSrc,function(w,h){
        if((width/height).toFixed(2) != (w/h).toFixed(2)){
            console.log((width/height).toFixed(2));
            console.log((w/h).toFixed(2));
            console.log(w+":"+h+':::'+width+':'+height);
            alert('上传图片宽高比应为'+width+':'+height);
            return false;
        }
    });

    return true;
}