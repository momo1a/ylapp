<?php
/**
 * 清除缓存日志
 *
 * @author 潘宏升
 * @version 2015-8-3 14:37:35
 */
class cache_clear_model extends MY_Model
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
	public static $table_name = 'cache_clear';

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
		$this->db->join('cache_catogery', self::$table_name . '.cid=cache_catogery.cid', 'left');
		$this->db->where(array('id' => $id));
		$query = $this->db->get(self::$table_name);
		return $query->row_array();
	}

	/**
	 * 通过栏目id（cid）查找地址数据
	 * @param $cid
	 * @return mixed
	 */
	public function get_by_cid($cid)
	{
		$cid_array = is_array($cid)?$cid:explode(',',rtrim($cid,','));
		$this->join('cache_catogery', self::$table_name . '.cid=cache_catogery.cid', 'left');
		$this->where(array('state'=>1));
		$this->where_in('cache_catogery.cid',$cid_array);
		$query = $this->db->get(self::$table_name);
		$data = $query->result_array();
		$rst = array();
		if($data)
			foreach($data as $val){
				$rst[$val['cid']][] = $val;
			}
		return $rst;
	}
	/**
	 * @param $data 插入数据
	 */
	public function add($data)
	{
		if (isset($data['cat_name'])) unset($data['cat_name']);
		return $this->db->insert(self::$table_name, $data);
	}

	/**
	 * 删除数据
	 * @param $id
	 * @return bool
	 */
	public function delete_data($id)
	{
		$data = $this->find($id);
		$this->db->last_query();
		if ($data) {
			$this->delete($id);
			if ($this->find_by(array('cid' => $data['cid']))) {
				return true;
			} else {
				return $data['cid'];
			}
		} else {
			return false;
		}
	}

	/**
	 * 获取缓存类目里的url
	 * @param $limit
	 * @param $offset
	 * @return mixed
	 */
	public function get_cache_data($cid, $limit, $offset = 0)
	{
		if ($cid) {
			$where = array('cache_catogery.cid' => $cid);
			$this->db->where($where);
		}
		$this->db->join('cache_catogery', self::$table_name . '.cid=cache_catogery.cid', 'left');
		$this->order_by('cache_catogery.cid desc')->limit($limit, $offset);
		$query = $this->db->get(self::$table_name);
		return $query->result_array();
	}

	/**
	 * 获取缓存类目里的url
	 * @param $limit
	 * @param $offset
	 * @return mixed
	 */
	public function count_cache_data($cid = '')
	{
		if ($cid) {
			$where = array('cid' => $cid);
			$this->db->where($where);
		}
		return $this->db->count_all_results(static::$table_name);
	}
}

?>