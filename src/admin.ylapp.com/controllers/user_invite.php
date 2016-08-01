<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 管理后台-会员邀请控制器类
 * 
 * @author  关小龙
 * @version 2015.1.21
 * @property invite_user_model $invite_user_model
 * @property invite_user_pay_model $invite_user_pay_model
 * @property invite_log_model $invite_log_model
 */
class User_invite extends MY_Controller 
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    
    /**
     * 显示好友邀请列表
     */
    public function index()
    {
    	$page_size = $this->uri->segment(3) ? $this->uri->segment(3) : '10';
    	$offset =  $this->uri->segment(4);
    	
    	$this->load->model('invite_user_model');
    	$search_where = self::_invite_search_where();
    	
    	//查询结果
    	$invite_user = $this->invite_user_model->find_invite_user($search_where,$page_size,$offset);//查询结果集
    	$total = $this->invite_user_model->find_invite_user_count($search_where); //条件查询总数
    	//分页
    	$page_conf = array('uri_segment'=>4,'anchor_class'=>'type="load" rel="div#main-wrap"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$page_size);
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$page_size.'/0');
    	$pager = $this->pager($total, $page_size, $page_conf);
    	
    	$this->load->view('user/invite/invite_user' , get_defined_vars());
    }
    
    
    /**
     * 邀请好友记录查询条件
     * 
     * @return array $where 查询条件
     */
    private function _invite_search_where()
    {
    	$startTime  = $this->input->get('startTime');
    	$endTime    = $this->input->get('endTime');
    	$search_key = $this->get_post('search_key');
    	$search_val = trim($this->get_post('search_value'));
    	$state      = (int)$this->input->get('state');
    	
    	$search_where = array();
    	//注册时间查询条件
    	$startTime = (int)strtotime($startTime);
    	$endTime   = (int)strtotime($endTime);
    	if( $startTime > 0 || $endTime > 0 )
    	{
    		$search_where = array_merge($search_where, array('reg_time >='=>$startTime,'reg_time <='=>$endTime) );
    	}
    	//被邀请人名称查询条件
    	if($search_key=='beivuname' && !empty($search_val))
    	{
    		$search_where = array_merge($search_where, array('beivuname LIKE'=>'%'.$search_val.'%') );
    	}
    	if($search_key=='ivuname' && !empty($search_val))
    	{
    		$search_where = array_merge($search_where, array('ivuname LIKE'=>'%'.$search_val.'%') );
    	}
    	//邀请编号查询条件
    	if($search_key=='ivid'  && !empty($search_val))
    	{
    		$search_where = array_merge($search_where, array('ivid'=>$search_val) );
    	}
    	//邀请状态查询条件
    	if( $state > 0 )
    	{
    		if( $state == YL_invite_user_model::STATUS_TIME_IN )
    		{
    			$search_where = array_merge($search_where, array('reg_time + expiry_date >='=>time() ,'state'=>'0' ) );
    		}
    		elseif( $state == YL_invite_user_model::STATUS_TIME_OUT )
    		{
    			$search_where = array_merge($search_where, array('reg_time + expiry_date <'=>time() ,'state'=>'0'  ) );
    		}
    		else
    		{
    			$search_where = array_merge($search_where, array('state'=>$state) );
    		}
    	}
    	
    	return $search_where;
    }
    
    
    /**
     * 修改奖励操作码
     */
    public function password()
    {
    	if('is_post' != $this->input->get_post('is_post'))
    	{
    		$this->load->view('user/invite/password');return;
    	}
    	$old_pwd = md5($this->get_post('old_pwd',''));
    	$new_pwd = md5($this->get_post('new_pwd',''));
    	$this->load->model('system_config_model');
    	$db_old_pwd = $this->system_config_model->get('iv_password');
    	if ($old_pwd != $db_old_pwd['value']) $this->error('当前奖励金操作码不正确！');
    	$this->system_config_model->save('iv_password', $new_pwd, $remark = '会员转介绍奖励金操作码');
    	$this->success('修改操作码成功！');
    }
    
    
    /**
     * 导出邀请好友列表
     */
    public function export()
    {
    	$this->load->model('invite_user_model');
    	$search_where = self::_invite_search_where();
    	//查询结果
    	$invite_user = $this->invite_user_model->find_invite_user($search_where);//查询结果集
    	if( count($invite_user)>20000 ) $this->error('由于数据太多，请选择起止时间');
    	
    	$data=array();
    	if( is_array($invite_user) && !empty($invite_user) )
    	{
    		foreach ($invite_user as $v)
    		{
    			$data[] = array(
    				$v['ivid'],
    				$v['beivuname'],
    				date('Y-m-d H:i',$v['reg_time']),
    				$v['ivuname'],
    				$v['commission'],
    				YL_invite_user_model::show_state($v['state'],$v['reg_time'],$v['expiry_date'])
    			);
    		}
    	}
    	
    	$title = '邀请好友数据导出';
    	$filename = '邀请好友数据导出.xls';
    	$header = array(
    			'邀请编号',
    			'新注册用户',
    			'注册时间',
    			'邀请人',
    			'获得奖励',
    			'邀请状态'
    	);
    	array_unshift($data, $header);
    	$this->data_export($data, $title, $filename);
    }
    
    
    /**
     * 奖励打款记录
     */
    public function commission()
    {
    	$page_size = $this->uri->segment(3) ? $this->uri->segment(3) : '10';
    	$offset =  $this->uri->segment(4);
    	
    	$this->load->model('invite_user_pay_model');
    	$search_where = self::_commission_search_where();
    	//查询结果
    	$user_pay = $this->invite_user_pay_model->find_invite_user_pay($search_where,$page_size,$offset);//查询结果集
    	$total = $this->invite_user_pay_model->find_invite_user_pay_count($search_where); //条件查询总数
    	//分页
    	$page_conf = array('uri_segment'=>4,'anchor_class'=>'type="load" rel="div#main-wrap"');
    	$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$page_size);
    	$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/'.$page_size.'/0');
    	$pager = $this->pager($total, $page_size, $page_conf);
    	
    	$this->load->view('user/invite/invite_commission' , get_defined_vars());
    }
    
    /**
     * 奖励打款记录查询条件
     * 
     * @return array $where 查询条件
     */
    private function _commission_search_where()
    {
    	$startTime  = $this->input->get('startTime');
    	$endTime    = $this->input->get('endTime');
    	$search_key = $this->get_post('search_key');
    	$search_val = trim($this->get_post('search_value'));
    	
    	$search_where = array();
    	//注册时间查询条件
    	$startTime = (int)strtotime($startTime);
    	$endTime   = (int)strtotime($endTime);
    	if( $startTime > 0 || $endTime > 0 )
    	{
    		$search_where = array_merge($search_where, array('dateline >='=>$startTime,'dateline <='=>$endTime) );
    	}
    	//邀请人名称查询条件
    	if($search_key=='ivuname' && !empty($search_val))
    	{
    		$search_where = array_merge($search_where, array('ivuname LIKE'=>'%'.$search_val.'%') );
    	}
    	//邀请编号查询条件
    	if($search_key=='ivid'  && !empty($search_val))
    	{
    		$search_where = array_merge($search_where, array('ivid'=>$search_val) );
    	}
    	
    	return $search_where;
    }
    
    
    /**
     * 查询已经打款的总额
     */
    public function sum_commission()
    {
    	$search_where = array('state'=>YL_invite_user_pay_model::STATUS_ALREADY_PAY);
    	$this->load->model('invite_user_pay_model');
    	$user_pay = $this->invite_user_pay_model->sum_commission($search_where);//查询结果集
    	echo isset($user_pay['commission']) ? $user_pay['commission'] : '0.00';
    }
    
    
    /**
     * 一键打款
     * 
     */
    public function pay()
    {
    	$limit = 100; //每次最大处理的数量
    	$offset = 0;
    	$this->load->model('invite_user_pay_model');
    	$wait_pay_user = $this->invite_user_pay_model->find_wait_pay_user($limit,$offset);//查询结果集
    	
    	$last_money = self::get_last_money(); //互联支付余额
    	
    	if('is_post' != $this->input->get_post('is_post'))
    	{
    		$need_money_count = count($wait_pay_user);
    		$need_money = 0; //待付款奖励
    		foreach ($wait_pay_user as $user){$need_money += $user['commission'];}
    		
    		$wait_pay_user_total = $this->invite_user_pay_model->find_wait_pay_user_count();//查询符合待打款的总数
    		$this->load->view('user/invite/pay_user',get_defined_vars());return;
    	}
    	$op_password =  trim($this->input->get_post('op_password'));
    	$this->load->model('system_config_model');
    	$db_pwd = $this->system_config_model->get('iv_password');
    	if ( md5($op_password) != $db_pwd['value'] ) $this->error('输入的操作码不正确！');
    	if( !is_array($wait_pay_user) || empty($wait_pay_user) ) $this->error('打款失败，无待打款账户！');
    	if( $this->input->get_post('need_money') > $last_money ) $this->error('打款失败，账户余额不足！');
    	
    	$sucess  = 0;
    	$failure = 0;
    	foreach ($wait_pay_user as $user)
    	{
    		$pay_info = array(
    			 'uid'   =>$user['payuid'],     //支付的用户id
    			 'touid' =>$user['ivuid'],      //接收的用户id
    			 'pNo'   =>$user['pno'],        //交易号
    			 'title' =>'管理员打款邀请奖励给“'.$user['ivuname'].'”（邀请编号'.$user['ivid'].'）',//标题
    			 'money' =>$user['commission']  //支付交易的金额,邀请人的奖励
    	    );
    		$res = (int)$this->hlpay->hlpay_immediate_pay($pay_info);
    		if( $res > 0 )
    		{
    			$sucess ++;
    			self::pay_return($user['ivuid'],$user['ivid']); //打款完成的回调函数
    		}
    		else 
    		{
    			$failure ++;
    			log_message('error', '邀请好友打款失败[error:'.$res.'],打款信息:'.print_r($pay_info,TRUE));
    		}
    	}
    	$this->success('打款完成，其中打款成功'.$sucess.'个，打款失败'.$failure.'个');
    }
    
    
    /**
     * 获取打款账户余额
     * 
     * @return decimal $money 金额
     */
    private function get_last_money() 
    {
    	$this->load->library('hlpay');
    	$money = (float)$this->hlpay->get_user_money( intval(config_item('iv_pay_uid')) );
    	return $money >=0 ? $money : '0.00';
    }
    
    
    /**
     * 打款完成的回调函数
     * 
     * @param int $ivuid 邀请人UID
     * @param int $ivid 邀请UID
     */
    private function pay_return($ivuid,$ivid)
    {
    	$this->invite_user_pay_model->pay_return($ivuid,$ivid,$this->user_id,$this->username,ip('int'));
    }
    
    
    /**
     * 操作记录
     * 
     */
    public function find_log()
    {
    	$ivid    = $this->get_post('ivid');
    	$this->load->model('invite_log_model');
    	$logs = $this->invite_log_model->find_logs( array('ivid'=>$ivid) );
    	$this->load->view('user/invite/log',get_defined_vars());
    }
    
} //end class