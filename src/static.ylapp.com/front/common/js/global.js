// 全局
var shs = (function() {
	function initSearch() {
		/* 搜索状态 */
		var form = $('form.header-search');
		var input = form.find(':text[name=key]');
		$("#js_header-search-type").hover(function() {
			var baba = $(this);
			baba.addClass("header-search-typeHover");
			baba.find("li").click(function() {
				var className = "header-search-type-selected";
				var searchType = $(this).attr("data-searchType");
				$(this).prependTo(baba).addClass(className).siblings("li").removeClass(className);
				form.find("input[name=type]").val(searchType);
				input.focus();
			});

		}, function() {
			$(this).removeClass("header-search-typeHover");
		});
		var searchT = form.find('input[name=type]').val();
		$("#js_header-search-type li").eq(searchT == 'shop' ? 0 : 1).click();
		input.focus();
		form.submit(function() {
			var val = $.trim(input.val());
			if (!val) {
				input.val('').focus();
				return false;
			}
		});
	}

	var domain = location.host.replace(/(\w+\.)*(\w+\.com)/, '.$2');

	return {
		init : function() {
			$(function() {
				initSearch();
				shs.user.init();
			});
		},
		site : function(site) {
			return 'http://' + site + domain + '/';
		},
		url : {
			reg : 'http://reg.shikee.com/reg/doreg/?uType=1'
		},
		query : function(name) {
			var result = location.search.match(new RegExp("[\?\&]" + name + "=([^\&]+)", "i"));
			if (result == null || result.length < 1) {
				return '';
			}

			return result[1];
		},
		cookie : function(name, value, options) {
			if ( typeof value != 'undefined') {
				options = options || {};
				if (value === null) {
					value = '';
					options = $.extend({}, options);
					options.expires = -1;
				}
				var expires = '';
				if (options.expires && ( typeof options.expires == 'number' || options.expires.toUTCString)) {
					var date;
					if ( typeof options.expires == 'number') {
						date = new Date();
						date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
					} else {
						date = options.expires;
					}
					expires = '; expires=' + date.toUTCString();
				}
				var path = options.path ? '; path=' + (options.path) : '';
				var domain = options.domain ? '; domain=' + (options.domain) : '';
				var secure = options.secure ? '; secure' : '';
				document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
			} else {
				var cookieValue = null;
				if (document.cookie && document.cookie != '') {
					var cookies = document.cookie.split(';');
					for (var i = 0; i < cookies.length; i++) {
						var cookie = jQuery.trim(cookies[i]);
						if (cookie.substring(0, name.length + 1) == (name + '=')) {
							cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
							break;
						}
					}
				}
				return cookieValue;
			}
		}
	};
})();

shs.init();

// 用户类
shs.user = (function() {
	var wrap;

	return {
		init : function() {
			wrap = $('.login-info');
			var user = this.info();
			if (!user) {
				wrap.show();
				return;
			}

			var uc = user.type == 1 ? shs.site('buyer') : shs.site('seller');

			if(user.type==0){
				location.href = shs.site('admin');
				return;
			}

			var message_url = uc + 'message/';

			// 账号不完善
			if (user.normal == 0 && user.type == 1){
				user.name = '请完善资料';
				uc = shs.site('buyer')+'login_bind/qq';
			}

			var html = [];
			html.push('您好,<a class="topbar-userName" title="进入个人中心" href="', uc, '">', user.name, '</a>');
			html.push('<span class="topbar-tipMsg">您有未读提醒<a href="', message_url, '">(<em>..</em>)</a>条</span>');
			html.push('<a href="', shs.site('login'), 'logout/?to=', encodeURIComponent(shs.site('login')), '">退出登录</a>');
			wrap.html(html.join('')).show();
			this.updateMsg();
			$('.topbar-nav').prepend('<a href="' + uc + '">我的众划算</a>');
		},
		info : function() {
			var cookie = shs.cookie('YL');
			if (!cookie || cookie.split('|').length != 4) {
				return null;
			}

			var info = cookie.split('|');
			return {
				name : info[0],
				type : info[1],
				normal : info[3]
			};
		},
		updateMsg : function() {
			var scriptElm = document.createElement("script");
			scriptElm.src = shs.site('www') + 'api/message?callback=shs.user.showMsg&_='+Math.floor(Math.random()*10e8);
			document.getElementsByTagName("head")[0].appendChild(scriptElm);
		},
		showMsg : function(response) {
			if (response.success) {
				wrap.find('.topbar-tipMsg em').html(response.data.UNREAD_MSG_NUM).end();
			}
		},
		login : function() {
            if(window.art){
                return art.dialog({
                    id : 'win-login',
                    lock : true,
                    fixed : true,
                    padding: 0,
                    title : '用户登录',
                    content: '<iframe src="' + shs.site('login') + 'iframe?dialog=1&jump=' + encodeURIComponent(location.href) + '" frameborder="0" style="width: 300px; height: 250px"></iframe>'
                });
            };
		}
	};
})();

// 认证手机号码提醒
$(function($) {
	var $box = $('.bind-remind'),
		user = shs.user.info();

	if( ! user || $box.length < 1) return;

	// 买家
	if(user.type != 1) return;

	$.getJSON(shs.site('www')+'api/mobile_auth?callback=?', function(response) {
		if ( ! response.success) {
			return;
		}

		// 手机号没有认证
		if(response.data.NEED_MOBILE_VALID && response.data.MOBILE_VALID == 2) {
			$box.show();
			var win = window,
				doc = document,
				ie6 = (navigator.userAgent.indexOf("MSIE")>0) ? (Number(navigator.userAgent.match(/MSIE *([0-9\.]+);/i)[1])<7) : false;

			if(ie6) {
				$(win).scroll( function(){
					$box.css('top', $(doc).scrollTop()+200 + "px");
				}).scroll();
			}
		}
	});
});

//顶部关于我们菜单
$(function($){
	$('.topbar-navMenu').mouseenter(function(event) {
		$(this).addClass("topbar-navMenu-open");
	}).mouseleave(function(event) {
		$(this).removeClass("topbar-navMenu-open");
	});
});

// 引导挂件，占用全局命名空间_widget_guide_callback
var _widget_guide_callback = function(data,undefined){
	// 跨域，只好用jsonp形式
	if(data && data.length>0){
		Loader.add(
			shs.site("static")+"front/common/widget/guide/guide.css?v=20140920",
			shs.site("static")+"front/common/widget/guide/guide.widget.min.js?v=20140920"
		).queue(false).ready(function(){
			Widgets.guide.init(data[0]);
		});
	}
	_widget_guide_callback = undefined;
};
!shs.cookie("widget_guide_isclose")=="1" && Loader(shs.site("www")+"guide/guide_json?callback=_widget_guide_callback&sz=1");
