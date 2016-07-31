<?php
/**
 * 帮助中心管理 控制器类
 * @author 邓元翔
 * @version 13.12.11
 */
class Help extends MY_Controller
{
	
	public function __construct(){
		parent::__construct();
		//加载数据模型
		$this->load->model('admin_help_category_model', 'help_category_model');	//帮助分类
		$this->load->model('admin_help_model', 'help_model');	//帮助列表
		$this->load->model('admin_help_hot_search_model', 'help_search_model');	//热门搜索
	}
	
	/*——————————————————————————————————帮助列表————————————————————————————————————————————*/
	
	/**
	 * 帮助列表：买家|商家(默认 买家)
	 */
	public function listing(){
		$type_url = $this->uri->segment(3);	//获取参数：buyer|seller
		$type = '';
		
		//判断uri请求参数：1买家、2商家
		switch ($type_url){
			case 'buyer':
				$type = 1;
				break;
			case 'seller':
				$type = 2;
				break;
		}
		//页签状态：1列表、2添加、3编辑、4常见问题
		$tag_type = intval($this->get_post('tag_type'))<=1 ? 1 : intval($this->get_post('tag_type'));
		//根据请求类型调用列表页面
		$this->_listing($type_url, $type, $tag_type);
	}
	
	/**
	 * 返回帮助列表页面
	 * @param string $type_url 用户类型url段 'buyer'买家、'seller'商家
	 * @param int $type 用户请求类型（1买家、2商家）
	 */
	private function _listing($type_url, $type, $tag_type){
		$pid = 0;
		if($tag_type === 1){
			$cate_parents = $this->help_category_model->get_by_pid($type);	//返回主分类-分类下拉框
			
			/*帮助列表*/
			$offset = intval($this->uri->segment(4));	//起始记录下标
			$limit = 10;
			$contents = $this->help_model->get_by_type($type, $limit, $offset);
			// 分页相关
			$list_count = $this->help_model->list_count($type);
			$page_conf = array('uri_segment'=>4, 'anchor_class'=>'type="load" rel="div#main-wrap"');
			$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$type_url);
			$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$type_url.'/0');
			$pager = $this->pager($list_count, $limit, $page_conf);
			
			/*添加预览地址*/
			foreach ($contents as $k=>$val){
				//链接请求格式：帮助中心域名/控制器/方法/父类型id/子类型id/帮助id
				$contents[$k]['link'] = $this->config->item('domain_help').$type_url.'/category/'.$val['pid'].'/'.$val['cid'].'/'.$val['id'].'?preview=1';
			}
			
