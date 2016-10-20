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