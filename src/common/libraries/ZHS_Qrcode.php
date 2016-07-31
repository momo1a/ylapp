<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ZHS_Qrcode
{
	/**
	 * @var string 活动二维码存放文件夹
	 */
	const GOODS_QRCODE_IMG_FOLDER = 'goodsqrcode';
	
	
	public function __construct()
	{
		require COMPATH.'third_party/Qrcode.php';
	}
	
	/**
	 * 保存二维码地址
	 * @param 二维码数据 $data
	 * @return string 二维码图片相对地址
	 */
	public function save( $data )
	{
		$upload_image_save_path = config_item('upload_image_save_path');
		$save_dir = $upload_image_save_path . self::GOODS_QRCODE_IMG_FOLDER . date('/Y/m/d');
		file_exists($save_dir) or mkdir($save_dir, 0775, TRUE);
		$original_path = $save_dir . date('/His') . rand(1000, 9999) . '.png';
		
		//二维码相对地址
		$relative_path = substr($original_path, strlen($upload_image_save_path));
		//创建一个二维码文件
		$res = Qrcode::png($data, $original_path, 'L', '4', 1);
		if( $res !==TRUE )
		{
			log_message('error', '生成二维码失败[error:'.(string)$res.'],二维码数据:'.$data.',二维码相对地址:'.$relative_path);
			return FALSE;
		}
		
		return $relative_path;
	}
	
} //end class