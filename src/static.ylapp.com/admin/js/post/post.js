/**
 * Created by Administrator on 2016/9/29.
 */

function editPostPre(e){
    var pid = $(e).attr('pid');
    $.ajax({
        url: SITE_URL + "post/getPostDetail",
        type: "post",
        data:{pid:pid},
        dataType: 'json',
        success: function (result) {
            console.log(result);
            $('#post_edit #postUname').html(result.data.nickname);
            $('#post_edit #postContent').html(result.data.postContent);
            if(result.data.)
        }
    });
}
