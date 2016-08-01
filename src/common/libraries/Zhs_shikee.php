<?php 

/**
 * 试客联盟接口
 *
 */
class YL_shikee
{
	/**
	 * 联盟api地址
	 * @var string
	 */
	protected $server;
	
	/**
	 * 联盟定义众划算站点的数字
	 * @var int
	 */
	protected $site;
	
	/**
	 * 试客联盟秘钥
	 * @var string
	 */
	protected $key;
	
	protected $CI;
	
	public function __construct()
	{
		$this->CI = &get_instance();
		$this->init();
	}
	
	/**
	 * 初始化
	 * 
	 * @author 杜嘉杰
	 * @version 2015年9月22日  下午4:48:54
	 *
	 */
	private function init() {
		$this->site =$this->CI->config->item('api_shikee_site') or show_error('缺少配置：api_shikee_site');
		$this->server =$this->CI->config->item('api_shikee') or show_error('缺少配置：api_shikee');
		$this->key = KEY_SHIKEE or show_error('缺少配置：KEY_SHIKEE');
	}
	
	/**
	 * 处理失败返回json数据
	 *
	 * @param mixed $data 返回的数据。默认：NULL
	 * @param string $callback  跨域时输出的方法名
	 */
	public function failure($data = NULL, $callback = 'callback') {
	    $ret = array('success' => FALSE);
	    $data!==NULL && ($ret['data'] = $data);
	    $json_str = json_encode($ret);
	    if(isset($_GET[$callback])){
	        $json_str = $_GET[$callback].'('.$json_str.');';
	    }
	    die($json_str);
	}
	
	/**
	 * 验证单号存在
	 * @param string $trade_no 订单号
	 *
	 * @author 韦贵华
	 * @version 2015-10-26 17:02:25
	 *
	 *	返回状态
	 *  ERROR_MISSING_PARAM：缺失参数
	 *  ERROR_MISSING_CONFIG：缺少配置项：KEY_YL
	 *  ERROR_SIGN_ERROR：签名错误
	 *  SUCCESS_EXISTS：单号已存在
	 *  SUCCESS_NOT_EXISTS：单号不存在
	 *  ERROR_UNKNOWN：未知错误
	 */
	public function order_exist_trade_no($trade_no)
	{
		// 整理数据
		$sign = md5(md5($this->site . $trade_no) . $this->key);
		$data = array(
			'site' => $this->site,
			'trade_no' => $trade_no,
			'sign' => $sign
		);

		// 请求
		$this->CI->load->library('YL_http');
		$url = $this->server . 'join/exist_trade_no';
		$this->CI->YL_http->timeout = 5;
		$re_str = $this->CI->YL_http->post($url, $data);
		
		// 处理返回结果
		/*	
		 	试客联盟返回的字段：
				MISSING_PARAM   缺失参数
				MISSING_CONFIG  缺少配置项：KEY_YL
				SIGN_ERROR      签名错误
				-1订单号不存在，1订单号存在
		*/
		$json_re = json_decode($re_str, TRUE);  

		if( isset($json_re['success']) && ($json_re['success'] == FALSE)
			&& isset($json_re['code']) && !in_array($json_re['code'], array(-1,1)) )
		{
			$err_map = array(
				'MISSING_PARAM' => array('code'=>'ERROR_MISSING_PARAM', 'msg'=>' 缺失参数'),
				'MISSING_CONFIG' => array('code'=>'ERROR_MISSING_CONFIG', 'msg'=>' 缺少配置项：KEY_YL'),
				'SIGN_ERROR' => array('code'=>'ERROR_SIGN_ERROR', 'msg'=>' 签名错误')
			);

			$msg = '';
			if(isset($err_map[$json_re['code']]))
			{
				$msg =  $err_map[$json_re['code']]['msg'];
			}
			else
			{
				$msg = ' 数据异常,请重新填写订单号!';
			}
			// 写入日志
			save_log('请求联盟单号重复的接口异常：'.$msg .'，'. $re_str , 'false' , 'INFO' , 'trade_no');
			$this->failure($msg);
		}
		else if($json_re['code']==1)
		{
			$msg = ' 单号已在其他平台存在,请重新填写!';
			// 写入日志
			save_log('请求联盟单号重复：单号已存在 '. $re_str , 'true' , 'INFO' , 'trade_no');
			$this->failure($msg);
		}
		else if($json_re['code']==-1)
		{
			$msg = '单号在其他平台不存在,可以填写!';
			// 写入日志
			save_log('请求联盟单号重复：单号不存在 '. $re_str , 'true' , 'INFO' , 'trade_no');
		}
		else
		{
		    $arr = $this->CI->YL_http->get_info();
		    $log_msg = '请求地址：'.$arr['url'].'  错误代码：[http_code :  '.$arr['http_code'].' ]';
			$msg = ' 数据异常,请重新填写订单号!';
			//写入日志
			save_log('请求联盟单号重复的接口异常：出现未知错误，请检查请求地址或网络连接。 '.$log_msg, 'false' , 'INFO' , 'trade_no');
			$this->failure($msg);
		}
	}
}