			$selected = 'yes';	//是否为当前页
			$this->load->view('help/list', get_defined_vars());
		}elseif ($tag_type === 2){
			$cate_parents = $this->help_category_model->get_by_pid($type);	//返回主分类-分类下拉框
			
			$selected = 'yes';	//是否为当前页
			$this->load->view('help/list', get_defined_vars());
		}elseif ($tag_type === 3){
			$id = $this->get_post('id');	//帮助表id
			
			$rs = $this->help_model->get_by_id($id);	//帮助记录
			$cate_parents = $this->help_category_model->get_by_pid($type);	//返回主分类-分类下拉框
			$cate_childs = $this->help_category_model->get_by_pid($type, $rs['pid']);	//返回子分类-分类下拉框
			
			$selected = 'yes';	//是否为当前页
			$this->load->view('help/list', get_defined_vars());
		}elseif ($tag_type === 4){
			/*常见问题*/
			$offset = intval($this->uri->segment(4));	//起始记录下标
			$limit = 10;
			$questions = $this->help_model->get_push_by_type($type, $limit, $offset);
			// 分页相关
			$list_count = $this->help_model->get_push_count_by_type($type);
			$page_conf = array('uri_segment'=>4, 'anchor_class'=>'type="load" rel="div#main-wrap"');
			$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$type_url);
			$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$type_url.'/0');
			$pager = $this->pager($list_count, $limit, $page_conf);
			
			foreach ($questions as $k=>$val){
				//链接请求格式：帮助中心域名/控制器/方法/父类型id/子类型id/帮助id
				$questions[$k]['link'] = $this->config->item('domain_help').$type_url.'/category/'.$val['pid'].'/'.$val['cid'].'/'.$val['id'].'?preview=1';
			}
			$selected = 'yes';	//是否为当前页
			
			$this->load->view('help/list', get_defined_vars());
		}
		
	}
	
	/**
	 * 添加帮助-分类回调
	 * @todo 根据用户类型和主分类id返回所有子分类
	 */
	public function callback_child_cate(){
		$type = $this->get_post('type');	//用户请求类型
		$pid = $this->get_post('pid');	//主分类id
		$cate_childs = $this->help_category_model->get_by_pid($type, $pid);
		
		$cate_childs_html = '';
		foreach ($cate_childs as $cate){
			$cate_childs_html .= "<option value='{$cate['id']}'>{$cate['name']}</option>";
		}
		
		echo $cate_childs_html;
	}
	
	/**
	 * 帮助列表-搜索
	 */
	public function listing_search(){
		$type_url = $this->uri->segment(3);	//获取参数：buyer|seller
		$type = '';
		
		//判断uri请求参数：1买家、2商家
		switch ($type_url){
			case 'buyer':
				$type = 1;
				break;
			case 'seller':
				$type = 2;
				break;
		}
		$tag_type = 1;
		
		$pid = $this->get_post('pid');	//主类型id，搜索时获取
		$cid = $this->get_post('cid');	//子类型id，搜索时获取
		$search_title = trim($this->get_post('search_title'));	//搜索字段，搜索时获取
		
		$cate_parents = $this->help_category_model->get_by_pid($type);	//返回主分类-分类表下拉框
		$cate_childs = $this->help_category_model->get_by_pid($type, $pid);
		
		$offset = $this->uri->segment(4);	//起始记录下标
		$limit = 10;
		$contents = $this->help_model->search($type, $pid, $cid, $search_title, $limit, $offset);	//返回用户记录-帮助表，无搜索操作时使用默认值
		// 分页相关
		$list_count = $this->help_model->list_count($type, $pid, $cid);
		$page_conf = array('uri_segment'=>4, 'anchor_class'=>'type="load" rel="div#main-wrap"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$type_url);
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$type_url.'/0');
		$pager = $this->pager($list_count, $limit, $page_conf);
		/*常见问题*/
		$questions = $this->help_model->get_push_by_type($type);
		
		/*添加预览地址*/
		foreach ($contents as $k=>$val){
			//链接请求格式：帮助中心域名/控制器/方法/父类型id/子类型id/帮助id
			$contents[$k]['link'] = $this->config->item('domain_help').$type_url.'/category/'.$val['pid'].'/'.$val['cid'].'/'.$val['id'].'?preview=1';
		}
			
		$selected = 'yes';	//是否为当前页
		$this->load->view('help/list', get_defined_vars());
		
	}
	
	/**
	 * 帮助列表 增|删|改 请求路由 :(add|edit|delete)
	 */
	public function listing_action(){
		$action = $this->uri->segment(3);	//操作类型：add|edit|delete
		
		if($action === 'add'){
			$type_url = $this->uri->segment(4);
			$type = $this->uri->segment(5);	//用户类型：1买家、2商家
			//添加记录
			$data = array(
				'type' => $type,
				'pid' => $this->get_post('pid'),
				'cid' => $this->get_post('cid'),
				'title' => trim($this->get_post('title')),
				'tag' => trim($this->get_post('tag')),
				'content' => $this->get_post('content'),
				'summarize' => $this->get_post('summarize'),
				'dateline' => time()
			);
			//获取图片id数组
			$imgid_arr;	//存储图片id
			if(preg_match('/<img[ ]+src="([^\?]+)\.jpg\?id=(\d+)"[^>]+>/', $data['content'])){
				preg_match_all('/<img[ ]+src="([^\?]+)\.jpg\?id=(\d+)"[^>]+>/', $data['content'], $imgid_arr);	//imgid_arr[2] id数组
			}
			$rs = $this->help_model->add($data);	//添加帮助信息，返回添加的行号，失败返回0

			//根据图片id更新图片表中对应的帮助id
			if(isset($imgid_arr[2]) && is_array($imgid_arr[2])){
				$this->load->model('admin_help_img_model', 'help_img_model');	//帮助图片模型
				$imgids = join(',', $imgid_arr[2]);
				$imgid = $this->help_img_model->update($imgids , $rs);	//保存图片信息
			}

			unset($_POST['pid'], $_POST['cid']);	//清除表单变量，防止内部调用到同名值
			
			if($rs){
				$this->success('发布成功');
			}else{
				$this->error('发布失败');
			}

		}
		if($action === 'edit'){
			$type_url = $this->uri->segment(4);
			$id = $this->get_post('id');

			//修改记录
			$data = array(
				'pid' => $this->get_post('pid'),
				'cid' => $this->get_post('cid'),
				'title' => trim($this->get_post('title')),
				'tag' => trim($this->get_post('tag')),
				'content' => $this->get_post('content'),
				'summarize' => $this->get_post('summarize'),
				'up_dateline' => time()
			);
			$rs = $this->help_model->edit($id, $data);
			unset($_POST['pid'], $_POST['cid']);	//清除表单变量，防止内部调用到同名值
			
			if($rs){
				$this->success('发布成功');
			}else{
				$this->error('发布失败');
			}
		}
		if($action === 'delete'){
			$id = $this->uri->segment(4);
			$rs = $this->help_model->delete($id);
			
			if($rs){
				$this->success('删除成功');
			}else{
				$this->error('删除失败');
			}
		}
	}

	/**
	 * 帮助列表-图片上传(添加/编辑)
	 */
	public function upload_img(){

		//定义允许上传的文件扩展名
		$ext_arr = array(
			'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
		);
		//最大文件大小 2M
		$max_size = 1048576;

		//PHP上传失败
		if (!empty($_FILES['imgFile']['error'])) {
			switch($_FILES['imgFile']['error']){
				case '1':
					$error = '超过php.ini允许的大小。';
					break;
				case '2':
					$error = '超过表单允许的大小。';
					break;
				case '3':
					$error = '图片只有部分被上传。';
					break;
				case '4':
					$error = '请选择图片。';
					break;
				case '6':
					$error = '找不到临时目录。';
					break;
				case '7':
					$error = '写文件到硬盘出错。';
					break;
				case '8':
					$error = 'File upload stopped by extension。';
					break;
				case '999':
				default:
					$error = '未知错误。';
			}
			$this->_upload_result($error);
		}

		//有上传文件时
		if (empty($_FILES) === false) {
			//原文件名
			$file_name = $_FILES['imgFile']['name'];
			//服务器上临时文件名
			$tmp_name = $_FILES['imgFile']['tmp_name'];
			//文件大小
			$file_size = $_FILES['imgFile']['size'];
		}
		//检查文件名
		if (!$file_name) {
			$this->_upload_result("请选择文件。");
		}
		//检查是否已上传
		if (@is_uploaded_file($tmp_name) === false) {
			$this->_upload_result("上传失败。");
		}
		//检查文件大小
		if ($file_size > $max_size) {
			$this->_upload_result("上传文件大小超过限制。");
		}
		//检查目录名
		$dir_name = 'image';
		if (empty($ext_arr[$dir_name])) {
			$this->_upload_result("目录名不正确。");
		}
		//获得文件扩展名
		$temp_arr = explode(".", $file_name);
		$file_ext = array_pop($temp_arr);
		$file_ext = trim($file_ext);
		$file_ext = strtolower($file_ext);
		//检查扩展名
		if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
			$this->_upload_result("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。");
		}
		

		$this->load->library('upload_image');	//图片上传类
		$imgurl = $this->upload_image->save('help', $_FILES['imgFile']['tmp_name']);	//保存到图片类里的路径，返回url路径+图片名称
		if($imgurl){	//上传成功
			$this->load->model('admin_help_img_model', 'help_img_model');	//帮助图片模型
			$data = array('uid'=>$this->user_id, 'url'=>$imgurl, 'dateline'=>time());
			$imgid = $this->help_img_model->add($data);	//保存图片信息

			//文件保存路径 如http://image1.sk.com/mall/help/2014/01/09/1640594601.jpg
			$imgurl = array_shift($this->config->item('image_servers')).$imgurl.'?id='.$imgid;
		}else{
			$this->_upload_result('保存上传图片失败');
		}
		
		$this->_upload_result('',$imgurl,0);
		exit;
	}

	/**
	 * 图片上传结果
	 */
	private function _upload_result($msg, $url='', $state=1) {
		header('Content-type: text/html; charset=UTF-8');
		$data = array('error' => $state, 'message' => $msg, 'url' => $url);
		echo json_encode($data);
		exit;
	}
	
	/**
	 * 帮助列表-屏蔽
	 */
	public function callback_block_change(){
		$ids = $this->get_post('ids');	//逗号连接的id字串
		$state = $this->get_post('state');	//请求状态：0屏蔽，1显示
		$rs = $this->help_model->block_change($ids, $state);
		
		if($rs){
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
	}
	
	/**
	 * 帮助列表-多选删除
	 */
	public function callback_delete(){
		$ids = $this->get_post('ids');	//逗号连接的id字串
		$state = $this->get_post('state');	//请求状态：0屏蔽，1显示
		$rs = $this->help_model->delete_by_ids($ids);
		
		if($rs){
			$this->success('删除成功');
		}else{
			$this->error('操作失败');
		}
	}
	
	/**
	 * 帮助列表-推送操作 callback_cancel_push
	 */
	public function callback_push(){
		$id = $this->get_post('id');
		$push = $this->get_post('push');	//请求状态：0未推，1已推
		
		$rs = $this->help_model->push($id, $push);
		echo $rs;
	}
	
	/**
	 * 帮助列表-常见问题 批量撤销推送操作
	 */
	public function callback_cancel_push(){
		$ids = $this->get_post('ids');
		$push = $this->get_post('push');	//请求状态：0未推，1已推
		
		$rs = $this->help_model->cancel_push($ids, $push);
		echo $rs;
	}
	
	/**
	 * 帮助列表-常见问题 批量排序
	 */
	public function callback_edit_sort(){
		$ids = $this->get_post('ids');
		$sorts = $this->get_post('sorts');
		
		$rs = $this->help_model->edit_sort($ids, $sorts);
		
		if($rs){
			$this->success('排序成功');
		}else{
			$this->error('排序失败');
		}
	}

	/**
	 * 帮助列表-常见问题 更新字体颜色
	*/
	public function callback_update_font_color(){
		$ids = $this->get_post('ids');
		$title_color = $this->get_post('title_color');	//颜色值

		$rs = $this->help_model->update_font_color_by_id($ids, $title_color);

		if($rs){
			$this->success('标题变色成功');
		}else{
			$this->error('标题变色失败');
		}
	}
	
	/**
	 * 帮助列表-常见问题 更新字体粗细
	*/
	public function callback_update_font_strong(){
		$ids = $this->get_post('ids');
		$title_font = $this->get_post('title_font');	//字体宽度

		$rs = $this->help_model->update_font_weight_by_id($ids, $title_font);

		if($rs){
			$this->success('标题字体变更成功');
		}else{
			$this->error('标题字体变更失败');
		}
	}

	/**
	 * 帮助列表-编辑(根据id返回记录)
	 */
	public function callback_get_by_id(){
		$id = $this->get_post('id');	//帮助表id
		$type = $this->get_post('type');
		$rs = $this->help_model->get_by_id($id);	//帮助记录
		
		$cate_parents = $this->help_category_model->get_by_pid($type);	//返回主分类-分类下拉框
		$cate_childs = $this->help_category_model->get_by_pid($type, $rs['pid']);	//返回子分类-分类下拉框
		
		$this->load->view('help/list_edit_js', get_defined_vars());
	}
	
	/**
	 * 帮助列表-删除当前记录
	 */
	public function callback_delete_by_id(){
		$id = $this->get_post('ids');
		$rs = $this->help_model->get_by_id($id);
	}
	
	/**
	 * 根据用户类型清除帮助中心前端页面缓存文
	 */
	public function callback_clear_html_cache(){
		//调用删除文件方法
		$rs = $this->deldir( dirname(dirname(dirname(__FILE__))).'/help.ylapp.com/cache' );
		
		if($rs){
			$this->success('更新成功');
		}else{
			$this->error('已是最新内容，无需继续更新');
		}
	}
	
	/**
	 * 屏蔽/解屏类目
	 * 
	 * @author 杜嘉杰
	 * @version 2015-3-4
	 */
	public function callback_category_block(){
		// 类目id
		$id = intval($this->uri->segment(3));
		
		// 状态
		$state =  intval($this->uri->segment(4));
		
		if( ! in_array($state, array(1,2))){
			$this->error('屏蔽状态有误');
		}
		$ret = $this->help_category_model->block($id, $state);
		
		if($ret){
			$this->success('操作成功！');
		}else{
			$this->error('操作失败！');
		}
	}

	/**
	 * 删除指定目录下的文件
	 * @param string $dir 文件路径
	 * @param string $include_file_name 包含的文件名
	 * @param bool $flag 要返回的操作结果：true为有文件操作、false为无操作任何文件
	 * @return $flag
	 */
	private function deldir($dir, $flag=false){
		$flag2 = false;
		//除目录下的文件：
		$dh=opendir($dir);
		while ($file=readdir($dh)) {
			if($file!="." && $file!="..") {
				$fullpath=$dir.'/'.$file;
				if(!is_dir($fullpath)) {
					$flag2 = unlink($fullpath);
				}
				//判断，有一个以上的删除成功操作则为更新成功
				if($flag2===true){
					$flag = true;
				}
			}
		}
		closedir($dh);
		return $flag;
	}

	/*——————————————————————————————————帮助分类————————————————————————————————————————————*/
	
	/**
	 * 帮助分类(买家|商家)
	 */
	public function category(){
		//获取参数：buyer|seller
		$type_url = $this->uri->segment(3);
		$id = $this->uri->segment(4);	//获取切换时ajax传来的主类id
		$type = '';
		//判断uri请求参数：1买家、2商家
		switch ($type_url){
			case 'buyer':
				$type = 1;
				break;
			case 'seller':
				$type = 2;
				break;
		}
		//根据请求类型调用列表页面
		$this->_category($type_url, $id, $type);
	}

	/**
	 * 分类页面展示
	 * @param string $type_url 请求类型（buyer、seller）
	 * @param int $id
	 * @param int $type 用户请求类型（1买家、2商家）
	 */
	private function _category($type_url, $id, $type){
		//根据用户类型，获取主类结果集
		$cate_parents = $this->help_category_model->get_by_pid($type);

		//判断url是否带有id参数，若不带，则当前主类的记录为$cate_parents结果集中第一条记录的主类id
		if(empty($id)){
			$id = $cate_parents[0]['id'];
		}
		$current_cate_parent = $this->help_category_model->get_by_id($id);	//当前记录
		
		$cate_childs = $this->help_category_model->get_by_pid($type, $id);	//字类结果集
		//返回帮助分类页 
		$this->load->view('help/category', get_defined_vars());
	}

	/**
	 * 分类 增|删|改 请求路由 :(add|edit|delete)
	 */
	public function category_action(){
		//获取url请求类型
		$action = $this->uri->segment(3);	//操作类型：add|edit|delete
		$flag = false;	//是否需要加载页面
		//添加请求
		if ($action === 'add'){
			$type = $this->uri->segment(4);	//用户类型：1买家、2商家
			$pid = $this->uri->segment(5);	//添加的上级id，添加子类型
			$flag = true;
		}
		//编辑请求，则需要查询出id对应的记录
		if ($action === 'edit'){
			$id = $this->uri->segment(4);	//当前id
			$cate_parent = $this->help_category_model->get_by_id($id);
			$flag = true;
		}
		//删除请求
		if ($action === 'delete'){
			$id = $this->uri->segment(4);	//当前分类id
			//判断该类是否在帮助表中有数据引用
			$cate = $this->help_category_model->get_by_id($id);	//查出当前分类记录
			$help = $this->help_model->get_by_cid($cate['id']);	//根据子类id查询帮助信息
			
			$rs = '';
			if(count($help)){	//有记录引用
				$this->error('该分类有记录引用，无法删除');
			}else{	//无记录引用
				$rs = $this->category_delete($id);	//调用删除操作
				if($rs){
					$this->success('删除成功');
				}else{
					$this->error('删除失败');
				}
			}
		}
		if ($flag){
			//根据 $action 分配到指定的操作页面
			$this->load->view('help/dialog/category_'.$action, get_defined_vars());
		}
		
	}

	/**
	 * 分类-添加操作
	 * ——》来自 category_action()
	 */
	public function category_add(){
		//获取 category_add.php 表单参数
		$type = $this->uri->segment(3);	//用户类型：1买家、2商家
		$pid = $this->uri->segment(4);	//上级类id，子类型添加时获取
		$name = $this->get_post('cate_name');
		$sort = $this->get_post('cate_sort');
		
		//调用模型中的添加方法
		$rs = $this->help_category_model->add($name , $type, $pid, $sort);

		if($rs){
			$this->success('保存成功');
		}else{
			$this->error('保存失败');
		}
	}
	
	/**
	 * 分类-编辑操作
	 * ——》来自 category_action()
	 */
	public function category_edit(){
		//获取 category_edit.php 表单参数
		$id = $this->uri->segment(3);	//当前id
		$name = $this->get_post('cate_name');
		$sort = $this->get_post('cate_sort');
		
		//调用模型中的编辑方法
		$rs = $this->help_category_model->edit($id, $name, $sort);

		if($rs){
			$this->success('保存成功');
		}else{
			$this->error('保存失败');
		}
	}
	
	/**
	 * 分类-删除操作
	 * ——》来自 category_action()
	 */
	private function category_delete($id){
		//调用模型中的编辑方法
		$rs = $this->help_category_model->delete($id);
		
		if($rs){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}
	
	/*——————————————————————————————————热门搜索————————————————————————————————————————————*/
	
	/**
	 * 热门搜索
	 */
	public function hot_search(){
		//获取参数：buyer|seller
		$type_url = $this->uri->segment(3);
		$type = '';
		//判断uri请求参数：1买家、2商家
		switch ($type_url){
			case 'buyer':
				$type = 1;
				break;
			case 'seller':
				$type = 2;
				break;
		}
		//根据请求类型调用列表页面
		$this->_hot_search($type, $type_url);
	}
	
	/**
	 * 热门搜索页面
	 * @param int $type 请求类型（1买家、2商家）
	 */
	private function _hot_search($type, $type_url){
		$offset = $this->uri->segment(4);	//起始记录下标
		$limit = 50;
		
		// 推荐的搜索词
		$hot_keywords = $this->help_search_model->get_hot_keyword();
		
		$contents = $this->help_search_model->get_all($limit, $offset);	//返回所有记录，可根据用户类型筛选
		
		// 分页相关
		$list_count = $this->help_search_model->list_count();
		$page_conf = array('uri_segment'=>4, 'anchor_class'=>'type="load" rel="div#main-wrap"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$type_url);
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$type_url.'/0');
		$pager = $this->pager($list_count, $limit, $page_conf);
		
		$this->load->view('help/hot_search', get_defined_vars());
	}
	
	/**
	 * 热门搜索-手动添加（管理员操作：可被后台和前端查询）
	 */
	public function hot_add(){
		$data = array(
			'type' => $this->get_post('type'),	//用户类型：1买家、2商家
			'keyword' => $this->get_post('keyword'),
			'dateline' => time(),
			'is_push' => 1,	//是否推送：0否、1是
			'create_type' => 2	//创建人：1搜索添加，2手动添加
		);
		
		$rs = $this->help_search_model->manual_add($data);
		if($rs){
			$this->success('保存成功');
		}else{
			$this->error('保存失败');
		}
	}
	
	/**
	 * 热门搜索-删除
	 */
	public function hot_delete(){
		$ids = $this->get_post('ids');
		
		$rs = $this->help_search_model->cancel_push($ids);
		if($rs){
			$this->success('撤销成功');
		}else{
			$this->error('撤销失败');
		}
	}
	
	/**
	 * 热门搜索-推送到热门搜索
	 */
	public function hot_push(){
		$id = $this->get_post('id');
		
		$rs = $this->help_search_model->push($id);
		if($rs){
			$this->success('添加成功');
		}else{
			$this->error('添加失败');
		}
	}
	
	/**
	 * 删除用户搜索的关键词
	 */
	public function delete_search(){
		$id = $this->uri->segment(3);	//id
		$rs = $this->help_search_model->delete($id);
		if($rs){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}
	
}
// End of class Help

/* End of file help.php */
/* Location: ./application/controllers/help.php */