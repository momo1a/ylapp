// <out: ../widget.js>
/**
 * 侧边用户条挂件
 * Author: 陆楚良
 *  Email: lu_chuliang@sina.com
 *     QQ: 874449204
 *  Build: {date}
 *
 *    依赖:
 *         reset.css
 *         function.css
 *         jQuery.js
 *         shs.js
 *         CL.js
 *         CL_Loader.js
 *         template-native.js   (不存在时自加载依赖)
 *         json2.js             (低版本浏览器下自加载依赖)
 *         jquery.inView.js     (页面存在楼层但是不存在该插件时自加载依赖)
 *         style.css            (当前挂件)
 */
var Widgets = Widgets || {};
!function(window, Widgets, undefined){
    var $      = window.jQuery;
    var shs    = window.shs;
    var CL     = window.CL;
    var Loader = window.CL_Loader;
    CL.$ = $;
    // 低版本浏览器直接忽略，iPhone和iPad会锁住界面导致链接无法正常使用，因此也忽略
    if($("html").hasClass("ie6") || /iphone|ipad/i.test(navigator.userAgent)){
        return;
    }
    // <include: model.sdbar.js>
    // <include: view.sdbar.js>
    // <include: view.dialog.js>
    // <include: view.detail.js>
    // <include: view.login.js>
    // <include: view.member.js>
    // <include: view.asset.js>
    // <include: controller.sdbar.js>
    var requires = [];
    window.JSON || requires.push(shs.static("common/js/json2.js"));
    window.template || requires.push(shs.static("common/js/template-native.js"));
    Loader.add(requires).ready(function(){
        Widgets.sdbar = new sdbarController();
    });
}(window, Widgets);
