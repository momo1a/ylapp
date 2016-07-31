<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 商品应用类库
 * @author minch
 */
class Goods_util
{
	private $_CI;
	
	private $const;
	
	private $status;
	
	private $status_map = array();
	
	private $status_str = array();

	/**
	 * 构造函数
	 */
	public function __construct()
	{
		$this->_CI = &get_instance();
		$this->_CI->load->model('admin_goods_model'); //加载数据库模型
		$this->_CI->load->helper('url'); 
		
		$goods_refl = new ReflectionClass('Admin_goods_model');
		$this->const = $goods_refl->getConstants();
		foreach ($this->const as $k=>$v){
			if(preg_match('/^STATUS_\w+_STR$/', $k)){
				$this->status_str[$k] = $v;
			}else if(preg_match('/^STATUS_\w+$/', $k)){
				$this->status[$k] = $v;
			}
		}
		$this->status_map = array_combine($this->status, $this->status_str);
	}
	
	/**
	 * @param array $ext 额外内容 array(''=>'所有商品状态')
	 * @return array
	 */
	public function get_status_map($ext = array())
	{
		$map = $ext;
		foreach ($this->status_map as $k=>$v){
			$map[$k] = $v;
		}
		return $map;
	}

	/**
	 * 获取商品类型
	 * @param array $types 类型
	 * @param int $goodstype 商品类型
	 * @return string
	 */
	public function get_goods_type($types, $goodstype){
		$rs = '';
		$goodstype = intval($goodstype);
		$rs = $types[$goodstype];
		return $rs;
	}
	
	/**
	 * @param array $goods_type 原有类型数组
	 * @param array $ext 额外内容 array(''=>'所有类型')
	 * @return array
	 */
	public function get_goods_type_map($goods_type, $ext = array())
	{
		$map = $ext;
		foreach ($goods_type as $k=>$v){
			$map[$k] = $v;
		}
		return $map;
	}
	
	/**
	 * 返回商品状态
	 * @param int $var 商品状态
	 * @return string
	 */
	public function get_status($var){
		return $this->status_map[$var];
	}
	
