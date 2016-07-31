<?php
require COMPATH."third_party/XingeApp.php";
/*
依赖常量：
// 信鸽推送android应用号
define('XINGE_ACCESS_ID_ANDROID', '2100083121');
// 信鸽推送android应用密钥
define('XINGE_SECRET_KEY_ANDROID', 'f08fd1a8f3fd39774faf604b77f2e027');

// 信鸽推送ios应用号
define('XINGE_ACCESS_ID_IOS', '2200083122');
// 信鸽推送ios应用密钥
define('XINGE_SECRET_KEY_IOS', '3da7a8bfad9dd841deb204b6eb5c755b');
// 信鸽推送ios的工作模式:1表示生产环境，2表示开发环境
define('XINGE_IOS_ENVIRONMENT', 1);
 */


/**
 * 消息推送类库
 * @author dujiajie
 *
 */
class App_push_xin_ge extends CI_Driver implements App_push_driver{
	private  $CI = NULL;
	
	/**
	 * @var 推送到全部设备
	 */
	public static $DEVICE_ALL = 1;
	/**
	 * @var 推送到安卓设备
	 */
	public static $DEVICE_ANDROID = 2;
	/**
	 * @var 推送到全部ios设备
	 */
	public static $DEVICE_IOS=3;
	
	/**
	 * 错误原因
	 */
	protected $error = NULL;
	
	/**
	 * @var 群发推送成功后返回的id
	 */
	protected $push_id = 0;
	
	/**
	 * 令牌记录
	 * @var array
	 */
	protected $mobile_token = NULL;
	
	public function __construct(){
		$this->CI  = &get_instance();
	}
	
	/**
	 * 返回最近操作的错误提示
	 */
	public function error() {
		return $this->error;
	}
	
	/**
	 * 获取群发推送成功后返回的id
	 * @return int
	 * @version 2014-9-9
	 */
	public function get_push_id(){
		return $this->push_id;
	}
	/**
	 * 根据uid发送单个消息推送到客户端
	 * @param int $uid:目标用户uid
	 * @param string $content:内容
	 * @param int $time:执行时间，0为马上推送，大于0为定时发送（最大支持定时未来三天发送）
	 * @param array $custom:推送的自定义参数，格式为：array('type'=>1001,'value'=>array('url'=>'http://www.baidu.com'))
	 * @return boolean
	 * @author 杜嘉杰
	 * @version 2014-9-5
	 */
	public function push_app_single($uid, $content, $time=0, $custom=NULL){
		// 使用php推送
		 //return $this->push_app_single_php($uid, $content, $time, $custom); 
		
		//使用java推送
		return $this->push_app_single_java($uid, $content, $time, $custom);
	}
	
	/**
	 * 根据uid发送单个消息推送到客户端（使用php请求）
	 * @param int $uid:目标用户uid
	 * @param string $content:内容
	 * @param int $time:执行时间，0为马上推送，大于0为定时发送（最大支持定时未来三天发送）
	 * @param array $custom:推送的自定义参数，格式为：array('type'=>1001,'value'=>array('url'=>'http://www.baidu.com'))
	 * @return boolean
	 * @author 杜嘉杰
	 * @version 2014-9-5
	 */
	public function push_app_single_php($uid, $content, $time=0, $custom=NULL){
		$custom = $this->set_custom_access_id($custom);
		
		// 获取该用户的设备号
		$mobile_token = $this->get_mobile_token($uid);
		
		if (isset($mobile_token)==FALSE || isset($mobile_token['token'])==FALSE) {
			$this->error = array('errcode' => 'NOT_EXISTS_TOKEN', 'errtxt' => '未找到客户端设备号，无法推送消息。');
			return FALSE;
		}
		
		$ret = FALSE;
		
		// 根据客户端推送消息
		switch ($mobile_token['client_type']){
			// android
			case App_mobile_token_model::CLIENT_TYPE_ANDROID:
				$ret = $this->push_android($content, $mobile_token['token'], $time, $custom); 
				break;
			//ios
			case App_mobile_token_model::CLIENT_TYPE_IOS:
				$ret = $this->push_ios($content, $mobile_token['token'], $time, $custom);
				break;
			default:
				$this->error = array('errcode' => 'CLIENT_TYPE_NOT_MATCH', 'errtxt' => '未找到客户端设备号，无法推送消息。');
				return FALSE;;
		}
		
		return $ret;
	}
	
