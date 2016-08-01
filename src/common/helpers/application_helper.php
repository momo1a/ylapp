<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 自动装载类库
 * 
 * 目前只实现model的自动加载
 * 
 * @param $class_name 类名
 * 
 * @return void
 */
function __autoload($class_name)
{
	// 转换为小写
	$class_name = strtolower($class_name);
	
	// model的加载方式
	if (substr($class_name, -5) == 'model')
	{
		if ( ! class_exists('CI_Model'))
		{
			load_class('Model', 'core');
		}
		
		$model_paths = array(
				COMPATH . 'models/',
				APPPATH . 'models/'
		);
		// $CI =& get_instance();
		// $model_paths = $CI->load->get_package_paths();
		
		// 历遍包,直到找到为止
		foreach ($model_paths as $mod_path)
		{
			if(file_exists($mod_path.$class_name.'.php'))
			{
				require($mod_path.$class_name.'.php');
				
				// 不进行初始化
				return;
			}
		}
	}
	// TODO 其它需要自动加载的请在后面追加
}

// --------------------------------------------------------------------

if ( ! function_exists('avatar'))
{
	/**
	 * 获取用户头像
	 * 
	 * @param int $uid			用户uid
	 * @param string $size		头像规格： 'big', 'middle', 'small'，默认middle
	 * @param string $show_type 强制显示方式，可选php|img，默认空 ,强制方式未定义,则以头像配置为准
	 *
	 * @return string 头像地址,如:http://uc.shikee.com/avatar.php?uid=2090063866&size=middle
	 */
	function avatar($uid, $size = 'middle', $show_type = '')
	{
		$avatar_server = '';
		$avatar_setting = config_item('avatar');
		
		$size = in_array($size, array('big', 'middle', 'small')) ? $size : 'middle';
		
		if( empty($show_type) || !isset($avatar_setting['server'][$show_type]))
		{
			$show_type = $avatar_setting['type'];
		}
		
		$avatar_server = $avatar_setting['server'][$show_type];
		
		if($show_type == 'img')
		{
			$uid = abs(intval($uid));
			$_uid = sprintf("%011d", $uid);
			$dir1 = substr($_uid, 0, 3);
			$dir2 = substr($_uid, 3, 3);
			$dir3 = substr($_uid, 6, 3);
			
			return $avatar_server . 'data/avatar/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).'_avatar_'.$size.'.jpg';
		}
		elseif ($show_type == 'php')
		{
			return $avatar_server. 'avatar.php?uid='.$uid.'&size='.$size;
		}
	}
}

// --------------------------------------------------------------------

/**
 * 生成商品详细页的链接地址
 *
 * @param $goods_id 商品ID
 *
 * @return string 商品详细页的链接地址
 */
function goods_detail_url($goods_id)
{
	return config_item('domain_detail') . $goods_id . '.html';
}

// --------------------------------------------------------------------

/**
 * 生成登录地址
 * 
 * @param string $to_url 登录成功后返回的地址
 * @return string
 */
function login_url($to_url = '')
{
	if (!$to_url)
	{
		$to_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];
	}
	
	return config_item('url_login').'?to='.urlencode($to_url);
}

// --------------------------------------------------------------------

if ( ! function_exists('save_cache'))
{
	/**
	 * 将输出的结果进行缓存
	 *
	 * @return void
	 */
	function save_cache($config)
	{
		$CI = &get_instance();
		$CI->cache->save($config['cache_key'], $CI->output->get_output(), $config['cache_time']);
	}
}

// --------------------------------------------------------------------

/**
 * 显示一个AJAX请求的结果
 * 
 * @param string $message	结果提示语
 * @param string $data		返回的数据,默认NULL
 * @param boolean $success	请求的结果是否正确,默认TRUE
 * @param string $callback	回调函数,默认callback
 */
