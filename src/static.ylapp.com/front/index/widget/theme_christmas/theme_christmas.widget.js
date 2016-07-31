/* ===========================================================
 * jquery-let_it_snow.js v1
 * ===========================================================
 * NOTE: This plugin is based on the work by Jason Brown (Loktar00)
 *
 * As the end of the year approaches, let's add 
 * some festive to your website!
 *
 *
 * ========================================================== */

!function($){
  
  var defaults = {
    speed: 0,
    interaction: true,
    size: 2,
    count: 200,
    opacity: 0,
    color: "#ffffff",
    windPower: 0,
    image: false
	};
	
  
  $.fn.let_it_snow = function(options){
    var settings = $.extend({}, defaults, options),
        el = $(this),
        flakes = [],
        canvas = el.get(0),
        ctx = canvas.getContext("2d"),
        flakeCount = settings.count,
        mX = -100,
        mY = -100;
    
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        
    (function() {
        var requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || window.msRequestAnimationFrame ||
        function(callback) {
            window.setTimeout(callback, 1000 / 60);
        };
        window.requestAnimationFrame = requestAnimationFrame;
    })();
    
    function snow() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        for (var i = 0; i < flakeCount; i++) {
            var flake = flakes[i],
                x = mX,
                y = mY,
                minDist = 100,
                x2 = flake.x,
                y2 = flake.y;

            var dist = Math.sqrt((x2 - x) * (x2 - x) + (y2 - y) * (y2 - y)),
                dx = x2 - x,
                dy = y2 - y;

            if (dist < minDist) {
                var force = minDist / (dist * dist),
                    xcomp = (x - x2) / dist,
                    ycomp = (y - y2) / dist,
                    deltaV = force / 2;

                flake.velX -= deltaV * xcomp;
                flake.velY -= deltaV * ycomp;

            } else {
                flake.velX *= .98;
                if (flake.velY <= flake.speed) {
                    flake.velY = flake.speed
                }
                
                switch (settings.windPower) { 
                  case false:
                    flake.velX += Math.cos(flake.step += .05) * flake.stepSize;
                  break;
                  
                  case 0:
                    flake.velX += Math.cos(flake.step += .05) * flake.stepSize;
                  break;
                  
                  default: 
                    flake.velX += 0.01 + (settings.windPower/100);
                }
            }

            var s = settings.color;
            var patt = /^#([\da-fA-F]{2})([\da-fA-F]{2})([\da-fA-F]{2})$/;
            var matches = patt.exec(s);
            var rgb = parseInt(matches[1], 16)+","+parseInt(matches[2], 16)+","+parseInt(matches[3], 16);

            
            flake.y += flake.velY;
            flake.x += flake.velX;

            if (flake.y >= canvas.height || flake.y <= 0) {
                reset(flake);
            }


            if (flake.x >= canvas.width || flake.x <= 0) {
                reset(flake);
            }
            if (settings.image == false) {
              ctx.fillStyle = "rgba(" + rgb + "," + flake.opacity + ")"
              ctx.beginPath();
              ctx.arc(flake.x, flake.y, flake.size, 0, Math.PI * 2);
              ctx.fill();
            } else {
              
              ctx.drawImage($("img#lis_flake").get(0), flake.x, flake.y, flake.size * 2, flake.size * 2);
            }
            
        }
        requestAnimationFrame(snow);
    };
    
    
    function reset(flake) {
        
        if (settings.windPower == false || settings.windPower == 0) {
          flake.x = Math.floor(Math.random() * canvas.width);
          flake.y = 0;
        } else {
          if (settings.windPower > 0) {
            var xarray = Array(Math.floor(Math.random() * canvas.width), 0);
            var yarray = Array(0, Math.floor(Math.random() * canvas.height))
            var allarray = Array(xarray, yarray)
            
            var selected_array = allarray[Math.floor(Math.random()*allarray.length)];
            
             flake.x = selected_array[0];
             flake.y = selected_array[1];
          } else {
            var xarray = Array(Math.floor(Math.random() * canvas.width),0);
            var yarray = Array(canvas.width, Math.floor(Math.random() * canvas.height))
            var allarray = Array(xarray, yarray)
            
            var selected_array = allarray[Math.floor(Math.random()*allarray.length)];
            
             flake.x = selected_array[0];
             flake.y = selected_array[1];
          }
        }
        
        flake.size = (Math.random() * 3) + settings.size;
        flake.speed = (Math.random() * 1) + settings.speed;
        flake.velY = flake.speed;
        flake.velX = 0;
        flake.opacity = (Math.random() * 0.5) + settings.opacity;
    }
     function init() {
      for (var i = 0; i < flakeCount; i++) {
          var x = Math.floor(Math.random() * canvas.width),
              y = Math.floor(Math.random() * canvas.height),
              size = (Math.random() * 3)  + settings.size,
              speed = (Math.random() * 1) + settings.speed,
              opacity = (Math.random() * 0.5) + settings.opacity;
      
          flakes.push({
              speed: speed,
              velY: speed,
              velX: 0,
              x: x,
              y: y,
              size: size,
              stepSize: (Math.random()) / 30,
              step: 0,
              angle: 180,
              opacity: opacity
          });
      }
      
      snow();
    }
    
    if (settings.image != false && $("#lis_flake").length==0) {	// 此处被修改
      $("<img src='"+settings.image+"' style='display: none' id='lis_flake'>").prependTo("body")
    }
    
    init();
    
    $(window).resize(function() {
      if(this.resizeTO) clearTimeout(this.resizeTO);
      this.resizeTO = setTimeout(function() {
        el2 = el.clone();
        el2.insertAfter(el);
        el.remove();
        
        el2.let_it_snow(settings);
      }, 200);
    });
    
    if (settings.interaction == true) {
      canvas.addEventListener("mousemove", function(e) {
          mX = e.clientX,
          mY = e.clientY
      });
    }
  }
}(jQuery);


