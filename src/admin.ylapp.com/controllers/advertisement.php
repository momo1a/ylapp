<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 广告管理控制器
 * @author minch <yeah@minch.me>
 * @version 2013-06-06
 * @property common_advertisement_model $common_advertisement_model
 * @property upload_image $upload_image
 */
class Advertisement extends MY_Controller
{
	public $check_access = TRUE;
	public $except_methods = array('index');

	public function __construct(){
		parent::__construct();
		$this->load->model('common_advertisement_model');
		$this->load->model('admin_advertisement_model');
		$this->load->helper(array('image_url','html'));
	}

	public function index($adv_type){
		switch ($adv_type){
			case 'slider':      //首页焦点广告推荐
				$type=1;
				$imgsize='图片尺寸为：766*470';
			 break;
			case 'login_adver': //登录页广告
				$type=2;
				$imgsize='图片尺寸为：2500*380';
			 break;
			case 'brandslider': //名品馆焦点广告推荐
				$type=3;
				$imgsize='图片尺寸为：1190*400';
			 break;
			/** 2015-1-23 名品馆页面优化去掉优质商家推荐功能
			 case 'brandslogo': //名品馆top10优质商家推荐
				$type=4;
				$imgsize='图片尺寸为：180*90';
			 break;
				*/
			case 'headerxia' : //页头下拉广告
				$type=5;
				$imgsize='页头下拉广告为 4 张图片组合，图片顺序高度为：①展开大图  ，②缩回小图 ，③收回按钮图 ，④展开按钮图。';
				$imgsize.='（注：链接为第一张图链接,收回和展开按钮大小要一致。）';
			 break;
		 	case 'floorleft' : //首页楼层左侧广告
		 		$type=6;
		 		$imgsize='首页楼层左侧广告，共三个楼层，每个楼层可设置2个广告图，尺寸为:①399*263、②399*263';
		 		$imgsize.='<br />（注：F1①：表示楼层1第一个广告图）';
		 		break;
			case 'rightads' : //公告下面幻灯广告
				$type=7;
				$imgsize='图片尺寸为：200*303';
			 break;
			case 'bannerads' : //通栏广告
				$type=8;
				$imgsize='首页通栏共三个广告位，每个通栏1张广告图 ，尺寸统一为：1190x150';
			 break;
			case 'indexlogo' : //首页优质商家推荐
				$type=9;
				$imgsize='图片尺寸为：180*90';
			 break;
			case 'yzcm' : //首页优质商家推荐
				$type=10;
			 	$imgsize='图片尺寸为：998*340';
			 	break;
			case 'rightfloat' : //右侧漂浮引导
				$type=11;
			 	$imgsize='图片尺寸为：50*50';
			 	break;
			case 'fenqi' : //分期首页焦点广告
				$type=12;
				$imgsize='图片尺寸为：998*340';
				break;
			default:
				$this->error('参数错误');	
		}
		$list = $this->common_advertisement_model->get(array(),50,0,$type);
		$this->load->view('recommend/advertisement', get_defined_vars());
	}

