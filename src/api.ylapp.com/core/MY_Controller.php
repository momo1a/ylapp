<?php

require COMPATH."core/ZHS_Controller.php";

class MY_Controller extends CI_Controller
{
	 function __construct()
	{
		parent::__construct();
	}
	/**
	 * 处理成功返回json数据
	 * @param int $code:z状态编码
	 * @param string $msg:描述
	 * @param string $data:数据
	 * @author 杜嘉杰
	 * @version 2014-6-19
	 */
	protected function go_back($code , $msg = '', $data = NULL, $output = TRUE) {
		$code = intval($code);
		$ret = array (
				'code' => $code,
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
}