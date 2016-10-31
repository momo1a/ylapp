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
        //console.log('');
        var patternStr = $.trim($("#medi-shaixuan").val());
        if(patternStr.length == 0){
            alert('请输入筛选内容');
            return false;
        }
        pattern =  new RegExp('.?'+ patternStr + '.?','i');

        $.ajax({
            url: SITE_URL + "medicine/getAllMedicine",
            type: "post",
            data: {},
            dataType: 'json',
            success: function (result) {
                var ele = $('#appoint_add #mediName');
                ele.html('<option value="0">请选择药品</option>');
                if(result.data != false){
                    $.each(result.data,function(i,d){
                        //console.log(d);
                        if(pattern.test(d.name)) {
                            ele.append('<option value="' + d.id + '">' + d.name + '</option>');
                        }
                    });
                }
            }
        });

    });


    // 用户手机筛选

    $('#user-btn').bind('click',function(){
        //console.log('');
        var patternStr = $.trim($("#user-shaixuan").val());
        if(patternStr.length == 0){
            alert('请输入筛选内容');
            return false;
        }
        patt =  new RegExp('.?'+ patternStr + '.?','i');

        $.ajax({
            url: SITE_URL + "medicine/getAllUser",
            type: "post",
            data: {},
            dataType: 'json',
            success: function (result) {
                var elem = $('#appoint_add #regTel');
                elem.html('<option value="0">请选择用户注册手机</option>');
                if(result.data != false){
                    $.each(result.data,function(i,d){
                        //console.log(d);
                        if(patt.test(d.phone)) {
                            elem.append('<option value="' + d.uid + '">' + d.phone + '</option>');
                        }
                    });
                }
            }
        });

    });


    //  筛选伙计

    $('#guys-btn').bind('click',function(){
        //console.log('');
        var patternStr = $.trim($("#guys-shaixuan").val());
        if(patternStr.length == 0){
            alert('请输入筛选内容');
            return false;
        }
        patt =  new RegExp('.?'+ patternStr + '.?','i');

        $.ajax({
            url: SITE_URL + "medicine/getGuys",
            type: "post",
            data: {},
            dataType: 'json',
            success: function (result) {
                var elem = $('#appoint_allot #guys');
                elem.html('<option value="0">请选择药房伙计</option>');
                if(result.data != false){
                    $.each(result.data,function(i,d){
                        //console.log(d);
                        if(patt.test(d.nickname)) {
                            elem.append('<option value="' + d.uid + '">' + d.nickname + '</option>');
                        }
                    });
                }
            }
        });

    });




    $('#medi-shaixuan').bind('blur',function(){
        $('#appoint_add #mediName').html('<option value="0">请选择药品</option>');
        appointAddPre();
    });

    $('#user-shaixuan').bind('blur',function(){
        $('#appoint_add #regTel').html('<option value="0">请选择用户注册手机</option>');
        appointAddPre();
    });

    $('#guys-shaixuan').bind('blur',function(){
        $('#appoint_allot #guys').html('<option value="0">请选择药房伙计</option>');
        $.ajax({
            url: SITE_URL + "medicine/getGuys",
            type: "post",
            data: {},
            dataType: 'json',
            success: function (result) {
                //console.log(result);
                if(result.data != false){
                    $.each(result.data,function(i,d){
                        $('#appoint_allot #guys').append('<option value="'+ d.uid +'">'+ d.nickname +'</option>');
                    });
                }
            }
        });
    });


});



// 获取预约详情
function appointDetailPre(e){
    var aid = $(e).attr('aid');
    console.log(aid);
    $.ajax({
        url: SITE_URL + "medicine/getAppointDetail",
        type: "post",
        data: {aid:aid},
        dataType: 'json',
        success: function (result) {
            if(result.data != false){
                console.log(result);
                $('#appoint_detail #appoint-content').html(result.data.content);
            }
        }
    });
}

// 初始化分配药品页面
function appointAllotPre(e){
    var aid =  $(e).attr('aid');
    $('#appoint_allot input[name="aid"]').val(aid);
    $.ajax({
        url: SITE_URL + "medicine/getGuys",
        type: "post",
        data: {},
        dataType: 'json',
        success: function (result) {
            console.log(result);
            if(result.data != false){
                $.each(result.data,function(i,d){
                    $('#appoint_allot #guys').append('<option value="'+ d.uid +'">'+ d.nickname +'</option>');
                });
            }
        }
    });
}

//  药品选择
function appointAddPre(){
    $.ajax({
        url: SITE_URL + "medicine/getAllMedicine",
        type: "post",
        data: {},
        dataType: 'json',
        success: function (result) {
            if(result.data != false){
                $.each(result.data,function(i,d){
                    $('#appoint_add #mediName').append('<option value="'+ d.id +'">'+ d.name +'</option>');
                });
            }
        }
    });


    $.ajax({
        url: SITE_URL + "medicine/getAllUser",
        type: "post",
        data: {},
        dataType: 'json',
        success: function (result) {
            if(result.data != false){
                $.each(result.data,function(i,d){
                    $('#appoint_add #regTel').append('<option value="'+ d.uid +'">'+ d.phone +'</option>');
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

// 分配伙计
function appointToAllot(){
    var formData = new FormData(document.getElementById("appointAllot"));
    $.ajax({
        url: SITE_URL + "medicine/allot",
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

function cleanForm(){
    document.appointAdd.reset();
    $("#appoint_add #mediName").html('<option value="0">请选择药品</option>');
    $("#appoint_add #regTel").html('<option value="0">请选择用户注册手机号码</option>');
    CKEDITOR.instances.content.setData('');
}