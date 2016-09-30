/**
 * Created by Administrator on 2016/9/30.
 */

/**
 * 设置提现状态
 * @param e
 */
function setCashStat(e){
    var tid = $(e).attr('tid');
    var status = $(e).attr('status');


    if(confirm("操作编号"+ tid +"不可逆，是否继续？")) {

    }else{
        return false;
    }


    $.ajax({
        url: SITE_URL + "cash/stateSetting",
        type: "post",
        data:{tid:tid,status:status},
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