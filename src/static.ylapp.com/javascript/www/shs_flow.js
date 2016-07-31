/*!
 * jQuery Cookie Plugin v1.3.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2013 Klaus Hartl
 * Released under the MIT license
 */
(function (factory) {
	if (typeof define === 'function' && define.amd) {
		// AMD. Register as anonymous module.
		define(['jquery'], factory);
	} else {
		// Browser globals.
		factory(jQuery);
	}
}(function ($) {

	var pluses = /\+/g;

	function raw(s) {
		return s;
	}

	function decoded(s) {
		return decodeURIComponent(s.replace(pluses, ' '));
	}

	function converted(s) {
		if (s.indexOf('"') === 0) {
			// This is a quoted cookie as according to RFC2068, unescape
			s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
		}
		try {
			return config.json ? JSON.parse(s) : s;
		} catch(er) {}
	}

	var config = $.cookie = function (key, value, options) {

		// write
		if (value !== undefined) {
			options = $.extend({}, config.defaults, options);

			if (typeof options.expires === 'number') {
				var days = options.expires, t = options.expires = new Date();
				t.setDate(t.getDate() + days);
			}

			value = config.json ? JSON.stringify(value) : String(value);

			return (document.cookie = [
				config.raw ? key : encodeURIComponent(key),
				'=',
				config.raw ? value : encodeURIComponent(value),
				options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
				options.path    ? '; path=' + options.path : '',
				options.domain  ? '; domain=' + options.domain : '',
				options.secure  ? '; secure' : ''
			].join(''));
		}

		// read
		var decode = config.raw ? raw : decoded;
		var cookies = document.cookie.split('; ');
		var result = key ? undefined : {};
		for (var i = 0, l = cookies.length; i < l; i++) {
			var parts = cookies[i].split('=');
			var name = decode(parts.shift());
			var cookie = decode(parts.join('='));

			if (key && key === name) {
				result = converted(cookie);
				break;
			}

			if (!key) {
				result[name] = converted(cookie);
			}
		}

		return result;
	};

	config.defaults = {};

	$.removeCookie = function (key, options) {
		if ($.cookie(key) !== undefined) {
			// Must not alter options, thus extending a fresh object...
			$.cookie(key, '', $.extend({}, options, { expires: -1 }));
			return true;
		}
		return false;
	};

}));

/*加入收藏*/
var addBookmark =  function(){ 
    var ctrl = (navigator.userAgent.toLowerCase()).indexOf('mac') != -1 ? 'Command/Cmd' : 'Ctrl';
    try{
        if (document.all) { /*IE*/
            try {
                window.external.toString(); /*360浏览器不支持window.external，无法收藏*/
                alert("360浏览器功能限制!您可以尝试通过浏览器菜单栏或快捷键 ctrl+D 试试。");
            }catch (e){
                try{
                    window.external.addFavorite(window.location, document.title);
                }catch (e){
                    window.external.addToFavoritesBar(window.location, document.title);  /*IE8+ ?*/
                }
            }
        }else if (window.sidebar) { /*firfox等*/
            window.sidebar.addPanel(document.title, window.location, "");
            /*如果文档里出现名为sidebar的class，那么代码有出错的可能*/
        }else {
            alert('您可以尝试通过快捷键: '+ctrl+' + D,添加书签');
        }
    }catch (e){
        window.alert('您可以尝试通过快捷键: '+ctrl+' + D,添加书签!');
    }
};


