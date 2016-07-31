<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 前台广告
 *
 * @author 众划算
 * @link http://www.zhonghuasuan.com/
 *
 */
class advertisement_model extends Zhs_advertisement_model
{
	/**
	 * 获取登录页的广告
	 * 只获取一条有效的广告
	 * 
	 * @return array 一条登录页的广告或者一个空的数组
	 */
	public function login_ad()
	{
		$ad = $this->find_ads(self::TYPE_LOGIN, 1);
		return $ad ? $ad[0] : FALSE;
	}
}
/* End of file advertisement_model.php */
/* Location: ./application/models/advertisement_model.php */