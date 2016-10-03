/**
 * Created by Administrator on 2016/10/2 0002.
 */
$(function(){
    $('#adate').datepicker({
        autoclose: true
    });

    //Timepicker
    $("#atime").timepicker({
        //showInputs: true
    });
});

function setOrderStat(e){

    var oid = $(e).attr('oid');
    var status = $(e).attr('status');
    console.log(status);
    if(confirm("操作订单号"+ oid +"不可逆，是否继续？")) {

    }else{
        return false;
    }


    $.ajax({
        url: SITE_URL + "telOnline/stateSetting",
        type: "post",
        data:{oid:oid,status:status},
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
 * 修改预约时间前
 * @param e
 */
function updateATimePre(e){
    var  oid = $(e).attr('oid');
    $('#time_update input[name="aid"]').val(oid);
    $.ajax({
        url: SITE_URL + "telOnline/getDetail",
        type: "post",
        data:{oid:oid},
        dataType: 'json',
        success: function (result) {
            $('#time_update #adate').val(result.data.adate);
            $('#time_update #atime').val(result.data.atime);
        }
    });
}

function updateATime(){
    var oid = $('#time_update input[name="aid"]').val();
    var adate = $('#time_update #adate').val();
    var atime = $('#time_update #atime').val();
    $.ajax({
        url: SITE_URL + "telOnline/updateDate",
        type: "post",
        data:{oid:oid,adate:adate,atime:atime},
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