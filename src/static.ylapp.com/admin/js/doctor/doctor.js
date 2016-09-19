$(function(){
    $('#doctor_detail .modal-dialog-info').css('width','650px');
});
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
            var doctor = result.data;
            if(doctor!= false){
                switch (doctor.sex){
                    case '1':
                        var sex = '男';
                        break;
                    case '2':
                        var sex = '女';
                        break;
                    default :
                        var sex = '男';
                }
                doctorInfo.append('<div class="doctor-detail"><div><span>账号：</span>'+ doctor.phone +'</div><div><span>密码：</span>'+ doctor.password +'</div></div>' +
                '<div class="doctor-detail"><div><span>姓名：</span>'+ doctor.nickname +'</div><div><span>出生日期：</span>'+ doctor.birthday +'</div></div>' +
                '<div class="doctor-detail"><div><span>性别：</span>'+ sex +'</div><div><span>电话一：</span>'+ doctor.phone +'</div></div>'+
                '<div class="doctor-detail"><div><span>所属医院：</span>'+ doctor.name +'</div><div><span>电话二：</span>'+ doctor.phoneSec +'</div></div>' +
                '<div class="doctor-detail"><div><span>科室：</span>'+ doctor.officeName +'</div><div><span>学历：</span>'+ doctor.degree +'</div></div>' +
                '<hr/>' +
                '<div class="doctor-desc"><span>简介：</span>'+ doctor.summary + '</div>'+
                '<hr/>' +
                '<div class="doctor-desc"><span>擅长：</span>'+ doctor.goodAt + '</div>');
                doctorInfo.append('<div class="doctor-cert-img">');
                if(doctor.certificateImg != ''){
                    doctorInfo.append('<hr/>');
                    $.each(doctor.certificateImg,function(i,d){
                        doctorInfo.append('<div><img src="'+ IMG_SERVER + d +'"/></div>');
                    });
                }
                //doctorInfo.append('</div>');
            }
        }

    });
}