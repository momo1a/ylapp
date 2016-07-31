<?php
if(!defined('BASEPATH'))
exit('No direct script access allowed');

/**
 * 推荐管理控制器
 * @author minch <yeah@minch.me>
 * @version 2013-06-06
 * @property Common_recommend_model $common_recommend_model
 * @property admin_goods_model  $goods_model
 */
class Recommend extends MY_Controller
{
	public $check_access = TRUE;
	public $except_methods = array('search_goods');
		
		// 定义活动的状态
	private $goods_status = array (
			'1' => '未付款待审核',
			'2' => '待审核付款中',
			'3' => '已支付待审核',
			'4' => '发布修改退款中',
			'5' => '审核通过待上线',
			'10' => '取消退款中',
			'11' => '已取消',
			'12' => '审核未通过退款中',
			'13' => '审核未通过',
			'20' => '正在进行',
			'21' => '已屏蔽',
			'22' => '已下架',
			'23' => '追加付款中',
			'30' => '结算退款中',
			'31' => '结算中',
			'32' => '已结算' 
	);

	public function __construct()
	{
		parent::__construct();
		$this->load->model('common_recommend_model');
		$this->load->helper(array('image_url','html'));
		$this->load->helper(array('form'));
	}

	/**
	 * 商品推荐
	 */
	public function goods()
	{
		//定义活动的状态
		$goods_status=$this->goods_status;
		
		$type = $this->uri->segment(3);
		$segment = $type;
		switch($type){
			case 'new':
				$recommend_type = Common_recommend_model::RECOMMEND_NEW;
				$list = $this->common_recommend_model->get_new(99999);
				break;
			case 'advance':
				$clear_count = 0;
				$recommend_type = Common_recommend_model::RECOMMEND_ADVANCE;
				$list = $this->common_recommend_model->get_advance(99999);
				foreach ($list as $k=>$item) {
					//【新品预告】里的商品上线后，自动删掉新品预告推送列表里对应的活动
					if($item['state'] >= 20){
						$clear_count++;
						$this->db->query("DELETE FROM ".$this->db->dbprefix('common_recommend')." WHERE type=2 AND category_id=0 AND target_id='".$item['target_id']."'");
						unset($list[$k]);
					}
				}
				if($clear_count > 0){//【新品预告】有变动是从新生成首页
					$this->make_index();
				}
				break;
			case 'yzcm':
				$recommend_type = Common_recommend_model::RECOMMEND_YZCM;
				$list = $this->common_recommend_model->get_yzcm(99999);
				break;
			case 'shuang11':
				// 双11普通推荐
				$recommend_type = Common_recommend_model::RECOMMEND_SHUANG11;
				$list = $this->common_recommend_model->get_shuang11(99999);
				break;
			case 'shuang11_custom':
				// 双11盟主推荐
				$recommend_type = Common_recommend_model::RECOMMEND_SHUANG11_CUSTOM;
				$list = $this->common_recommend_model->get_shuang11_custom(99999);
				break;
			case 'mpg':
				//名品馆推荐
				//搜索
				$so=array();
				$so['key'] = $this->get_post('mpg_key');
				$so['val'] = $this->get_post('mpg_val');
				$recommend_type = Common_recommend_model::RECOMMEND_MPG;
				$offset_mpg= $this->uri->segment(4);
				$limit=20;
				$count_mpg= $this->common_recommend_model->get_mpg_count($so);
				$list = $this->common_recommend_model->get_mpg($limit,$offset_mpg,$so);
				$page_conf_mpg = array('uri_segment'=>4,'anchor_class'=>'type="load" rel="div#main-wrap"');
				$page_conf_mpg['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment);
				$page_conf_mpg['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment.'/0');
				$pager_mpg = $this->pager($count_mpg, $limit, $page_conf_mpg);
				break;
			case 'fenqi':
				//众分期推荐(进行中)
				$recommend_type = Common_recommend_model::RECOMMEND_FENQI;
				$list = $this->common_recommend_model->goods($recommend_type, 99999);
				break;
			case 'fenqi_new':
				//众分期推荐(新品)
				$recommend_type = Common_recommend_model::RECOMMEND_FENQI_NEW;
				$list = $this->common_recommend_model->goods($recommend_type, 99999);
				break;
			default:
				//首页楼层分类商品推荐
				$recommend_type = Common_recommend_model::RECOMMEND_CATEGORY;
				$this->load->model('zhs_goods_category_model');
				// 商品分类
				$goods_categories = $this->zhs_goods_category_model->find_all_to_assembly();
				$params_type = explode('_', $type);
				$category =  isset($params_type[0]) ? $params_type[0] : '';
				$cat_type =  isset($params_type[1]) ? $params_type[1] : $goods_categories['parent']['0']['id'];
				$cid = isset($params_type[2]) ? $params_type[2] : $goods_categories['children'][$cat_type]['0']['id'];
				if('category' == $category && is_numeric($cid) && $cid > 0){
					$list = $this->common_recommend_model->get_category($cid, 99999);
				}
		}
		if($this->is_ajax() && $this->get_post('search_goods')){
			if($type=='mpg'){$list= $this->common_recommend_model->get_mpg_gid();}//查询总的名品馆活动数据gid
			$searchList = $this->search_goods($list);
			exit($searchList);
		}
		if($this->is_ajax() && $this->get_post('listonly')){
			if($type=='mpg'){
				$this->load->view('recommend/goods_list_mpg', get_defined_vars());
			}else{
				$this->load->view('recommend/goods_list', get_defined_vars());
			}
		}else{
			$this->load->view('recommend/goods', get_defined_vars());
		}
	}

	private function _goods_cates()
	{
		$this->load->model('goods_category_model');
		$goods_cates = array();
		foreach ($this->goods_category_model->get_all() as $k=>$v){
			$children = $v['children'];
			unset($v['children']);
			$goods_cates[$v['id']] = $v;
			if(count($children)>0){
				foreach ($children as $k=>$v){
					$goods_cates[$v['id']] = $v;
				}
			}
		}
		return $goods_cates;
	}

	/**
	 * 专题页面
	 */
	public function special()
	{
		$segment = $this->uri->segment(3);
		$id = intval($this->uri->segment(3));
		if(!$id){
			$this->error('专题不存在');
		}
		$goods_cates = $this->_goods_cates();
		$goods_status= $this->goods_status;

		$this->load->model('common_recommend_category_model');
		$special_category = $this->common_recommend_category_model->get_by_pid($id);
		$category = $special_category[0];
		$cate_id = intval($this->get_post('cate_id'));
		$cate_id = $cate_id ? $cate_id : intval($category['id']);
		$list = $this->common_recommend_model->get_special($id, $cate_id);
		if($this->is_ajax() && 'yes' == $this->get_post('listonly')){
			$this->load->view('recommend/special_list', get_defined_vars());
		}elseif($this->is_ajax() && 'item' == $this->get_post('listonly')){
			$this->load->view('recommend/special_item_list', get_defined_vars());
		}else{
			$this->load->view('recommend/special', get_defined_vars());
		}
	}

	/**
	 * 专题页面活动搜索
	 */
	public function special_search()
	{
		$this->load->model('admin_goods_model', 'goods_model');
		$segment = $this->uri->segment(3);
		$key = $this->get_post('search_key');
		$val = $this->get_post('search_val');
		$cate_id = intval($this->get_post('cate_id'));
		$ext_where = '';
		$list = $this->common_recommend_model->get_special($segment, $cate_id);
		$list_gids = array();
		if(count($list)){
			foreach($list as $k=>$v){
				$list_gids[] = $v['gid'];
			}
		}

		$limit = 10;
		$offset = $this->uri->segment(4);
		$goods_cates = $this->_goods_cates();
		$search_goods = $this->goods_model->search($key, $val, '', 0, 0, $ext_where, '', $limit, $offset);
		$total_count = $this->goods_model->search_count($key, $val, '', 0, 0, $ext_where);

		$page_conf = array('uri_segment'=>4,'anchor_class'=>'type="load" rel="div#special_search_'.$segment.'_'.$cate_id.'"');
		$page_conf['base_url'] = site_url($this->router->class . '/' . $this->router->method . '/' . $segment);
		$page_conf['first_url'] = site_url($this->router->class . '/' . $this->router->method . '/' . $segment . '/0');
		$pager = $this->pager($total_count, $limit, $page_conf);

		$this->load->view('recommend/special_search_list', get_defined_vars());
	}

	/**
	 * 搜索待推荐商品
	 */
	private function search_goods($list = array())
	{
		$this->load->model('admin_goods_model', 'goods_model');
		$segment = $this->uri->segment(3);
		$key = trim($this->get_post('search_key'));
		$val = trim($this->get_post('search_val'));
		$recommend_type = $this->get_post('recommend_type');
		$category = strval($this->get_post('category'));
		$cate_id = intval($this->get_post('cate_id'));
		$uri_string = $this->get_post('uri_string');
		$list_gids = array();
		$ext_where = '';
		if(count($list)){
			foreach($list as $k=>$v){
				$list_gids[] = $v['gid'];
			}
		}
		if($cate_id){
			$ext_where .= 'AND (pid = ' . $cate_id . ' OR cid = ' . $cate_id . ') ';
		}
		switch($segment){
			case 'advance':
				$ext_where .= 'AND '.$this->db->dbprefix('goods').'.state IN(' . Goods_model::STATUS_UNCHECK_PAID . ',' . Goods_model::STATUS_CHECKED . ') ';
				break;
			case 'new':
				$ext_where .= 'AND '.$this->db->dbprefix('goods').'.state NOT IN(' . Goods_model::STATUS_UNCHECK_UNPAY . ',' . Goods_model::STATUS_UNCHECK_PAYING . ') ';
				break;
			case 'fenqi':
				$ext_where .= 'AND '.$this->db->dbprefix('goods').'.type = ' . Goods_model::TYPE_STAGES . ' ';
				$ext_where .= 'AND '.$this->db->dbprefix('goods').'.state NOT IN(' . Goods_model::STATUS_UNCHECK_UNPAY . ',' . Goods_model::STATUS_UNCHECK_PAYING . ') ';
				break;
			case 'fenqi_new':
				$ext_where .= 'AND '.$this->db->dbprefix('goods').'.type = ' . Goods_model::TYPE_STAGES . ' ';
				$ext_where .= 'AND '.$this->db->dbprefix('goods').'.state IN(' . Goods_model::STATUS_UNCHECK_PAID . ',' . Goods_model::STATUS_CHECKED . ') ';
				break;
			default:
		}
		$ext_where = trim($ext_where, 'AND ');

		$limit = 10;
		$offset = $this->uri->segment(4);
		$_GET['search_goods'] = 'true';

		$search_goods = $this->goods_model->search($key, $val, '', 0, 0, $ext_where, '', $limit, $offset);
		$total_count = $this->goods_model->search_count($key, $val, '', 0, 0, $ext_where);

		$page_conf = array('uri_segment'=>4,'anchor_class'=>'type="load" rel="div#searchList"');
		$page_conf['base_url'] = site_url($this->router->class . '/' . $this->router->method . '/' . $segment);
		$page_conf['first_url'] = site_url($this->router->class . '/' . $this->router->method . '/' . $segment . '/0');
		$pager = $this->pager($total_count, $limit, $page_conf);

		return $this->load->view('recommend/search_goods', get_defined_vars(), TRUE);
	}

	/**
	 * 晒单达人推荐
	 */
	public function show_order()
	{
		$recommend_type = Common_recommend_model::RECOMMEND_SHOWORDER;
		$list = $this->common_recommend_model->get_show_order(9999);

		if($this->is_ajax() && $this->get_post('search_show')){
			exit($this->search_show($list));
		}

		if($this->is_ajax() && $this->get_post('listonly')){
			$this->load->view('recommend/show_list', get_defined_vars());
		}else{
			$this->load->view('recommend/show_order', get_defined_vars());
		}
	}

	/**
	 * 搜索晒单
	 * @param array $list
	 */
	private function search_show($list)
	{
		$this->load->model('admin_user_model', 'user_model');
		$search_key = $this->get_post('search_key');
		$search_val = $this->get_post('search_val');
		$recommend_type = $this->get_post('recommend_type');
		$uri_string = $this->get_post('uri_string');

		$limit = 10;
		$offset = $this->uri->segment(3);
		$_GET['search_show'] = 'yes';
		$recommended_shows = $this->common_recommend_model->get_show_order(9999);
		$recommended_ids = array();
		foreach($recommended_shows as $k=>$v){
			$v['id'] && $recommended_ids[] = $v['id'];
		}

		$search_list = $this->user_model->search_show($search_key, $search_val, $recommended_ids, '', '', $limit, $offset);
		$total_count = $this->user_model->search_show_count($search_key, $search_val, $recommended_ids);

		$page_conf = array('uri_segment'=>3,'anchor_class'=>'type="load" rel="div#searchList"');
		$page_conf['base_url'] = site_url($this->router->class . '/' . $this->router->method);
		$pager = $this->pager($total_count, $limit, $page_conf);

		return $this->load->view('recommend/search_show', get_defined_vars(), true);
	}

	/**
	 * 搜索用户
	 * @param unknown $list
	 * @return Ambigous <void, string>
	 */
	private function search_user($list)
	{
		$this->load->model('admin_user_model', 'user_model');
		$search_key = $this->get_post('search_key');
		$search_val = $this->get_post('search_val');
		$recommend_type = $this->get_post('recommend_type');
		$uri_string = $this->get_post('uri_string');

		$ext_where = '';
		if(count($list)){
			foreach($list as $k=>$v){
				$list_ids[] = $v['uid'];
			}
			$idstr = implode(',', $list_ids);
			$ext_where = 'uid NOT IN(' . $idstr . ')';
		}

		$limit = 10;
		$offset = $this->uri->segment(3);
		$_GET['search_user'] = 'yes';

		$search_list = $this->user_model->search($search_key, $search_val, '', $ext_where);
		$total_count = $this->user_model->search_count($search_key, $search_val, '', $ext_where);

		$page_conf = array('uri_segment'=>3,'anchor_class'=>'type="load" rel="div#searchList"');
		$page_conf['base_url'] = site_url($this->router->class . '/' . $this->router->method);
		$pager = $this->pager($total_count, $limit, $page_conf);

		return $this->load->view('recommend/search_user', get_defined_vars(), true);
	}

	/**
	 * 保存排序
	 */
	public function set_sort()
	{
		$map = array();
		foreach($_POST as $k=>$v){
			list($pre, $id) = explode('_', $k);
			if('id' == $pre){
				$map[$id] = $v;
			}
		}
		$rs = $this->common_recommend_model->set_sort($map);
		if($rs){
			$this->log('修改商品推荐排序成功');
			$this->success('排序成功');
		}else{
			$this->log('修改商品推荐排序失败');
			$this->error('排序失败');
		}
	}

	/**
	 * 删除推荐
	 */
	public function delete()
	{
		$id = $this->get_post('id');
		$rs = $this->common_recommend_model->delete($id);
		if($rs){
			$this->log('删除商品推荐成功');
			$this->success('删除成功');
		}else{
			$this->log('删除商品推荐失败');
			$this->error('删除失败');
		}
	}

	/**
	 * 删除指定推荐列表
	 */
	public function delete_all()
	{
		$id = $this->get_post('id');
		$type = $this->get_post('type');
		$category = $this->get_post('category');
		$this->db->select('id')->from('common_recommend');
		$chk = $this->db->where(array('id'=>$id,'type'=>$type,'category_id'=>$category))->get()->row_array();
		if($chk['id']){
			$this->db->delete('common_recommend', array('type'=>$type,'category_id'=>$category));
		}
		$this->success('操作成功');
	}

	/**
     * 生成主站首页
     */
    public function make_index()
    {
        $cache_path = COMPATH.'..'.DIRECTORY_SEPARATOR.'www.ylapp.com'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR;
        $uri = $this->config->item('domain_www');

        $filepath = $cache_path.md5($uri);
        
        if ( @file_exists($filepath))
        {
        	@unlink($filepath);
        }
        
        $filepath = $cache_path.md5($uri.'home/index');
        if ( @file_exists($filepath))
        {
        	@unlink($filepath);
        }
        
        //清除缓存，重新生成缓存
        file_get_contents($uri.'?fc');
        
    	//更新App首页
    	$domain_appsystem=config_item('domain_appsystem');
    	$domain_appsystem or show_error('请配置domain_appsystem');
    	$re = file_get_contents($domain_appsystem.'app/update_app_index');
    	if ($re != 'true'){
    		$this->error('app更新失败');
    	}
    	$this->is_ajax() && $this->success('更新成功');
    }

	/**
	 * 设置推荐
	 */
	public function set_recommend()
	{
		$recommend_type = $this->get_post('recommend_type');
		$targetid = $this->get_post('targetid');
		$category = $this->get_post('category');
		$cate_id = intval($this->get_post('cate_id'));
		$special_id = intval($this->get_post('special_id'));
		$rs =0;
		if($category){
			$rs = $this->common_recommend_model->set_category_goods($cate_id, $targetid);
			$content = '添加分类商品推荐';
		}elseif ($special_id){
			$rs = $this->common_recommend_model->set_special($special_id, $cate_id, $targetid);
			$content = '添加专题推荐';
		}elseif(!empty($recommend_type) && !empty($targetid) ){
			$rs = $this->common_recommend_model->set_recommend($recommend_type, $targetid);
			$content = '添加推荐';
		}
		if($rs){
			$this->log($content . '成功');
			$this->success('推荐成功');
		}else{
			$this->log($content . '失败');
			$this->error('推荐失败');
		}
	}
	
	/**
	 * 批量插入专题推荐的活动(主要用于专题推荐)
	 */
	public function batch_push_special(){
		$type_id = intval($this->get_post('type_id')); // 类型id
		$cate_id = intval($this->get_post('cate_id')); // 场次id
		$type_id OR $this->error('请求缺少参数：type_id');
		$cate_id OR $this->error('请求缺少参数：cate_id');
		$recommend_category = $this->db->select('id, name')->from('common_recommend_category')->where_in('id',array($type_id, $cate_id))->get()->result_array();
		count($recommend_category) <> 2 AND $this->error('数据有变更，请刷新页面！');
		if ($this->get_post('ispost')) {
			// 批量推送处理
			$content = trim($this->get_post('content')); // 原始提交的gid字符串
			$content OR $this->error('请输入要推荐活动编号！');
			
			$gids = $this->explode_gid ( $content );
 
			count($gids)>100 AND $this->error('批量推荐每次不允许超过100条,请分多次推送！');
			
			$batch_data = $this->batch_push_data($gids, $type_id, $cate_id);
			
			$this->db->trans_start();
			$this->db->insert_batch('shs_common_recommend', $batch_data);
			$this->db->trans_complete();
			
			$this->success('批量推送成功！');
		}else{
			// 批量推送的窗口
			$special_name ='';
			$cate_name = '';
			foreach ($recommend_category as $k=>$v){
				if($type_id == $v['id']){
					$special_name = $v['name'];
				}
				if($cate_id == $v['id']){
					$cate_name = $v['name'];
				}
			}
			$crumbs = $special_name . '&nbsp;>&nbsp;' . $cate_name;
			$action = 'recommend/batch_push_special';
			$this->load->view('recommend/batch_push_goods', get_defined_vars());
		}
	}
	
	/**
	 * 批量取消专题推荐的活动(主要用于专题)
	 */
	public function batch_cancel_special(){
		$type_id = intval($this->get_post('type_id')); // 类型id
		$cate_id = intval($this->get_post('cate_id')); // 场次id
		$type_id OR $this->error('请求缺少参数：type_id');
		$cate_id OR $this->error('请求缺少参数：cate_id');
		$recommend_category = $this->db->select('id, name')->from('common_recommend_category')->where_in('id',array($type_id, $cate_id))->get()->result_array();
		count($recommend_category) <> 2 AND $this->error('数据有变更，请刷新页面！');
		if ($this->get_post('ispost')) {
			// 批量取消处理
			$content = trim($this->get_post('content')); // 原始提交的gid字符串
			$content OR $this->error('请输入要取消活动编号！');
			$gids = $this->explode_gid ( $content );
	
			count($gids)>100 AND $this->error('批量取消每次不允许超过100条,请分多次取消！');
			$this->db->where(array('type'=>$type_id,'category_id'=>$cate_id))->where_in('target_id',$gids);
			$this->db->delete('shs_common_recommend');
	        if($this->db->affected_rows())
			   $this->success('批量取消成功！');
	        else 
	        	$this->success('批量取消失败！');
		}else{
			// 批量取消的窗口
			$special_name ='';
			$cate_name = '';
			foreach ($recommend_category as $k=>$v){
				if($type_id == $v['id']){
					$special_name = $v['name'];
				}
				if($cate_id == $v['id']){
					$cate_name = $v['name'];
				}
			}
			$crumbs = $special_name . '&nbsp;>&nbsp;' . $cate_name;
			$action = 'recommend/batch_cancel_special';
			$this->load->view('recommend/batch_cancel_goods', get_defined_vars());
		}
	}
	
	/**
	 * 批量推送推荐商品、新品上线（主要用于首页）
	 */
	public function batch_push_goods(){

		$type_id = intval($this->get_post('type_id'), 0); // 类型id
		$cate_id = intval($this->get_post('cate_id'), 0); // 场次id
		$type_id OR $this->error('请求缺少参数：type_id');
		
		$type_map = array('1'=>'最新上线','2'=>'新品预告','6'=>'分类推荐');
		if ($this->get_post('ispost')) {
			// 批量推送处理
			$content = trim($this->get_post('content')); // 原始提交的gid字符串
			$content OR $this->error('请输入要推荐活动编号！');
			$gids = $this->explode_gid ( $content );
			
			$batch_data = array();
			switch ($type_id){
				case 1:
				case 2:
					// 最新上线/新品预告
					$batch_data = $this->batch_push_data($gids, $type_id, $cate_id);
					break;
				case 6:
					//分类推荐'
					$cate_id OR $this->error('请求缺少参数：cate_id');
					//判断推荐成功后总个数不超过7个
					$all_count = $this->db->from('common_recommend')->where('type',$type_id)->where('category_id',$cate_id)->count_all_results();
					if($all_count + count($gids) > 10) $this->error('总推荐个数不能超过10个');
					$batch_data = $this->batch_push_data($gids, $type_id, $cate_id);
					break;
				default:$this->error('未知的推送类型:'.$type_id);
			}
			
			$this->db->trans_start();
			$this->db->insert_batch('shs_common_recommend', $batch_data);
			$this->db->trans_complete();
				
			$this->success('批量推送成功！');
		}else{
			// 显示
			$crumbs = $type_map[$type_id]; // 面包屑导航
			 
			$action = 'recommend/batch_push_goods';
			$this->load->view('recommend/batch_push_goods', get_defined_vars());
		}
	}
	
	/**
	 * 批量插入数据的整理
	 * @param unknown $gids
	 * @param unknown $type_id
	 * @param unknown $cate_id
	 * @return multitype:multitype:number unknown
	 */
	private function batch_push_data($gids, $type_id, $cate_id){
		$db_recommend_goods = array();
		foreach ($this->db->select('target_id')->from('common_recommend')->where('type',$type_id)->where('category_id',$cate_id)->get()->result_array() as $tiem){
			$db_recommend_goods[] = $tiem['target_id'];
		}
		
		//查找重复
		$intersect_recommend_goods = array_intersect($gids, $db_recommend_goods);
		$intersect_recommend_goods AND $this->error('活动编号重复推荐：'. implode(',', $intersect_recommend_goods));
		
		//排序值，从当前的最大值自增
		$max_sort = $this->db->select('max(sort) as max_sort')->from('shs_common_recommend')->where('type',$type_id)->where('category_id',$cate_id)->get()->row_array();
		$sort = $max_sort['max_sort'];
		
		// 批量插入数据
		$batch_data = array();
		foreach ($gids as $gid){
			$sort ++;
			$batch_data[] = array('type'=>$type_id, 'category_id'=> $cate_id, 'target_id'=>$gid, 'dateline'=>time(), 'starttime'=>0, 'endtime'=>0,'sort'=>$sort);
		}
		return $batch_data;
	}
	
	/**
	 * 字符串gid解析成数组
	 *  1,2,3,4,5...  ->   array(1,2,3,4,5);
	 * @param content
	 * @return array();
	 */
	 private function explode_gid($content) {
		 // 去空格
		$content = str_replace(' ', '', $content);
		// 替换中文逗号为英文逗号
		$content = str_replace('，', ',', $content);
		
		// 如果推送单个活动，在字符后面拼接逗号
		$content = strpos($content, ',') ? $content.',' : $content;
		
		// 去除无效元素
		$gids = array();
		foreach (explode(',', $content) as $tiem){
			$gid = intval($tiem);
			if($gid>0){
				$gids[] = $gid;
			}
		}
		//去重复
		$gids = array_unique($gids);
		count($gids) OR $this->error('请输入活动编号！');
		
		// 查找不存在的活动编号
		$db_goods = array();
		foreach ($this->db->select('gid')->from('goods')->where_in('gid', $gids)->get()->result_array() as $item){
			$db_goods [] = $item['gid'];
		}
		$diff_goods = array_diff($gids, $db_goods);
		count($diff_goods) AND $this->error('输入了不存在的活动编号：'. implode(',', $diff_goods));
		
		return $gids;
	}
	
}
// End of class Recommend

/* End of file recommend.php */
/* Location: ./application/controllers/recommend.php */