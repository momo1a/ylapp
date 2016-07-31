/**
 * CL_Animate动画计算插件
 * Author: 陆楚良
 * Version: 1.0.0
 * Date：Thu Dec 05 2013 17:55:52 GMT+0800 (中国标准时间)
 * QQ群: 197201959
 **/

function CL_Animate(arg1,arg2,arg3){this.init(arg1,arg2,arg3)}
CL_Animate.fps = 100;

CL_Animate.prototype.run = function(callback,onStop){
	var self = this, timer;
	var fps  = typeof(CL_Animate.fps) =="number"?CL_Animate.fps :100;
	var start_time = new Date().getTime();
	var run_time   = typeof(this.time)=="number"?this.time:1000;
	var alg   	   = typeof(this.alg) =="string"?this.alg :"para-dec";
	if(!/^(uniform|acc|dec|accdec|arc-acc|arc-dec|arc-accdec|para-acc|para-dec)$/i.test(alg))
		alg = "para-dec";
	var calculation = function(){
		var now_time = new Date().getTime();
		var time = now_time-start_time;
		if(time>run_time){
			callback.call(self,1)
			self.stop(timer,onStop);
			return;
		}
		switch(alg){
			case "uniform":			// 匀速
				callback.call(self,time/run_time);
				break;
			case "acc":				// 匀加速
				var s = 0.002*0.5*(time/run_time*1000)*(time/run_time*1000);
				callback.call(self,s/1000);
				break;
			case "dec":				// 匀减速
				var s = 2*(time/run_time*1000)-0.002*0.5*(time/run_time*1000)*(time/run_time*1000);
				callback.call(self,s/1000);
				break;
			case "accdec":			// 匀加速后匀减速
				var t = (time/run_time*1000);
				if(t<500){var s = 0.5*0.004*t*t;}
				else{t-=500;var s = 500+2*t-0.004*0.5*t*t;}
				callback.call(self,s/1000);
				break;
			case "arc-acc":			// 圆弧加速
				var x = time/run_time*1000;
				var y = 1000-Math.pow(1000000-x*x,0.5);
				callback.call(self,y/1000);
				break;
			case "arc-dec":			// 圆弧减速
				var x = 1000-time/run_time*1000;
				var y = Math.pow(1000000-x*x,0.5);
				callback.call(self,y/1000);
				break;
			case "arc-accdec":		// 圆弧加速后圆弧减速
				var x = (time/run_time*1000);
				if(x<500){
					var y = 500-(Math.pow(250000-x*x,0.5));
				}
				else{
					x = 1000-x;
					var y = 500+Math.pow(250000-x*x,0.5);
				}
				callback.call(self,y/1000);
				break;
			case "para-acc":		// 抛物线加速
				var x = (time/run_time*1000);
				callback.call(self,Math.pow(x,5)/Math.pow(1000,5));
				break;
			case "para-dec":		// 抛物线减速
				var x = (time/run_time*1000);
				callback.call(self,1-Math.pow(1000-x,5)/Math.pow(1000,5));
				break;
		}
	}
	this.timer = timer = setInterval(calculation,parseInt(run_time/fps));
	setTimeout(calculation,0);
	return this.timer;
}

CL_Animate.prototype.stop = function(timer,onStop){
	timer = timer||this.timer;
	clearInterval(timer);
	if(typeof(onStop)=="function")onStop();
	if(typeof(this.callback)=="function")this.callback();
	return this;
}

CL_Animate.prototype.init = function(){
	var args  = [].slice.call(arguments);
	for( var i=0; (i<3 && i<args.length); i++){
		switch(typeof(args[i])){
			case "number":
				this.time = args[i];break;
			case "string":
				this.alg = args[i];break;
			case "function":
				this.callback = args[i];break;
		}
	}
	return this;
}

CL_Animate.prototype.easy = function(element, css, onStop) {
	var name, style={}, temp = element.currentStyle?element.currentStyle:document.defaultView.getComputedStyle(element,null);
	for(name in temp)style[name] = temp[name];
	var timer = this.run(function(x){
		var ext;
		for(name in css){
			if(typeof(css[name])=="string" && !isNaN(parseFloat(css[name])) 
				&& style[name] && !isNaN(parseFloat(style[name]))){
				ext = css[name].match(/(%|in|cm|mm|em|ex|pt|pc|px)$/i);
				element.style[name] = (parseFloat(style[name])+(parseFloat(css[name])-parseFloat(style[name]))*x)+(ext ? ext[1] : "");
			}
		}
	},onStop);
	return timer;
}