	public function save_slider(){
		$data = array();
		$data['style']=$this->get_post('style');
		$data['id'] = intval($this->get_post('id'));
		$data['title'] = strval($this->get_post('title'));
		$data['sort'] = intval($this->get_post('sort'));
		$data['type'] = intval($this->get_post('type'));
		$data['width'] = intval($this->get_post('width'));
		$data['height'] = intval($this->get_post('height'));
		$data['starttime'] = strtotime($this->get_post('start_time'));
		$data['endtime'] = strtotime($this->get_post('end_time'));
		$data['enable'] = intval($this->get_post('enable'));

		$ad_info = $data['id'] > 0 ? $this->admin_advertisement_model->get_by_id($data['id']) : array();
		if(empty($ad_info) && $data['id'] > 0){
			$this->error('广告不存在无法编辑');
		}
		
		if($data['title'] == ''){
			$this->error('请填写广告标题');
		}
		# 如果是 首页楼层左侧广告
		if($data['type'] == 6){
			$floor = intval($this->get_post('floor'));
			if($floor === -1 && $data['id'] <= 0){
				$this->error('楼层广告位已满，不能再新增');
			}elseif( ! $floor){
				$this->error('请选择楼层');
			}
			$floor_ad = $this->admin_advertisement_model->get_floor_ad($floor);
			if(is_array($floor_ad) && ! empty($floor_ad) && $data['id'] <= 0){
				$this->error('该楼层对应广告已存在');
			}
			
			$data['sort'] = $floor;
		}
		
		$data['link'] = strval($this->get_post('link'));
		if(!$data['link']){
			$this->error('请填写链接地址');
		}

		if (isset($_FILES['img']) && $_FILES['img'] && $_FILES['img']['error'] != UPLOAD_ERR_NO_FILE)
		{
			if ($_FILES['img']['error'] == UPLOAD_ERR_OK)
			{
				$img_size = $_FILES['img']['size']/1024/1024;
				if ($img_size > 2) {
					$this->error('您上传的图片大小不能超过2M');
				}
				$this->load->library('upload_image');
				$imgurl = $this->upload_image->save('dimg', $_FILES['img']['tmp_name'],null,true);
				if($imgurl){
					$data['img'] = array_shift($this->config->item('image_servers')).$imgurl;
					if($data['width'] <= 0 || $data['height'] <= 0){
						$img_info = getimagesize(config_item('upload_image_save_path').$imgurl);
						$img_info = $img_info == false ? getimagesize($data['img']) : $img_info;
						if($img_info != false){
							list($data['width'], $data['height']) = $img_info;
						}
					}
				}else{
					$this->error('保存上传图片失败');
				}
			}
			elseif ($_FILES['img']['error'] == UPLOAD_ERR_INI_SIZE)
			{
				$upload_max_filesize = @ini_get('upload_max_filesize');
				$this->error('您上传的图片大小不能超过'.$upload_max_filesize);
			}
		}else{
			$data['img'] = strval($this->get_post('imgurl'));
			if($data['width'] <= 0 || $data['height'] <= 0){
				$img_info = getimagesize($data['img']);
				if($img_info != false){
					list($data['width'], $data['height']) = $img_info;
				}
			}
		}

		if(!$data['img']){
			$this->error('请上传图片或填写远程图片地址');
		}
		if($data['starttime'] >= $data['endtime']){
			$this->error('起始时间不能大于或等于结束时间');
		}
		if($data['endtime'] <= 0){
			$this->error('结束时间不能为空');
		}
		if($data['endtime'] <= time()){
			$this->error('结束时间至必须大于当前时间');
		}
		$logpre = '';
		if(!$data['id']){
			$data['dateline'] = time();
			$logpre = '添加';
		}else{
			$logpre = '修改';
		}
		$rs = $this->common_advertisement_model->save($data);
		if($rs){
			$this->log($logpre.'焦点广告成功', array_merge($_GET, $_POST));
			$this->success('保存成功');
		}else{
			$this->log($logpre.'焦点广告失败', array_merge($_GET, $_POST));
			$this->error('保存失败');
		}
	}

	public function delete(){
		$id = intval($this->get_post('id'));
		$rs = $this->common_advertisement_model->delete($id);
		if($rs){
			$this->log('删除焦点广告成功', array_merge($_GET, $_POST));
			$this->success('删除成功');
		}else{
			$this->log('删除焦点广告失败', array_merge($_GET, $_POST));
			$this->error('删除失败');
		}
	}
	/**
	 * 保存排序
	 */
	public function setads_sort()
	{
		$map = array();
		foreach($_POST as $k=>$v){
			list($pre, $id) = explode('_', $k);
			if('id' == $pre){
				$map[$id] = $v;
			}
		}
		if(!is_array($map)){return FALSE;}
		$flag = TRUE;
		$this->db->trans_begin(); //事务开始
		foreach($map as $id=>$sort){
			$rs = $this->db->set('sort', $sort)->where('id', $id)->update('common_advertisement');
			if(!$rs){
				$flag = FALSE;
				break;
			}
		}
		if($flag){
			$this->db->trans_commit();//提交事务
		}else{
			$this->db->trans_rollback();//回滚
		}
		if($flag){
			$this->log('修改推荐广告排序成功');
			$this->success('排序成功');
		}else{
			$this->log('修改推荐广告排序失败');
			$this->error('修改排序失败');
		}
	}
}
// End of class Advertisement

/* End of file advertisement.php */
/* Location: ./application/controllers/advertisement.php */