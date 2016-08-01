<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class YL_avatar
{
	private $CI;
	public $uid;
	public $avatar_size_config;
	public $upload_avatar_save_path;

	public function __construct($config)
	{
		$config||exit('缺少传参：arrary("uid"=>?)');
		$this->CI =& get_instance();
		$this->uid = $config['uid'];
		$this->avatar_size_config = config_item('upload_avatar_thumb_size');
		$this->upload_avatar_save_path = config_item('upload_avatar_save_path');
	}

	/**
	 * 图片上传
	 * @param $upload_file_path
	 */
	public function upload_img($upload_file_path)
	{
		$uid = $this->uid;
		$success = true;
		$upload_file_path_list = array();
		$home = $this->_get_user_avatar_home($uid);
		$home_path = $this->upload_avatar_save_path . 'avatar/' . $home;
		if (!is_dir($home_path)) {
			$this->_set_user_avatar_home($this->uid, $this->upload_avatar_save_path . 'avatar');
		}
		//所有的头像都要转换为jpg格式
		foreach ($this->avatar_size_config as $key => $val) {
			$avatar_file_path = $this->upload_avatar_save_path . 'avatar/' . $this->_get_avatar($uid, $key);
			copy($upload_file_path, $avatar_file_path);
			$result = $this->_adjust_image_size($avatar_file_path, $val);
			if (!$result) {
				$success = false;
			}
			$upload_file_path_list[] = $avatar_file_path;
		}
		//上传失败的处理
		if (!$success) {
			$this->error = array('code' => 5, 'msg' => '服务器保存文件出错');
		}
		return $success;
	}

	/**
	 * 通过uid和site获取图片的url
	 */

	public function get_avatar_url($uid,$size){
		$avatar_setting = $this->CI->config->item('avatar');
		return $avatar_setting['server']['img'].'data/avatar/'.$this->_get_avatar($uid,$size);
	}

	/**
	 * 调整图片大小
	 * @param $avatar_file_path
	 * @param $size array();
	 */
	public function _adjust_image_size($file_path, $width, $hight = '')
	{
		$config = array();
		$this->CI->load->library('image_lib');
		$hight = $hight ? $hight : $width;
		$config['width'] = $width;
		$config['height'] = $hight;
		$config['image_library'] = 'gd2';
		$config['source_image'] = $file_path;
		$config['maintain_ratio'] = true;
		$this->CI->image_lib->initialize($config);
		$this->CI->image_lib->resize();
		if (!$this->CI->image_lib->crop()) {
			return FALSE;
		}
		return true;
	}

	/**
	 * 通过用户id获取保存图片的位置
	 * @param $uid
	 * @param string $size
	 * @param string $type
	 * @return string
	 */
	public function _get_avatar($uid, $size = 'big', $type = '')
	{
		$size = in_array($size, array('big', 'middle', 'small')) ? $size : 'big';
		$uid = abs(intval($uid));
		$uid = sprintf("%09d", $uid);
		$dir1 = substr($uid, 0, 3);
		$dir2 = substr($uid, 3, 2);
		$dir3 = substr($uid, 5, 2);
		$typeadd = $type == 'real' ? '_real' : '';
		return $dir1 . '/' . $dir2 . '/' . $dir3 . '/' . substr($uid, -2) . $typeadd . "_avatar_$size.jpg";
	}

	/**
	 * 获取用户存放头像的目录
	 * @param $uid
	 * @return string
	 */
	public function _get_user_avatar_home($uid)
	{
		$uid = sprintf("%09d", $uid);
		$dir1 = substr($uid, 0, 3);
		$dir2 = substr($uid, 3, 2);
		$dir3 = substr($uid, 5, 2);
		return $dir1 . '/' . $dir2 . '/' . $dir3;
	}

	/**
	 * 创建用户存放头像的目录
	 * @param $uid
	 * @return string
	 */
	public function _set_user_avatar_home($uid, $dir = '.')
	{
		$uid = sprintf("%09d", $uid);
		$dir1 = substr($uid, 0, 3);
		$dir2 = substr($uid, 3, 2);
		$dir3 = substr($uid, 5, 2);
		!is_dir($dir) && mkdir($dir, 0777);
		!is_dir($dir . '/' . $dir1) && mkdir($dir . '/' . $dir1, 0777);
		!is_dir($dir . '/' . $dir1 . '/' . $dir2) && mkdir($dir . '/' . $dir1 . '/' . $dir2, 0777);
		!is_dir($dir . '/' . $dir1 . '/' . $dir2 . '/' . $dir3) && mkdir($dir . '/' . $dir1 . '/' . $dir2 . '/' . $dir3, 0777);
	}

} 