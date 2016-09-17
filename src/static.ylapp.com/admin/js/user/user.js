$(function(){
    $(".modal-dialog").css('width','400px');
    $(".modal-dialog-info").css('width','600px');
    $(".modal-dialog-order").css('width','800px');
});


/**
 * 获取用户病历
 */
function getIllInfo(e){
    var uid = $(e).attr('uid');
    $.ajax({
        url: SITE_URL + "User/getUserIllness",
        type: "post",
        data: {uid:uid},
        dataType: 'json',
        success: function (result) {
            $('#ill_info .modal-body').html("");  // 清空模态框内容
            if(result.code == 0){
                $('#ill_info .modal-body').css('height','650px').css('overflow','auto');
                //console.log(result);
                $.each(result.data,function(idx,obj){
                    switch (obj.sex){
                        case "1":
                            var sex = "男";
                            break;
                        case "2":
                            var sex = "女";
                            break;
                        default :
                            break;
                    }
                    var remarks = '';
                    // 就诊记录
                    if(obj.remarks != false){
                        remarks += '<div style="margin-top: 15px"><div>就诊记录：</div>';
                        $.each(obj.remarks,function(i,d){
                            var  image = '';
                            if(d.img != '' || d.img != false){
                                image += '<div class="ill_remark_img">';
                                $.each(d.img,function(imgI,imgO){
                                    image += '<span>';
                                    image += '<img src="'+ IMG_SERVER + imgO + '"\/>';
                                    image += '</span>';
                                });
                                image  += '</div>';
                            }
                            /*第一行*/
                            remarks += '<div style="color: #ffff00">';
                            remarks += '<div class="remark-info-base">';
                            remarks += d.visitDate;
                            remarks += '&nbsp;&nbsp;';
                            remarks += d.stage;
                            remarks += '</div>';
                            remarks += '</div>';
                            /*第二行*/
                            remarks += '<div>';
                            remarks += d.content;
                            remarks += '</div>';

                            /*第三行图片*/

                            remarks += image;

                            remarks += '<div style="display: block;border-bottom: 1px dotted gainsboro;margin-bottom: 10px"></div>';
                        });
                        remarks += '</div>';
                    }
                    $("#ill_info .modal-body").append("<div style='color: #ffff00;display: block;font-weight: bold'>" +
                    obj.illName + "</div>" +
                    "<div><span>就诊人：</span><div class='ill_info_base'>" + obj.realname +"</div>" +
                    "<span>性别：</span><div class='ill_info_base'>" + sex +"</div>" +
                    "<span>年龄：</span><div class='ill_info_base'>" + obj.age +"</div></div>" +
                    "<div><span>过敏史：</span><div class='ill_info_sec'>" + obj.allergyHistory + "</div>" +
                    "<span>诊断：</span><div class='ill_info_sec'>" + obj.result + "</div></>" +
                    "<div><span>分期：</span><div class='ill_info_trd'>" + obj.stages + "期</div></div>" +
                    "<div><span>基本病情：</span><div class='ill_info_four'>" + obj.situation + "</div></div>" + remarks +
                    "<hr/>");
                });
            }else{
                $('#ill_info .modal-body').css('height','100px');
                $('#ill_info .modal-body').html("<div style='display: block;text-align: center'>"+ result.msg +"</div>");

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
        url: SITE_URL + "User/getUserOrder",
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
                            yygh.append('<div class="zxwz-f"><div><span>预约人：</span>' + d.contacts + '</div>' + '<div><span>预约医生：</span>' + d.docName+ '</div><div><span>定金：</span>' + d.price + '元</div></div>' +
                                '<div class="zxwz-f"><div><span>电话：</span>'+ d.appointTel +'</div><div><span>所属医院：</span>' + d.name +'</div></div>' +
                                '<div class="zxwz-f"><div><span>预约时间：</span>'+ d.phoneTimeLen +'分</div><div><span>职位：</span>'+ d.docLevel +'</div><div><span>状态：</span>'+ orderState +'</div></div>' +

                                '<hr/>'
                            );
                        });
                    }else{
                        yygh.html('<div style="text-align: center;margin-top: 20px">该用户暂无预约记录</div>');
                    }
                    break;
                case 3:
                    break;
                case 4:
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

