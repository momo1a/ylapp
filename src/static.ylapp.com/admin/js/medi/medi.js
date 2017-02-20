$(function(){
    $("#news_add .modal-dialog").css('width','800');
    $("#news_edit .modal-dialog").css('width','800');
    CKEDITOR.replace('content');
    CKEDITOR.replace('contentEdit');
    initFileInput("medi-img", "/news/newsAdd",350,350);
    initFileInput("banner-1", "/news/newsAdd",350,350);
    initFileInput("banner-2", "/news/newsAdd",350,350);
    initFileInput("banner-3", "/news/newsAdd",350,350);
    initFileInput("banner-4", "/news/newsAdd",350,350);
    initFileInput("banner-5", "/news/newsAdd",350,350);

    initFileInput("banner-edit-1", "/news/newsAdd",350,350);
    initFileInput("banner-edit-2", "/news/newsAdd",350,350);
    initFileInput("banner-edit-3", "/news/newsAdd",350,350);
    initFileInput("banner-edit-4", "/news/newsAdd",350,350);
    initFileInput("banner-edit-5", "/news/newsAdd",350,350);

    initFileInput("news-img-edit", "/news/newsAdd",350,350);
    $(".fileinput-remove-button").css('display','none');  // 隐藏图片上传移除按钮

    // 编辑药品页面 删除banner图片
    $("#medi_edit .banner-img  .fileinput-remove").bind('click',function(){
        //console.log('test')
        //console.log($(this).parents('.banner-img').attr('id'),$('#medi_edit input[name="mid"]').val());
        var bannerKey = $(this).parents('.banner-img').attr('id');
        var mid = $('#medi_edit input[name="mid"]').val();
        $.ajax({
            url: SITE_URL + "medicine/delMedibanner",
            type: "post",
            data: {mid: mid,bannerKey:bannerKey},
            dataType: 'json',
            success: function (result) {
                ;;
            }
        });
    })
});

function mediSave(){
    var mid = $('input[name="mid"]').val();
    var name = mid == 0 ? $('#medi_add input[name="name"]').val() : $('#medi_edit input[name="name"]').val();
    var outline = mid == 0 ? $('#medi_add input[name="outline"]').val() : $('#medi_edit input[name="outline"]').val();
    var content =  mid == 0 ?  CKEDITOR.instances.content.getData() : CKEDITOR.instances.contentEdit.getData();
    if(!checkInputLength(name,'品名',2,25)){ return false;}
    if(!checkInputLength(outline,'概述',8,200)){ return false;}
    if(!checkInputLength(content,'正文',20,20000)){ return false;}
    var mediImgSrc = $('.medi-img img').attr('src');
    var bannerImgSrc = $('.banner-img img').attr('src');
    if(!(ImageValidata(mediImgSrc,'药品缩略图',350,350))){ return false;}
    if(!(ImageValidata(bannerImgSrc,'banner图片',290,135))){ return false;}
    if(!(Bilivalidate(bannerImgSrc,'banner图片',15,7))){return false;}  // 宽高比
    for(instance in CKEDITOR.instances){
        CKEDITOR.instances[instance].updateElement();
    }
    var fromName = mid == 0 ?  "mediAdd" : "mediEdit";
    var formData = new FormData(document.getElementById(fromName));
    $.ajax({
        url: SITE_URL + "medicine/mediSave",
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


function mediAddPre(){
    $('input[name="mid"]').val(0);
}
/**
 * 编辑资讯初始化页面
 * @param e
 */
function editMediPre(e){
    var mid = $(e).attr('mid');
    $('input[name="mid"]').val(mid);

    $.ajax({
        url: SITE_URL + "medicine/getMedicineDetail",
        type: "post",
        data:{mid:mid},
        dataType: 'json',
        success: function (result) {
            //console.log(result);
            $('#medi_edit input[name="name"]').val(result.data.name);
            $('#medi_edit input[name="outline"]').val(result.data.outline);
            CKEDITOR.instances.contentEdit.setData(result.data.content);
            $('#medi_edit .medi-img .file-drop-zone-title ').html('<div><img  style="width: 100%;height: 100%" src="'+ IMG_SERVER + result.data.thumbnail +'"/><input type="hidden" name="origin-news-img" value="'+ result.data.thumbnail +'"/></div>');
            //console.log(result.data.banner);
            $.each(result.data.banner,function(i,d){
                //console.log(i);
                $('#medi_edit .'+ i +' .file-drop-zone-title ').html('<div><img  style="width: 100%;height: 100%" src="'+ IMG_SERVER + d +'"/><input type="hidden" name="origin-news-banner" value="'+ d +'"/></div>');
            });
            $('#medi_edit #cateEdit').val(result.data.cid);
        }
    });

}


/**
 * 添加药品分类
 * @param e
 */
function addCate(){
    var cateName = $('#cate_add input[name="cateName"]').val();

    $.ajax({
        url: SITE_URL + "medicine/addCate",
        type: "post",
        data:{cateName:cateName},
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




function MediDel(){
    var mid = $('#medi_del input[name="mid"]').val();

    $.ajax({
        url: SITE_URL + "medicine/mediDel",
        type: "post",
        data:{mid:mid},
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




