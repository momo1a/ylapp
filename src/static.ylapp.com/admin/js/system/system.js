function setting(ele,action,value){
    $.ajax({
        url: SITE_URL + "system/index",
        type: "post",
        data: {value:value,dosave:action},
        dataType: 'json',
        success: function (result) {
            if(result.code == 0){
                alert('设置成功');
                $('#'+ele).val(value);
            }else{
                alert('设置失败');
            }
        }

    });
}

function textSetting(ele,value){
    for(instance in CKEDITOR.instances){
        CKEDITOR.instances[instance].updateElement();
    }
    var formData = new FormData(document.getElementById(ele));
    console.log(formData);
    $.ajax({
        url: SITE_URL + "system/index",
        type: "post",
        data:formData,
        contentType:false,
        processData:false,
        dataType: 'json',
        success: function (result) {
            if(result.code == 0){
                alert('设置成功');
                $('#'+ele).val(value);
            }else{
                alert('设置失败');
            }
        }
    });
}


function fileUpload(ele){
    var formData = new FormData(document.getElementById(ele));
    console.log(formData);
    $.ajax({
        url: SITE_URL + "system/index",
        type: "post",
        data:formData,
        contentType:false,
        processData:false,
        dataType: 'json',
        success: function (result) {
            if(result.code == 0){
                alert('上传成功');
            }else{
                alert(result.msg);
            }
        }
    });
}