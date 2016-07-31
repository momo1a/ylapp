<?php
/**
 * 帮助管理-帮助列表 模型(后台)
 * @author 邓元翔
 * @version 13.12.16
 */
class Admin_help_model extends CI_Model
{

	private $_table;	//表名

	public function __construct(){
		parent::__construct();
		$this->_table = 'help';
	}

	/**
	 * 添加帮助
	 * @param array $data 数据
	 * @return int $hid 帮助id
	 */
	public function add($data){
		$data['parent_state'] = 1;
		$data['child_state'] = 1;
		
		$bool = $this->db->insert($this->_table, $data);
		$hid = $bool ? $this->db->insert_id() : 0;
		return $hid;
	}
	
	/**
	 * 根据用户类型返回记录
	 * @param number $type 用户类型
	 * @param number $size 抓取记录数
	 * @param number $star 起始记录下标
	 */
	public function get_by_type($type, $limit=30, $offset=0){
		$query = $this->db->order_by('state asc,dateline desc')->get_where($this->_table, array('type'=>$type), $limit, $offset);
		$rows = $query->result_array();
		return $rows;
	}
	
	/**
	 * 返回列表记录数
	 */
	public function list_count($type, $pid=0, $cid=0){
		$data = array('type' => $type);
		if($pid >= 1){ $data['pid'] = $pid; }
		if($cid > $pid){ $data['cid'] = $cid; }
		
		return $this->db->select('id')->get_where($this->_table, $data)->num_rows();
	}
	
	/**
	 * 返回已推送的题记录数
	 */
	public function question_count($type, $push=1){
		$data = array(
			'type' => $type,
			'push' => $push
		);
		
		return $this->db->get_where($this->_table, $data)->result_array();
	}
	
	/**
	 * 搜索，根据用户类型、主类、子类、标题
	 * @param number $type
	 * @param number $pid
	 * @param unknown $cid
	 * @param string $search_title
	 * @param number $limit
	 * @param number $offset
	 */
	public function search($type, $pid=0, $cid, $search_title='', $limit=30, $offset=0){
		$data = array('type' => $type);
		if($pid >= 1){ $data['pid'] = $pid; }
		if($cid > $pid){ $data['cid'] = $cid; }
		
		if ($search_title === ''){
			$query = $this->db->order_by('state asc,dateline desc')->get_where($this->_table, $data, $limit, $offset);
		}else{
			$query = $this->db->like('title',$search_title)->order_by('state asc,dateline desc')->get_where($this->_table, $data, $limit, $offset);
		}
		return $query->result_array();
	}
	
	/**
	 * 屏蔽帮助信息
	 * @param string $ids 要屏蔽的记录ID
	 * @param number $state 请求状态：0屏蔽，1显示
	 */
	public function block_change($ids, $state){
		if(is_string($ids)){
			$ids = explode(',', $ids);
		}
		if(!is_array($ids) OR !count($ids)){
			return false;
		}
		$this->db->where_in('id', $ids);
		$this->db->set('state', $state);
		return $this->db->update($this->_table);
	}
	
	/**
	 * 推送
	 * @param string $id 推送记录的ID
	 * @param number $push 推送状态：0未推，1已推
	 */
	public function push($id, $push){
		$this->db->where('id', $id);
		return $this->db->update($this->_table, array('push'=>$push));
	}
	
	/**
	 * 批量撤销推送
	 * @param string $ids 撤销推送记录的ID
	 * @param number $push 推送状态：0未推，1已推
	 */
	public function cancel_push($ids, $push=0){
		if(is_string($ids)){
			$ids = explode(',', $ids);
		}
		if(!is_array($ids) || !count($ids)){
			return false;
		}
		return $this->db->where_in('id',$ids)->update($this->_table, array('push'=>$push));
	}
	
	/**
	 * 编辑排序号
	 * @param string $ids
	 * @param string $sorts
	 * @return boolean|Ambigous <boolean, unknown>
	 */
	public function edit_sort($ids, $sorts){
		if(is_string($ids) && is_string($sorts)){
			$ids = explode(',', $ids);
			$sorts = explode(',', $sorts);
		}
		if(!is_array($ids) || !count($ids) || !is_array($sorts) || !count($sorts)){
			return false;
		}
		
		$rs = false;
		$this->db->trans_start();
		for ($i=0;$i<count($ids);$i++){
			$flag = false;
			$flag = $this->db->where('id',$ids[$i])->update($this->_table, array('sort'=>$sorts[$i], 'up_dateline'=>time()));
			$rs = $rs===$flag ? $rs : $flag;
		}
		$this->db->trans_complete();
		
		return $rs;
	}

	/**
	 * 编辑字体颜色
	 * @param string $ids 逗号连接的id字串
	 * @param string $title_color
	 * @return boolean|Ambigous <boolean, unknown>
	 */
	public function update_font_color_by_id($ids, $title_color=''){
		if( is_string($ids) ){
			$ids = explode(',', $ids);
		}
		if( !is_array($ids) || !count($ids) ){
			return false;
		}
		
		$rs = false;
		$this->db->trans_start();	//开启事物
		for ($i=0;$i<count($ids);$i++){
			$flag = false;
			$flag = $this->db->where('id',$ids[$i])->update($this->_table, array('title_color'=>$title_color, 'up_dateline'=>time()));
			$rs = $rs===$flag ? $rs : $flag;
		}
		$this->db->trans_complete();	//提交事物
		
		return $rs;
	}
	
