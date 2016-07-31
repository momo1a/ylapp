<?php
/**
 * 管理员商品操作类
 * @author minch <yeah@minch.me>
 */
class Admin_goods_model extends Goods_Model
{
	/**
	 * 当前模型对应表名
	 * @var string
	 */
	private $_table;
	
	/**
	/*活动为手机专享类型
	 */
	const GOODS_IS_MOBILE_PRICE = 1000;
	
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();
		$this->_table = 'goods';
	}
	
	/**
	 * 管理员搜索商品方法
	 * @param string $key 搜索类型
	 * @param string $val 搜索值
	 * @param string $status 状态
	 * @param int $stime 开始时间
	 * @param int $etime 结束时间
	 * @param array $ext_where 额外参数
	 * @param string $order 排序
	 * @param int $limit
	 * @param int $offset
	 */
	public function search($key = '', $val = '', $status = '', $stime = 0, $etime = 0, $ext_where = '', $order = '', $limit = 0, $offset = 0)
	{
		if(''!==$key && ''!==$val){
			switch($key){
				case 'title':
					$this->db->like('goods.title', $val);
					break;
				case 'gid':
					$this->db->where('goods.gid', $val);
					break;
				case 'uname':
					$this->db->like('goods.uname', $val);
					break;
				case 'email':
					$this->db->like('user.email', $val);
					break;
				case 'uid':
					$this->db->where('goods.uid', $val);
					break;
				default:
				// XXXX do nothing
			}
		}
		if('' !== $status){
			$this->db->where('goods.state', $status);
		}
		if($stime && $etime){
				$this->db->where('goods.dateline >', $stime)->where('goods.dateline <', $etime);
			}
		if('' !== $ext_where){
			if (is_array($ext_where)) {
				foreach ($ext_where as $k=>$v){
					if(is_numeric($k)){
						$this->db->where($v);
						continue;
					} 
				   if (is_string($k)){
						$this->db->where($k, $v);
						continue;
					}
					if(is_array($v)){
						$this->db->where($k, array_shift($v), (boolean)array_shift($v));
					}elseif (is_string($v)){
						$this->db->where($k, $v);
					}
				}
			}elseif (is_string($ext_where)){
				$this->db->where($ext_where);
			}
		}
		if('' !== $order){
			$this->db->order_by($order);
		}
		if($limit){
			$this->db->limit($limit, $offset);
		}
		$this->db->select('goods.*,user.uid,user.uname,user.email,user.mobile');
		$this->db->from('goods');
		$this->db->join('user', 'goods.uid = user.uid');
		return $this->db->get()->result_array();
	}
	/**
	 * 管理员搜索商品方法统计数
	 * @param string $type 搜索类型
	 * @param string $key 搜索值
	 * @param string $status 状态
	 * @param int $stime 开始时间
	 * @param int $etime 结束时间
	 * @param array $ext_where 额外参数
	 */
	public function search_count($key = '', $val = '', $status = '', $stime = 0, $etime = 0, $ext_where = '')
	{
		if(''!==$key && ''!==$val){
			switch($key){
				case 'title':
					$this->db->like('goods.title', $val);
					break;
				case 'gid':
					$this->db->where('goods.gid', $val);
					break;
				case 'uname':
					$this->db->like('goods.uname', $val);
					break;
				case 'email':
					$this->db->like('user.email', $val);
					break;
				case 'uid':
					$this->db->where('goods.uid', $val);
					break;
				default:
				// XXXX do nothing
			}
		}
		if('' !== $status){
			$this->db->where('state', $status);
		}
		if($stime && $etime){
			$this->db->where('goods.dateline >', $stime)->where('goods.dateline <', $etime);
		}
		if('' !== $ext_where){
			if (is_array($ext_where)) {
				foreach ($ext_where as $k=>$v){
					if(is_numeric($k)){
						$this->db->where($v);
						continue;
					}
				    if (is_string($k)){
						$this->db->where($k, $v);
						continue;
					}
					if(is_array($v)){
						$this->db->where($k, array_shift($v), (boolean)array_shift($v));
					}elseif (is_string($v)){
						$this->db->where($k, $v);
					}
				}
			}elseif (is_string($ext_where)){
				$this->db->where($ext_where);
			}
		}
		$this->db->select('goods.gid');
		$this->db->from('goods');
		$this->db->join('user', 'goods.uid = user.uid');
		return $this->db->get()->num_rows();
	}
	
	/**
	 * 审核商品
	 * @param number $gid 商品ID
	 * @param number $uid 管理员ID
	 * @param string $uname 管理员用户名
	 * @param number $online_time 上线时间
	 */
	public function check($gid, $uid, $uname, $online_time)
	{
		$result = $this->db->query('CALL proc_goods_check(?,?,?,?,?)', array($gid, $uid, $uname, bindec(decbin(ip2long($this->input->ip_address()))), $online_time ));
		$rs = $result->first_row('array');
		$result->free_result();
		$this->db->close();
		return $rs;
	}
	
	/**
	 * 商品手动上线
	 * @param number $gid 商品ID
	 * @param number $uid 管理员ID
	 * @param string $uname 管理员用户名
	 */
	public function set_online($gid, $uid, $uname)
	{
		$result = $this->db->query('CALL proc_goods_online(?,?,?,?)', array($gid, $uid, $uname, bindec(decbin(ip2long($this->input->ip_address()))) ));
		$rs = $result->first_row('array');
		$result->free_result();
		$this->db->close();
		return $rs;
	}
	
	/**
	 * 修改活动上线时间
	 * @param string $gids 商品ID,多个活动用英文逗号隔开
	 * @param number $uid 管理员ID
	 * @param string $uname 管理员用户名
	 * @param number $online_time 上线时间
	 */
	public function set_online_time($gids, $online_time, $uid, $uname)
	{
		$result = $this->db->query('CALL proc_goods_set_online_time(?,?,?,?,?)', array($gids, $online_time, $uid, $uname, bindec(decbin(ip2long($this->input->ip_address()))) ));
		$rs = $result->first_row('array');
		$result->free_result();
		$this->db->close();
		return $rs;
	}
	
	/**
	 * 商品手动下架
	 * @param number $gid 商品ID
	 * @param number $uid 管理员ID
	 * @param string $uname 管理员用户名
	 */
	public function set_offline($gid, $uid, $uname)
	{
		$result = $this->db->query('CALL proc_goods_offline(?,?,?,?)', array($gid, $uid, $uname, bindec(decbin(ip2long($this->input->ip_address()))) ));
		$rs = $result->first_row('array');
		$result->free_result();
		$this->db->close();
		return $rs;
	}
	
	/**
	 * 审核商品不通过退款中
	 * @param number $gid 商品ID
	 * @param number $uid 管理员ID
	 * @param string $uname 管理员用户名
	 */
	public function check_refund($gid, $uid, $uname)
	{
		$result = $this->db->query('CALL proc_goods_check_refund(?,?,?,?)', array($gid, $uid, $uname,  bindec(decbin(ip2long($this->input->ip_address()))) ));
		$rs = $result->first_row('array');
		$result->free_result();
		$this->db->close();
		return $rs;
	}
	
	/**
	 * 审核商品不通过
	 * @param number $gid 商品ID
	 * @param number $uid 管理员ID
	 * @param string $uname 管理员用户名
	 */
	public function check_refused($gid, $uid, $uname)
	{
		$result = $this->db->query('CALL proc_goods_check_refused(?,?,?,?)', array($gid, $uid, $uname,  bindec(decbin(ip2long($this->input->ip_address()))) ));
		return $result->first_row('array');
	}
	
	/**
	 * 取消审核商品
	 * @param number $gid 商品ID
	 * @param number $uid 管理员ID
	 * @param string $uname 管理员用户名
	 */
	public function uncheck($gid, $uid, $uname)
	{
		$result = $this->db->query('CALL proc_goods_unchecked(?,?,?,?)', array($gid, $uid, $uname,  bindec(decbin(ip2long($this->input->ip_address()))) ));
		$rs = $result->first_row('array');
		$result->free_result();
		$this->db->close();
		return $rs;
	}
	
	/**
	 * 屏蔽商品
	 * @param number $gid 商品ID
	 * @param string $reason 原因
	 * @param number $uid 操作人ID
	 * @param string $uname 操作人用户名
	 */
	public function block($gid, $reason, $uid, $uname)
	{
		$result = $this->db->query('CALL proc_goods_block(?,?,?,?,?)', array($gid, $reason, $uid, $uname,  bindec(decbin(ip2long($this->input->ip_address()))) ));
		return $result->first_row('array');
	}
	
	/**
	 * 商品解除屏蔽
	 * @param number $gid 商品ID
	 * @param string $reason 原因
	 * @param number $uid 操作人ID
	 * @param string $uname 操作用户名
	 */
	public function unblock($gid, $reason, $uid, $uname)
	{
		$result = $this->db->query('CALL proc_goods_unblocked(?,?,?,?,?)', array($gid, $reason, $uid, $uname,  bindec(decbin(ip2long($this->input->ip_address()))) ));
		return $result->first_row('array');
	}
	
	/**
	 * 获取指定商品屏蔽原因
	 * @param int $gid 商品ID
	 */
	public function get_block_reason($gid)
	{
		$where = array();
		$where['gid'] = $gid;
		return $this->db->from('goods_block')->where($where)->get()->result_array();
	}
	
	/**
	 * 保存商品信息
	 * @param array $data 商品信息
	 * @param number $gid 商品ID
	 */
	public function save_goods_info($data, $gid = 0)
	{
		if(!is_array($data)){
			return FALSE;
		}
		$gid = $gid ? $gid : $data['gid'];
		if(!$gid){
			return FALSE;
		}
		$goods_fields = array(
				'pid','cid','title','type'
		);
		$goods_business_fields = array(
			'title','type','url'
		);
		$goods_content_fields = array(
				'seo_title','seo_keyword','seo_description','url','content','items','prompts','instruction'
		);
		$this->db->trans_start();
		foreach($data as $k=>$v){
			
			// shs_goods表
			if(in_array($k, $goods_fields)){
				$this->db->set($k, $v)->where('gid', $gid)->update('goods');
			}elseif('add_days' == $k && $v){
				// 增加天数
				$this->db->set('first_days', $data['first_days'] + $v);
				if($data['first_days'] && $data['endtime']){
					$this->db->set('endtime', $data['endtime'] + $v * 3600 * 24);
				}
				$this->db->where('gid', $gid)->update('goods');
			}
			
			// shs_goods_business表
			if(in_array($k, $goods_business_fields)){
				$this->db->set($k, $v)->where('gid', $gid)->update('goods_business');
			}elseif('add_days' == $k && $v){
				// 增加天数
				$this->db->set('first_days', $data['first_days'] + $v);
				if($data['first_days'] && $data['endtime']){
					$this->db->set('endtime', $data['endtime'] + $v * 3600 * 24);
				}
				$this->db->where('gid', $gid)->update('goods_business');
			}
			
			// shs_goods_content表
			if(in_array($k, $goods_content_fields)){
				$this->db->set($k, $v)->where('gid', $gid)->update('goods_content');
			}
		}
        //shs_stages_goods_extend表 （众分期业务）
        if($data['type'] == Goods_model::TYPE_STAGES){
            $update_data = array(
                'back_money' => $data['back_money'],
                'escape_interest_days' => $data['escape_interest_days'],
                'late_fee_percent' => $data['late_fee_percent'],
                'gifts_percent' => $data['gifts_percent'],
                'interest_percent' => $data['interest_percent']
            );
            $this->db->set($update_data)->where('gid',$gid)->update('stages_goods_extend');
        }
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	
	/**
	 * 商品结算信息
	 * @param number $gid 商品ID
	 * @return array
	 */
	public function get_checkout($gid)
	{
		$this->db->where('gid', $gid);
		return $this->db->from('goods_checkout')->get()->first_row('array');
	}
	
	/**
	 * 商品结算详细记录
	 * @param number $gid 商品ID
	 * @param number $type 结算类型
	 * @param number $limit 
	 * @param number $offset
	 * @return array
	 */
	public function get_checkout_detail($gid, $type,$limit=10,$offset=0)
	{
		$this->db->where('order_checkout.gid', $gid);
		$this->db->join('order','order.oid=order_checkout.oid');
		return $this->db->from('order_checkout')->where('order_checkout.type', $type)->where('order_checkout.state', 3)->limit($limit,$offset)->get()->result_array();
	}
	
	public function get_checkout_detail_count($gid, $type)
	{
		$this->db->where('gid', $gid);
		$rs = $this->db->select('count(oid) cnt')->from('order_checkout')->where('type', $type)->where('state', 3)->get()->row_array();
		return intval($rs['cnt']);
	}

	/**
	 * 获取商品金钱总额
	 * @param number $gid 商品ID
	 * @return array
	 */
	public function get_money_stat($gid)
	{
		$this->db->where('gid', $gid);
		return $this->db->from('goods_money_stat')->get()->row_array();
	}
	
	/**
	 * 获取活动付款信息
	 * @param number $gid 商品ID
	 * @param string $type 支付类型
	 * @param string $state 状态
	 * @param string $sort 排序
	 */
	public function get_goods_pay($gid, $type='', $state='', $sort='dateline ASC')
	{
		$this->db->where('gid', $gid);
		if ($type !=='' ){
			$this->db->where('type', $type);
		}
		if ($state !=='' ){
			$this->db->where('state', $state);
		}
		if ($sort !=='' ){
			$this->db->order_by($sort);
		}
		return $this->db->from('goods_pay')->get()->result_array();
	}
	
	/**
	 * 查询追加记录
	 * @param number $gid
	 * @return array
	 */
	public function addition_log($gid)
	{
		return $this->db->from('goods_addition')->where('gid', $gid)->where('add_num <>', 0)->order_by('id ASC')->get()->result_array();
	}
	
	/**
	 * 操作记录
	* @param int $gid
	* @param int $page 当前页，默认1
	* @param int $per 每页显示条数，默认10
	* @param string $orderby 排序，如默认：dateline DSC
	*
	* @return array('count'=>int, 'list'=>array())
	*/
	public function option_log($gid, $page=1, $per=10, $orderby='dateline DESC,id DESC')
	{
		$this->db->select('count(*) as count')->from('goods_log');
		$this->db->where(array('gid'=>$gid));
		$count = $this->db->get()->row_array();
		$row['count'] = $count['count'];
		
		$this->db->select('*')->from('goods_log');
		$this->db->where(array('gid'=>$gid));
		$orderby != '' && $this->db->order_by($orderby);
		$this->db->limit($per, ($page-1)*$per);
		$row['list'] = $this->db->get()->result_array();
		return $row;
	}
	
	/**
	 * 根据ID查询活动信息
	 * @param string|array $gids
	 * @param string $ext_where
	 * @param string $field
	 * @return array
	 */
	public function get_by_gids($gids, $ext_where = '', $field='*')
	{
		$arrGids = array();
		if (is_numeric($gids)){
			$arrGids[] = $gids;
		}elseif(is_string($gids) && strpos($gids, ',')){
			$arrGids = explode(',', $gids);
		}elseif (is_array($gids)){
			$arrGids = $gids;
		}
		if(count($arrGids) < 1){
			return array();
		}
		$this->db->where_in('gid', $arrGids);
		if('' !== $ext_where){
			$this->db->where($ext_where, '', FALSE);
		}
		return $this->db->select($field)->from('goods_business')->get()->result_array();
	}
	
	/**
	 * 管理员屏蔽活动后，需要操作结算时，将活动追加未上线的上线，以便能够操作结算
	 * @author 宁天友
	 * @param int $gid 活动ID
	 * @param int $opuid 操作者uid
	 * @param int $opname 操作用户名
	 * @return array(Code,Message) Code=>1:商品状态不是已屏蔽,2:该活动没有追加未上线记录,3:操作上线成功
	 */
	function blocked_goods_addition_online($gid, $opuid, $opname){
		$result = $this->db->query('CALL proc_goods_blocked_append_online(?,?,?,?)', array($gid, $opuid, $opname, bindec(decbin(ip2long($this->input->ip_address()))) ))->row_array();;
		$this->db->close();
		return $result;
	}
	
	/**
	 * 更改已结算活动的显示状态
	 * @param array $gids 商品id
	 * @param int $change_value 要改成的状态
	 */
	public function change_alway_show($gids,$change_value) {
		$this->db->where_in('gid',$gids);
		$this->db->update($this->_table,array(
				'alway_show'=>$change_value
		));
		return $this->db->affected_rows();
	}
	
	/**
	 * 获取活动存入担保金的类型
	 * @param int $gid
	 * @return int
	 */
	public function get_goods_deposit_type($gid)
	{
		$goods_info = $this->db->select('deposit_type')->from('goods')->where(array('gid'=>$gid))->get()->row_array();
		return $goods_info['deposit_type'];
	}
}
?>