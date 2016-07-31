<?php

/**
 * 获取从库数据库连接
 * 
 * @TODO 随机返回从库连接
 * 
 * @param string $slave_name (可选)从库的连接名称
 * @return object DB
 */
if ( ! function_exists('get_slave_db')) {
	
	function get_slave_db($slave_name = NULL) {

		$CI =& get_instance();
		
		if ($slave_name === NULL) {
			// 默认从库
			return $CI->load->database('slave', TRUE);
		}else {
			return $CI->load->database($slave_name, TRUE);
		}
	}
}

/**
 * 核心函数库
 * @author 宁天友
 * @copyright 宁天友 - www.zhonghuasuan.com
 * @since 
 * @license 
 * @version 2.0
 */

/**
 * 替换内容中的url标示符，如{#domain_detail#} 替换为 http://detail.zhonghuasuan.com/
 * @param string $content 内容
 * @return string 替换后的content
 */
function content_replace_url($content){
	$CI = &get_instance();
	$replace_item = array(
		'domain_www','domain_list','domain_detail','domain_buyer','domain_seller',
		'domain_mobile',
	);
	foreach ($replace_item as $item) {
		$r_palce = $CI->config->item($item);
		if ($r_palce != '' && preg_match('/\{#'.$item.'#\}/', $content)) {
			$content = preg_replace('/\{#'.$item.'#\}/', $r_palce, $content);
		};
	}
	return $content;
}

/**
 * 获取用户头像
 * @param int $uid 用户uid
 * @param string $size 头像规格： 'big', 'middle', 'small'，默认middle
 * @param string $show_type 强制显示方式，可选php|img，默认空 ,强制方式未定义,则以头像配置为准
 * 
 * @return string 头像地址,如:http://uc.shikee.com/avatar.php?uid=2090063866&size=middle
 */
