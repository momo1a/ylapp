!function($, shs){
    // 静态文件域名获取
    var Url = function(){
            var site = shs.site("static");
            var ver  = "?v=" + shs.sys_version();
            return function(uri){
                return site + uri + ver;
            };
        }();
    // 批量包定义，v1.2.1版本才支持
    Loader.define(
        {
            // 挂件集
            name : "widgets",
            queue: false,
            files: [
                // 顶部用户条
                Url("common/widget/topbar/css/style.css"),
                // 压缩打包
                shs.minify(
                    // 顶部状态栏
                    "common/widget/topbar/widget.js",
                    // 返回顶部按钮
                    "common/widget/backtotop/backtotop.widget.min.js"
                )
            ],
            // 自动运行
            auto: true
        },
        {
            // 框架js
            name : "frame",
            files: Url("buyer/common/js/frame.js"),
            auto: true
        },
        {
            // 弹窗插件
            name : "artDialog",
            queue: false,
            files: [
                Url("common/js/jquery/artDialog/skins/zhonghuasuan.css"),
                Url("common/js/jquery/artDialog/jquery.artDialog.js")
            ]
        },
        {
            // 日期选择器插件
            name : "WdatePicker",
            files: Url("common/js/My97DatePicker/WdatePicker.js")
        },
        {
            // 时间操作插件
            name : "Time",
            files: Url("ommon/js/object/Time.object.min.js")
        }
    );
}($, shs);
