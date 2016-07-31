<?php
/**
 * 清除缓存日志
 *
 * @author 潘宏升
 * @version 2015-8-3 14:37:35
 */
class cache_clear_log_model extends MY_Model
{
	/**
	 * 数据表主键,如果没有默认使用"id".
	 * @var string
	 * @access protected
	 */
	protected $key = 'id';

	/**
	 * 数据库表名
	 *
	 * @var string
	 */
	public static $table_name = 'cache_clear_log';

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
	 * 获取缓存类目里的url
	 * @param $limit
	 * @param $offset
	 * @return mixed
	 */
	public function get_log_data($cid, $limit, $offset = 0)
	{
		$where = array('cat_name <>'=>' ');
		if ($cid) {
			$where['cache_catogery.cid'] = $cid;
		}
		$this->db->where($where);
		$this->db->join('cache_catogery', self::$table_name . '.cid=cache_catogery.cid', 'left');
		$this->order_by('id desc')->limit($limit, $offset);
		$query = $this->db->get(self::$table_name);
		return $query->result_array();
	}

	/**
	 * 获取缓存类目里的url
	 * @param $limit
	 * @param $offset
	 * @return mixed
	 */
	public function count_log_data($cid = '')
	{
		$where = array('cat_name <>'=>' ');
		if ($cid) {
			$where['cache_catogery.cid'] = $cid;
		}
		$this->db->join('cache_catogery', self::$table_name . '.cid=cache_catogery.cid', 'left');
		$this->db->where($where);
		return $this->db->count_all_results(static::$table_name);
	}
}

?>