<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 消息推送管理控制器
 * @author 张桂赏
 * @version 2014-7-25
 * @property app_push_message_model $app_push_message_model
 */
class App_message_push extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('app_push_message_model');
	}
	
	public function index()
	{
		$this->message();
	}
	
	/**
	 * 显示推送信息
	 */
	public function message()
	{
		
		$refresh_ret = $this->app_push_message_model->refresh_state();
		if(!$refresh_ret){
			$this->error('更新消息状态失败');
		}
		
		$search_key=trim($this->input->get_post('search_key',""));
		$start_time=strtotime($this->input->get_post('startTime',0));
		$end_time=strtotime($this->input->get_post('endTime',0));
		$segment = $this->uri->segment(3);
		$segment='message';
		$offset = intval($this->uri->segment(4));
		$size=10;
		$all_message=$this->app_push_message_model->search($search_key,$start_time,$end_time,$size,$offset);
		$total=$all_message['count'];
		//分页
		$page_conf = array('uri_segment'=>4,'anchor_class'=>'type="load" rel="div#main-wrap"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment);
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment.'/0');
		$pager = $this->pager($total, $size, $page_conf);
		$this->load->view('app/message_push',get_defined_vars());
	}
	
	/**
	 * 新增推送信息
	 */
	public function add_message()
	{
		$this->load->view('app/message_add');
	}
	
	public function add()
	{
		$title=trim($this->get_post('title'));
		$content=trim($this->get_post('content'));
		$client_type=intval($this->get_post('client_type'));
		$push_time=strtotime($this->get_post('push_time'));
		$dateline=time();
		
		if(mb_strlen($content)<=0)
		{
			$this->error('请填写内容');
		}
		if(mb_strlen($content)>50)
		{
			$this->error('内容不能超过50个字');
		}
		
		$re=$this->app_push_message_model->add($title, $content, $client_type, $dateline, $push_time);
		if ($re)
		{
			$message="添加成功！";
			$this->success($message);		
		}else
		{
			$message="添加失败！";
			$this->error($message);
		}	
	}
	
	public function push()
	{
		// 获取推送数据
		$id = intval($this->get_post('id'));
		$id OR $this->error('缺少参数：id');
		
		$ret = $this->app_push_message_model->push_all($id);
		if ($ret) {
			$this->success('推送成功');
		}else{
			$error = $this->app_push_message_model->error();
			//$this->log($error_str, array_merge($_GET, $_POST));
			$this->error('推送失败');
		}
			
	}

}