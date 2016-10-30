$(function(){
    $("#hospital_detail .modal-dialog").css('width','400px');
    $("#hospital_add .modal-dialog").css('width','400px');
    initFileInput("hospital-img", "/hospital/saveHospital",350,350);
})
/**
 * 医院详情
 * @param e
 */
function getHospitalDetail(e){
    var hid = $(e).attr('hid');
    $.ajax({
        url: SITE_URL + "hospital/getHospitalDetail",
        type: "post",
        data: {hid: hid},
        dataType: 'json',
        success: function (result) {
            //console.log(result);
            var content = $("#hospital_detail .modal-body");
            content.html('');
            if(result.code == 0){
                content.html(
                    '<div class="hospital-detail"><span>医院名称：</span><div>' + result.data.name + '</div></div>' +
                    '<div class="hospital-detail"><span>医院地址：</span><div>' + result.data.address + '</div></div>' +
                    '<div class="hospital-detail"><span>图片：</span><div style="display: block"><img src="'+ IMG_SERVER + result.data.img +'" /></div></div>'
                );
            }else{
                content.html('<div style="text-align: center">请求错误！请联系管理员</div>');
            }
        }
    });
}

/**
 * 添加医院
 * @returns {boolean}
 */
function hospitalSave(){
    var hosName = $('#hospital_add input[name="hos_name"]').val();
    var address = $('#hospital_add input[name="address"]').val();
    if(!checkInputLength(hosName,'医院名称',4,50)){ return false;}
    if(!checkInputLength(address,'地址',8,60)){ return false;}
    var imgSrc = $('.kv-file-content img').attr('src');
    if(imgSrc == undefined){
        alert('请上传正确类型的文件');
        return false;
    }
    getImageWidth(imgSrc,function(w,h){
        if(w > 350 || h > 350){
            alert('图片大小宽不能超过350px，高不能超过350px');
            return false;
        }
    });
    var formData = new FormData(document.getElementById('myForm'));
    $.ajax({
        url: SITE_URL + "hospital/saveHospital",
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
 * 删除医院之前
 * @param e
 */
function hospitalDelPre(e){
    var hid = $(e).attr('hid');
    $("input[name='hid']").val(hid);
}

// 添加医院之前
function hosAddPre(){
    $('#hospital_add input[name="hos_name"]').val('');
    $('#hospital_add input[name="address"]').val('');
    $('.hidden-xs').trigger('click');
}

function hospitalDel(){
    var hid = $("input[name='hid']").val();
    $.ajax({
        url: SITE_URL + "hospital/delHospital",
        type: "post",
        data:{hid:hid},
        dataType: 'json',
        success: function (result) {
            console.log(result)
            if(result.code == 0){
                alert(result.msg);
                location.reload();
            }else{
                alert(result.msg);
            }
        }
    });
}