$(function(){
    $("#help_add .modal-dialog").css('width','600px');
    $("#hospital_edit .modal-dialog").css('width','400px');
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
 * 编辑医院之前
 * @param e
 * @constructor
 */
function EditHospitalPre(e){
    var hid = $(e).attr('hid');
    $('#hospital_edit input[name="hid"]').val(hid);
    $.ajax({
        url: SITE_URL + "hospital/getHospitalDetail",
        type: "post",
        data: {hid: hid},
        dataType: 'json',
        success: function (result) {
            console.log(result);
            if(result.code == 0){
                $('#hospital_edit input[name="hos_name"]').val(result.data.name);
                $('#hospital_edit input[name="address"]').val(result.data.address);
                $('#hospital_edit .file-drop-zone-title ').html('<div><img  style="width: 100%;height: 100%" src="'+ IMG_SERVER + result.data.img +'"/><input type="hidden" name="origin-hospital-img" value="'+ result.data.img +'"/></div>');
                /*content.html(
                 '<div class="hospital-detail"><span>医院名称：</span><div>' + result.data.name + '</div></div>' +
                 '<div class="hospital-detail"><span>医院地址：</span><div>' + result.data.address + '</div></div>' +
                 '<div class="hospital-detail"><span>图片：</span><div style="display: block"><img src="'+ IMG_SERVER + result.data.img +'" /></div></div>'
                 );*/
            }else{
                //content.html('<div style="text-align: center">请求错误！请联系管理员</div>');
            }
        }
    });
}


/**
 * 添加医院
 * @returns {boolean}
 */
function helpSave(){
    var title = $('#help_add input[name="title"]').val();
    if(!checkInputLength(title,'帮助标题',4,50)){ return false;}
    var formData = new FormData(document.getElementById('helpAdd'));
    $.ajax({
        url: SITE_URL + "help/saveHelp",
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
    $('input[name="hid"]').val('');
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