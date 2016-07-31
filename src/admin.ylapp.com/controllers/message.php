<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 消息管理控制器类
 * @author minch <yeah@minch.me>
 * @update 更改使用队列发送系统消息(Author:韦明磊,Date:2015-01-19)
 * @version 2013-07-15
 */
class Message extends MY_Controller 
{
	public $check_access = TRUE;
	public $except_methods = array();
	
	private static $_arr_message_type = array(
			'buyer'		=> array('id' => 1, 'txt' => '买家'),
			'seller'	=> array('id' => 2, 'txt' => '商家'),
			'all'		=> array('id' => 3, 'txt' => '全部'),
			'part'		=> array('id' => 4, 'txt' => '部分'),
	);
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_message_model', 'message_model');
	}

	/**
	 * 群发消息
	 * @update 更改使用队列发送系统消息(Author:韦明磊,Date:2015-01-19)
	 * @update 不再过滤封号和不存在的用户（由JAVA进行判断）(Author:韦明磊,Date:2015-03-03)
	 */
	public function send()
	{
		if ($this->input->post('dosend') && 'yes' == $_POST['dosend']) {
			// POST DATA
			$title				= trim($this->input->post('title'));
			$priority			= intval($this->input->post('priority')) ?: 3; // 优先级(默认3)
			$height_level		= $this->input->post('height_level');
			$starttime			= trim($this->input->post('startTime'));
			$endtime			= trim($this->input->post('endTime'));
			$lastlogintime		= trim($this->input->post('lastlogintime'));
			
			$content			= trim($this->input->post('content'));
			// 内容过滤
			$_tmp_content		= preg_replace("/<(?:img|embed).*?>/i", 'K', $content);
			$_tmp_content		= preg_replace("/\r\n|\n|\r/", '', $_tmp_content);
			$_tmp_content		= preg_replace("/&nbsp;/", ' ', $_tmp_content);
			$_tmp_content		= trim(strip_tags($_tmp_content));
			
			$content_len		= mb_strlen($_tmp_content, 'utf-8');
			if ($content_len < 1) {
				$this->error('内容不能为空');
			} elseif ($content_len > 500) {
				$this->error('内容不能超过500个字');
			}
			
			// 定时发送
			$starttime	= empty($starttime) ? 0 : strtotime($starttime);
			$endtime	= empty($endtime) ? 0 : strtotime($endtime);
			
			if ($endtime > 0 && $starttime > $endtime) {
				$this->error('结束时间不能大于开始时间');
			}
			
			$lastlogintime = empty($lastlogintime) ? 0 : strtotime($lastlogintime);
			
			// 普通模式
			if ( ! $height_level) {
				
				$lastlogintime = 0; // 普通模式下不需要这个参数

				$_str_tousers = trim($this->input->post('tousers'));
				if (empty($_str_tousers)) {
					$this->error('请填写收件人');
				}

				// 处理发送的用户
				$_str_tousers		= str_replace('，', ',', $_str_tousers); // 替换中文'，'为英文','
				$_arr_tousers		= explode(',', $_str_tousers);
				$_arr_clean_touser	= array(); // 用于存储清理完成后的用户
				foreach ($_arr_tousers as $name) {
					$_name = trim($name);
					if ( ! empty($_name)) {
						$_arr_clean_touser[] = $_name;
					}
				}
				// 去重
				$_arr_clean_touser = array_unique($_arr_clean_touser);
				$to_users = implode(',', $_arr_clean_touser); // 发送对象

				$str_touser_type	= 'part'; // 部分发送
				$log_unames			= implode(',', $_arr_clean_touser);

			} else { // 高级模式
				$priority = 5; // 群发默认优先级最低
				$to_users = $str_touser_type = $this->get_post('utype', 'all');
				if( ! array_key_exists($str_touser_type, self::$_arr_message_type)) {
					$this->error('请选择用户类型');
				}
				$log_unames = self::$_arr_message_type[$str_touser_type]['txt'];
			}
			
			// 添加发送记录
			$data = array(
					'to_uname'				=> $log_unames,
					'to_type'				=> self::$_arr_message_type[$str_touser_type]['id'],
					'to_scope'				=> $height_level ? 1 : 0,
					'from_uid'				=> $this->user_id,
					'from_uname'			=> $this->username,
					'title'					=> $title,
					'content'				=> $content,
					'dateline'				=> time(),
					'startline'				=> $starttime,
					'limit_lastlogintime'	=> $lastlogintime,
					'endline'				=> $endtime,
			);
			;

			// 存储站内信内容
			if ($id = $this->message_model->add_sent($data)) {
				// 使用队列发送
				if (send_message($to_users, $id, $title, $content, $priority, $starttime, $endtime, $lastlogintime)) {
					$this->success('操作成功');
				}
			}
	
			$this->error('操作失败');
			
		} else {
			//转发站内信
			$resend = $this->input->get_post('resend');
			if ($resend) {
				$id			= intval($this->input->get_post('id'));
				$msg_sent	= $this->message_model->get_sent(array('id'=>$id, 'from_uid'=>$this->user_id));
				if (empty($msg_sent)) {
					$error = '要转发的站内信不存在';
				} else{
					$to_scope		= $msg_sent['to_scope'];
					$to_type		= $msg_sent['to_type'];
					$title			= $msg_sent['title'];
					$content		= $msg_sent['content'];
					$height_level	= $to_scope ? 1 : 0;
					$to_type		= $msg_sent['to_type'];
					$to_uname		= $msg_sent['to_uname'];
					$startline		= $msg_sent['startline'] ? date('Y-m-d H:i:s', $msg_sent['startline']) : '';
					$endline		= $msg_sent['endline'] ? date('Y-m-d H:i:s', $msg_sent['endline']) : '';
					$lastlogintime	= $msg_sent['limit_lastlogintime'] ? date('Y-m-d H:i:s', $msg_sent['limit_lastlogintime']) : '';
				}
			} else {
				$to_type = 1;
			}
			
			$this->load->view('message/send', get_defined_vars());
		}
		
	}
	
	/**
	 * 已发送
	 */
	public function sent()
	{
		$allowmod = array('index','del','list','view');
		$mod = trim(strval($this->get_post('mod')));
		$mod = ! in_array($mod, $allowmod) ? 'index' : $mod;
		switch ($mod) {
			case 'index':$this->_sent_index();
			break;
			case 'list':$this->_sent_list();
			break;
			case 'view':$this->_sent_view();
			break;
			case 'del':$this->_sent_del();
			break;
		}
	}
	
	/**
	 * 模板
	 */
	function template()
	{
		$allowmod = array('index','add','del','edit','use');
		$mod = trim(strval($this->get_post('mod')));
		$mod = ! in_array($mod, $allowmod) ? 'index' : $mod;
		switch ($mod) {
			case 'index':$this->_tpl_list();
				break;
			case 'add':$this->_tpl_add();
				break;
			case 'del':$this->_tpl_del();
				break;
			case 'edit':$this->_tpl_edit();
				break;
			case 'use':$this->_tpl_use();
				break;
		}
	}
	
	/**
	 * 整理收件人
	 * @param string $tousers 收件人用户名(多个以英文逗号隔开)
	 * @return array 收件人用户名数组(无重复)
	 */
	private function _parse_tousers($tousers)
	{
		$tousers = trim($tousers);
		if($tousers == ''){
			return array();
		}
		$tousers = str_replace('，', ',', $tousers);
		$tousers_arr = explode(',', $tousers);
		foreach ($tousers_arr as &$name){
			$name = trim($name);
		}
		$tousers_arr = array_unique($tousers_arr);
		return $tousers_arr;
	}
	
	private function _tpl_list()
	{
		$per = 10;
		$page = intval($this->get_post('p', 1, TRUE));
		$where = array('uid'=>$this->user_id);
		$msg_tpl = $this->message_model->get_msg_tpl_list($where, $page, $per);
		$sumpage = ceil($msg_tpl['count'] / $per);
		$msg_template = $msg_tpl['list'];
		// 翻页
		//$this->load->helper('pagination');
		//$pageString = pagination($page, $sumpage, site_url('message/template?mod=index&p=%d'), 1, FALSE, FALSE);
		$this->load->view('message/tpl', get_defined_vars());
	}
	
	private function _tpl_use()
	{
		$tpl_id = intval($this->get_post('tpl_id', 0, TRUE));
		! $tpl_id && $this->error('站内信模板不能小于等于0');
		$where = array('uid'=>$this->user_id,'id'=>$tpl_id);
		$msgtpl = $this->message_model->get_msg_tpl($where);
		empty($msgtpl) && $this->error('站内信模板不存在');
		$this->success('',$msgtpl);
	}
	
	private function _tpl_add()
	{
		$title = trim($this->get_post('title', '', TRUE));
		$content = trim($this->get_post('content', ''));
		$title == '' && $this->error('模板标题为空');
		$content == '' && $this->error('模板内容为空');
		if($this->db->insert('system_admin_msg_template', array(
			'title'=>$title,
			'content'=>$content,
			'uid'=>$this->user_id,
			'uname'=>$this->username,
			'dateline'=>time()
		))){
			$this->success('添加成功');
		}else{
			$this->error('添加失败');
		}
	}
	
	private function _tpl_del()
	{
		$do = intval($this->get_post('do', 0, TRUE));
		$tpl_id = $this->get_post('id');
		if( ! is_array($tpl_id)){
			$tpl_id = intval($tpl_id);
			! $tpl_id && $this->error('模板id为空');
			$tpl_id = array($tpl_id);
		}else{
			empty($tpl_id) && $this->error('模板id为空');
		}
		if($do == 1){
			foreach ($tpl_id as $id){
				$this->db->delete('system_admin_msg_template', array('id'=>$id));
			}
			$this->success('删除成功');
		}
	}
	
	private function _tpl_edit()
	{
		$do = trim($this->get_post('do', '', TRUE));
		$tpl_id = intval($this->get_post('id', 0, TRUE));
		! $tpl_id && $this->error('模板id为空');
		if($do == 'save'){
			$title = trim($this->get_post('title', '', TRUE));
			$content = trim($this->get_post('content', ''));
			$title == '' && $this->error('模板标题为空');
			$content == '' && $this->error('模板内容为空');
			$this->db->update('system_admin_msg_template', array('title'=>$title,'content'=>$content), array('id'=>$tpl_id));
			$this->success('编辑成功');
		}else{
			$tpl = $this->db->select('*')->from('system_admin_msg_template')->where('id', $tpl_id)->get()->row_array();
			$this->load->view('message/edit', get_defined_vars());
		}
	}
	
	private function _sent_index()
	{
		$this->load->view('message/sent', get_defined_vars());
	}
	
	private function _sent_view()
	{
		$id = intval($this->input->get_post('id'));
		$direction = trim($this->input->get_post('d'));
		if( ! in_array($direction, array('-1', '1'))){
			$where = array('id'=>$id,'from_uid'=>$this->user_id);
		}else{
			$where[$direction == '1' ? 'id <' : 'id >'] = $id;
			$where['from_uid'] = $this->user_id;
		}
		$msg_sent = $this->message_model->get_sent($where, 'dateline '.($direction == '-1' ? 'ASC' : 'DESC'));
		if(empty($msg_sent)){
			$msg = $direction == '1' ? '已经是最后一封' : ($direction == '-1' ? '已经是第一封' : '已发送的站内信不存在,无法查看');
			$this->error($msg);
		}
		$this->load->view('message/sent_view', get_defined_vars());
	}
	
	private function _sent_list()
	{
		$per = 10;
		$page = intval($this->get_post('p', 1, TRUE));
		$where = array('from_uid'=>$this->user_id);
		$msg_sent = $this->message_model->get_sent_list($where, $page, $per);
		$sumpage = ceil($msg_sent['count'] / $per);
		$msg_sent = $msg_sent['list'];
		// 翻页
		$this->load->helper('pagination');
		$pageString = pagination($page, $sumpage, site_url('message/sent?mod=list&p=%d'), 3, FALSE, FALSE);
		$this->load->view('message/sent_list', get_defined_vars());
	}
	
	private function _sent_del()
	{
		$do = intval($this->get_post('do', 0, TRUE));
		$sent_id = $this->input->get_post('id', TRUE);
		if( ! is_array($sent_id) || empty($sent_id)){
			$this->error('请选择要删除的站内信');
		}
		if($do == 1){
			foreach ($sent_id as $id){
				$this->db->delete('system_admin_msg_sent', array('id'=>$id));
			}
			$this->success('删除成功');
		}else{
			$this->error('错误请求');
		}
	}
}
// End of class Message

/* End of file message.php */
/* Location: ./application/controllers/message.php */