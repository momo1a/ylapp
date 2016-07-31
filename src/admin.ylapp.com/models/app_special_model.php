<?php
/**
 * 管理员APP管理banner广告和快捷入口操作类
 */
class App_special_model extends CI_Model
{
	/**
	 * 当前模型对应表名
	 * @var string
	 */
	private $_table;
	
	/**
	 * 构造函数
	 */
	public function __construct() {
		parent::__construct ();
		$this->_table = 'app_special';
	}
	
	/**
	 * 获取App专场列表
	 * @param string $title   标题
	 * @param number $stime  开始时间
	 * @param number $etime  结束时间
	 */
	public function get_search_special($title = '', $stime = 0, $etime = 0,$limit = 10, $offset = 0) {
				if($title !==''){
					$this->db->like( 'title', $title );
				}
				if ($stime > 0 && $etime > 0) {
					$this->db->where ( 'dateline >', $stime )->where ( 'dateline <', $etime );
				}
				if($limit){
					$this->db->limit($limit, $offset);
				}
				$this->db->select('*');
				$this->db->from($this->_table);
				$this->db->order_by('sort','DESC');
				return $this->db->get()->result_array();
		}
		/**
		 *  获取App专场列表总数
		 * @param string $title   标题
		 * @param number $stime  开始时间
		 * @param number $etime  结束时间
		 */
		public function get_search_special_count($title = '', $stime = 0, $etime = 0) {
			
			if($title !==''){
				$this->db->like( 'title', $title );
			}
			if ($stime > 0 && $etime > 0) {
				$this->db->where ( 'dateline >', $stime )->where ( 'dateline <', $etime );
			}
			$this->db->select('*');
			$this->db->from($this->_table);
			return $this->db->count_all_results();
		}
		
		/**
		 * 获取单个专场详情
		 * @param number $id
		 */
		public function get_special_data($id=0){
			
			$this->db->select('*');
			$this->db->where('id',$id);
			$this->db->from($this->_table);
			return $this->db->get()->row_array();
		}
		
		/**
		 * 判断标题是否存在
		 * @param string $title
		 * @param number $differ
		 */
		public function is_check($title='',$id=0){
			if($title!==''){
				$this->db->where('title',$title);
			}
			if($id >0){
				$this->db->where_not_in('id',$id);
			}
			$this->db->from($this->_table);
			return $this->db->get()->num_rows();
		}
}
?>