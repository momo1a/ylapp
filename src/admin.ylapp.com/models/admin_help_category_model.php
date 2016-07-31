<?php
/**
 * 帮助管理-分类表 模型(后台)
 * @author 邓元翔
 * @version 13.12.11
 */
class Admin_help_category_model extends CI_Model
{

	private $_table;	//表名
	private $_help_table;

	public function __construct(){
		parent::__construct();
		$this->_table = 'help_category';
		$this->_help_table = 'help';
	}

	/**
	 * 添加分类
	 * @param string $name 分类名称
	 * @param int $type 用户类型：1买家、2商家
	 * @param int $pid 上级编号（默认0-根类）
	 * @param double $sort 优先级：小优先（默认1）
	 * @return bool $bool 执行结果(TURE|FALSE)
	 */
	public function add($name ,$type, $pid=0, $sort){
		$sort = $sort>1 ? $sort : 1;

		$data = array(
			'name' => $name,
			'type' => intval($type),
			'pid' => intval($pid),
			'sort' => $sort,
			'dateline' => time(),
			'state' => 1
		);
		$bool = $this->db->insert($this->_table, $data);

		return $bool;
	}
	
	/**
	 * 根据 pid 获取所有记录
	 * @param int $type 用户类型:1买家、2商家
	 * @param int $pid 上一级id(默认0)
	 * @param string $sort_str 要排序的字段，如：'age ASC'、'age ASC,id DESC'
	 * @return array $rows 返回上一级分类 结果集
	 */
	public function get_by_pid($type=1, $pid=0, $sort_str='sort ASC,dateline ASC'){
		//定义where参数
		 $data = array(
		 	'type' => $type,
		 	'pid' => $pid
		 );
		$query = $this->db->order_by($sort_str)->get_where($this->_table, $data);
		$rows = $query->result_array();
		
		return $rows;
	}

	/**
	 * 根据 id 获取一条记录
	 * @param int $id 分类id
	 * @return array $row 返回 结果集
	 */
	public function get_by_id($id){
		$query = $this->db->from($this->_table)->where('id', $id)->get();
		$row = $query->row_array();

		return $row;
	}
	
	/**
	 * 返回所有记录
	 * @param int $type 用户类型:1买家、2商家
	 * @return array $rows 返回 结果集
	 */
	public function get_all($type){
		$query = $this->db->from($this->_table)->where('type', $type)->get();
		$rows = $query->result_array();

		return $rows;
	}

	/**
	 * 编辑分类
	 * @param int $id 编号
	 * @param string $name 分类名称
	 * @param double $sort 优先级：小优先（默认1）
	 * @return bool $bool 执行结果(TURE|FALSE)
	 */
	public function edit($id, $name, $sort){
		$sort = $sort>1 ? $sort : 1;

		$data = array(
			'name' => $name,
			'sort' => $sort
		);
		$bool = $this->db->where('id', $id)->update($this->_table, $data); 

		return $bool;
	}

	/**
	 * 删除分类
	 * @param int $id 编号
	 * @return bool $bool 执行结果(TURE|FALSE)
	 */
	public function delete($id){
		$bool = $this->db->where('id', $id)->delete($this->_table); 

		return $bool;
	}
	
	/**
	 * 屏蔽帮助类型
	 * @param int $id:帮助类型id
	 * @param int $state:状态
	 * @return bool
	 * @author 杜嘉杰
	 * @version 2014-10-22
	 */
	public function block($id, $state){
		// 获取类目
		$category = $this->db->select('pid')->from($this->_table)->where('id',$id)->get()->row_array();
		if( isset($category['pid']) == FALSE){
			return FALSE;
		}
		
		$this->db->trans_begin();//开始事务
		if($category['pid']==0){
			// 主类目
			$update_help = array('parent_state'=> $state);
			$this->db->set($update_help)->where('pid',$id)->update($this->_help_table);
		}else{
			// 子类目
			$update_help = array('child_state'=> $state);
			$this->db->set($update_help)->where('cid',$id)->update($this->_help_table);;
		}
		
		// 更新类目状态
		$this->db->set('state', $state)->where('id',$id)->update($this->_table);
		
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return FALSE;
		}else{
			$this->db->trans_commit();
			return TRUE;
		}
	}

}