<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mobile
{
	//-------------------------------------------
	//luosimao语音验证发送地址
	private $luosimao_url = '';
	//luosimao API Key
	private $luosimao_key= '';
	//-------------------------------------------
	
	//-------------------------------------------
	//浪驰短信接口用户ID
	private $lanz_userid = '';
	//浪驰短信接口帐号
	private $lanz_account = '';
	//浪驰短信接口密码 （密码需密码转换器加密传输）
	private $lanz_password = '';
	//-------------------------------------------
	
	
	//luosimao语音发送接口START---------------------------------------------------------------------------------
	
	/**
	 * 语音验证
	 * @param mobile 接收号码
	 * @param verifycode 验证码内容，为数字和英文字母，不区分大小写，长度4-8位
	 * @return boolean FALSE：发送不成功；TRUE：发送成功
	 */
	public function voice_verify($mobile,$verifycode)
	{
		self::luosimao_init();
		$post_data = array('mobile' => $mobile,'code' => $verifycode);
		$result = self::luosimao_post($this->luosimao_url,$this->luosimao_key,$post_data);
		$result = json_decode($result);
		/**
		 * $result->error的可能返回值
		 * @link http://luosimao.com/docs/api/51
		 * 
		 * 错误码 	错误描述 	                                          解决方案
			-10 	验证信息失败 	              检查api key是否和各种中心内的一致，调用传入是否正确
			-20 	余额不足 	                         进入个人中心购买充值
			-30 	验证码内容为空 	    检查调用传入参数：code
			-40 	错误的手机号 	               检查手机号是否正确
		 */
		if( $result == NULL || $result->error != 0 )
		{
			$error = $result ? $result->error : '-';
			log_message('error', '语音验证码发送失败[error:'.$error.'],手机号码:'.$mobile.',验证码:'.$verifycode);
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * 初始化语音验证功能的配置项
	 */
	private function luosimao_init()
	{
		$this->luosimao_url = config_item('voice_luosimao_url');
		$this->luosimao_key = config_item('voice_luosimao_key');
	}
	
    /**
     * 发起HTTPS post请求
     */
     private function luosimao_post($url,$key,$data)
     {
	    $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_HTTPAUTH , CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD  , 'api:key-'.$key);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$res = curl_exec( $ch );
		curl_close( $ch );
		return $res;
     }
     
     //luosimao语音发送接口END---------------------------------------------------------------------------------
     
     
     
     
     
     
     //浪驰短信发送接口START---------------------------------------------------------------------------------
     
     /**
      * 浪驰发送手机短信函数
      * @param string $mobile 发送的手机号码
      * @param string $content 发送的短信内容
      * @param string $senddate 定时发送的日期，立即发送可以不输入或者为空
      * @param string $sendtime 定时发送的时间，立即发送可以不输入或者为空
      * @param int $smstype 短信类型 （小灵通已经退市 所以默认是1即可）1表示移动、联通 ; 2表示小灵通
      * @return boolean FALSE：发送不成功；TRUE：发送成功
      */
     public function lanz_sms($mobile,$content,$senddate='',$sendtime='',$smstype='1')
     {
     	self::lanz_init();
     	$sURL = 'http://www.lanz.net.cn/LANZGateway/DirectSendSMSs.asp';
     	$post_data = array(
     			'UserID'   =>$this->lanz_userid,
     			'Account'  =>$this->lanz_account,
     			'Password' =>$this->lanz_password,
     			'SMSType'  =>$smstype,
     			'Phones'   =>$mobile,
     			'Content'  =>iconv( "UTF-8", "gb2312" , $content),
     			'SendDate' =>$senddate,
     			'Sendtime' =>$sendtime
     	);
     	/**
     	 * $result的可能返回值
     	 * @link http://www.lanz.com.cn/down.html
     	 * 
     	 1000：当前用户已经登录
     	 1001：当前用户没有登录
     	 1002：登录被拒绝（一般是账号和密码错误了）
     	 2001：短信发送失败
     	 2002：短信库存不足
     	 2003：存在无效的手机号码
     	 2004：短信内容包含禁用词语
     	 3001：没有要接收的短信
     	 3002：没有要接收的回复状态
     	 9001：JobID参数不符合要求
     	 9002：SendDate或SendTime参数不是有效日期
     	 9003：短信内容长度超过300(短信内容为空也会报这个错误)
     	 9004：参数不符合要求
     	 9099：其它系统错误；
     	 false:发送不成功成功
     	 true: 发送成功
     	 */
     	$result = self::lanz_httppost($sURL,http_build_query($post_data, '', '&'));
     	
     	if($result===TRUE)
     	{
     		return TRUE;
     	}
     	return FALSE;
     }
     
     /**
      * 浪驰短信接口配置初始化
      */
     private function lanz_init()
     {
     	$this->lanz_userid   = config_item('msg_lanz_userid');
     	$this->lanz_account  = config_item('msg_lanz_account');
     	$this->lanz_password = config_item('msg_lanz_password');
     }
     
     /**
      * 请求短信发送接口
      * @param string $sURL 请求地址
      * @param string $aPostVars 请求参数
      * @return boolean|string （TRUE:表示成功，否则为错误数字编码）
      */
     private function lanz_httppost($sURL,$aPostVars)
     {
     	$srv_ip = '219.136.252.188';//你的目标服务地址或频道.
     	$srv_port = 80;
     	$url = $sURL; //接收你post的URL具体地址
     	$fp = '';
     	$resp_str = '';
     	$errno = 0;
     	$errstr = '';
     	$timeout = 300;
     	$post_str = $aPostVars;//要提交的内容.
     
     	$fp = fsockopen($srv_ip,$srv_port,$errno,$errstr,$timeout);
     	if (!$fp)
     	{
     		return FALSE;
     	}
     
     	$content_length = strlen($post_str);
     	$post_header = "POST $url HTTP/1.1\r\n";
     	$post_header .= "Content-Type:application/x-www-form-urlencoded\r\n";
     	$post_header .= "User-Agent: MSIE\r\n";
     	$post_header .= "Host: ".$srv_ip."\r\n";
     	$post_header .= "Cookie: \r\n";
     	$post_header .= "Content-Length: ".$content_length."\r\n";
     	$post_header .= "Connection: close\r\n\r\n";
     	$post_header .= $post_str."\r\n\r\n";
     
     	fwrite($fp,$post_header);
     
     	while(!feof($fp))
     	{
     		$resp_str .= fgets($fp,4096);//返回值放入$resp_str
     	}
     
     	if( substr( $resp_str,strpos($resp_str,"<ErrorNum>")+10,strpos($resp_str,"</ErrorNum>") -strpos($resp_str,"<ErrorNum>")-10) ==0)
     	{
     	 	return TRUE;
     	}
     	else
     	{
     		return substr( $resp_str,strpos($resp_str,"<ErrorNum>")+10,strpos($resp_str,"</ErrorNum>") -strpos($resp_str,"<ErrorNum>")-10);//处理返回值.
     	}
     	
     	fclose($fp);
     }
     
     //浪驰短信发送接口END---------------------------------------------------------------------------------
     
     /**
      * 发送短信验证码
      * 
      * @param int $mobile 手机号
      * @param int $code 验证码
      * @param int $expiration_time 过期时间（分钟）
      * @return boolean
      * 
      * @author 杜嘉杰
      * @version 2015年6月4日 下午5:26:42
      */
     public function send_msg($mobile,$code,$expiration_time)
     {
     	/*
     	 * 模板内容(模板id:21136)：
     	 * 【众划算】您的验证码为：{1}，请在{2}分钟内完成验证，如被他人操作，请忽略。请勿向任何人提供您收到的验证码！
     	 */
     	$template_id = 21136; //模板id
     	$datas = array($code, $expiration_time); // 模板里面的数据
     	
     	return $this->send_template_sms($mobile,$template_id,$datas);
     }
     
     /**
      * 发送短信底层函数
      * 
      * @param int $mobile 手机号
      * @param int $template_id 模板编号(在第三方获取)
      * @param array $datas 模板里边的参数
      * 
      * @return boolean
      * 
      * @author 杜嘉杰
      * @version 2015年6月5日 上午9:25:30
      */
     public function send_template_sms($mobile, $template_id, $datas)
     {
     	require COMPATH."third_party/CCPRestSDK.php";
      
     	$account_sid = config_item('msg_ytx_account_sid');//主帐号,对应开官网发者主账号下的 ACCOUNT SID
     	$account_token = config_item('msg_ytx_auth_token');//主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
     	$app_id = config_item('msg_ytx_app_id');//在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID//应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
     	
     	if(!$account_sid)
     	{
     		log_message('error', '缺少配置:msg_ytx_account_sid,请在管理员后台【手机接口设置】进行设置。');
     		return FALSE;
     	}
     	if(!$account_token)
     	{
     		log_message('error', '缺少配置:account_token,请在管理员后台【手机接口设置】进行设置。');
     		return FALSE;
     	}
     	if(!$app_id)
     	{
     		log_message('error', '缺少配置:app_id,请在管理员后台【手机接口设置】进行设置。');
     		return FALSE;
     	}
     	
     	$server_ip = 'app.cloopen.com'; // 请求地址:沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com 生产环境（用户应用上线使用）：app.cloopen.com
     	$server_port = '8883'; //请求端口，生产环境和沙盒环境一致
     	$sof_version = '2013-12-26';//REST版本号，在官网文档REST介绍中获得。
     	
     	// 初始化REST SDK
     	$rest = new REST($server_ip, $server_port, $sof_version);
     	$rest->setAccount($account_sid, $account_token);
     	$rest->setAppId($app_id);

     	$result = $rest->sendTemplateSMS($mobile, $datas, $template_id);

     	if ($result->statusCode != 0) {
     		// 请求接口失败
     		log_message('error', print_r($result,true));
     		return FALSE;
     	}
     	return TRUE;
     }
}
?>