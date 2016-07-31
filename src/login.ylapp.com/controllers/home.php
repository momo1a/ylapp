<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 登录控制器
 * 处理登录相关调用
 * 
 * @author "韦明磊-众划算项目组<nicolaslei@163.com>"
 * 
 * @property Auth $auth
 * @property User_model $user_model
 */
class Home extends MY_Controller
{
	/**
	 * 登录页面
	 * 
	 * @update 在已登录的情况下允许重新登录---韦明磊by2014-07-31
	 * @return void
	 */
	public function index()
	{
		$cache_key = 'login_adver_img'; // 广告缓存标识
		
		$ad = $this->cache->get($cache_key);
		if ( !$ad || isset($_GET['fc']))
		{
			// 缓存没有就读库
			$this->load->model('advertisement_model');
			$ad = $this->advertisement_model->login_ad();
			if (empty($ad))
			{
				// 默认广告图片
				$ad['img'] = $this->config->item('domain_static') . 'images/login/banner.jpg?v=2';
			}
				
			if ( !isset($ad['link']) || empty($ad['link']))
			{
				$ad['link'] = $this->config->item('domain_www');
			}
			// 缓存600秒
			$this->cache->save($cache_key, $ad, 600);
		}
		
		// 保存的账号
		$account = $this->input->cookie(config_item('cookie_account'), TRUE);

		Template::set(array(
				'account' => $account ? $account : '',
				'to_url' => $this->_to_url(),
				'ad' => $ad,
		));
		Template::set_title('登录众划算');
		Template::render('index');
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * 验证登录
	 * 只接受AJAX方式提交
	 * 
	 * @update 在已登录的情况下允许重新登录---韦明磊by2014-07-31
	 * @return json/string 如果有callback传入,将放回一段JS脚本：callback(javascript脚本)或者JSON
	 */
	public function login()
	{	
		$account = trim($this->input->get_post('account', TRUE));
		$password = trim($this->input->get_post('password', TRUE));
		$to_url = $this->input->post('to', TRUE);

		if ($account && $password)
		{
			// 返回客户端的信息
			$return = array('state' => 'SUCCESS','message' => '登录成功','url' => site_url('success?to=' . urlencode($to_url)));
			
			// 执行登录
			if ($this->auth->login($account, $password, isset($_POST['remember'])) === FALSE)
			{
				// 取出错误
				$return['state'] = $this->auth->get_error();
				$return['message'] = Auth::$login_error_message[$return['state']];
				// 需要激活账号
				if (in_array($return['state'], array(Auth::LOGIN_STATUS_ISOLD, Auth::LOGIN_STATUS_NO_ACTIVATE)))
				{
					$return['url'] = $this->auth->finish_reg_url($this->auth->user['uid']);
				}
			}
			$result = json_encode($return);
			
			if ($this->input->get('callback'))
			{
				echo $this->input->get('callback', TRUE) . '(' . $result . ');';
			}
			else
			{
				// jsonp形式
				$this->output->set_content_type('application/json')
								->set_output($result);
			}
		}
	}
	
	/**
	 * app登录
	 * 
	 * @author 杜嘉杰
	 * @version 2014-6-21
	 */
	public function app_login(){
		// 返回json
		$account = trim($this->input->get_post('account', TRUE));
		$password = trim($this->input->get_post('password', TRUE));
		$token = trim($this->input->get_post('token', TRUE));
		$client_type    = intval($this->input->get_post('client_type',TRUE));
		$channel_name = trim($this->input->get_post('channel_name')); // 渠道名称
	
		// 返回客户端的信息
		
		if ($account == FALSE)
		{
			$return = array(	'code' => '331', 'msg' => '请输入用户名');
			echo json_encode($return);
			return;
		}
		if ($password == FALSE)
		{
			$return = array(	'code' => '332', 'msg' => '请输入密码');
			echo json_encode($return);
			return;
		}
		
		$uid = $this->auth->app_login($account, $password);
		if ( $uid === FALSE)
		{
			$code_map = array(
				Auth::LOGIN_STATUS_NOT_FOUND => 333, 
				Auth::LOGIN_STATUS_ISOLD => 334,
				Auth::LOGIN_STATUS_SEAL => 335,
				Auth::LOGIN_STATUS_NO_ACTIVATE => 336,
				Auth::LOGIN_STATUS_SHS_SEAL => 337,
				Auth::LOGIN_STATUS_WRONG_PASSWORD => 338
			);
			$error = $this->auth->get_error();
			
			$code = isset($code_map[$error]) ? $code_map[$error] : 339;//未知错误
			
			$err_msg = '';
			if($error == Auth::LOGIN_STATUS_ISOLD)
			{
				$err_msg = '账号认定为旧账户，请前往电脑登录激活';
			}else
			{
				$err_msg = Auth::$login_error_message[$error];
			}
			
			$return = array('code' => $code, 'msg' => $err_msg);
			echo json_encode($return);
			return;
		}
		
		//禁止管理员帐号登录APP
		if( $this->_appforbid_login($uid) )
		{
			$return = array('code' => '340', 'msg' => '禁止管理员帐号登录');
			echo json_encode($return);
			return;
		}
		
		$user = $this->db->select('utype')->from('user')->where('uid',$uid)->get()->row_array();
		if( $user['utype']!=1 )
		{
			$return = array('code' => '341', 'msg' => '亲，请使用买家账号登录');
			echo json_encode($return);
			return;
		}
		
		// 返回登录成功数据
		$user_data = $this->auth->app_user_data($uid);
		//记录客户端用户手机token标识
		$this->load->model('app_mobile_token_model');
		$this->app_mobile_token_model->addtoken($user_data['uid'],$user_data['uname'],$token,$client_type);
		
		// 记录渠道的来源
		$login_log = array(
			'uid' => $uid,
			'login_pathway' => $client_type==User_model::CLIENT_TYPE_ANDROID ? User_model::REG_FROM_ANDROID_LOCAL : User_model::REG_FROM_IOS_LOCAL,
			'channel_name' =>$channel_name,
			'dateline' => TIMESTAMP,
			'ip' => ip('int')
		);
		$this->load->model('analy_user_login_log_model');
		$this->analy_user_login_log_model->insert($login_log);

		$return = array(	'code' => '201', 'msg' => '登录成功', 'data'=>$user_data);
		
		echo json_encode($return);
	}
	
	/**
	 * 判断用户是否是管理员，管理员不能登录APP
	 */
	private function _appforbid_login( $uid )
	{
		// 超级管理员不能登录APP
		if( in_array($uid, $this->config->item('super_admin_uids')) )
		{
			return TRUE;
		}
		// 在管理员用户组中的用户也不能登录
		$roles = $this->db->where('user_id', $uid)->get('system_privilege_role_user')->row_array();
		if($roles)
		{
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * 外部站点登录信息同步到众划算站点
	 * 
	 * @todo (未完成)之前的这个流程有问题,查看后再修改
	 * @return void
	 */
	public function sync_login()
	{
		$js_callback 	= $this->input->get('callback', TRUE);
		$to_url 		= $this->input->get('to');
		$uid			= intval($this->input->get('uid'));
		$sign			= $this->input->get('sign');
		
		$js_param		= "false, '缺少参数或者签名验证失败：uid:{$uid},sign:{$sign}'";

		if ($uid && $sign && $this->auth->check_sign($uid, $sign))
		{
			// 从UC中获取用户
			$uc_user = $this->user_model->find_ucuser_by_uid($uid);
			// 获取众划算本地用户
			$local_user = $this->user_model->find_local_user($uid);
			if ( ! $local_user)
			{
				$this->auth->sync_user($uc_user); // 同步用户
			}
			// 保存登录信息
			$this->auth->_login_local($uc_user,FALSE);
			//验证成功信息
			$js_param = 'true';
			
			header("P3P: CP=CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR");
		}

		if($js_callback)
		{
			@header('Content-type:text/html;charset=utf8');
			echo($js_callback."({$js_param})");
		}
		else
		{
			if ($to_url) redirect($to_url);
			else redirect($this->config->item('domain_www'));
		}
		
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * 登录成功提示
	 * 
	 * @return void
	 */
	public function success()
	{
		$this->load->view('success', array(
				'to_url' => $this->_to_url(),
				'my_zhs_url' => AuthUser::is_buyer() ? config_item('domain_buyer') : config_item('domain_seller'),
				'sync_login_shikee' => $this->auth->sync_login_shikee_url(AuthUser::id())
		));
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * 登出
	 * 
	 * @return void
	 */
	public function logout()
	{
		// 清除登录状态
		AuthUser::clean();
		
		// 返回登录页面
		redirect('/');
	}
	
	/**
	 * 注销登录
	 * 
	 * @author 杜嘉杰
	 * @version 2014-7-4
	 */
	public function app_logout(){
		
		// 用户id
		$uid = $this->input->get_post('uid', TRUE);
		$uid = intval($uid);
		if ( ! $uid) {
			echo json_encode(array('code' => '301', 'msg' => '缺少参数:uid='.$uid));
			return;
		}
		
		// 签名
		$sign = $this->input->get_post('sign', TRUE);
		$sign = trim($sign);
		if ( ! $sign) {
			echo json_encode(array('code' => '301', 'msg' => '缺少参数:sign='.$sign));
			return;
		}
		
		// 注销
		$ret = $this->auth->app_logout($uid, $sign);
		if ($ret) {
			$cache_key = 'app_user_info_' . $uid;
			$this->cache->delete($cache_key);
			echo json_encode(array('code' => '201', 'msg' => '注销登录成功'));
			return;
		}else{
			echo json_encode(array('code' => '331', 'msg' => '注销登录失败'));
			return;
		}
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * 过滤登陆后的跳转URL,特定域名的URL设置成跳转到首页
	 * 
	 * @return $url         过滤后的跳转地址
	 */
	private function _to_url()
	{
		$url = urldecode(from_url());
		$no_to_domain = array('reg', 'login');
		
		$url_data = parse_url($url);
		$domain = explode('.', $url_data['host']);
		
		if(strpos($url , $this->config->item('cookie_domain')) && !in_array($domain[0], $no_to_domain))
		{
			return urlencode($url);
		}
		return urlencode($this->config->item('domain_www'));
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * 添加iframe小窗口登录页
	 * @author 宁天友
	 * @version 2015-6-12 9:54:25
	 */
	public function iframe()
	{
		$dialog = intval($this->input->get('dialog', TRUE));
		$dialog = $dialog > 1 ? 1 : $dialog;
		Template::set(array(
			'dialog' => $dialog,
		));
		Template::render('iframe');
	}
}
/* End of file home.php */
/* Location: ./application/controllers/home.php */