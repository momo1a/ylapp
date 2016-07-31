var Frame = function(Fr, $, shs){
	'use strict';
	var undefined = void 0;
	/**
	 * 创建事件机制
	 * @param  {Object} Evt 可省，允许自定义事件对象
	 * @return {Object}	 返回事件对象
	 */
	Fr.Event = function(self){
		var Evt = {};
		self = self || Evt;
		var monitors = {};
		function bind(event, monitor){
			if(event && $.type(monitor.callback)=="function"){
				if(!monitors[event]){
					monitors[event] = [];
				}
				monitors[event].push(monitor);
			}
		}
		function unbind(query){
			var
				event,
				monitor,
				es,
				i = 0,
				result
			;
			if(query.event){
				es = monitors[query.event];
				if(es){
					result  = [];
					for(;i<es.length;i++){
						monitor = es[i];
						if(
							   (query.callback!==undefined && query.callback!==monitor.callback)
							|| (query.other   !==undefined && query.other   !==monitor.other)
						){
							result.push(monitor);
						}
					}
					monitors[query.event] = result;
				}
			}else{
				for(event in monitors){
					es = monitors[event];
					result  = [];
					for(;i<es.length;i++){
						monitor = es[i];
						if(
							   (query.callback!==undefined && query.callback!==monitor.callback)
							|| (query.other   !==undefined && query.other   !==monitor.other)
						){
							result.push(monitor);
						}
					}
					monitors[event] = result;
				}
			}
		}
		/**
		 * 事件绑定
		 * @param  {String}   event	 事件名
		 * @param  {Function} callback  回调方法
		 * @param  {Object}   context   可省，可修改上下文this对象，缺省为当前事件所在的上下文对象
		 */
		Evt.on = function(event, callback, context){
			event = event.split(" ");
			for(var i=0;i<event.length;i++){
				bind(event[i], {
					callback: callback,
					on: 1,
					context: context||self,
					other: this
				});
			}
		};
		/**
		 * 事件绑定，与on不同之处就是当回调被执行一次以后就会销毁
		 * @param  {String}   event	事件名
		 * @param  {Function} callback 回调方法
		 * @param  {Object}   context  可省，可修改上下文this对象
		 */
		Evt.once = function(event, callback, context){
			event = event.split(" ");
			for(var i=0;i<event.length;i++){
				bind(event[i], {
					callback: callback,
					on: 1,
					context: context||self,
					other: this
				});
			}
		};
		/**
		 * 事件解绑
		 * @param  {String}   event	事件名
		 * @param  {Function} callback 需要删除的回调引用
		 * Evt.off()				   解绑所有回调
		 * Evt.off(event)			  解绑event事件下的所有回调
		 * Evt.off(event, callback)	解绑event事件下的callback回调
		 * Evt.off(null,  callback)	解绑所有事件下的callback回调
		 */
		Evt.off = function(event, callback){
			event = event.split(" ");
			for(var i=0;i<event.length;i++){
				unbind({
					event:event[i],
					callback:callback,
					other: this
				});
			}
		};
		/**
		 * 监听另一个对象上的一个特定事件
		 * @param  {Object}   other	监听的对象
		 * @param  {String}   event	事件名
		 * @param  {Function} callback 回调方法
		 * 效果等同于 other.on(event, callback)，特点在于可以在stopListening方法中一次性解除所有
		 */
		Evt.listenTo = function(other, event, callback){
			other.on.call(Evt, event, callback);
		};
		/**
		 * 监听另一个对象上的一个特定事件，但是是一次性的，与once同理
		 * 用法与listenTo相同
		 */
		Evt.listenToOnce = function(other, event, callback){
			other.once.call(Evt, event, callback);
		};
		/**
		 * 解除监听对象下的回调
		 * @param  {Object}   other			 监听的对象
		 * @param  {String}   event			 事件名
		 * @param  {Function} callback		  需要删除的回调引用
		 * Evt.stopListening()				  同Event.off()
		 * Evt.stopListening(other)			 同other.off()
		 * Evt.stopListening(other, event)	  同other.off(event)
		 * Evt.stopListening(other, event, callback)  同other.off(event, callback)
		 * Evt.stopListening(null,  event, callback)  同Event.off(null, event, callback)
		 * Evt.stopListening(other, null,  callback)  同other.off(null, event, callback)
		 * ......
		 */
		Evt.stopListening= function(other, event, callback){
			if(other){
				other.off.call(Evt, event, callback)
			}else{
				Evt.off(event, callback);
			}
		};
		/**
		 * 触发事件运行
		 * @param  {String} event  事件名
		 * @param  {Array}  args   可省，需要广播出去的参数
		 * @return {Boolean}	   当所有槽的返回值都不为false时，该返回值为true，否则为false
		 */
		Evt.trigger = function(event){
			var
				events = event.split(" "),
				args   = [].slice.call(arguments),
				args2,
				ret = true,
				ei  = 0,
				mi,
				eLength  = events.length,
				me,
				mLength,
				result,
				m;
			;
			for (;ei<eLength;ei++){
				event = events[ei];
				args2 = args[ei+1] || [];
				if(monitors[event]){
					me	  = [].slice.call(monitors[event]);
					mLength = me.length;
					result  = [];
					for(mi=0;mi<mLength;mi++){
						m = me[mi];
						if(m.callback.apply(m.context, args2)===false){
							ret = false;
						}
						// 只留下非一次性回调
						m.on && result.push(m);
					}
					monitors[event] = result;
				}
				// 通配事件
				if(event!=="all"){
					if(!Evt.trigger("all", [event].concat(args2))){
						ret = false;
					}
				}
			}
			return ret;
		};
		return Evt;
	};
	/**
	 * Search搜索模块插件
	 */
	Fr.Search = function(){
		function Search(element){
			var self  = this;
			self.es   = ["set_type", "submit"];
			self.Event= Fr.Event(self);
			self._dom = {};
			var $sear = self._dom.element = $(element);
			var $sel  = self._dom.select  = $(".J_fr_search_sel", element);
			$sel.hover(
				function(){$sel.addClass("z-open")},
				function(){$sel.removeClass("z-open")}
			);
			$sel.find("a").click(function(){
				self.set_type_for_element(this);
			});
			$sear.find("form").submit(function(){
				return ($sear.find(":text").val()!=="" && self.Event.trigger("submit")) ? true : false;
			});
		}
		Search.prototype.set_type = function(val){
			var self = this;
			self._dom.select.find("a").each(function(){
				$(this).data("type")===val && self.set_type_for_element(this);
			});
		};
		Search.prototype.set_type_for_element = function(ele){
			var $ele = $(ele);
			var $sel = this._dom.select;
			var type = $ele.data("type");
			if(!$ele.hasClass("z-crt")){
				$sel.removeClass("z-open");
				$sel.find("a").removeClass("z-crt");
				$ele.addClass("z-crt").prependTo($sel);
				this._dom.element.find(".J_type").val(type);
				this._dom.element.find(":text").focus();
				this.Event.trigger("set_type", [type]);
			}
		};
		return function(element){
			// 增加缓存防止多次创建
			var ret = $(element).data("frame.search");
			if(!ret){
				ret = new Search(element);
				$(element).data("frame.search", ret);
			}
			return ret;
		};
	}();
	/**
	 * Menu菜单模块插件
	 */
	Fr.Menu = function(){
		function Menu(element){
			var self  = this;
			self.es   = ["show","hide"];
			self.Event= Fr.Event(self);
			var dom   = self._dom = {};
			dom.element = $(element);
			dom.element.find("dt").click(function(){
				var dl = $(this).closest("dl");
				dl.hasClass("z-open") ? self.hide(dl) : self.show(dl);
			});
		}
		Menu.prototype.show = function(ele){
			$(ele).addClass("z-open").find("dd").stop().slideDown();
			this.Event.trigger("show", [ele]);
		};
		Menu.prototype.hide = function(ele){
			var self = this;
			$(ele).find("dd").stop().slideUp(function(){
				$(ele).removeClass("z-open");
				self.Event.trigger("hide", [ele]);
			});
		};
		return function(element){
			// 增加缓存防止多次创建
			var ret = $(element).data("frame.menu");
			if(!ret){
				ret = new Menu(element);
				$(element).data("frame.menu", ret);
			}
			return ret;
		};
	}();
	/**
	 * 选项卡模块插件
	 */
	Fr.Tab = function(){
		/**
		 * 选项卡插件
		 * @class Tab(element, [options]);
		 * @param {Element} element	   需要实例化的选项卡节点对象
		 * @param {Object}  options	   可选，详细配置
		 *								 {String}  options.selector_item  = "li"			 item选择器
		 *								 {String}  options.selector_line  = ".J_fr_line"	 底部滑动高亮横条选择器
		 *								 {String}  options.className_crt  = "z-crt"		  高亮样式名
		 *								 {String}  options.selector_sub   = ".J_fr_sub"	  下拉菜单选择器
		 *								 {String}  options.className_open = "z-open"		 下拉菜单开启样式名
		 */
		function Tab(element, options){
			var self  = this;
			self.es   = ["show","move","open","close","sub_show"];
			self.Event= Fr.Event(self);
			options   = options || {};
			self.element	    = $(element);
			self.selector_item  = options.selector_item  || "li";
			self.selector_line  = options.selector_line  || ".J_fr_line";
			self.className_crt  = options.className_crt  || "z-crt";
			self.selector_sub   = options.selector_sub   || ".J_fr_sub";
			self.selector_sub_item = options.selector_sub_item || ".J_fr_sub a";
			self.className_open = options.className_open || "z-open";
			self.items = self.element.find(self.selector_item);
			self.subs  = self.element.find(self.selector_sub);
			self.subs_items = self.element.find(self.selector_sub_item);
			self.line  = self.element.find(self.selector_line);
			self.index = 0;
			self.move_index=0;
			self.items.each(function(i){
				var $this = $(this);
				if($this.hasClass(self.className_crt)){
					self.index = i;
				}
				$this.mouseenter(function(){
					self.move(i);
				}).mouseleave(function(){
					self.move(self.index);
				});
			});
			self.subs.hover(
				function(){self.open(this)},
				function(){self.close(this)}
			);
			// 将下拉菜单中当前显示的菜单初始化到第一位置
			self.subs_items.each(function(){
				var $this = $(this);
				if($this.hasClass(self.className_crt)){
					$this.prependTo($this.parent());
				}
			});
			// 将第一项高亮
			self.subs_items.each(function(){
				$(this).parent().find(":first").get(0)===this && self.sub_show(this);
			});
			// 初始化显示选项卡
			self.subs.css("visibility", "visible");
			self.show(self.index);
		}
		Tab.prototype.show = function(index){
			this.index = index;
			this.items.eq(index).addClass(self.className_crt);
			this.Event.trigger("show", [index]);
			this.move(index);
		};
		Tab.prototype.move = function(index){
			var self= this;
			var crt = self.items.eq(index);
			self.move_index = index;
			self.line.stop().animate({
				width : crt.innerWidth(),
				left  : crt.position().left
			}, function(){
				self.Event.trigger("move", [index]);
			});
		};
		Tab.prototype.open = function(obj){
			$(obj).addClass(this.className_open);
			this.Event.trigger("open", [obj]);
		};
		Tab.prototype.close= function(obj){
			$(obj).removeClass(this.className_open);
			this.Event.trigger("close", [obj]);
		};
		Tab.prototype.sub_show = function(obj){
			$(obj).siblings().removeClass(this.className_crt);
			$(obj).addClass(this.className_crt).prependTo($(obj).parent());
			this.Event.trigger("sub_show", [obj]);
			// 让线条响应变化后的宽度
			this.move(this.move_index);
		};
		return function(element, options){
			// 增加缓存防止多次创建tab对象
			var ret = $(element).data("frame.tab");
			if(!ret){
				ret = new Tab(element, options);
				$(element).data("frame.tab", ret);
			}
			return ret;
		};
	}();
	/**
	 * 分页模块插件
	 */
	Fr.Page = function(){
		/**
		 * 分页插件
		 * @class Page(element, [options]);
		 * @param {Element} element	   需要实例化的选项卡节点对象
		 * @param {Object}  options	   分页配置
		 *							   {Number}  options.total	总页数
		 *							   {Number}  options.now	当前页
		 *							   {Number}  options.num	显示的页数
		 *							   {String}  options.url	url规则（{p}会被替换成目标页数）：http://xxx.xxx.com/page={p}
		 *							   {Boolean} options.custom 可省，是否开启自定义跳转页数（总页数大于num值时候拥有输入框跳页功能）
		 *							   {Boolean} options.autohide 可省，当总页数只有1页的时候，是否隐藏分页模块，缺省值“true”
		 */
		function Page(element, options){
			var self   = this;
			self.es    = ["set_url", "set_custom", "set_autohide", "set_total", "set_now", "set_num", "render", "goto"];
			self.Event = Fr.Event(self);
			options    = options || {};
			self.element = $(element);
			self.url     = options.url;
			self.custom  = !!options.custom;
			self.autohide= options.autohide==undefined ? true : !!options.autohide;
			self.total   = parseInt(options.total);
			self.now     = parseInt(options.now);
			self.num     = parseInt(options.num);
			self.element.on("click", "a", function(){
				$(this).data("page") && self.goto($(this).data("page"));
				return false;
			});
			self.element.on("click", "button", function(){
				var page = self.element.find("input").val();
				if(page){
					page = parseInt(page);
					if(page>this.total){
						page = this.total;
					}else if(page<1){
						page = 1;
					}
					self.element.find("input").val(page);
					self.goto(page);
				}
			});
			self.render();
		}
		/**
		 * 修改url规则
		 * @param  {String} url
		 */
		Page.prototype.set_url = function(url){
			this.url = url;
			this.Event.trigger("set_url", [url]);
			this.render();
		};
		/**
		 * 修改是否开启自定义跳转页数
		 * @param  {String} custom
		 */
		Page.prototype.set_custom = function(custom){
			this.custom = !!custom;
			this.Event.trigger("set_custom", [custom]);
			this.render();
		};
		/**
		 * 修改自动隐藏规则
		 * @param  {String} custom
		 */
		Page.prototype.set_autohide = function(autohide){
			this.autohide = !!autohide;
			this.Event.trigger("set_autohide", [autohide]);
			this.render();
		};
		/**
		 * 修改总页数数值
		 * @param  {Number} total
		 */
		Page.prototype.set_total = function(total){
			this.total = parseInt(total);
			this.Event.trigger("set_total", [this.total]);
			this.render();
		};
		/**
		 * 修改当前页数值
		 * @param  {Number} now
		 */
		Page.prototype.set_now = function(now){
			this.now = parseInt(now);
			this.Event.trigger("set_now", [this.now]);
			this.render();
		};
		/**
		 * 修改显示的页数
		 * @param  {Number} num
		 */
		Page.prototype.set_num = function(num){
			this.num = parseInt(num);
			this.Event.trigger("set_num", [this.num]);
			this.render();
		};
		/**
		 * 渲染分页（内部使用，外部一般用不到）
		 */
		Page.prototype.render = function(){
			var total= this.total;
			var now  = this.now;
			var num  = this.num;
			var lnum = Math.ceil(num/2-2)-1;
			var rnum = parseInt(num/2-2);
			var html = "";
			var list = [];
			var i;
			// 总页数只有1页时候渲染空
			if(this.autohide && total<=1){
				this.element.html(html).hide();
				this.Event.trigger("render");
				return;
			}
			if(now>1){
				html += '<a href="#" class="prev" data-page="'+(now-1)+'">&lt;</a>';
			}
			if(total<=num){
				// 页数不够
				for(i=1; i<=total; i++){
					list.push(i);
				}
			}else if(now<=2+lnum+1){
				// 左边页数不够
				for(i=1; i<=num-2; i++){
					list.push(i);
				}
				list.push(0);
				list.push(total);
			}else if(total<=now+rnum+2){
				// 右边页数不够
				list.push(1);
				list.push(0);
				for(i=total-num+3; i<=total; i++){
					list.push(i);
				}
			}else{
				// 两边页数不够
				list.push(1);
				list.push(0);
				for(i=0; i<num-4; i++){
					list.push(now-lnum+i);
				}
				list.push(0);
				list.push(total);
			}
			for(i=0; i<list.length; i++){
				if(list[i]){
					html += '<a href="#"' + (list[i]==now ? ' class="z-crt"' : '') + ' data-page="'+list[i]+'">' + list[i] + '</a>';
				}else{
					html += '<i>...</i>';
				}
			}
			if(now<total){
				html += '<a href="#" class="next" data-page="'+(now+1)+'">&gt;</a>';
			}
			if(this.custom && total>num){
				html += '<span>共'+total+'页</span><span>到<input type="text">页</span><button>确定</button>';
			}
			this.element.html(html).show();
			this.Event.trigger("render");
		};
		/**
		 * 跳转至指定页
		 * @param  {Number} page
		 * 提示：本方法中的goto事件可以使用return false;阻止页面跳转（ajax无刷新分页技术可用）
		 */
		Page.prototype.goto = function(page){
			page = parseInt(page);
			if(page>this.total){
				page = this.total;
			}else if(page<1){
				page = 1;
			}
			if(page!==this.now){
				this.set_now(page);
				if(this.Event.trigger("goto", [page])){
					location.href = this.url.replace(/\{p\}/, page);
				}
			}
		};
		return function(element, options){
			// 增加缓存防止多次创建tab对象
			var ret = $(element).data("frame.page");
			if(!ret){
				ret = new Page(element, options);
				$(element).data("frame.page", ret);
			}
			return ret;
		};
	}();
	/**
	 * 表单控件集
	 * @param {Object} Form
	 */
	Fr.Form = function(Form){
		/**
		 * select控件
		 * 例：
		 * <ul class="J_fr_sel f-dn" data-name="adsasd">
		 *	 <li data-value="1" data-selected="true">选项一</li>
		 *	 <li data-value="2">选项二</li>
		 *	 <li data-value="3">选项三</li>
		 *	 <li data-value="4">选项四</li>
		 * </ul>
		 * 需要注意的几个属性：
		 * data-name		对应的是原生select的name
		 * data-value	   对应的是原生option的value
		 * data-selected	   对应的是原生option的selected
		 * li中的内容对应的是option中的内容
		 * 备注：本控件为基于css量身定做的
		 */
		Form.Select = function(){
			function Select(element){
				var self = this;
				var $ele = $(element);
				var _name	 = $ele.data("name");
				var _options = [];
				var _index   = 0;
				self.es      = ["set_name","set_index","set_value","set_list","append"];
				self.Event   = Fr.Event(self);
				$ele.find("li").each(function(i){
					var $op = $(this);
					_options.push({
						name : $op.html(),
						value: $op.data("value")
					});
					if($op.data("selected")){
						_index = i;
					}
				});
				var html = '<dl class="u-sel f-usn">'
						 +	 '<dt>'
						 +		 '<span>&nbsp;</span><i class="arrow">&nbsp;</i>'
						 +		 '<input type="hidden" name="">'
						 +	 '</dt>'
						 +	 '<dd>'
						 // +		 '<p data-value="1">订单号</p>'
						 // +		 '<p data-value="2" data-selected="true">关键字</p>'
						 +	 '</dd>'
						 + '</dl>';
				self._dom = {};
				self._dom.element = $(html);
				self._dom.show	  = self._dom.element.find("dt");
				self._dom.option  = self._dom.element.find("dt span");
				self._dom.input   = self._dom.element.find("dt input");
				self._dom.options = self._dom.element.find("dd");
				$ele.after(self._dom.element);
				$ele.hide();
				self.set_name(_name);
				self.set_list(_options);
				self.set_index(_index);
				self._dom.show.click(function(){
					self._dom.show.addClass("z-focus");
					self._dom.options.stop().addClass("z-focus").slideDown(200);
					$(document).one("click", function(){
						self._dom.show.removeClass("z-focus");
						self._dom.options.stop().removeClass("z-focus").slideUp(200);
					});
					return false;
				});
				self._dom.options.on("click", "p", function(){
					self._dom.show.removeClass("z-focus");
					self._dom.options.stop().removeClass("z-focus").slideUp(200);
					self.set_index($(this).data("index"));
				});
				// 鼠标进入下拉选项后，阻止页面滚动条滚动
				var
				    // 用于记录滚动条位置，锁定滚动条  {Number|FALSE}
				    scrollLock = false,
				    $d = $(document),
				    $h = $("html,body")
				;
				self._dom.options.on("mouseenter mouseleave", function(event){
				    if(event.type == "mouseenter"){
				        scrollLock = $d.scrollTop(); // 记录滚动条位置，锁住滚动条
				    }else{
				        scrollLock = false;      // 解锁滚动条
				    }
				});
				// 监听滚动条，适时锁定(有抖动...如有高人解决，不胜感激)
				$(window).on("scroll mousewheel", function(e){
				    scrollLock!==false && $h.scrollTop(scrollLock);
				});
			}
			Select.prototype.get_name  = function(){return this._name;} ;
			Select.prototype.get_value = function(){return this._value;};
			Select.prototype.get_index = function(){return this._index;};
			Select.prototype.get_list  = function(){return this._potions;};
			/**
			 * 设置name值
			 * @param  {String} name
			 */
			Select.prototype.set_name  = function(name) {
				this._name = name;
				this._dom.input.attr("name", name);
				this.Event.trigger("set_name", [name]);
			};
			/**
			 * 根据value值设置当前选项
			 * @param  {String} value
			 */
			Select.prototype.set_value = function(value){
				for(var i=0;i<this._options.length;i++){
					if(this._options[i].value===value){
						self.set_index(i);
						break;
					}
				}
			};
			/**
			 * 根据index值设置当前选项
			 * @param  {Number} index
			 */
			Select.prototype.set_index = function(index){
				var option = this._options[index];
				var name  = option.name;
				var value = option.value;
				this._dom.option.html(name);
				this._dom.input.val(value);
				this._index = index;
				this._value = value;
				this.Event.trigger("set_index", [index]);
				this.Event.trigger("set_value", [value]);
			};
			/**
			 * 使用数组重设下拉框
			 * @param  {Array} list
			 * [
			 * 		{name:"选项显示的名称",value:"1"},
			 * 		{name:"选项显示的名称",value:"2"},
			 * 		{name:"选项显示的名称",value:"3"}
			 * 	}
			 */
			Select.prototype.set_list = function(list){
				this._options = [];
				this._width = 0;
				this._dom.options.html("");
				for(var i=0;i<list.length;i++){
					this.append(list[i]);
				}
				this.set_index(0);
				this.Event.trigger("set_list", [list]);
			};
			/**
			 * 向末尾追加选项
			 * @param  {Object} item 	{name:"选项显示的名称",value:"1"}
			 */
			Select.prototype.append = function(item){
				var op = $('<p>'+item.name+'</p>');
				op.data("value", item.value);
				op.data("index", this._options.length);
				this._dom.options.append(op);
				this._dom.options.show();
				var width = op.width();
				if( width > this._width ){
					this._width = width;
					this._dom.show.css("width", width);
					this._dom.options.find("p").css("width", width);	/* IE7 BUG FUCK！ */
				}
				this._dom.options.hide();
				this._options.push({
					name : item.name,
					value: item.value
				});
				this.Event.trigger("append", [item]);
			};
			return function(element){
				var ret = $(element).data("frame.form.select");
				if(!ret){
					ret = new Select(element);
					$(element).data("frame.form.select", ret);
				}
				return ret;
			}
		}();
		/**
		 * 时间控件
		 *  data-fmt		日期格式，如：yyyy-MM-dd
		 *  data-mindate	日期格式，如：2015-04-20，支持使用id与其他时间控件关联，如：#F{$dp.$D('elementId')}
		 *  data-maxdate	日期格式，如：2015-04-30，支持使用id与其他时间控件关联，如：#F{$dp.$D('elementId')}
		 * 例：
		 *		 <input type="text" class="u-ipt u-ipt-date J_fr_date" data-fmt="yyyy-MM-dd" data-mindate="2015-04-20" data-maxdate="2015-04-30" >
		 */
		Form.Date = function(){
			function Date(element){
                var self   = {};
                self.es    = ["set_date"];
                self.Event = Fr.Event(self);
                self.get_date = function(date){
                    return $ele.val();
                };
                self.set_date = function(date){
                    var old = $ele.val();
                    if(String(date)!==old){
                        $ele.val(date);
                        self.Event.trigger("set_date", [date]);
                    }
                };
                var $ele = $(element);
                var c = {el: element, dateFmt: $ele.data("fmt") || 'yyyy-MM-dd', onpicked: function(dp){self.Event.trigger("set_date", [dp.cal.getNewDateStr()])}};
                if($ele.data("mindate")){
                    c.minDate = $ele.data("mindate");
                }
                if($ele.data("maxdate")){
                    c.maxDate = $ele.data("maxdate");
                }
                // 按需加载WdatePicker.js
                Loader.use("WdatePicker").run(function(){
                    $ele.click(function(){WdatePicker(c);});
                });
                return self;
			};
			return function(element){
				var ret = $(element).data("frame.form.date");
				if(!ret){
					ret = Date(element);
					$(element).data("frame.form.date", ret);
				}
				return ret;
			}
		}();
		return Form;
	}({});

	// 事件
	Fr.Action = function(Act){
		var es = "blur change click dblclick focus focusin focusout keydown keypress keyup load mousedown mouseenter mouseleave mousemove mouseout mouseover mouseup scroll select submit".split(" ");
		$.each(es, function(i, e){
			$("#J_bd").on(e, ".J_"+e, function(event){
				var a = $(this).data('action');
				if($.type(a)=="string" && a.indexOf("(") > 0){
					// 支持简单的参数传递
					var func = $.trim(a.substr(0, a.indexOf("(")));
					if($.type(Act[func])=="function"){
						var args = a.match(/\((.*)\)/);
						args = Function("return [" + args[1] + "]").call(this);
						return Act[func].apply(this, [event].concat(args));
					}
				}else if($.type(Act[a])=="function"){
					return Act[a].call(this, event);
				}
			});
		});
		return Act;
	}({});
	return Fr;
}({}, $, shs);


