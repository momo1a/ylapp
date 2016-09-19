$(function(){
    $('#doctor_detail .modal-dialog-info').css('width','800px');
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
                doctorInfo.html('<div class="doctor-detail"><div><span>账号：</span>'+ doctor.phone +'</div><div><span>密码：</span>'+ doctor.password +'</div></div>' +
                '<div class="doctor-detail"><div><span>姓名：</span>'+ doctor.nickname +'</div><div><span>出生日期：</span>'+ doctor.birthday +'</div></div>' +
                '<div class="doctor-detail"><div><span>性别：</span>'+ sex +'</div><div><span>电话一：</span>'+ doctor.phone +'</div></div>'+
                '<div class="doctor-detail"><div><span>所属医院：</span>'+ doctor.name +'</div><div><span>电话二：</span>'+ doctor.phoneSec +'</div></div>' +
                '<div class="doctor-detail"><div><span>科室：</span>'+ doctor.officeName +'</div><div><span>学历：</span>'+ doctor.degree +'</div></div>' +
                '<hr/>' +
                '<div class="doctor-desc"><span>简介：</span>'+ doctor.summary + '</div>'+
                '<hr/>' +
                '<div class="doctor-desc" id="goodAt"><span>擅长：</span>'+ doctor.goodAt + '</div>');
                if(doctor.certificateImg != ''){
                    //$("#goodAt").append('<hr /><div class="doctor-cert-img">');
                    var html = '<hr /><span>证书：</span><div class="doctor-cert-img">';
                    $.each(doctor.certificateImg,function(i,d){
                         html += '<div><img src="'+ IMG_SERVER + d +'"/></div>';
                    });
                    html += '</div>'
                    $('#goodAt').append(html);
                }
            }
        }

    });
}

/**
 * 订单类型切换
 * @param e
 */
