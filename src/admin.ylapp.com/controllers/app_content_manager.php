<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *APP 内容管理控制器
 *@property app_advertisement_model  $app_advertisement_model
 *@property goods_search_keyword_model $goods_search_keyword_model
 */

class App_content_manager extends MY_Controller
{
	/**
	 * @var 模块ID跳转类型
	 */
	private $_type; 
	
	public function __construct(){
		parent::__construct();
		$this->load->model(array('app_advertisement_model','app_special_model'));	
		$this->load->model('goods_search_keyword_model');
		//跳转模块id
		$this->_type = array (
				// '1001' => 'URL',
				// '1010' => '类目',
				// '1003' => '最新上线',
				// '1002' => '一站成名',
				'1006' => '商品列表',
				'1004' => '商品详情',
				// '1007' => '抢购提醒',
				// '1008' => '我的订单',
				// '1005' => '专场' ,
				'1011' => '搜索商家'
		);
	}
	
	public function index()
	{
		$this->show_keyword();
	}
	
	/**
	 * 所有关键字列表
	 */
	public function show_keyword()
	{	
 		$key=$this->get_post('search_key','');
		$offset = intval($this->uri->segment(4));
		$segment = $this->uri->segment(3);
		$segment='show_keyword';
		$size=10;
		$all_keywords=$this->goods_search_keyword_model->get_keyword($key,$size,$offset);
		$total=$all_keywords['count'];
		//分页
		$page_conf = array('uri_segment'=>4,'anchor_class'=>'type="load" rel="div#main-wrap"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment);
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment.'/0');
		$pager = $this->pager($total, $size, $page_conf);
		$this->load->view('app/search_keyword_manager',get_defined_vars());
	}
		
	/**
	 * 新增关键字
	 */
	public function keyword_add()
	{	
		$this->load->view('app/keyword_add');
	}
	
	public function add()
	{
		$keyword=$this->input->post('keyword');
		$sort_val=$this->input->post('sort_val');
		$is_check=$this->goods_search_keyword_model->check($keyword);
		$max_sort=$this->goods_search_keyword_model->max_sort();
		$sort=$max_sort['sort']+1;
		if($is_check>0)
		{
			$message="关键字已存在";
			$this->error($message);
		}else
		{
			$this->goods_search_keyword_model->add($keyword,$sort_val,$sort);
			$message="添加成功";
			$this->success($message);
	    }
	}
	
	/**
	 * 手动排序
	 * @access public
	 * @return void
	 */
	public function sort() {
		
		$sorts = $this->input->post('sorts', TRUE);  
		
		if ($sorts) {
			$this->goods_search_keyword_model->update_sort_val($sorts);
		}		
		$this->success('排序成功');
	}
	
	/**
	 * 导出关键字列表
	 */
	public function export_keyword()
	{
		$keyword=$this->input->get_post('search_key','');
		$list=$this->goods_search_keyword_model->export($keyword);
		$data=array();
		foreach ($list as $k=>$v)
		{
			$data[]=array(
				'id'=>$v['id'],
				'keyword'=>$v['keyword'],
				'search_num'=>$v['search_num'],
				'sort'=>$v['sort'],
				'sort_change'=>$v['sort_change'],
				'sort_val'=>$v['sort_val'],
				'web'=>$v['web_count'],
				'iOS'=>$v['ios_count'],
				'Android'=>$v['android_count']
					);
		}	
		$title = '搜索关键字管理';
		$filename = $title.'.xls';
		$header = array(
				'序号',
				'关键字',
				'搜索次数',
				'排名',
				'排名变化',
				'排序值',
				'web',
				'iOS',
				'Android'
		);
		array_unshift($data, $header);
		$this->data_export($data, $title, $filename);
	}
	
