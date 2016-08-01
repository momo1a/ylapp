/*推广跟踪*/
!function()
{
	var url,ref,flag_spm,flag_ref,domain,exptime;
	
	domain   = 'YLdev1.com';
	url      = window.location.href;
	ref      = document.referrer;
	flag_spm = false;
	flag_ref = false;

	if(url.indexOf('spm=') != -1)
	{
		flag_spm = true;
	}
 	if((typeof(ref) == "string") && (ref.length > 0))
	{
		flag_ref = true;
	}
	if(flag_spm)
	{
		var arr,reg;
		//检测是否为第一次
		reg = new RegExp('(^| )'+'spm_flag'+'=([^;]*)(;|$)');
		if(arr = document.cookie.match(reg)){/*alert(unescape(arr[2]));*/}
		else
		{
			//查看是否已经存过
			reg = new RegExp('(^| )'+'spm'+'=([^;]*)(;|$)');
			if(arr = document.cookie.match(reg)){/*alert(unescape(arr[2]));*/}
			else
			{
				//提取spm
				var patt = /spm=([0-9.]+)/gi;
				var tmp  = url.match(patt);
				var spm  = tmp[0].split('=');
				var tmp  = spm[1];
				//分离参数
				spm = tmp.split('.');
				//cookie有效时间（天）
				var exp = new Date(); 
				exptime = exp.getTime() + spm[2] * 24 * 60 * 60 * 1000;
				exp.setTime(exptime);
				//写入cookie
				document.cookie = "spm=" + tmp +"; expires=" + exp.toGMTString() + "; domain=" + domain + "; path=/";
				//写入cookie
				document.cookie = "spm_flag=yes; expires=" + exp.toGMTString() + "; domain=" + domain + "; path=/";
			}	
		}
	}
	if(flag_ref)
	{
		var arr,reg;
		reg = new RegExp('(^| )'+'spm_ref'+'=([^;]*)(;|$)');
		if(arr = document.cookie.match(reg)){/*alert(unescape(arr[2]));*/}
		else
		{
			var exp = new Date();
			if(flag_spm)
			{
				exp.setTime(exptime);//过期时间与spm一致
			}
			else
			{
				exp.setTime(exp.getTime() + 7 * 24 * 60 * 60 * 1000);
			}
			document.cookie = "spm_ref=" + encodeURIComponent(ref) + "; expires=" + exp.toGMTString() + "; domain=" + domain + "; path=/";
		}
	}
}(document);