	/**
	 * 推送单个android客户端
	 * @param string $content:内容
	 * @param string $token:客户端的token
	 * @param int $time:推送时间戳，0表示立即推送
	 * @param array $custom:自定义参数
	 * @return boolean
	 * @author 杜嘉杰
	 * @version 2014-9-9
	 */
	public function push_android($content, $token, $time=0, $custom=NULL){
		$custom = $this->set_custom_access_id($custom);
		
		$push = $this->init_xinge(self::$DEVICE_ANDROID);
		
		$mess = $this->message_android($content, $time, $custom);
		// 成功：ret_code=0 
		$ret = $push->PushSingleDevice($token, $mess);
		if(isset($ret['ret_code']) && $ret['ret_code']==0){
			return TRUE;
		}else{
			$this->error = array('errcode' => 'PUSH_ERROR', 'errtxt' => $ret['err_msg']);
			return FALSE;
		}
	}
	
	/**
	 * 整理android消息内容
	 * @param unknown $content
	 * @param unknown $time
	 * @param unknown $custom
	 * @return Message
	 * @author 杜嘉杰
	 * @version 2014-9-9
	 */
	public function message_android($content, $time=0, $custom=NULL){
		$custom = $this->set_custom_access_id($custom);
		$mess = new Message();
		
		// 发送时间
		if($time > 0){
			$mess->setSendTime(date('Y-m-d H:i:s', $time));
		}
		
		// 自定义参数
		if($custom){
			$mess->setCustom($custom);
		}
		
		//$mess->setType(Message::TYPE_NOTIFICATION);
		$mess->setType(Message::TYPE_MESSAGE);
		$mess->setTitle('众划算'); // 标题写死“众划算”
		$mess->setContent($content);
		
		$style = new Style(0);
		#含义：样式编号0，响铃，震动，不可从通知栏清除，不影响先前通知
		$style = new Style(0,1,1,1,0);
		$action = new ClickAction();
		$action->setActionType(ClickAction::TYPE_ACTIVITY);
		$mess->setStyle($style);
		$mess->setAction($action);
		
		return $mess;
	}
	
	/**
	 * 推送单个ios客户端
	 * @param string $content:内容
	 * @param string $token:客户端的token
	 * @param int $time:推送时间戳，0表示立即推送
	 * @param array $custom:自定义参数
	 * @return boolean
	 * @author 杜嘉杰
	 * @version 2014-9-9
	 */
	public function push_ios($content, $token, $time=0, $custom=NULL){
		$custom = $this->set_custom_access_id($custom);
		// 获取常量配置
		$environment = XINGE_IOS_ENVIRONMENT;
		$environment OR show_message('请配置常量：XINGE_IOS_ENVIRONMENT');
		
		$push = $this->init_xinge(self::$DEVICE_IOS);
		
		// 自定义参数
		$mess = $this->message_ios($content, $time, $custom);
		
		$ret = $push->PushSingleDevice($token, $mess, $environment);
		// 成功：ret_code=0
		if(isset($ret['ret_code']) && $ret['ret_code']==0){
			return TRUE;
		}else{
			$this->error = array('errcode' => 'PUSH_ERROR', 'errtxt' =>$ret['err_msg'] );
			return FALSE;
		}
	}
	
	/**
	 * 整理ios消息的内容
	 * @param int $content:消息内容
	 * @param int $time:发送时间戳，0表示立即发送
	 * @param array $custom:自定义参数
	 * @return MessageIOS
	 * @author 杜嘉杰
	 * @version 2014-9-9
	 */
	public function message_ios($content, $time=0, $custom=NULL){
		$custom = $this->set_custom_access_id($custom);
		$mess = new MessageIOS();
		$mess->setExpireTime(86400);
		
		if($time > 0){
			$mess->setSendTime(date('Y-m-d H:i:s',$time));
		}
		
		if($custom){
			$mess->setCustom($custom);
		}
		
		$mess->setAlert($content);
		$mess->setBadge(1);
		$mess->setSound("bugunao.caf");
		$acceptTime = new TimeInterval(0, 0, 23, 59);
		
		$mess->addAcceptTime($acceptTime);

		return $mess;
	}
	
