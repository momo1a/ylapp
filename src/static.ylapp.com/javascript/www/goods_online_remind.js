/*底部商品上线时间提示横幅*/
$(document).ready(function(){
	var isCloce = ($.cookie("isCloseRemind")=="1")?true:false;
	if(isCloce)return;/*如果被关闭了，直接返回不执行下文*/
	var html = '\
	<div class="online-remind">\
		<div class="online-remind-content">\
			<span class="online-remind-info"></span>\
			<a class="online-remind-btn" target="_blank" href="http://list.zhonghuasuan.com/hot/"></a>\
			<span class="online-remind-qrcode"></span>\
		</div>\
		<span class="online-remind-close"></span>\
	</div>';
	var f = $(html);
	$("body").append(f);
	var ie6 = (navigator.userAgent.indexOf("MSIE")>0) ? (Number(navigator.userAgent.match(/MSIE *([0-9\.]+);/i)[1])<7) : false;
	$(window).scroll( function(){
		if(isCloce)return;
		if($(document).scrollTop()>0){
			f.show();
			if(ie6){	/*ie6下是absolute定位*/
				f.css('top', ($(document).scrollTop()+$(window).height()-f.height())+"px");
			}
		}
		else{
			f.hide();
		}
	});
	f.find(".online-remind-close,.online-remind-btn").bind("click",function(){
		f.hide();
		isCloce=true;
		/*写入cookie*/
		$.cookie("isCloseRemind", 1, { expires: 1 });
	});
});