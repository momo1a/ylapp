<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 总控制器
 *
 * 主要调配一些公用的类库或者存放一些公用的方法
 *
 * @package		ZHS
 * @subpackage	Libraries
 * @author		韦明磊
 * @category	core
 * @link		http://www.zhonghuasuan.com/
 * @see 		CI_Controller
 */
class ZHS_Base_Controller extends CI_Controller
{
	/**
	 * construct
	 * @update 韦明磊by:2014-12-30修复一个BUG,改BUG导致部分routes->uri重写后面不带'/'会出现404(统一后面不带'/')
	 */
	public function __construct()
	{
		parent::__construct();

		/*
		 * 通过反射判断方法参数数量是否合法,
		 * 防止非法请求通过页面缓存进行攻击.
		 */
		$class_reflection = new ReflectionClass($this);
		$method_reflection = $class_reflection->getMethod($this->router->method);
		$argnum = count(array_slice($this->uri->rsegments, 2));
		
		if ($method_reflection->getNumberOfRequiredParameters() > $argnum
			|| $method_reflection->getNumberOfParameters() < $argnum)
		{
			show_404(get_class($this)."/{$this->router->method}");
		}
		
		// 加载公用类库
		$this->load->library('cache_memcached', NULL, 'cache');
		$this->load->library('authUser');
		$this->load->helper('application');
	}
}

#---------------------------------------------------------------------------
#---------------------------------------------------------------------------

/**
 * 众划算的页面显示总控制器
 * @package		ZHS
 * @subpackage	Libraries
 * @author		韦明磊<nicolaslei@163.com>
 * @category	core
 * @link		http://www.zhonghuasuan.com/
 *
 */
class ZHS_Controller extends ZHS_Base_Controller
{
	public $view_data = array();
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('template');
	}
}

#---------------------------------------------------------------------------
#---------------------------------------------------------------------------

/**
 * 请求缓存控制器
 * 
 * @package		ZHS
 * @subpackage	Libraries
 * @author		韦明磊
 * @category	core
 * @link		http://www.zhonghuasuan.com/
 * @see 		CI_Controller
 */
class ZHS_Request_Cache_Controller extends ZHS_Controller
{
	/**
	 * 自定义请求缓存KEY
	 * @var array
	 * @example array('方法名'=>'缓存KEY')
	 */
	var $request_cache_keys = NULL;
	
	/**
	 * 定义当前站点使用的二级域名
	 * 用于定位站点中的请求是否使用缓存
	 * @var string
	 */
	var $SLD = 'www';
	
	/**
	 * 默认进行缓存的时间
	 * @var int
	 */
	var $default_cache_time = array();
	
	//---------------------------------------------------------------------------
	
	/**
	 * 映射方法
	 *
	 * 用于缓存的调用
	 *
	 * @param string $method		请求的方法
	 * @param array $method_param	请求的参数
	 *
	 * @return void
	 */
	public function _remap($method, $method_param)
	{
		if ( ! method_exists($this, $method))
		{
			show_404(get_class($this)."/{$method}");
		}
	
		$this->load->config('requests_cache');
		$config = $this->config->item('cache.requests');
	
		$page_cache_config = isset($config[$this->SLD][$this->router->class][$method])
							? $config[$this->SLD][$this->router->class][$method]
							: FALSE;
		unset($config);

		// 如果缓存配置存在,那说明该请求将使用缓存
		if ($page_cache_config)
		{
			$cache_key = $this->_request_cache_key($method, $page_cache_config);
			
			// 从缓存读取数据
			if (!isset($_GET['fc']) AND ($data = $this->cache->get($cache_key)))
			{
				$this->output->set_output($data);
				return; // 退出(控制器执行结束)
			}

			// 缓存时间
			if (isset($this->default_cache_time[$method])) 
			{
				$cache_time = $this->default_cache_time[$method];
			} 
			else 
			{
				$cache_time = isset($page_cache_config['cache_time']) ? intval($page_cache_config['cache_time']) : 3600;
			}
	
			// 开启HOOKS(保存缓存)
			$this->hooks->enabled = TRUE;
				
			// 在控制器(post_controller)执行完成之后调用这个钩子
			$this->hooks->hooks['post_controller'][] = array(
					'function'	=> 'save_cache',
					'filename'	=> 'application_helper.php',
					'filepath'	=> 'helpers',
					'params'	=> array('cache_key' => $cache_key, 'cache_time'=>$cache_time)
			);
		}
	
		// 执行请求的方法.
		call_user_func_array(array(&$this, $method), $method_param);
	}
	
