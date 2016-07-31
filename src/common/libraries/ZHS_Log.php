<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 日志
 *
 * 修改CI框架的日志路径,将日志存放地址统一定位到src同级的logs目录下
 *
 * @author "韦明磊<nicolaslei@163.com>"
 *
 */
class ZHS_Log extends CI_Log
{
	public function __construct()
	{
		$config =& get_config();
		
		// 统一定义日志存放路径
		$this->_log_path = COMPATH . '../../logs/';
		
		if ( is_dir($this->_log_path))
		{
			// 站点文件夹
			$this->_log_path .= $_SERVER['HTTP_HOST'].'/';
	
			if ( ! is_dir($this->_log_path))
			{
				mkdir($this->_log_path, 0777);
			}
		}
		else
		{
			$this->_log_path = ($config['log_path'] != '') ? $config['log_path'] : APPPATH.'logs/';
			if ( ! is_dir($this->_log_path) OR ! is_really_writable($this->_log_path))
			{
				$this->_enabled = FALSE;
			}
		}

		if (is_numeric($config['log_threshold']))
		{
			$this->_threshold = $config['log_threshold'];
		}
		
		// 强制开启最低error写入级别
		$this->_threshold = $this->_threshold?:1;

		if ($config['log_date_format'] != '')
		{
			$this->_date_fmt = $config['log_date_format'];
		}
	}
	
	/**
	 * Write Log File
	 * 
	 * 重写write_log方法
	 *
	 * Generally this function will be called using the global log_message() function
	 *
	 * @param	string	the error level
	 * @param	string	the error message
	 * @param	bool	whether the error is a native PHP error
	 * @return	bool
	 */
	public function write_log($level = 'error', $msg, $php_error = FALSE)
	{
		if ($this->_enabled === FALSE)
		{
			return FALSE;
		}
	
		$level = strtoupper($level);
	
		if ( ! isset($this->_levels[$level]) OR ($this->_levels[$level] > $this->_threshold))
		{
			return FALSE;
		}
		//日志内容
		$log_data =$this->log_content($level, $msg);
		//写入日志
		return $this->write_file($log_data);
		
	}
	
	/**
	 * 获取收集到的数据
	 *
	 * @param string $level
	 * @return string 
	 *
	 * @author 杜嘉杰
	 * @version 2015年7月29日 上午11:32:26
	 */
	protected function log_content($level, $msg,$other=NULL){
	    $message ='';
	    // 日志内容
	    $message .= $level.' '.(($level == 'INFO') ? ' -' : '-').' '.date($this->_date_fmt)."\n";
	    
	    if($other){
	        if(is_array($other)){
	            $other_str = print_r($other,FALSE);
	            $message .= 'Other_msg：'.mb_substr($other_str, 4)."\n";
	        }else if(in_array(strtolower($other), array('true','false'))){
	            $message .= 'Success : '.$other."\n";
	        }else {
	            $message .= 'Other_msg：'.$other."\n";
	        }   
	    }
	    $message .= 'Request_date：'.date($this->_date_fmt, TIMESTAMP)."\n";
	    $message .= 'Return_msg : '.$msg."\n"; 
	    $message .= 'Request-Url：'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']."\n";
	    $message .= 'Referer：'.(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'')."\n";
	    $message .= 'Request-Method：'.$_SERVER['REQUEST_METHOD']."\n";
	    
	    $cookie = '';
	    if (!empty($_COOKIE))
	    {
	        foreach ($_COOKIE as $key=>$val)
	        {
	            $cookie .= "\t{$key}=>{$val}\n";
	        }
	    }
	    $message .= "Cookie：{\n{$cookie}}\n";
	    
	    if (! empty($_POST))
	    {
	        $post = '';
	        foreach ($_POST as $key=>$val)
	        {
	            $post .= "\t{$key}=>{$val}\n";
	        }
	        $message .= "Request-Data：{\n{$post}}\n";
	    }
	    
	    if (class_exists('AuthUser', FALSE) && AuthUser::is_logged_in())
	    {
	        $message .= 'User: ID['.AuthUser::id().'],NAME['.AuthUser::account().']'."\n";
	    }
	    
	    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
	    $message .= 'User-Agent: '. $user_agent ."\n";
	    $message .= 'IP-Adddress: '.$this->ip_address()."\n";
	    $message .= "-----------------------------------------------------\n";
	    $message .= "-----------------------------------------------------\n";
	    // 日志内容 end
	    
	    return $message;
	}
	
	/**
	 * 写入文件
	 *
	 * @param string $content 文件内容
	 * @param string $dir 站点目录里面的子文件夹
	 * @return boolean
	 *
	 * @author 杜嘉杰
	 * @version 2015年7月30日 上午11:08:46
	 */
	protected function write_file($content, $dir=NULL){
	    
	    // 创建目录
	    $log_dir = NULL;
	    if($dir)
	    {
	        $log_dir = $this->_log_path . $dir . '/';
	    }else
	    {
	        $log_dir = $this->_log_path;
	    }
	    
	    if ( ! is_dir($log_dir))
	    {
	        mkdir($log_dir, 0777, TRUE);
	    }
	    
	    // 拼接文件名
	    $filepath = $log_dir . 'log-'.date('Y-m-d').'.php';
	    
	    // 文件内容
	    $message  = '';
	    
	    if ( ! file_exists($filepath))
	    {
	        $message .= "<"."?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?".">\n\n";
	    }
	    
	    if ( ! $fp = @fopen($filepath, FOPEN_WRITE_CREATE))
	    {
	        return FALSE;
	    }
	    // 内容拼接
	    $message .= $content;
	    
	    flock($fp, LOCK_EX);
	    fwrite($fp, $message);
	    flock($fp, LOCK_UN);
	    fclose($fp);
	    
	    @chmod($filepath, FILE_WRITE_MODE);
	    return TRUE;
	}
	
	/**
	 * 获取IP地址
	 * @return Ambigous <NULL, string, unknown>
	 */
	function ip_address()
	{
		if (class_exists('CI_Controller', FALSE))
		{
			$CI =& get_instance();
			$ip = $CI->input->ip_address();
		}
		elseif ($_SERVER['REMOTE_ADDR'])
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		elseif (getenv("REMOTE_ADDR"))
		{
			$ip = getenv("REMOTE_ADDR");
		}
		elseif (getenv("HTTP_CLIENT_IP"))
		{
			$ip = getenv("HTTP_CLIENT_IP");
		}
		else
		{
			$ip = "unknown";
		}
		return $ip;
	}
	
	/**
	 * 保存日志
	 *
	 * @param string $msg 描述
	 * @param array $other 其它信息
	 * @param string $level 类型
	 * @param string $path 指定子目录，如果想放在默认目录传入NULL
	 * @return boolean
	 *
	 * @author 杜嘉杰
	 * @version 2015年7月30日 上午11:24:48
	 */
	public function save_log($msg, $other=NULL, $level='INFO' , $path=NULL){
	    if ($this->_enabled === FALSE)
	    {
	        return FALSE;
	    }
	    $level = strtoupper($level);
	    $log_data = $this->log_content($level, $msg,$other);
	    return $this->write_file($log_data,$path);
	}
	
}