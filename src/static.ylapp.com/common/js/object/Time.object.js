/**
 * 时间处理模块
 * Author: 陆楚良
 * Version: 1.0.5
 * Date: 2015-05-08
 * QQ: 519998338
 *
 * https://git.oschina.net/luchg/Time.js.git
 *
 * License: http://www.apache.org/licenses/LICENSE-2.0
 **/

!function(){
'use strict';

var Time = {};

// 类型判断
var isType = (function(){
	var class2type = {},i,
		core_toString = class2type.toString,
		L = "Boolean Number String Function Array Date RegExp Object Error".split(" ");
	for(i=0;i<9;i++){
		class2type[ "[object " + L[i] + "]" ] = L[i].toLowerCase();
	}
	return function( obj ) {
		if ( obj == null ) {
			return String( obj );
		}
		return typeof obj === "object" || typeof obj === "function" ?
			class2type[ core_toString.call(obj) ] || "object" :
			typeof obj;
	};
})();

//补0
var f = function(s,n){
	n = isType(n)=="number" ? n : 2;
	s = String(s);
	for(var i=(n-s.length);i>0;i--){
		s = "0"+s;
	}
	return s;
};

/**
 * 计时器
 *
 * 该函数运行时会返回一个函数，每次调用返回的函数会得到与执行该函数时候的时间间隔
 * 如：
 * 		var clock = Time.clock();	// 1、获取计时函数
 * 		//假设等了N秒
 * 		clock()						// 2、得到此时与步骤1相差的时间间隔（N秒）
 * 		//假设再等了N2秒
 * 		clock()						// 3、同样得到此时与步骤1相差的时间间隔（N+N2秒）
 * 		//假设再等了N3秒
 * 		clock(1)					// 3、此时得到的是与步骤1相差的时间间隔，单位毫秒
 *									// 参数设置：1毫秒，1000秒，60000分，3600000时，86400000天
 *									// 规律就是：1毫秒，1*1000秒，1*1000*60分，1*1000*60*60时，1*1000*60*60*24天
 *									// 缺省 1000
 */
Time.clock = function(){
	var first = new Date().getTime();
	return function(unit){
		unit = isType(unit)=="number" ? unit : 1000;
		return parseInt((new Date().getTime()-first)/unit);
	};
};

/**
 * 快速格式化时间
 * @param	{String}			字串
 * @param	{Number}{String}	时间，缺省值为当前时间
 * @return	{String}
 */
Time.strftime = (function(){
	var r = {
			//星期几的简写
			'%a' : function(d){return ['Sun','Mon','Tues','Wed','Thur','Fri','Sat'][d.getDay()];},
			//星期几的全称
			'%A' : function(d){return ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'][d.getDay()]; },
			//月份的简写
			'%b' : function(d){return ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][d.getMonth()];},
			//月份的全称
			'%B' : function(d){return ['January','February','March','April','May','June','July','August','September','October','November','December'][d.getMonth()];},
			//不带时分秒的日期的时间串：2014-03-17
			'%c' : function(d){
				 return d.getFullYear()
				 		+'-'+f(d.getMonth()+1)
				 		+'-'+f(d.getDate());
			},
			//标准的日期的时间串：2014-03-17 15:37:20
			'%C' : function(d){
				 return d.getFullYear()
				 		+'-'+f(d.getMonth()+1)
				 		+'-'+f(d.getDate())
				 		+' '+f(d.getHours())
				 		+':'+f(d.getMinutes())
				 		+':'+f(d.getSeconds());
			},
			//几日，二位数字，若不足二位不补零; 如: "1" 至 "31"
			'%d' : function(d){return d.getDate();},
			//几日，二位数字，若不足二位则前面补零; 如: "01" 至 "31"
			'%D' : function(d){return f(d.getDate());},
			//英文表示的本月第几日
			'%e' : function(d){
				var day = String(d.getDate());
				switch(day){
					case '1':
					case '21': day+='st';break;
					case '2':
					case '22': day+='nd';break;
					case '3':
					case '23': day+='rd';break;
					default:day+='th';
				}
				return day;
			},
			//毫秒; 0-999
			'%f' : function(d){return d.getMilliseconds()},
			//毫秒; 000-999
			'%F' : function(d){return f(d.getMilliseconds(),3)},
			//12 小时制的小时，不足二位不补零; 如: "1" 至 12"
			'%g' : function(d){var h = d.getHours(); return h>12?h-12:h; },
			//12 小时制的小时; 如: "01" 至 "12"
			'%G' : function(d){var h = d.getHours(); return f(h>12?h-12:h); },
			//24 小时制的小时，不足二位不补零; 如: "0" 至 "23"
			'%h' : function(d){return d.getHours(); },
			//24 小时制的小时; 如: "00" 至 "23"
			'%H' : function(d){return f(d.getHours()); },
			//分钟；如0-59
			'%i' : function(d){return d.getMinutes(); },
			//分钟；如00-59
			'%I' : function(d){return f(d.getMinutes()); },
			//月份，二位数字，若不足二位则不补零; 如: "1" 至 "12"
			'%m' : function(d){return d.getMonth()+1; },
			//月份，二位数字，若不足二位则在前面补零; 如: "01" 至 "12"
			'%M' : function(d){return f(d.getMonth()+1); },
			//获取am或pm
			'%p' : function(d){return d.getHours()<12 ? 'am' : "pm";},
			//获取AM或PM
			'%P' : function(d){return d.getHours()<12 ? 'AM' : "PM";},
			//秒; 如: "0" 至 "59"
			'%s' : function(d){return d.getSeconds();},
			//秒; 如: "00" 至 "59"
			'%S' : function(d){return f(d.getSeconds());},
			//中文表示的星期几
			'%u' : function(d){return ['日','一','二','三','四','五','六'][d.getDay()];},
			//年份的后两位数字
			'%y' : function(d){return String(d.getFullYear()).substr(-2);},
			//四位数的年份
			'%Y' : function(d){return d.getFullYear();},
			//百分号
			'%%' : function(d){return '%'}
		};
	return function(format,timestamp){
		var d = isType(timestamp)!="undefined" ? new Date(timestamp) : new Date();
		for(i in r){
			if(i.substr(0,1)=='%')
				format = format.replace(new RegExp(i,'g'), r[i](d));
		}
		return format;
	};
})();

/**
 * 时间转换
 * @param	{String}	字串
 * @param	{Number}	时间戳(单位：毫秒)
 * @param	{Boolean}	标记是否将值为0的单位清除
 * @return	{String}
 * 用法
 * Time.converter("{%d天}{%h时}{%i分}{%s秒}",378250000);
 */
Time.converter= function(format,time,clear){
	clear= clear===false ? "0" : "";
	var t= {};
	t.f  = time % 1000;
	time = Math.floor(time / 1000);
	t.s  = time % 60;
	time = Math.floor(time / 60);
	t.i  = time % 60;
	time = Math.floor(time / 60);
	t.h  = time % 24;
	t.d  = Math.floor(time / 24);
	var ment = function(t){
		return t ? "$1"+t+"$2" : "";
	};
	format = format.replace(/\{([^{]*?)%f(.*?)\}/g, ment(t.f || clear));
	format = format.replace(/\{([^{]*?)%s(.*?)\}/g, ment(t.s || clear));
	format = format.replace(/\{([^{]*?)%i(.*?)\}/g, ment(t.i || clear));
	format = format.replace(/\{([^{]*?)%h(.*?)\}/g, ment(t.h || clear));
	format = format.replace(/\{([^{]*?)%d(.*?)\}/gi,ment(t.d || clear));
	format = format.replace(/\{([^{]*?)%F(.*?)\}/g, ment(t.f ? f(t.f,3) : clear+clear));
	format = format.replace(/\{([^{]*?)%S(.*?)\}/g, ment(t.s ? f(t.s) : clear+clear));
	format = format.replace(/\{([^{]*?)%I(.*?)\}/g, ment(t.i ? f(t.i) : clear+clear));
	format = format.replace(/\{([^{]*?)%H(.*?)\}/g, ment(t.h ? f(t.h) : clear+clear));
	return format;
};
/**
 * 判断是否为闰年
 * @param {Number}	完整的年份，如2014，默认当前年份
 */
Time.isLeapYear = function(year){
	var year = isType(year)!="undefined" ? year : new Date().getFullYear();
	return (((year % 4) == 0) && ((year % 100) != 0) || ((year % 400) == 0));
};


/**
 * 昨天0点毫秒值
 * @param {Number}{String}	可设置对比的时间，缺省当前时间
 */
Time.yesterday = function(date){
	var d = isType(date)!="undefined" ? new Date(date) : new Date();
	return new Date(d.getFullYear(),d.getMonth(),d.getDate()-1).getTime();
};

/**
 * 今天0点毫秒值
 * @param {Number}{String}	可设置对比的时间，缺省当前时间
 */
Time.today = function(date){
	var d = isType(date)!="undefined" ? new Date(date) : new Date();
	return new Date(d.getFullYear(),d.getMonth(),d.getDate()).getTime();
};

/**
 * 明天0点毫秒值
 * @param {Number}{String}	可设置对比的时间，缺省当前时间
 */
Time.tomorrow = function(date){
	var d = isType(date)!="undefined" ? new Date(date) : new Date();
	return new Date(d.getFullYear(),d.getMonth(),d.getDate()+1).getTime();
};


/**
 * 上个月1日0点毫秒值
 * @param {Number}{String}	可设置对比的时间，缺省当前时间
 */
Time.lastMonth = function(date){
	var d = isType(date)!="undefined" ? new Date(date) : new Date();
	return new Date(d.getFullYear(),d.getMonth()-1,1).getTime();
};
/**
 * 本月1日0点毫秒值
 * @param {Number}{String}	可设置对比的时间，缺省当前时间
 */
Time.thisMonth = function(date){
	var d = isType(date)!="undefined" ? new Date(date) : new Date();
	return new Date(d.getFullYear(),d.getMonth(),1).getTime();
};
/**
 * 下个月1日0点毫秒值
 * @param {Number}{String}	可设置对比的时间，缺省当前时间
 */
Time.nextMonth = function(date){
	var d = isType(date)!="undefined" ? new Date(date) : new Date();
	return new Date(d.getFullYear(),d.getMonth()+1,1).getTime();
};

/**
 * 前年1月1日0点毫秒值
 * @param {Number}{String}	可设置对比的时间，缺省当前时间
 */
Time.lastYear = function(date){
	var d = isType(date)!="undefined" ? new Date(date) : new Date();
	return new Date(d.getFullYear()-1,0,1).getTime();
};

/**
 * 今年1月1日0点毫秒值
 * @param {Number}{String}	可设置对比的时间，缺省当前时间
 */
Time.thisYear = function(date){
	var d = isType(date)!="undefined" ? new Date(date) : new Date();
	return new Date(d.getFullYear(),0,1).getTime();
};

/**
 * 明年1月1日0点毫秒值
 * @param {Number}{String}	可设置对比的时间，缺省当前时间
 */
Time.nextYear = function(date){
	var d = isType(date)!="undefined" ? new Date(date) : new Date();
	return new Date(d.getFullYear()+1,0,1).getTime();
};

/**
 * 获取星座、干支、生肖
 * @param {String}			可选值XZ、GZ、SX
 * @param {Number}{String} 	可设置需要获取的日期
 */
Time.magicInfo = function (type,date){
	var d = isType(date)!="undefined" ? new Date(date) : new Date();
	var $result =   '';
	var $i,$XZDict,$Zone,$GZDict,$SXDict;
    var $m      =   d.getMonth()+1;
    var $y      =   d.getFullYear();
    var $d      =   d.getDate();

    switch (type)
    {
    case 'XZ'://星座
        $XZDict = ['摩羯','宝瓶','双鱼','白羊','金牛','双子','巨蟹','狮子','处女','天秤','天蝎','射手'];
        $Zone   = [1222,122,222,321,421,522,622,722,822,922,1022,1122,1222];
        if((100*$m+$d)>=$Zone[0]||(100*$m+$d)<$Zone[1])
            $i=0;
        else
            for($i=1;$i<12;$i++){
            if((100*$m+$d)>=$Zone[$i]&&(100*$m+$d)<$Zone[$i+1])
              break;
            }
        $result = $XZDict[$i] + '座';
        break;

    case 'GZ'://干支
        $GZDict = [
                    ['甲','乙','丙','丁','戊','己','庚','辛','壬','癸'],
                    ['子','丑','寅','卯','辰','巳','午','未','申','酉','戌','亥']
                    ];
        $i= $y -1900+36 ;
        $result = $GZDict[0][$i%10] + $GZDict[1][$i%12];
        break;

    case 'SX'://生肖
        $SXDict = ['鼠','牛','虎','兔','龙','蛇','马','羊','猴','鸡','狗','猪'];
        $result = $SXDict[($y-4)%12];
        break;

    }
    return $result;
};



// RequireJS && SeaJS && GlightJS
// GlightJS: https://git.oschina.net/luchg/Glight.js.git
if(typeof define==="function"){
	define(function(){return Time});
// NodeJS
}else if(typeof exports!=="undefined"){
	module.exports = Time;
}else{
	window.Time = Time;
}


}();
