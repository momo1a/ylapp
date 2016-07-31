<?php
/**
 * 广告模型
 * 
 * @author 宁天友
 * @version 2015-5-8 9:00:35
 */
class Admin_advertisement_model extends MY_Model
{
	/**
	 * 数据表主键,如果没有默认使用"id".
	 *
	 * @var string
	 * @access protected
	 */
	protected $key = 'id';
	
	/**
	 * 数据库表名
	 * 
	 * @var string
	 */
	public static $table_name = 'common_advertisement';
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 根据ID获取
	 *
	 * @param int $id 广告id
	 */
	public function get_by_id($id)
	{
		return $this->find($id);
	}
	
	/**
	 * 检测首页楼层广告是否存在(注:只适用于“首页楼层广告”)
	 * 
	 * @param int $floor 楼层索引
	 * @param int $ad_index 广告索引
	 */
	public function get_floor_ad($floor_index)
	{
		$where = array (
			'sort' => $floor_index,
			'type' => 6,
		);
		return $this->find_by($where);
	}
	
	
}
?>