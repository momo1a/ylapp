<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends MY_Controller
{
	public $check_access = TRUE;
	public $except_methods = array('index');
	
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * 活动分类
	 */
	public function goods_category()
	{
		$this->load->model('goods_category_model');
		$cate_level1 = $this->goods_category_model->get_by_pid();
		$pid = intval($this->input->get_post('pid'));
		if(!$pid){
			$pid = $cate_level1[0]['id'];
		}
		$current_cate = $this->goods_category_model->get($pid);
		$cate_level2 = $this->goods_category_model->get_by_pid($pid);
		$this->load->view('category/goods_category', get_defined_vars());
	}
	
	/**
	 * 添加活动分类
	 */
	public function add_goods_category()
	{
		if('yes' == $this->input->get_post('dosave')){
			$this->save_goods_category();
		}
		$cate = array();
		$cate['name'] = '';
		$cate['discount'] = '';
		$cate['discount_yzcm'] = '';
		$cate['discount_mpg'] = '';
        $cate['discount_zfq'] = '';
		$cate['sort'] = '';
		$cate['pid'] = intval($this->input->get_post('pid'));
		$cate['id'] = 0;
		if($cate['pid']){
			$this->load->model('goods_category_model');
			$parent_cate = $this->goods_category_model->get($cate['pid']);
			$maxdiscount = $parent_cate['discount'];
			$maxdiscount_yzcm = $parent_cate['discount_yzcm'];
			$maxdiscount_mpg = $parent_cate['discount_mpg'];
            $maxdiscount_zfq = $parent_cate['discount_zfq'];
		}else{
			$maxdiscount = 9.9;
			$maxdiscount_yzcm = 9.9;
			$maxdiscount_mpg = 9.9;
            $maxdiscount_zfq = 9.9;
		}
		$this->load->view('category/add_goods_category', get_defined_vars());
	}
	
	/**
	 * 编辑活动分类
	 */
	public function edit_goods_category()
	{
		if('yes' == $this->input->get_post('dosave')){
			$this->save_goods_category();
		}
		$id = intval($this->input->get_post('id'));
		$this->load->model('goods_category_model');
		$cate = $this->goods_category_model->get($id);
		if($cate['pid']){
			$this->load->model('goods_category_model');
			$parent_cate = $this->goods_category_model->get($cate['pid']);
			$maxdiscount = $parent_cate['discount'];
			$maxdiscount_yzcm = $parent_cate['discount_yzcm'];
			$maxdiscount_mpg = $parent_cate['discount_mpg'];
            $maxdiscount_zfq = $parent_cate['discount_zfq'];
		}else{
			$maxdiscount = 9.9;
			$maxdiscount_yzcm = 9.9;
			$maxdiscount_mpg = 9.9;
            $maxdiscount_zfq = 9.9;
		}
		$this->load->view('category/add_goods_category', get_defined_vars());
	}
	
	/**
	 * 保存活动分类
	 */
	private function save_goods_category()
	{
		$this->load->model('goods_category_model');
		$id = intval($this->input->get_post('id'));
		$pid = intval($this->input->get_post('pid'));
		$name = strval($this->input->get_post('name'));
		$discount = floatval($this->input->get_post('discount'));
		$discount_yzcm = floatval($this->input->get_post('discount_yzcm'));
		$discount_mpg = floatval($this->input->get_post('discount_mpg'));
		$discount_zfq = floatval($this->input->get_post('discount_zfq'));
		$sort = intval($this->input->get_post('sort'));
		if($id){
			$rs = $this->goods_category_model->update($id, $pid, $name, $discount, $discount_yzcm, $discount_mpg,$discount_zfq,$sort);
		}else{
			$rs = $this->goods_category_model->add($pid, $name, $discount, $discount_yzcm, $discount_mpg,$discount_zfq, $sort);
		}
		if($rs){
			$this->success('保存成功');
		}else{
			$this->error('保存失败');
		}
	}
	
	/**
	 * 删除活动分类
	 */
	public function delete_goods_category()
	{
		$this->load->model('goods_category_model');
		$id = intval($this->input->get_post('id'));
		$rs = $this->goods_category_model->delete($id);
		if($rs){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}
	
	/**
	 * 推荐分类
	 */
	public function recommend_category()
	{
		$this->load->model('common_recommend_category_model');
		$cate_level1 = $this->common_recommend_category_model->get_by_pid();
		$pid = intval($this->input->get_post('pid'));
		if(!$pid){
			$pid = $cate_level1[0]['id'];
		}
		$current_cate = $this->common_recommend_category_model->get($pid);
		$cate_level2 = $this->common_recommend_category_model->get_by_pid($pid);
		$this->load->view('category/recommend_category', get_defined_vars());
	}
	
	/**
	 * 添加推荐分类
	 */
	public function add_recommend_category()
	{
		if('yes' == $this->input->get_post('dosave')){
			$this->save_recommend_category();
		}
		$cate = array();
		$cate['id'] = 0;
		$cate['name'] = '';
		$cate['sort_order'] = '';
		$cate['parent_id'] = intval($this->input->get_post('parent_id'));
		if($cate['parent_id']){
			$this->load->model('common_recommend_category_model');
			$parent_cate = $this->common_recommend_category_model->get($cate['parent_id']);
		}
		$this->load->view('category/add_recommend_category', get_defined_vars());
	}
	
	/**
	 * 编辑推荐分类
	 */
	public function edit_recommend_category()
	{
		if('yes' == $this->input->get_post('dosave')){
			$this->save_recommend_category();
		}
		$id = intval($this->input->get_post('id'));
		$this->load->model('common_recommend_category_model');
		$cate = $this->common_recommend_category_model->get($id);
		if($cate['parent_id']){
			$parent_cate = $this->common_recommend_category_model->get($cate['parent_id']);
		}
		$this->load->view('category/add_recommend_category', get_defined_vars());
	}
	
	/**
	 * 保存推荐分类
	 */
	private function save_recommend_category()
	{
		$this->load->model('common_recommend_category_model');
		$id = intval($this->input->get_post('id'));
		$parent_id = intval($this->input->get_post('parent_id'));
		$name = strval($this->input->get_post('name'));
		$sort_order = intval($this->input->get_post('sort_order'));
		if($id){
			$rs = $this->common_recommend_category_model->update($id, $parent_id, $name, $sort_order);
		}else{
			$rs = $this->common_recommend_category_model->add($parent_id, $name, $sort_order);
		}
		if($rs){
			$this->success('保存成功');
		}else{
			$this->error('保存失败');
		}
	}
	
	/**
	 * 删除推荐分类
	 */
	public function delete_recommend_category()
	{
		$this->load->model('common_recommend_category_model');
		$id = intval($this->input->get_post('id'));
		$rs = $this->common_recommend_category_model->delete($id);
		if($rs){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}
}
// End of class Category

/* End of file category.php */
/* Location: ./application/controllers/category.php */