function show_message_ajax($message = '', $success = TRUE, $data = NULL, $callback = 'callback')
{
	$CI = &get_instance();
	$ret = array('success'=>$success, 'message'=>$message);
	
	if (!is_null($data))
	{
		$ret['data'] = $data;
	}	
	$json = json_encode($ret); // JSON
	// 回调函数
	if(isset($_GET[$callback]))
	{
		$json = $CI->input->get($callback, TRUE).'('.$json.');';
	}
	
	// 告诉CodeIgniter方法执行完毕后，将$json做为输出
	$CI->output->set_content_type('application/json')->set_output($json);
}

// -------------------------------------------------------------------

/**
 * 显示提示信息
 * @param string|array $message 提示信息
 * @param array $actionurl 可操作项 ：$actionurl[] = array('name'=>'返回上一页', 'url'=>site_aurl('singleanswer/index/'.$course));
 * @param string $extraparam 其他参数:
 * $extraparam=array(
 * 		'redirect'=>是否自定重定向(默认不重定向),
 * 		'locationtime'=>延迟重定向倒计时间(默认3秒)
 * );
 *
 * @example
 * <p>$actionurl[] = array('name'=>'返回上一页', 'url'=>'index');</p>
 * <p>$extraparam = array('redirect'=>true);</p>
 * <p>show_message('dfsadfsda', $actionurl, $extraparam);</p>
 */
function show_message($message, $actionurl=array(), $extraparam=array())
{
	$CI = &get_instance();
	$inajax = $CI->input->get_post('inajax', TRUE);//$inajax=1 ajax方式
	$newwin = $CI->input->get_post('nw', TRUE);//是否是新开页面
	if( ! $inajax){
		$inajax = isset($extraparam['inajax'])?$extraparam['inajax']:FALSE;
		if( ! $inajax){
			$extraparam['backurl'] =isset($extraparam['backurl']) ? trim($extraparam['backurl']) : '';
		}
	}

	$data['inajax'] = $inajax;
	$data['newwin'] = $newwin;
	$data['title'] = '系统提示-众划算';
	$data['message'] = $message;
	$data['actionurl'] = $actionurl;
	$data['extraparam'] = $extraparam;
	$data['domain_static'] = $CI->config->item('domain_static');
	$buffer = $CI->load->view('back/message', $data, true);
	exit($buffer);
}

// ------------------------------------------------------------------

if ( ! function_exists('image_url'))
{
	/**
	 * 获取上传图片链接
	 * 需要进行配置image_domains，测试配置如下：
	 * $config['image_servers'] = array('http://192.168.1.47:8001/', 'http://192.168.1.47:8002/', 'http://192.168.1.47:8003/', 'http://192.168.1.47:8004/');
	 *
	 * @param id    图片id
	 * @param path  图片相对路径
	 * @param size  要显示的图片大小（字符串，例如'350x350'），默认为空表示原图
	 *
	 * @return string
	 *
	 * @author 温守力
	 * @version 130622
	 */
	function image_url($id, $path, $size = '')
	{
		$servers = config_item('image_servers') or show_error('请先配置图片服务器！');
		$pos = $id % count($servers);
		return $servers[$pos] . $path . ($size ? ('_' . $size . '.jpg') : '');
	}
}
/**
 * 商家logo
 * @param int $uid 商家UID
 * @return string 商家logo图片地址
 */
function buyer_logo($uid)
{
	$uid		= intval($uid);
	$cache_key	= 'seller_logo_'.$uid;
	$ci			= &get_instance();
	
	$url = isset($_GET['fc']) ? '' : $ci->cache->get($cache_key);
	if ($url)
	{
		return $url;
	}
	$ci->load->model('YL_user_seller_model');
	$logo = $ci->YL_user_seller_model->get_field($uid, 'logo');
	if ($logo)
	{
		$logo = image_url($uid, $logo);
	}
	else
	{
		$logo = $ci->config->item('domain_static').'images/user/seller_logo.jpg';
	}
	$ci->cache->save($cache_key, $logo, 3600);
	return $logo;
}

/**
 * 来源地址
 *
 * @return string 来源地址
 */
function from_url()
{
	if (isset($_GET['to']))
	{
		return $_GET['to'];
	}

	$url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : config_item('domain_www');
	return urlencode($url);
}


