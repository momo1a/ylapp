<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 日志管理控制器
 * @author minch <yeah@minch.me>
 * @version 2013-06-21
 */
class Loglist extends MY_Controller 
{
	public $check_access = TRUE;
	public $except_methods = array('index');
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 系统异常日志
	 */
	public function syslog(){
		$this->load->model('system_log_model');
		$stime = strtotime($this->get_post('startTime'));
		$etime = strtotime($this->get_post('endTime'));
		$key = $this->get_post('key', '');
		$limit = 15;
		$offset = $this->uri->segment(3);
		$list = $this->system_log_model->get($key, $stime, $etime, $limit, $offset);
		$total = $this->system_log_model->count($key, $stime, $etime);
		$page_conf = array('anchor_class'=>'type="load" rel="div#main-wrap"');
		$pager = $this->pager($total, $limit, $page_conf);
		$this->load->view('loglist/syslog', get_defined_vars());
	}
	
	/**
	 * 管理员操作日志
	 */
	public function admin(){
		$this->load->model('system_admin_log_model');
		$stime = strtotime($this->get_post('startTime'));
		$etime = strtotime($this->get_post('endTime'));
		$key = $this->get_post('key', '');
		$limit = 15;
		$offset = $this->uri->segment(3);
		$list = $this->system_admin_log_model->get($key, $stime, $etime, $limit, $offset);
		$total = $this->system_admin_log_model->count($key, $stime, $etime);
		$page_conf = array('anchor_class'=>'type="load" rel="div#main-wrap"');
		$pager = $this->pager($total, $limit, $page_conf);
		$this->load->view('loglist/admin', get_defined_vars());
	}

	/**
	 * 商家操作日志
	 */
	public function seller(){
		$offset = $this->uri->segment(3);
		$this->load->view('loglist/seller', get_defined_vars());
	}
}
// End of class Loglist

/* End of file loglist.php */
/* Location: ./application/controllers/loglist.php */