!(function(){
	/* 搜索框效果实现 */
	!function(){
		$sear = $("#J_search");
		$sel  = $sear.find(".J_sel");
		$sel.hover(
			function(){$sel.addClass("z-open")},
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


	// Search模块
	$(".J_fr_search").each(function(){Frame.Search(this)});
	// Menu模块
	$(".J_fr_menu").each(function(){Frame.Menu(this)});
	// 选项卡模块
	$(".J_fr_tab" ).each(function(){Frame.Tab(this)});
	// select控件
	$(".J_fr_sel" ).each(function(){Frame.Form.Select(this)});
	// date控件，需要依赖WdatePicker插件
	$(".J_fr_date").each(function(){Frame.Form.Date(this)});
	$(".J_fr_page").each(function(){
		var $this = $(this);
		Frame.Page(this, {
			total: $this.data("total"),		// 总页数
			now  : $this.data("now"),		// 当前页
			num  : $this.data("num"),		// 显示多少个页数按钮(不包括上下页)
			url  : $this.data("url"),		// url规则
			custom  :$this.data("custom"),	// 是否开启自定义跳转页数（总页数大于num值时候拥有输入框跳页功能）
			autohide:$this.data("autohide")	// 当总页数只有1页的时候，是否隐藏分页模块
		});
	});
	// 倒计时，存在需要倒计时的地方才按需加载time扩展，倒计时因网络问题可能会稍微有点小误差
	var loadTime = (window.LOADTIME||new Date()).getTime();
	$(".J_fr_cd").length>0 && Loader.js(shs.static("common/js/object/Time.object.min.js")).run(function(){
		setInterval((function cd(){
			$(".J_fr_cd").each(function(){
				var $this = $(this);
				var time  = parseInt($this.data("time"))*1000-new Date().getTime()+loadTime;
				var html;
				if(time>0){
					html = Time.converter($this.data("format") || "{%d天}{%H时}{%I分}{%S秒}",time);
				}else{
					// 还原回原有的html
					html = $this.data("end") || "0秒";
				}
				$this.html(html);
			});
			return cd;
		})(), 1000);
	});
})();
