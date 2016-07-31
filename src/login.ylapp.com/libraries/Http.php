<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * 
 * @author "韦明磊-众划算项目组<nicolaslei@163.com>"
 */
class Http {
	
	static $_url;
	
	static $_remote_ip;
	
	public static function get($url, $para = array())
	{
		return self::request('GET', $url, $para);
	}
	
	public static function post($url, $para = array())
	{
		return self::request('POST', $url, $para);
	}
	
	public static function request($method, $url, $para)
	{
		$response = '';
		
		switch ($method) {
			case 'GET':
				self::$_url = $url . '?' . http_build_query($para);
				$response = self::_request('GET');
				break;
			default:
				if (is_array($para) || is_object($para))
				{
					$body = http_build_query($para);
				}
				$response = self::_request($method, $body);
		}
		
		return $response;
	}
	
	/**
	 * 执行一个 HTTP请求
	 *
	 * @return string API results
	 * @ignore
	 */
	private static function _request($method, $postfields = NULL, $headers = array())
	{
		//return file_get_contents(self::$_url);
		$ci = curl_init();
		/* Curl settings */
		curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		//curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ci, CURLOPT_TIMEOUT, 30);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ci, CURLOPT_ENCODING, "");
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 2);
		//curl_setopt($ci, CURLOPT_HEADERFUNCTION, array(self, 'get_header'));
		curl_setopt($ci, CURLOPT_HEADER, FALSE);
	
		if ($method == 'POST')
		{
			curl_setopt($ci, CURLOPT_POST, TRUE);
			if (!empty($postfields))
			{
				curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
			}
		}
	
		if ( !empty(self::$_remote_ip) ) {
			$headers[] = "API-RemoteIP: " . self::$_remote_ip;
		} else {
			$headers[] = "API-RemoteIP: " . $_SERVER['REMOTE_ADDR'];
		}
		curl_setopt($ci, CURLOPT_URL, self::$_url );
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );
	
		$response = curl_exec($ci);
		curl_close($ci);
		
		return $response;
	}
}