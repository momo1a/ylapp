<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 接口适配器接口
 *
 * 要求每个接口适配器必须实现这个接口定义的方法
 *
 * @author 宁天友
 * @version 2015年7月16日14:56:43
 * 
 */
interface ZHS_api_driver
{

	/**
	 * 检测签名，验证请求是否合法
	 * @return bool
	 */
	public function check_sign();
	
	/**
	 * 检测参数是否正确
	 * @param array $params 请求参数数组
	 * @return bool
	 */
	public function check_param($params);
	
	/**
	 * 生成接口sign
	 * @return string
	 */
	public function sign();
	
	/**
	 * 接口版本
	 * @return string
	 */
	public function version();
	
	/**
	 * 是否开放
	 *
	 * @return boolean
	 */
	public function is_open();
}