function avatar($uid, $size = 'middle', $show_type = '') {
	$avatar_server = $avatar_path = '';
	$CI = &get_instance();
	$avatar_setting = $CI->config->item('avatar');
	$size = in_array($size, array('big', 'middle', 'small')) ? $size : 'middle';
	if( ! isset($avatar_setting['server'][$show_type])){
		$show_type = $avatar_setting['type'];
	}
	if($show_type == 'img'){
		$uid = abs(intval($uid));
		$_uid = sprintf("%011d", $uid);
		$dir1 = substr($_uid, 0, 3);
		$dir2 = substr($_uid, 3, 3);
		$dir3 = substr($_uid, 6, 3);
	}
	$rand_num = rand(1000,100000);
	$avatar_server = $avatar_setting['server'][$show_type];
	return $avatar_server.($show_type == 'php' ? 'avatar.php?uid='.$uid.'&size='.$size : 'data/avatar/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).'_avatar_'.$size.'.jpg?fresh='.$rand_num);
}

/**
 * 拼接sql字段
 * @param array $array 字段数据数组
 * @param string $glue 链接符,默认：,
 * 
 * @return string
 * 
 * @example $field = implode_field_value(array('id'=>1, 'uid'=>2));//返回：`id`=1,`uid`=2
 */
function implode_field_value($array, $glue = ',') {
	$sql = $comma = '';
	foreach ($array as $k => $v) {
		$sql .= $comma."`$k`='$v'";
		$comma = $glue;
	}
	return $sql;
}

/**
 * 字符串截取
 * @param string $string 源字符串
 * @param int $length 截取的长度
 * @param string $dot 省略部分表示的符号,默认' ...'
 * @param string $charset 字符串编码,默认'utf-8'
 * 
 * @return string
 * @example echo cutstr('abcdefg', 4,);//输出：abcd ...
 */
function cutstr($string, $length, $dot = ' ...', $charset = 'utf-8') {
	if(strlen($string) <= $length) {
		return $string;
	}

	$pre = chr(1);
	$end = chr(1);
	$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre.'&'.$end, $pre.'"'.$end, $pre.'<'.$end, $pre.'>'.$end), $string);

	$strcut = '';
	if(strtolower($charset) == 'utf-8') {

		$n = $tn = $noc = 0;
		while($n < strlen($string)) {

			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t <= 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}

			if($noc >= $length) {
				break;
			}

		}
		if($noc > $length) {
			$n -= $tn;
		}

		$strcut = substr($string, 0, $n);

	} else {
		for($i = 0; $i < $length; $i++) {
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
	}

	$strcut = str_replace(array($pre.'&'.$end, $pre.'"'.$end, $pre.'<'.$end, $pre.'>'.$end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

	$pos = strrpos($strcut, chr(1));
	if($pos !== false) {
		$strcut = substr($strcut,0,$pos);
	}
	return $strcut.$dot;
}

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

/**
 * 清除不是存储过程参数的字段
 * @param array $parma 存放存储过程参数数据
 * @param array $data 字段数据(最多1维数组)
 * @param string $prefix 字段前缀
 */
function clear_fields(&$parma, $data, $prefix){
	if(is_array($data)){
		foreach ($data as $i=>$sub) {
			if($sub === '?'){
				unset($data[$i]);
			}
			if($sub === 'UNIX_TIMESTAMP'){
				unset($data[$i]);
			}
		}
		foreach ($data as $i=>$sub) {
			if(is_array($sub) && empty($sub) && count($sub) <= 0){
				unset($data[$i]);
			}
		}
		foreach ($data as $i=>$sub) {
			$parma[$prefix.$i] = $data[$i];
		}
	}
}

/**
 * 生成预处理sql语句
 * @param string $table_name 表全名
 * @param array $params 数据
 * @param string $type 类型：insert|update|delete
 * @param array $update_where 更新条件字段，$type为delete或update时有效
 * @deprecated 废弃
 */
function get_prepare_sql($table_name, $params, $type, $update_where=array()){
	$return = '';
	$type = strtoupper($type);
	if(is_array($params) && trim($table_name) != '' && in_array($type, array('INSERT', 'UPDATE', 'DELETE'))){
		$_params = array();
		$_where = array();
		if($type == 'INSERT'){
			foreach ($params as $field=>$value) {
				$_params[$field] = '?';
			}
			$fieldstr = implode_field_value($_params);
			if($fieldstr != '' && $fieldstr != ''){
				$return = 'INSERT INTO '.$table_name.' SET '.$fieldstr;
			}
		}else if($type == 'UPDATE'){
			foreach ($params as $field=>$value) {
				$_params[$field] = '?';
			}
			$fieldstr = implode_field_value($_params);
			foreach ($update_where as $field=>$value) {
				$_where[$field] = '?';
			}
			$wherestr = implode_field_value($_where, ' AND ');
			if($wherestr != '' && $fieldstr != ''){
				$return = 'UPDATE '.$table_name.' SET '.$fieldstr.' WHERE '.$wherestr;
			}
		}else{
			foreach ($update_where as $field=>$value) {
				$_where[$field] = '?';
			}
			$wherestr = implode_field_value($_where);
			if($wherestr != ''){
				$return = 'DELETE FROM '.$table_name.' WHERE '.$wherestr;
			}
		}
	}
	$return = str_replace("'?'", '?', $return);
	return $return;
}

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
function show_message($message, $actionurl=array(), $extraparam=array()){
	error_reporting(0);
	$CI = &get_instance();
	$inajax = $CI->input->get_post('inajax', TRUE);//$inajax=1 ajax方式
	$newwin = $CI->input->get_post('nw', TRUE);//是否是新开页面
	if( ! $inajax){
		$inajax = isset($extraparam['inajax'])?$extraparam['inajax']:FALSE;
		if( ! $inajax){
			$extraparam['backurl'] =isset($extraparam['backurl']) ? trim($extraparam['backurl']) : '';
		}
	}
	$data['the'] = $CI;
	$data['inajax'] = $inajax;
	$data['newwin'] = $newwin;
	$data['message'] = $message;
	$data['actionurl'] = $actionurl;
	$data['extraparam'] = $extraparam;
	$data['domain_static'] = $CI->config->item('domain_static');
	$buffer = $CI->load->view('back/message', $data, true);
	echo $buffer;exit;
}


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

/**
 * 处理成功返回json数据
 *
 * @param mixed $data 返回的数据。默认：NULL
 * @param string $callback 跨域时输出的方法名
 * @param bool $out_put 输出方式(默认true)，true:直接输出内容并停止程序，false：只返回内容
 */
function ajax_success($data = NULL, $callback = 'callback', $out_put=TRUE) {
	$ret = array('success' => TRUE);
	$data!==NULL && ($ret['data'] = $data);
	$json_str = json_encode($ret);
	if(isset($_GET[$callback])){
		$json_str = $_GET[$callback].'('.$json_str.');';
	}
	
	if($out_put)
	{
		die($json_str);
	}
	else
	{
		return $json_str;
	}
}

/**
 * 处理失败返回json数据
 *
 * @param mixed $data 返回的数据。默认：NULL
 * @param string $callback  跨域时输出的方法名
 * @param bool $out_put 输出方式(默认true)，true:直接输出内容并停止程序，false：只返回内容
 */
function ajax_error($data = NULL, $callback = 'callback', $out_put=TRUE) {
	$ret = array('success' => FALSE);
	$data!==NULL && ($ret['data'] = $data);
	$json_str = json_encode($ret);
	if(isset($_GET[$callback])){
		$json_str = $_GET[$callback].'('.$json_str.');';
	}
	
	if($out_put)
	{
		die($json_str);
	}
	else 
	{
		return $json_str;
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
		$CI->load->model('zhs_uc_password_rules');
		$password_rules = $CI->zhs_uc_password_rules->find_password_rules();
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

/**
 * 发送站内信
 *
 * @param string $to 发送对象：如果单个或多个用户,使用用户uid, 并用逗号分割,
 * 						如果发给全部用户, 使用:"all",
 * 						如果仅仅发送给买家, 使用:"buyer",
 * 						如果仅仅发送给商家，使用:"seller"
 * @param int $id 站内信ID
 * @param string $title 信息标题
 * @param string $content 信息内容
 * @param number $level 发送优先级(整数，越低越优先)
 * @param number $starttime 定时发送(开始时间)
 * @param number $endtime 定时发送(结束时间)
 * @param number $lastlogintime 用户最后登录时间(用于筛选活跃用户)
 *
 * @return boolean
 */
function send_message($to, $id, $title, $content, $level = 3, $starttime = 0, $endtime = 0, $lastlogintime = 0)
{
	$_STOMP			= NULL;
	$_queue_theme	= 'zhs/site/batch/message';
	$CI				= &get_instance();

	if ( ! extension_loaded('Stomp')) {
		log_message('error', 'Stomp扩展没有安装.');
		return FALSE;
	}

	if ($CI->config->load('stomp', TRUE, TRUE)) {
		$config = $CI->config->item('stomp');
		// 队列主题
		if (isset($config['sitemessage_queue'])) {
			$_queue_theme = $config['sitemessage_queue'];
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

	$arr_message = array(
			'to'			=> $to,
			'msgId'			=> $id,
			'title'			=> $title,
			'content'		=> $content,
			'id'			=> uniqid(), // 连续并发操作可能性不高,这个函数够用
			'level'			=> $level,
			'startTime'		=> $starttime,
			'endTime'		=> $endtime,
			'lastLoginDate' => $lastlogintime,
	);
	$message = json_encode($arr_message);

	if ( ! $_STOMP->send($_queue_theme, $message)) {
		log_message('error', "站内信:{$to}发送失败,错误原因:".$_STOMP->error());
		return FALSE;
	}

	return TRUE;
}

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
			$CI->load->model('zhs_user_model');
			if( $CI->zhs_user_model->mobile_valid( $uid ) )
			{
				$return = TRUE;
			}
			$CI->cache->save($key, $return , 5);
		}
		return $return;
	}
}
/**
 * 把静态的css文件和js文件转换为minify显示的的方式
 * @param $asset_css 字符串和数组，注意：传参文件中的文件后缀名要统一
 */
if (!function_exists('change_to_minify')) {
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
				$CI->load->model('zhs_goods_source_model','source_model');
				
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


