<?php
if(!defined('BASEPATH'))
	exit('No direct script access allowed');
/**
 * 管理员控制器类
 * @author minch <yeah@minch.me>
 * @property CI_Config $config
 * @property CI_Loader $load
 * @property CI_DB_active_record $db
 * @property CI_Calendar $calendar
 * @property Email $email
 * @property Excel_Xml $excel
 * @property CI_Encrypt $encrypt
 * @property CI_Ftp $ftp
 * @property CI_Hooks $hooks
 * @property CI_Image_lib $image_lib
 * @property CI_Input $input
 * @property CI_Language $language
 * @property CI_Log $log
 * @property CI_Output $output
 * @property CI_Pagination $pagination
 * @property CI_Parser $parser
 * @property CI_Session $session
 * @property CI_Sha1 $sha1
 * @property CI_Table $table
 * @property CI_Trackback $trackback
 * @property CI_Unit_test $unit
 * @property CI_Upload $upload
 * @property CI_URI $uri
 * @property CI_User_agent $agent
 * @property CI_Validation $validation
 * @property CI_Xmlrpc $xmlrpc
 * @property CI_Zip $zip
 * @property Common_advertisement_model $common_advertisement_model
 * @property Common_link_model $common_link_model
 * @property Common_recommend_model $common_recommend_model
 * @property Goods_addition_model $goods_addition_model
 * @property Goods_category_model $goods_category_model
 * @property Goods_remind_model $goods_remind_model
 * @property Admin_goods_model $goods_model
 * @property User_message_model $message_model
 * @property Admin_order_appeal_model $order_appeal_model
 * @property Order_appeal_type_model $order_appeal_type_model
 * @property Order_log_model $order_log_model
 * @property Admin_order_model $order_model
 * @property System_config_model $system_config_model
 * @property Admin_user_model $user_model
 * @property Common_recommend_category_model $common_recommend_category_model
 * @property Goods_util $goods_util
 * @property Order_uril $order_util
 * @property Rbac $rbac
 * @property Rbac_model $rbac_model
 */
class MY_Controller extends CI_Controller
{
	/**
	 * @var integer 当前登录管理员ID
	 */
	protected $user_id;
	/**
	 * 管理员用户名
	 * @var string 管理员用户名
	 */
	protected $username = 'admin';
	
	public function __construct()
	{
		parent::__construct();
		// 添加公共类库的加载路径
		//$this->load->add_package_path(COMPATH . '/');
		$this->load->helpers(array('autoload','get_user','core','image_url'));
		// 检查登录状态
		$this->_check_login();
		$this->_check_access();
		
		// 强制不使用页面缓存
		$this->no_browser_cache();
		
		// Ajax缓存参数
		unset($_GET['_']);
	}
	
	/**
	 * 检查管理员登录状态
	 */
	private function _check_login()
	{
		$user = get_user();
		if(!$user){
			$login_url = $this->config->item('url_login') . '?to=' . site_url('/');
			if($this->is_ajax()){
				$this->ajax_return(array('type'=>'NO_LOGIN','login_url'=>$login_url,'timeout'=>2000));
			}else{
				// redirect($login_url);
				echo <<<JS
				<script type="text/javascript">window.top.location.href='{$login_url}'</script>
JS;
				exit();
			}
		}else{
			$this->user_id = $user['id'];
			$this->username = $user['name'];
		}
	}
	