	/**
	 * 编辑字体粗细
	 * @param string $ids 逗号连接的id字串
	 * @param string $title_font 字体宽度(默认bold)
	 * @return boolean|Ambigous <boolean, unknown>
	 */
	public function update_font_weight_by_id($ids, $title_font='bold'){
		if( is_string($ids) ){
			$query = $this->db->query("SELECT `id` FROM `shs_help` t1 WHERE t1.`id` IN($ids) AND t1.`title_font`='bold'"); //返回选中项中是粗体的id
			$id_arr = $query->result_array();
			
			foreach ($id_arr as $key => $value) {
				$id_arr[$key] = $value['id'];
			}

			$ids = explode(',', $ids);
		}
		
		if( !is_array($ids) || !count($ids) ){
			return false;
		}
		
		$rs = false;
		$this->db->trans_start();	//开启事物

		for ($i=0;$i<count($ids);$i++){
			$flag = false;
			//判断遍历中的id，加粗的记录则去掉加粗，反之
			if ( in_array($ids[$i], $id_arr ) ) {
				$flag = $this->db->where('id',$ids[$i])->update($this->_table, array('title_font'=>'', 'up_dateline'=>time()));
				$rs = $rs===$flag ? $rs : $flag;
			}else{
				$flag = $this->db->where('id',$ids[$i])->update($this->_table, array('title_font'=>$title_font, 'up_dateline'=>time()));
				$rs = $rs===$flag ? $rs : $flag;
			}
		}
		$this->db->trans_complete();	//提交事物
		
		return $rs;
	}

	/**
	 * 根据id返回记录
	 * @param number $id help表id
	 */
	public function get_by_id($id){
		$query = $this->db->get_where($this->_table, array('id'=>$id));
		return $query->row_array();
	}
	
	/**
	 * 根据子类id查询记录
	 * @param number $cid
	 */
	public function get_by_cid($cid){
		$query = $this->db->get_where($this->_table, array('cid'=>$cid));
		return $query->result_array();
	}
	
	/**
	 * 添加分类
	 * @param number $id 数据
	 * @param array $data 数据
	 * @return bool $bool 执行结果(TURE|FALSE)
	 */
	public function edit($id, $data){
		$this->db->where('id', $id);
		return $this->db->update($this->_table, $data);
	}
	
	/**
	 * 删除记录
	 * @param number $id 编号
	 * @return bool 执行结果(TURE|FALSE)
	 */
	public function delete($id){
		return $this->db->where('id', $id)->delete($this->_table); 
	}
	
	/**
	 * 批量删除记录
	 * @param number $ids 多个编号组成的字串
	 * @return bool 执行结果(TURE|FALSE)
	 */
	public function delete_by_ids($ids){
		if(is_string($ids)){
			$ids = explode(',', $ids);
		}
		if(!is_array($ids) OR !count($ids)){
			return false;
		}
		
		return $this->db->or_where_in('id', $ids)->delete($this->_table);
	}
	
	/**
	 * 根据用户类型返回已推送记录
	 * @param number $type
	 * @param number $limit 抓取长度
	 * @param number $type 起始长度
	 */
	public function get_push_by_type($type=1, $limit=30, $offset=0){
		$data = array(
			'type'=>$type,
			'push'=>1
		);
		$query = $this->db->order_by('sort asc,dateline desc')->get_where($this->_table, $data);
		$query = $this->db->query("
				SELECT * 
				FROM `shs_{$this->_table}` 
				WHERE `type`={$type} AND `push`=1 
				ORDER BY `sort` ASC, `dateline` DESC 
				LIMIT {$offset},{$limit}
				");
		return $query->result_array();
	}
	
	/**
	 * 根据用户类型返回推送到常见问题的记录数
	 * @param int $type 用户类型：1买家、2商家
	 */
	public function get_push_count_by_type($type){
		$query = $this->db->query("
				SELECT COUNT(`id`) AS count 
				FROM `shs_{$this->_table}` 
				WHERE `type`={$type} AND `push`=1 
				");
		$rs = $query->row_array();
		return $rs['count'];
	}
	
	/**
	 *  保存数据(插入/更新通用)
	 * @param array|object $data 图片数据
	 * @return boolean|Ambigous <object, boolean, string, mixed, unknown>
	 */
	public function save($data){
		if(!is_array($data) && !is_object($data)){
			return FALSE;
		}
		//var_dump($data); exit;
		$id = '';
		$fields = $this->db->list_fields($this->_table);
		foreach ($data as $k=>$v){
			if(in_array($k, $fields) && 'id' != $k){
				$this->db->set($k, $v);
			} elseif ('id' == $k){
				$id = $v;
			}
		}
		if($id){
			$rs = $this->db->where('id', $id)->update($this->_table);
		}else{
			$rs = $this->db->insert($this->_table);
		}
		return $rs;
	}

}