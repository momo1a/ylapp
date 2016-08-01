<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 商品（活动）控制器
 * @author minch <yeah@minch.me>
 * @version 2013-06-06
 * @property Admin_goods_model $goods_model
 * @property user_seller_deposit_model $deposit_model
 * @property admin_goods_balance $admin_goods_balance
 * @property hlpay $hlpay
 */
class Goods extends MY_Controller 
{
	public $check_access = TRUE;
	public $except_methods = array('index');
	
	private $util;
	
	public $search_map = array(
			'gid'=>'活动编号',
			'title'=>'活动标题',
			'uname'=>'商家名称',
			'email'=>'商家邮箱',
			'uid'=>'商家编号',
		);
	
	//商品类型，对应商品表字段type。
	public $goods_types =NULL;
	public $goods_types_map = array(-1=>'所有类型');
	public $status_map;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_goods_model','goods_model');
		$this->load->model(array('goods_category_model'));
		$this->load->helper(array('form'));
		$this->goods_types = Goods_model::$type_str;
		$this->goods_types[Admin_goods_model::GOODS_IS_MOBILE_PRICE] = '手机专享价';
		$this->load->library('goods_util');
		$this->util = new Goods_util();
		$this->status_map = $this->util->get_status_map(array(''=>'所有活动状态'));
		$this->goods_types_map = $this->util->get_goods_type_map($this->goods_types, $this->goods_types_map);
	}
	
	/**
	 * 所有的活动（商品）列表
	 */
	public function index()
	{
		$goods_util = $this->util;
		$segment = $this->uri->segment(3);
		$segment = $segment ? $segment : 'all';
		$ext_where = array();
		if('all' == $segment){
			$status = $this->get_post('status', '');
		}else{
			$status = $this->_get_segment_status($segment);
			if($status==Goods_model::STATUS_BLOCKED){
					$ext_where[101]="goods.state in (21,30)";
					$status='';
			}
		}
		$goods_type = trim($this->get_post('goods_type'));
		$search_key = $this->get_post('search_key');
		$search_value = $this->get_post('search_value');
		$stime = strtotime($this->get_post('startTime'));
		$etime = strtotime($this->get_post('endTime'));
		
		//最新上线   待上线，场次筛选
		$timetype = $this->get_post('timetype');
		$parvial_field = intval($this->get_post('parvial_field'));
		$today=strtotime(date('Y-m-d',time()));
		
		if($timetype=='today'){  //今天场次时间筛选
			if($parvial_field > 0){
			   $exptime=$today+$parvial_field*60*60;     //今天场次时间
			   $ext_where['expect_online_time']=$exptime;
			}else{
			   $ext_where['expect_online_time >=']=$today;
			   $ext_where['expect_online_time <']=$today+86400;
			}  
		}else if($timetype=='tomorrow'){ //明天场次时间筛选
			if($parvial_field > 0){
			    $exptime=$today+$parvial_field*60*60+86400; //明天场次时间
			   $ext_where['expect_online_time']=$exptime;
			}else{
			   $ext_where['expect_online_time >=']=$today+86400;
			   $ext_where['expect_online_time <'] = $today+86400*2;
			}  
		}else{  //全部场次筛选
			if($parvial_field > 0){  
				$dexptime=$today+$parvial_field*60*60;     //今天场次时间
				$mexptime=$today+$parvial_field*60*60+86400; //明天场次时间
				$ext_where[100]="(expect_online_time =$dexptime or expect_online_time =$mexptime )";
			}
		}
		//最新上线   待上线，场次筛选结束
		$ext_where['gid >'] = 0;
		if(isset($this->goods_types[$goods_type])){
			switch ($goods_type){
			case Admin_goods_model::GOODS_IS_MOBILE_PRICE:
				// 手机专享价
				$ext_where['price_type'] = 2;
				break;
			default:
				// 默认用活动类型筛选活动
				$ext_where['type'] = $goods_type;
				break;
			}
		}else{
			$goods_type = '-1';
		}
		$order = 'gid desc';

		$limit = 10;
		
		$offset = $this->uri->segment(4);
		$items = $this->goods_model->search($search_key, $search_value, $status, $stime, $etime, $ext_where, $order, $limit, $offset);
		$items_count = $this->goods_model->search_count($search_key, $search_value, $status, $stime, $etime, $ext_where);
		
		if (count($items) >0) {
			foreach ($items as $key=>$va){
				//获得活动类型uid
				$uid[]=$va['uid'];
				
				// 第三方平台url
				if($va['price_type'] == Goods_model::PRICE_TYPE_MOBILE)
				{
					$goods_content = $this->db->select('url')->from('goods_content')->where('gid',$va['gid'])->get()->row_array();
					$items[$key]['third_party_url'] = isset($goods_content['url']) ? $goods_content['url'] : '';
				}
			}
			$deposit_type=$this->db->select('uid,state,deposit_type')->from('user_seller_deposit')->where_in('uid',$uid)->get()->result_array();
			$yzcm=$mpg=array();
			foreach ( $deposit_type as $key=> $v) {
				if($v['deposit_type']==1){
					$yzcm[$v['uid']]['deposit_type']=$v['deposit_type'];
					$yzcm[$v['uid']]['state']=$v['state'];
				}else if($v['deposit_type']==2){
					$mpg[$v['uid']]['deposit_type']=$v['deposit_type'];
					$mpg[$v['uid']]['state']=$v['state'];
				}
			}
		}

		$page_conf = array('uri_segment'=>4,'anchor_class'=>'type="load" rel="div#main-wrap"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment);
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment.'/0');
		$pager = $this->pager($items_count, $limit, $page_conf);
         $user_id=$this->user_id;
		$this->load->view('goods/index', get_defined_vars());
	}
	
	/**
	 * 活动导出函数
	 * @author 杨积广
	 */
	public function exportgoods(){ 
		
		$status = $this->get_post('status','');
		$goods_type = trim($this->get_post('goods_type'));
		$search_key = $this->get_post('search_key');
		$search_value = $this->get_post('search_value');
		$stime = strtotime($this->get_post('startTime'));
		$etime = strtotime($this->get_post('endTime')); 
		if(!$stime OR !$etime){
			$this->error('由于数据太多请务必选择起止时间!');
		}
		$ext_where['gid >'] = 0;
		if(isset($this->goods_types[$goods_type])){
			switch ($goods_type){
				case Admin_goods_model::GOODS_IS_MOBILE_PRICE:
					// 手机专享价
					$ext_where['price_type'] = 2;
					break;
				default:
					// 默认用活动类型筛选活动
					$ext_where['type'] = $goods_type;
					break;
			}
		}else{
			$goods_type = '-1';
		}
		$order = 'gid desc';
		$items_count = $this->goods_model->search_count($search_key, $search_value, $status, $stime, $etime, $ext_where);
		if($items_count >10000){
			$this->error('由于数据太多超过10000条，请缩小起始时间跨度或者增加其它筛选条件！');
		}
		$items = $this->goods_model->search($search_key, $search_value, $status, $stime, $etime, $ext_where, $order,10000);
		// 整理数据
		$data=array();
		foreach ($items as $k=>$v){
			$vstate=$this->util->get_status($v['state']);
			$vtype=$this->util->get_goods_type($this->goods_types_map, $v['type']);
			$vfee=in_array($v['state'], array(1,2,11,13))? 0 : $v['paid_guaranty']+$v['paid_fee'];
                if($v['state']==32)
                {
                     $seed=$v['uid'];
                }else{
                    $seed=$v['dateline'];
                }
			$url=create_fuzz_link($v['gid'], $v['state'], $seed);
			
			// 手机专享价
			$mobile_price = '无'; 
			if ($v['price_type'] == 2)
			{
				$mobile_price = $v['mobile_price'];
			}
			
			$data[] = array(
					$v['gid'],
					$v['title'],
					$url,
					$v['uname'],
					$v['email'],
					$v['uid'],
					date('Y-m-d H:i:s',$v['dateline']),
				    $v['first_starttime']? date('Y-m-d H:i:s',$v['first_starttime']).' 到 '. date('Y-m-d H:i:s',$v['endtime']) :'无',
					$v['first_days'],
					$v['quantity'],
					$v['price'].'/'.$v['discount'],
					$mobile_price,
					(($v['price']+$v['single_fee'])*$v['quantity']).'/'.$vfee,
					$v['mobile'],
					$vstate,
					$vtype
			);
		}
		$title = '活动数据导出';
		$filename = '活动数据导出'.date("Y-m-d", $stime).' - '.date("Y-m-d",$etime).'.xls';
		$header = array(
				'活动编号',
				'活动标题',
				'活动链接',
				'商家名称',
                '商家邮箱' ,
                '商家编号',
				'发布时间',
				'活动时间',
				'活动天数',
				'数量',
				'网购价/折扣',
				'手机专享价',
				'应存费用/已存费用',
				'联系商家',
				'活动状态',
				'活动类型'
		);
		array_unshift($data, $header);
		$this->data_export($data, $title, $filename);
	} 
	
	
	/**
	 * 获取商品状态
	 * @param string $segment
	 * @return Ambigous <string, number>
	 */
	private function _get_segment_status($segment)
	{
		$status = '';
		switch ($segment){
			case 'unckeck':
				$status = Goods_model::STATUS_UNCHECK_PAID;
				break;
			case 'checked':
				$status = Goods_model::STATUS_CHECKED;
				break;
			case 'online':
				$status = Goods_model::STATUS_ONLINE;
				break;
			case 'offline':
				$status = Goods_model::STATUS_OFFLINE;
				break;
			case 'havechance':
				$status = Goods_model::STATUS_HAVE_CHANCE;
				break;
			case 'blocked':
				$status = Goods_model::STATUS_BLOCKED;
				break;
			case 'checkout':
				$status = Goods_model::STATUS_CHECKOUT;
				break;
			case 'closed':
				$status = Goods_model::STATUS_CHECKOUT_CLOSED;
				break;
			default:
		}
		return $status;
	}
	
	/**
	 * 删除商品
	 */
	public function delete()
	{
		$gid = $this->get_post('gid');
		if(!$gid){
			$this->error('非法操作,商品ID错误');
		}
		$rs = $this->goods_model->delete($gid);
		if($rs){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}
	
	/**
	 * 编辑商品方法
	 */
	public function edit()
	{
		$this->load->model('user_seller_deposit_model', 'deposit_model');
        $this->load->model('stages_goods_extend_model','stg_ext_model');
		$gid = $this->get_post('gid');
		$goods = $this->goods_model->get($gid);
		$cates = $this->goods_category_model->get_all();
        $goods['stg_ext'] = $this->stg_ext_model->get_goods_by_gid($gid);
		$goods['items']=unserialize($goods['items']);
		$goods['prompts']=unserialize($goods['prompts']);
		
		if('yes' == $_POST['dosave']){
			$goods['seo_title'] = trim(strval($this->get_post('seo_title')));
			if(!$goods['seo_title']){
				$this->error('请输入浏览器标题');
			}
			$goods['title'] = trim(strval($this->get_post('title')));
			if(!$goods['title']){
				$this->error('请输入标题');
			}
			$goods['content'] = trim(strval($this->get_post('content')));
			
			$goods['seo_keyword'] = trim(strval($this->get_post('seo_keyword')));
			if('' === $goods['seo_keyword']){
				$this->error('请输入关键字');
			}
			$goods['pid'] = intval($this->get_post('pid'));
			if(!$goods['pid']){
				$this->error('请选择主分类');
			}
			if( $goods['type']==Goods_model::TYPE_SEARCH_BUY ){
				if( !in_array($goods['pid'], explode(',', config_item('search_buy_category_pids'))) ){
					$this->error('搜索下单活动不允许设置成该分类');
				}
			}
			$goods['cid'] = intval($this->get_post('cid'));
			if(!$goods['cid']){
				$this->error('请选择子分类');
			}
			$goods['url'] = trim(strval($this->get_post('url')));
			if(!$goods['url']){
				$this->error('请输入下单地址');
			}elseif(0 !== strpos($goods['url'], 'http')){
				$this->error('请下单地址不正确,必需以http开头');
			}
			$goods['seo_description'] = trim(strval($this->get_post('seo_description')));
			
			//温馨提示项
			$goods['items']= serialize($this->get_post('items'));
			$goods['prompts']= serialize($this->get_post('prompts'));
			$goods['instruction']=trim($this->get_post('instruction'));//拍下须知

			$_type =  intval($this->input->get_post('goodstype', TRUE));
            //如果是众分期活动 接收众分期活动扩展表参数
            if($_type == Goods_model::TYPE_STAGES){
                //返利金额
                $goods['back_money'] = round($this->input->get_post('back_money',TRUE),2,PHP_ROUND_HALF_ODD);
                //免息天数
                $goods['escape_interest_days'] = intval($this->input->get_post('escape_interest_days',TRUE));
                //滞纳金百分比
                $goods['late_fee_percent'] = round($this->input->get_post('late_fee_percent',true),2,PHP_ROUND_HALF_ODD);
                //礼包百分比
                $goods['gifts_percent'] = intval($this->input->get_post('gifts_percent',true));
                //利息百分比
                $goods['interest_percent'] = round($this->input->get_post('interest_percent',true),2,PHP_ROUND_HALF_ODD);
            }
			if( in_array($_type, array(0,2,Goods_model::TYPE_STAGES)) && in_array($goods['type'], array(0,2,Goods_model::TYPE_STAGES)) ){
				$goods['type'] = $_type;
			}else{
				unset($goods['type']);
			}
			$goods['add_days'] = intval($this->get_post('days'));
			$rs = $this->goods_model->save_goods_info($goods);
			if($rs){
				$this->log('修改商品SEO信息成功', array_merge($_GET, $_POST));
				$this->success('保存成功');
			}else{
				$this->log('修改商品SEO信息失败', array_merge($_GET, $_POST));
				$this->error('保存失败');
			}
		}
		//查询温馨提示项
		$prompts_list = $this->db->select ( '*' )->from ( 'goods_prompts' )->where(array('goods_type'=>$goods['type'], 'state'=>'1'))->order_by('type','asc')->get ()->result_array();
		
		// 活动类型
		$goods_types = $this->goods_types;
		unset($goods_types['1000']);
		$goods['goods_type']  = $goods_types;
		
		$goods['seo_title'] = $goods['seo_title'] ? $goods['seo_title'] : $goods['title'];
		$goods['seo_keyword'] = $goods['seo_keyword'] ? $goods['seo_keyword'] : $goods['keyword'];
		$goods['seo_description'] = $goods['seo_description'] ? $goods['seo_description'] : mb_substr(trim(strip_tags($goods['content'])), 0, 200);
		$this->load->view('goods/edit', get_defined_vars());
	}
	
	/**
	 * 审核通过活动的时候检测商家状态
	 * @param int $gid 活动gid
	 *
	 * @author 杜嘉杰
	 * @version 2015年11月20日  下午2:25:25
	 *
	 */
	private function check_goods_user_state($gid)
	{
		//更新用户屏蔽状态
		$user_lock =$this->get_user_lock($gid);
		if ($user_lock == 3 ) {
			$this->error('该商家目前屏蔽（严重屏蔽），无法上线！');
		}elseif($user_lock == 4){
			$this->error('该商家目前屏蔽（很严重屏蔽），不能进行任何操作！');
		}elseif($user_lock == 5){
			$this->error('该商家目前已封号，无法上线！');
		}elseif($user_lock == 9){
			$this->error('该商家目前为特殊商家，无法审核上线！');
		}
	}
	
	/**
	 * 审核通过商品
	 */
	public function check()
	{
		$gid = intval($this->get_post('gid'));
		$this->check_goods_user_state($gid);

		if($this->get_post('showform') == 'yes'){
			$this->load->view('goods/check_form', get_defined_vars());
		}else{
			$goods_default_online_type=unserialize($this->config->item('goods_default_online_type'));
			$online_type=isset($goods_default_online_type[$this->user_id])?$goods_default_online_type[$this->user_id]:0;
			if($online_type==1){//系统自动类型获取整场分点的时间
				$today=strtotime(date('Y-m-d',time()));
				$curtime=time();
				$mtime=0;
				$goods_new_parvial_field=explode(',',$this->config->item('goods_new_parvial_field'));
				foreach($goods_new_parvial_field as $k=>$val){
					$val=intval($val);
					if(($today+$val*60*60) > $curtime){
						$mtime= $today+$val*60*60;
						break;
					}
				}
				$online_time=$mtime >0 ? $mtime : $today+intval($goods_new_parvial_field[0])*60*60+86400;
			}elseif($online_type==3){//设置默认类型获取设置的时间
				$online_type_value=unserialize($this->config->item('goods_default_online_type_value'));
				$online_time=isset($online_type_value[$this->user_id])?intval($online_type_value[$this->user_id]):0;
			}else{ //全部手动设置
				$endTimetype=intval($this->get_post('endTimetype'));
				$online_time = $endTimetype ? $endTimetype : strtotime(strval($this->get_post('endTime')));
			}
			
			if(!$online_time OR $online_time < time()){
				$this->error('审核失败,请选择正确的上线时间');
			}
			$rs = $this->goods_model->check($gid, $this->user_id, $this->username, $online_time);
			
			// 调用java接口添加收藏推送任务
			$this->goods_remind($gid, $online_time);
			
			if($rs['Code']){
				$this->error('操作失败'.$rs['Message']);
			}else{
				$this->success('操作成功');
			}
		}
	}
	
	/**
	 * 活动任务提醒
	 * 
	 * @param int $gid 活动id
	 * @param int $online_time活动上线时间
	 * @return boolean
	 * 
	 * @author 杜嘉杰
	 * @version 2015年5月11日 下午2:53:16
	 */
	private function goods_remind($gids, $online_time)
	{
		if(is_array($gids) == FALSE)
		{
			$gids = array($gids);
		}
		foreach ($gids as $gid)
		{
			$adjust_time = intval(config_item('goods_collect_online_remind_prefix_time'));
			$remind_time = $online_time - $adjust_time*60;
			batch_goods_remind($gid, '有活动即将上线啦，赶快准备！', $remind_time);
		}
	}
	
	/**
	 * 审核不通过活动
	 * 
	 * @author 杜嘉杰
	 * @version 2014-12-3
	 */
	public function check_refund()
	{
		$gid = intval($this->get_post('gid'));

		// 先把状态改为审核不通过退款中
		$rs = $this->goods_model->check_refund($gid, $this->user_id, $this->username);
		if($rs['Code']){
			$this->error('操作失败'.$rs['Message']);
		}
		$pay_rs = $this->_refund($gid);
		if($pay_rs > 0){
			// 互联支付退款成功,把状态改为审核不通过
			$rs = $this->goods_model->check_refused($gid, $this->user_id, $this->username);
		}else{
			$this->error('互联支付退款失败', $pay_rs);
		}
		if($rs['Code']){
			$this->error('操作失败'.$rs['Message']);
		}else{
			$this->success('操作成功');
		}
	}
	
	/**
	 * 退款给商家
	 * @param number $gid 活动ID
	 */
	private function _refund($gid)
	{
		$this->load->library('Hlpay', NULL, 'pay_service');
		$goods = $this->goods_model->get($gid);
		
		$money_state = $this->goods_model->get_money_stat($gid);
		$goods_pays = $this->goods_model->get_goods_pay($gid,1,2);
		$goods_pay = array_pop($goods_pays);
		if ($money_state['guaranty'] != $goods_pay['guaranty'] OR $money_state['fee'] != $goods_pay['fee'] OR $money_state['search_reward'] != $goods_pay['search_reward']){
			$this->error('活动担保金额异常');
		}
		
		$refund_pays = $this->goods_model->get_goods_pay($gid, 2, 1);
		$refund_pay = array_pop($refund_pays);
		if(!$refund_pay['gid']){
			mt_srand((double)microtime() * 1000000);
			//互联支付退款接口中只需要一个退款单号
			$rand_pno = date('YmdHis', time()).mt_rand(1000, 9999);
			//在前面加2个6、7分别表示退款担保金订单号、服务费订单号，避免重复
			$guaranty_pno = '66'.$rand_pno;
			$fee_pno = '77'.$rand_pno;
			$search_reward_pno = '88'.$rand_pno;
			
			//不存在订单记录就新增,退款的数据记录
			$goodsPayData = array(
				'gid'            => $gid,                         //退款商品ID
				'type'           => 2,                            //类型2标识“退款”
				'dateline'       => time(),                       //退款时间
				'guaranty_pno'   => $guaranty_pno,                //退款的担保金订单号
				'fee_pno'        => $fee_pno,                     //退款的服务费订单号（只是众划算记录，互联支付不记录）
				'search_reward_pno' => $search_reward_pno,              //退款的搜索奖励金订单号（只是众划算记录，互联支付不记录）
				'guaranty'       => $money_state['guaranty'],     //退款的担保金
				'fee'            => $money_state['fee'],          //退款的服务费
				'search_reward'  => $money_state['search_reward'],//退款的搜索奖励金
				'add_day'        => 0,
				'add_num'        => 0,
				'state'          => 1,                            //退款状态，1表示退款中
			);
			$orderId = $this->db->insert_string('goods_pay', $goodsPayData);
			$rs = $this->db->query($orderId);
			if (!$rs){
				$this->error('生成退款订单失败');
			}
		}else{
			if ($money_state['guaranty'] != $refund_pay['guaranty'] OR $money_state['fee'] != $refund_pay['fee']){
				$this->error('活动担保金额异常');
			}
			$guaranty_pno = $refund_pay['guaranty_pno'];
		}
		$pay_rs = $this->pay_service->goods_refund($gid, $goods['uid'], $goods['title'], bcadd($money_state['guaranty'],$money_state['fee'],2), $guaranty_pno, $goods_pay['guaranty_pno'] );
		return $pay_rs;
	}
	
	/**
	 * 取消审核通过
	 */
	public function uncheck()
	{
		$gid = intval($this->get_post('gid'));
		$rs = $this->goods_model->uncheck($gid, $this->user_id, $this->username);
		if($rs['Code']){
			$this->error('取消审核失败'.$rs['Message']);
		}else{
			// 删除收藏提醒数据
			$this->load->model('goods_remind_model');
			$this->goods_remind_model->delete_where(array('gid'=>$gid));
				
			$this->success('取消审核成功');
		}
	}
	
	/**
	 * 设置活动上线时间
	 */
	public function set_online_time()
	{
		if($this->get_post('showform') == 'yes'){
			$gids = $this->get_post('gids');
			$state_checked = Goods_model::STATUS_CHECKED;
			$ext_where = "state={$state_checked}";
			$goods_list = $this->goods_model->get_by_gids($gids, $ext_where, 'gid,state,title,expect_online_time');
			$gids = array(); // 获取满足条件的活动编号
			foreach ($goods_list as $k=>$v){
				$gids[] = $v['gid'];
			}
			if(count($gids) < 1){
				$this->error('请选择活动');
			}
			$this->load->view('goods/set_online_time', get_defined_vars());
		}elseif($this->get_post('dosave') == 'yes'){
			$gids = $this->get_post('gids');
			
			//更新用户屏蔽状态
			$this->load->model('admin_user_model','user_model');
			$this->user_model->update_lock_status();

			//更新用户屏蔽状态
			$this->load->model('admin_user_model','user_model');
			$this->user_model->update_lock_status();
			//判断用户是否被屏蔽
			$user_lock = $this->db->select('is_lock')->from('user')->join('goods_business','user.uid=goods_business.uid')->where('gid',$gids)->get()->row_array();
			if($user_lock == 4){
				$this->error('操作失败,活动编号'.$gids.'的商家已屏蔽，不能设置上线时间操作！');
			}elseif ($user_lock == 5){
				$this->error('该商家目前屏蔽（严重屏蔽），无法上线！');
			}
			
			$online_time = strtotime($this->get_post('endTime'));
			$rs = $this->goods_model->set_online_time($gids, $online_time, $this->user_id, $this->username);
			if($rs['Code']){
				$this->error('操作失败'.$rs['Message']);
			}else{
				// 调用java接口添加收藏推送任务
				$gids_arr = explode(',', $gids);
				$this->goods_remind($gids_arr,$online_time);
				$this->success('操作成功');
			}
		}
	}
	
	/**
	 * 手动上线商品
	 */
	public function set_online()
	{
		$gid = intval($this->get_post('gid'));
		$rs = $this->goods_model->set_online($gid, $this->user_id, $this->username);
		if($rs['Code']){
			$this->error('手动上线失败'.$rs['Message']);
		}else{
			$this->success('手动上线成功');
		}
	}
	
	/**
	 * 手动下架
	 */
	public function set_offline()
	{
		$gid = intval($this->get_post('gid'));
		$rs = $this->goods_model->set_offline($gid, $this->user_id, $this->username);
		if($rs['Code']){
			$this->error('商品下架失败'.$rs['Message']);
		}else{
			$this->success('商品下架成功');
		}
	}
	
	/**
	 * 屏蔽商品
	 */
	public function block()
	{
		$gid = intval($this->get_post('gid'));
		if('yes' == $_POST['dosave']){
			$reason = $this->get_post('reason', '');
			$rs = $this->goods_model->block($gid, $reason, $this->user_id, $this->username);
			if($rs['Code']){
				$this->error('操作失败'.$rs['Message']);
			}else{
				$this->success('操作成功');
			}
		}else{
			$this->load->view('goods/block_form', get_defined_vars());
		}
	}
	
	/**
	 * 取消屏蔽
	 */
	public function unblock()
	{
		$gid = intval($this->get_post('gid'));
		if('yes' == $_POST['dosave']){
			$reason = $this->get_post('reason', '');
			$rs = $this->goods_model->unblock($gid, $reason, $this->user_id, $this->username);
			if($rs['Code']){
				$this->error('操作失败'.$rs['Message']);
			}else{
				$this->success('操作成功');
			}
		}else{
			$this->load->view('goods/block_form', get_defined_vars());
		}
	}
	
	/**
	 * 屏蔽原因
	 */
	public function block_reason()
	{
		$gid = $this->get_post('gid');
		$reasons = $this->goods_model->get_block_reason($gid);
		$this->load->view('goods/block_reason', get_defined_vars());
	}
	
	/**
	 * 结算记录
	 */
	public function checkout_detail()
	{
		$gid = $this->get_post('gid');
		$logs = $this->goods_model->get_checkout_detail($gid);
		$this->load->view('goods/balance_log', get_defined_vars());
	}
	
	/**
	 * 结算记录
	 * int $segment 活动ID
	 */
	public function checkout()
	{
		$segment = $this->uri->segment(3);
		if($segment){
			$gid = $segment;
		}else{
			$gid = $this->get_post('gid');
		}
		$type = intval($this->get_post('type'));
		if(!$type){
			$type = 1;
			$checkout_count = $this->goods_model->get_checkout_detail_count($gid, 2);
		}
		
		$goods = $this->goods_model->get($gid);
		$goods_checkout = $this->goods_model->get_checkout($gid);
		
		$limit = 10;
		$offset = $this->uri->segment(4);
		
		$count = $this->goods_model->get_checkout_detail_count($gid, $type);
		$logs = $this->goods_model->get_checkout_detail($gid, $type, $limit, $offset);
		$deposit_type = $this->goods_model->get_goods_deposit_type($gid);
		
		$page_conf = array('uri_segment'=>4,'anchor_class'=>'type="load" rel="div#checkout_list_'.$type.'" data-type="'.$type.'" data-listonly="yes"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment);
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$segment.'/0');
		$pager = $this->pager($count, $limit, $page_conf);
		
		if($this->is_ajax() && $this->get_post('listonly')){
			$this->load->view('goods/balance_log', get_defined_vars());
		}else{
			$this->load->view('goods/checkout', get_defined_vars());
		}
	}
	
	/**
	 * 推荐商品
	 */
	public function set_recommend()
	{
		$type = $this->get_post('type');
		$gid = $this->get_post('gid');
		$cid = $this->get_post(cid);
		if($type == Common_recommend_model::RECOMMEND_CATEGORY){
			$rs = $this->common_recommend_model->set_category_goods($cid, $gid);
		}else{
			$rs = $this->common_recommend_model->set_recommend($type, $gid);
		}
		if($rs){
			$this->success('推荐成功');
		}else{
			$this->error('推荐失败');
		}
	}
	
	/**
	 * 结算记录
	 */
	public function balance_log()
	{
		$this->load->model('goods_balancerecord_model');
		$gid = $this->get_post('gid');
		$logs = $this->goods_balancerecord_model->getby_gid($gid);
		$this->load->view('goods/balance_log', get_defined_vars());
	}
	
	/**
	 * 商品订单列表页面
	 */
	public function order()
	{
		$this->_order_search();
	}
	
	/**
	 * 订单流程记录
	 */
	public function order_flow()
	{
		$this->load->model('admin_order_model','order_model');
		$this->load->model('order_log_model');
		$oid = $this->get_post('oid');
		$order = $this->order_model->get($oid);
		$logs = $this->order_log_model->getby_oid($oid);
		$this->load->view('goods/order_flow', get_defined_vars());
	}
	
	/**
	 * 进入活动 - 订单搜索
	 */
	private function _order_search()
	{
		$this->load->model('admin_order_model','order_model');
        $this->load->model('admin_stages_order_model','stage_order_model');
        $this->load->library('order_util');
		
		$gid = intval($this->uri->segment(3));
		$gid = $gid ? $gid : intval($this->get_post('gid'));
		
		// 获取搜索信息
		$search_key = isset($_GET['search_key']) ? trim(strip_tags($_GET['search_key'])) : '';
		$search_val = isset($_GET['search_val']) ? trim(strip_tags($_GET['search_val'])) : '';

		$goods = $this->goods_model->get($gid);

		$order_util = new Order_util();
		$extrm_where = array();
		$goods['type'] == Goods_model::TYPE_STAGES ?  $extrm_where['stages_order.gid'] = $gid : $extrm_where['order.gid'] = $gid;
		$limit = 10;
		$offset = intval($this->uri->segment(4));

        //如果是分期活动 调用分期活动的订单数据
        if($goods['type'] != Goods_model::TYPE_STAGES) {
            $orders = $this->order_model->search($search_key, $search_val, '', '*', $extrm_where, $limit, $offset);
            $items_count = $this->order_model->search_count($search_key, $search_val, '', $extrm_where);
        }else{
            $orders = $this->stage_order_model->search($search_key, $search_val, '', '*', $extrm_where, $limit, $offset);
            $items_count = $this->stage_order_model->search_count($search_key, $search_val, '', $extrm_where);
        }
		$page_conf = array('uri_segment'=>4,'anchor_class'=>'type="load" rel="div#main-wrap"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$gid);
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$gid.'/0');
		$pager = $this->pager($items_count, $limit, $page_conf);
		
		$this->load->helper('image_url');
		$goods['img'] = image_url($goods['gid'], $goods['img']).'_60x60.jpg';
        $show_type = $goods['type'] == Goods_model::TYPE_STAGES ?  'stages_order':'order';
		$this->load->view('goods/'.$show_type, get_defined_vars());
	}

    /**
     * 确认分期订单付款操作
     */
    public function confirm_pay(){
        $this->load->model('admin_stages_order_model','stages_order_model');
        $this->load->model('admin_log_model');
        $oid = intval($this->input->get_post('oid',true));
        $user = get_user();
        $username = $user['name'];
        $uid = $user['id'];
        $content = '确认付款';
        $ip = ip2long($this->input->server('REMOTE_ADDR'));
        $res = $this->stages_order_model->confirm_pay($oid,$username,$uid,$content,$ip);

        if(intval($res['Code'] === 1)){
            $this->admin_log_model->save($uid,$username,'确认付款成功',serialize($_GET));
            $this->success('操作成功');
        }else{
            $this->admin_log_model->save($uid,$username,'确认付款失败',serialize($_GET));
            $this->success('操作失败');
        }

    }

	/**
	 * 扣除返现金额记录
	 */
	public function deduct_money()
	{
		$this->load->model('order_adjust_rebate_model');
		$search_key = $this->get_post('search_key');
		$search_val = $this->get_post('search_val');
		$amount = $this->get_post('amount');
		
		$start_time = trim($this->get_post('startTime'));
		$end_time = trim($this->get_post('endTime'));
		
		$startTime = strtotime($start_time);
		$endTime = strtotime($end_time);

		$limit = 10;
		$offset = $this->uri->segment(3);
		
		$list = $this->order_adjust_rebate_model->get($search_key, $search_val, $amount, $startTime, $endTime, $limit, $offset);
		$total = $this->order_adjust_rebate_model->count($search_key, $search_val, $amount, $startTime, $endTime);
		
		$oids = array();
		foreach ($list as $row)
		{
			$oids[] = $row['oid'];
		}
		// $oids不为空才执行，否则find_all会报SQL语法错误，fix BUG#2245
		if(count($oids) > 0)
		{
			// 根据oid查找订单
			$this->load->model('YL_order_model');
			$order_fields = 'oid, site_type, fill_site_type';
			$order_list = array();
			foreach ($this->YL_order_model->select($order_fields)->where_in('oid', $oids)->find_all() as $order)
			{
				$order_list[$order['oid']] = $order;
			}
			foreach ($list as $k=>$v)
			{
				$list[$k]['order_site_type'] = $order_list[$v['oid']]['site_type'];
				$list[$k]['order_fill_site_type'] = $order_list[$v['oid']]['fill_site_type'];
			}
		}

		unset($oids);
		unset($order_list);
		unset($_GET['listonly']);
		$page_conf = array('anchor_class'=>'type="load" rel="div#main-wrap" data-listonly="yes"');
		$pager = $this->pager($total, $limit,$page_conf);
		$data = array(
			'start_time' => $start_time,
			'end_time' => $end_time,
			'pager' => $pager,
			'list' => $list,
			'amount' => $amount,
			'search_key' => $search_key,
			'search_val' => $search_val,
			'offset' => $offset,
		);
		$this->load->view('goods/deduct_money', $data);
	}
	
	/**
	 * 扣除返现金额不成功记录
	 */
	public function deduct_money_failed()
	{
		$this->load->view('goods/deduct_money_failed', get_defined_vars());
	}
	
	/**
	 * 追加记录
	 */
	public function addition_log()
	{
		$gid = intval($this->input->get('gid'));
		if ($gid) {
			$goods = $this->goods_model->get($gid);
			$logs = $this->goods_model->addition_log($gid);

			$batch = array();
			// 一站成名
			if ($goods['type'] == Goods_model::TYPE_YZCM) {
				foreach ($logs as $log) {
					if ($log['pid'] > 0)
						$batch[$log['pid']][] = $log;
				}
			}
			$this->load->view('goods/addition_log', array('logs'=>$logs, 'goods'=>$goods, 'batch'=>$batch));
		}
	}

	/**
	 * 操作记录
	 */
	public function option_log()
	{
		$per = 10;
		$gid = $this->get_post('gid');
		$page = intval($this->uri->segment(3));
		$page = $page > 0 ? $page : $this->get_post('page', 1, TRUE);
		$this->option_log_data($gid, $page, $per);
	}
	
	/**
	 * 获取订单日志数据
	 * @param int $gid
	 * @param int $page 总记录数
	 * @param int $per 每页显示记录数
	 *
	 * @author 杜嘉杰
	 * @version 2015年11月20日  上午9:52:26
	 *
	 */
	private function option_log_data($gid, $page, $per)
	{
		$logs = $this->goods_model->option_log($gid, $page, $per);
		$total = $logs['count'];
		$logs = $logs['list'];
		$page_conf = array(
			'first_url'=>site_url($this->router->class.'/'.$this->router->method.'/1'),
			'anchor_class'=>'data-listonly="yes" onclick="load($(this).attr(\'href\'),\'div#option_list_div\', $(this).data());return false;"',
			'use_page_numbers' => TRUE
		);
		$pageString = $this->pager($total, $per, $page_conf);
		$this->load->view('goods/option_log', get_defined_vars());
	}
	
	/**
	 * 开团提醒记录
	 */
	public function remind()
	{
		$this->load->model('goods_remind_model');

		$startTime = strtotime($this->get_post('startTime'));
		$endTime = strtotime($this->get_post('endTime'));
		$endTime = $endTime ? $endTime+24*3600 : 0;
		$search_key = trim(strval($this->get_post('search_key')));
		$search_val = trim(strval($this->get_post('search_val')));
		
		$limit = 10;
		$offset = $this->uri->segment(3);
		$ext_where = '';
		$list = $this->goods_remind_model->search($startTime, $endTime, $search_key, $search_val, $ext_where, 'id DESC', $limit, $offset);
		$total = $this->goods_remind_model->search_count($startTime, $endTime, $search_key, $search_val, $ext_where);
		
		$page_conf = array('anchor_class'=>'type="load" rel="div#remind_list" data-listonly="yes"');
		$pager = $this->pager($total, $limit, $page_conf);
		
		if($this->is_ajax() && $this->get_post('listonly') == 'yes' ){
			$this->load->view('goods/remind_list', get_defined_vars());
		}else{
			$this->load->model('system_stat_model');
			$stat_keys = array('goods_online_remind_total','goods_online_remind_yes','goods_online_remind_no','goods_addition_remind_total','goods_addition_remind_yes','goods_addition_remind_no');
			$system_stat = array();
			foreach ($this->system_stat_model->get($stat_keys) as $k=>$v){
				$system_stat[$v['stat_key']] = $v['stat_value'];
			}
			$this->load->view('goods/remind', get_defined_vars());
		}
	}
	
	/**
	 * 管理员取消买家资格
	 */
	function cancel_join(){
		$oid = intval($this->input->get_post('oid'));
		$reason = trim($this->input->get_post('reason'));
		$submit = intval($this->input->get_post('submit'));
		$this->load->model('order_model','order_model');
		$this->load->model('admin_order_model','admin_order_model');
		$order = $this->order_model->get($oid);
		if(empty($order)){
			$this->error('商品订单不存在');
		}elseif( ! in_array($order['state'], array(3,4))){
			$this->error('商品订单不允许该操作');
		}
		if($submit > 0){
			$params = array(
				'oid' =>$oid,
				'reason' => $reason
			);
			$execBack = $this->admin_order_model->set_cancel($params);
			$execBack['Code'] = intval($execBack['Code']);
			if($execBack['Code'] === 1){
				$this->success('取消买家资格成功');
			}else{
				$this->error('取消买家资格失败。(错误:'.$execBack['Message'].')');
			}
		}else{
			$this->load->view('goods/cancel_join', get_defined_vars());
		}
	}
	
	/**
	 * 获取用户屏蔽状态
	 * @param unknown $gid
	 */
	private function get_user_lock($gid){
		$this->load->model('admin_user_model','user_model');
		//更新屏蔽到期用户
		$this->user_model->update_lock_status();
		$user = $this->db->select('is_lock')->from('user')->join('goods_business','user.uid=goods_business.uid')->where('gid',$gid)->get()->row_array();
		return $user['is_lock'];
	}

	/**
	 * 活动管理已屏蔽列表-结算活动
	 *@author 杨积广
	 */
	function balance(){
    	$this->load->model('admin_goods_balance');
		$gid = intval($this->input->get_post('gid'));
		if($gid){
			
			// 如果活动有未填单号的订单允许结算
			$unfull_count = $this->db->from('order')->where( array('gid'=>$gid,'state'=>Order_model::STATUS_UNFILL))->count_all_results();
			if($unfull_count>0){
				$this->error('目前活动存在未填写单号的订单，不允许结算活动');
			}
			
			$doBalanceSubmit = intval($this->input->post('doBalanceSubmit',true));
			$goodsInfo = $this->admin_goods_balance->get_goods_info($gid);
			if($goodsInfo['state'] == Goods_model::STATUS_BLOCKED){
				//检测追加未上线的记录，若存在则上线
				$check_addition_online = $this->goods_model->blocked_goods_addition_online($gid, $this->user_id, $this->username);
				if( ! in_array($check_addition_online['Code'], array(2,3))){
					$this->error($check_addition_online['Message']);
				}
				//重新获取活动数据
				$goodsInfo = $this->admin_goods_balance->get_goods_info($gid);
			}
			//检测是否已有整点上线的等待上线的追加记录
			$has_wait_online_addition = $this->admin_goods_balance->check_goods_addition($gid);
			if(empty($goodsInfo)){
				$this->error('商品不存在');
			}elseif($has_wait_online_addition || $goodsInfo['pay_state'] == 7 || $goodsInfo['state'] == Goods_model::STATUS_HAVE_CHANCE
					|| ! in_array($goodsInfo['state'], array(Goods_model::STATUS_BLOCKED,Goods_model::STATUS_OFFLINE, Goods_model::STATUS_CHECKOUT_PAYING))){//状态不为：已下架|商品结算付款中
				$this->error('当前商品状态不允许该操作');
			}
			
			//检测是否存在结算支付订单
			$orderInfo = $this->admin_goods_balance->get_balance_order($gid);
			$orderId = isset($orderInfo['id'])?$orderInfo['id']:0;
			$orderInfo['state'] = isset($orderInfo['state'])?$orderInfo['state']:0;
			if($orderInfo['state'] == 2){//结算记录已是成功结算状态
				$this->error('商品已结算，不能重复操作');
			}
			$goodsMoneyStat = $this->admin_goods_balance->get_goods_money_stat($gid);
      
			if($orderId > 0){ 
				$guaranteeOrder = $orderInfo['guaranty_pno'];
				$serviceOrder = $orderInfo['fee_pno'];
				$searchOrder = $orderInfo['search_reward_pno'];
	
				//剩余份数=结算订单记录的份数
				$remainQuantity = $orderInfo['num'];
				//可结算的担保金金额 =结算订单记录的担保金金额
				$guaranteeMoney = $orderInfo['guaranty'];
				//可结算的服务费金额 = 结算订单记录的服务费金额
				$serviceMoney = $orderInfo['fee'];
				// 是搜索下单奖励金
				$search_reward= $orderInfo['search_reward'];
				
				$canBalanceSum = bcadd($serviceMoney, $guaranteeMoney, 2);
                    // 是搜索下单，则加上搜索奖励金///
                 if( $goodsInfo['type'] == Goods_model::TYPE_SEARCH_BUY ){
                        $canBalanceSum = bcadd($canBalanceSum, $search_reward, 2);
                 }
			}else{
				mt_srand((double)microtime()*1000000);
				//在前面加个8、9分别表示商家结算担保金订单号、服务费订单号，避免重复
				$guaranteeOrder = '8'.date('YmdHis', time()).mt_rand(1000, 9999);
				$serviceOrder = '9'.date('YmdHis', time()).mt_rand(1000, 9999);
				$searchOrder =  '10'.date('YmdHis', time()).mt_rand(1000, 9999);

				//计算结算金额
				$remainQuantity = $goodsInfo['remain_quantity'];
				//计算商家存入的每份担保金金额 说明：之前存入的每份担保金金额为网购价，修改后存入的每份担保金金额为返回给买家的金额  updateby 关小龙 2015-09-22 10:12:00
				$real_single_guaranty = $goodsInfo['deposit_type']==1 ? $goodsInfo['single_rebate'] : $goodsInfo['price'];
				$guaranteeMoney = bcmul($remainQuantity,$real_single_guaranty,2);
				//可结算的服务费金额 = 剩余资格数 * 每份服务费
				$serviceMoney = bcmul($remainQuantity,$goodsInfo['single_fee'],2);
				$canBalanceSum = bcadd($serviceMoney,$guaranteeMoney,2);
				// 是搜索下单奖励金
				$search_reward= bcmul($remainQuantity,$goodsInfo['search_reward'],2);
                    // 是搜索下单，则加上搜索奖励金///
                 if( $goodsInfo['type'] == Goods_model::TYPE_SEARCH_BUY ) {
                        $canBalanceSum = bcadd($canBalanceSum, $search_reward, 2);
                 }
			}
			$rate = $goodsInfo['rate'];
			//商品剩余总金额（担保金+服务费）
			$lastMoney = bcadd($goodsMoneyStat['remain_guaranty'], $goodsMoneyStat['remain_fee'], 2);
			if( $goodsInfo['type'] == Goods_model::TYPE_SEARCH_BUY ) {
				$lastMoney = bcadd($lastMoney, $goodsMoneyStat['remain_search_reward'], 2);
			}
			
               // 活动结算清单
			if($doBalanceSubmit <= 0){
                    // 结算清单没有，则生成
				if($orderId <= 0){
					$params = array(
							'gid' => $gid,
							'guaranty_pno' => $guaranteeOrder,
							'fee_pno' => $serviceOrder,
							'search_pno'=>$searchOrder,
							'guaranty' => $guaranteeMoney,
							'fee' => $serviceMoney,
							'search_reward'=>$search_reward,
							'num' => $remainQuantity,
							'opuid' => $this->user_id,
							'opname' => $this->username,
							'ip' => bindec(decbin(ip2long($this->input->ip_address()))),
					);
					$execBack = $this->admin_goods_balance->update_data_by_procedure_before_checkout_pay($params);
					$execBack['Code'] = intval($execBack['Code']);
					$execBack['orderid'] =isset($execBack['orderid'])? intval($execBack['orderid']):0;
					$execBack['Message'] =isset($execBack['Message'])? trim($execBack['Message']):'';
					if($execBack['Code'] !== 1 || $execBack['Message'] !== '' || $execBack['orderid'] <= 0){
						$procErr = '创建结算支付订单出错|错误:['.$execBack['Code'].']'.$execBack['Message'];
						$this->error($procErr);
					}
				}
				
				$data['goodsInfo']=$goodsInfo;
				$this->load->vars('rate', $rate);
				$data['remainQuantity']=$remainQuantity;
				$data['serviceMoney']= $serviceMoney;
				$data['payMoney']=$guaranteeMoney;
				$data['canBalanceSum']=$canBalanceSum;
				$data['lastMoney']= $lastMoney;
				$this->load->view('goods/balance',$data);
        
                // 提交结算
			}else{
				if($orderId <= 0){
					$this->error('结算订单不存在');
				}
				if($guaranteeMoney <= 0 && $serviceMoney <= 0 && $search_reward<=0 ){//没有可结算的金额
					//存储过程参数数据
					$params = array(
							'in_gid' => $gid,
							'in_opuid' => $this->user_id,
							'in_opname' => $this->username,
							'in_checkout_pid' => $orderId,
							'in_ip' => bindec(decbin(ip2long($this->input->ip_address()))),
					);
					$execBack = $this->admin_goods_balance->update_data_by_procedure_after_checkout_pay($params);
					$execBack['Code'] = intval($execBack['Code']);
					$execBack['Message'] = trim($execBack['Message']);
					if($execBack['Code'] === 1 && $execBack['Message'] === ''){
						$this->success("商品结算成功！");
					}else{
				
						$procErr = ' 结算(无结算金额)错误:['.$execBack['Code'].']'.$execBack['Message'];
						$this->error($procErr);
					}
				}else{
					if($canBalanceSum > $lastMoney){//可用余额不足以结算
						$this->error('商品剩余金额不足，结算失败!');
					}
					if($goodsInfo['state'] == Goods_model::STATUS_CHECKOUT_PAYING && $orderInfo['state'] == 1){//继续结算
						if($canBalanceSum <= $lastMoney && $lastMoney > 0){
							//计算商家存入的每份担保金金额 说明：之前存入的每份担保金金额为网购价，修改后存入的每份担保金金额为返回给买家的金额  updateby 关小龙 2015-09-22 10:12:00
							$real_single_guaranty = $goodsInfo['deposit_type']==1 ? $goodsInfo['single_rebate'] : $goodsInfo['price'];
							//结算请求互联支付的数据
							$data = array(
									'gid'             => $gid,
									'uid'             => $goodsInfo['uid']?$goodsInfo['uid']:0,
									'guaranteetitle'  => '结算众划算商品“'.$goodsInfo['title'].'“担保金',
									'servicetitle'    => '结算众划算商品“'.$goodsInfo['title'].'“服务费',
									'discounttitle'   => '结算众划算商品“'.$goodsInfo['title'].'“搜索奖励金',
									'paymoney'        => $guaranteeMoney,
									'servermoney'     => $serviceMoney,
									'discountmoney'   => $search_reward,
									'lastmoney'       => $lastMoney,
									'paymessage'      => '结算众划算商品“' .$goodsInfo['title'].'”，退还担保金：' .$remainQuantity.'份×' .$real_single_guaranty.'元='.$guaranteeMoney.'元；',
									'servicemessage'  => '退还平台损耗费：' . $remainQuantity.'份×' . $goodsInfo['single_fee'].'元(单笔平台损耗费)=' . $serviceMoney.'元。',
									'discountmessage' => '结算众划算商品“' .$goodsInfo['title'].'”，退还搜索奖励金：' .$remainQuantity.'份×' .$goodsInfo['search_reward'].'元='.$search_reward.'元；',
									'guaranteeOrder'  => $guaranteeOrder,  //结算活动担保金订单号
									'serviceOrder'    => $serviceOrder,    //结算活动服务费订单号
									'discountOrder'   => $searchOrder,     //结算活动奖励订单号
							);
							$this->load->library('hlpay');
							$balanceBack = $this->hlpay->goods_balance($data, $canBalanceSum);
							$balanceBack = intval($balanceBack);
							if($balanceBack > 0){//互联支付结算成功
								//存储过程参数数据
								$params = array(
										'in_gid' => $gid,
										'in_opuid' => $this->user_id,
										'in_opname' => $this->username,
										'in_checkout_pid' => $orderId,
										'in_ip' => bindec(decbin(ip2long($this->input->ip_address()))),
								);
								$execBack = $this->admin_goods_balance->update_data_by_procedure_after_checkout_pay($params);
								$execBack['Code'] = intval($execBack['Code']);
								$execBack['Message'] = trim($execBack['Message']);
								if($execBack['Code'] === 1 && $execBack['Message'] === ''){
									$this->success("商品结算成功！");
								}else{
								
									$procErr = '结算过程失败！ 错误:['.$execBack['Code'].']'.$execBack['Message'];
									$this->error($procErr);
								}
							}elseif($balanceBack == -2){
								$this->error('密匙验证不通过');
							}elseif($balanceBack == -3){
								$this->error('总金额不对应');
							}elseif($balanceBack == -10){
								$this->error('没有剩余的金额');
							}else{
								$this->error('结算失败');
							}
						}
					}else{
						$this->error('商品状态或结算支付订单状态异常。');
					}
				}
			}
		}
	}
	
	/**
	 * 显示部分已结算的活动-列表页
	 * @author 莫嘉伟
	 */
	public function alway_show_list() {
		$goods_util = $this->util;
		$ext_where = array();
		$status=  32; // 只查看已结算的活动
		$search_key = $this->get_post('search_key');
		$search_value = $this->get_post('search_value');
		$stime = $etime = 0;
		
		$ext_where['gid >'] = 0;
		$order = 'gid desc';

		$limit = 10;
		
		$offset = $this->uri->segment(3);
		$items = $this->goods_model->search($search_key, $search_value, $status, $stime, $etime, $ext_where, $order, $limit, $offset);
		$items_count = $this->goods_model->search_count($search_key, $search_value, $status, $stime, $etime, $ext_where);
		
		if (count($items) >0) {
			//获得活动类型uid
			foreach ($items as $va){
				$uid[]=$va['uid'];
			}
			$deposit_type=$this->db->select('uid,state,deposit_type')->from('user_seller_deposit')->where_in('uid',$uid)->get()->result_array();
			$yzcm=$mpg=array();
			foreach ( $deposit_type as $key=> $v) {
				if($v['deposit_type']==1){
					$yzcm[$v['uid']]['deposit_type']=$v['deposit_type'];
					$yzcm[$v['uid']]['state']=$v['state'];
				}else if($v['deposit_type']==2){
					$mpg[$v['uid']]['deposit_type']=$v['deposit_type'];
					$mpg[$v['uid']]['state']=$v['state'];
				}
			}
		}

		$page_conf = array('uri_segment'=>3,'anchor_class'=>'type="load" rel="div#main-wrap"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method);
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method);
		$pager = $this->pager($items_count, $limit, $page_conf);
		$this->search_map['uid'] = '商家ID';
		unset($this->search_map['email']);//删除商家邮箱的搜索选项
		$this->load->view('goods/alway_show_list', get_defined_vars());
	}
	
	/**
	 * 更改活动的显示状态
	 * @author 莫嘉伟
	 */
	public function change_alway_show() {
				
		//修改单个活动显示状态 start
		$id_info = explode('-', $this->get_post('id'));
		if (count($id_info) == 2) {
			$gid = (int)$id_info[0];
			$alway_show = (int)$id_info[1];//当前状态
			$to_change = 1; //要被改变的状态值
			if ($alway_show == 1) {
				$to_change = 2;
			}
			$this->goods_model->change_alway_show(array($gid), $to_change);
			$this->success('修改显示状态成功');
			return;
		}
		//修改单个活动显示状态 end
		
		//批量修改活动显示状态 start
		$to_change = $this->get_post('to_change');

		$gids_str = $this->get_post('gids');
		$gids_arr = explode(',', $gids_str);
		$this->goods_model->change_alway_show($gids_arr, $to_change);
		$this->success('修改显示状态成功');
		return;
		//批量修改活动显示状态 end
	}
	
	/**
	 * 商品来源数据
	 * 
	 *
	 * @author 杜嘉杰
	 * @version 2015年10月16日  上午10:35:22
	 *
	 */
	public function sources_stat()
	{
		$this->load->model('goods_source_model');
		
		// 开始时间
		$start_time = intval(strtotime($this->get_post('start_time')));
		// 结束时间
		$end_time = intval(strtotime($this->get_post('end_time')));
		// 每页数量
		$page_size = 10;
		$offset = intval($this->uri->segment(3));
		
		$this->load->model('goods_source_model');
		$tmp_end_time = $end_time ? $end_time : TIMESTAMP;
		$sources_total = $this->goods_source_model->sources_stat_count($start_time, $tmp_end_time);
		$sources = $this->goods_source_model->sources_stat($offset, $page_size, $start_time, $tmp_end_time);
		
		$page_conf = array('uri_segment'=>3,'anchor_class'=>'type="load" rel="div#main-wrap"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
		$pager = $this->pager($sources_total, $page_size, $page_conf);
		
		$data = array(
			'pager' => $pager,
			'start_time' => $start_time,
			'end_time' => $end_time,
			'sources' => $sources
		);
		
		$this->load->view('goods/sources_stat', $data);
	}
	
	/**
	 * 批量审核活动
	 *
	 * @author 杜嘉杰
	 * @version 2015年11月19日  上午9:52:49
	 *
	 */
	public function batch_check(){
		$post_type = $this->get_post('post_type');
		if($post_type=='check_goods')
		{
			// 提交数据
			$this->batch_check_goods_post();
		}
		else if($post_type == 'log')
		{
			// 查看日志
			$this->batch_check_goods_log();
		}
		else
		{
			// 查看活动
			$this->batch_check_goods_view();
		}
	
	}
	
	/**
	 * 批量审核活动 - 提交数据
	 *
	 * @author 杜嘉杰
	 * @version 2015年11月20日  下午2:46:10
	 *
	 */
	private function batch_check_goods_post()
	{
		$fuzz_gid_txt = $this->get_post('fuzz_gid_txt');
		if( ! $fuzz_gid_txt)
		{
			$this->error('未选择活动编号');
		}
		foreach (explode(',',$fuzz_gid_txt) as $fuzz_gid)
		{
			$fuzz_gid = trim($fuzz_gid);
			if( ! $fuzz_gid)
			{
				continue;
			}
			
			list($gid,$fuzz) = explode('-', $fuzz_gid);
			$gid = intval($gid);
			if($gid<=0)
			{
				$this->error('活动编号不存在：'.$fuzz_gid);
			}
			
			$this->check_goods_user_state($gid);
			
			$goods = $this->db->select('gid,state,dateline')->from(YL_goods_model::$table_name)
				->where('gid',$gid)->where_in('state', array(Goods_model::STATUS_UNCHECK_PAID,Goods_model::STATUS_CHECKED))
				->get()->row_array();
			if( ! $goods)
			{
				$this->error('获取操作失败：活动编号不存在');
			}
			
			if($fuzz != fuzz_str($gid, $goods['dateline']))
			{
				$this->error('获取操作失败：模糊参数不正确');
			}
			
			// 上线时间
			$online_time = intval($this->get_post('online_time'));
			if(!$online_time OR $online_time < time()){
				$this->error('审核失败,请选择正确的上线时间');
			}
			$rs = $this->goods_model->check($gid, $this->user_id, $this->username, $online_time);
	
			// 调用java接口添加收藏推送任务
			$this->goods_remind($gid, $online_time);
			if($rs['Code']){
				$this->error('操作失败'.$rs['Message']);
			}
		}
		$this->success('操作成功');
		
	}
	
	
	/**
	 * 批量审核活动 - 查看活动操作日志
	 * 
	 * @author 杜嘉杰
	 * @version 2015年11月20日  下午2:46:35
	 *
	 */
	private function batch_check_goods_log()
	{
		list($gid,$fuzz) = explode('-', $this->get_post('gid_fuzz'));
		$gid = intval($gid);
		if($gid<=0)
		{
			$this->error('活动不存在');
		}
		$this->db->select('gid,state,dateline')->from(YL_goods_model::$table_name)->where('gid',$gid);
		$goods = $this->db->where_in('state', array(Goods_model::STATUS_UNCHECK_PAID,Goods_model::STATUS_CHECKED))->get()->row_array();
			
		if($fuzz != fuzz_str($gid, $goods['dateline']))
		{
			$this->error('获取操作记录失败：模糊参数不正确');
		}
			
		$per = 10;
		$page = 1;
		$this->option_log_data($gid, $page, $per);
	}
	
	/**
	 * 批量审核活动 - 查看活动列表
	 *
	 * @author 杜嘉杰
	 * @version 2015年11月20日  下午2:46:53
	 *
	 */
	private function batch_check_goods_view()
	{
		// 存放活动的模糊链接，定义：gid_fuzz(全部)：1001-aabbcc(后面一节); fuzz:aabbcc;
		$gids_fuzz = array();
		
		// 匹配失败的模糊链接
		
		$failure_fuzz_gid = array();
		// 提交模糊gid的文本
		$fuzz_gid_txt = $this->get_post('fuzz_gid_txt');
		foreach (explode(',', $fuzz_gid_txt) as $tmp_fuzz_gid){
			$tmp_fuzz_gid = trim($tmp_fuzz_gid);
			if( ! $tmp_fuzz_gid)
			{
				continue;
			}
			$gid = 0;
			$fuzz = '';
			$ex = explode('-', $tmp_fuzz_gid);
			$gid = isset($ex[0]) ? intval($ex[0]) : 0;
			$fuzz = isset($ex[1]) ? trim($ex[1]) : '';
		
			if( $gid <= 0 ){
				continue;
			}
		
			$gids_fuzz[$gid] = array(
				'gid_fuzz' => $tmp_fuzz_gid,
				'fuzz' => $fuzz
			);
		}
		
		$db_goods = array();
		if(count(array_keys($gids_fuzz)))
		{
			// 查询活动表
			$fields = 'gid,state,title,uname,first_days,quantity,discount,price,deposit_type,search_reward,dateline';
			$fields .= ',first_starttime,price_type,paid_guaranty,single_fee,paid_fee,paid_search_reward,type';
			$fields .= ',mobile_price,single_rebate';
			$this->db->select($fields)->from('goods')->where_in('gid',array_keys($gids_fuzz));
			$this->db->where_in('state', array(Goods_model::STATUS_UNCHECK_PAID,Goods_model::STATUS_CHECKED));
			$this->db->limit(20);
			$db_goods = $this->db->get()->result_array();
		}
			
		$db_gids = array();
		$goods_list = array();
		if(is_array($db_goods))
		{
			foreach ($db_goods as $goods)
			{
				if($gids_fuzz[$goods['gid']]['fuzz'] != fuzz_str($goods['gid'], $goods['dateline']))
				{
					continue;
				}
				$db_gids [] = $goods['gid'];
				$goods['fuzz_data'] = $gids_fuzz[$goods['gid']];
				$goods_list[] = $goods;
			}
		}
			
		// 找出不存在的活动编号
		foreach (array_diff(array_keys($gids_fuzz), $db_gids) as $gid)
		{
			$failure_fuzz_gid [] = $gids_fuzz[$gid]['gid_fuzz'];
		}
			
		$data = array();
		$data['fuzz_gid_txt'] = $fuzz_gid_txt;
		$data['failure_fuzz_gid'] = $failure_fuzz_gid;
		$data['goods_list'] = $goods_list;
		$data['goods_util'] = $this->util;
			
		// 显示页面
		$this->load->view('goods/batch_check',$data);
	}
	
}
// End of class Goods

/* End of file goods.php */
/* Location: ./application/controllers/goods.php */