	//---------------------------------------------------------------------------
	
	/**
	 * 生成当前请求的KEY
	 *
	 * 可以通过重写的这个方法定义你的KEY
	 *
	 * @param string $method	当前请求的方法
	 * @param array $config		缓存配置文件
	 *
	 * @return string 缓存KEY
	 */
	protected function _request_cache_key($method, $config)
	{
		// 默认KEY
		$cache_key = "cache_request_{$this->SLD}_{$this->router->class}_{$method}";
	
		// 获取自定义缓存KEY
		if ($this->request_cache_keys !== NULL AND isset($this->request_cache_keys[$method]))
		{
			return $this->page_cache_keys[$method];
		}
		
		if (!isset($config['cache_key']))
		{
			return $cache_key;
		}
	
		if (is_string($config['cache_key']))
		{
			return $config['cache_key'];
		}
	
		if (is_array($config['cache_key']))
		{
			$additional = ''; // 额外的缓存KEY标识
	
			// 加上用户ID作为标识
			if (isset($config['cache_key']['user_id']))
			{
				$additional .= AuthUser::is_logged_in() ? '_'.AuthUser::id() : '';
			}
	
			// 查看是否缓存KEY是否需要额外条件(uri的segment)
			if (isset($config['cache_key']['segments']))
			{
				// 为了减少一些不必要的兼容判断,要求segments必须是数组
				foreach ($config['cache_key']['segments'] as $segment_num)
				{
					$segment = $this->uri->rsegment($segment_num);
					$additional .= $segment ? '_'.$segment : '';
				}
			}
			// 缓存KEY
			return $cache_key.$additional;
		}
	
		return $cache_key;
	}
}

/**
 * App使用的控制器
 * @author 杜嘉杰
 *
 */
class ZHS_App_Controller extends CI_Controller {
	/**
	 * 用户id
	 * @var int
	 */
	protected $uid;

	/**
	 * 用户名
	 * @var string
	 */
	protected $uname;

	/**
	 * 状态
	 * @var int
	 */
	protected $status;

	/**
	 * 用户信息
	 * @var array
	 */
	protected $user;

	/**
	 * @var android客户端类型
	 */
	const CLIENT_TYPE_ANDROID = 3;
	/**
	 * @var ios客户端类型
	 */
	const CLIENT_TYPE_IOS = 4;

	/**
	 * @var 客户端平台：1.pc版，2触屏版，3-ANDROID，4-IOS
	 */
	protected $client_type;

	/**
	 * @var 客户端的版本号
	 */
	protected $client_version;

	function __construct() {
		parent::__construct ();
		header("Content-Type:text/json;charset=utf-8");
	}

	/**
	 * 检测用户信息
	 *
	 * @param int $uid:用户id
	 * @param int $sign:请求的签名
	 * @param string $use_cache:true读取缓存信息，false读取数据库,默认读取缓存
	 * @param bool $check_status:检查用户状态，true检测，false不检测，默认为检查状态
	 * @return array
	 * @author 杜嘉杰
	 * @version 2014-6-19
	 */
	protected function check_user($uid = NULL, $sign = NULL, $use_cache = TRUE, $check_status = TRUE){
		// 获取uid
		if($uid == NULL){
			$uid = $this->get_uint ( 'uid');
		}

		if ($sign == NULL) {
			$sign = $this->get_string('sign');
		}

		$this->load->helper('get_user');
		// 获取缓存信息
		$user = NULL;
		if($use_cache){
			$user = get_user($uid);
			$user OR $this->failure('2', '用户不存在');
				
			// 如果当前用户状态异常，重新查数据库
			if(!$user['status']){
				$user = get_user($uid, FALSE);
			}
		}else{
			$user = get_user($uid, FALSE);
		}

		$this->user = $user;
		$this->uid = $user['id'];
		$this->uname = $user['name'];
		$this->status = $user['status'];

		if ($check_status && !$this->user['status']) {
			$this->user['is_activated'] OR $this->failure('3', '账号尚未激活');
			// 0（正常）、1（被锁定封号）、2(未激活) 、4(被屏蔽) -- 用户中心
			$this->user['is_locked']==1 and $this->failure('4', '账号已被封号');
			$this->user['is_locked']==2 and $this->failure('5', '账号尚未激活');
		}

		// 判断签名
		if($sign != $this->user_sign($uid)){
			if($sign != $this->user_sign($uid, FALSE))
			{
				$this->failure('6', '哎呀呀~账号不对劲了！需要重新登录噢~');
			}
		}

		return $user;
	}

