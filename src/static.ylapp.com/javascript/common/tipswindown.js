///-------------------------------------------------------------------------
//jQuery弹出窗口 By Await [2010-08-12]
//--------------------------------------------------------------------------
/*参数：[可选参数在调用时可写可不写,其他为必写]
----------------------------------------------------------------------------
title:	窗口标题
content:  内容(可选内容为){ text | id | img | url | iframe }
width:	内容宽度
height:	内容高度
drag:  是否可以拖动(ture为是,false为否)
time:	自动关闭等待的时间，为空是则不自动关闭
showbg:	[可选参数]设置是否显示遮罩层(0为不显示,1为显示)
cssName:  [可选参数]附加class名称
------------------------------------------------------------------------*/
//示例:
//------------------------------------------------------------------------
//1.弹出文本信息提示：tipsWindown("提示","text:提示信息内容","250","150","true","","true","msg")
//2.弹出页面中的某个ID的html:tipsWindown("标题","id:testID","300","200","true","","true","id")
//3.弹出图片：tipsWindown("图片","img:图片路径","250","150","true","","true","img")
//4.get加载一个.html文件（也可以是.php/.asp?id=4之类的）：tipsWindown("标题","url:get?test.html","250","150","true","","true","text");
//5.加载一个页面到框架显示：tipsWindown("标题","iframe:http://leotheme.cn","900","580","true","","true","leotheme");
//6.弹出一个不能拖动且没有遮罩背景的文本信息层：tipsWindown("提示","text:提示信息内容","250","150","false","","false","msg")
//7.弹出一个不能拖动，三秒钟自动关闭的层：tipsWindown("提示","text:提示信息内容","250","150","false","3000","true","msg")
//------------------------------------------------------------------------
//var showWindown = true;
function tipsWindown(title, content, width, height, drag, time, showbg, cssName,refreshObj) {
    $("#windown-box").remove(); //请除内容
    var width = width >= 950 ? this.width = 950 : this.width = width;     //设置最大窗口宽度
    var height = height >= 598 ? this.height = 598 : this.height = height;  //设置最大窗口高度
    //if (showWindown == true) {
        var simpleWindown_html = new String;
        simpleWindown_html = "<div id=\"windownbg\" style=\"height:" + $(document).height() + "px ;width:"+ $(document).width() +"px ;filter:alpha(opacity=0);opacity:0;z-index: 999901\"><iframe style=\"width:100%;height:100%;border:none;filter:alpha(opacity=0);opacity:0;\"></iframe></div>";
        simpleWindown_html += "<div id=\"windown-box\">";
        simpleWindown_html += "<div id=\"windown-title\"><h2></h2><span id=\"windown-close\">关闭</span></div>";
        simpleWindown_html += "<div id=\"windown-content-border\"><div id=\"windown-content\">X</div></div>";
        simpleWindown_html += "</div>";
        $("body").append(simpleWindown_html);
       // showWindown = false;
    //}
    contentType = content.substring(0, content.indexOf(":"));
    content = content.substring(content.indexOf(":") + 1, content.length);
    switch (contentType) {
        case "text":
            $("#windown-content").html(content);
            break;
        case "id":
            $("#windown-content").html($("#" + content + "").html());
            break;
        case "img":
            $("#windown-content").ajaxStart(function () {
                $(this).html("<img src='/images/loading.gif' class='loading' />");
            });
            $.ajax({
                error: function () {
                    $("#windown-content").html("<p class='windown-error'>加载数据出错...</p>");
                },
                success: function (html) {
                    $("#windown-content").html("<img src=" + content + " alt='' />");
                }
            });
            break;
        case "url":
            var content_array = content.split("?");
            $("#windown-content").ajaxStart(function () {
                $(this).html("<img src='/images/loading.gif' class='loading' />");
            });
            $.ajax({
                type: content_array[0],
                url: content_array[1],
                data: content_array[2],
                error: function () {
                    $("#windown-content").html("<p class='windown-error'>加载数据出错...</p>");
                },
                success: function (html) {
                    $("#windown-content").html(html);
                }
            });
            break;
        case "iframe":
			/*$("#windown-content").ajaxStart(function () {*/
			/*$(this).html("<img src='/images/loading.gif' class='loading' />");*/
			/*});*/
			/*$.ajax({*/
			/*error: function () {*/
			/*$("#windown-content").html("<p class='windown-error'>加载数据出错...</p>");*/
			/*},*/
			/*success: function (html) {*/
			/*$("#windown-content").html("<iframe src=\"" + content + "\" width=\"100%\" height=\"" + parseInt(height) + "px" + "\" scrolling=\"auto\" frameborder=\"0\" marginheight=\"0\" marginwidth=\"0\"></iframe>");*/
			/*}*/
			/*});*/
			$("#windown-content").html("<iframe src=\"" + content + "\" width=\"100%\" height=\"" + parseInt(height) + "px" + "\" scrolling=\"auto\" frameborder=\"0\" marginheight=\"0\" marginwidth=\"0\"></iframe>");
    }
    $("#windown-title h2").html(title);
    if (showbg == "true") { $("#windownbg").show(); } else { $("#windownbg").remove(); };
    $("#windownbg").animate({ opacity: "0.5" }, "normal"); //设置透明度
    $("#windown-box").show();
    if (height >= 527) {
        $("#windown-title").css({ width: (parseInt(width) + 22) + "px" });
        $("#windown-content").css({ width: (parseInt(width) + 17) + "px", height: height + "px" });
    } else {
        $("#windown-title").css({ width: (parseInt(width) + 10) + "px" });
        $("#windown-content").css({ width: width + "px", height: height + "px" });
    }

    var cw, ch, est = document.documentElement.scrollTop; //窗口的高和宽
    //取得窗口的高和宽
    if (self.innerHeight) {
        cw = self.innerWidth;
        ch = self.innerHeight;
    } else if (document.documentElement && document.documentElement.clientHeight) {
        cw = document.documentElement.clientWidth;
        ch = document.documentElement.clientHeight;
    } else if (document.body) {
        cw = document.body.clientWidth;
        ch = document.body.clientHeight;
    }
    var isIE6 = $.browser.version;
    if (isIE6 == 6.0) {
        $("#windown-box").css({ left: "50%", top: (parseInt((ch) / 2) + est) + "px", marginTop: -((parseInt(height) + 53) / 2) + "px", marginLeft: -((parseInt(width) + 32) / 2) + "px", zIndex: "999999" });
    } else {
        $("#windown-box").css({ left: "50%", top: "50%", marginTop: -((parseInt(height) + 53) / 2) + "px", marginLeft: -((parseInt(width) + 32) / 2) + "px", zIndex: "999999" });
    };
    var Drag_ID = document.getElementById("windown-box"), DragHead = document.getElementById("windown-title");

    var moveX = 0, moveY = 0, moveTop, moveLeft = 0, moveable = false;
    if (isIE6 == 6.0) {
        moveTop = est;
    } else {
        moveTop = 0;
    }
    var sw = Drag_ID.scrollWidth, sh = Drag_ID.scrollHeight;
    DragHead.onmouseover = function (e) {
        if (drag == "true") { DragHead.style.cursor = "move"; } else { DragHead.style.cursor = "default"; }
    };
    DragHead.onmousedown = function (e) {
        $("#windown-box").css({ opacity: "0.5" }, "normal");
        if (drag == "true") { moveable = true; } else { moveable = false; }
        e = window.event ? window.event : e;
        var ol = Drag_ID.offsetLeft, ot = Drag_ID.offsetTop - moveTop;
        moveX = e.clientX - ol;
        moveY = e.clientY - ot;
        document.onmousemove = function (e) {
            if (moveable) {

                e = window.event ? window.event : e;
                var x = e.clientX - moveX;
                var y = e.clientY - moveY;
                if (x > 0 && (x + sw < cw) && y > 0 && (y + sh < ch)) {
                    Drag_ID.style.left = x + "px";
                    Drag_ID.style.top = parseInt(y + moveTop) + "px";
                    Drag_ID.style.margin = "auto";
                }
            }
        }
        document.onmouseup = function () { moveable = false; $("#windown-box").css({ opacity: "1" }, "normal"); };
        Drag_ID.onselectstart = function (e) { return false; }
    }
    $("#windown-content").attr("class", "windown-" + cssName);
    var closeWindown = function () {
        $("#windownbg").remove();
        $("#windown-box").fadeOut("slow", function () { $(this).remove(); });
    }
    if (time == "" || typeof (time) == "undefined") {
        $("#windown-close").click(function () {
            if ( refreshObj && refreshObj.refresh ) { window.location.reload(); };
            $("#windownbg").remove();
            $("#windown-box").remove();
        });
    } else {
        setTimeout(closeWindown, time);
    }
}
function SeeLogistics(tid, uid) {
    tipsWindown("<strong>查看物流单:</strong>", "text:<div class='load1'>加载中，请稍候...</div>", "450", "200", "true", "", "true", "msg");
    $.post("/Ajax/SeeLogistics_" + tid + "_" + uid + "_" + Math.random() + ".htm", {}, function (data) {
        $("#windown-content").html(data);
    });
}
function ShowBox(id, showId) {
    var left = $("#" + id).offset().left;
    var top = $("#" + id).offset().top;
    var wight = $("#" + id).width();
    var height = $("#" + id).height();
    $("#" + showId).show();
    $("#" + showId).css({ top: top + height + 5, left: left, width: wight });
}
function CloseWin() {
    $("#windownbg").remove();
    $("#windown-box").fadeOut("slow", function () { $(this).remove(); });
}
function Loadding() {
    CloseWin();
    $("<div id=\"windownbg\" style=\"height:" + $(document).height() + "px;filter:alpha(opacity=0);opacity:0;z-index: 999901;\"><iframe style=\"width:100%;height:100%;border:none;filter:alpha(opacity=0);opacity:0;\"></iframe></div>").appendTo($("body"));
    $("#windownbg").show();
    showTipsForJquery("请稍候...", null, "TLloading");
}
function CloseLoad() {
    $("#windownbg").detach();
    $('#tips_box').detach();
}
function Succeed() {
    showTipsForJquery("操作成功", 1, "popTitLineR");
}
function FlagAffirm(tid, jid, uid, word) {
    tipsWindown("<strong>当前流程状态:</strong>", "text:<div class='load2'>加载中，请稍候...</div>", "380", "150", "true", "", "true", "msg");
    $.post("/Ajax/GetDateDiff.htm", { jid: jid, type: 12 }, function (data) {
        if (data != "-1") {
            var str = "<p style='color:#F00;text-align:left;'>当前流程状态： 买家已提交试用报告和下单号，等待您的确认。</p><p style='text-align:left;line-height:25px;'>您还有" + data + "来完成本次试用的确认完成。 逾期未操作，系统将自动确认，并将试用担保金支付给买家。</p><p><a href='/cReport/AuditReport_" + jid + ".htm' class='Fbtn' target='_blank'>确认报告和单号</a></p>";
        } else {
            var str = "<div class='load2' style='color:#FF00FF;'>此状态已过期！</div>";
        }
        $("#windown-content").html(str);
    });
}
function GetDateDiff(jid, type, mes) {
    $.post("/Ajax/GetDateDiff.htm", { jid: jid, type: 11 }, function (data) {
        if (data != "-1") {
            var str = "<p style='color:#F00;text-align:left;'>当前剩余时间：</p><p style='text-align:left;line-height:25px;'>您还有" + data + "来完成本次下单操作完成。" + mes + "</p>";
        }
        else {
            var str = "<p style='color:#FF00FF;'>已过期！</p>";
        }
        tipsWindown("<strong>剩余时间:</strong>", "text:" + str, "380", "150", "true", "", "true", "msg");
    });
}
function GetPassLastDate(jid) {
    $.post("/Ajax/GetPassLastDate_" + jid + ".htm", function (data) {
        if (data != "-1") {
            var str = "<p style='color:#F00;text-align:left;'>当前剩余时间：</p><p style='text-align:left;line-height:25px;'>您还有" + data + "来完成本次下单操作完成。逾期未操作，商家将有权取消您的通过资格。</p>";
        }
        else {
            var str = "<p style='color:#FF00FF;text-align:left;'>您获得试用资格后已经7天未去下单，商家有可能会取消您的试用资格，请尽快去下单！</p><p><a href='javascript:CloseWin()' class='Fbtn'>我知道了</a></p>";
        }
        tipsWindown("<strong>剩余时间:</strong>", "text:" + str, "380", "150", "true", "", "true", "msg");
    });
}
function showHint(strContent) {
    $(strContent).appendTo("body");
    function pstn() {
        winh = $(window).height();
        winw = $(window).width() - 10;
        wint = $(window).scrollTop();
        var docW = $( document ).width();
        var docH = $( document ).height();
        $('#Shade').height(docH).width(docW);
        Ht = $('#Hint');
        Ht.offset({ top: (winh - Ht.height()) * 0.5 + wint, left: (winw - Ht.width()) * 0.5 });
    };
    pstn();
    $(window).scroll(function () {
        pstn();
    });
    $(window).resize(function () {
        pstn();
    })
};
function delHint() {
    $('#Shade').detach();
    $('#Hint').detach();
}