<?php
/**
 * 用户模型
 * @author minch <yeah@minch.me>
 */

class Admin_user_model extends CI_Model
{
	/**
	 * 试客用户类型
	 * @var number
	 */
	const UTYPE_BUYRER = 1;
	
	/**
	 * 商家用户类型
	 * @var number
	 */
	const UTYPE_SELLER = 2;

	/**
	 * 当前模型对应表名
	 * @var string
	 */
	private $_table;
	
	private $error;
	
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		$this->_table = 'user';
	}
	
	public function error(){
		return $this->error;
	}
	
	

	/**
	 * 搜索用户
	 * @param string $key 搜索字段
	 * @param string $val 搜索值
	 * @param string $utype 用户类型
	 * @param string $ext_where 额外条件
	 * @param string $order
	 * @param number $limit
	 * @param number $offset
	 * @param string  $uesr_source_where 用户来源url条件筛选 
	 * @param array() $other_uids  用户来源其它url uid
	 */
	public function search($key = '', $val = '', $utype = '', $ext_where = '', $order = '', $limit = 10, $offset = 0,$user_source_where='',$other_uids=array())
	{
		if($key && $val){
			switch($key){
				case 'uid':
					$this->db->where('uid', $val);
					break;
				case 'uname':
					$this->db->where('uname', $val);
					break;
				case 'email':
					$this->db->where('email', $val);
					break;
				case 'mobile':
					$this->db->where('mobile', $val);
					break;
				case 'regip':
				    //判断是否是为合法IP地址
				    if(filter_var(trim($val), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
				        $this->db->where('regip', bindec(decbin(ip2long(trim($val)))));
				    }else{
				        $this->db->where('regip', 1);
				    }
				    break;
				case 'bind_account':
					$uids = array();
					$this->db->where('bind_name', $val);
					foreach ($this->db->select('uid')->from('user_bind')->get()->result_array() AS $k=>$v){
						if($v['uid']){
							$uids[] = $v['uid'];
						}
					}
					if(count($uids)){
						$this->db->where_in('uid', $uids);
					}else{
					    $this->db->where_in('uid', 0);
					}
					break;					
				default:
					// XXXX do nothing
			}
		}
		if('' !== $utype){
			$this->db->where('utype', $utype);
		}
		if('' !== $ext_where){
			if (is_array($ext_where)) {
				foreach ($ext_where as $k=>$v){
					if(is_numeric($k)){
						$this->db->where($v);
						continue;
					}
					//fix
					if (is_string($k)){
						$this->db->where($k, $v);
						continue;
					}
					//
					if(is_array($v)){
						$this->db->where($k, array_shift($v), (boolean)array_shift($v));
					}elseif (is_string($v)){
						$this->db->where($k, $v);
					}
				}
			}elseif (is_string($ext_where)){
				$this->db->where($ext_where);
			}
		}
		if('' !== $user_source_where){
			$this->db->where($user_source_where,null,false);
		}
		if(isset($other_uids['otheruids']) && count($other_uids['otheruids'])>0){
			$this->db->where_in('uid',$other_uids['otheruids']);
		}else{
			if(isset($other_uids['user_uids']) && count($other_uids['user_uids'])>0){
			$this->db->where_not_in('uid',$other_uids['user_uids']);
			}
		}
		if('' !== $order){
			$this->db->order_by($order);
		}
		if($limit){
			$this->db->limit($limit, $offset);
		}
		$this->db->select('uid,uname,utype,email,mobile,is_lock,lock_day,release_lock_time,bind,reg_source,dateline,regip,is_premium')->from('user');
		$result = $this->db->get()->result_array();//echo $this->db->last_query();exit;
		$arr_uid = $return = array();
		//自动屏蔽-状态原因
		foreach($result as $user)
		{
		    if($user['is_lock'] > 0)
		    {
    		    $info = $this->db->select('content')
    		    				->from('user_lock_log')
    		    				->where(array('uid'=>$user['uid'],'after_state'=>$user['is_lock']))
    		    				->order_by('id','desc')
    		    				->limit(1)
    		    				->get()
    		    				->row_array();
    		    
    		    $user['content'] = $info['content'];
		    }
		    else
		    {
		        $user['content'] = '-';
		    }
		    $arr_uid[] = $user['uid'];
		    $user['is_bind_taobao'] = FALSE;
		    $return[$user['uid']] = $user;
		}
		unset($result);

		if ($arr_uid)
		{
			// 绑定淘宝信息
			$bind_info = $this->db->select('uid,bind_type,bind_name,dateline')
							->from('user_bind')
							->where_in('uid', $arr_uid)
							->get()
							->result_array();

			if ($bind_info)
			{
				foreach ($bind_info as $bind)
				{
					$return[$bind['uid']]['is_bind_taobao'] = TRUE; // 给予绑定标识
				}
			}

		}
		//
		return $return;
	}

	/**
	 * 搜索用户统计数
	 * @param string $key 搜索字段
	 * @param string $val 搜索值
	 * @param string $utype 用户类型
	 * @param string $ext_where 额外条件
	 * @param string  $uesr_source_where 用户来源条件筛选
	* @param array() $other_uids  用户来源其它url uid
	 */
	public function search_count($key = '', $val = '', $utype = '', $ext_where = '',$user_source_where='',$other_uids=array())
	{
		if($key && $val){
			switch($key){
				case 'uid':
					$this->db->where('uid', $val);
					break;
					break;
				case 'uname':
					$this->db->where('uname', $val);
					break;
				case 'email':
					$this->db->where('email', $val);
					break;
				case 'mobile':
					$this->db->where('mobile', $val);
					break;
				case 'regip':
				    //判断是否是为合法IP地址
			        if(filter_var(trim($val), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
				        $this->db->where('regip', bindec(decbin(ip2long(trim($val)))));
				    }else{
				        $this->db->where('regip', 1);
				    }
				    break;					
				case 'bind_account':
					$uids = array();
					$this->db->where('bind_name', $val);
					foreach ($this->db->select('uid')->from('user_bind')->get()->result_array() AS $k=>$v){
						if($v['uid']){
							$uids[] = $v['uid'];
						}
					}
					if(count($uids)){
						$this->db->where_in('uid', $uids);
					}else{
					    $this->db->where_in('uid', 0);
					}
					break;
				default:
					// XXXX do nothing
			}
		}
		if('' !== $utype){
			$this->db->where('utype', $utype);
		}
		if('' !== $ext_where){
			if (is_array($ext_where)) {
				foreach ($ext_where as $k=>$v){
					if(is_numeric($k)){
						$this->db->where($v);
						continue;
					}
					//fix
					if (is_string($k)){
						$this->db->where($k, $v);
						continue;
					}
					//
					if(is_array($v)){
						$this->db->where($k, array_shift($v), (boolean)array_shift($v));
					}elseif (is_string($v)){
						$this->db->where($k, $v);
					}
				}
			}elseif (is_string($ext_where)){
				$this->db->where($ext_where);
			}
		}
		if('' !== $user_source_where){
			$this->db->where($user_source_where,null,false);
		}
	   if(isset($other_uids['otheruids']) && count($other_uids['otheruids'])>0){
			$this->db->where_in('uid',$other_uids['otheruids']);
		}else{
			if(isset($other_uids['user_uids']) && count($other_uids['user_uids'])>0){
			$this->db->where_not_in('uid',$other_uids['user_uids']);
			}
		}
		$this->db->select('uid')->from('user');
		return $this->db->get()->num_rows(); 
	}
	
	/**
	 * 屏蔽用户
	 * @param string|array $uids 要屏蔽的用户ID
	 * @param int $state 用户状态
	 * @param number $day 屏蔽天数(0-365)(如果为0则需要手动解除屏蔽)
	 */
	public function lock($uids, $state ,$content, $day = 0,  $admin_uid)
	{
		if(is_string($uids)){
			$uids = explode(',', $uids);
		}
		
		// 去重复
		$uids = array_unique($uids);
		
		if(!is_array($uids) OR !count($uids)){
			return false;
		}
		
		$error = '';
		$ret = TRUE;

		$ip = bindec(decbin(ip2long($this->input->ip_address())));
		foreach ($uids as $uid){
			$r = $this->db->query('CALL proc_user_lock(?,?,?,?,?,?)', array($uid, $state, $content, $day, $admin_uid, $ip))->row_array();
			$this->db->close();
			if( ! in_array($r['Code'] , array(1,-4) ) ){
				$error.= $r['Message'].'(uid='.$uid.')';
				$ret = FALSE;
			}
		}
		$this->error = $error;
		return $ret;
	}

	/**
	 * 查询用户的抢购记录
	 * @param number $uid
	 * @param number $limit
	 * @param number $offset
	 */
	public function get_order_list($uid, $limit = 10, $offset = 0)
	{
		$this->db->where('buyer_uid', $uid);
		$this->db->limit($limit, $offset);
		return $this->db->from('order')->get()->result('array');
	}
	
	/**
	 * 查询用户的抢购记录数
	 * @param number $uid
	 */
	public function get_order_count($uid)
	{
		$this->db->where('buyer_uid', $uid);
		return $this->db->from('order')->get()->num_rows();
	}
	
	/**
	 * 根据用户名查询用户信息
	 * @param string $uname
	 */
	public function getby_uname($uname)
	{
		$this->db->where('uname', $uname);
		return $this->db->from($this->_table)->get()->first_row('array');
	}
	
	/**
	 * 搜索用户晒单
	 * @param string $search_key 搜索字段
	 * @param string $search_val 搜索值
	 * @param array $exclude_ids 排除的ID
	 * @param string $ext_where 其它条件
	 * @param string $order 排序
	 * @param number $limit 查询记录数
	 * @param number $offset
	 */
	public function search_show($search_key = '', $search_val = '', $exclude_ids = array(), $ext_where = '', $order = '', $limit = 10, $offset = 0)
	{
		$this->db = $this->load->database('shikee', TRUE);
		$this->db->select('id, uid, uname, yesvalueid AS gid, jid AS oid, title, img_url');
		$this->db->from('yesvalue_showshop');
		if('' !== $search_key && '' !== $search_val){
			switch ($search_key) {
				case 'uid':
					$this->db->where('uid', $search_val, FALSE);
					break;
				case 'uname':
					$this->db->like('uname', $search_val);
					break;
				case 'gid':
					$this->db->where('yesvalueid', $search_val, FALSE);
					break;
				case 'title':
					$this->db->like('title', $search_val);
					break;
			}
		}
		if(is_array($exclude_ids) && count($exclude_ids)){
			$this->db->where_not_in('id', $exclude_ids);
		}
		if('' !== $ext_where){
			$this->db->where($ext_where);
		}
		$this->db->limit($limit, $offset);
		$result = $this->db->get()->result_array();
		$this->db->close();
		$this->db = $this->load->database('default', TRUE);
		return $result;
	}
	
	/**
	 * 搜索用户晒单数
	 * @param string $search_key 搜索字段
	 * @param string $search_val 搜索值
	 * @param array $exclude_ids 排除的ID
	 * @param string $ext_where 其它条件
	 */
	public function search_show_count($search_key = '', $search_val = '', $exclude_ids = array(), $ext_where = '')
	{
		$this->db = $this->load->database('shikee', TRUE);
		$this->db->select('id');
		$this->db->from('yesvalue_showshop');
		if('' !== $search_key && '' !== $search_val){
			switch ($search_key) {
				case 'uid':
					$this->db->where('uid', $search_val, FALSE);
					break;
				case 'uname':
					$this->db->like('uname', $search_val);
					break;
				case 'gid':
					$this->db->where('yesvalueid', $search_val, FALSE);
					break;
				case 'title':
					$this->db->like('title', $search_val);
					break;
			}
		}
		if(is_array($exclude_ids) && count($exclude_ids)){
			$this->db->where_not_in('id', $exclude_ids);
		}
		if('' !== $ext_where){
			$this->db->where($ext_where);
		}
		return $this->db->get()->num_rows();
	}

	/**
	 * 一站成名或名品馆扣款
	 * @param array $params 存储过程参数数组
	 * @param bool $iscontinue 是否是继续上次扣款
	 */
	function update_deduct_order($params, $iscontinue=FALSE){
		$back = array('Code'=>-1000, 'Message'=>'更新扣款订单存储过程未执行或执行错误');
		$_param = $inparams = array();
		if(is_array($params)){
			foreach ($params as $k=>$value) {
				$inparams[$k] = $value;
			}
			$_param = array_pad(array(), count($inparams), '?');
			$proc = $iscontinue ? 'proc_deposit_deduct_continue_before_pay_update_order' : 'proc_deposit_deduct_before_pay_update_order';
			$call = 'CALL '.$proc.'('.implode(', ', $_param).');';//以?填充参数
			$back = $this->db->query($call, $inparams)->row_array();
			$this->db->close();
		}
		return $back;
	}
	
	/**
	 * 一站成名扣款成功后处理
	 */
	function deduct_handle($params){
		$back = array('Code'=>-1000, 'Message'=>'扣款成功业务处理存储过程未执行或执行错误');
		$_param = $inparams = array();
		if(is_array($params)){
			foreach ($params as $k=>$value) {
				$inparams[$k] = $value;
			}
			$_param = array_pad(array(), count($inparams), '?');
			$call = 'CALL proc_deposit_deduct_handle('.implode(', ', $_param).');';//以?填充参数
			$back = $this->db->query($call, $inparams)->row_array();
			$this->db->close();
		}
		return $back;
	}
	
	/**
	 * 用户屏蔽状态到期后恢复到正常
	 * @return int 受影响行数
	 */
	public function update_lock_status(){
		$ret = $this->db->query('UPDATE shs_user SET is_lock = 0, release_lock_time = 0 WHERE is_lock IN(2,3,4) AND release_lock_time<>0 AND UNIX_TIMESTAMP()>release_lock_time;');
	}
	
	/**
	 * 获取用户注册来源url统计
	 */
	 public function get_reg_source($startTime=0,$endTime=0,$search_val=''){
		if ($search_val != '') {
			// 输入名称搜索
			$search_url = '';
			$urllist = array ();
			
			$this->load->model('system_config_reg_source_model','reg_source_model');
			$urls = $this->reg_source_model->find_all();
			$urls = $urls ? $urls : array();
			foreach ( $urls as $k => $v ) {
				if (stristr ( $v ['name'], $search_val ) !== false) {
					$search_url = $v ['url'];
					break;
				}
			}
			if ($search_url !== '') {
	 				$this->db->where ( ' (reg_from_url  like "%' . $search_val . '%" or  reg_from_url  like "%' . $search_url . '%")', null, false );
	 			} else {
	 				$this->db->like ( 'reg_from_url', $search_val );
	 		}
		}
		if ($startTime > 0 && $endTime > 0) {
			$this->db->where ( '  dateline  BETWEEN ' . $startTime . ' and ' . $endTime, null, false );
		}
		$this->db->select ( 'uid,uname,utype,reg_from_url' );
		return $this->db->from('user' )->get ()->result_array();
	 }
	
	/**
	 * 获取用户注册来源url统计导出
	 */
	public function get_reg_source_export($startTime = 0, $endTime = 0, $search_val = '', $other_uids = array(), $type = 0) {
		if ($search_val != '') {
			// 输入名称搜索
			$search_url = '';
			$urllist = array ();
			$this->load->model('system_config_reg_source_model');
			$urls = $this->system_config_reg_source_model->find_all();
			foreach ( $urls as $k => $v ) {
				if (stristr ( $v ['name'], $search_val ) !== false) {
					$search_url = $v ['url'];
					break;
				}
			}
			if ($type == 2) { // 精确筛选
				if ($search_url !== '') {
					$this->db->where ( ' (reg_from_url  = "' . $search_val . '" or  reg_from_url  = "' . $search_url . '")', null, false );
				} else {
					$this->db->where ( 'reg_from_url', $search_val );
				}
			} else {
				foreach ( $urls as $k => $v ) {
					// 模糊查询，排除精确的
					if ($v ['type'] == 2) {
					if (stristr ( $v ['url'], $search_val ) !== false && $v ['url'] != $search_val) {
						$urllist [] = $v ['url'];
					}
				 }
				}
				if ($type == 1 && count ( $urllist ) > 0) {
					
					if ($startTime > 0 && $endTime > 0) {
						$this->db->where ( '  dateline  BETWEEN ' . $startTime . ' and ' . $endTime, null, false );
					}
					$this->db->where_in ( 'reg_from_url', $urllist );
					$urluser = $this->db->select ( 'uid' )->from ( 'user' )->get ()->result_array ();
					$url_uidlist = array ();
					foreach ( $urluser as $u ) {
						$url_uidlist [] = $u ['uid'];
					}
					if (count ( $url_uidlist ) > 0) {
						$this->db->where_not_in ( 'uid', $url_uidlist );
					}
				}
				if ($search_url !== '') {
					$this->db->where ( ' (reg_from_url  like "%' . $search_val . '%" or  reg_from_url  like "%' . $search_url . '%")', null, false );
				} else {
					$this->db->like ( 'reg_from_url', $search_val );
				}
			}
		}
		if ($startTime > 0 && $endTime > 0) {
			$this->db->where ( '  dateline  BETWEEN ' . $startTime . ' and ' . $endTime, null, false );
		}
		if ( isset($other_uids ['otheruids']) && count ( $other_uids ['otheruids'] ) > 0) {
			$this->db->where_in ( 'uid', $other_uids ['otheruids'] );
		} else {
			if (isset($other_uids ['user_uids']) && count ( $other_uids ['user_uids'] ) > 0) {
				$this->db->where_not_in ( 'uid', $other_uids ['user_uids'] );
			}
		}
		$this->db->select ( 'uid,uname,utype,reg_from_url,reg_source' );
		return $this->db->from ( 'user' )->get ()->result_array ();
	}
}