$(function(){

if($.cookie("isShowFlow") !== "1"){
	showMask(); 
	showFlow(); 
	$.cookie("isShowFlow", 1, { expires: 1 });
}
/*--- 遮罩层模块 ---*/
function showMask(){
	/*定位*/
	var mask = $('<div id="shs_flow_mask" style="position: fixed; top: 0px; left: 0px; z-index: 100000000; width: 100%; height: 100%; background-color: rgb(0, 0, 0); opacity: 0.8; filter: Alpha(Opacity=80); *zoom:1;"></div>');
	if(!-[1,] && !window.XMLHttpRequest){ // IE 6
		var bt = document.body.style,
			win =  $(window);
		bt.position="relative";
		bt.zoom=1;
		mask.css("position","absolute").height( win.height() ).width( document.body.clientWidth );
		win.scroll(function(){
			mask.css({
				top:win.scrollTop(),
				left:win.scrollLeft()
			});
		}).triggerHandler("scroll");
	}
	/*置入*/
	mask.appendTo("body");
};

/*--- 流程动画模块 ---*/
function showFlow(){
	/*定位*/
	var win = $(window);
	var view = $('<div id="shs_flow_view" data-flow="1" style="position: fixed; z-index: 100000001; width:900px; height:600px; overflow: hidden;"><ol style="width:2700px; overflow: hidden;*zoom:1;">'
		+'<li style="float:left; width:900px; height:600px; overflow: hidden; background-position: 105px 0;"></li>'
		+'<li style="float:left; width:900px; height:600px; overflow: hidden; background-position: 68px -600px;"></li>'
		+'<li style="float:left; width:900px; height:600px; overflow: hidden; background-position: 103px -1200px;">'
		+'<span title="收藏" style="display:none; position:absolute; left:261px; bottom:117px; width:171px; height:61px; cursor:pointer;" onclick="addBookmark()" id="shs_flow_addBookmark"></span>'
		+'<a title="立即体验" style="display:none; position:absolute; left:479px; bottom:118px; width:171px; height:61px; cursor:pointer;" href="http://www.zhonghuasuan.com/" id="shs_flow_go"></a>'
		+'</li></ol>'
		+'<span title="返回" id="shs_flow_prev" style="display:inline-block;position:absolute;left:126px;bottom:34px; width:94px; height:40px; padding:10px; cursor:pointer; display:none;"></span>'
		+'<span title="继续" id="shs_flow_next" style="display:inline-block;position:absolute;right:127px;bottom:34px; width:94px; height:40px;padding:10px; cursor:pointer"></span>'
		+'<span title="关闭" id="shs_flow_close" style="display:block; position:absolute; top:100px; right:65px; width:40px; height:40px; background-position: 0 bottom; cursor:pointer;"></span></div>');
	view.css({
		top:win.height()/2-view.height()/2,
		left:win.width()/2-view.width()/2
	});
	if(!-[1,] && !window.XMLHttpRequest){ // IE6
		var bt = document.body.style,
			win =  $(window);
		bt.position="relative";
		bt.zoom=1;
		view.css("position","absolute");
		win.scroll(function(){
			view.css({
				top:win.scrollTop() + (win.height()/2-view.height()/2),
				left:win.scrollLeft() + (win.width()/2-view.width()/2)
			});
		}).triggerHandler("scroll");
	}
	/*置入*/
	view.appendTo("body");
	/*切换*/
	$("#shs_flow_prev").click(function(){
		var num = parseInt(view.data("flow"));
		num-=1;
		view.data("flow",num);
		view.find("ol").fadeOut(400,function(){
			$(this).css("margin-left","+=900px");
		}).fadeIn(400);
		if( num === 1 ){ this.style.display = "none"; }
		if (num < view.find("li").length) {
		    document.getElementById("shs_flow_next").style.display = "inline-block";
		    document.getElementById("shs_flow_addBookmark").style.display = "none";
		    document.getElementById("shs_flow_go").style.display = "none";
		}
	});
	$("#shs_flow_next").click(function(){
		var num = parseInt(view.data("flow"));
		num+=1;
		view.data("flow",num);
		view.find("ol").fadeOut(400,function(){
			$(this).css("margin-left","-=900px");
		}).fadeIn(400);
		if( num === view.find("li").length ){ 
			this.style.display = "none"; 
			document.getElementById("shs_flow_addBookmark").style.display="block";
			document.getElementById("shs_flow_go").style.display="block";
		}
		if( num > 1 ){ document.getElementById("shs_flow_prev").style.display="inline-block"; }
	});
	$("#shs_flow_close").hover(function(){
		this.style.backgroundPosition = "-50px bottom";
	},function(){
		this.style.backgroundPosition = "0 bottom";
	});
	/*关闭*/
	var closeElm = $("#shs_flow_go, #shs_flow_close");
	var missElm = $("#shs_flow_mask, #shs_flow_view");
	closeElm.click(function(){
		missElm.animate( {top:-$(window).height()},300,function(){
			missElm.remove();
		});
		return false;
	});

};


});