/**
 * 2014-12-25首页圣诞节主题widget
 * Author: 陆楚良
 * Date: 2014-12-23
 * QQ: 519998338
 */
var Widgets = function(mod, document, ua, $){
	if($("html").hasClass("ie6")?!0:ua.indexOf("MSIE")>0?Number(ua.match(/MSIE *([0-9\.]+);/i)[1])<7:!1)return;
	/*--- 私有变量 Begin ---*/

	// html内容
	var Private_html= '<div class="w-christmas">'
					+ 	'<div class="bd">'
					+ 		'<div class="mk">&nbsp;</div>'
					+ 		'<div class="moon-outter">'
					+ 			'<div class="moon">'
					+ 				'<span class="flame">&nbsp;</span>'
					+ 				'<span class="tt">圣诞快乐</span>'
					+ 			'</div>'
					+ 		'</div>'
					+ 		'<div class="land">'
					+ 			'<a href="'+shs.site('www')+'yzcm" target="_blank" class="close" title="前往众划算">前往众划算</a>'
					+ 		'</div>'
					+ 		'<canvas width="100%" height="100%" class="snow"></canvas>'
					+ 		'<canvas width="100%" height="100%" class="flake1"></canvas>'
					+ 		'<canvas width="100%" height="100%" class="flake2"></canvas>'
					+ 	'</div>'
					+ '</div>';
	// 图片data-url
	var Private_image="data:image/png;charset=utf-8;base64,iVBORw0KGgoAAAANSUhEUgAAADkAAAA8CAYAAADc1RI2AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MUI3MDkzNjM4OUI3MTFFNDkyNzU4OEEzN0FGQkZBRTYiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MUI3MDkzNjQ4OUI3MTFFNDkyNzU4OEEzN0FGQkZBRTYiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDoxQjcwOTM2MTg5QjcxMUU0OTI3NTg4QTM3QUZCRkFFNiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDoxQjcwOTM2Mjg5QjcxMUU0OTI3NTg4QTM3QUZCRkFFNiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PmsofKoAAAwaSURBVHja1Jv5VxvX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";
	/*--- 私有变量 End   ---*/

	// 初始化方法
	var init = function(){
		/*--- DOM存储 Begin ---*/
		var D = Widget.DOM,W = D.widget = $(Private_html);
		D.outter = W.find(".outter");
		D.bd     = W.find(".bd");
		D.moon   = W.find(".moon");
		D.land   = W.find(".land");
		D.close  = W.find(".close");
		D.tt     = W.find(".tt");
		D.canvas = W.find("canvas");
		/*--- DOM存储 End   ---*/

		/*--- 事件绑定 Begin ---*/
		D.close.click(function(){
			Widget.hide(true);
		});
		/*--- 事件绑定 End   ---*/

		/*--- 雪花效果初始化 Begin ---*/
		if(!!document.createElement('canvas').getContext){
			W.find("canvas.snow").let_it_snow({
				windPower: -3,
				speed: 1,
				count: 200,
				size: 1
			});
			W.find("canvas.flake1").let_it_snow({
				windPower: -3,
				speed: 1,
				count: 15,
				size: 10,
				image: Private_image
			});
			W.find("canvas.flake2").let_it_snow({
				windPower: -3,
				speed: 1,
				count: 5,
				size: 20,
				image: Private_image
			});
		}
		/*--- 雪花效果初始化 End   ---*/

		$(document.body).append(D.widget);
		Widget.show();
		setTimeout(Widget.hide, 5600+10000);
		return Widget;
	};

	// 挂件对象
	var Widget = {
		// 存储DOM专用对象
		DOM:{},
		/**
		 * 显示
		 */
		show:function(){
			var D = Widget.DOM;
			D.canvas.hide();
			D.land.hide();
			D.tt.hide().css({"margin-top":495});
			D.widget.show();
			D.bd.animate({"margin-top":0}, 800, function(){
				D.canvas.fadeIn(800);
				D.land.fadeIn(800, function(){
					D.moon.animate({top:-485},2000, function(){
						D.tt.fadeIn(2000);
					});
				});
				
			});
		},
		/**
		 * 隐藏
		 */
		hide:function(op){
			if(op){
				shs.cookie("widget_theme_christmas_ishide", "1", {expires: 1});	// 写入cookie，一天内不再显示
			}
			var D = Widget.DOM;
			D.bd.animate({"margin-top":"-100%"}, 800, function(){
				D.widget.hide();
			});
		}
	};


//	mod.theme_christmas = init();	// 初始化挂件并加入全局挂件列表
	init();					// 不加入控件列表，不对外开放
	return mod;				// 返回全局挂件列表

}(window.Widgets||{}, document, navigator.userAgent, jQuery);