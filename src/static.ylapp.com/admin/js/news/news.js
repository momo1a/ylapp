$(function(){
    $("#news_add .modal-dialog").css('width','800');
    $("#news_edit .modal-dialog").css('width','800');
    CKEDITOR.replace('content');
    CKEDITOR.replace('contentEdit');
    initFileInput("news-img", "/news/newsAdd",2000,2000);
    initFileInput("banner", "/news/newsAdd",2000,2000);
    initFileInput("banner-edit", "/news/newsAdd",2000,2000);
    initFileInput("news-img-edit", "/news/newsAdd",2000,2000);
    $(".fileinput-remove-button").css('display','none');  // 隐藏图片上传移除按钮
});

function newsSave(){
    var nid = $('input[name="nid"]').val();
    var title = nid == 0 ? $('#news_add input[name="title"]').val() : $('#news_edit input[name="title"]').val();
    var author = nid == 0 ? $('#news_add input[name="author"]').val() : $('#news_edit input[name="author"]').val();
    var tag = nid == 0 ? $('#news_add input[name="tag"]').val() : $('#news_edit input[name="tag"]').val();
    var content =  nid == 0 ?  CKEDITOR.instances.content.getData() : CKEDITOR.instances.contentEdit.getData();
    //if(!checkInputLength(title,'资讯标题',8,50)){ return false;}
    if(!checkInputLength(author,'作者',2,20)){ return false;}
    if(!checkInputLength(tag,'标签',2,6)){ return false;}
    if(!checkInputLength(content,'正文',20,20000)){ return false;}
    //var newsImgSrc = $('.news-img img').attr('src');
    //var bannerImgSrc = $('.banner-img img').attr('src');
    //if(!(ImageValidata(newsImgSrc,'资讯缩略图',350,350))){ return false;}
    //if(!(ImageValidata(bannerImgSrc,'banner图片',350,350))){ return false;}
    var pos = nid == 0 ? 'postPo' : 'postPoEdit';
    if($("#"+ pos).val() == -1){
        alert('请选择发布位置');
        return false;
    }
    var recmd = nid == 0 ? 'isRecmd' : 'isRecmdEdit';
    if($("#"+recmd).val() == -1){
        alert('请选择是否推荐');
        return false;
    }
    var  recmdIndex = nid == 0 ? 'isRecmdIndex' : 'isRecmdIndexEdit';
    if($("#"+recmdIndex).val() == -1){
        alert('请选择是否推荐至首页');
        return false;
    }

    var  state = nid == 0 ? 'state' : 'stateEdit';
    if($("#" + state).val() == -1){
        alert('请选择状态');
        return false;
    }

    for(instance in CKEDITOR.instances){
        CKEDITOR.instances[instance].updateElement();
    }
    var fromName = nid == 0 ?  "newsAdd" : "newsEdit";
    var formData = new FormData(document.getElementById(fromName));
    $.ajax({
        url: SITE_URL + "news/newsAdd",
        type: "post",
        data:formData,
        contentType:false,
        processData:false,
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


function newsAddPre(){
    $('input[name="nid"]').val(0);
}
/**
 * 编辑资讯初始化页面
 * @param e
 */
function editNews(e){
    var nid = $(e).attr('nid');
    $('#news_edit input[name="nid"]').val(nid);

    $.ajax({
        url: SITE_URL + "news/getNewsDetail",
        type: "post",
        data:{nid:nid},
        dataType: 'json',
        success: function (result) {
            //console.log(result);
            $('#news_edit input[name="title"]').val(result.data.title);
            $('#news_edit input[name="author"]').val(result.data.author);
            $('#news_edit input[name="tag"]').val(result.data.tag);
            CKEDITOR.instances.contentEdit.setData(result.data.content);
            $('#news_edit .news-img .file-drop-zone-title ').html('<div><img  style="width: 100%;height: 100%" src="'+ IMG_SERVER + result.data.thumbnail +'"/><input type="hidden" name="origin-news-img" value="'+ result.data.thumbnail +'"/></div>');
            $('#news_edit .banner-img .file-drop-zone-title ').html('<div><img  style="width: 100%;height: 100%" src="'+ IMG_SERVER + result.data.banner +'"/><input type="hidden" name="origin-news-banner" value="'+ result.data.banner +'"/></div>');
            $('#news_edit #postPosEdit').val(result.data.postPos);
            $('#news_edit #isRecmdEdit').val(result.data.isRecmd);
            $('#news_edit #isRecmdIndexEdit').val(result.data.isRecmdIndex);
            $('#news_edit #stateEdit').val(result.data.state);
        }
    });

}

/**
 * 删除前
 * @param e
 */
function newsDelPre(e){
    var nid = $(e).attr('nid');
    $('#news_del input[name="nid"]').val(nid);
}

function newsDel(){
    var nid = $('#news_del input[name="nid"]').val();

    $.ajax({
        url: SITE_URL + "news/newsDel",
        type: "post",
        data:{nid:nid},
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