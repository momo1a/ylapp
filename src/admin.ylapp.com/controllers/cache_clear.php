<?php
/**
 * Created by PhpStorm.
 * User: phs
 * Date: 15-8-3
 * Time: 上午11:10
 * 管理员后台增加缓存清除管理，涉及模块首页和帮助中心
 */
class cache_clear extends MY_Controller
{
	//缓存类型
	private $cache_type = array('www', 'help');
	//定义业务方法
	private $show_tag_wap = array(
		'1' => 'list_cache', //显示清理缓存的类别
		'2' => 'add', //添加需要处理的缓存页面
		'3' => 'list_log', //缓存处理日志
		'4' => 'list_url', //类别缓存地址列表
	);
	private $tag_type = '';
	private $set_vars = array();
	private $page_num = 10;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cache_catogery_model', 'cache_catogery');
		$this->load->model('cache_clear_model', 'cache_clear');
		$this->load->model('cache_clear_log_model', 'cache_clear_log');
	}

	/**
	 * 执行清楚缓存操作
	 */
	public function clear_action()
	{
		$cat_error = false;
		$all_error = false;
		$cid = $this->get_post('cid');
		$catogery_list = $this->cache_clear->get_by_cid($cid);
		if ($catogery_list) {
			foreach ($catogery_list as $key => $vall) {
				foreach ($vall as $val) {
					$action_result = $this->_clear_cache($val['url']);
					if ($action_result['error']) {
						$cat_error = 1;
						$all_error = 1;
					}
					$log_data = array(
						'cid' => $val['cid'],
						'url' => $val['url'],
						'actiontime' => time(),
						'uid' => $this->user_id,
						'username' => $this->username,
						'result' => $action_result['msg'],
					);
					$this->cache_clear_log->insert($log_data);
				}
				$catogery_log_data = array(
					'cid' => $key,
					'uid' => $this->user_id,
					'username' => $this->username,
					'result' => $cat_error ? '清除缓存失败' : '清除缓存成功',
					'actiontime' => time(),
				);
				$this->load->model('cache_catogery_log_model', 'cache_catogery_log');
				$this->cache_catogery_log->insert($catogery_log_data);
				$cat_error = false;
			}
		} else {
			$this->error('该类目下不存在可启用的地址');
		}
		if ($all_error) {
			$this->error('清除缓存失败，请重新操作');
		} else
			$this->success('清除缓存成功');
	}

	/**
	 * @param string $cid
	 * 编辑地址
	 */
	public function cache_edit($eid = '')
	{
		$eid = $eid ? $eid : $this->get_post('id');
		$post_data = $_POST;
		$this->load->helper('form');
		if (empty($post_data)) {
			$data = $this->cache_clear->get_by_id($eid);
			$select_data = $this->cache_catogery->get_catogery_select();
			$data['default_id'] = $this->cache_catogery->get_id_by_cat_name($data['cat_name']);;
			$data['select_data'] = $select_data;
		} else {
			$this->_check_url($post_data['url']);
			if ($this->cache_clear->update(array('id' => $eid), $post_data))
				$this->success('操作成功');
		}
		$this->load->view('cache_clear/edit', $data);
	}

	/**
	 * @param string $cid
	 * 删除数据
	 */
	public function delete_catogery()
	{
		$cid = $this->get_post('cid');
		if (!$this->cache_clear->find_by(array('cid' => $cid))) {
			$this->cache_catogery->delete($cid);
			$this->success('操作成功');
		} else {
			$this->error('当前类别下有缓存地址，无法删除');
		}
	}

	/**
	 * @param string $cid
	 * 删除数据
	 */
	public function delete_url()
	{
		$id = $this->get_post('id');
		if ($cid = $this->cache_clear->delete_data($id)) {
			if (is_numeric($cid)) $this->cache_catogery->delete($cid);
			$this->success('操作成功');
		} else {
			$this->error('不存在数据');
		}
	}

	/**
	 * 查看单个类目的日志
	 */
	public function clear_log()
	{
		$this->_list_log();
		$this->load->view('cache_clear/list_log', $this->set_vars);
	}

	/**
	 * 清除缓存日志列表
	 * 获取类别名称进行url的筛选
	 */
	public function ajax_list_log()
	{
		$this->load->model('cache_catogery_log_model', 'cache_catogery_log');
		$cid = $this->get_post('cid'); //所属类目
		$offset = intval($this->uri->segment(3)); //起始记录下标
		$contents = $this->cache_catogery_log->get_log_data($cid, $this->page_num, $offset);
		$list_count = $this->cache_catogery_log->count_log_data($cid);
		$page_conf = array('uri_segment' => 3, 'anchor_class' => ' onclick="load($(this).attr(\'href\'),\'div#dialog\', $(this).data());return false;" data-listonly="yes"');
		$page_base_url = site_url('cache_clear/ajax_list_log');
		$page_conf['base_url'] = $page_base_url;
		$page_conf['first_url'] = $page_base_url . '/0';
		$pager = $this->pager($list_count, $this->page_num, $page_conf);
		$this->set_vars = array_merge($this->set_vars, get_defined_vars());
		$this->load->view('cache_clear/catogery_list_log', $this->set_vars);
	}

	/**
	 * ajax 请求检查接口值
	 */
	public function ajax_check_url()
	{
		$ret = array(
			'state' => false,
			'msg' => ''
		);
		$url = $this->get_post('url');
		$url || exit();
		$msg = $this->_check_url($url, true);
		if ($msg)
			$ret['msg'] = $msg;
		else
			$ret['state'] = true;
		exit(json_encode($ret));
	}

	/**
	 * 缓存管理切换显示层逻辑
	 */
	public function cache()
	{
		$this->tag_type = intval($this->get_post('tag_type')) <= 1 ? 1 : intval($this->get_post('tag_type'));
		$this->{'_' . $this->show_tag_wap[$this->tag_type]}();
		$this->set_vars['selected'] = 'yes'; //是否为当前页
		$this->set_vars['tag_type'] = $this->tag_type;
		$this->load->view('cache_clear/index', $this->set_vars);
	}

	/**
	 * 缓存分类列表
	 * 缓存类别，清楚缓存
	 */
	public function _list_cache()
	{
		if($cat_name = $this->input->post('cat_name')){
			$insert_data['cid'] = $this->cache_catogery->add($cat_name);
			$ret = array(
				'state'=>true,
				'msg'=>'添加成功'
			);
			exit(json_encode($ret));

		}
		/*常见问题*/
		$offset = intval($this->uri->segment(3)); //起始记录下标
		$contents = $this->cache_catogery->get_cat_name($this->page_num, $offset);
		$list_count = $this->cache_catogery->count_all();
		$page_conf = array('uri_segment' => 3, 'anchor_class' => 'type="load" rel="div#main-wrap"');
		$page_base_url = site_url('cache_clear/cache');
		$page_conf['base_url'] = $page_base_url;
		$page_conf['first_url'] = $page_base_url . '/0';
		$pager = $this->pager($list_count, $this->page_num, $page_conf);
		$this->set_vars = array_merge($this->set_vars, get_defined_vars());
	}

	/**
	 * 缓存列表
	 * 缓存类别，清楚缓存
	 */
	public function _list_url()
	{
		/*常见问题*/
		$this->load->helper('form');
		$select_data = $this->cache_catogery->get_catogery_select();
		$cid = $this->get_post('cid'); //所属类目
		$offset = intval($this->uri->segment(3)); //起始记录下标
		$contents = $this->cache_clear->get_cache_data($cid, $this->page_num, $offset);
		$list_count = $this->cache_clear->count_cache_data($cid);
		$page_conf = array('uri_segment' => 3, 'anchor_class' => 'type="load" rel="div#main-wrap"');
		$page_base_url = site_url('cache_clear/cache');
		$page_conf['base_url'] = $page_base_url;
		$page_conf['first_url'] = $page_base_url . '/0';
		$pager = $this->pager($list_count, $this->page_num, $page_conf);
		$this->set_vars = array_merge($this->set_vars, get_defined_vars());
	}

	/**
	 * 添加缓存地址
	 * 包括自增编号、类别名称、地址、是否启用、备注、添加时间
	 */
	public function _add()
	{
		if ($this->get_post('cid')) {
			$insert_data = $this->_get_insert_data();
			if ($this->cache_clear->add($insert_data)) {
				if ($this->is_ajax()) {
					$this->success('添加成功');
				}
			}
		}else{
			$this->load->helper("form");
			$select_data = $this->cache_catogery->get_catogery_select();
			$this->set_vars = array_merge($this->set_vars, get_defined_vars());
		}
	}

	/**
	 * 清除缓存日志列表
	 * 获取类别名称进行url的筛选
	 */
	public function _list_log()
	{
		$cid = $this->get_post('cid'); //所属类目
		$offset = intval($this->uri->segment(3)); //起始记录下标
		$contents = $this->cache_clear_log->get_log_data($cid, $this->page_num, $offset);
		$list_count = $this->cache_clear_log->count_log_data($cid);
		$page_conf = array('uri_segment' => 3, 'anchor_class' => 'type="load" rel="div#main-wrap"');
		$page_base_url = site_url('cache_clear/cache');
		$page_conf['base_url'] = $page_base_url;
		$page_conf['first_url'] = $page_base_url . '/0';
		$pager = $this->pager($list_count, $this->page_num, $page_conf);
		$this->set_vars = array_merge($this->set_vars, get_defined_vars());
	}

	/**
	 * 通过url清楚缓存
	 * @param $url
	 */
	public function _clear_cache($url)
	{
		$ret = array(
			'error' => 1,
			'msg' => '清除成功'
		);
		if (file_get_contents($url)) {
			preg_match('/^http:\/\/([a-z]+)\..*?/', $url, $str);
			$sld = $str[1];
			if (in_array($sld, $this->cache_type)) {
				$cache_filepath = $this->{'_get_' . $sld . '_path'}($url);
				if (@file_exists($cache_filepath)) {
					@unlink($cache_filepath);
					$ret['error'] = 0;
					file_get_contents($url . '?fc');
				} else {
					$ret['msg'] = '清除失败：不存在缓存文件';
				}
			} else {
				$ret['msg'] = '清除失败：域名下未设置缓存';
			}
		} else {
			$ret['msg'] = '清除失败：无效地址';
		}
		return $ret;
	}

	/**
	 * 通过传输进来的url判断二级域名获取缓存文件
	 */
	protected function _get_insert_data()
	{
		$data = $_POST;
		$this->_check_url($data['url']);
		$data['addtime'] = time();
		unset($data['submit']);
		return $data;
	}

	/**
	 * 获取首页的缓存地址
	 * @param $url
	 * @return bool|string
	 */
	protected function _get_www_path($url)
	{
		if (preg_match('/^http:\/\/www\.(\w+)\.com$/', rtrim($url, '/'))) {
			$cache_path = COMPATH . '..' . DIRECTORY_SEPARATOR . 'www.ylapp.com' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;
			$uri = $this->config->item('domain_www');
			return $cache_path . md5($uri);
		} else {
			return false;
		}
	}

	/**
	 * 获取帮助中心的缓存地址
	 * @param $url
	 * @return bool|string
	 */
	protected function _get_help_path($url)
	{
		$data = explode('/', str_replace('http://', '', $url));
		array_shift($data);
		$cache_path = COMPATH . '..' . DIRECTORY_SEPARATOR . 'help.ylapp.com' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;
		if ($data&&!@empty($data[0])){
			return $cache_path . implode('_', $data) . '.html';
		}else{

			return $cache_path . 'buyer_index.html';
		}
	}

	/**
	 * @param $url 地址url
	 * @param bool $ret返回类型
	 * @return string
	 */
	protected function _check_url($url, $ret = false)
	{
		$msg = '';
		if (!preg_match('/^[a-zA-z]+:\/\/[^\s]*/', $url))
			$msg = '请输入有效的地址';
		if (!@file_get_contents($url)) {
			$msg = '请输入可以访问的地址';
		}
		if (!strstr($url, rtrim($this->config->item('domain_www'), '/')) && !strstr($url, rtrim($this->config->item('domain_help'), '/'))) {
			$msg = '你设置的域名未设置缓存';
		}
		if ($ret)
			return $msg;
		elseif ($msg)
			$this->error($msg);
	}
}