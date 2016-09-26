/**
 * Created by Administrator on 2016/9/26 0026.
 */
function delBannerPre(e){
    var nid = $(e).attr('nid');
    $('#banner_del input[name="nid"]').val(nid);
}


function bannerDel(){
    var  nid = $('#banner_del input[name="nid"]').val();

    $.ajax({
        url: SITE_URL + "client_index/delBanner",
        type: "post",
        data: {nid:nid},
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