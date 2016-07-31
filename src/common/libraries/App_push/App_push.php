<?php
class App_push extends CI_Driver_Library implements App_push_driver
{
	//--------------------------------------------------------------------------------------------------------------
	
	/*
	// 传给客户端你的自定义参数例子，内容结构已经与客户端调试完毕，详见《众划算APP推送及Banner模块定义.doc》文档定义。
	$custom = array('type'=>'1001', 'uid'=>'-1', 'value'=>array('url'=>'http://www.baidu.com'));// url
	$custom = array('type'=>'1002', 'uid'=>'-1', 'value'=>array());//一战成名
	$custom = array('type'=>'1003', 'uid'=>'-1', 'value'=>array());//最新上线
	$custom = array('type'=>'1004', 'uid'=>'-1', 'value'=>array('goods_id'=>'36752'));//商品详情
	$custom = array('type'=>'1005', 'uid'=>'-1', 'value'=>array('special_id'=>'1','special_title'=>'八月专题'));//专题
	$custom = array('type'=>'1006', 'uid'=>'-1', 'value'=>array('type'=>'search_goods', 'keyword'=>'裤子')); //搜索活动
	$custom = array('type'=>'1007', 'uid'=>'-1', 'value'=>array());//抢购提醒
	$custom = array('type'=>'1008', 'uid'=>'-1', 'value'=>array());//我的订单
	$custom = array('type'=>'1009', 'uid'=>'-1', 'value'=>array('appeal_id'=>14724));//申诉详情
	$custom = array('type'=>'1010', 'uid'=>'-1', 'value'=>array('category_id'=>1));//类目
	$custom = array('type'=>'1011', 'uid'=>'-1', 'value'=>array('type'=>'search_shops', 'keyword'=>'裤子'));  //搜索商家
	*/
	//--------------------------------------------------------------------------------------------------------------
	
	
	protected $valid_drivers = array (
			'app_push_xin_ge' 
	);
	
	public $_adapter = 'xin_ge';
	
	public function __construct()
	{
	}

	/**
	 * __get()
	 *
	 * @param child
	 * @return object
	 */
	public function __get($child)
	{
		$obj = parent::__get($child);

		return $obj;
	}
	
	/**
	 * 根据uid发送单个消息推送到
	 * @param int $uid:目标用户uid
	 * @param string $content:内容
	 * @param int $time:执行时间，0为马上推送，大于0为定时发送（最大支持定时未来三天发送）
	 * @param array $custom:推送的自定义参数，格式为：array('type'=>1001,'value'=>array('url'=>'http://www.baidu.com'))
	 * @return boolean
	 * @author 杜嘉杰
	 * @version 2014-9-5
	 */
	public function push_app_single($uid ,$content ,$time = 0 ,$custom = NULL)
	{
		$re = $this->{$this->_adapter}->push_app_single($uid ,$content ,$time, $custom);
		return $re;
	}

	/**
	 * 群发所有android客户端的推送
	 * @param string $content：内容
	 * @param int $time:推送时间戳，0表示马上推送
	 * @param array $custom:自定义参数
	 * @return boolean
	 * @author 杜嘉杰
	 * @version 2014-9-9
	 */
	public function push_all_devices_android($content, $time=0, $custom=NULL)
	{
		$re = $this->{$this->_adapter}->push_all_devices_android($content, $time, $custom);
		return $re;
	}
	
	/**
	 * 群发所有ios客户端的推送
	 * @param string $content：内容
	 * @param int $time:推送时间戳，0表示马上推送
	 * @param array $custom:自定义参数
	 * @return boolean
	 * @author 杜嘉杰
	 * @version 2014-9-9
	 */
	public function push_all_devices_ios($content, $time=0, $custom=NULL)
	{
		$re = $this->{$this->_adapter}->push_all_devices_ios($content, $time, $custom);
		return $re;
	}
	
	/**
	 * 查询推送消息的状态
	 * @param array $push_ids:推送消息的id，array(1,2,3);
	 * @param int $client_type:设备类型；
	 * @return array
	 * @author 杜嘉杰
	 * @version 2014-9-9
	 */
	public function query_push_status($push_ids, $client_type)
	{
		$re = $this->{$this->_adapter}->query_push_status($push_ids, $client_type);
		return $re;
	}

	/**
	 * 获取错误信息
	 * 
	 */
	public function error()
	{
		return $this->{$this->_adapter}->error();	
	}

	/**
	 * 获取群发推送成功后返回的id
	 * @return int
	 * @version 2014-9-9
	 */
	public function get_push_id(){
		return $this->{$this->_adapter}->get_push_id();	
	}

}
interface App_push_driver
{
	/**
	 * 根据uid发送单个消息推送到
	 * @param int $uid:目标用户uid
	 * @param string $content:内容
	 * @param int $time:执行时间，0为马上推送，大于0为定时发送（最大支持定时未来三天发送）
	 * @param array $custom:推送的自定义参数，格式为：array('type'=>1001,'value'=>array('url'=>'http://www.baidu.com'))
	 * @return boolean
	 * @author 杜嘉杰
	 * @version 2014-9-5
	 */
	public function push_app_single($uid, $content, $time=0, $custom=NULL);
	
	/**
	 * 群发所有android客户端的推送
	 * @param string $content：内容
	 * @param int $time:推送时间戳，0表示马上推送
	 * @param array $custom:自定义参数
	 * @return boolean
	 * @author 杜嘉杰
	 * @version 2014-9-9
	 */
	public function push_all_devices_android($content, $time=0, $custom=NULL);
	
	/**
	 * 群发所有ios客户端的推送
	 * @param string $content：内容
	 * @param int $time:推送时间戳，0表示马上推送
	 * @param array $custom:自定义参数
	 * @return boolean
	 * @author 杜嘉杰
	 * @version 2014-9-9
	 */
	public function push_all_devices_ios($content, $time=0, $custom=NULL);
	
	/**
	 * 查询推送消息的状态
	 * @param array $push_ids:推送消息的id，array(1,2,3);
	 * @param int $client_type:设备类型；
	 * @return array
	 * @author 杜嘉杰
	 * @version 2014-9-9
	 */
	public function query_push_status($push_ids, $client_type);
	
	/**
	 * 获取错误信息
	 * 
	 * @author 杜嘉杰
	 * @version 2015-1-28
	 */
	public function error();
	
	/**
	 * 获取群发推送成功后返回的id
	 * @return int
	 * @version 2014-9-9
	 */
	public function get_push_id();
}
