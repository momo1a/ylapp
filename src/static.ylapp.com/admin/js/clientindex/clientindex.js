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

function addRollingMsg(){
    var  content = $('#rolling_add input[name="content"]').val();

    $.ajax({
        url: SITE_URL + "client_index/rollingAdd",
        type: "post",
        data: {content:content},
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
 * 删除前
 * @param e
 */
function rollingDelPre(e){
    var nid = $(e).attr('rid');
    $('#rolling_del input[name="rid"]').val(nid);
}

function rollingDel(){
    var rid = $('#rolling_del input[name="rid"]').val();

    $.ajax({
        url: SITE_URL + "client_index/rollingDel",
        type: "post",
        data:{rid:rid},
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

function editRollingPre(e){
    var rid = $(e).attr('rid');
    $('#rolling_edit input[name="rid"]').val(rid);
    $.ajax({
        url: SITE_URL + "client_index/getRolling",
        type: "post",
        data:{rid:rid},
        dataType: 'json',
        success: function (result) {
            if(result.code == 0){
                $('#rolling_edit input[name="content"]').val(result.data.content);
            }else{
                alert(result.msg);
            }
        }
    });

}

function editRollingMsg(){
    var rid = $('#rolling_edit input[name="rid"]').val();
    var content = $('#rolling_edit input[name="content"]').val();
    $.ajax({
        url: SITE_URL + "client_index/editRolling",
        type: "post",
        data:{rid:rid,content:content},
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