if ( ! function_exists('send_email_code')) {
	
	/**
	 * 发送邮箱验证码
	 * @param string $email 要发送的邮件
	 * @param string $cache_prefix 缓存KEY前缀
	 * @return boolean 发送结果
	 */
	function send_email_code($email, $cache_prefix = '') 
	{
		$email = strtolower(trim($email));
		$CI =& get_instance();
		// 加载驱动
		$CI->load->driver('captcha', array(
				'store_adapter' => 'cache',
				'store_expiration' => 1200, // 过期时间
				'store_key' => $cache_prefix.md5($email)
		));
		
		return $CI->captcha->send_email_captcha($email);
	}
}

if ( ! function_exists('cleanup_email_code'))
{
	/**
	 * 清除邮箱验证码
	 * @param string $email 要验证的邮箱
 	 * @param string $cache_prefix 缓存KEY前缀
	 */
	function cleanup_email_code($email, $cache_prefix = '')
	{
		$email = strtolower(trim($email));
		$CI =& get_instance();
		// 加载驱动
		$CI->load->driver('captcha', array(
				'store_adapter' => 'cache',
				'store_expiration' => 1200, // 过期时间
				'store_key' => $cache_prefix.md5($email)
		));
		$CI->captcha->cleanup();
	}
	
}

if ( ! function_exists('check_email_code'))
{
	/**
	 * 验证邮箱验证码
	 * @param string $email 要验证的邮箱
	 * @param string $code 要对比的验证码
	 * @param string $cache_prefix 缓存KEY前缀
	 */
	function check_email_code($email, $code, $cache_prefix = '')
	{
		$email = strtolower(trim($email));
		$CI =& get_instance();
		// 加载驱动
		$CI->load->driver('captcha', array(
				'store_adapter' => 'cache',
				'store_expiration' => 1200, // 过期时间
				'store_key' => $cache_prefix.md5($email)
		));
		
		return $CI->captcha->check($code);
	}
}

if ( ! function_exists('check_strong_passwd'))
{
	/**
	 * 检测帐号的弱密码
	 *
	 * @param string $username
	 * @param string $password
	 * @return boolean FALSE是弱密码,TRUE不是弱密码
	 */
	function check_strong_passwd($username,$password)
	{
		//密码包含账号名
		if( strpos($password , $username ) !== FALSE )
		{
			return FALSE;
		}
		
		$CI			= &get_instance();
		$CI->load->library('Cache_memcached', NULL, 'cache');
		$key = 'yz_password_rules';
		$password_rules = $CI->cache->get($key);
		if ( $password_rules === FALSE ) 
		{
			$CI->load->model('YL_uc_password_rules');
			$password_rules = $CI->YL_uc_password_rules->find_password_rules();
			$CI->cache->save($key, $password_rules , 600);
		}
		
		if( $password_rules==NULL || !is_array($password_rules) )
		{
			return TRUE;
		}
	
		//遍历所有设置有效的正则
		foreach ($password_rules as $key=>$rule)
		{
			if( preg_match ( $rule['rule'], $password ) )
			{
				return FALSE;
			}
		}
	
		return TRUE;
	}
}

/**
 * 通过分类ID生成LIST站点的URL地址
 * 
 * @param number $category_pid	一级分类(必填)
 * @param number $category_id	二级分类
 * 
 * @return string url 完整的URL地址
 */
function list_site_cate_url($category_pid, $category_id = 0)
{
	$url = '?cat='.$category_pid;
	if ($category_id > 0) {
		$url .= ','.$category_id;
	}
	
	return config_item('domain_list').$url;
}

