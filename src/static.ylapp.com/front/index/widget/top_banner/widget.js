/**
 * TopBanner挂件
 * @author: 陆楚良
 * @date: 2015-03-31
 * @ps: 本挂件由旧代码top_banner.js移植，并没有按照众划算widget标准规范编写
 */
;var Widgets = function(mod){
	mod.TopBanner = function(C){
		var win  = window,
			doc  = document,
			head = doc.head?doc.head:doc.getElementsByTagName("head")[0];
			//C.href,			/*跳转链接*/
			//C.LargeImg,		/*大图地址*/
			//C.LargeImgHeight,	/*大图高度*/
			//C.LargeBgC,		/*大图背景色*/
			//C.smallImg,		/*小图地址*/
			//C.smallImgHeight,	/*小图高度*/
			//C.smallBgC,		/*小图背景色*/
			//C.buttonUp,		/*收回按钮图片*/
			//C.buttonDown,		/*展开按钮图片*/
			//C.UpDownHeight,	/*收起、展开按钮高度*/
			//C.UpDownWidth		/*收起、展开按钮宽度*/


		var html = ''
				//  + '<div id="cekebrate_banner">'
				 +     '<div class="cekebrate_bg2">'
				 +         '<div class="cekebrate_head">'
				 +             '<span class="slideDown" title="展开"></span>'
				 +         '</div>'
				 +         '<a target="_blank" href="'+C.href+'">'
				 +             '<img class="cekebrate_Minlogo none" src="'+C.smallImg+'" />'
				 +         '</a>'
				 +     '</div>'
				 +     '<div  class="cekebrate_bg">'
				 +         '<div class="cekebrate_head">'
				 +             '<span class="slideUp" title="收起"></span>'
				 +         '</div>'
				 +         '<a target="_blank" class="cekebrate_logo" href="'+C.href+'">'
				 +             '<img class="cekebrate_logo none" src="'+C.LargeImg+'" />'
				 +         '</a>'
				 +     '</div>'
				//  + '</div>'
		;

		var cssText = ''
			+ '#cekebrate_banner{ width:100%; min-width:1000px; position:relative; height:'+C.LargeImgHeight+'px; background-color:'+C.LargeBgC+';}'
			+ '#cekebrate_banner .cekebrate_bg, .cekebrate_bg2 {display:block; width:100%; position:absolute;}'
			+ '#cekebrate_banner .cekebrate_bg a, .cekebrate_bg2 a{ width:100%; height: 100%; display: block;z-index:1; cursor: pointer;}'
			+ '#cekebrate_banner .cekebrate_bg {height:'+C.LargeImgHeight+'px; top:0;background:url('+C.LargeImg+') no-repeat bottom '+C.LargeBgC+'; }'
			+ '#cekebrate_banner .cekebrate_bg2{ top:-'+C.smallImgHeight+'px; height:'+C.smallImgHeight+'px;background:url('+C.smallImg+') no-repeat bottom '+C.smallBgC+';}'
			+ '#cekebrate_banner .cekebrate_head{margin:0 auto; width:1000px;position:relative; z-index:1;}'
			+ '#cekebrate_banner .cekebrate_logo,.cekebrate_Minlogo{margin:0 auto;}'
			+ '#cekebrate_banner .cekebrate_logo{height:'+C.LargeImgHeight+'px;}'
			+ '#cekebrate_banner .none{display:none;}'
			+ '#cekebrate_banner .cekebrate_Minlogo{ width:1000px; height:'+C.smallImgHeight+'px;}'
			+ '#cekebrate_banner .slideUp,.slideDown{width:'+C.UpDownWidth+'px;position:absolute;height:'+C.UpDownHeight+'px; top:0px;right:0px;display:block;cursor:pointer; z-index:1000;}'
			+ '#cekebrate_banner .slideUp{background: transparent url('+C.buttonUp+') no-repeat 0 0;_background: transparent none;_filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\''+C.buttonUp+'\', sizingMethod=\'scale\');*zoom: 1;}'
			+ '#cekebrate_banner .slideDown{background: transparent url('+C.buttonDown+') no-repeat 0 0;_background: transparent none;_filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\''+C.buttonDown+'\', sizingMethod=\'scale\');*zoom: 1;}'
			+ '#cekebrate_banner .slideDown{top:-'+C.UpDownHeight+'px;}'
		;



		/*插入css*/
		var css = doc.createElement('style');
		css.type = 'text/css';
		if (css.styleSheet) {
			css.styleSheet.cssText = cssText;
		} else {
			css.appendChild(doc.createTextNode(cssText));
		}
		head.appendChild(css);
		/*插入html*/
		//doc.body.innerHTML = html+doc.body.innerHTML;
		var html2dom = doc.createElement('div');
		html2dom.id="cekebrate_banner";
		html2dom.innerHTML = html;
		doc.body.insertBefore(html2dom, doc.getElementById("J_doc"));

		// 模拟SeaJS环境，无污染取出Animate对象
		var Animate;
		var define = function(A){Animate = A();}
		/**
		 * CL_Animate动画计算插件 - 缩水版，仅保留swing缓动公式
		 * Author: 陆楚良
		 * Version: 2.1.0
		 * Date: 2015/03/06
		 * QQ: 519998338
		 *
		 * https://git.oschina.net/luchg/CL_Animate.js.git
		 *
		 * License: http://www.apache.org/licenses/LICENSE-2.0
		 **/
		!function(){function t(){t.prototype.init.apply(this,[].slice.call(arguments))}t.fps=60,t.easing={"default":"swing",swing:function(t){return.5-Math.cos(t*Math.PI)/2}},t.prototype.init=function(){for(var t=[].slice.call(arguments),e=0;3>e&&e<t.length;e++)switch(typeof t[e]){case"number":this._d=t[e];break;case"string":this._e=t[e];break;case"function":this._c=t[e]}return this},t.prototype.run=function(e,n){var i,s=this,o=new Date,r="number"==typeof t.fps?t.fps:60,a="number"==typeof this._d?this._d:1e3,c="string"==typeof this._e&&"function"==typeof t.easing[this._e]?this._e:t.easing["default"],f=t.easing[c];if("function"!=typeof f)throw new TypeError('Animate.easing["'+c+'"] type is a '+typeof f+" not a function");return this._t=i=setInterval(function(){var t=(new Date-o)/a;t>=1?(e.call(s,1,t),s.stop(i,n)):e.call(s,f(t,0,1,1),t)},parseInt(1e3/r)),i},t.prototype.stop=function(){for(var t,e=[].slice.call(arguments),n=0;n<e.length;n++)switch(typeof e[n]){case"number":clearInterval(e[n]),t=1;break;case"function":e[n].call(this)}return!t&&clearInterval(this._t),"function"==typeof this._c&&this._c(),this},"function"==typeof define?define(function(){return t}):"undefined"!=typeof exports?module.exports=t:this.CL_Animate=this.Animate=t}();


		/*下拉动画的实现*/
		 var topBanner = {
		 	isCartoon:false,
			ele : {},
			options : {
			    executeTime: 6000,  /* 首次加载后执行动画的默认时间*/
			    Up_DownHeight: 75, /* 收起按钮的高度*/
			    UpbannerHeight: 300, /*收起广告前的高度*/
			    DownbannerHeight: 75, /*展开广告前的高度*/
			    slideUp_btn: '.slideUp',/*收起按钮默认值*/
			    slideDown_btn: '.slideDown', /*展开按钮默认值*/
			    ad_Upbanner: '.cekebrate_bg', /*大广告默认值*/
			    ad_Downbanner: '.cekebrate_bg2', /*小广告默认值*/
			    ad_time: 700, /*大广告动画时间*/
			    btn_time:700, /*小广告动画时间*/
			    ud_time: 500  /*按钮冒出动画时间*/
			},
			A: new Animate(),
			merge : function(obj1, obj2){
				var obj = {},i;
				for(i in obj1)
					obj[i] = obj1[i];
				for(i in obj2)
					obj[i] = obj2[i];
				return obj;
			},
			/*根据id或class查找节点(只能查找一个)*/
			dom: function(selector){
				if(selector.substr(0,1)=="#"){
					return doc.getElementById(selector.substr(1));
				}
				else if(selector.substr(0,1)=="."){
					selector = " "+selector.substr(1).toLowerCase()+" ";
					for(var i=0;i<this.elements.length;i++){
						if((" "+this.elements[i].className+" ").toLowerCase().indexOf(selector)>=0)
							return this.elements[i];
					}
				}
				return null;
			},
			/*收起*/
			bannerSlideUp:function(){
				if(this.isCartoon)return;
				this.isCartoon = true;
				clearTimeout(this.timer);
				var self = this;
				self.A.init(self.options.ad_time).run(function(i){
					self.ele.top_banner.style.height = (self.options.UpbannerHeight-self.options.UpbannerHeight*i)+"px";
					self.ele.Upbanner.style.top = (-self.options.UpbannerHeight*i)+"px";
					self.ele.slideUp.style.top  = (-self.options.Up_DownHeight*i)+"px";
				},function(){
					self.A.init(self.options.btn_time).run(function(i){
						self.ele.top_banner.style.height = (self.options.DownbannerHeight*i)+"px";
						self.ele.Downbanner.style.top = (-self.options.DownbannerHeight+self.options.DownbannerHeight*i)+"px";
					},function(){
						self.A.init(self.options.ud_time).run(function(i){
							self.ele.slideDown.style.top = (-self.options.Up_DownHeight+self.options.Up_DownHeight*i)+"px";
						},function(){self.isCartoon = false});
					});
				});
			},
			/*展开*/
			bannerSlideDown : function(){
				if(this.isCartoon)return;
				this.isCartoon = true;
				clearTimeout(this.timer);
				var self = this;
				self.A.init(self.options.btn_time).run(function(i){
					self.ele.top_banner.style.height = (self.options.DownbannerHeight-self.options.DownbannerHeight*i)+"px";
					self.ele.Downbanner.style.top = (-self.options.DownbannerHeight*i)+"px";
					self.ele.slideDown.style.top = (-self.options.Up_DownHeight*i)+"px";
				},function(){
					self.A.init(self.options.ad_time).run(function(i){
						self.ele.top_banner.style.height = (self.options.UpbannerHeight*i)+"px";
						self.ele.Upbanner.style.top = (-self.options.UpbannerHeight+self.options.UpbannerHeight*i)+"px";
					},function(){
						self.A.init(self.options.ud_time).run(function(i){
							self.ele.slideUp.style.top  = (-self.options.Up_DownHeight+self.options.Up_DownHeight*i)+"px";
						},function(){self.isCartoon = false});
					});
				});
			},

			init : function(element,options){
				if(typeof options=="object")
					this.options   	= this.merge(this.options,options);
				this.ele.top_banner = typeof element=="string" ? doc.getElementById(element) : element;
				this.elements 		= this.ele.top_banner.getElementsByTagName("*");
		        this.ele.slideUp 	= this.dom(this.options.slideUp_btn);	/*收起按钮*/
		        this.ele.slideDown 	= this.dom(this.options.slideDown_btn);	/*展开按钮*/
		        this.ele.Upbanner 	= this.dom(this.options.ad_Upbanner); 	/*大广告*/
		        this.ele.Downbanner = this.dom(this.options.ad_Downbanner); /*小广告*/
		        var self = this;
		        this.ele.slideUp.onclick  = function(){self.bannerSlideUp()};
		        this.ele.slideDown.onclick= function(){self.bannerSlideDown()};
		        this.timer = setTimeout(function(){self.bannerSlideUp()},this.options.executeTime);
			}
		};
		topBanner.init("cekebrate_banner",{
			executeTime		: 6000,
			Up_DownHeight	: C.UpDownHeight,
			UpbannerHeight	: C.LargeImgHeight,
			DownbannerHeight: C.smallImgHeight,
			slideUp_btn		: '.slideUp',
			slideDown_btn	: '.slideDown',
			ad_Upbanner		: '.cekebrate_bg',
			ad_Downbanner	: '.cekebrate_bg2',
			ad_time			: 700,
			btn_time		: 700,
			ud_time			: 500
		});
	}
	return mod;
}(window.Widgets||{});
