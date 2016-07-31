<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理员中心</title>
<?php change_to_minify("style/admin/base.css,style/admin/ui.css,style/admin/index.css,style/admin/allpage.css"); ?>
<?php change_to_minify("javascript/common/jquery/jquery-1.9.1.min.js,javascript/common/jquery/jquery-migrate-1.2.1.js,javascript/common/jquery/jquery.form.min.js,/javascript/admin/jquery.history.js"); ?>
<script src="<?php echo $this->config->item('static_url');?>/javascript/common/My97DatePicker/WdatePicker.js" type="text/javascript"></script>
<script src="<?php echo $this->config->item('static_url');?>/javascript/common/jquery/artDialog/jquery.artDialog.js?skin=default" type="text/javascript"></script>
<script type="text/javascript">
var SITE_URL = "<?php echo site_url('/');?>";
var STATIC_URL = "<?php echo $this->config->item('static_url');?>";
</script>
<?php change_to_minify("javascript/admin/common.js"); ?>
<script type="text/javascript">
$(function(){

	// 删除缓存
	if (sessionStorage && sessionStorage.length) {
		sessionStorage.removeItem('History.store');
	}
	
	/*--- 根据导航切换菜单 ---*/
	$("#js-header-nav").click(function(e){
		var $curNav = $(e.target).closest("li").not(".nav-admin"),
			$menu = $("#js-sidebar").find(".menu");
		$curNav.addClass("nav-current").siblings("li").removeClass("nav-current");
		$menu.filter("[data-js="+$curNav.data("js")+"]").removeClass("hidden").siblings().addClass("hidden");
		// 加载第一个链接地址内容
		$menu.filter("[data-js="+$curNav.data("js")+"]").find('a:first').trigger('click');
	});

	/*--- 二级导航动画 ---jquery 1.9 版本之后没有toggle方法*/
	$(".menu-item dt").click(function(){
		$this = $(this);
		if($this.hasClass("menu-itemHidden-dt")){
			$this.siblings("dd").slideDown(200);
			$this.removeClass("menu-itemHidden-dt");
		}else{
			$this.siblings("dd").slideUp(200,function(){ /*隐藏*/
				$this.addClass("menu-itemHidden-dt");
			});
		}

	});

	/*--- 当前二级导航 ---*/
	(function(){
		var $a = $("#js-sidebar dd a");
		$a.click(function(){
			$a.filter(".menu-item-currentA").removeClass("menu-item-currentA");
			$(this).addClass("menu-item-currentA");
		});
		$a.filter(function(){
			return $(this).attr("href") == window.location.href.substr(0, $(this).attr("href").length);
		}).addClass("menu-item-currentA");
	})();
	$("div#main-wrap").data('url', window.location.href);
	History.pushState({rand:Math.random(),rel:'div#main-wrap',content:JSON.stringify($("div#main-wrap").html())}, "管理员中心", window.location.href);
        //为所有 .ui-form-textDatetime元素 赋予时间选择功能
        $(document.body).on("click",".ui-form-textDatetime",function(evt){
        		var $this = $(this);
                var startTime = function(){
                         WdatePicker({
                            dateFmt: formElm.data("datefmt") || 'yyyy-MM-dd',
                            maxDate:'#F{$dp.$D(\'dateTo\')}'
                         });
                    },
                    endTime = function(){
                         WdatePicker({
                            dateFmt: toElm.data("datefmt") || 'yyyy-MM-dd',
                            minDate:'#F{$dp.$D(\'dateForm\')}'
                         });
                    },
                    fnTime = function(){
                    	 var c = {dateFmt: $this.data("datefmt") || 'yyyy-MM-dd'};
                    	 if($this.data("mindate")){
                    	 	 c.minDate = $this.data("mindate");
                    	 }
                    	 if($this.data("maxdate")){
                    	 	c.maxDate = $this.data("maxdate");
                    	 }
                         WdatePicker(c);
                    };

                var from = $(this).closest("form");
                var dt = from.find('.ui-form-textDatetime');
                // 向下兼容旧版代码
                if (!from.data("newdatecontrol") && dt.length === 2 ){
                    var formElm = dt.eq(0).attr('id','dateForm');
                    var toElm =  dt.eq(1).attr('id','dateTo');
                    formElm.unbind("click", startTime).bind("click", startTime);	// 旧代码存在极大的BUG，请勿再使用
                    toElm.unbind("click", endTime).bind("click", endTime);
                    evt.target.click();
                } else {
                	fnTime.call(this);
                    //$this.unbind("click", fnTime).bind("click", fnTime);
                }

//                evt.target.click();
        });
	//返现金额提示

});
</script>