if ( ! function_exists('batch_goods_remind'))
{
	/**
	 * java服务发送活动上线、追加提醒
	 *
	 * @param int $gid:活动id
	 * @param int $content:消息推送内容
	 * @param int $remind_time:推送的时间戳
	 * @return boolean
	 *
	 * @author 杜嘉杰
	 * @version 2015年4月24日 下午3:43:08
	 */
	function batch_goods_remind($gid, $content, $remind_time){
		$_STOMP			= NULL;
		$_queue_theme	= '';
		$CI				= &get_instance();

		// 插入或修改提醒队列数据
		$CI->load->database();
		$remind = $CI->db->select('id')->from('goods_remind')->where(array('gid'=>$gid, 'uid'=>0))->get()->row_array();
		if($remind)
		{
			$CI->db->where('id',$remind['id'])->update('goods_remind', array('remind_time'=>$remind_time));
		}else
		{
			$data = array('gid'=>$gid, 'uid'=>0, 'app_push'=>2, 'app_title'=>'众划算' ,
				'app_content'=>$content, 'app_push_state'=>1, 'state'=>1,
				'remind_time'=>$remind_time, 'dateline'=>TIMESTAMP
			);
			$CI->db->insert('goods_remind', $data);
		}

		// 通知java服务监控新的队列
		if ( ! extension_loaded('Stomp')) {
			log_message('error', 'Stomp扩展没有安装.');
			return FALSE;
		}

		if ($CI->config->load('stomp', TRUE, TRUE)) {
			$config = $CI->config->item('stomp');
			// 队列主题
			if (isset($config['batch_goods_remind'])) {
				$_queue_theme = $config['batch_goods_remind'];
			}else
			{
				log_message('error', 'stomp缺少配置:batch_goods_remind');
				return FALSE;
			}

			try {
				$_STOMP = new Stomp($config['broker'], $config['name'], $config['password']);
			} catch(StompException $e) {
				log_message('error', 'Stomp开启失败:'.$e->getMessage());
				return FALSE;
			}
		}else {
			log_message('error', 'Stomp配置文件没有找到.');
			return FALSE;
		}

		/* java接口参数
		 gid	活动id
		 remind_time	提醒时间
		 title	标题
		 content	内容
		 */
		$arr_message = array(
			'gid' 			=> $gid,
			'remind_time' => $remind_time,
			'title'			=> '众划算',
			'content'	=> $content,
		);

		$message = json_encode($arr_message);

		if ( ! $_STOMP->send($_queue_theme, $message)) {
			log_message('error', "调用抢购提醒{$gid}失败,错误原因:".$_STOMP->error());
			return FALSE;
		}
		return TRUE;
	}
}

if( !function_exists('cache') )
{
	/**
	 * 全局缓存方法 - 默认为memcache缓存，仅限于常见的小数据缓存
	 *
	 * @param $key 缓存key值
	 * @param $data 默认为NULL，表示读取；若为FALSE，表示删除；其它表示设置缓存
	 * @param $expire 缓存时间。单位：秒，默认1800秒。
	 */
	function cache($key, $data = NULL, $expire = 1800) {
		static $_cache = NULL;
		if ($_cache === NULL) {
			$ci = &get_instance ();
			$ci->load->library ( 'cache_memcached', NULL, 'cache' );
			$_cache = $ci->cache;
		}
	
		if ($data === NULL) {
			return $_cache->get ( $key );
		}
	
		if ($data === FALSE) {
			return $_cache->delete ( $key );
		}
	
		return $_cache->save ( $key, $data, $expire );
	}
}

if ( ! function_exists('ip'))
{
	/**
	 * 获取IP地址
	 *
	 * @param $format 返回IP格式
	 *        	string（默认）表示传统的127.0.0.1，int或其它表示转化为整型，便于存放到数据库字段
	 * @param $side IP来源
	 *        	client（默认）表示客户端，server或其它表示服务端
	 * @return string or int
	 */
	function ip($format = 'string', $side = 'client') {
		if ($side === 'client') {
			static $_client_ip = NULL;
			if ($_client_ip === NULL) {
				// 获取客户端IP地址
				$ci = &get_instance ();
				$_client_ip = $ci->input->ip_address ();
			}
			$ip = $_client_ip;
		} else {
			static $_server_ip = NULL;
			if ($_server_ip === NULL) {
				// 获取服务器IP地址
				if (isset ( $_SERVER )) {
					if ($_SERVER ['SERVER_ADDR']) {
						$_server_ip = $_SERVER ['SERVER_ADDR'];
					} else {
						$_server_ip = $_SERVER ['LOCAL_ADDR'];
					}
				} else {
					$_server_ip = getenv ( 'SERVER_ADDR' );
				}
			}
			$ip = $_server_ip;
		}
	
		return $format === 'string' ? $ip : bindec ( decbin ( ip2long ( $ip ) ) );
	}
}

