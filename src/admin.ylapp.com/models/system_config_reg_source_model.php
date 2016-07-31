<?php

class System_config_reg_source_model extends MY_Model{
	/**
	 * 使用UID作为主键
	 * @var string
	 */
	protected $key = 'id';
	
	/**
	 * 系统 - 配置 - 注册来源表(不带前缀)
	 * @var string
	 */
	public static $table_name = 'system_config_reg_source';
	
	protected  $skip_validation = FALSE;
	
	/**
	 * 判断来源名称存在
	 * @param string $name
	 * @param int $id 
	 *
	 * @author 杜嘉杰
	 * @version 2015年10月13日  下午2:22:47
	 *
	 */
	public function exists_name($name,$id=null)
	{
		$where = array(
			'name' => $name
		);
		
		if($id){
			$where['id <>'] = $id;
		}
		return $this->where($where)->find_all();
	}
	
	/**
	 * 判断url存在
	 * @param unknown $url
	 * @param int $id 
	 *
	 * @author 杜嘉杰
	 * @version 2015年10月13日  下午5:48:28
	 *
	 */
	public function exists_url($url, $id=null)
	{
		$where = array(
			'url' => $url
		);
		
		if($id){
			$where['id <>'] = $id;
		}
		
		return $this->where($where)->find_all();
	}
	
	
	
}