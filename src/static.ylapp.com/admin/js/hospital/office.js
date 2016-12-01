$(function(){
    $("#help_add .modal-dialog").css('width','600px');
    $("#help_edit .modal-dialog").css('width','600px');
})

/**
 * 编辑帮助之前
 * @param e
 * @constructor
 */
function editHelpPre(e){
    var hid = $(e).attr('hid');
    $('#office_edit input[name="hid"]').val(hid);
    $.ajax({
        url: SITE_URL + "hospital/getOfficeDetail",
        type: "post",
        data: {hid: hid},
        dataType: 'json',
        success: function (result) {
            if(result.code == 0){
                //console.log(result.data[0].title);
                $('#office_edit input[name="officeName"]').val(result.data[0].officeName);
            }
        }
    });
}


/**
 * 添加科室
 * @returns {boolean}
 */
function officeSave(){
    var hid = $('#office_edit input[name="hid"]').val();
    console.log(hid);
    var titleInput = hid == 0 ? 'office_add' : 'office_edit';
    console.log(titleInput);
    var officeName = $('#'+ titleInput +' input[name="officeName"]').val();
    console.log(officeName);
    if(!checkInputLength(officeName,'科室名称',2,16)){ return false;}
    var Form = hid == 0 ? 'officeAdd' : 'officeEdit';
    var formData = new FormData(document.getElementById(Form));
    $.ajax({
        url: SITE_URL + "hospital/saveOffice",
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


/*
function helpDel(){
    var hid = $("#help_del input[name='hid']").val();
    $.ajax({
        url: SITE_URL + "help/helpDel",
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
}*/
