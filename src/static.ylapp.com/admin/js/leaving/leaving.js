/**
 * Created by Administrator on 2016/10/3 0003.
 */

function setOrderStat(e){

    var oid = $(e).attr('oid');
    var status = $(e).attr('status');
    if(confirm("操作订单号"+ oid +"不可逆，是否继续？")) {

    }else{
        return false;
    }


    $.ajax({
        url: SITE_URL + "leavMsg/stateSetting",
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