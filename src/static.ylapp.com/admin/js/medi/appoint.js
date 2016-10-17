$(function(){
    /*$("#news_add .modal-dialog").css('width','800');
    $("#news_edit .modal-dialog").css('width','800');*/
    CKEDITOR.replace('content');
    /*CKEDITOR.replace('contentEdit');
    initFileInput("medi-img", "/news/newsAdd",350,350);
    initFileInput("banner", "/news/newsAdd",350,350);
    initFileInput("banner-edit", "/news/newsAdd",350,350);
    initFileInput("news-img-edit", "/news/newsAdd",350,350);
    $(".fileinput-remove-button").css('display','none');  // 隐藏图片上传移除按钮*/
    $('#appointTime').datetimepicker({
        format:"Y-m-d H:i:s",      //格式化日期
        lang:"ch"
    });
});