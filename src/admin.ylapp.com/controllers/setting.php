<?php

if(!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * 系统设置控制器类
 * @author minch <yeah@minch.me>
 * @property System_config_model $config_model
 * @property System_tasktimer_config_model $tasktimer_config_model
 */
class Setting extends MY_Controller
{

	public $check_access = TRUE;

	public $except_methods = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model('system_config_model', 'config_model');
	}
	
	/**
	 * 名品馆参数设置
	 */
	public function mpg()
	{
		if(isset($_POST['save']))
		{
			$setting = $this->input->post('setting');
			foreach ($setting as $key=>$set)
			{
				if (!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $set['value'])) {
					$this->error($set['name'] . '阿拉伯数字，保留2位小数，0.01~10000000');
				}

				$value = round($set['value'], 2);
				if ($value < 0.01 || $value > 10000000) {
					$this->error($set['name'] . '阿拉伯数字，保留2位小数，0.01~10000000');
				}
				$this->config_model->replace_save($key,$value,$set['remark']);
			}
			$this->_build_cache();
			$this->success('配置成功');
		}
		else
		{
			define('MPG_CATE_SETTING_PIX', 'mpg_cate_setting_');
			
			$this->load->model('goods_category_model');
			$categories = $this->goods_category_model->get_by_pid();
			
			$data = array();
			if ($categories)
			{
				$keys = array();
				foreach ($categories as $cate)
				{
					$keys[] = MPG_CATE_SETTING_PIX.$cate['id'];
				}
				$keys[] = 'mpg_guarantee_money';
				$setting = array();
				foreach ($this->config_model->get($keys) as $set)
				{
					$setting[$set['key']] = $set['value'];
				}
				
				foreach ($categories as &$cate)
				{
					$key = MPG_CATE_SETTING_PIX.$cate['id'];
					$cate['setting'] = isset($setting[$key]) ? $setting[$key] : '';
				}
				
				$data['setting_category'] = $categories;
				$data['setting_guarantee_money'] = isset($setting['mpg_guarantee_money'])?$setting['mpg_guarantee_money']:'';
				
				$this->load->view('setting/mpg', $data);
			}
		}
	}
	
	/**
	 * 一站成名活动发布设置
	 * @access public
	 * @author 韦明磊
	 */
	public function yzcm()
	{
		if (isset($_POST['save'])) {
			$setting_guarantee_money = $_POST['guarantee_money'];
			
			if (!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $setting_guarantee_money)) {
				$this->error('商品总价值必须是阿拉伯数字，保留2位小数，0.01~10000000');
			}
			
			$value = round($setting_guarantee_money, 2);
			if ($value < 0.01 || $value > 10000000) {
				$this->error('商品总价值必须是阿拉伯数字，保留2位小数，0.01~10000000');
			}
			$this->config_model->replace_save('yzcm_guarantee_money', $setting_guarantee_money, '一站成名商品总价值必须大于等于这个值');
			
			$this->_build_cache();
			$this->success('配置成功');
		} else {
			$arr = $this->config_model->get('yzcm_guarantee_money');
			$data = array('setting_guarantee_money'=>$arr?$arr['value']:0);
			$this->load->view('setting/yzcm', $data);
		}
	}

	/**
	 * 设置活动上线时间
	 */
	public function online_time()
	{
		if(isset($_POST['save']) && 'yes' == $_POST['save']){
			
			$rows = array();
			// 活动配置
			if( $this->get_post('action')=== 'online_time' ){
				
				$goods_auto_online_time_remark = strval($_POST['goods_auto_online_time_remark']);
				$order_auto_clear_time_min_remark = strval($_POST['order_auto_clear_time_min_remark']);
				$order_auto_checkout_time_day_remark = strval($_POST['order_auto_checkout_time_day_remark']);
				$order_auto_close_time_day_remark = $this->get_post('order_auto_close_time_day_remark');
				//每天抢购次数
				$goods_today_buy_num_remark  = $this->get_post('goods_today_buy_num_remark');
				//同个抢购单号填写时间间隔
				$order_fill_interval_remark  = $this->get_post('order_fill_interval_remark');
				//所填订单号匿名时间，即填了单号以后多少个小时内订单号是匿名显示
				$order_hidden_time_remark  = $this->get_post('order_hidden_time_remark');
				//发布/追加商品时间最小天数
				$goods_allow_day_min_remark  = $this->get_post('goods_allow_day_min_remark');
				//发布/追加商品时间最大天数
				$goods_allow_day_max_remark  = $this->get_post('goods_allow_day_max_remark');
				
				$goods_auto_online_time = intval($_POST['goods_auto_online_time']);
				if($goods_auto_online_time < 0 or $goods_auto_online_time > 24){
					$this->error($goods_auto_online_time_remark . '只能输入1-24之间的整数');
				}
				$order_auto_clear_time_min = intval($_POST['order_auto_clear_time_min']);
				if($order_auto_clear_time_min < 1 or $order_auto_clear_time_min > 60){
					$this->error($order_auto_clear_time_min_remark . '只能输入1-60之间的整数');
				}
				$order_auto_checkout_time_day = intval($_POST['order_auto_checkout_time_day']);
				if($order_auto_checkout_time_day < 1 or $order_auto_checkout_time_day > 365){
					$this->error($order_auto_checkout_time_day_remark . '只能输入1-365之间的整数');
				}
				$order_auto_close_time_day = intval($_POST['order_auto_close_time_day']);
				if($order_auto_close_time_day < 1 or $order_auto_close_time_day > 365){
					$this->error($order_auto_close_time_day_remark . '只能输入1-365之间的整数');
				}
				$goods_today_buy_num = intval($_POST['goods_today_buy_num']);
				if($goods_today_buy_num < 1 or $goods_today_buy_num > 100){
					$this->error($goods_today_buy_num_remark . '只能输入1-100之间的整数');
				}
				$order_fill_interval = intval($_POST['order_fill_interval']);
				if($order_fill_interval < 1 or $order_fill_interval > 60){
					$this->error($order_fill_interval_remark . '只能输入1-60之间的整数');
				}
				$order_hidden_time = intval($_POST['order_hidden_time']);
				if($order_hidden_time < 0 or $order_hidden_time > 72){
					$this->error($order_hidden_time_remark . '只能输入0-72小时之间的整数');
				}
				#发布/追加商品时间最小天数
				$goods_allow_day_min = intval($_POST['goods_allow_day_min']);
				if($goods_allow_day_min < 3 || $goods_allow_day_min > 14){
					$this->error($goods_allow_day_min_remark . ' 只能是3<=min<=14的整数');
				}
				#发布/追加商品时间最大天数
				$goods_allow_day_max = intval($_POST['goods_allow_day_max']);
				if($goods_allow_day_max < 4 || $goods_allow_day_max > 15){
					$this->error($goods_allow_day_max_remark . ' 只能是4<=min<=15的整数');
				}
				if($goods_allow_day_max <= $goods_allow_day_min){
					$this->error($goods_allow_day_max_remark . ' 必须大于 最小天数');
				}
				
				$rows[] = array('key'=>'goods_auto_online_time','value'=>$goods_auto_online_time,'remark'=>$goods_auto_online_time_remark);
				$rows[] = array('key'=>'order_auto_clear_time_min','value'=>$order_auto_clear_time_min * 60,'remark'=>$order_auto_clear_time_min_remark);
				$rows[] = array('key'=>'order_auto_checkout_time_day','value'=>$order_auto_checkout_time_day * 24 * 3600,'remark'=>$order_auto_checkout_time_day_remark);
				$rows[] = array('key'=>'order_auto_close_time_day','value'=>$order_auto_close_time_day * 24 * 3600,'remark'=>$order_auto_close_time_day_remark);
				$rows[] = array('key'=>'goods_today_buy_num','value'=>$goods_today_buy_num,'remark'=>$goods_today_buy_num_remark);
				$rows[] = array('key'=>'order_fill_interval','value'=>$order_fill_interval,'remark'=>$order_fill_interval_remark);
				$rows[] = array('key'=>'order_hidden_time','value'=>$order_hidden_time,'remark'=>$order_hidden_time_remark);
			
				#发布/追加商品时间范围
				$rows[] = array('key'=>'goods_allow_day_min','value'=>$goods_allow_day_min,'remark'=>$goods_allow_day_min_remark);
				$rows[] = array('key'=>'goods_allow_day_max','value'=>$goods_allow_day_max,'remark'=>$goods_allow_day_max_remark);
				
				$rs = $this->config_model->save_all($rows);
				if($rs){
					$this->_build_cache();
					$this->log('设置活动上线时间成功', array_merge($_GET, $_POST));
					$this->success('设置活动上线时间成功');
				}else{
					$this->log('设置活动上线时间失败', array_merge($_GET, $_POST));
					$this->error('设置活动上线时间失败');
				}

			}elseif($this->get_post('action')=== 'new_parvial_field'){ //设置最新上线整点分场
				
				$goods_new_parvial_field_not = $this->get_post('goods_new_parvial_field_not');
				$goods_new_parvial_field_not_remark = $this->get_post('goods_new_parvial_field_not_remark');
				if(count($goods_new_parvial_field_not)==0){
				  $this->error('必须选择一个整点分场！');
				}
				if(count($goods_new_parvial_field_not)>5){
				  $this->error('最多只能选择5个整点分场！');
				}
				$rows[] = array('key'=>'goods_new_parvial_field_not','value'=>implode(',', $goods_new_parvial_field_not),'remark'=>$goods_new_parvial_field_not_remark);
				$rs = $this->config_model->save_all($rows);
				if($rs){
					$this->_build_cache();
					$this->log('保存最新上线整点分场成功', array_merge($_GET, $_POST));
					$this->success('保存最新上线整点分场成功');
				}else{
					$this->log('保存最新上线整点分场失败', array_merge($_GET, $_POST));
					$this->error('保存最新上线整点分场失败');
				}
				
			}elseif ($this->get_post('action')=== 'mail'){// 邮箱配置保存
				
				$smtp_user_remark = $this->get_post('smtp_user_remark');	//邮箱配置
				$smtp_pass_remark = $this->get_post('smtp_pass_remark');
				$smtp_host_remark = $this->get_post('smtp_host_remark');
				$trial_goods_url_remark = $this->get_post('trial_goods_url_remark');	//商品试用链接
				
				$smtp_host = strval($_POST['smtp_host']);	//smtp_host
				if( !preg_match("/[0-9a-zA-Z]/i",$smtp_host) ){
					$this->error($smtp_host_remark . '格式不正确');
				}
				$smtp_user = strval($_POST['smtp_user']);	//官方邮箱
				if( !preg_match("/^[0-9a-zA-Z]+@(([0-9a-zA-Z]+)[.])+[a-z]{2,4}$/i",$smtp_user) ){
					$this->error($smtp_user_remark . '格式不正确');
				}
				$smtp_pass = strval($_POST['smtp_pass']);	//邮箱 密码
				if( !preg_match("/^[0-9a-zA-Z]{6,18}$/i",$smtp_pass) ){
					$this->error($smtp_pass_remark . '只能为6~18位的字母和数字');
				}
				$trial_goods_url = strval($_POST['trial_goods_url']);	//免费试用链接
				if( !preg_match("/[0-9a-zA-Z]/i",$trial_goods_url) ){
					$this->error($trial_goods_url_remark . '式不正确');
				}
				
				$rows[] = array('key'=>'smtp_user','value'=>$smtp_user,'remark'=>$smtp_user_remark);
				$rows[] = array('key'=>'smtp_pass','value'=>$smtp_pass,'remark'=>$smtp_pass_remark);
				$rows[] = array('key'=>'smtp_host','value'=>$smtp_host,'remark'=>$smtp_host_remark);
				$rows[] = array('key'=>'trial_goods_url','value'=>$trial_goods_url,'remark'=>$trial_goods_url_remark);
				
				$rs = $this->config_model->save_all($rows);
				if($rs){
					$this->_build_cache();
					$this->log('保存官方邮箱配置成功', array_merge($_GET, $_POST));
					$this->success('保存官方邮箱配置成功');
				}else{
					$this->log('保存官方邮箱配置失败', array_merge($_GET, $_POST));
					$this->error('保存官方邮箱配置失败');
				}
			}
			
		}
		$data = array();
		 $fields=array('goods_auto_online_time','order_auto_clear_time_min','order_auto_checkout_time_day','goods_new_parvial_field_not','goods_allow_day_min','goods_allow_day_max',
		 'order_auto_close_time_day','smtp_user','smtp_pass','smtp_host','trial_goods_url','goods_today_buy_num','order_fill_interval','order_hidden_time');
		foreach($this->config_model->get($fields) as $v){
			switch($v['key']){
				case 'order_auto_clear_time_min':
					$data[$v['key']] = $v['value'] / 60;
					break;
				case 'order_auto_checkout_time_day':
				case 'order_auto_close_time_day':
					$data[$v['key']] = $v['value'] / (24 * 3600);
					break;
				case 'goods_new_parvial_field_not':
					$data[$v['key']] = explode(',', $v['value']);
					break;
				default:
					$data[$v['key']] = $v['value'];
			}
		}
		$this->load->view('setting/online_time', $data);
	}

	/**
	 * 设置近期热卖
	 */
	public function hot_percent()
	{
		if(isset($_POST['save']) && 'yes' == $_POST['save']){
			$value = intval($_POST['sellWell']);
			if($value < 1 or $value > 100){
				$this->error('销量百分比只能输入1-100之间的整数。');
			}
			$remark = strval($_POST['sellWell_remark']);
			$rs = $this->config_model->save('sellWell', $value, $remark);
			if($rs){
				$this->_build_cache();
				$this->log('设置近期热卖百分比成功', array_merge($_GET, $_POST));
				$this->success('保存成功');
			}else{
				$this->log('设置近期热卖百分比失败', array_merge($_GET, $_POST));
				$this->error('保存失败');
			}
		}
		$rs = $this->config_model->get('sellWell');
		$sellWell = $rs['value'];
		$this->load->view('setting/hot_percent', get_defined_vars());
	}

	/**
	 * 设置开团提醒时间
	 */
	public function goods_remind()
	{
		if(isset($_POST['save']) && 'yes' == $_POST['save']){
			$section = trim(strval($_POST['section']));
			if($section=='prefix'){
				$goods_online_remind_prefix_time_remark = strval($_POST['goods_online_remind_prefix_time_remark']);
				$goods_addition_remind_prefix_time_remark = strval($_POST['goods_addition_remind_prefix_time_remark']);
				$goods_online_remind_prefix_time = intval($_POST['goods_online_remind_prefix_time']);
				$goods_addition_remind_prefix_time = intval($_POST['goods_addition_remind_prefix_time']);
				if($goods_online_remind_prefix_time < 1 or $goods_online_remind_prefix_time > 15){
					$this->error($goods_online_remind_prefix_time_remark . '只能输入1-15之间的整数');
				}
				$goods_addition_remind_prefix_time = intval($_POST['goods_addition_remind_prefix_time']);
				if($goods_addition_remind_prefix_time < 1 or $goods_addition_remind_prefix_time > 15){
					$this->error($goods_addition_remind_prefix_time_remark . '只能输入1-15之间的整数');
				}
				$rows = array();
				$rows[] = array('key'=>'goods_collect_online_remind_prefix_time','value'=>$goods_online_remind_prefix_time,'remark'=>$goods_online_remind_prefix_time_remark);
				$rows[] = array('key'=>'goods_collect_addition_remind_prefix_time','value'=>$goods_addition_remind_prefix_time,'remark'=>$goods_addition_remind_prefix_time_remark);
				$rs = $this->config_model->save_all($rows);
				if($rs){
					$this->_build_cache();
					$this->log('设置众划算开团提醒时间成功', array_merge($_GET, $_POST));
					$this->success('保存成功');
				}else{
					$this->log('设置众划算开团提醒时间失败', array_merge($_GET, $_POST));
					$this->error('保存失败');
				}
			}elseif ($section=='hours'){
				$goods_remind_hours = $_POST['goods_remind_hours'];
				if(count($goods_remind_hours) < 1){
					$this->error( '请选择要设置追加上线的整点');
				}
				$rows = array();
				$rows[] = array('key'=>'goods_remind_hours','value'=>implode(',', $goods_remind_hours),'remark'=>'设置追加上线的整点');
				$rs = $this->config_model->save_all($rows);
				if($rs){
					$this->_build_cache();
					$this->log('设置追加上线的整点成功', array_merge($_GET, $_POST));
					$this->success('保存成功');
				}else{
					$this->log('设置追加上线的整点失败', array_merge($_GET, $_POST));
					$this->error('保存失败');
				}
			}
		}
		$data = array();
		foreach($this->config_model->get(array('goods_collect_online_remind_prefix_time','goods_collect_addition_remind_prefix_time','goods_remind_hours')) as $v){
			switch($v['key']){
				case 'goods_remind_hours':
					$data[$v['key']] = explode(',', $v['value']);
					break;
				default:
					$data[$v['key']] = $v['value'];
			}
		}
		if (!is_array($data['goods_remind_hours'])) {
			$data['goods_remind_hours'] = array();
		}
		$this->load->view('setting/goods_remind', $data);
	}
		
	/**
	 * 设置活动默认上线时间类型
	 * @author 杨积广 2014 3 27
	 */
	public function set_default_online_time(){ 
		if($this->input->get_post('dopost')){
			$online_type=$this->input->get_post('goods_default_online_type');
			$orderTime=strtotime($this->input->get_post('orderTime'));
			$goods_new_parvial_field=explode(',',$this->config->item('goods_new_parvial_field'));
			if(!$online_type){
				$this->error('必须选择一个上线类型！');
			}
			if($online_type==1 && empty($goods_new_parvial_field[0])){
				 $this->error('系统自动 ，必须先设置好整点分场！'); 
			}
			if($online_type==3 && ($orderTime < time())){
				$this->error('请选择正确的上线时间！');
			}
			    $goods_default_online_type=unserialize($this->config->item('goods_default_online_type'));
			    $goods_default_online_type[$this->user_id]=$online_type;
			    $goods_default_online_type=serialize($goods_default_online_type);
				$rows[] = array('key'=>'goods_default_online_type','value'=>$goods_default_online_type,'remark'=>'默认上线时间类型：1系统自动2全部手动3设置默认');
				
				if($online_type==3 && $orderTime>0){
					$orderTimeval=unserialize($this->config->item('goods_default_online_type_value'));
					$orderTimeval[$this->user_id]=$orderTime;
					$orderTimeval=serialize($orderTimeval);
					$rows[] = array('key'=>'goods_default_online_type_value','value'=>$orderTimeval,'remark'=>'默认上线时间类型goods_default_online_type等于3默认值');
				}
				$rs = $this->config_model->save_all($rows);
				if($rs){
					$this->_build_cache();
					$this->log('保存默认上线时间成功', array_merge($_GET, $_POST));
					$this->success('保存成功');
				}else{
					$this->log('保存默认上线时间失败', array_merge($_GET, $_POST));
					$this->error('保存失败');
				}
		}else{

			$orderTimeval=unserialize($this->config->item('goods_default_online_type_value'));
			$orderTime=isset($orderTimeval[$this->user_id])? $orderTimeval[$this->user_id] : 0 ;
			$default_online_type=unserialize($this->config->item('goods_default_online_type'));
			$type=isset($default_online_type[$this->user_id]) ? $default_online_type[$this->user_id]:0;
			$this->load->view('setting/set_default_online_type', get_defined_vars());
			
		}
	}
	
	
	/**
	 * 自动屏蔽配置
	 * 2014-03-24
	 */
	public function auto_shield(){
	    $key = 'account_auto_shield';
	    
	    $data['order']     = 0;
	    $data['appeal']    = 0;
	    $data['purchase']  = 0;
	    $data['lock']      = 0;
	    
	    if(isset($_POST['order'])){
	        $order    = intval(trim($this->input->post('order')));
	        $appeal   = intval(trim($this->input->post('appeal')));
	        $purchase = intval(trim($this->input->post('purchase')));
	        $lock     = intval(trim($this->input->post('lock')));
	        //再次检验参数
	        if(!($order >= 0 && $order <= 100)){
	            $this->error('连续7天内被审核“订单号有误”次数范围：0≤X≤100');
	        }
	        if(!($purchase >= 0 && $purchase <= 1000)){
	            $this->error('1小时内抢购商品次数范围：0≤X≤1000');
	        }
	        if(!($appeal >= 0 && $appeal <= 100)){
	            $this->error('连续7天内买家账户被申述次数次数范围：0≤X≤100');
	        }
	        if(!($lock >= 0 && $lock <= 1000)){
	            $this->error('被自动屏蔽帐号的封号天数范围：0≤X≤1000');
	        }
	        //
	        $data['order']   = $order;
	        $data['appeal']  = $appeal;
	        $data['purchase']= $purchase;
	        $data['lock']    = $lock;
	        //是否已存在
	        $rs = $this->db->select("*")->from('system_config')->where('key',$key)->get()->row_array();
	        if(count($rs)>0){//更新
	            $update_data = array(
	            	'value'=>serialize($data),
	            );
	            $this->db->where('key',$key);
	            $this->db->update('system_config',$update_data);
	        }
	        else{//入库
	            $update_data = array(
            		'key'=>$key,
            		'value'=>serialize($data),
            		'remark'=>'账号自动屏蔽条件设置'
	            );
	            $this->db->insert('system_config',$update_data);
	        }
	        $this->success('保存成功');
	    }else{
	        $rs = $this->db->select("value")->from('system_config')->where('key',$key)->get()->row_array();
	        if(count($rs)>0){
	           $data = unserialize($rs['value']);
	        }
	    }
	    $this->load->view('setting/auto_shield', $data);
	}
	
	/**
	 * 设置用户注册来源URL的名称
	 */
	public function set_reg_source_name(){
		if($this->input->get_post('dopost')){
			$source=$this->input->get_post('source'); // 来源名称
			$type=$this->input->get_post('type'); //统计方式：1.模糊；2.精确
			$url=$this->input->get_post('url'); // 来源地址
			
			if($source=='其它'){
					$this->error('用户来源名称重复，请重新添加！');
			}
			$this->load->model('system_config_reg_source_model');
			if($this->system_config_reg_source_model->exists_name($source)){
				$this->error('用户来源名称重复，请重新添加！');
			}
			if($this->system_config_reg_source_model->exists_url($url)){
				$this->error('用户来源url重复，请重新添加！');
			}
			
			$data = array('name'=>$source, 'url'=>$url,'type'=>$type, 'dateline'=>TIMESTAMP);
			$this->system_config_reg_source_model->insert($data);
			
			$this->success('保存成功');
		}else {
		    $this->load->view('setting/set_reg_source_name', get_defined_vars());
		}
	}
	
	/**
	 * 编辑用户注册来源URL的名称设置
	 */
	public function edit_reg_source_name(){
		
		$id = intval($this->input->get_post('id'));
		$this->load->model('system_config_reg_source_model');
		$urls = $this->system_config_reg_source_model->find($id);
		if ($urls) {
			if ($this->input->get_post ( 'editpost' )) {
				$source=trim($this->input->get_post('source'));
				$type=intval($this->input->get_post('type'));
				$url=trim($this->input->get_post('url'));
				
				
				if($source=='其它'){
					
					$this->error('用户来源名称重复，请重新添加！');
				}
				$this->load->model('system_config_reg_source_model');
				if($this->system_config_reg_source_model->exists_name($source, $id)){
					
					$this->error('用户来源名称重复，请重新添加！');
				}
				if($this->system_config_reg_source_model->exists_url($url, $id)){
					$this->error('用户来源url重复，请重新添加！');
				}
				
				$data = array('name'=>$source, 'url'=>$url,'type'=>$type);
				
				$this->system_config_reg_source_model->update($id, $data);
				$this->success('编辑成功！');
			} else {
				$this->load->view ( 'setting/edit_reg_source_name', get_defined_vars());
			}
		} else {
			$this->error ( '查询不到数据！' );
		}
	}
	/**
	 * 删除用户注册来源URL的名称设置
	 */
	public function del_reg_source_name(){
		$id=intval($this->input->get_post('id'));
		$this->load->model('system_config_reg_source_model');
		$re = $this->system_config_reg_source_model->delete($id);
		if($re){
			$this->success('删除成功！');
		}else{
			$this->error('删除失败！');
		}
	}
  
  /**
   * 系统设置-搜索下单参数设置
   */
  public function search_buy() {
      /* Submit */
    if( isset( $_POST['action'] ) && $_POST['action'] == 'save' )
    {
        // 活动网购价（未生效）
        $not_search_buy_min_price = sprintf('%.2f',floatval($this->input->post('not_search_buy_min_price')));
        // 活动担保金（未生效）
        $not_search_buy_min_paid_guaranty = sprintf('%.2f',floatval($this->input->post('not_search_buy_min_paid_guaranty')));
        // 搜索下单活动的类目（未生效）
        $not_search_buy_category_pids =  $this->input->post('not_search_buy_category_pids');
        
        // 验证 活动网购价、担保金 是否在合理范围
        $message = '必须为 0.01~1000000.00 的阿拉伯数字';
        $this->_check_number_range($not_search_buy_min_price, 0.01, 1000000, $message);
        $this->_check_number_range($not_search_buy_min_paid_guaranty, 0.01, 1000000, $message);
        // 验证活动类目是否有值，并转成字符串用逗号拼接
        $not_search_buy_category_pids = $this->_array_to_string($not_search_buy_category_pids, '请至少选择一个类目');

        // 验证配置表中是否存在对应的生效记录，不存在则将现提交的值初始化到配置表中
        $conf_search_buy = $this->config_model->get( array( 'search_buy_min_price', 'search_buy_min_paid_guaranty', 'search_buy_category_pids' ) );
        if( empty($conf_search_buy) || count($conf_search_buy)==0 ){
            $data = array(
                array('key'=>'search_buy_min_price', 'value'=>$not_search_buy_min_price, 'remark'=>'已生效的活动网购价（搜索下单）'),
                array('key'=>'search_buy_min_paid_guaranty', 'value'=>$not_search_buy_min_paid_guaranty, 'remark'=>'已生效的活动担保金（搜索下单）'),
                array('key'=>'search_buy_category_pids', 'value'=>$not_search_buy_category_pids, 'remark'=>'已生效的活动的类目编号（搜索下单）')
            );
            $insert_rs = $this->config_model->insert_batch($data);
            if ($insert_rs){
                $this->log('已生效的搜索下单配置-初始化成功', array_merge($_GET, $_POST)) ;
            }else{
                $this->log('已生效的搜索下单配置-初始化失败', array_merge($_GET, $_POST)) ;
            }
        }
        
        // 未生效的值存入配置表
        $rows = array( );
        $rows[] = array('key'=>'not_search_buy_min_price','value'=>$not_search_buy_min_price,'remark'=>'未生效的活动网购价（预更新值）');
        $rows[] = array('key'=>'not_search_buy_min_paid_guaranty','value'=>$not_search_buy_min_paid_guaranty,'remark'=>'未生效的活动担保金（预更新值）');
        $rows[] = array('key'=>'not_search_buy_category_pids','value'=>$not_search_buy_category_pids,'remark'=>'搜索下单活动的类目编号（预更新值）');
        $rs = $this->config_model->save_all($rows);

        if( $rs ){
            $this->_build_cache();
            $this->log('保存搜索下单参数配置成功', array_merge($_GET, $_POST)) ;
            $this->success('保存成功');
        }else{
            $this->log('保存搜索下单参数配置失败', array_merge($_GET, $_POST)) ;
            $this->error('保存失败');
        }
    }
    
    /* Read View */
    // 获取商品一级类目
    $this->load->model('goods_category_model');
    $goods_categorys = $this->goods_category_model->get_by_pid();
    
    // 获取已存配置
    $conf_search_buy = $this->config_model->get( array( 'not_search_buy_min_price', 'not_search_buy_min_paid_guaranty', 'not_search_buy_category_pids' ) );
    $conf_search_buy = $this->_get_conf_map($conf_search_buy);
    $conf_search_buy['not_search_buy_category_pids'] = explode(',', $conf_search_buy['not_search_buy_category_pids']);
    
    $data = array();
    $data['goods_categorys'] = $goods_categorys;
    $data['conf_search_buy'] = $conf_search_buy;
//    var_dump($conf_search_buy);
    $this->load->view('setting/search_buy', $data);
  }
		
	/*
	 * 手机接口设置
	 */
	public function mobile_setting()
	{
		$mobile_config = array ();
		$mobile_config ['voice_luosimao_url'] = array (
				'value' => 'test',
				'remark' => '语音验证发送API接口',
				'html_type' => 'text' 
		);
		$mobile_config ['voice_luosimao_key'] = array (
				'value' => 'test',
				'remark' => '语音验证发送API Key',
				'html_type' => 'text' 
		);
		 
		$mobile_config ['msg_ytx_account_sid'] = array (
				'value' => '8a48b5514d32a2a8014d4192dfd20a7d',
				'remark' => '云通信主账号(ACCOUNT SID)',
				'html_type' => 'text' 
		);
		$mobile_config ['msg_ytx_auth_token'] = array (
				'value' => '74c105168c9a429cb1a5e8601025227d',
				'remark' => '云通讯主帐号令牌(AUTH TOKEN)',
				'html_type' => 'text' 
		);
		$mobile_config ['msg_ytx_app_id'] = array (
				'value' => 'aaf98f894d328b13014d4b22ddbf12a5',
				'remark' => '云通讯APP ID',
				'html_type' => 'text' 
		);
		
		// 是表单提交的话
		if ($this->input->post ( 'save' ) == 'yes') {
			$config = array ();
			
			// 语音验证发送API接口
			$voice_luosimao_url = strip_tags ( $this->get_post ( 'voice_luosimao_url', '' ) );
			$config [] = array (
					'key' => 'voice_luosimao_url',
					'value' => $voice_luosimao_url,
					'remark' => $mobile_config ['voice_luosimao_url'] ['remark'] 
			);
			
			// 语音验证发送API Key
			$voice_luosimao_key = strip_tags ( $this->get_post ( 'voice_luosimao_key', '' ) );
			$config [] = array (
					'key' => 'voice_luosimao_key',
					'value' => $voice_luosimao_key,
					'remark' => $mobile_config ['voice_luosimao_key'] ['remark'] 
			);
			
			// 云通信主账号(ACCOUNT SID)
			$msg_lanz_userid = strip_tags ( $this->get_post ( 'msg_ytx_account_sid', '' ) );
			$config [] = array (
					'key' => 'msg_ytx_account_sid',
					'value' => $msg_lanz_userid,
					'remark' => $mobile_config ['msg_ytx_account_sid'] ['remark'] 
			);
			
			// 云通讯主帐号令牌(AUTH TOKEN)
			$msg_lanz_account = strip_tags ( $this->get_post ( 'msg_ytx_auth_token', '' ) );
			$config [] = array (
					'key' => 'msg_ytx_auth_token',
					'value' => $msg_lanz_account,
					'remark' => $mobile_config ['msg_ytx_auth_token'] ['remark'] 
			);
			
			// 云通讯APP ID
			$msg_lanz_password = strip_tags ( $this->get_post ( 'msg_ytx_app_id', '' ) );
			$config [] = array (
					'key' => 'msg_ytx_app_id',
					'value' => $msg_lanz_password,
					'remark' => $mobile_config ['msg_ytx_app_id'] ['remark'] 
			);
			$ret = $this->config_model->save_all ( $config );
			if ($ret == FALSE) {
				$this->error ( '手机接口设置编辑失败！' );
			}
			
			$this->_build_cache ();
			
			$this->success ( '手机接口设置编辑成功！' );
		}else {
			$db_config = $this->config_model->get(array('voice_luosimao_url','voice_luosimao_key','msg_ytx_account_sid','msg_ytx_auth_token','msg_ytx_app_id'));
			foreach ($db_config as $item){
				$mobile_config[$item['key']]['value'] = $item['value'];
			}
			
			$this->load->view ( 'setting/mobile_interface', array ('mobile_config' => $mobile_config) );
		}
		
	}
  
  /*
   *抢购限制设置
  */
  public function trade_limit_setting()
  {
  	$this->load->helper('form');
  	$trade_limit_config=array();
  	$trade_limit_config['need_mobile_valid']=array(
  			'value'=>'1',
  			'key'=>'need_mobile_valid',
  			'remark'=>'是否开启手机认证',
  			'html_type'=>'checkbox',
  	);
  	//加载已有配置项,覆盖上面的数组
  	$this->load->config('shs_system');
  	foreach (array_keys($trade_limit_config) as $trade_limit_key) {
  		$trade_limit_config[$trade_limit_key]['value']=  $this->config->item($trade_limit_key);
  	}
  
  	//是表单提交的话
  	if ($this->input->post('save')=='yes') {
  		foreach (array_keys($trade_limit_config) as $trade_limit_key) {
  			$v=strip_tags(trim($this->input->post($trade_limit_key)));
  			//把不存在或者''等假条件一律转成0
  			if (!$v) {
  				$v=0;
  			}
  			$trade_limit_config[$trade_limit_key]['value']=$v;
  		}
  		$res=$this->config_model->save_all($trade_limit_config);
  		if ($res) {
			$this->_build_cache();
			$this->success('抢购限制设置编辑成功！');
		}else{
			$this->error('抢购限制设置编辑失败！');
			log_message('error', '抢购限制设置编辑失败');
		}
  	}
  	$this->load->view('setting/trade_limit', array(
  			'trade_limit_config'=>$trade_limit_config,
  	));
  }
  
  /*
   *已结算活动连接模糊化key设置
  */
  public function have_pay_key_setting()
  {
  	$this->load->helper('form');
  	$have_pay_key_config=array();
  	$have_pay_key_config['have_pay_key']=array(
  			'value'=>'1',
  			'key'=>'have_pay_key',
  			'remark'=>'已结算活动查看时加密用的key',
  			'html_type'=>'text',
  	);
  	//加载已有配置项,覆盖上面的数组
  	//		$this->load->config('shs_system');
  	foreach (array_keys($have_pay_key_config) as $have_pay_key_key) {
  		$have_pay_key_config[$have_pay_key_key]['value']=  $this->config->item($have_pay_key_key);
  	}
  
  
  	//是表单提交的话
  	if ($this->input->post('save')=='yes') {
  		foreach (array_keys($have_pay_key_config) as $have_pay_key_key) {
  			$v=strip_tags(trim($this->input->post($have_pay_key_key)));
  			//把不存在或者''等假条件一律转成0
  			if (!$v) {
  				$v=0;
  			}
  			$have_pay_key_config[$have_pay_key_key]['value']=$v;
  		}
  		$res=$this->config_model->save_all($have_pay_key_config);
  		if ($res) {
  			$this->_build_cache();
  			$this->success('已结算活动查看时密钥编辑成功！');
  		}else{
  			$this->error('已结算活动查看时密钥编辑失败！');
  			log_message('error', '已结算活动查看时密钥编辑失败');
  		}
  	}
  	$this->load->view('setting/have_pay_key', array(
  			'have_pay_key_config'=>$have_pay_key_config,
  	));
  }
  
  /**
   * 提供文件名与数据生成配置文件
   * @param string $file_name 配置文件名
   * @param array $data 配置数据
   */
  public function _create_config_file($file_name,$data)
  {
  	$setting_str = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n";
  	foreach ($data as $k=>$v){
  		$setting_str .= '$config[\''.$k.'\']=\''.$v['value'].'\'; //'.$v['remark']."\n";
  	}
  	$setting_str.= "\n?>";
  	file_put_contents($file_name, $setting_str);
  }
  
  
  
  /**
     * 验证数值区间（在指定范围内通过）
     * @param type $check_num 要验证值
     * @param type $start_num 范围起始值
     * @param type $end_num 范围结束值
     * @param type $message 提示消息
     * @return boolean json/true
     */
    private function _check_number_range($check_num, $start_num, $end_num, $message='')
    {
        if( $check_num < $start_num || $check_num > $end_num )
        {
            $this->error( $message );
        }
        return TRUE;
    }
    
    /**
     * 将数组转成字符串返回
     * @param array $param
     * @param string $message 错误提示
     * @param string $glue 拼接字串
     * @return string
     */
    private function _array_to_string($param, $message='', $glue=',' )
    {
        if( empty( $param )  || !is_array($param) )
        {
            $this->error( $message );
        }
        return implode($glue, $param);
    }
    
    /**
     * 将多条配置记录转成 array(key=>value, key2=>value2) 的形式 
     * @param array $array
     * @return array
     */
    private function _get_conf_map($array)
    {
        $temp = array();
        foreach ($array as $value) {
            $temp[$value['key']] = $value['value'];
        }
        return $temp;
    }

    /**
	 * 生成配置缓存文件
	 */
	private function _build_cache()
	{
		$this->load->library('YL_setting');
		$re = $this->YL_setting->build_cache();
		if( ! $re)
		{
			$this->error($this->YL_setting->flag_msg());
		}
		
		return TRUE;
	}
	
	
	/**
	 * 设置app版本号
	 * 
	 * @author 杜嘉杰
	 * @version 2014-12-13
	 */
	public function set_app_version(){
		if($this->input->post('versionpost')){
			$app_android_version = intval($this->input->post('app_android_version')); // APP Android（安卓）最低版本号
			$app_android_current_version    = intval($this->input->post('app_android_current_version'));// APP Android（安卓）当前版本号
			$app_android_size  = floatval($this->input->post('app_android_size'));// APP Android（安卓）安装包大小MB
			$app_android_current_version_show = trim($this->input->post('app_android_current_version_show'));// APP Android（安卓）当前显示使用的版本号
			$app_android_current_version_url    = trim($this->input->post('app_android_current_version_url'));// APP Android（安卓）当前版本下载地址
			
			$app_ios_version = intval($this->input->post('app_ios_version')); // APP IOS（苹果）最低版本号
			$app_ios_current_version = intval($this->input->post('app_ios_current_version')); // APP IOS（苹果）当前最高版本号
			$app_ios_size = floatval($this->input->post('app_ios_size')); // APP IOS（苹果）安装包大小MB
			$app_ios_current_version_show = trim($this->input->post('app_ios_current_version_show'));// APP IOS（苹果）当前显示使用的版本号
			$app_ios_current_version_url = trim($this->input->post('app_ios_current_version_url')); // APP IOS（苹果）当前最高版本号
			
			$rows[] = array('key'=>'app_android_version','value'=>$app_android_version,'remark'=>'APP Android（安卓）最低版本号');
			$rows[] = array('key'=>'app_android_current_version','value'=>$app_android_current_version,'remark'=>'APP Android（安卓）当前最高版本号');
			$rows[] = array('key'=>'app_android_size','value'=>$app_android_size, 'remark'=>'APP Android（安卓）安装包大小，单位为MB');
			$rows[] = array('key'=>'app_android_current_version_show','value'=>$app_android_current_version_show, 'remark'=>'APP Android（安卓）当前显示使用的版本号');
			$rows[] = array('key'=>'app_android_current_version_url','value'=>$app_android_current_version_url,'remark'=>'APP Android（安卓）当前最高版本下载地址');
			
			$rows[] = array('key'=>'app_ios_version','value'=>$app_ios_version,'remark'=>'APP IOS（苹果）最低版本号');
			$rows[] = array('key'=>'app_ios_current_version','value'=>$app_ios_current_version,'remark'=>'APP IOS（苹果）当前最高版本号');
			$rows[] = array('key'=>'app_ios_size','value'=>$app_ios_size,'remark'=>'APP IOS（苹果）安装包大小，单位为MB');
			$rows[] = array('key'=>'app_ios_current_version_show','value'=>$app_ios_current_version_show,'remark'=>'APP IOS（苹果）当前显示使用的版本号');
			$rows[] = array('key'=>'app_ios_current_version_url','value'=>$app_ios_current_version_url,'remark'=>'APP IOS（苹果）当前最高版本下载地址');
			
			$rs = $this->config_model->save_all($rows);
			if($rs){
				$this->_build_cache();
				$this->log('保存APP版本号成功', array_merge($_GET, $_POST));
				$this->success('保存APP版本号成功');
			}else{
				$this->log('保存APP版本号失败', array_merge($_GET, $_POST));
				$this->error('保存APP版本号失败');
			}
		}else{
			$data=array();
			foreach($this->config_model->get(array('app_android_version', 'app_android_current_version', 'app_android_size', 'app_android_current_version_show', 'app_android_current_version_url',
										 'app_ios_version', 'app_ios_current_version' , 'app_ios_size', 'app_ios_current_version_show', 'app_ios_current_version_url')) as $v){
				$data[$v['key']] = $v['value'];
			}
			$this->load->view('setting/set_app_version',$data);
		}
	}

    /**
     * 邀请好友配置
     *
     * @author  唐赫
     * @version 2015.01.19
     */
    public function set_invite_conf()
    {
        $day_time = 24 * 60 * 60; //一天的时间戳

        if($this->input->post('dosubmit')) { //保存配置

            $expiry = (int)$this->input->post('iv_expiry');
            $expiry_remark = $this->input->post('iv_expiry_remark');
            $order_sum = (float)$this->input->post('iv_order_sum');
            $order_sum_remark = $this->input->post('iv_order_sum_remark');
            $commission = (float)$this->input->post('iv_commission');
            $commission_remark = $this->input->post('iv_commission_remark');

            $config[] = array('key' => 'iv_expiry', 'value' => $expiry * $day_time, 'remark' => $expiry_remark);
            $config[] = array('key' => 'iv_order_sum', 'value' => $order_sum, 'remark' => $order_sum_remark);
            $config[] = array('key' => 'iv_commission', 'value' => $commission, 'remark' => $commission_remark);

            $status = $this->config_model->save_all($config); //保存到数据库

            if($status){
                $this->_build_cache(); //生成配置文件
                $this->success('保存邀请好友配置成功');
            }else{
                $this->error('保存邀请好友配置失败');
            }

        } else { //读取配置

            $config_list = $this->config_model->get(array('iv_expiry', 'iv_order_sum', 'iv_commission'));

            $data = array();
            foreach($config_list as $v) {
                $data[$v['key']] = $v['value'];
				$data[$v['key'].'_remark'] = $v['remark'];
            }
            $data['day_time'] = $day_time;

            $this->load->view('setting/set_invite_conf', $data);
        }
    }
    
    /**
     * 活动设置
     * @access public
     * @author 韦明磊
     */
    public function goods_conf()
    {
    	$this->load->view('setting/goods_conf_main');
    }
    
    /**
     * 用户名关键字过滤配置
     * 
     * @author 关小龙
     * @version 2015-08-05 15:12:00
     */
    public function reg_keywords_filt()
    {
    	$this->load->model('uc_yzw_config_model');
    	$filt_keywords = $this->uc_yzw_config_model->find_all();
    	
    	if ($this->input->post('save')=='yes')
    	{
    		$post_filt_keywords = trim( $this->input->post('filt_keywords') );
    		$post_filt_keywords	= str_replace('，', ',', $post_filt_keywords); // 替换中文'，'为英文','
    		$post_filt_keywords	= preg_replace("/,(\s)+/", ',', $post_filt_keywords); //去掉关键字前端的空格
    		$post_filt_keywords	= preg_replace("/(\s)+,/", ',', $post_filt_keywords); //去掉关键字后端的空格
    		
    		$ret = $this->uc_yzw_config_model->update( array() , array('NameWord'=>$post_filt_keywords) );
    		if( $ret ){
    			$this->success('保存成功');
    		}else{
    			$this->error('保存失败');
    		}
    	}
    	
    	$this->load->view('setting/reg_keywords_filt', array(
    		'filt_keywords'=> $filt_keywords ? $filt_keywords['0']['NameWord'] : '',
    	));
    }
	
    /**
     * 分期付款账号设置
     * @author minchyeah
     * @version 2015-12-10 10:12:00
     */
    public function fenqi_account()
    {
    	if ($this->input->post('save')=='yes')
    	{
    		$section = trim(strval($_POST['section']));
    		switch ($section){
    			case 'pay_account':
    				$fenqi_pay_account = trim( $this->input->post('fenqi_pay_account') );
    				$fenqi_pay_account	= str_replace('，', ',', $fenqi_pay_account); // 替换中文'，'为英文','
    				$fenqi_pay_account	= preg_replace("/,(\s)+/", ',', $fenqi_pay_account); //去掉关键字前端的空格
    				$fenqi_pay_account	= preg_replace("/(\s)+,/", ',', $fenqi_pay_account); //去掉关键字后端的空格
    
    				$ret = $this->config_model->replace_save('fenqi_pay_account', $fenqi_pay_account, '分期付款账号');
    				if( $ret ){
    					$this->_build_cache();
    					$this->log('保存分期付款账号设置成功', array_merge($_GET, $_POST));
    					$this->success('保存成功');
    				}else{
    					$this->log('保存分期付款账号设置失败', array_merge($_GET, $_POST));
    					$this->error('保存失败');
    				}
    				break;
    			case 'exchange_scale':
    				$exchange_scale = trim( $this->input->post('fenqi_exchange_scale') );
    
    				$ret = $this->config_model->replace_save('fenqi_exchange_scale', $exchange_scale, '兑换券赠送比例');
    				if( $ret ){
    					$this->_build_cache();
    					$this->log('保存分期兑换券赠送比例设置成功', array_merge($_GET, $_POST));
    					$this->success('保存成功');
    				}else{
    					$this->log('保存分期兑换券赠送比例设置失败', array_merge($_GET, $_POST));
    					$this->error('保存失败');
    				}
    				break;
    			default:
    		}
    	}
    	$data = array();
    	foreach($this->config_model->get(array('fenqi_pay_account','fenqi_exchange_scale')) as $v){
    		$data[$v['key']] = $v['value'];
    	}
    	$this->load->view('setting/fenqi_account', $data);
    }
    
	/**
	 * 优质会员配置
	 */
	function premium_user()
	{
		$old_accelerate_rebate_day = $old_invalid_rebate_money = 0;
		$setting_keys = array('accelerate_rebate_day', 'invalid_rebate_money');
		$setting = $this->config_model->get($setting_keys);
		foreach ($setting as $_item) {
			if($_item['key'] == 'accelerate_rebate_day'){
				$old_accelerate_rebate_day = intval($_item['value']);
			}
			if($_item['key'] == 'invalid_rebate_money'){
				$old_invalid_rebate_money = floatval($_item['value']);
			}
		}
		if(isset($_POST['save']))
		{
			$accelerate_rebate_day = intval($this->get_post('accelerate_rebate_day'));
			$accelerate_rebate_day_remark = trim($this->get_post('accelerate_rebate_day_remark'));
			
			$invalid_rebate_money = floatval($this->get_post('invalid_rebate_money'));
			$invalid_rebate_money_remark = trim($this->get_post('invalid_rebate_money_remark'));
			
			if($accelerate_rebate_day < 0 || $accelerate_rebate_day > 21){
				$this->error('返现加速天数必须是大于等于0且小于等于21的正整数');
			}
			
			if($invalid_rebate_money < 0 || $invalid_rebate_money > 100000 || bcadd($invalid_rebate_money, 0, 2) != $invalid_rebate_money){
				$this->error('返现金额必须是0~100000之间的数字，保留两位小数');
			}
			// 有变更才更新
			if($invalid_rebate_money !== $old_invalid_rebate_money ){
				$this->config_model->replace_save('invalid_rebate_money',$invalid_rebate_money, $invalid_rebate_money_remark);
			}
			if($accelerate_rebate_day !== $old_accelerate_rebate_day){
				$this->config_model->replace_save('accelerate_rebate_day',$accelerate_rebate_day, $accelerate_rebate_day_remark);
				$this->load->model('user_premium_model', 'user_premium');
				# 优质会员1期，没有优质会员等级，默认为1
				$this->user_premium->update_premium_user(array('level'=>1), array('accelerate'=>$accelerate_rebate_day, 'updateline'=>TIMESTAMP));
			}
			
			$this->_build_cache();
			$this->success('保存成功');
		}else{
			$accelerate_rebate_day = $old_accelerate_rebate_day;
			$invalid_rebate_money = $old_invalid_rebate_money;
			$this->load->view('user/premium', get_defined_vars());
		}
	}
}
// End of class Setting

/* End of file setting.php */
/* Location: ./application/controllers/setting.php */