if ( ! function_exists('ajax_success'))
{
	/**
	 * 处理成功返回json数据
	 *
	 * @param mixed $data 返回的数据。默认：NULL
	 * @param string $callback 跨域时输出的方法名
	 */
	function ajax_success($data = NULL, $callback = 'callback') {
		$ret = array('success' => TRUE);
		$data!==NULL && ($ret['data'] = $data);
		$json_str = json_encode($ret);
		if(isset($_GET[$callback])){
			$json_str = $_GET[$callback].'('.$json_str.');';
		}
		die($json_str);
	}
}

if ( ! function_exists('ajax_error'))
{
	/**
	 * 处理失败返回json数据
	 *
	 * @param mixed $data 返回的数据。默认：NULL
	 * @param string $callback  跨域时输出的方法名
	 */
	function ajax_error($data = NULL, $callback = 'callback') {
		$ret = array('success' => FALSE);
		$data!==NULL && ($ret['data'] = $data);
		$json_str = json_encode($ret);
		if(isset($_GET[$callback])){
			$json_str = $_GET[$callback].'('.$json_str.');';
		}
		die($json_str);
	}
}

/**
 * 创建商品模糊连接
 * @param int  $gid 商品id
 * @param int  $state 商品状态
 * @param int  $seed 加密用的种子
 * @return string 如果是未达到上线要求或者已结算的活动,返回一个模糊化连接,否则返回正常的商品连接
 * @author 莫嘉伟
 */
function create_fuzz_link($gid,$state,$seed) {
	if($state<=13 && $state!=5)
	{
		return  config_item('domain_detail') . $gid . '-'.fuzz_str($gid,$seed).'.html';
	}
	if ($state==32) {
		return  config_item('domain_detail') . $gid . '-'.fuzz_str($gid,$seed,TRUE).'.html';
	}
	return  config_item('domain_detail') . $gid . '.html';
}

/**
 * 按格式生成一串模糊化的字符串
 * @param int $gid
 * @param string $seed
 * @param bool $need_secret_key
 * @return string
 * @author 莫嘉伟
 */
function fuzz_str($gid,$seed,$need_secret_key=FALSE) {
	if (!$need_secret_key) {
		return mb_substr(md5($gid.$seed),0, 6, 'utf-8') ;
	}
	$secret_key=  config_item('have_pay_key');//中间密钥
	return mb_substr(md5($gid.$secret_key.$seed),0, 6, 'utf-8') ;
}

if ( ! function_exists('YL_config_item'))
{
	/**
	 * 对CI的config_item改装，配置未设置时，返回默认值
	 * @param string $key 配置键
	 * @param string $default_val 未配置时的默认值
	 * @return mixd
	 * @author 宁天友
	 */
	function YL_config_item($key, $default_val = NULL) {
		# 不存在$key的配置时，congif_item返回FALSE
		$_config = config_item($key);
		return $_config === FALSE ? $default_val : $_config;
	}
}

if ( ! function_exists('content_replace_url'))
{
	/**
	 * 替换内容中的url标示符，如{#domain_detail#} 替换为 http://detail.zhonghuasuan.com/
	 * @param string $content 内容
	 * @return string 替换后的content
	 */
	function content_replace_url($content){
		$replace_item = array(
			'domain_www','domain_list','domain_detail','domain_buyer','domain_seller',
			'domain_mobile',
		);
		foreach ($replace_item as $item) {
			$r_palce = config_item($item);
			if ($r_palce != '' && preg_match('/\{#'.$item.'#\}/', $content)) {
				$content = preg_replace('/\{#'.$item.'#\}/', $r_palce, $content);
			};
		}
		return $content;
	}
}