	/**
	 * 检查操作权限
	 */
	private function _check_access()
	{
		// 超级管理员不用验证权限
		if(in_array($this->user_id, $this->config->item('super_admin_uids'))){
			return;
		}
		// 判断用户是否在管理员用户组中
		$roles = $this->rbac_model->get_user_roles($this->user_id);
		if(!count($roles)){
			if($this->is_ajax()){
				$this->ajax_return(array('type'=>'ACCESS_DENY'));
			}else{
				exit($this->load->view('public/access_deny', array('msg'=>'非法操作，您无管理员权限'), TRUE));
			}
		}
		// 判断模块是否需要验证权限
		$need_check = property_exists($this, 'check_access') ? $this->check_access : false;
		$except_methods = property_exists($this, 'except_methods') ? $this->except_methods : array();
		if($need_check && !in_array($this->router->method, $except_methods)){
			// 当前操作
			$current_action = trim($this->router->directory . '/' . $this->router->class . '/' . $this->router->method, '/');
			// 用户可操作的所有操作权限
			$user_actions = $this->rbac->get_user_actions($this->user_id);
			if(!in_array($current_action, $user_actions)){
				if($this->is_ajax()){
					$this->ajax_return(array('type'=>'ACCESS_DENY'));
				}else{
					exit($this->load->view('public/access_deny', array('msg'=>'您无此操作权限，请联系超级管理员'), TRUE));
				}
			}
		}
	}
	
	/**
	 * 改进的Input类get_post方法
	 * @param string $key 索引键值
	 * @param mix $default 不存在GET/POST对应的$key时默认值（默认NULL）
	 * @param boolean $xss_clean XSS清除（默认FALSE）
	 * @return string
	 */
	public function get_post($key, $default = NULL, $xss_clean = FALSE)
	{
		$val = $this->input->get_post($key, $xss_clean);
		if(FALSE === $val){
			$val = $default;
		}
		return $val;
	}
	
	/**
	 * 判断是否AJAX请求
	 * @return boolean
	 */
	protected function is_ajax()
	{
		return $this->input->is_ajax_request();
	}
	
	/**
	 * Ajax统一返回处理方法
	 * @param mix $data 返回数据
	 * @param string $data_type 返回数据类型
	 */
	protected function ajax_return($data, $data_type = 'JSON')
	{
		switch(strtoupper($data_type)){
			case 'JSON':
				header('Content-Type:application/json; charset=utf-8');
				exit(json_encode($data));
			case 'XML':
				// TODO convert data to XML and return;
				break;
			default:
				header('Content-Type:text/html; charset=utf-8');
				exit($data);
		}
	}
	
	/**
	 * 统一成功提示方法
	 * @param string $message 提示信息
	 * @param mix $data 其它数据
	 * @param string $type 输出数据类型
	 */
	protected function success($message, $data = NULL, $type = 'JSON')
	{
		if($this->is_ajax()){
			$ajdata = array();
			$ajdata['type']='SUCCESS';
			$ajdata['error'] = 0;
			$ajdata['msg'] = $message;
			$ajdata['data'] = $data;
			$this->ajax_return($ajdata, $type);
		}
		// TODO perfect method
		header("Content-Type:text/html;charset=utf-8");
		show_error($message, 200, '操作成功');
	}
	
	/**
	 * 统一错误处理方法
	 * @param string $message 提示信息
	 * @param mix $data 其它数据
	 * @param string $type 输出数据类型
	 */
	protected function error($message, $data = NULL, $type = 'JSON')
	{
		if($this->is_ajax()){
			$ajdata = array();
			$ajdata['type']='ERROR';
			$ajdata['error'] = 1;
			$ajdata['msg'] = $message;
			$ajdata['data'] = $data;
			$this->ajax_return($ajdata, $type);
		}
		// TODO perfect method
		header("Content-Type:text/html;charset=utf-8");
		show_error($message);
	}
	
	/**
	 * 记录操作日志
	 * @param string $content
	 * @param string $param
	 */
	protected function log($content = '', $param = '')
	{
		$this->load->model('system_admin_log_model');
		if('' === $param){
			$param = array_merge($_GET, $_POST);
		}
		$this->system_admin_log_model->save($this->user_id, $this->username, $content, $param);
	}
	