	/**
	 * 群发所有android客户端的推送
	 * @param string $content：内容
	 * @param int $time:推送时间戳，0表示马上推送
	 * @param array $custom:自定义参数
	 * @return boolean
	 * @author 杜嘉杰
	 * @version 2014-9-9
	 */
	public function push_all_devices_android($content, $time=0, $custom=NULL){
		$custom = $this->set_custom_access_id($custom);
		$this->push_id = 0;
		$push = $this->init_xinge(self::$DEVICE_ANDROID);
		
		$mess = $this->message_android($content, $time, $custom);
		
		$ret = $push->PushAllDevices(XingeApp::DEVICE_ANDROID, $mess);
		// 成功：ret_code=0
		if(isset($ret['ret_code']) && $ret['ret_code']==0){
			$this->push_id = $ret['result']['push_id'];
			return TRUE;
		}else{
			$this->error = array('errcode' => 'PUSH_ERROR', 'errtxt' =>$ret['err_msg'] );
			return FALSE;
		}
	}
	
	/**
	 * 群发所有ios客户端的推送
	 * @param string $content：内容
	 * @param int $time:推送时间戳，0表示马上推送
	 * @param array $custom:自定义参数
	 * @return boolean
	 * @author 杜嘉杰
	 * @version 2014-9-9
	 */
	public function push_all_devices_ios($content, $time=0, $custom=NULL){
		$custom = $this->set_custom_access_id($custom);
		$this->push_id = 0;
		// 获取常量配置
		$environment =  XINGE_IOS_ENVIRONMENT;
		$environment OR show_message('请配置常量：XINGE_IOS_ENVIRONMENT');

		$push = $this->init_xinge(self::$DEVICE_IOS);
		$mess = $this->message_ios($content, $time );
		$ret = $push->PushAllDevices(0, $mess,$environment);

		// 成功：ret_code=0
		if(isset($ret['ret_code']) && $ret['ret_code']==0){
			$this->push_id = $ret['result']['push_id'];
			return TRUE;
		}else{
			$err_msg = isset($ret['err_msg']) ? $ret['err_msg'] : '';
			$this->error = array('errcode' => 'PUSH_ERROR', 'errtxt' =>$err_msg );
			return FALSE;
		}
	}
	
	/**
	 * 创建信鸽对象
	 * @param int $client_type:android还是ios
	 * @return XingeApp
	 * @author 杜嘉杰
	 * @version 2014-9-5
	 */
	protected function init_xinge($client_type){
		// 获取常量配置
		switch ($client_type){
			// android
			case self::$DEVICE_ANDROID:
				$access_id = XINGE_ACCESS_ID_ANDROID;
				$secret_key = XINGE_SECRET_KEY_ANDROID;
				break;
			// ios
			case self::$DEVICE_IOS:
				$access_id = XINGE_ACCESS_ID_IOS;
				$secret_key = XINGE_SECRET_KEY_IOS;
				break;
			default:show_error('未知推送客户端类型');
		}
		
		$access_id OR show_error('请配置常量：XINGE_ACCESS_ID_ANDROID');
		$secret_key OR show_error('请配置常量：XINGE_SECRET_KEY_ANDROID');
		
		$push = new XingeApp($access_id, $secret_key);
		return $push;
	}
 
	/**
	 * 查询推送消息的状态
	 * @param array $push_ids:推送消息的id，array(1,2,3);
	 * @param int $client_type:设备类型；
	 * @return array
	 * @author 杜嘉杰
	 * @version 2014-9-9
	 */
	public function query_push_status($push_ids, $client_type){
		// 把int型的id转换成string类型，否则信鸽接口不认识这个值
		$push_id_arr = array();
		foreach ($push_ids as $item){
			$push_id_arr[] = trim($item);
		}
		$push = $this->init_xinge($client_type);
		$ret = $push->QueryPushStatus($push_id_arr);
		return ($ret);
	}

