<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 名品馆商品管理
 * 
 * @author "韦明磊-众划算项目组<nicolaslei@163.com>"
 */
class Mpg extends MY_Controller
{
	/**
	 * 要放入视图的数据
	 * @var array
	 */
	private $view_data = array();
	
	protected $check_access = TRUE;
	
	// ---------------------------------------------------------------
	
	public function __construct() {
		
		parent::__construct();

		$this->load->model('goods_mpg_model');
		
		$this->load->helper(array('image_url'));
	}
	
	// ---------------------------------------------------------------
	
	/**
	 * 名品馆商品管理列表
	 * 
	 * @access public
	 * 
	 * @return void
	 */
	public function index($type = 'ongoing') {
		
		// 获取查询参数,只接受GET提交
		$this->view_data['search_key'] = $this->input->get('search_key', TRUE); // 查询的字段
		$this->view_data['search_val'] = $this->input->get('search_val', TRUE); // 查询的值
		
		if ($this->view_data['search_key'] && $this->view_data['search_val']) {
			$where[$this->view_data['search_key']] = $this->view_data['search_val'];
		}
		
		// 分页偏移量
		$this->goods_mpg_model->offset = $this->uri->segment(3);
		
		$this->view_data['list'] = $this->goods_mpg_model->find_mpg_limit($type, $where);

		// HTML分页设置
		$page_conf = array('uri_segment'=>3, 'anchor_class'=>'type="load" rel="div#main-wrap"');
		$page_conf['base_url'] = site_url('mpg/'.$type);
		$page_conf['first_url'] = site_url('mpg/'.$type);
		
		$this->view_data['pager'] = $this->pager($this->goods_mpg_model->total_count, $this->goods_mpg_model->limit, $page_conf);
		$this->view_data['get_type'] = $type;
		
		$this->load->view('mpg/index', $this->view_data);
		
	}// end index()
	
	// ---------------------------------------------------------------
	
	/**
	 * 更新名品馆商品排序(手动排序项)
	 * 
	 * @access public
	 * 
	 * @return void
	 */
	public function sort() {
		
		$sorts = $this->input->post('sorts', TRUE);
		
		if ($sorts) {
			$this->goods_mpg_model->update_manual_sort($sorts);
		}
		
		$this->success('排序成功');
	}// end sort()
}

/**
 *---------------------------------------------------------------
 * FUNCTION
 *---------------------------------------------------------------
 */
if ( ! function_exists('goods_buying_status'))
{
	function goods_buying_status($status, $remain_quantity, $wait_fill_num, $endtime) {
		
		$state_name = '';
		
		switch ($status) {
			case 5:
				$state_name = '<font color="blue">即将上线</font>';
				break;
			case 20:
				$state_name = '<font color="green">正在进行</font>';
				break;
			case 22:
				$state_name = '<font color="orange">活动结束</font>';
				break;
			case 24:
				$state_name = '<font color="gray">还有机会</font>';
				break;
			case 31:
				$state_name = '<font color="red">结算中</font>';
				break;
			case 32:
				$state_name = '<font color="green">已结算</font>';
				break;
			default:
				$state_name = '<font color="red">已屏蔽</font>';
		}
		return $state_name;
	}
}
// end mpg Controller