function orderTurnTab(e){
    $(e).css('background','#808080').siblings().css('background','gainsboro');
    var showId = $(e).attr('for');
    $("#"+showId).show().siblings().hide();
    var  type = $(e).attr('type');
    type = type ? type : 1;   // 默认type值为1
    var uid = $("input[name='uid']").val();
    $.ajax({
        url: SITE_URL + "Doctor/getUserOrder",
        type: "post",
        data: {type: type,uid:uid},
        dataType: 'json',
        success: function (result) {
            console.log(result);
            switch (result.data.type){
                case 1: // 在线问诊
                    var zxwz = $(".order_detail #zxwz");
                    zxwz.html('');
                    if(result.data.order != false){
                        $.each(result.data.order,function(i,d){
                            var orderState = '';
                            switch (d.orderState){
                                case '0':
                                    orderState = '未付款';
                                    break;
                                case '1':
                                    orderState = '待处理';
                                    break;
                                case '2':
                                    orderState = '已经确认沟通时间';
                                    break;
                                case '3':
                                    orderState = '完成';
                                    break;
                                case '4':
                                    orderState = '失败';
                                    break;
                                case '5':
                                    orderState = '用户取消';
                                    break;
                                default :
                                    break;

                            }
                            zxwz.append('<div class="zxwz-f"><div><span>问诊人：</span>' + d.askNickname + '</div>' + '<div><span>就诊医生：</span>' + d.docName+ '</div><div><span>金额：</span>' + d.price + '元</div></div>' +
                                '<div class="zxwz-f"><div><span>电话：</span>'+ d.askTelephone +'</div><div><span>所属医院：</span>' + d.name +'</div></div>' +
                                '<div class="zxwz-f"><div><span>沟通时长：</span>'+ d.phoneTimeLen +'分</div><div><span>职位：</span>'+ d.docLevel +'</div><div><span>状态：</span>'+ orderState +'</div></div>' +
                                '<div class="zxwz-f"><div><span>沟通时间：</span>'+ d.hopeCalldate +'</div><div><span>电话：</span>'+ d.phone +'</div></div>' +
                                '<div class="zxwz-s"><div><span>基本病情：</span>' + d.askContent +  '</div></div>' +
                                '<hr/>'
                            );
                        });
                    }else{
                        zxwz.html('<div style="text-align: center;margin-top: 20px">该用户暂无问诊记录</div>');
                    }
                    break;
                case 2:
                    var yygh = $(".order_detail #yygh");
                    yygh.html('');
                    if(result.data.order != false){
                        $.each(result.data.order,function(i,d){
                            var orderState = '';
                            switch (d.orderState){
                                case '0':
                                    orderState = '未付款';
                                    break;
                                case '2':
                                    orderState = '待处理';
                                    break;
                                case '3':
                                    orderState = '预约成功';
                                    break;
                                case '4':
                                    orderState = '预约失败';
                                    break;
                                case '5':
                                    orderState = '完成';
                                    break;
                                case '6':
                                    orderState = '用户取消';
                                default :
                                    break;

                            }
                            yygh.append('<div class="zxwz-f"><div><span>预约人：</span>' + d.contacts + '</div>' + '<div><span>预约医生：</span>' + d.docName+ '</div><div><span>定金：</span>' + d.price + '元</div></div>' +
                                '<div class="zxwz-f"><div><span>电话：</span>'+ d.appointTel +'</div><div><span>所属医院：</span>' + d.name +'</div></div>' +
                                '<div class="zxwz-f"><div><span>预约时间：</span>'+ d.appointTime +'</div><div><span>职位：</span>'+ d.docLevel +'</div><div><span>状态：</span>'+ orderState +'</div></div>' +
                                '<div class="zxwz-f"><div>&nbsp;</div><div><span>电话：</span>'+ d.docTel +'</div></div>' +
                                '<hr/>'
                            );
                        });
                    }else{
                        yygh.html('<div style="text-align: center;margin-top: 20px">该用户暂无预约记录</div>');
                    }
                    break;
                case 3:
                    var lywd = $(".order_detail #lywd");
                    lywd.html('');
                    if(result.data.order != false){
                        $.each(result.data.order,function(i,d){
                            var orderState = '';
                            switch (d.orderState){
                                case '0':
                                case '1':
                                case '2':
                                case '3':
                                    orderState = '未完成';
                                    break;
                                case '4':
                                    orderState = '已完成';
                                    break;
                                default :
                                    break;
                            }
                            var replyContent = d.replyContent ? d.replyContent : '未回复';
                            lywd.append('<div class="zxwz-f"><div><span>留言问诊人：</span>' + d.nickname + '</div>' + '<div><span>问诊医生：</span>' + d.docName+ '</div><div><span>金额：</span>' + d.price + '元</div></div>' +
                                '<div class="zxwz-f"><div><span>电话：</span>'+ d.askerPone +'</div><div><span>所属医院：</span>' + d.name +'</div></div>' +
                                '<div class="zxwz-f"><div></div><div><span>职位：</span>'+ d.docLevel +'</div><div><span>状态：</span>'+ orderState +'</div></div>' +
                                '<div class="zxwz-f"><div>&nbsp;</div><div><span>电话：</span>'+ d.docPhone +'</div></div>' +
                                '<div class="zxwz-s"><div><span>留言：</span>' + d.askerContent +  '</div></div>' +
                                '<div class="zxwz-s"><div><span>医生回复：</span>' + replyContent +  '</div></div>' +
                                '<hr/>'
                            );
                        });
                    }else{
                        lywd.html('<div style="text-align: center;margin-top: 20px">该用户暂无问答记录</div>');
                    }
                    break;
                default :
                    break;
            }
        }
    });
}

/**
 * 获取用户订单记录
 * @param e
 */
function getOrderInfo(e){
    var uid = $(e).attr('uid');
    $("input[name='uid']").val(uid);
    $("#order_sub_tab_frt").trigger("click");
}


/**
 * 获取用户交易记录
 */
function getTradeInfo(e){
    var uid = $(e).attr('uid');
    $.ajax({
        url: SITE_URL + "Doctor/getTradeList",
        type: "post",
        data: {uid: uid},
        dataType: 'json',
        success: function (result) {
            var info = $("#trade_info .modal-body");
            if(result.data != false){
                var flag = '';
                info.html('');
                $.each(result.data,function(i,d){
                    switch (d.tradeType){
                        case '2':
                            flag = '<strong style="color: green;font-weight: bold">+&nbsp;</strong>';
                            break;
                        default :
                            flag = '<strong style="color: red;font-weight: bold">-&nbsp;</strong>';
                    }
                    info.append('<div><strong>'+ d.tradeDesc +'</strong></div>'+
                        '<div class="order-taocan"><div>'+ d.dateline +'</div><div><span>'+ flag +'</span>'+ d.tradeVolume +'元</div></div>'+
                        '<hr/>'
                    );
                });
            }else{
                info.html('<div style="text-align: center;margin-top: 20px">该用户暂无交易记录</div>');
            }
        }
    });
}