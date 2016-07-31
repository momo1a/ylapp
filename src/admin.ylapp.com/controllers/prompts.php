<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

/**
 * 发布活动—温馨提示控制器类
 */
class Prompts extends MY_Controller {
	/**
	 * 发布活动—温馨提示列表
	 */
	public function index() {
		// 活动类型（选项卡类型）
		$goods_type = intval($this->uri->segment(3));
		$state = intval($this->uri->segment(4));
		$data['state'] = $state;
        if($state){
            $data ['prompts_list'] = $this->db->select ( '*' )->from ( 'goods_prompts' ) ->where(array('goods_type'=>$goods_type,'state'=>$state)) ->order_by('sort ASC, dateline ASC')->get ()->result_array ();
        }else{
            $data ['prompts_list'] = $this->db->select ( '*' )->from ( 'goods_prompts' ) ->where(array('goods_type'=>$goods_type)) ->order_by('sort ASC, dateline ASC')->get ()->result_array ();
        }
		$data ['goods_type'] = $goods_type;
		$this->load->view ( 'prompts/index', $data );
	}
	
	public function add() {
		$goods_type = intval ( $this->get_post ( 'goods_type' ) );
		if ($this->get_post ( 'is_post' )) {
			$type = intval ( $this->get_post ( 'type' ) );
			$title = trim ( $this->get_post ( 'title' ) );
			$prompts = trim ( $this->get_post ( 'prompts' ) );
			if(!$title){
				$this->error('请填写标题');
			}
			
			$sort_max  = $this->db->select_max('sort') ->from ( 'goods_prompts' ) ->where(array('goods_type'=>$goods_type))->get()->row_array();
			$sort_max = isset($sort_max['sort']) ? $sort_max['sort'] : 0;
			
			$user = get_user ();
			$data = array (
					'title' => $title,
					'type' => $type,
					'prompts' => $prompts,
					'state' => 1,
					'uid' => $user ['id'],
					'uname' => $user ['name'],
					'dateline' => time () ,
					'goods_type' => $goods_type,
					'sort' => $sort_max + 1
			);
			$this->db->insert ( 'goods_prompts', $data );
			if ($this->db->insert_id ()) {
				$this->success ( '添加成功' );
			} else {
				$this->error ( '添加失败，请重试！' );
			}
		} else {
			$this->load->view ( 'prompts/add', get_defined_vars() );
		}
	}
	public function hide() {
		$id = $this->get_post ( 'id' );
		$state = $this->get_post ( 'state' );
		if ($id <= 0) {
			$this->error ( '找不到该记录！' );
		}
		if ($state == 1) {
			$state = 2;
		} elseif ($state == 2) {
			$state = 1;
		} else {
			$this->error ( '出错了！' );
		}
		$this->db->update ( 'goods_prompts', array (
				'state' => $state 
		), array (
				'id' => $id 
		) );
		$this->success ( '操作成功！' );
	}
	
	/**
	 * 排序
	 * 
	 * @author 杜嘉杰
	 * @version 2014-12-4
	 */
	public function set_sort(){
		// 活动类型（选项卡类型）
		$goods_type = $this->get_post('goods_type', 0);
		
		// 排序值
		$sorts = trim($this->get_post ( 'sorts' , ''));
		$sorts = explode(';', $sorts);
		
		$this->db->trans_begin();
		foreach ($sorts as $v){
			if ($v=='') {
				continue;
			}
			$item = explode('_', $v);
			$id = intval($item[0]);
			$sort = intval($item[1]);
			if($id>0){
				$this->db->set('sort', $sort)->where('id', $id)->update('goods_prompts');
			}
		}
		$this->db->trans_complete();
		$this->log('类目管理列表排序成功');
		$this->success('排序成功');
		
	}
}
