$(function(){
    $(".modal-dialog").css('width','400px');
    $(".modal-dialog-info").css('width','650px');
    $(".modal-dialog-order").css('width','800px');
});


/**
 * 获取用户病历
 */
function getIllInfo(e){
    var uid = $(e).attr('uid');
    $.ajax({
        url: SITE_URL + "user/getUserIllness",
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
        url: SITE_URL + "user/getUserOrder",
        type: "post",
        data: {type: type,uid:uid},
        dataType: 'json',
        success: function (result) {
            //console.log(result);
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
                case 4:
                    var gmjl = $(".order_detail #gmjl");
                    gmjl.html('');
                    if(result.data.order != false){
                        $.each(result.data.order,function(i,d){
                            var orderType = '';
                            switch (d.orderType){
                                case '1':
                                    orderType = '疫苗接种';
                                    break;
                                case '2':
                                    orderType = '基因检测';
                                    break;
                                default :
                                    break;
                            }
                            var vaccinumType = '';
                            switch (d.vaccinumType){
                                case '1':
                                    vaccinumType = '(儿童)';
                                    break;
                                case '2':
                                    vaccinumType = '(成人)';
                                    break;
                                default :
                                    vaccinumType = '';
                            }
                            gmjl.append('<div><strong>'+ orderType + vaccinumType +'</strong></div>'+
                                    '<div class="order-taocan"><div>'+ d.packageTitle +'</div><div><span>金额：</span>'+ d.orderPrice +'元</div></div>'+
                                '<hr/>'
                            );
                        });
                    }else{
                        gmjl.html('<div style="text-align: center;margin-top: 20px">该用户暂无购买记录</div>');
                    }
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
        url: SITE_URL + "user/getTradeList",
        type: "post",
        data: {uid: uid},
        dataType: 'json',
        success: function (result) {
            var info = $("#trade_info .modal-body");
            console.log('test');
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

/**
 * 设置用户黑名单
 * @param e
 */
function setUserBlank(e){
    var flag = $(e).attr("flag");
    var uid = $(e).attr("uid");
    $.ajax({
        url: SITE_URL + "user/setUserBlank",
        type: "post",
        data: {uid: uid,flag:flag},
        dataType: 'json',
        success: function (result) {
            if(result.code == 0){
                alert(result.msg);
                location.reload();
            }
        }

    });
}

