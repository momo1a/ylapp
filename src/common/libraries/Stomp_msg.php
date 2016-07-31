<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 * Stomp协议消息发送类
 * @author 关小龙
 * @version 2015.05.29
 * @link    http://www.zhonghuasuan.com/
 * 
 */
class Stomp_msg
{
	private $_stomp = NULL; //消息对象
	private $_destination = NULL; //消息发送到目的地
	
	
	/**
	 * 发送Stomp消息
	 * 
	 * @param string $destination 消息发送到目的地名
	 * @param array $arr_message 发送的消息内容数组
	 * @return boolean
	 */
	public function send($destination,$arr_message)
	{
		if( ! self::_setup_stomp($destination) )
		{
			return FALSE;
		}
		//发送消息
		$message = json_encode($arr_message);
		if ( ! $this->_stomp->send( $this->_destination, $message ) )
		{
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * 启动Stomp连接并指定消息发送到目的地
	 * 
	 * @param string $destination 消息发送到目的地名
	 * @return boolean
	 */
	private function _setup_stomp($destination)
	{
		// 加载配置文件.
		$CI =& get_instance();
		if ($CI->config->load('stomp', TRUE, TRUE)) {
			$config = $CI->config->item('stomp');
			// Stomp:destination,指定消息发送到目的地
			if( isset($config[$destination]) ){
				$this->_destination = $config[$destination];
			}else{
				log_message('error', 'stomp缺少消息发送到目的地名的配置:'.$destination);
				return FALSE;
			}
			
			try {
				$this->_stomp = new Stomp($config['broker'], $config['name'], $config['password']);
				return TRUE;
			} catch(StompException $e) {
				log_message('error', 'Stomp开启失败:'.$e->getMessage());
				return FALSE;
			}
		}else {
			log_message('error', 'Stomp配置文件没有找到.');
			return FALSE;
		}
	}
	
	/**
	 * 获取发送消息的错误信息
	 */
	public function error()
	{
		if( $this->_stomp instanceof Stomp ){
			return $this->_stomp->error();
		}
		return 'Stomp开启失败';
	}
	
}//end class