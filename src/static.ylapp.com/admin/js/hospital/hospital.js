/**
 * 医院详情
 * @param e
 */
function getHospitalDetail(e){
    var hid = $(e).attr('hid');
    $.ajax({
        url: SITE_URL + "Hospital/getHospitalDetail",
        type: "post",
        data: {hid: hid},
        dataType: 'json',
        success: function (result) {
            var content = $("#hospital_detail .modal-body");
            content.html('');
            if(result.data.code == 0){

            }else{

            }
        }
    });
}