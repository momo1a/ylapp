$(function(){
    $("#news_add .modal-dialog").css('width','800');
    $("#news_edit .modal-dialog").css('width','800');
    CKEDITOR.replace('content');
    CKEDITOR.replace('contentEdit');
    initFileInput("news-img", "/news/newsAdd",350,350);
    initFileInput("banner", "/news/newsAdd",350,350);
    initFileInput("banner-edit", "/news/newsAdd",350,350);
    initFileInput("news-img-edit", "/news/newsAdd",350,350);
});

function newsSave(){
    var title = $('#news_add input[name="title"]').val();
    var author = $('#news_add input[name="author"]').val();
    var content =  CKEDITOR.instances.content.getData();
    if(!checkInputLength(title,'资讯标题',8,50)){ return false;}
    if(!checkInputLength(author,'作者',2,20)){ return false;}
    if(!checkInputLength(content,'正文',20,20000)){ return false;}
    var newsImgSrc = $('.news-img img').attr('src');
    var bannerImgSrc = $('.banner-img img').attr('src');
    if(!(ImageValidata(newsImgSrc,'资讯缩略图',350,350))){ return false;}
    if(!(ImageValidata(bannerImgSrc,'banner图片',350,350))){ return false;}

    if($("#postPos").val() == -1){
        alert('请选择发布位置');
        return false;
    }

    if($("#isRecmd").val() == -1){
        alert('请选择是否推荐');
        return false;
    }
    if($("#isRecmdIndex").val() == -1){
        alert('请选择是否推荐至首页');
        return false;
    }
    if($("#state").val() == -1){
        alert('请选择状态');
        return false;
    }

    for(instance in CKEDITOR.instances){
        CKEDITOR.instances[instance].updateElement();
    }
    var formData = new FormData(document.getElementById('newsAdd'));
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
    $('#news_add input[name="nid"]').val(0);
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
            console.log(result);
            $('#news_edit input[name="title"]').val(result.data.title);
            $('#news_edit input[name="author"]').val(result.data.author);
            CKEDITOR.instances.contentEdit.setData(result.data.content);
            $('#news_edit .news-img .file-drop-zone-title ').html('<div><img src="'+ IMG_SERVER + result.data.thumbnail +'"/></div>');
            $('#news_edit .banner-img .file-drop-zone-title ').html('<div><img src="'+ IMG_SERVER + result.data.banner +'"/></div>');
            $('#news_edit #postPos').val(result.data.postPos);

        }
    });

}