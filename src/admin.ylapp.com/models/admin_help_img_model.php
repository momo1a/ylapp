<?php
/**
 * 帮助中心-帮助表 模型(前端)
 * @author 邓元翔
 * @version 13.12.23
 * @property CI_DB_active_record $db 数据库链接
 */
class Admin_help_img_model extends CI_Model
{

	private $_table;	//表名
	private $dbprefix;	//表前缀

	public function __construct(){
		parent::__construct();
		$this->_table = 'help_img';
		$this->load->database('default');
	}

	
	/**
	 * 添加
	 * @param array $data 数据(id uid hid url is_use dateline)
	 * @return $imgid 图片id
	 */
	public function add($data){
		$imgid = 0;
		if(is_array($data)){
			$bool = $this->db->insert($this->_table, $data);
			$imgid = $bool ? $this->db->insert_id() : 0;
		}
		return $imgid;
	}
	
	/**
	 * 添加
	 * @param array $imgids 图片id
	 * @param int $hid 帮助id
	 * @return bool 
	 */
	public function update($imgids, $hid){
		$this->db->where_in('id', $imgids);
		$this->db->set('hid', $hid);
		$this->db->set('is_use', 1);	//是否使用：1使用，0否
		return $this->db->update($this->_table);
	}

}