<?php
/**
 * 清除缓存日志
 *
 * @author 潘宏升
 * @version 2015-8-3 14:37:35
 */
class cache_catogery_model extends MY_Model
{
	/**
	 * 数据表主键,如果没有默认使用"id".
	 *
	 * @var string
	 * @access protected
	 */
	protected $key = 'cid';

	/**
	 * 数据库表名
	 *
	 * @var string
	 */
	public static $table_name = 'cache_catogery';

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
	 * @param $cat_name通过栏目名称获取id
	 */
	public function get_id_by_cat_name($cat_name)
	{
		$data = $this->select($this->key)->find_by(array('cat_name'=>$cat_name));
		return isset($data[$this->key])?$data[$this->key]:'';
	}
	/**
	 * @return mixed
	 * 获取缓存类目
	 */
	public function get_cat_name($limit,$offset=0){
		$this->order_by('cid desc')->limit( $limit, $offset);
		$query = $this->db->get(self::$table_name);
		return $query->result_array();
	}

	/**
	 * @return mixed
	 * 获取缓存类目
	 */
	public function add($cat_name)
	{
		$result = $this->find_by(array('cat_name' => $cat_name));
		if ($result) {
			return $result['cid'];
		} else {
			$data = array(
				'cat_name' => $cat_name,
				'addtime' => time()
			);
			$this->return_insert_id();
			return $this->insert($data);
		}
	}

	/**
	 * 获取类目的下拉内容
	 * @return array
	 */
	public function get_catogery_select()
	{
		$this->load->helper("core_helper");
		$ret = array();
		if ($data = $this->select('cid,cat_name')->find_all())
			foreach ($data as $val) {
				$ret[$val['cid']] = cutstr($val['cat_name'],20);
			}
		return $ret;
	}
}

?>