<?php
/**
 * 互联支付交易类（java版）
 * 
 * @author 杜嘉杰
 * @version 2015-06-16
 */
class Hlpay_v2 {
	/**
	 * @var 互联支付V2服务器地址
	 */
	protected $server;
	
	/**
	 * @var 站点
	 */
	protected $site;
	
	/**
	 * @var 秘钥
	 */
	protected $key;

	// 保存状态
	private $flag;

	private $CI; 
	
	function __construct() {
		$this->CI = &get_instance();
		$this->server = $this->CI->config->item('hulian_server_v2') or show_error('缺少配置项：hulian_server_v2');
		$this->site = $this->CI->config->item('hulian_site') or show_error('缺少配置项：hulian_site');
		$this->key = defined('KEY_HLPAY_V2')?KEY_HLPAY_V2 : show_error('缺少配置项：KEY_HLPAY_V2');
	}
	
	/**
	 * 获取上次操作的状态及数据
	 * 
	 * @return array 
	 * @author 杜嘉杰
	 * @version 2015年6月16日 上午10:22:19
	 */
	public function flag()
	{
		return $this->flag;
	}
	
	/**
	 * 设置操作的状态及数据
	 * 
	 * @param int/string $code 状态码
	 * @param unknown $msg 文字描述
	 * @param string $data 附加数据
	 * 
	 * @author 杜嘉杰
	 * @version 2015年6月16日 上午10:21:11
	 */
	protected function set_flag($code, $msg, $data = NULL)
	{
		$this->flag = array('code'=>$code, 'msg'=>$msg, 'data'=>$data);
	}
	
	/**
	 * 查询互联支付是否有此用户
	 * 
	 * @param string $userdata 账号名(用户编号/:电话/:邮箱)
	 * @param string $type 查询方式,uid:用户编号, mobile:电话, email:邮箱
	 * @return boolean
	 * 返回状态：
	 * SUCCESS:用户存在
	 * TYPE_NOT_FIND:无效查询类型
	 * ERROR:用户不存在/其它错误
	 * 
	 * @author 杜嘉杰
	 * @version 2015年6月16日 上午10:22:37
	 */
	public function hlpay_user_exixts($userdata, $type)
	{
		
		// 1:用户编号  2:电话  3:邮箱
		$type_map = array('uid'=>'1', 'mobile'=>'2', 'email'=>'3');
		if (isset($type_map[$type]) == FALSE)
		{
			//无效的查询类型
			$this->set_flag('TYPE_NOT_FIND', '无效查询类型');
			return false;
		}
		
		// jsonData  数据
		$json_data = json_encode(array(
			"site"=>$this->site,
			"userdata"=>$userdata,
			"type" => $type_map[$type]
		));
		
		// sign 数据
		$sign = md5($json_data . $this->key);
		
		$param = array(
			"jsonData" => $json_data,
			"sign" => $sign
		);
		
		$url = $this->server. 'isExistOfUser';
		
		$this->CI->load->library('YL_http');
		$result_str = $this->CI->YL_http->post($url, $param);
		/* 互联支付返回值：
		 * error	String	错误信息
		 * success	Number	用户编号
		*/
		
		$result = json_decode($result_str,true);
		
		if (isset($result['success']))
		{
			$data = array('uid'=>$result['success']);
			$this->set_flag('SUCCESS', '用户存在', $data);
			return TRUE;
		}else {
			$this->set_flag('ERROR', $result['error']);
			return FALSE;
		}
		
		
	}
	
}