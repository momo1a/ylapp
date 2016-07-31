$(function(){

/*--- "商品分类"智能浮动效果 ---*/
(function(){
	var floatElm = $("#js-smartFloat");
	var t = floatElm.offset().top;
	$(window).scroll(function(){
		var st = $(this).scrollTop();
	    if(!window.XMLHttpRequest) { /*ie6*/
	    	st>t ? floatElm.addClass("smartfloat").css("top",st) : floatElm.removeClass("smartfloat");
	    	return;
	    }
		st>t ? floatElm.addClass("smartfloat") : floatElm.removeClass("smartfloat");
	});
})();	

/*--- 可能感兴趣的商品滚动 ---*/
(function($){
	var $h = $(".horizonal");
	if($h.length<1) return;
	$h.scrollBox({
		parentWidth:995,
		width:199,
		prevSelector:".horizonal-prev",
		nextSelector:".horizonal-next",
		scrollWidth:995,
		auto:5000,
		extend: function(d){
			if(d==="failed"){
				/*如果插件未执行，表示元素不足，则不存在翻页，故而隐藏翻页按钮*/
				$h.find(".horizonal-prev,.horizonal-next").hide();
			}
		}
	});
})(jQuery);

	// 折扣筛选
	$('.count-text').focus(function() {
	    $('.element-box').addClass("active");
	    $('.btn-box').show();
	    if ($("input[name=start]").val() != "" && $("input[name=end]").val() != ""  && $("input[name=start]").val() >= 0 && $("input[name=end]").val() >= 0 && $("input[name=start]").val() <= 8 && $("input[name=end]").val() <= 8) {
	    	$(".sure").addClass('abled').removeAttr('disabled','disabled');
	    }
	    if ($("input[name=start]").val() != "" || $("input[name=end]").val() != "") {
			$(".empty").removeAttr('disabled','disabled');
	    };
	});
	$('.count-text').on('blur keyup', function() {
		if ($("input[name=start]").val() == "" && $("input[name=end]").val() == "") {
			$(".empty").attr('disabled','disabled');
			
		} else {
			$(".empty").removeAttr('disabled','disabled');
			
		}
		if ($(this).is("input[name=start]")) {
			if ($(this).val() == "") {
				$(".sure").removeClass('abled').attr('disabled','disabled');

			} else if ($(this).val() != "" && ($(this).val() < 0 || $(this).val() > 8 || $("input[name=end]").val() < 0 || $("input[name=end]").val() > 8)) {
				$('.ui-error-msg').html('请输入正整数：0-8').css('display','inline-block');
				$(".sure").removeClass('abled').attr('disabled','disabled');

			} else if ($(this).val() != "" && $("input[name=end]").val() == "") {
				$("input[name=end]").focus();
				$(".sure").removeClass('abled').attr('disabled','disabled');
				$('.ui-error-msg').html("");

			} else {
				$(".sure").addClass('abled').removeAttr('disabled','disabled');
				$('.ui-error-msg').html("");
			}
		} else if ($(this).is("input[name=end]")) {
			if ($(this).val() == "") {
				$(".sure").removeClass('abled').attr('disabled','disabled');

			} else if ($(this).val() != "" && ($(this).val() < 0 || $(this).val() > 8 || $("input[name=start]").val() < 0 || $("input[name=start]").val() > 8)) {
				$('.ui-error-msg').html('请输入正整数：0-8').css('display','inline-block');
				$(".sure").removeClass('abled').attr('disabled','disabled');

			} else if ($(this).val() != "" && $("input[name=start]").val() == "") {
				$("input[name=strat]").focus();
				$(".sure").removeClass('abled').attr('disabled','disabled');
				$('.ui-error-msg').html("");

			} else {
				$(".sure").addClass('abled').removeAttr('disabled','disabled');
				$('.ui-error-msg').html("");
			}
		} else {
			$(".sure").addClass('abled').removeAttr('disabled','disabled');
			$('.ui-error-msg').html("");
			return true;
		}
	});
	$(document).on('click', function(e) {
		if ($('.count-text').val() =="" && $(e.target).closest('.click_obj').length <= 0) {
			$('.element-box').removeClass("active");
	    	$('.btn-box').hide();
		}
	});
	$(".empty").click(function() {
		$(".count-text").val("");
		return true;
	});
});	
