/*-------------------------------
倒计时器
使用示例：
countdownTimer(10,function(d,h,m,s){
	console.log(d+"天"+h+"小时"+m+"分钟"+s+"秒");
	return s===3;
});
---------------------------------*/
var countdownTimer = function(numS, callback){ /*参数：秒数,回调函数(每秒调用一次;参数:d, h, m, s; return true可以停止倒计) */

	var isNumber = function (val) { return typeof val === 'number' && isFinite(val) };
	if( !isNumber(numS) ) return;
	numS = numS<0? 0:numS;
	
	var transverter = function (s,callback) { //转换器
		var dd, hh, mm, ss;
		ss = s % 60; //秒
		var allm = Math.floor( s/60 ); //总的分钟数
		mm = allm % 60; //分
		var allh = Math.floor( allm/60 ); //总的小时数
		hh = allh % 24; //小时
		dd = Math.floor( allh/24 ); //总的天数
		typeof callback ==="function" && callback(dd,hh,mm,ss);
	};
	var d,h,m,s;
	var isFn = typeof callback === "function";

	transverter(numS, function(dd,hh,mm,ss){ //转换初始值
		d = dd;
		h = hh;
		m = mm;
		s = ss;
	});
	isFn && callback(d,h,m,s); //处理初始值
	if(numS<=0) return;

	var cdIng = setInterval(function(){
		numS-=1;
		transverter(numS,function(dd,hh,mm,ss){
			d = dd;
			h = hh;
			m = mm;
			s = ss;
		});
		numS || clearInterval(cdIng);
		isFn && callback(d,h,m,s) && clearInterval(cdIng);
	},1000);

};