	/**
	 * 根据uid发送单个消息推送到客户端（使用java请求请求）
	 * @param int $uid:目标用户uid
	 * @param string $content:内容
	 * @param int $time:执行时间，0为马上推送，大于0为定时发送（最大支持定时未来三天发送）
	 * @param array $custom:推送的自定义参数，格式为：array('type'=>1001,'value'=>array('url'=>'http://www.baidu.com'))
	 * @return boolean
	 * @author 杜嘉杰
	 * @version 2014-9-5
	 */
	public function push_app_single_java($uid, $content, $time=0, $custom=NULL){
		$custom = $this->set_custom_access_id($custom);
		$_STOMP			= NULL;
		$_queue_theme	= '';
		$CI				= &get_instance();
	
		if ( ! extension_loaded('Stomp')) {
			$this->error = array('errcode' => 'STOMP_CONNETC_ERROR', 'errtxt' => 'Stomp扩展没有安装.');
			log_message('error', 'Stomp扩展没有安装.');
			return FALSE;
		}
	
		if ($CI->config->load('stomp', TRUE, TRUE)) {
			$config = $CI->config->item('stomp');
			// 队列主题
			if (isset($config['singel_app_push'])) {
				$_queue_theme = $config['singel_app_push'];
			}else
			{
				$this->error = array('errcode' => 'CONFIG_ERROR', 'errtxt' => 'stomp缺少配置:singel_app_push.');
				log_message('error', 'stomp缺少配置:singel_app_push');
				return FALSE;
			}

			try {
				$_STOMP = new Stomp($config['broker'], $config['name'], $config['password']);

			} catch(StompException $e) {
				$this->error = array('errcode' => 'STOMP_OPEN_ERROR', 'errtxt' => 'Stomp开启失败');
				log_message('error', 'Stomp开启失败:'.$e->getMessage());
				return FALSE;
			}
		}else {
			$this->error = array('errcode' => 'CONFIG_FILE_NOT_FIND', 'errtxt' => 'Stomp配置文件没有找到.');
			log_message('error', 'Stomp配置文件没有找到.');
			return FALSE;
		}
	
		$arr_message = array(
			'uid' 			=> $uid,
			'title'			=> '众划算',
			'content'	=> $content,
			'custom' 	=>$custom
		);
		
		$message = json_encode($arr_message);

		if ( ! $_STOMP->send($_queue_theme, $message)) {
			$this->error = array('errcode' => 'SEND_ERROR', 'errtxt' => '消息推送失败.');
			log_message('error', "消息推送{$message}发送失败,错误原因:".$_STOMP->error());
			return FALSE;
		}
		return TRUE;
	}
	
	
	/**
	 * 获取该用户的设备令牌
	 * 
	 * @param unknown $uid
	 * @return multitype:
	 * 
	 * @author 杜嘉杰
	 * @version 2015年5月7日 上午9:44:38
	 */
	private function get_mobile_token($uid)
	{
		if($this->mobile_token)
		{
			return $this->mobile_token;
		}
		$this->mobile_token = $this->CI->db->select('*')->from( App_mobile_token_model::$table_name)->where('uid',$uid)->get()->row_array();
		return $this->mobile_token;
	}
	
	/**
	 * $custom设置access_id
	 * 
	 * @param array $custom
	 * @return array $custom
	 * 
	 * @author 杜嘉杰
	 * @version 2015年5月7日 上午10:11:09
	 */
	private function set_custom_access_id($custom)
	{
		if ($custom == NULL && isset($custom['access_id'])==FALSE && isset($custom['$uid'])==FALSE)
		{
			return $custom;
		}

		// 获取该用户的设备类型
		$mobile_token = $this->get_mobile_token($custom['uid']);
		if( ! $mobile_token )
		{
			return $custom;
		}
		
		$access_id = 0;
		switch ($mobile_token['client_type']){
			// android
			case App_mobile_token_model::CLIENT_TYPE_ANDROID:
				$access_id = XINGE_ACCESS_ID_ANDROID;
				break;
			//ios
			case App_mobile_token_model::CLIENT_TYPE_IOS:
				$access_id = XINGE_ACCESS_ID_IOS;
				break;
			default:
				// 不在任何处理
				break;
		}
		$custom['access_id'] = $access_id;
		
		return $custom;
	}
	
}