<?php
/**
 * 管理员APP管理banner广告和快捷入口操作类
 */
class App_advertisement_model extends CI_Model
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
		$this->_table = 'app_advertisement';
	}
	
	/**
	 * 获取App广告列表
	 * @param string $title   标题
	 * @param number $stime  开始时间
	 * @param number $etime  结束时间
	 * @param number $differ  区别 1 为banner 广告 2为快捷入口
	 */
	public function get_search_adv($title = '', $stime = 0, $etime = 0, $differ = 0,$limit = 10, $offset = 0) {
				if ($differ == 0) {
					return;
				}
				if($title !==''){
					$this->db->like( 'title', $title );
				}
				if ($stime > 0 && $etime > 0) {
					$this->db->where ( 'dateline >', $stime )->where ( 'dateline <', $etime );
				}
				if ($differ > 0) {
					$this->db->where ( 'differ', $differ );
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
		 * 获取App广告列表总数
		 * @param string $title   标题
		 * @param number $stime  开始时间
		 * @param number $etime  结束时间
		 * @param number $differ  区别 1 为banner 广告 2为快捷入口
		 */
		public function get_search_adv_count($title = '', $stime = 0, $etime = 0, $differ = 0) {
			if ($differ == 0) {
				return;
			}
			if($title !==''){
				$this->db->like( 'title', $title );
			}
			if ($stime > 0 && $etime > 0) {
				$this->db->where ( 'dateline >', $stime )->where ( 'dateline <', $etime );
			}
			if ($differ > 0) {
				$this->db->where ( 'differ', $differ );
			}
			$this->db->select('*');
			$this->db->from($this->_table);
			return $this->db->count_all_results();
		}
		
		/**
		 * 获取单个广告详情
		 * @param number $id
		 */
		public function get_adv_data($id=0){
			
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
		public function is_check($title='',$differ=0,$id=0){
			if($title!==''){
				$this->db->where('title',$title);
			}
			if($differ >0){
				$this->db->where('differ',$differ);
			}
			if($id >0){
				$this->db->where_not_in('id',$id);
			}
			$this->db->from($this->_table);
			return $this->db->get()->num_rows();
		}
		
		/**
		 * 获取启动页广告
		 * @param int $size
		 * @param int $offset
		 * @return array
		 */
		public function get_start_advertisement($size = 10,$offset = 0)
		{
			$this->db->select('*');
			$this->db->from($this->_table);
			$this->db->where('differ',3);
			$data['list'] = $this->db->limit($size,$offset)->order_by('dateline desc')->get()->result_array();
			$this->db->last_query();
			//总条数
			$this->db->select('*');
			$this->db->from($this->_table);
			$this->db->where('differ',3);
			$data['count']=$this->db->get()->num_rows();
			
			return $data;
		} 
}
?>