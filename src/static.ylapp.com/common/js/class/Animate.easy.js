/**
 * CL_Animate快速css动画扩展
 * Author: 陆楚良
 * Version: 2.1.0
 * Date: 2015/03/06
 * QQ: 519998338
 *
 * https://git.oschina.net/luchg/CL_Animate.js.git
 *
 * License: http://www.apache.org/licenses/LICENSE-2.0
 **/
!function(undefined){
	function easy(An){
		/**
		 * 拟补低端浏览器没有trim的不足
		 * @param {string} str
		 */
		function trim(str){
		    if(String.prototype.trim)return str.trim();
		    return str.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, "");
		}
	    /**
	     * 提取样式中的数字
	     * @type {RegExp}
	     */
		var rfxnum  = /^(-?(0|[1-9][0-9]*)(\.[0-9]+)?)([a-z%]*)$/i;
		var floatAll= /-?(0|[1-9][0-9]*)(\.[0-9]+)?/g;
	    /**
	     * 初始化方法
	     * @param {any} element 需要获取的元素节点对象
	     */
		function Style(element){this.element = element}
	    /**
	     * 获取当前样式对象
	     * @return {any}
	     */
		Style.prototype.current = function(){
	        var element = this.element;
	        return element.currentStyle?element.currentStyle:window.getComputedStyle(element,null);
    	};
	    /**
	     * 获取单位为px的盒子宽高
	     * @return {string}
	     */
    	Style.prototype.getWidthOrHeight = function(name){
	        var element = this.element;
	        var old;
	        var ret;
	        var cur  = this.current();
	        var comp = parseFloat(cur[name]);
	        old = element.style[name];
	        element.style[name] = comp+"px";
	        ret = comp+comp-parseFloat(cur[name]);
	        element.style[name] = old;
	        return ret+"px";
    	};
	    /**
	     * 获取、设置css样式
	     * @param   {string}{Object}  name  样式名
	     * @param   {any}             value 样式值（当缺省时候为获取样式值，当为true时候，获取的宽高单位为px）
	     * @return  {any}
	     */
	    Style.prototype.css = function(name, value){
	        if(value!==undefined && typeof value!="boolean"){
	            this.element.style[name] = value;
	        }else{
	            var istyle = this.element.style;
	            var fstyle = this.current();
	            switch(typeof name){
	                case "string":
	                    // 优先读取行内样式(行内样式无需转换)
	                    return (value!==true && istyle[name]) ? istyle[name] : (
	                            (name=="width" || name=="height") ? this.getWidthOrHeight(name) : (fstyle[name]||"")
	                        );
	                case "object":
	                    for(var i in name){
	                        if(typeof i == "string"){
	                            name[i] = (value!==true && istyle[i]) ? istyle[i] : (
	                                (i=="width" || i=="height") ? this.getWidthOrHeight(i) : (fstyle[i]||"")
	                            );
	                        }
	                    }
	                    return name;
	                default:
	                    return "";
	            }
	        }
	    };
	    /**
	     * 获取指定单位的样式值
	     * @param  {string} name 样式名
	     * @param  {string} unit 单位
	     * @return {number}
	     */
	    Style.prototype.tween = function(name, unit){
	        var target = this.css(name).match(rfxnum)[1];
	        var start  = target;
	        var scale  = 1;
	        var maxIterations = 20;
	        do {
	            scale = scale || 0.5;
	            start = start / scale;
	            this.css(name, start+unit);
	        } while ( 
	            scale !== (scale = parseFloat(this.css(name, true).match(rfxnum)[1]) / target) 
	            && scale !== 1 
	            && --maxIterations 
	        );
	        return start;
	    };
		/**
		 * 类jQuery的基于Element快速动画方法扩展
		 * @param  {any}        element 节点对象
		 * @param  {any}        css     样式，可以是数组也可以是对象，当是数组时候，类似于css3动画的起始样式到终止样式变化方式(此方法兼容性更高)
		 * @param  {Function}   onStop  可省，动画结束回调
		 * @return {number}     返回动画异步Id
		 */
		An.prototype.easy = function(element, css, onStop){
			var name;
		    var style={};
		    var c;
		    var g1;
		    var g2;
		    var i;
		    var eleStyle = new Style(element);
		    if (Object.prototype.toString.call(css) !== '[object Array]'){
		        for(name in css){
		            style[name] = void 0;
		        }
		        style = eleStyle.css(style);
		        var start,end;
		        for(name in style){
		            start = trim(style[name]).match(rfxnum);
		            end   = trim(css[name]+"").match(rfxnum);
		            if(start && end){
		                if(start[4]!==end[4]){
		                    style[name] = eleStyle.tween(name, end[4])+end[4];
		                }
		            }
		        }
		    }else{
		        style=css[0];
		        css = css[1];
		    }
		    // 转成字符串
		    for(name in css){
		        css[name]   = String(  css[name]);
		        style[name] = String(style[name]);
		    }
		    return this.run(function(x){
		        for(name in css){
		            if(typeof(css[name])=="string" && typeof(style[name])=="string"){
		                g1 = style[name].match(floatAll);
		                g2 = css[name].match(floatAll);
		                c  = css[name].replace(floatAll, "###");
		                if(g1 && g2 && g1.length==g2.length){
		                    for(i=0;i<g1.length;i++){
		                        c = c.replace("###", parseFloat(g1[i])+(parseFloat(g2[i])-parseFloat(g1[i]))*x);
		                    }
		                }
		                element.style[name] = c;
		            }
		        }
		    }, onStop);
		};
		return An;
	}
	
	function cmd(require, exports, module){
		var An = require("./Animate");
		module.exports = easy(An);
	}

	if (typeof define === "function") {
		if(define.amd){
			// AMD
			define(["./Animate"], function(An) {
				return easy(An);
			});
		}else if(define.cmd){
			// CMD: SeaJS && GlightJS
			define(cmd);
		}
	// NodeJS
	} else if (typeof exports !== "undefined") {
		cmd(require, exports, module);
	// Normal JacaScript
	} else {
	    this.Animate && easy(this.Animate);
	}
}()