if ( ! function_exists('msubstr'))
{
	/**
	 * 字符串截取，支持中文和其他编码
	 *
	 * @param string $str 需要转换的字符串
	 * @param string $start 开始位置
	 * @param string $length 截取长度
	 * @param string $dot 省略部分表示的符号,默认'...'
	 * @param string $suffix 是否显示省略部分表示符号
	 * @param string $charset 编码格式
	 * @return string
	 * @example echo msubstr('abcdefg', 0, 4);//输出：abcd...
	 */
	function msubstr($str, $start=0, $length, $dot='...', $suffix=TRUE, $charset='utf-8' )
	{
		if(function_exists("mb_substr"))
			$slice = mb_substr($str, $start, $length, $charset);
		elseif(function_exists('iconv_substr')) {
			$slice = iconv_substr($str,$start,$length,$charset);
		}else{
			$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
			$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
			$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
			$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
			preg_match_all($re[$charset], $str, $match);
			$slice = join("",array_slice($match[0], $start, $length));
		}
		return $suffix ? $slice.$dot : $slice;
	}
}

if ( ! function_exists('get_hlpay_money'))
{
	/**
	 * 获取用户互联支付可用余额
	 *
	 * @param int $uid 用户uid
	 * @return decimal 
	 */
	function get_hlpay_money($uid)
	{
		$user_money = '0.00';
		if( $uid > 0 ){
			$CI = &get_instance();
			$CI->load->library('hlpay');
			$user_money = $CI->hlpay->get_user_money($uid);
			if($user_money == FALSE){
				$user_money = '0.00';
			}
		}
		return $user_money;
	}
}

if( ! function_exists('cache'))
{
	/**
	 * 全局缓存方法 - 默认为memcache缓存，仅限于常见的小数据缓存
	 *
	 * @param $key 缓存key值
	 * @param $data 默认为NULL，表示读取；若为FALSE，表示删除；其它表示设置缓存
	 * @param $expire 缓存时间。单位：秒，默认1800秒。
	 */
	function cache($key, $data = NULL, $expire = 1800) {
		static $_cache = NULL;
		if ($_cache === NULL) {
			$ci = &get_instance ();
			$ci->load->library ( 'cache_memcached', NULL, 'cache' );
			$_cache = $ci->cache;
		}
	
		if ($data === NULL) {
			return $_cache->get ( $key );
		}
	
		if ($data === FALSE) {
			return $_cache->delete ( $key );
		}
	
		return $_cache->save ( $key, $data, $expire );
	}
}

if ( ! function_exists('mobile_auth'))
{
	/**
	 * 判断登录用户是否已经手机认证
	 * @return boolean
	 */
	function mobile_auth()
	{
		$CI	= &get_instance();
		$CI->load->library('authUser');
		$uid = AuthUser::id();
		if( !$uid ) return FALSE;

		$CI->load->library('Cache_memcached', NULL, 'cache');
		$key = 'user_mobile_valid_'.$uid;
		$return = $CI->cache->get($key);
		if( !$return )
		{
			$CI->load->model('YL_user_model');
			if( $CI->YL_user_model->mobile_valid( $uid ) )
			{
				$return = TRUE;
			}
			$CI->cache->save($key, $return , 5);
		}
		return $return;
	}
}

if (!function_exists('change_to_minify')) {
	/**
	 * 把静态的css文件和js文件转换为minify显示的的方式
	 * @param $asset_css 字符串和数组，注意：传参文件中的文件后缀名要统一
	 */
	function change_to_minify($asset_file, $to_minify = true, $print = true)
	{
		if ($to_minify) {
		if (!is_array($asset_file)) {
			$minify_url = config_item('domain_static') . 'min/?f=' . $asset_file . '&v=' . SYS_VERSION . SYS_BUILD;
		} else {
			$minify_url = config_item('domain_static') . 'min/?f=' . implode(",", $asset_file) . '&v=' . SYS_VERSION . SYS_BUILD;
		}
		$string = strstr($minify_url, '.css&') ? '<link href="' . $minify_url . '" rel="stylesheet" />' . PHP_EOL : '<script src="' . $minify_url . '" type="text/javascript"></script>' . PHP_EOL;
		} else {
			$minify_url = config_item('domain_static') .$asset_file;
			$string = strstr($minify_url, '.css') ? '<link href="' . $minify_url . '" rel="stylesheet" />' . PHP_EOL : '<script src="' . $minify_url . '" type="text/javascript"></script>' . PHP_EOL;
		}
		if ($print) {
			echo $string;
		} else {
			return $string;
		}
	}
}

