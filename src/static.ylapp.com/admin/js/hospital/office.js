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
    $('#help_edit input[name="hid"]').val(hid);
    $.ajax({
        url: SITE_URL + "help/getHelpDetail",
        type: "post",
        data: {hid: hid},
        dataType: 'json',
        success: function (result) {
            if(result.code == 0){
                //console.log(result.data[0].title);
                $('#help_edit input[name="title"]').val(result.data[0].title);
                $('#help_edit #contentEdit').val(result.data[0].description);
                $('#help_edit #posEdit').val(result.data[0].type);
                $('#help_edit #is_show_edit').val(result.data[0].isShow);
            }
        }
    });
}


/**
 * 添加医院
 * @returns {boolean}
 */
function officeSave(){
    var hid = $('#office_edit input[name="hid"]').val();
    var titleInput = hid == 0 ? 'office_add' : 'office_edit';
    var officeName = $('#'+ titleInput +' input[name="officeName"]').val();
    if(!checkInputLength(title,'科室名称',2,16)){ return false;}
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
}