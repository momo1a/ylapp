<?php

/**
 * 配置文件类
 *
 */
class YL_setting
{
	/**
	 * 保存状态
	 * @var array
	 */
	protected $flag;
	
	protected $CI;
	
	public function __construct()
	{
		$this->CI = &get_instance();
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
	 * 获取上次操作的状态
	 *
	 * @return string/int:
	 *
	 * @author 杜嘉杰
	 * @version 2015年7月16日 上午10:21:29
	 */
	public function flag_code()
	{
		return $this->flag['code'];
	}
	
	/**
	 * 获取上次操作的状态文字描述
	 *
	 * @return string:
	 *
	 * @author 杜嘉杰
	 * @version 2015年7月16日 上午10:21:46
	 */
	public function flag_msg()
	{
		return $this->flag['msg'];
	}
	
	/**
	 * 获取上次操作的数据
	 *
	 * @return array
	 *
	 * @author 杜嘉杰
	 * @version 2015年7月16日 上午10:22:42
	 */
	public function flag_data()
	{
		return $this->flag['data'];
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
	 * 生成配置缓存文件
	 *
	 * @author 杜嘉杰
	 * @version 2015年10月13日  上午10:16:12
	 *
	 */
	public function build_cache()
	{
		
		$configs = array(); 
		
		// 系统配置
		$this->CI->load->model('YL_system_config_model');
		$system_config = $this->CI->YL_system_config_model->find_all();
		
		// TODO 补充的配置

		// 最终的配置数据
		$configs = array_merge($system_config);

		$str = "<?php\n";
		foreach ($configs as $k=>$v){
			$str .= '$config[\''.$v['key'].'\']='.$this->_value_filter($v['key'], $v['value']).'; //'.$v['remark']."\n";
		}
		$str .= "\n?>";
		if(defined('ENVIRONMENT')){
			file_put_contents(COMPATH.'/config/'.ENVIRONMENT.'/shs_system.php', $str);
		}else {
			file_put_contents(COMPATH.'/config/shs_system.php', $str);
		}
		
		// app站点生成配置文件
		//$ret = $this->build_app_config();
        $ret = true;
		if( ! $ret){
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * 配置值过虑
	 * @param string $key
	 * @param mix $val
	 */
	private function _value_filter($key, $val)
	{
		switch ($key) {
			case 'goods_auto_online_time':
				return '\''.$val.':00\'';
			case 'order_auto_clear_time_min':
				return $val/60;
			case 'order_auto_close_time_day':
			case 'order_auto_checkout_time_day':
				return $val/86400;
		}
		if (is_numeric($val)) {
			return $val;
		}else{
			return '\''.$val.'\'';
		}
	}
	
	/**
	 * app站点生成配置文件
	 *
	 * @author 杜嘉杰
	 * @version 2014-10-18
	 */
	public function build_app_config(){
		$domain_appsystem=config_item('domain_appsystem');
		if ($domain_appsystem==''){
			$this->set_flag('CONFIG_ONT_FIND', '缺少配置：domain_appsystem');
			return FALSE;
		}
	
		$app_key = KEY_APP_SERVER;
		if(!$app_key){
			$this->set_flag('CONSTANTS', '缺少常量：KEY_APP_SERVER');
			return FALSE;
		}
	
		$time = time();
		$sign = md5($time.$app_key);
		$url = $domain_appsystem.'app/create_config?time='.$time.'&sign='.$sign;

		$this->CI->load->library('YL_http');
		$re = $this->CI->YL_http->get($url);
		$json_ret = json_decode($re,TRUE);
		if(isset($json_ret['code']) && $json_ret['code']==201){
			return TRUE;
		}else{
			$this->set_flag("BUILD_APP_CONFIG_ERROR", '请求app系统站点失败:'.$json_ret['msg']);
			return FALSE;
		}
	}

}