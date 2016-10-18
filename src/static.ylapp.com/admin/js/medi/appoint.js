$(function(){
    $("#appoint_add .modal-dialog").css('width','800');
    /*$("#news_edit .modal-dialog").css('width','800');*/
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


    // 药品筛选

    $('#medi-btn').bind('click',function(){
        console.log('test');
    });


});


//  药品选择
function selectMedi(e){
    $.ajax({
        url: SITE_URL + "medicine/getAllMedicine",
        type: "post",
        data: {},
        dataType: 'json',
        success: function (result) {
            if(result.data != false){
                $.each(function(i,d){
                    $(e).append('<option value="'+ d.id +'">'+ d.name +'</option>');
                });
            }
        }
    });
}

//  添加预约
function addAppoint(){
    var appointTime = $('#appoint_add #appointTime').val();  // 预约时间
    var realName = $('#appoint_add #realName').val();        // 姓名
    var telephone = $('#appoint_add #telephone').val();     // 预约人电话
    var mediName = $('#appoint_add #mediName').val();       // 药品id
    var content = CKEDITOR.instances.content.getData();
    for(instance in CKEDITOR.instances){
        CKEDITOR.instances[instance].updateElement();
    }
    var formData = new FormData(document.getElementById("appointAdd"));
    $.ajax({
        url: SITE_URL + "medicine/appointAdd",
        type: "post",
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (result) {
            if (result.code == 0) {
                alert(result.msg);
                location.reload();
            } else {
                alert(result.msg);
            }
        }
    });
}