	/**
	 * banner和首页快捷入口广告列表
	 */
	public function show_advertisement()
	{
		$segment = $this->uri->segment(3);
		switch ($segment){
			case 'banner':
				$differ = 1;
				break;
			case 'quick':
				$differ = 2;
				break;
			default:
				$this->error('未知类型');
		}
		$limit = 10;
		$segment = $segment ? $segment : 1;
		$offset = $this->uri->segment(4);
		$title=$this->get_post('title');
		$stime=strtotime($this->get_post('startTime'));
		$etime=strtotime($this->get_post('endTime'));
		$type=$this->_type;
		$bannerlist=$this->app_advertisement_model->get_search_adv($title,$stime,$etime,$differ,$limit,$offset);
		$bannercount=$this->app_advertisement_model->get_search_adv_count($title,$stime,$etime,$differ);
		//分页
		$page_conf = array('uri_segment'=>4,'anchor_class'=>'type="load" rel="div#main-wrap"');
	    $page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment);
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment.'/0');
		$pager = $this->pager($bannercount, $limit, $page_conf);

		$this->load->view('app/show_advertisement',get_defined_vars());
	}
	
	/**
	 * 添加banner和首页快捷入口广告列表
	 */
	public function add_advertisement()
	{
		if($this->get_post('dopost')){
			
			$data['title'] = strval($this->get_post('title'));
			$data['differ'] = intval($this->get_post('differ'));
			 $is_check=$this->app_advertisement_model->is_check($data['title'] ,$data['differ'] );
			 if($is_check >0){
			 	$this->error('标题存在了');
			 }

			$data['sort'] = intval($this->get_post('sort'));
			$data['type'] = intval($this->get_post('type'));
			$data['value'] = strval($this->get_post('value'));
			$data['enable'] = intval($this->get_post('enable'));
			$data['dateline'] = time();
			$pid= intval($this->get_post('pid'));
			$cid= intval($this->get_post('cid'));
			if( in_array($data['type'], array(1001,1004,1005,1006,1011))){
			   if(!$data['value']){
				    $this->error('跳转值不能为空！');
				 }
			}else if( $data['type']==1010 ){
				$data['value']=$cid>0?$cid:$pid;
				if(!$data['value']){
					$this->error('跳转类目为类目必须选择跳转的分类！');
				}				
			}
			if($_FILES['images']['tmp_name'] && $_FILES['images']['error'] == 0 && $_FILES['images']['size'] > 0){
				if($_FILES['images']['size']>500*1024)
				{
					$this->error('上传图片大小不能超过500KB');
				}
				$this->load->library('upload_image');
				$imgurl = $this->upload_image->save('dimg', $_FILES['images']['tmp_name'],null,true);
				if($imgurl){
					$data['images'] = array_shift($this->config->item('image_servers')).$imgurl;
				}else{
					$this->error('保存上传图片失败');
				}
			}
			if(!isset($data['images'])){
				$this->error('请上传图片');
			}
			$this->db->insert('app_advertisement', $data);
			$this->log('添加APP广告成功');
			$this->success('添加成功！');	
		}
		$type=$this->_type;
		$differ = intval($this->get_post('differ'));
		//查询所有的类目
		$this->load->model('goods_category_model');
		$categoryInfo = $this->goods_category_model->get_all();
		$allcCategory = $this->init_category($categoryInfo);
		$pid_arr= json_encode($allcCategory['parent']);
		$child_arr=json_encode($allcCategory['child']);
		//查询类目结束
		$this->load->view('app/add_advertisement',get_defined_vars());
	}
	
	/**
	 * 编辑banner和首页快捷入口广告
	 */
	public function edit_advertisement(){
		
		$id=$this->get_post('id');
		if(!$id){
			$this->error('找不到记录！');
		}
		if($this->get_post('editpost')){
			$data['title'] = strval($this->get_post('title'));
			$data['differ'] = intval($this->get_post('differ'));
			$is_check=$this->app_advertisement_model->is_check($data['title'] ,$data['differ'] ,$id);
			if($is_check >0){
				$this->error('标题存在了');
			}
			$data['sort'] = intval($this->get_post('sort'));
			$data['type'] = intval($this->get_post('type'));
			$data['value'] = strval($this->get_post('value'));
			$data['enable'] = intval($this->get_post('enable'));
			$data['dateline'] = time();
			$pid= intval($this->get_post('pid'));
			$cid= intval($this->get_post('cid'));
			if( in_array($data['type'], array(1001,1004,1005,1006,1011))){
				if(!$data['value']){
					$this->error('跳转值不能为空！');
				}
			}else if( $data['type']==1010 ){
				$data['value']=$cid>0?$cid:$pid;
				if(!$data['value']){
					$this->error('跳转类目为类目必须选择跳转的分类！');
				}
			}
			if (isset ( $_FILES ['images'] )) {
				if ($_FILES ['images'] ['tmp_name'] && $_FILES ['images'] ['error'] == 0 && $_FILES ['images'] ['size'] > 0) {
					if($_FILES['images']['size']>500*1024)
					{
						$this->error('上传图片大小不能超过500KB');
					}
					$this->load->library ( 'upload_image' );
					$imgurl = $this->upload_image->save ( 'dimg', $_FILES ['images'] ['tmp_name'], null, true );
					if ($imgurl) {
						$data ['images'] = array_shift ( $this->config->item ( 'image_servers' ) ) . $imgurl;
					} else {
						$this->error ( '保存上传图片失败' );
					}
				}
			}
			$this->db->where('id', $id);
			$this->db->update('app_advertisement', $data);
			$this->log('编辑APP广告成功');
			$this->success('编辑成功！');
		}
		$banner=$this->app_advertisement_model->get_adv_data($id);
		//当跳转类型为类目查询选择的类目
		if($banner['type']==1010){ 
			$cid=intval($banner['value']);
			$goods_category=$this->db->select('pid')->where('id',$cid)->from('goods_category')->get()->row_array();
			if($goods_category['pid']){
				$pid=$goods_category['pid'];
			}else{
				$pid=$cid;
				$cid=0;
			}
		} //echo 'eee';exit;
		$type=$this->_type;
		//查询所有的类目
		$this->load->model('goods_category_model');
		$categoryInfo = $this->goods_category_model->get_all();
		$allcCategory = $this->init_category($categoryInfo);
		$pid_arr= json_encode($allcCategory['parent']);
		$child_arr=json_encode($allcCategory['child']);
		//查询类目结束
		$this->load->view('app/edit_advertisement',get_defined_vars());
	}
	
	/**
	 * 隐藏和显示广告
	 */
	public function hide_advertisement(){
		$id = $this->get_post ( 'id' );
		$enable = $this->get_post ( 'enable' );
		if ($id <= 0) {
			$this->error ( '找不到该记录！' );
		}
		if ($enable == 1) {
			$enable = 2;
		} elseif ($enable == 2) {
			$enable = 1;
		} else {
			$this->error ( '出错了！' );
		}
		$this->db->update ( 'app_advertisement', array ('enable' => $enable), array ('id' => $id) );
		$this->log('修改APP广告停用启用成功');
		$this->success ( '操作成功！' );
	}
	
	/**
	 * 保存排序
	 */
	public function save_adv_sort()
	{
		$map = array();
		foreach($_POST as $k=>$v){
			list($pre, $id) = explode('_', $k);
			if('id' == $pre){
				$map[$id] = $v;
			}
		}
		if(!is_array($map)){return FALSE;}
		foreach($map as $id=>$sort){
			$batch[]=array('id'=>$id,'sort'=>$sort);
		}
	    	$this->db->update_batch('app_advertisement',$batch,'id');
			$this->log('修改APP广告排序成功');
			$this->success('排序成功');
	}
	

	/**
	 * app专题管理
	 */
	public function show_special(){
		
		$limit = 10;
		$segment = $this->uri->segment(3);
		$segment = $segment ? $segment : 1;
		$offset = $this->uri->segment(4);
		$title=$this->get_post('title');
		$stime=strtotime($this->get_post('startTime'));
		$etime=strtotime($this->get_post('endTime'));
		$special=$this->app_special_model->get_search_special($title,$stime,$etime,$limit,$offset);
		$gids=array();
        foreach ($special as $k=>$v){
     	$gids[]=$v['gid1'];
     	$gids[]=$v['gid2'];
     	$gids[]=$v['gid3'];
        }
        $goods_title=$goods_img=array();
        if(count($gids)>0){
        	$this->load->helper(array('image_url'));
        	  $goods=$this->db->select('gid,title,img')->from('goods')->where_in('gid',$gids)->get()->result_array();
	       foreach ($goods as $val){
	        $goods_title[$val['gid']]=$val['title']?$val['title']:'';
	        $goods_img[$val['gid']] = image_url($val['gid'], $val['img']);
	       }
         } 
		$specialcount=$this->app_special_model->get_search_special_count($title,$stime,$etime);
		//分页
		$page_conf = array('uri_segment'=>4,'anchor_class'=>'type="load" rel="div#main-wrap"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment);
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment.'/0');
		$pager = $this->pager($specialcount, $limit, $page_conf);
		
		$this->load->view('app/show_special',get_defined_vars());
	}

	/**
	 * app专题管理增加专题
	 */
	public function add_special(){
		
		if($this->get_post('addpost')){
			
			$data['title'] = strval($this->get_post('title'));
			$is_check=$this->app_special_model->is_check($data['title']);
			if($is_check >0){
				$this->error('标题存在了');
			}
			$data['gid1'] = intval($this->get_post('gid1'));
			if(!$data['gid1'] ){
				$this->error('请输入关联gid1');
			}
			$data['gid2'] = intval($this->get_post('gid2'));
			if(!$data['gid2'] ){
				$this->error('请输入关联gid2');
			}
			$data['gid3'] = intval($this->get_post('gid3'));
			if(!$data['gid3'] ){
				$this->error('请输入关联gid3');
			}
			$data['special_id'] = intval($this->get_post('special_id'));
			if(!$data['special_id'] ){
				$this->error('请输入专场id');
			}
			$this->load->library('upload_image');
			if($_FILES['img']['tmp_name'] && $_FILES['img']['error'] == 0 && $_FILES['img']['size'] > 0){
				$imgurl = $this->upload_image->save('dimg', $_FILES['img']['tmp_name'],null,true);
				if($imgurl){
					$data['img'] = array_shift($this->config->item('image_servers')).$imgurl;
				}else{
					$this->error('保存上传专场广告图片失败');
				}
			}
			if(!isset($data['img'])){
				$this->error('请上传专场主打列表图片');
			}
			$data['sort'] = intval($this->get_post('sort'));
			$data['enable'] = intval($this->get_post('enable'));
			$data['dateline'] = time();
			$this->db->insert('app_special', $data);
			$this->log('添加APP专场成功');
			$this->success('添加成功！');
		}
		$this->load->view('app/add_special',get_defined_vars());
	}

	/**
	 * app专题管理编辑专题
	 */
	public function edit_special(){
		$id=$this->get_post('id');
		if(!$id){
			$this->error('找不到记录！');
		} 
		
		if($this->get_post('editpost')){
			
			$data['title'] = strval($this->get_post('title'));
			$is_check=$this->app_special_model->is_check($data['title'],$id);
			if($is_check >0){
				$this->error('标题存在了');
			}
			$data['gid1'] = intval($this->get_post('gid1'));
			if(!$data['gid1'] ){
				$this->error('请输入关联gid1');
			}
			$data['gid2'] = intval($this->get_post('gid2'));
			if(!$data['gid2'] ){
				$this->error('请输入关联gid2');
			}
			$data['gid3'] = intval($this->get_post('gid3'));
			if(!$data['gid3'] ){
				$this->error('请输入关联gid3');
			}
			$data['special_id'] = intval($this->get_post('special_id'));
			if(!$data['special_id'] ){
				$this->error('请输入专场id');
			}
			if (isset ( $_FILES ['img'] )) {
				$this->load->library('upload_image');
				if ($_FILES ['img'] ['tmp_name'] && $_FILES ['img'] ['error'] == 0 && $_FILES ['img'] ['size'] > 0) {
					$imgurl = $this->upload_image->save ( 'dimg', $_FILES ['img'] ['tmp_name'], null, true );
					if ($imgurl) {
						$data ['img'] = array_shift ( $this->config->item ( 'image_servers' ) ) . $imgurl;
					} else {
						$this->error ( '保存上传专场广告图片失败' );
					}
				}
			}
			$data['sort'] = intval($this->get_post('sort'));
			$data['enable'] = intval($this->get_post('enable'));
			$data['dateline'] = time();
			$this->db->update('app_special', $data,array('id'=>$id));
			$this->log('编辑APP专场成功');
			$this->success('编辑成功！');	
		}
		$special=$this->app_special_model->get_special_data($id);
		$this->load->view('app/edit_special',get_defined_vars());
	}
	
	/**
	 * 隐藏和显示专场记录
	 */
	public function hide_special(){
		$id = $this->get_post ( 'id' );
		$enable = $this->get_post ( 'enable' );
		if ($id <= 0) {
			$this->error ( '找不到该记录！' );
		}
		if ($enable == 1) {
			$enable = 2;
		} elseif ($enable == 2) {
			$enable = 1;
		} else {
			$this->error ( '出错了！' );
		}
		$this->db->update ( 'app_special', array ('enable' => $enable), array ('id' => $id) );
		$this->log('修改APP专场隐藏显示成功');
		$this->success ( '操作成功！' );
	}
	
	/**
	 * 保存专场排序
	 */
	public function save_special_sort()
	{
		$map = array();
		foreach($_POST as $k=>$v){
			list($pre, $id) = explode('_', $k);
			if('id' == $pre){
				$map[$id] = $v;
			}
		}
		if(!is_array($map)){return FALSE;}
		foreach($map as $id=>$sort){
			$batch[]=array('id'=>$id,'sort'=>$sort);
		}
		$this->db->update_batch('app_special',$batch,'id');
		$this->log('修改APP专场排序成功');
		$this->success('排序成功');
	}
	
	
	/**
	 * app跳转类目初始化分类数据
	 * @param array $category
	 * @return array('parent'=>array, 'child'=>array)
	 */
	private function init_category($category){
		$return = array('parent' => array(), 'child' => array());
		if(is_array($category)){
			foreach ($category as $item) {
				unset($item['children']['sort']);
				$child['k_'.$item['id']] = $item['children'];
				unset($item['children']);
				unset($item['pid']);
				unset($item['sort']);
				$parent[$item['id']] = $item;
			}
			$return['parent'] = $parent;
			foreach ($child as $k=>$value) {
				foreach ($value as $kk=>$val) {
					unset($child[$k][$kk]);
					$child[$k][$val['id']] = $val;
				}
			}
			$return['child'] = $child;
		}
		return $return;
	}
	
	/**
	 * 显示启动页广告列表
	 */
	public function start_advertisement()
	{
		$offset = intval($this->uri->segment(4));
		$segment = $this->uri->segment(3);
		$segment='start_advertisement';
		$size=10;
 		$data=$this->app_advertisement_model->get_start_advertisement($size,$offset);
		$total=$data['count'];
		//分页
		$page_conf = array('uri_segment'=>4,'anchor_class'=>'type="load" rel="div#main-wrap"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment);
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment.'/0');
		$pager = $this->pager($total, $size, $page_conf);
		$this->load->view('app/start_page_advertisement',get_defined_vars());
	}
	
	/**
	 *  添加启动页广告
	 */
	public function add_start_page()
	{
		if($this->get_post('addpost')){
		$data['title'] = $this->get_post('title','');
		$data['differ'] = 3; //启动页广告类型
		$data['dateline'] = time();
		$data['enable'] = 2;
		if($_FILES['images']['tmp_name'] && $_FILES['images']['error'] == 0 && $_FILES['images']['size'] > 0){
			if($_FILES['images']['size']>500*1024)
			{
				$this->error('上传图片大小不能超过500KB');
			}
			$this->load->library('upload_image');
			$imgurl = $this->upload_image->save('dimg', $_FILES['images']['tmp_name'],null,true);
			if($imgurl){
				$data['images'] = array_shift($this->config->item('image_servers')).$imgurl;
			}else{
				$this->error('保存上传图片失败');
			}
		}
		if(!isset($data['images'])){
			$this->error('请上传图片');
			}
		
		$this->db->insert('app_advertisement', $data);
		$this->log('添加启动页广告成功');
		$this->success('添加成功！');
		}
		$this->load->view('app/add_start_page',get_defined_vars());	
	}
}