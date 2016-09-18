/**
 * 设置医生状态
 * @param e
 */
function setDoctorStat(e){
    var state = $(e).attr("state");
    var uid = $(e).attr("uid");
    $.ajax({
        url: SITE_URL + "Doctor/setDoctorStat",
        type: "post",
        data: {uid: uid,state:state},
        dataType: 'json',
        success: function (result) {
            if(result.code == 0){
                alert(result.msg);
                location.reload();
            }
        }

    });
}

/**
 * 获取医生详情
 * @param e
 */
function getDoctorDetail(e){
    var uid = $(e).attr("uid");
    $.ajax({
        url: SITE_URL + "Doctor/getDoctorDetail",
        type: "post",
        data: {uid: uid},
        dataType: 'json',
        success: function (result) {
            console.log(result);
            var doctorInfo = $("#doctor_detail .modal-body");
            doctorInfo.html('');
            if(result.date != false){
                doctorInfo.html();
            }
        }

    });
}