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
function helpSave(){
    var hid = $('#help_edit input[name="hid"]').val();
    console.log(hid);
    var titleInput = hid == 0 ? 'help_add' : 'help_edit';
    var title = $('#'+ titleInput +' input[name="title"]').val();
    if(!checkInputLength(title,'帮助标题',4,50)){ return false;}
    var Form = hid == 0 ? 'helpAdd' : 'helpEdit';
    var formData = new FormData(document.getElementById(Form));
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