/**
 * Created by Administrator on 2016/10/1 0001.
 */
$(function(){
    $("#package_add .modal-dialog").css('width','800');
    $("#package_edit .modal-dialog").css('width','800');
    CKEDITOR.replace('content');
    CKEDITOR.replace('contentEdit');
    initFileInput("vaccine_img", "/vaccine/packageAdd",350,350);
    initFileInput("vaccine_img_edit", "/vaccine/packageAdd",350,350);
    $(".fileinput-remove-button").css('display','none');  // 隐藏图片上传移除按钮
});


/**
 * 初始化添加套餐
 */
function packageAddPre(){
    $('input[name="pid"]').val(0);
}

function packageSave(){
    var pid = $('input[name="pid"]').val();
    //console.log(pid);
    var title = pid == 0 ? $('#package_add input[name="title"]').val() : $('#package_edit input[name="title"]').val();
    var content =  pid == 0 ?  CKEDITOR.instances.content.getData() : CKEDITOR.instances.contentEdit.getData();
    if(!checkInputLength(title,'套餐名称',8,50)){ return false;}
    if(!checkInputLength(content,'正文',20,20000)){ return false;}
    var packageImgSrc = $('.package-img img').attr('src');
    console.log(packageImgSrc);
    if(!(ImageValidata(packageImgSrc,'套餐缩略图',350,350))){ return false;}
    var status = pid == 0 ? 'status' : 'statusEdit';
    if($("#"+ status).val() == -1){
        alert('请选择状态');
        return false;
    }
    var type = pid == 0 ? 'type' : 'typeEdit';
    if($("#"+type).val() == -1){
        alert('请选择类型');
        return false;
    }

    for(instance in CKEDITOR.instances){
        CKEDITOR.instances[instance].updateElement();
    }
    var fromName = pid == 0 ?  "packageAdd" : "packageEdit";
    var formData = new FormData(document.getElementById(fromName));
    $.ajax({
        url: SITE_URL + "vaccine/packageSave",
        type: "post",
        data:formData,
        contentType:false,
        processData:false,
        dataType: 'json',
        success: function (result) {
            if(result.code == 0){
                alert(result.msg);
                location.reload();
            }else{
                alert(result.msg);
            }
        }
    });
}


/**
 * 编辑资讯初始化页面
 * @param e
 */
function editPackagePre(e){
    var pid = $(e).attr('pid');
    $('input[name="pid"]').val(pid);

    $.ajax({
        url: SITE_URL + "vaccine/getPackageDetail",
        type: "post",
        data:{pid:pid},
        dataType: 'json',
        success: function (result) {
            //console.log(result);
            $('#package_edit input[name="title"]').val(result.data.name);
            CKEDITOR.instances.contentEdit.setData(result.data.detail);
            $('#package_edit .package-img .file-drop-zone-title ').html('<div><img  style="width: 100%;height: 100%" src="'+ IMG_SERVER + result.data.thumbnail +'"/><input type="hidden" name="origin-thumbnail-img" value="'+ result.data.thumbnail +'"/></div>');
            $('#package_edit #statusEdit').val(result.data.status);
            $('#package_edit #typeEdit').val(result.data.type);
            $('#package_edit #price').val(result.data.price);
        }
    });

}

/**
 * 删除前
 * @param e
 */
function packageDelPre(e){
    var pid = $(e).attr('pid');
    $('#package_del input[name="pid"]').val(pid);
}

function packageDel(){
    var pid = $('#package_del input[name="pid"]').val();

    $.ajax({
        url: SITE_URL + "vaccine/packageDel",
        type: "post",
        data:{pid:pid},
        dataType: 'json',
        success: function (result) {
            if(result.code == 0){
                alert(result.msg);
                location.reload();
            }else{
                alert(result.msg);
            }
        }
    });
}