/**
 * 获取IP地址
 *
 * @param $format 返回IP格式
 *        	string（默认）表示传统的127.0.0.1，int或其它表示转化为整型，便于存放到数据库字段
 * @param $side IP来源
 *        	client（默认）表示客户端，server或其它表示服务端
 * @return string or int
 */
function ip($format = 'string', $side = 'client') {
	if ($side === 'client') {
		static $_client_ip = NULL;
		if ($_client_ip === NULL) {
			// 获取客户端IP地址
			$ci = &get_instance ();
			$_client_ip = $ci->input->ip_address ();
		}
		$ip = $_client_ip;
	} else {
		static $_server_ip = NULL;
		if ($_server_ip === NULL) {
			// 获取服务器IP地址
			if (isset ( $_SERVER )) {
				if ($_SERVER ['SERVER_ADDR']) {
					$_server_ip = $_SERVER ['SERVER_ADDR'];
				} else {
					$_server_ip = $_SERVER ['LOCAL_ADDR'];
				}
			} else {
				$_server_ip = getenv ( 'SERVER_ADDR' );
			}
		}
		$ip = $_server_ip;
	}

	return $format === 'string' ? $ip : bindec ( decbin ( ip2long ( $ip ) ) );
}

if( ! function_exists('send_mobile_msg_captcha'))
	{
	/**
	 * 发送短信验证码
	 * 
	 * @param string $mobile 手机号
	 * @param string $cache_prefix 缓存KEY前缀
	 * 
	 * @author 杜嘉杰
	 * @version 2015年8月21日 下午3:35:53
	 */
	function send_mobile_msg_captcha($mobile, $cache_prefix = '')
	{
		$CI =& get_instance();
		// 加载驱动
		$CI->load->driver('captcha', array(
			'store_adapter' => 'cache',
			'store_expiration' => 1200, // 过期时间
			'store_key' => $cache_prefix.md5($mobile)
		));
		return $CI->captcha->send_mobile_msg_captcha($mobile);
	}
}

if( ! function_exists('check_mobile_msg_captcha'))
{
	/**
	 * 检测短信验证码是否正确
	 * 
	 * @param string $mobile 手机号
	 * @param string $code 验证码
	 * @param string $cache_prefix 缓存KEY前缀
	 * 
	 * @author 杜嘉杰
	 * @version 2015年8月21日 下午3:39:22
	 */
	function check_mobile_msg_captcha($mobile, $code, $cache_prefix = '')
	{
		$CI =& get_instance();
		// 加载驱动
		$CI->load->driver('captcha', array(
			'store_adapter' => 'cache',
			'store_expiration' => 1200, // 过期时间
			'store_key' => $cache_prefix.md5($mobile)
		));
		return $CI->captcha->check($code);
	}
}

if( ! function_exists('cleanup_mobile_msg_captcha'))
{
	/**
	 * 清除手机短信验证码
	 * 
	 * @param string $mobile 手机号
	 * @param string $cache_prefix 缓存KEY前缀
	 * 
	 * @author 杜嘉杰
	 * @version 2015年8月21日 下午3:40:53
	 */
	function cleanup_mobile_msg_captcha($mobile, $cache_prefix = '')
	{
		$CI =& get_instance();
		// 加载驱动
		$CI->load->driver('captcha', array(
			'store_adapter' => 'cache',
			'store_expiration' => 1200, // 过期时间
			'store_key' => $cache_prefix.md5($mobile)
		));
		return $CI->captcha->cleanup();
	}
}