	/**
	 * 获取用户login_sign,(前提是用户必须存在)
	 *
	 * @param int $uid 用户uid
	 * @param bool $is_cache 是否使用缓存
	 * @return login_sign字符串
	 *
	 * @author 杜嘉杰
	 * @version 2015年7月9日 上午10:15:52
	 */
	protected function user_sign($uid, $is_cache=TRUE)
	{
		$cache_key = 'app_user_sign';
		$login_sign = NULL;

		if($is_cache)
		{
			$login_sign = cache($cache_key);
		}

		if( ! $login_sign)
		{
			$this->load->model('zhs_user_model');
			$user = $this->zhs_user_model->select('login_sign')->find($uid);
			$login_sign = $user['login_sign'];
			cache($cache_key,$login_sign);
		}
		return $login_sign;
	}
	/**
	 * 改进的Input类get_post方法
	 *
	 * @param string $key:索引键值
	 * @param boolean $xss_clean:XSS清除（默认FALSE）
	 * @return string
	 */
	protected function get_post($key, $xss_clean = FALSE) {
		$val = $this->input->post ( $key, $xss_clean );
		if (FALSE === $val) {
			$this->failure(1, '请求缺少参数:'.$key);
		}
		return $val;
	}

	/**
	 * 获取请求参数为string的值
	 * @param string $key:索引键值
	 * @param boolean $xss_clean:XSS清除（默认FALSE）
	 * @return string
	 * @author 杜嘉杰
	 * @version 2014-9-13
	 */
	protected function get_string($key, $xss_clean = FALSE){
		$val = $this->get_post($key, $xss_clean = FALSE);
		$val = trim($val);
		return $val;
	}

	/**
	 * 获取请求参数为int的值
	 * @param string $key:索引键值
	 * @param boolean $xss_clean:XSS清除（默认FALSE）
	 * @return int
	 * @author 杜嘉杰
	 * @version 2014-9-13
	 */
	protected function get_int($key, $xss_clean = FALSE){
		$val = $this->get_post($key, $xss_clean = FALSE);
		if($val === FALSE || !is_numeric($val)){
			if(empty($val)){
				$this->failure(1, '参数值不能为空:'.$key);
			}
			if (is_string($val)) {
				$this->failure(1, '参数传入了字符串:'.$key.'='.$val);
			}
			$this->failure(1, '参数未传值:'.$key);
		}

		$val = intval($val);
		return $val;
	}

	/**
	 * 获取请求参数为uint的值
	 * @param string $key:索引键值
	 * @param boolean $xss_clean:XSS清除（默认FALSE）
	 * @return uint
	 * @author 杜嘉杰
	 * @version 2014-9-13
	 */
	protected function get_uint($key, $xss_clean = FALSE){
		$val = $this->get_int($key, $xss_clean = FALSE);
		if ($val<0) {
			$this->failure(1, '参数不允许为负数:'.$key);
		}
		return $val;
	}

	/**
	 *  获取请求参数为有符号浮点数的值
	 * @param string $key:索引键值
	 * @param boolean $xss_clean:XSS清除（默认FALSE）
	 * @return float
	 * @author 杜嘉杰
	 * @version 2014-9-15
	 */
	protected function get_floatvalt($key, $xss_clean = FALSE){
		$val = $this->get_post($key, $xss_clean = FALSE);
		if($val === FALSE || !is_numeric($val)){
			if(empty($val)){
				$this->failure(1, '参数值不能为空:'.$key);
			}
			if (is_string($val)) {
				$this->failure(1, '参数传入了字符串:'.$key.'='.$val);
			}
			$this->failure(1, '参数未传值:'.$key);
		}

		$val = floatval($val);
		return $val;
	}

