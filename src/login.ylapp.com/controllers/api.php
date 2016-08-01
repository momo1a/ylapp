<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 第三方登录接口处理控制器
 * 
 * @author "韦明磊-众划算项目组<nicolaslei@163.com>"
 * 
 * @property Auth						$auth
 * @property User_login_bind_model		$user_login_bind_model
 * @property User_login_bind_log_model	$log_model
 * @property User_model					$user_model
 * @property auth  $auth
 */
class Api extends MY_Controller
{
	/**
	 * 来源地址保存的KEY
	 * @var string
	 */
	private static $_fromurl_key_name = 'YL_from_url';
	
	/**
	 * 用于存储第三方用户授权信息
	 * @var array
	 */
	private $third_party_user = FALSE;
	
	/**
	 * 第三方用户类型(1、QQ,2、weibo,...)
	 * @var int
	 */
	private $bind_type = 0;
	
	//------------------------------------------------------------------------------
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model(array('user_model','user_login_bind_model'));
	}
	
	//------------------------------------------------------------------------------
	
	public function index()
	{
		$this->event('qq', 'login');
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 授权统一入口
	 * 
	 * @param string $driver 登录方式
	 * @param string $action 操作
	 */
	public function event($driver = 'qq', $action = 'login')
	{
		if ( ! in_array($action, array('login', 'bind', 'updatebind')))
		{
			log_message('error', '[非法操作][QQ登录]['.$action.']没有该操作,来自IP:'.$this->input->ip_address());
			show_404();
		}
		$driver = strtolower($driver);
		
		// 登录才会记录来源地址用于登录授权完成后跳转
		if ($action == 'login')
		{
			// 存储来源地址
			$from_url = from_url();
			if (stripos($from_url, 'login') !== FALSE)
			{
				$from_url = $this->config->item('domain_www');
			}
			// 存放在Cookie中,用于回调授权完成之后进行跳转
			$this->input->set_cookie(self::$_fromurl_key_name, $from_url, time()+600);
		}
		
		// 加载驱动
		$this->load->driver('third_party_login', array(
				'adapter'		=> $driver,
				'callback_url'	=> site_url('api/callback/' . $driver . '/' . $action)
		));
		
		$this->third_party_login->login();
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 第三方登录授权回调处理
	 * 
	 * 所有回调处理在这里统一做分配
	 * 
	 * @param sting $driver
	 * @param string $action
	 * 
	 * @return void
	 */
	public function callback($driver, $action)
	{
		$this->load->driver('third_party_login', array(
			'adapter'		=> $driver,
			'callback_url'	=> site_url('api/callback/' . $driver . '/' . $action)
		));

		$this->third_party_user = $this->third_party_login->callback();
		if ($this->third_party_user === FALSE)
		{
			$error = $this->third_party_login->get_error();// 授权回调错误信息,可以写入日志
			show_message($error?$error:'服务器未知错误', array(), array('backurl'=>config_item('domain_buyer').'login_bind/qq'));
		}
		$this->bind_type = User_login_bind_model::type_string2int($driver); // 将string类型的登录方式映射为int类型
		$method = '_' . $action;
		if (method_exists($this, $method))
		{
			$this->{$method}(); // 执行动作
		}
		else
		{
			log_message('error', '[非法操作][QQ登录]['.$action.']没有该操作,来自IP:'.$this->input->ip_address());
			show_404();
		}
	}

	//------------------------------------------------------------------------------
		
	/**
	 * 第三方登录处理
	 * 
	 * @param string $driver
	 * @param array $user
	 */
	private function _login()
	{
		// 强制浏览器不缓存页面
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', FALSE);
		header('Pragma: no-cache');
		
		/* 取出授权之前的来源地址 */
		$from_url = $this->input->cookie(self::$_fromurl_key_name, TRUE);
		$from_url = $from_url ? $from_url : urlencode($this->config->item('domain_www'));
		$this->input->set_cookie(self::$_fromurl_key_name, '', ''); // 删除cookie
		
		if (AuthUser::is_logged_in())
		{
			redirect(urldecode($from_url));
		}

		// 查看用户是否已经授权该账号
		$local_user = $this->user_login_bind_model->has_authorize($this->bind_type, $this->third_party_user['open_id']);
		
		// 该第三方账户是否已经绑定?
		if ($local_user === FALSE)
		{
			/* 将第三方授权用户信息写入缓存,保存时间为20分钟 */
			$this->third_party_user['type'] = $this->bind_type;
			$openuserkey = md5($this->third_party_user['open_id']);
			$this->cache->save($openuserkey, $this->third_party_user, 1200);
			
			$this->view_data['openuserkey'] = $openuserkey;
			$this->view_data['nickname']	= $this->third_party_user['nickname'];
			$this->view_data['avatar']		= $this->third_party_user['avatar'];
			$this->view_data['driver']		= $this->bind_type;
			$this->view_data['to_url']		= $from_url;
			
			Template::set_title('QQ绑定-众划算官方网站');
			// 显示绑定页面
			Template::render('bind_account');
		}
		else
		{
			$result = $this->auth->login_by_uid($local_user['uid'], FALSE); // login
			if ($result === FALSE)
			{
				log_message('error', '[程序出错][QQ登录][登录]无法执行登录操作,来自用户：'.AuthUser::id());
				show_message(Auth::$login_error_message[$this->auth->get_error()],
					array(),
					array('backurl'=>$this->config->item('domain_buyer').'login_bind/qq'));
			}
			else
			{
				redirect('success?to=' . $from_url);
			}
		}
	}
	
	/**
	 * app第三方登录处理
	 *
	 * @param string $driver
	 * @param array $user
	 */
	public function app_qq_login()
	{
		$qq_user['open_id']= $this->input->get_post('open_id');
		$qq_user['access_token']=$this->input->get_post('access_token');
		$qq_user['refresh_token']=$this->input->get_post('refresh_token');
		$qq_user['expires_in']=$this->input->get_post('expires_in');
		$qq_user['nickname']=$this->input->get_post('nickname');
		$qq_user['gender']=$this->input->get_post('gender');
		$qq_user['avatar']=$this->input->get_post('avatar');
		$qq_user['type']=$this->input->get_post('type');
		$server_data['token'] = trim($this->input->get_post('token'));
		$server_data['client_type'] = trim($this->input->get_post('client_type'));

		if(empty($qq_user['open_id'])||empty($qq_user['access_token'])){
			echo json_encode( array(	'code' => 330, 'msg' =>'QQ通信失败'));
			return;
		}
		
		if( !in_array( $server_data['client_type'],array(App_mobile_token_model::CLIENT_TYPE_ANDROID,App_mobile_token_model::CLIENT_TYPE_IOS) ) ){
			echo json_encode( array(	'code' => 332, 'msg' =>'客户端类型有误'));
			return;
		}

		// 查看用户是否已经授权该账号
		$local_user = $this->user_login_bind_model->has_authorize($qq_user['type'], $qq_user['open_id']);

		// 该第三方账户是否已经绑定?
		if ($local_user === FALSE)
		{
			$this->load->library('Cache_memcached', NULL, 'cache');
			$user_key='app_'.$qq_user['open_id'];
			$this->cache->save($user_key, $qq_user, 300);
			echo json_encode( array(	'code' => 203, 'msg' =>'需要绑定帐号'));
			return;
		}
		else
		{
			$result = $this->auth->login_by_uid($local_user['uid'], FALSE); //登录操作
			if ($result === FALSE)
			{
				echo json_encode( array(	'code' =>331, 'msg' =>'QQ登录失败'));
				return;
			}
			else
			{
				$user_data = $this->auth->app_user_data($local_user['uid']);
				//记录客户端用户手机token标识
				$this->load->model('app_mobile_token_model');
				$this->app_mobile_token_model->addtoken($user_data['uid'],$user_data['uname'],$server_data['token'],$server_data['client_type'] );
				$return = array(	'code' => '201', 'msg' => '登录成功', 'data'=>$user_data);
				echo json_encode($return);
				return;
			}
		}
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 用在已登录的情况进行绑定
	 * 
	 * 同一种登录方式只能绑定一个账号
	 * 
	 * @param string $driver	第三方登录类型
	 * @param array $user		第三方登录用户信息
	 * 
	 * @return void
	 */
	private function _bind()
	{
		if ( !AuthUser::is_logged_in())
		{
			log_message('error', '[非法操作][QQ登录][绑定QQ]没有登录,来自IP:'.$this->input->ip_address());
			$msg = array(
				'msg'=>'没有登录不允许绑定！'
			);
			$url = $this->config->item('domain_buyer').'display/qq_bind_failure?';
			$this->_bind_message($url,$msg);
		}
		if (AuthUser::is_seller())
		{
			log_message('error', '[非法操作][QQ登录][绑定QQ]商家是无入口进入,来自用户：'.AuthUser::id().',IP:'.$this->input->ip_address());
			$msg = array(
				'msg'=>'商家不开放QQ绑定功能！'
			);
			$url = $this->config->item('domain_buyer').'display/qq_bind_failure?';
			$this->_bind_message($url,$msg);
		}

		if ($this->user_login_bind_model->has_bind(AuthUser::id(), $this->bind_type))
		{
			$msg = array(
				'msg'=>'一个众划算账号只能绑定一个QQ账号！',
				'backname' => '个人中心',
				'backurl'=>$this->config->item('domain_buyer')
			);
			$url = $this->config->item('domain_buyer').'display/qq_bind_failure?';
			$this->_bind_message($url,$msg);
		}
		
		if ($this->user_login_bind_model->has_authorize($this->bind_type, $this->third_party_user['open_id']))
		{
			$msg = array(
		 		'msg'=>'绑定失败，该QQ账号已被绑定！',
				'backname'=> '重新绑定',
				'backurl'=>site_url('api/event/qq/bind')
			);
			$url = $this->config->item('domain_buyer').'display/qq_bind_failure?';
			$this->_bind_message($url,$msg);
		}
		else
		{
			// 绑定账号
			if ($this->user_login_bind_model->fresh_bind(AuthUser::id(), AuthUser::account(), $this->bind_type, $this->third_party_user))
			{
				$url = $this->config->item('domain_buyer').'display/bind_success';
				redirect($url);
			}
			else
			{
				log_message('error', '[程序出错][QQ登录][绑定QQ]无法写入绑定信息,来自用户：'.AuthUser::id());
				$msg = array(
					'msg'=>'绑定失败，服务未知错误！',
					'backname'=> '重新绑定',
					'backurl'=>site_url('api/event/qq/bind')
				);
				$url = $this->config->item('domain_buyer').'display/qq_bind_failure?';
				$this->_bind_message($url,$msg);
			}
		}
	}
	
	//------------------------------------------------------------------------------
	/**
	 * 绑定QQ后返回信息
	 * @param string $url
	 */
	private function _bind_message($url,$msg){
		redirect($url.http_build_query($msg));
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 用户更新绑定账号
	 * 
	 * @param string $driver	第三方登录类型
	 * @param array $user		第三方登录用户信息
	 * 
	 * @return void
	 */
	private function _updatebind()
	{
		if ( !AuthUser::is_logged_in())
		{
			log_message('error', '[非法操作][QQ登录][更新绑定]没有登录,来自IP:'.$this->input->ip_address());
			show_message('没有登录不允许修改绑定');
		}

		if ($this->user_login_bind_model->has_authorize($this->bind_type, $this->third_party_user['open_id']))
		{
			show_message('该QQ账号已绑定了另一个众划算账户,不用重复绑定', array(
				array('name'=>'个人中心','url'=>$this->config->item('domain_buyer'))),
				array('backurl'=>$this->config->item('domain_buyer').'login_bind/qq'));
		}

		$uid		= AuthUser::id();
		$bind_data	= $this->user_login_bind_model->has_bind($uid, $this->bind_type);
		if ($bind_data)
		{
			if ($bind_data['open_id'] == $this->third_party_user['open_id'])
			{
				show_message('绑定没有变化，无法更新！', array(
					array('name'=>'个人中心','url'=>$this->config->item('domain_buyer'))),
					array('backurl'=>$this->config->item('domain_buyer').'login_bind/qq'));
			}
			
			if ($this->bind_type != $bind_data['type'])
			{
				show_message('绑定登录类型不一致，无法更新！',array(
					array('name'=>'个人中心','url'=>$this->config->item('domain_buyer'))),
					array('backurl'=>$this->config->item('domain_buyer').'login_bind/qq'));
			}

			// 修改绑定信息
			if ($this->user_login_bind_model->replace_bind($uid, AuthUser::account(), $this->bind_type, $this->third_party_user))
			{
				show_message('绑定已更新',array(
					array('name'=>'查看绑定信息','url'=>$this->config->item('domain_buyer').'login_bind/qq')),
					array('backurl'=>config_item('domain_buyer').'login_bind/qq'));
			}
			else
			{
				log_message('error', '[程序出错][QQ登录][更新绑定]无法更新绑定信息,来自用户：'.AuthUser::id());
				show_message('绑定失败！无法更新数据', array(
					array('name'=>'个人中心','url'=>$this->config->item('domain_buyer'))),
					array('backurl'=>$this->config->item('domain_buyer').'login_bind/qq'));
			}
		}
		else
		{
			log_message('[非法操作][QQ登录][更新绑定]没有绑定信息是无法通过页面进入这个地址,来自用户：'.$uid);
			show_message('没有绑定记录');
		}
	}
	
	//------------------------------------------------------------------------------
	
	/**
	 * 发送验证邮件
	 *
	 * @update 使用全站统一验证码(韦明磊修改于2014-07-28)
	 * @update 封装发送方法,使用队列发送验证码(韦明磊修改于2014-12-31)
	 * @todo 一些安全限制：禁止别站提交、限定提交的时间间隔
	 *
	 * @return void
	 */
	public function send_email()
	{
		$email = trim($this->input->post('email'));
		// 验证邮件地址是否有效
		if ($email && preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) {
			// 检查Email是否已被使用
			if ($this->user_model->uc_email_exists($email)) {
				exit(json_encode(array('message' => '发送失败：该邮箱已被使用', 'state' => FALSE)));
			}
			// 判断互联支付手机号是否重复
			$this->load->library('hlpay_v2');
			if($this->hlpay_v2->hlpay_user_exixts($email,'email'))
			{
				exit(json_encode(array('message' => '发送失败：该邮箱已在互联支付使用，请更换其他的邮箱。', 'state' => FALSE)));
			}
			
			if(send_email_code($email, 'login')) {
				exit(json_encode(array('message' => '验证码发送成功', 'state' => TRUE)));
			}else {
				exit(json_encode(array('message' => '验证码发送失败，您提供的邮箱不存在或者未激活！', 'state' => FALSE)));
			}
		}
		else
			exit(json_encode(array('message' => '发送失败:无效的电子邮箱', 'state' => FALSE)));
	}
	
	//------------------------------------------------------------------------------

	/**
	 * 显示验证码
	 * @return void
	 */
	public function captcha()
	{
		$this->load->driver('captcha');
		$this->captcha->show_captcha_image();
	}
}

/* End of file api.php */
/* Location: ./application/controllers/api.php */