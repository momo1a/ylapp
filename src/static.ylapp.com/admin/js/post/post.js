/**
 * Created by Administrator on 2016/9/29.
 */

/**
 * 初始化编辑
 * @param e
 */
function editPostPre(e){
    var pid = $(e).attr('pid');
    $('#post_edit input[name="pid"]').val(pid);
    $.ajax({
        url: SITE_URL + "post/getPostDetail",
        type: "post",
        data:{pid:pid},
        dataType: 'json',
        success: function (result) {
            //console.log(result);
            $('#post_edit #postUname').html(result.data.nickname);
            $('#post_edit #postContent').html(result.data.postContent);
            var imgHtml = '';
            if(result.data.img != null){
                $.each(result.data.img,function(index,obj){
                    imgHtml += '<div style="display: inline-block;height: 128px;width: 30%;margin: 0 3px"><img style="width: 100%;height: 100%" src="' + IMG_SERVER + obj + '"/></div>';
                });
            }
            $('#post_edit #postImg').html(imgHtml);
            $('#post_edit #clickCount').val(result.data.clickLikeCount);


        }
    });
}

/**
 * 修改点赞数量
 */
function postSave(){
    var clickCount =  $('#post_edit #clickCount').val();
    var pid =  $('#post_edit input[name="pid"]').val();
    $.ajax({
        url: SITE_URL + "post/postSettingClick",
        type: "post",
        data:{clickCount:clickCount,pid:pid},
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