	/**
	 * 获取商品要不同状态下可执行的操作
	 * @param array $goods 商品信息
	 * @return string
	 */
	public function get_action($goods){
		$rs = '';
		switch ($goods['state']){
			case Goods_model::STATUS_UNCHECK_UNPAY:
				$rs .= $this->_get_edit_action($goods);
				break;
			case Goods_model::STATUS_UNCHECK_PAYING:
				$rs .= $this->_get_edit_action($goods);
				break;
			case Goods_model::STATUS_UNCHECK_PAID:
				$rs .= $this->_get_edit_action($goods);
				$rs .= $this->_get_check_action($goods);
				break;
			case Goods_model::STATUS_EDIT_REFUND_PAYING:
				$rs .= $this->_get_edit_action($goods);
				break;
			case Goods_model::STATUS_CHECKED:
				$rs .= $this->_get_edit_action($goods);
				$rs .= $this->_get_check_action($goods);
				break;
			case Goods_model::STATUS_CANCEL_PAYING:
				$rs = $this->_get_edit_action($goods);
				break;
			case Goods_model::STATUS_CANCELED:
				$rs .= $this->_get_edit_action($goods);
				break;
			case Goods_model::STATUS_REFUSE_PAYING:
				$rs .= $this->_get_edit_action($goods);
				break;
			case Goods_model::STATUS_REFUSED:
				$rs .= $this->_get_edit_action($goods);
				break;
			case Goods_model::STATUS_ONLINE:
				$rs .= $this->_get_edit_action($goods);
				$rs .= $this->_get_block_action($goods);
				$rs .= $this->_get_addition_action($goods);
				$rs .= $this->_get_detail_action($goods);
				break;
			case Goods_model::STATUS_BLOCKED:
				$rs .= $this->_get_edit_action($goods);
				$rs .= $this->_get_block_action($goods);
				$rs .= $this->_get_settlement_action($goods);
				$rs .= $this->_get_addition_action($goods);
				$rs .= $this->_get_checkout_action($goods);
				$rs .= $this->_get_detail_action($goods);
				break;
			case Goods_model::STATUS_OFFLINE:
				$rs .= $this->_get_block_action($goods);
				$rs .= $this->_get_detail_action($goods);
				$rs .= $this->_get_addition_action($goods);
				$rs .= $this->_get_checkout_action($goods);
				break;
			case Goods_model::STATUS_HAVE_CHANCE:
				$rs .= $this->_get_block_action($goods);
				$rs .= $this->_get_detail_action($goods);
				$rs .= $this->_get_addition_action($goods);
				$rs .= $this->_get_checkout_action($goods);
				break;
			case Goods_model::STATUS_ADDITION_PAYING:
				$rs .= $this->_get_edit_action($goods);
				$rs .= $this->_get_detail_action($goods);
				$rs .= $this->_get_addition_action($goods);
				$rs .= $this->_get_checkout_action($goods);
				break;
			case Goods_model::STATUS_CHECKOUT_PAYING:
				$rs .= $this->_get_edit_action($goods);
				$rs .= $this->_get_detail_action($goods);
				$rs .= $this->_get_settlement_action($goods);
				$rs .= $this->_get_addition_action($goods);
				$rs .= $this->_get_checkout_action($goods);
				break;
			case Goods_model::STATUS_CHECKOUT:
				$rs .= $this->_get_edit_action($goods);
				$rs .= $this->_get_detail_action($goods);
				$rs .= $this->_get_addition_action($goods);
				$rs .= $this->_get_checkout_action($goods);
				break;
			case Goods_model::STATUS_CHECKOUT_CLOSED:
				$rs .= $this->_get_edit_action($goods);
				$rs .= $this->_get_detail_action($goods);
				$rs .= $this->_get_addition_action($goods);
				$rs .= $this->_get_checkout_action($goods);
				break;
			default:
				
		}
		$rs .= $this->_get_option_action($goods);
		return $rs;
	}
		
	/**
	 * 显示详细操作
	 * @param array $goods
	 * @return string
	 */
	private function _get_detail_action($goods){
		$attr = 'type="load" rel="div#main-wrap" data-gid="'.$goods['gid'].'"';
		return anchor(site_url('goods/order/'.$goods['gid']), '进入活动', $attr).'<br />';
	}

	private function _get_status_action($goods){
		$str = <<<AA
		<a href="javascript:;" onclick="GoodsOrders({$goods['gid']});return false;">状态</a>
AA;
		return $str;
	}
	
	/**
	 * 修改SEO操作
	 * @param array $goods 商品信息
	 * @return string
	 */
	private function _get_edit_action($goods){
		$attr = 'type="form" width="600" height="400" data-gid="'.$goods['gid'].'" callback="reload"';
		return anchor(site_url('goods/edit'), '修改', $attr).'<br />';
	}
	
