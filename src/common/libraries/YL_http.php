<?php
class YL_http {
	public $timeout = 0;
	
	// cURL连接资源句柄的信息
	protected $info = NULL;
	
	/**
	 * 获取一个cURL连接资源句柄的信息
	 * @return array
	 *
	 * @author 杜嘉杰
	 * @version 2015年11月5日  上午11:55:37
	 *
	 */
	public function get_info()
	{
		return $this->info;
	}
	
	/*
     * get 方式获取访问指定地址
     * @param  string url 要访问的地址
     * @param  string cookie cookie的存放地址,没有则不发送cookie
     * @return string curl_exec()获取的信息
     * @author andy
     **/
	public function get($url, $cookie = '') {
		// 初始化一个cURL会话  
		$curl = curl_init ( $url );
		// 不显示header信息  
		curl_setopt ( $curl, CURLOPT_HEADER, 0 );
		// 将 curl_exec()获取的信息以文件流的形式返回，而不是直接输出。  
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
		// 使用自动跳转  
		curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, 1 );
		if (! empty ( $cookie )) {
			// 包含cookie数据的文件名，cookie文件的格式可以是Netscape格式，或者只是纯HTTP头部信息存入文件。  
			curl_setopt ( $curl, CURLOPT_COOKIEFILE, $cookie );
		}
		// 自动设置Referer  
		curl_setopt ( $curl, CURLOPT_AUTOREFERER, 1 );
		//如果是https
		if(strpos($url,'https://')!==false){
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,2);			
		}
		// 执行一个curl会话  
		$tmp = curl_exec ( $curl );
		
		// 保存cURL连接资源句柄的信息
		$this->info = curl_getinfo($curl);
		
		// 关闭curl会话  
		curl_close ( $curl );
		return $tmp;
	}
	
	/*
     * post 方式模拟请求指定地址
     * @param  string url   请求的指定地址
     * @param  array  params 请求所带的
     * #patam  string cookie cookie存放地址
     * @return string curl_exec()获取的信息
     * @author andy
     **/
	public function post($url, $params, $cookie='') {
		$curl = curl_init ( $url );
		curl_setopt ( $curl, CURLOPT_HEADER, 0 );
		// 对认证证书来源的检查，0表示阻止对证书的合法性的检查。  
		curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, false );
		// 从证书中检查SSL加密算法是否存在  
//		curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 1 );
		//模拟用户使用的浏览器，在HTTP请求中包含一个”user-agent”头的字符串。  
		if(isset($_SERVER ['HTTP_USER_AGENT']))
		{
			curl_setopt ( $curl, CURLOPT_USERAGENT, $_SERVER ['HTTP_USER_AGENT'] );
		}
		
		//发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。  
		curl_setopt ( $curl, CURLOPT_POST, 1 );
		// 将 curl_exec()获取的信息以文件流的形式返回，而不是直接输出。  
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
		// 使用自动跳转  
		curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, 1 );
		// 自动设置Referer  
		curl_setopt ( $curl, CURLOPT_AUTOREFERER, 1 );
		
		//CURLOPT_TIMEOUT 	设置cURL允许执行的最长秒数。
		//CURLOPT_TIMEOUT_MS 	设置cURL允许执行的最长毫秒数。
		//CURLOPT_CONNECTTIMEOUT 	在发起连接前等待的时间，如果设置为0，则无限等待。
		//CURLOPT_CONNECTTIMEOUT_MS 	尝试连接等待的时间，以毫秒为单位。如果设置为0，则无限等待。
		$this->timeout > 0 && curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
		$this->timeout > 0 && curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		
		// Cookie地址  
		if ($cookie !== '')
		{
			curl_setopt ( $curl, CURLOPT_COOKIEJAR, $cookie );
		}
		// 全部数据使用HTTP协议中的"POST"操作来发送。要发送文件，  
		// 在文件名前面加上@前缀并使用完整路径。这个参数可以通过urlencoded后的字符串  
		// 类似'para1=val1$para2=val2&...'或使用一个以字段名为键值，字段数据为值的数组  
		// 如果value是一个数组，Content-Type头将会被设置成multipart/form-data。  
		curl_setopt ( $curl, CURLOPT_POSTFIELDS, http_build_query ( $params ) );
		$result = curl_exec ( $curl );
		
		// 保存cURL连接资源句柄的信息
		$this->info = curl_getinfo($curl);
		
		curl_close ( $curl );
		return $result;
	}
	
	/**
	 * 远程下载
	 * @param string $remote 远程图片地址
	 * @param string $local 本地保存的地址
	 * @param string $cookie cookie地址 可选参数由
	 * 于某些网站是需要cookie才能下载网站上的图片的
	 * 所以需要加上cookie
	 * @return void
	 * @author andy
	 */
	public function reutersload($remote, $local, $cookie = '') {
		$cp = curl_init ( $remote );
		$fp = fopen ( $local, "w" );
		curl_setopt ( $cp, CURLOPT_FILE, $fp );
		curl_setopt ( $cp, CURLOPT_HEADER, 0 );
		if ($cookie != '') {
			curl_setopt ( $cp, CURLOPT_COOKIEFILE, $cookie );
		}
		curl_exec ( $cp );
		curl_close ( $cp );
		fclose ( $fp );
	}

} 