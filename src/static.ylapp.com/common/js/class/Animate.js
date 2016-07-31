/**
 * CL_Animate动画计算插件
 * Author: 陆楚良
 * Version: 2.1.0
 * Date: 2015/03/06
 * QQ: 519998338
 *
 * https://git.oschina.net/luchg/CL_Animate.js.git
 *
 * License: http://www.apache.org/licenses/LICENSE-2.0
 **/
!function(){
	function Animate(){Animate.prototype.init.apply(this, [].slice.call(arguments))}
    /**
     * 帧率
     * @type {number}
     */
	Animate.fps = 60;
    /**
     * 缓动公式集
     * @type {any}  使用any类型以供扩展缓动公式使用
     */
	Animate.easing = {
		"default": "swing",
		linear: function (t) {
			return t
		},
		swing: function (t) {
			return 0.5-Math.cos(t*Math.PI)/2
		},
		easeInQuad : function (t) {
			return t*t
		},
		easeOutQuad: function (t) {
			return -1*t*(t-2)
		},
		easeInOutQuad: function (t) {
			if ((t/=0.5) < 1) return 0.5*t*t;
			return -0.5 * ((--t)*(t-2) - 1);
		},
		easeInCubic: function (t) {
			return t*t*t;
		},
		easeOutCubic: function (t) {
			return ((t=t-1)*t*t + 1);
		},
		easeInOutCubic: function (t) {
			if ((t/=0.5) < 1) return 0.5*t*t*t;
			return 0.5*((t-=2)*t*t + 2);
		},
		easeInQuart: function (t) {
			return t*t*t*t;
		},
		easeOutQuart: function (t) {
			return -1 * ((t-=1)*t*t*t - 1);
		},
		easeInOutQuart: function (t) {
			if ((t/=0.5) < 1) return 0.5*t*t*t*t;
			return -0.5 * ((t-=2)*t*t*t - 2);
		},
		easeInQuint: function (t) {
			return t*t*t*t*t;
		},
		easeOutQuint: function (t) {
			return (t-=1)*t*t*t*t + 1;
		},
		easeInOutQuint: function (t) {
			if ((t/=0.5) < 1) return 0.5*t*t*t*t*t;
			return 0.5*((t-=2)*t*t*t*t + 2);
		},
		easeInSine: function (t) {
			return -1 * Math.cos(t * (Math.PI/2)) + 1;
		},
		easeOutSine: function (t) {
			return Math.sin(t * (Math.PI/2));
		},
		easeInOutSine: function (t) {
			return -0.5 * (Math.cos(Math.PI*t) - 1);
		},
		easeInExpo: function (t) {
			return (t==0) ? 0 : Math.pow(2, 10 * (t - 1));
		},
		easeOutExpo: function (t) {
			return (t==1) ? 1 : -Math.pow(2, -10 * t) + 1;
		},
		easeInOutExpo: function (t) {
			if (t==0 || t==1) return t;
			if ((t/=0.5) < 1) return 0.5 * Math.pow(2, 10 * (t - 1));
			return 0.5 * (-Math.pow(2, -10 * --t) + 2);
		},
		easeInCirc: function (t) {
			return -1 * (Math.sqrt(1 - t*t) - 1);
		},
		easeOutCirc: function (t) {
			return Math.sqrt(1 - (t-=1)*t);
		},
		easeInOutCirc: function (t) {
			if ((t/=0.5) < 1) return -0.5 * (Math.sqrt(1 - t*t) - 1);
			return 0.5 * (Math.sqrt(1 - (t-=2)*t) + 1);
		},
		easeInElastic: function (t) {
			var s=1.70158;var p=0;var a=1;
			if (t==0 || t==1) return t; if (!p) p=0.3;
			if (a < Math.abs(1)) { a=1; var s=p/4; }
			else var s = p/(2*Math.PI) * Math.asin (1/a);
			return -a*Math.pow(2,10*(t-=1)) * Math.sin( (t-s)*(2*Math.PI)/p );
		},
		easeOutElastic: function (t) {
			var s=1.70158;var p=0;var a=1;
			if (t==0 || t==1) return t; if (!p) p=0.3;
			if (a < Math.abs(1)) { a=1; var s=p/4; }
			else var s = p/(2*Math.PI) * Math.asin (1/a);
			return a*Math.pow(2,-10*t) * Math.sin( (t-s)*(2*Math.PI)/p ) + 1;
		},
		easeInOutElastic: function (t) {
			var s=1.70158;var p=0;var a=1;
			if (t==0) return 0;  if ((t/=0.5)==2) return 1;  if (!p) p=0.3*1.5;
			if (a < Math.abs(1)) { a=1; var s=p/4; }
			else var s = p/(2*Math.PI) * Math.asin (1/a);
			if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t-s)*(2*Math.PI)/p ));
			return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t-s)*(2*Math.PI)/p )*.5 + 1;
		},
		easeInBack: function (t) {
			var s = 1.70158;
			return t*t*((s+1)*t - s);
		},
		easeOutBack: function (t) {
			var s = 1.70158;
			return (t-=1)*t*((s+1)*t + s) + 1;
		},
		easeInOutBack: function (t) {
			var s = 1.70158; 
			if ((t/=0.5) < 1) return 0.5*(t*t*(((s*=(1.525))+1)*t - s));
			return 0.5*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2);
		},
		easeInBounce: function (t) {
			return 1 - Animate.easing.easeOutBounce (1-t);
		},
		easeOutBounce: function (t) {
			if (t < (1/2.75)) {
				return 7.5625*t*t;
			} else if (t < (2/2.75)) {
				return 7.5625*(t-=(1.5/2.75))*t + .75;
			} else if (t < (2.5/2.75)) {
				return 7.5625*(t-=(2.25/2.75))*t + .9375;
			} else {
				return 7.5625*(t-=(2.625/2.75))*t + .984375;
			}
		},
		easeInOutBounce: function (t) {
			if (t < 0.5) return Animate.easing.easeInBounce (t*2) * .5;
			return Animate.easing.easeOutBounce (t*2-1) * .5 + .5;
		}
	};
    /**
     * 可重新设置动画实例化时候的参数
     * @return {Animate}
     */
	Animate.prototype.init = function(){
		var args  = [].slice.call(arguments);
		for( var i=0; (i<3 && i<args.length); i++){
			switch(typeof(args[i])){
				case "number":
					this._d = args[i];break;
				case "string":
					this._e = args[i];break;
				case "function":
					this._c = args[i];break;
			}
		}
		return this;
	};
    /**
     * 执行动画计算
     * @param  {Function}    callback 即时回调
     * @param  {Function}    onStop   可省，动画结束时回调
     * @return {number}               返回动画异步Id
     */
	Animate.prototype.run = function(callback,onStop){
		var timer;
		var self     = this;
		var beginTime= new Date;
		var fps      = typeof(Animate.fps) =="number"?Animate.fps :60;
		var duration = typeof(this._d)=="number"?this._d:1000;
		var ease     = (typeof(this._e)=="string" && typeof Animate.easing[this._e]=="function") ? this._e : Animate.easing["default"];
		var easing   = Animate.easing[ease];
		if(typeof easing!="function"){
			throw new TypeError("Animate.easing[\""+ease+"\"] type is a "+(typeof easing)+" not a function");
			return;
		}
		this._t = timer = setInterval(function(){
			var per = (new Date - beginTime) / duration;
			if(per>=1){
				callback.call(self,1, per);
				self.stop(timer,onStop);
			}else{
				callback.call(self, easing(per,0,1,1), per);
			}
		},parseInt(1000/fps));
		return timer;
	};
    /**
     * 终止动画
     * @return {Animate}
     */
	Animate.prototype.stop = function(){
		var p,args  = [].slice.call(arguments);
		for( var i=0; i<args.length; i++){
			switch(typeof(args[i])){
				case "number":
					clearInterval(args[i]);p=1;break;
				case "function":
					args[i].call(this);break;
			}
		}
		!p && clearInterval(this._t);
		if(typeof(this._c)=="function")this._c();
		return this;
	};

	// RequireJS && SeaJS && GlightJS
	if (typeof define === "function") {
	    define(function() {
	        return Animate;
	    });
	// NodeJS
	} else if (typeof exports !== "undefined") {
	    module.exports = Animate;
	} else {
	    this.CL_Animate = this.Animate = Animate;
	}
}();