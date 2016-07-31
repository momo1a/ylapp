<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 链接管理控制器
 * @author minch <yeah@minch.me>
 * @version 2013-06-19
 * @property common_link_model $common_link_model
 */
class Link extends MY_Controller 
{
	public $check_access = TRUE;
	public $except_methods = array('index');
	
	public function __construct(){
		parent::__construct();
		$this->load->model('common_link_model');
		$this->load->helper('html');
	}
	
	public function index(){
		$type_uri = $this->uri->segment(3);
		switch ($type_uri){
			case 'buyer_rule':
				$type = Common_link_model::TYPE_BUYER_RULE;
				break;
			case 'seller_rule':
				$type = Common_link_model::TYPE_SELLER_RULE;
				break;
			case 'notice':
				$type = Common_link_model::TYPE_NOTICE;
				break;
			case 'headernotice':
				$type = Common_link_model::TYPE_HEADERNOTICE;
				break;
			case 'guarantee':
				$type = Common_link_model::TYPE_GUARANTEE;
				break;
			case 'newbie':
				$type = Common_link_model::TYPE_NEWBIE;
				break;
			case 'biz':
				$type = Common_link_model::TYPE_BIZ;
				break;
			case 'service':
				$type = Common_link_model::TYPE_SERVICE;
				break;
			case 'friend':
				$type = Common_link_model::TYPE_FRIEND_LINK;
				break;
			default:
				$this->error('参数错误');
		}
		$list = $this->common_link_model->getby_type($type);
		$this->load->view('recommend/link', get_defined_vars());
	}
	
	/**
	 * 保存链接
	 */
	public function save(){
		$data = array();
		$data['id'] = intval($this->get_post('id'));
		$data['title'] = trim(strval($this->get_post('title')));
		$data['content'] = trim(strval($this->get_post('content')));
		$data['url'] = trim(strval($this->get_post('url')));
		$data['differ'] = intval($this->get_post('differ'));
		$data['sort'] = intval($this->get_post('sort'));
		$data['type'] = $this->get_post('type');
		$rs = $this->common_link_model->save($data);
		if($rs){
			$static_path = dirname(realpath(APPPATH)).DIRECTORY_SEPARATOR.'static.ylapp.com';
			$nfilename = $static_path.DIRECTORY_SEPARATOR.'javascript'.DIRECTORY_SEPARATOR.'www'.DIRECTORY_SEPARATOR.'top_notice.js';
			$nstring = '';
			if($data['differ']==Common_link_model::DIFFER_TYPE_WEB){
				// 生成页头公告条js
				$list = $this->common_link_model->getby_type($data['type'],1, Common_link_model::DIFFER_TYPE_WEB);
				if ($data ['type'] == Common_link_model::TYPE_HEADERNOTICE ) {
				
					if (count ( $list )) {
						foreach ( $list as $nk => $nval ) {
							$ntitle = $nval ['title'];
							$nurl = $nval ['url'];
						}
						$nstring = 'document.writeln("<p style=\"background-color:#F3DE6A; text-align:center;\"><a target=\"_blank\" href=\"' . $nurl . '\" style=\"display:block; line-height:30px; font-weight:700; color:#c00;\">' . $ntitle . '</a></p>");';
					}
				}
			}
			@file_put_contents ( $nfilename, $nstring );
			
			$this->success('保存成功');
		}else{
			$this->error('保存失败');
		}
	}

	/**
	 * 删除链接
	 */
	public function delete(){
		$id = $this->get_post('id');
		$type = $this->get_post('type');
		$rs = $this->common_link_model->delete($id);
		if($rs){
			//清空页头公告条js
			if($type==Common_link_model::TYPE_HEADERNOTICE){
			  $static_path = dirname(realpath(APPPATH)).DIRECTORY_SEPARATOR.'static.ylapp.com';
			  $nfilename = $static_path.DIRECTORY_SEPARATOR.'javascript'.DIRECTORY_SEPARATOR.'www'.DIRECTORY_SEPARATOR.'top_notice.js';
			  @file_put_contents($nfilename,'');
			}
			$this->log('删除链接成功');
			$this->success('删除链接成功');
		}else{
			$this->log('删除链接失败');
			$this->error('删除链接失败');
		}
	}

}
// End of class link

/* End of file link.php */
/* Location: ./application/controllers/link.php */