	/**
	 * 获取请求参数为无符号浮点数的值
	 * @param string $key:索引键值
	 * @param boolean $xss_clean:XSS清除（默认FALSE）
	 * @return float
	 * @author 杜嘉杰
	 * @version 2014-9-15
	 */
	protected function get_ufloat($key, $xss_clean = FALSE){
		$val = $this->get_floatvalt($key, $xss_clean);
		if($val < 0){
			$this->failure(1, '参数不允许为负数:'.$key);
		}
		return $val;
	}

	/**
	 * 获取可选参数的值
	 * @param string $key:索引键值
	 * @param boolean $xss_clean:XSS清除（默认FALSE）
	 * @return Ambigous <string, multitype:Ambigous <string, boolean, unknown> , boolean, unknown>
	 *
	 * @author 杜嘉杰
	 * @version 2015年7月14日 下午5:27:27
	 */
	protected function get_optional($key, $xss_clean = FALSE){
		$val = $this->input->post ( $key, $xss_clean );
		return $val;
	}

	/**
	 * 处理成功返回json数据
	 * @param int $code:错误编码
	 * @param string $msg:描述
	 * @param string $data:数据
	 * @author 杜嘉杰
	 * @version 2014-6-19
	 */
	protected function success($code , $msg = '', $data = NULL, $output = TRUE) {
		$code = intval($code);
		$ret = array (
			'code' => 200+ $code,
			'msg' => $msg
		);
		$data !== NULL && ($ret ['data'] = $data);
		$json_str = json_encode ( $ret);

		if ($output) {
			// 输出后停止程序
			die ( $json_str );
		}else{
			// 返回json字符串
			return $json_str;
		}
	}

	/**
	 * 处理失败返回json数据
	 * @param int $code:错误编码
	 * @param string $msg:描述
	 * @param string $data:数据
	 * @author 杜嘉杰
	 * @version 2014-6-19
	 */
	protected function failure($code , $msg = '', $data = NULL, $output = TRUE) {
		$code = intval($code);
		$ret = array (
			'code' => 300 + $code,
			'msg' => $msg
		);
		$data !== NULL && ($ret ['data'] = $data);
		$json_str = json_encode ( $ret );

		if ($output) {
			// 输出后停止程序
			die ( $json_str );
		}else{
			// 返回json字符串
			return $json_str;
		}
	}

	/**
	 * 检测版请求版本号
	 * @author 杜嘉杰
	 * @version 2014-6-28
	 */
	protected function check_version(){
		// 获取客户端类型
		$client_type = $this->get_uint('client_type');
		
		if( ! in_array($client_type, array(self::CLIENT_TYPE_ANDROID,self::CLIENT_TYPE_IOS))){
			$this->failure ( '1', '不支持客户端类型：'. $client_type);
		}
		
		// 获取版本号
		$version = $this->get_uint('version');
		
		$this->client_type = $client_type;
		$this->client_version = $version;
		
		$this->load->model('Zhs_sys_config_model');
		$app_version = $this->Zhs_sys_config_model->get_app_min_version($client_type);
		if( $app_version['new_version'] > $version){
			$this->check_version_msg($app_version);
		}
	}
	
	/**
	 * 版本升级的提示语
	 * @param array $app_version 版本信息
	 *
	 * @author 杜嘉杰
	 * @version 2015年9月29日  上午10:15:58
	 *
	 */
	private function check_version_msg($app_version)
	{
		switch($this->client_type)
		{
			case $this::CLIENT_TYPE_ANDROID : 
				$msg = '客户端版本号过低';
				if($this->client_version < 2005101)
				{
					// 解决旧版客户端强制更新代码有误,只能在抢购的时候判断
					return ;
				}
				else
				{
					// 正常情况返回的数据
					$this->failure('49', $msg, array('new_version'=>$app_version['new_version'],'new_version_url'=>$app_version['new_version_url']));
				}
				exit;
			case $this::CLIENT_TYPE_IOS:
				$msg = '客户端版本过低,请去AppStore更新';
				$this->failure('49', $msg, array('new_version'=>$app_version['new_version'],'new_version_url'=>$app_version['new_version_url']));
				exit;
			default:
				$this->failure('49', $msg, array('new_version'=>$app_version['new_version'],'new_version_url'=>$app_version['new_version_url']));
				exit;
		}
	}
}