	/**
	 * 审核操作
	 * @param array $goods
	 * @return string
	 */
	private function _get_check_action($goods){
		$str = '';
		if($goods['state'] == Goods_model::STATUS_CHECKED){
			$attr = 'type="form" width="400" height="200" data-gids[]="'.$goods['gid'].'" data-showform="yes" callback="reload" title="设置活动上线时间" ';
			$str .= anchor(site_url('goods/set_online_time'), '上线时间', $attr).'<br />';
			$attr = 'type="confirm" title="您确定要取消审核通过吗？" data-gid="'.$goods['gid'].'" callback="reload"';
			$str .= anchor(site_url('goods/uncheck'), '取消通过', $attr).'<br />';
			//$attr = 'type="confirm" title="您确定要手动上线该商品吗？" data-gid="'.$goods['gid'].'" callback="reload"';
			//$str .= anchor(site_url('goods/set_online'), '上线', $attr).'<br />';
		}elseif($goods['state'] == Goods_model::STATUS_UNCHECK_PAID){
			
			//修改最新上线分场——设置分场 审核活动，获取上线时间类型链接
			$goods_default_online_type=unserialize($this->_CI->config->item('goods_default_online_type'));
			$online_type=isset($goods_default_online_type[$goods['user_id']])?$goods_default_online_type[$goods['user_id']]:0;
			if($online_type==1 || $online_type==3){
			$attr = 'type="post" width="400" height="200" data-gid="'.$goods['gid'].'" data-pass="1" callback="reload" title="选择活动上线时间" ';
			$str .= anchor(site_url('goods/check'), '通过', $attr).'<br />';
			}else{
			$attr = 'type="form" width="400" height="200" data-gid="'.$goods['gid'].'" data-showform="yes" callback="reload" title="选择活动上线时间" ';
			$str .= anchor(site_url('goods/check'), '通过', $attr).'<br />';
			}
			$attr = ' data-gid="'.$goods['gid'].'" callback="reload" type="confirm" title="确定不通过吗？"';
			$str .= anchor(site_url('goods/check_refund'), '不通过', $attr).'<br />';
		}
		return $str;
	}
	
	/**
	 * 屏蔽操作
	 * @param array $goods
	 * @return string
	 */
	private function _get_block_action($goods){ 
		if(in_array($goods['state'], array(Goods_model::STATUS_ONLINE, Goods_model::STATUS_OFFLINE, Goods_model::STATUS_HAVE_CHANCE))){
			$attr = 'type="form" width="400" height="200" data-gid="'.$goods['gid'].'" callback="reload"';
			$str = anchor(site_url('goods/block'), '屏蔽', $attr).'<br />';
		}elseif($goods['state'] == Goods_model::STATUS_BLOCKED){
			$attr = 'type="form" width="400" height="200" data-gid="'.$goods['gid'].'" callback="reload"';
			$str = anchor(site_url('goods/unblock'), '解屏', $attr).'<br />';
			
			$attr = 'type="dialog" width="500" height="300" data-gid="'.$goods['gid'].'"';
			$str .= anchor(site_url('goods/block_reason'), '屏蔽原因', $attr).'<br />';
		}
		return $str;
	}

	/**
	 * 查看结算记录
	 * @param array $goods
	 * @return string
	 */
	private function _get_checkout_action($goods){
		$attr = 'type="load" rel="div#main-wrap"  data-gid="'.$goods['gid'].'"';
		return anchor(site_url('goods/checkout/'.$goods['gid']), '结算记录', $attr).'<br />';
	}
	
	/**
	 * 结算活动
	 * @param array $goods
	 * @return string
	 */
	private function _get_settlement_action($goods){
		$attr = 'type="confirm|form" title="此操作不可逆，您确定要结算该商品吗？" data-gid="'.$goods['gid'].'" callback="reload"';
		return anchor(site_url('goods/balance'), '结算活动', $attr).'<br />';
	}
	
	/**
	 * 下架操作
	 * @param array $goods
	 * @return string
	 */
	private function _get_offline_action($goods){
		$attr = 'type="confirm" title="您确定要手动下架该商品吗？" data-gid="'.$goods['gid'].'" callback="reload"';
		return anchor(site_url('goods/set_offline'), '下架', $attr).'<br />';
	}
	
	/**
	 * 追加记录
	 * @param array $goods
	 * @return string
	 */
	private function _get_addition_action($goods){
		$attr = 'type="dialog" width="960" height="550" data-gid="'.$goods['gid'].'"';
		return anchor(site_url('goods/addition_log'), '追加记录', $attr).'<br />';
	}
	
	/**
	 * 操作记录
	 * @param array $goods
	 * @return string
	 */
	private function _get_option_action($goods){
		$attr = 'type="dialog" width="800" data-gid="'.$goods['gid'].'"';
		return anchor(site_url('goods/option_log'), '操作记录', $attr).'<br />';
	}

}