if( ! function_exists('goods_source_name'))
{
	/**
	 * 获取商品来源显示用的文字
	 * @param int $source_id 商品来源id
	 * @return string 来源名称， 例如：淘宝价，天猫价。。。
	 *
	 * @author 杜嘉杰
	 * @version 2015年10月15日  上午11:39:05
	 *
	 */
	function goods_source_name($source_id)
	{
		static $goods_sources = NULL;
		if( $goods_sources === NULL )
		{
			// 查询缓存
			$cache_key = 'goods_sources_show';
			$cache_data = cache($cache_key);
			if( ! $cache_data){
				// 从数据库获取数据
				$CI =& get_instance();
				$CI->load->model('YL_goods_source_model','source_model');
				
				$where = array('state'=>1);
				$db_source = $CI->source_model->select('id,show_name')->where($where)->find_all();
		
				$cache_data = array();
				foreach($db_source as $item)
				{
					$cache_data[$item['id']] = $item;
				}
				cache($cache_key, $cache_data, 86400);
			}
			$goods_sources = $cache_data;
		}
		
		$goods_source_name = '网购价';
		if(isset($goods_sources[$source_id]['show_name']))
		{
			$goods_source_name = $goods_sources[$source_id]['show_name'];
		}
		return $goods_source_name;
	}
}	
	
if ( ! function_exists('save_log'))
{
    /**
     *写入日志
     *
     * @param string $msg 描述
     * @param array $other 其它数据
     * @param string $level 日志的类型
     * @param string $path 指定子目录，如果想放在默认目录传入NULL
     *
     * @author 韦贵华
     * @version 2015年11月5日09:59:20
     */
    function save_log($msg, $other = NULL, $level = 'INFO', $path=NULL)
    {
        static $_log;

        if (config_item('log_threshold') == 0)
        {
            return;
        }

        $_log =& load_class('Log');
        return $_log->save_log($msg, $other, $level,$path);
    }
}

if( ! function_exists('adjust_tips'))
{
	/**
	 *  返回调整返现金额提示(使用范围：买家后台、商家后台、管理员后台)
	 *
	 * @param array $search_where 查询条件
	 * @return boolean/html
	 */
	function adjust_tips( $search_where )
	{
		if( !is_array($search_where) || !isset($search_where['oid']) || intval($search_where['oid'])<=0 )
		{
			return FALSE;
		}
		$CI = &get_instance();
		//查询调整返现金额记录表
		$CI->load->model('order_adjust_rebate_model');
		$adjust_rebate_logs = $CI->order_adjust_rebate_model->find_all_by( $search_where );
		if( !$adjust_rebate_logs ) return FALSE;
		$goodsDetail =  $CI->db->select('price,mobile_price')->where('gid',$adjust_rebate_logs['0']['gid'])->get('goods')->row_array();
		//查询订单的信息
		$CI->load->model('order_model');
		$order_info = $CI->order_model->get($search_where['oid']);
		$data['goodsDetail'] = $goodsDetail;//手机专享价格
		$data['due_rebate'] = $order_info['due_rebate'];  //原返现金额
		$data['real_rebate'] = $order_info['real_rebate'];  //最终返现金额
		$data['adjust_rebate_logs'] = $adjust_rebate_logs; //调整返现金额详细信息
	
		return $CI->load->view('template/adjust_rebate',$data,TRUE);
	}
}

if( ! function_exists('mask_email')){
    /**
     * 处理邮箱地址遮罩
     * @param string $str  字符串
     *  @param Int $num 整数  遮罩*号的个数
     */
    function mask_email($str, $num =4) {
        list ( $name, $host ) = array_pad ( explode ( '@', $str ), 2, '' );
        $len = mb_strlen ( $name );
        if ($len >= 6) {
            $start = 2;
            $star_len = $len - 4;
        } else if ($len >= 3) {
            $start = 1;
            $star_len = $len - 2;
        } else {
            $start = $len;
            $star_len = $num;
        }
        return substr_replace ( $name, str_repeat ( '*', $num ), $start, $star_len ) . '@' . $host;
    }
}

if( ! function_exists('mask_mobile')){
    /**
     * 处理手机号码遮罩
     * @param string $str  字符串
     * @param Int $star  $star_len  整数
     */
    function mask_mobile($str, $start = 3, $star_len = 4) {
        return substr_replace ( $str, str_repeat ( '*', $star_len ), $start, $star_len );
    }
}
