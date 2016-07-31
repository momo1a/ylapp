/* 搜索框效果实现 */
!function(){
    $sear = $("#J_search");

    $sel  = $sear.find(".J_sel");
    $sel.hover(
        function(){$sel.addClass("z-open");},
        function(){$sel.removeClass("z-open")}
    );
    $sel.find("a").click(function(){

        $this = $(this);
        if(!$this.hasClass("z-crt")){
            $sel.removeClass("z-open");
            $sel.find("a").removeClass("z-crt");
            $this.addClass("z-crt").prependTo($sel);
            $sear.find(".J_type").val($this.data("type"));
        }
    });
    $sear.find("form").submit(function(){
        return $sear.find(":text").val()!=="";
    });
}();
