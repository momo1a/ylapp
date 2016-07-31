/*=============================
 此处存放的是一些常用的js小功能
===============================*/

/* placeholder shim  文本占位符*/
$(function(){
	if ( !("placeholder" in document.createElement("input")) ) {
		$("input[placeholder]").focus(function() {
			var phd = this.getAttribute("placeholder");
			if (this.value === phd) {
				this.value = "";
				this.style.color = "#000";
			}
		}).blur(function() {
			var phd = this.getAttribute("placeholder");
			if (this.value === "") {
				this.value = phd;
				this.style.color = "#a9a9a9";
			}
		}).blur();
	}
});

/*使用示例：$( '#checkall' ).checkAll( 'table tbody input[checkbox]' ) */
(function($) {
	$.fn.checkAll = function(checkbox) {/*参数：匹配需要被选中的checkbox的选择器;*/
		var $cAll = this.eq(0), $cBox = $(checkbox);
		$cAll.click(function() {
			$cBox.prop("checked", $cAll.prop("checked"));
		});
		$cBox.click(function() {
			var len = $cBox.length, trueLen = $cBox.filter(":checked").length;
			$cAll.prop("checked", len === trueLen);
		});
	}
})(jQuery); 