	/**
	 * 分页公共方法
	 * @param number $total 总记录数
	 * @param number $per_page 每页显示记录数
	 * @param array $ext_conf 其它配置
	 */
	protected function pager($total = 0, $per_page = 10, $ext_conf = array())
	{
		$this->load->library('pagination');
		$uri = http_build_query(array_merge($_GET, $_POST));
		$config['total_rows'] = $total;
		$config['per_page'] = $per_page;
		$config['base_url'] = site_url($this->router->class . '/' . $this->router->method);
		$config['prev_link'] = '上一页';
		$config['next_link'] = '下一页';
		$config['first_link'] = '首页';
		$config['last_link'] = '末页';
		$config['uri_segment'] = 3;
		$config['num_links'] = 3;
		$ext_conf['first_url'] = isset($ext_conf['first_url'])?$ext_conf['first_url']:NULL;
		if($ext_conf['first_url']){
			$config['first_url'] = $ext_conf['first_url'];
			unset($ext_conf['first_url']);
		}else{
			$config['first_url'] = site_url($this->router->class . '/' . $this->router->method . '/0');
		}
		if($uri){
			$config['suffix'] = '?' . $uri;
			$config['first_url'] = $config['first_url'] . '?' . $uri;
		}
		foreach($ext_conf as $k=>$v){
			if(property_exists($this->pagination, $k)){
				$config[$k] = $v;
			}
		}
		if($config['per_page'] == 0 or $config['total_rows'] == 0){
			return '';
		}
		$this->pagination->initialize($config);
		$html = $this->pagination->create_links();
		$total_page = ceil($config['total_rows'] / $config['per_page']);
		$cur_page = $this->pagination->cur_page;
		$cur_page = $cur_page ? $cur_page : 1;
		$html = '<span style="padding-right:10px;">第'.$cur_page.'页/共'.$total_page.'页</span>'.$html;
		if($total_page>1){
			$html .= '<a '.$this->pagination->anchor_class.'href="'.$this->pagination->base_url.$this->pagination->prefix.'{offset}'.$this->pagination->suffix.'" style="display:none;">go</a>';
			$html .= '<form onsubmit="(function(f){';
			$html .= "var per_page = {$config['per_page']};";
			$html .= 'var page = $(f).find(\'input.gopage\').val();';
			$html .= 'if(page>=1 && page<='.$total_page.' && page!='.$cur_page.'){}else{return false;}';	//判断输入的页数是否合理
			$html .= "var a = $(f).prev('a'); var gohref = a.attr('href').replace('{offset}', page*per_page-per_page);";
			$html .= "if(a.attr('rel') && a.attr('type')=='load'){a.attr('href', gohref).trigger('click');}else{location.href=gohref}";
			$html .= '})(this);return false;" style="display:inline;">';
			$html .= ' 到第 <input type="text" size="3" class="gopage" style="padding:3px 2px;" /> 页';
			$html .= ' <input type="submit" value="确定" class="ui-form-btnSearch" />';
			$html .= '</form>';
		}
		return $html;
	}
	
	/**
	 * 数据导出下载
	 * @param array $data 数据
	 * @param string $title 标题
	 * @param string $filename 下载文件名
	 */
	protected function data_export($data, $title, $filename)
	{
		$this->load->library('ExportCSV');
		$this->exportcsv->export($data,$filename);
		exit();
		
		//把数据导出从xls格式修改为csv格式,update by 关小龙  2015-11-30 16:54:20 (备注：可通过注释代码块来切换不同格式的导出)
		/*
		$this->load->library('Excel_Xml', '', 'excel');
		$this->excel->addWorksheet($title, $data);
		// $this->excel->sendWorkbook($filename);
		$this->excel->download($filename);
		exit();
		*/
	}
	
	/**
	 * GET请求获取数据
	 * @param string $url 请求URL
	 * @param array $param 请求参数
	 */
	protected function get_url($url,$param=array())
	{
		$time = time();
		$key_param = array('t'=>$time,'k'=>md5($time.KEY_APP_SERVER)); //传递参数时间和加密字符串
		$param = array_merge($param,$key_param);
		return file_get_contents($url."?".http_build_query($param, '', '&'));
	}

	/**
	 * 强制不使用页面缓存
	 */
	public function no_browser_cache()
	{
		// 强制不使用页面缓存
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false );
		header('Pragma: no-cache');
	}
}
// End of MY_Controller class

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */