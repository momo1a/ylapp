$(function(){
    $("#news_add .modal-dialog").css('width','800');
    CKEDITOR.replace('content');
    initFileInput("news-img", "/News/newsAdd",350,350);
    initFileInput("banner", "/News/newsAdd",350,350);
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
        url: SITE_URL + "News/newsAdd",
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