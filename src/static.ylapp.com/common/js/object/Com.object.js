/**
 * 常用方法合集
 * by：陆楚良
 */

var Com = (function(){
var M = {};

/**
 * 获取邮箱域名
 * @param	{String}	邮箱地址
 * @return	{String}	返回邮箱域名，不存在则返回空字符串
 */
M.getEmailDomain = function(mail){
	var d = {
		"163.com"		: "http://mail.163.com/",
		"10086.cn"		: "http://mail.10086.cn/",
		"sohu.com"		: "http://mail.sohu.com/",
		"qq.com"		: "http://mail.qq.com/",
		"189.cn"		: "http://mail.189.cn/",
		"126.com"		: "http://www.126.com/",
		"google.com"	: "https://mail.google.com/",
		"sina.com.cn"	: "http://mail.sina.com.cn/",
		"sina.com"		: "http://mail.sina.com/",
		"outlook.com"	: "http://www.outlook.com/",
		"aliyun.com"	: "http://mail.aliyun.com/",
		"tom.com"		: "http://mail.tom.com/",
		"sogou.com"		: "http://mail.sogou.com/",
		"2980.com"		: "http://www.2980.com/",
		"21cn.com"		: "http://mail.21cn.com/",
		"188.com"		: "http://www.188.com/",
		"yeah.net"		: "http://www.yeah.net/",
		"foxmail.com"	: "http://www.foxmail.com/",
		"wo.com.cn"		: "http://mail.wo.com.cn/",
		"263.net"		: "http://www.263.net/"
	};
	var ret = mail.match(/\@(.+$)/,mail.toLowerCase());
	if(!ret)return '';
	return d[ret[1]]||'';
};





return M;
})();