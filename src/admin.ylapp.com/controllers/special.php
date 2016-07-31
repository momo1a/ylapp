<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

/**
 * 专题控制器类
 * 
 * @author 宁天友
 * @version 2014-10-30
 */
class Special extends MY_Controller {
	public function __construct() {
		parent::__construct ();
	}
	
	public function index(){
	}
	
	/**
	 * 2014双11奖品
	 */
	public function prize() {
		$list = $this->db->select('*')->from('special_prize')->get()->result_array();
		$this->load->view ( 'special/prize', get_defined_vars());
	}
	
	/**
	 * 2014双11奖品保存分类
	 */
	public function save(){
		$allow_prize_pid = array(1,2,3,4,5,6,7,8,9); 
		$pid = intval($this->input->post('pid', true));
		$name = trim($this->input->post('name', true));
		$quantity = intval($this->input->post('quantity', true));
		$type = intval($this->input->post('type', true));
		$cid = intval($this->input->post('cid', true));

		if( ! in_array($pid, $allow_prize_pid) OR $name == '' OR $quantity <= 0 OR $type <= 0 OR (in_array($pid, array(1,2,3,4)) && $cid <= 0)){
			$this->error('提交数据有误');
		}
        if(in_array($pid, array(1,2,3,4)) && $cid > 0) {
            $this->load->model('cash_model');
            ($quantity != $this->cash_model->get_enable_number($cid)) && $this->error('现金券发放数量不对');
        }
		$this->db->update('special_prize', array('name'=>$name, 'quantity'=>$quantity, 'type'=>$type, 'cid'=>$cid), array('pid'=>$pid));
		$this->success('保存成功');
	}
	
	/**
	 * 奖品随机分配生成
	 */
	public function rand_prize() {
		$this->load->model(
            array('special_prize_model','special_prize_rand_model')
        );
        $this->special_prize_rand_model->get_count() && $this->error('数据已生成');

		$prize_result = $this->special_prize_model->get_list();
		$tmp_am = $tmp_pm = $prize_arr = array();
		foreach($prize_result as $v) {
			if(!$v['quantity']) continue;
            $prize_arr['p'.$v['pid']] = $v['name'];
			//奖品数量为1时，放在下午抽奖数据中
			if($v['quantity'] <= 1) {
				$tmp_pm = array_merge($tmp_pm, array_fill(0, $v['quantity'], $v['pid']));
				continue;
			}
			$_tmp_sum = floor($v['quantity'] / 2);
			//上午奖品数据
			$tmp_am = array_merge($tmp_am, array_fill(0, $_tmp_sum, $v['pid']));
			//下午奖品数据
			$tmp_pm = array_merge($tmp_pm, array_fill(0, $v['quantity'] - $_tmp_sum, $v['pid']));
		}

		/**-- 随机打乱数据后生成到随机分配表中 start --**/
		$data = array();
		$status = shuffle($tmp_am);
		$status || $this->error('数据随机失败');
		for($i = 0; $i < count($tmp_am); $i++) {
			$data[] = array('pid' => $tmp_am[$i], 'time_type' => 1, 'use' => 0);
		}
		$return = $this->special_prize_rand_model->insert_batch($data);
		$return || $this->error('上午数据插入失败');
		unset($tmp_am, $data);
	
		$status = shuffle($tmp_pm);
		$status || $this->error('数据随机失败');
		for($i = 0; $i < count($tmp_pm); $i++) {
			$data[] = array('pid' => $tmp_pm[$i], 'time_type' => 2, 'use' => 0);
		}
		$return = $this->special_prize_rand_model->insert_batch($data);
        $return || $this->error('下午数据插入失败');
		unset($tmp_pm, $data);

        $diff_arr = $this->special_prize_rand_model->get_diff_count();
        $str = ''; $count = 0;
        foreach($diff_arr as $k => $v) {
            $count += $v;
            $str .= $prize_arr[$k] .' ========= '. $v .'<br>';
        }
		/**-- 随机打乱数据后生成到随机分配表中 end --**/
	
		$this->success('数据生成成功<br>'.$str.'总数为：'.$count);
	}
	
	/**
	 * 2014双11下单排名
	 */
	public function rank($offset=0) {
		$this->load->model(array(
				'special_rank_model',
		));
		$page_size=10;
		$offset=max(0,(int)$offset);
		$search_val= trim($this->input->get('search_val',true));
		$search_key= trim( $this->input->get('search_key',true));
	
		$total=  $this->special_rank_model->get_count($search_key,$search_val);
	
		//分页配置 start
		$page_conf = array('uri_segment'=>3,'anchor_class'=>'type="load" rel="div#main-wrap"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
		$pager = $this->pager($total, $page_size, $page_conf);
		//分页配置 end
	
	
		$data['rank_list']= $this->special_rank_model->get_rank_list($offset,$page_size,$search_key,$search_val);
	
		/**
		 * 账号状态:0正常,1调查,2一般屏蔽,3严重屏蔽,4很严重屏蔽,5封号,6自动屏蔽-订单错误数,7自动屏蔽-被申诉次数,8自动屏蔽-购买次数
		*/
		$lock_status = array(
				0=> '正常',
				1=> '调查',
				2=>'一般屏蔽',
				3=>'严重屏蔽',
				4=> '很严重屏蔽',
				5=> '封号',
				6=> '自动屏蔽(订单错误)',
				7=> '自动屏蔽(被申诉次数)',
				8 => '自动屏蔽(购买次数)',
		);
		$data['lock_status']=$lock_status;
		$data['search_val']=$search_val;
		$data['search_key']=$search_key;
		$data['pager']=$pager;
		$this->load->view('special/rank_data',$data);
	}
	
	/**
	 * 2014双11下单排名导出
	 */
	public function rank_export() {
		$this->load->model(array(
				'special_rank_model',
		));
		$search_val= trim($this->input->get('search_val',true));
		$search_key= trim( $this->input->get('search_key',true));
	
		$end_page =(int)  $this->input->get('end_page');
		$offset=(int)  $this->input->get('start_page')-1;
            if ($offset<=0) {
                $offset=0;
            }
            if ($end_page<$offset) {
                    @header("Content-type: text/html; charset=utf-8"); 
                    show_error('结束范围必须大于开始范围');
            }
            
           $page_size=$end_page-$offset;
           
		$rank_list= $this->special_rank_model->get_rank_list($offset,$page_size,$search_key,$search_val);
		if (count($rank_list)>10000) {
			//不知道是否需要限制
                    @header("Content-type: text/html; charset=utf-8"); 
                    show_error('数据超过10000条,请重新选择范围');
		}
		/**
		 * 账号状态:0正常,1调查,2一般屏蔽,3严重屏蔽,4很严重屏蔽,5封号,6自动屏蔽-订单错误数,7自动屏蔽-被申诉次数,8自动屏蔽-购买次数
		 */
		$lock_status = array(
				0=> '正常',
				1=> '调查',
				2=>'一般屏蔽',
				3=>'严重屏蔽',
				4=> '很严重屏蔽',
				5=> '封号',
				6=> '自动屏蔽(订单错误)',
				7=> '自动屏蔽(被申诉次数)',
				8 => '自动屏蔽(购买次数)',
		);
		$data['title'] = array(
				'排名',
				'用户ID',
				'用户名称',
				'用户状态',
				'双11网购价总和',
				'双11已返现的网购价',
		);
		foreach ($rank_list as $value) {
			$temp_data = array(
					$value['rid'],
					$value['uid'],
					$value['uname'],
					@$lock_status[$value['is_lock']],
					$value['amount'],
					$value['repayment'],
			);
			$data[] = $temp_data;
		}
		$title = "双十一下单排名导出";
		$this->data_export($data, $title, $title . ".xls");
	
	
	}
	
	/**
	 * 抽奖记录
	 */
	public function lottery($offset=0) {
		$this->load->model(array(
				'special_user_win_model',
		));
		$page_size=10;
		$offset=max(0,(int)$offset);
		$start_time=  $this->input->get('start_time',true);
		$end_time=  $this->input->get('end_time',true);
		$search_val= trim($this->input->get('search_val',true));
		$search_key= trim( $this->input->get('search_key',true));
	
		$total=  $this->special_user_win_model->get_count($search_key,$search_val,$start_time,$end_time);
	
		//分页配置 start
		$page_conf = array('uri_segment'=>3,'anchor_class'=>'type="load" rel="div#main-wrap"');
		$page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
		$page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
		$pager = $this->pager($total, $page_size, $page_conf);
		//分页配置 end
	
	
		$data['win_list']= $this->special_user_win_model->get_list($offset,$page_size,$start_time,$end_time,$search_key,$search_val);
	
		/**
		 * 账号状态:0正常,1调查,2一般屏蔽,3严重屏蔽,4很严重屏蔽,5封号,6自动屏蔽-订单错误数,7自动屏蔽-被申诉次数,8自动屏蔽-购买次数
		*/
		$lock_status = array(
				0=> '正常',
				1=> '调查',
				2=>'一般屏蔽',
				3=>'严重屏蔽',
				4=> '很严重屏蔽',
				5=> '封号',
				6=> '自动屏蔽(订单错误)',
				7=> '自动屏蔽(被申诉次数)',
				8 => '自动屏蔽(购买次数)',
		);
		$data['lock_status']=$lock_status;
		$data['end_time']=$end_time;
		$data['start_time']=$start_time;
		$data['search_val']=$search_val;
		$data['search_key']=$search_key;
		$data['pager']=$pager;
		$this->load->view('special/lottery_data',$data);
	}
	
	/**
	 * 抽奖记录导出
	 */
	public function lottery_export() {
		$this->load->model(array(
				'special_user_win_model',
		));
		$page_size=null;
		$offset=0;
		$start_time=  $this->input->get('start_time',true);
		$end_time=  $this->input->get('end_time',true);
		$search_val= trim($this->input->get('search_val',true));
		$search_key= trim( $this->input->get('search_key',true));
	
	
		$win_list= $this->special_user_win_model->get_list($offset,$page_size,$start_time,$end_time,$search_key,$search_val);
	
		if (count($win_list)>10000) {
			//不知道是否需要限制
	
		}
		/**
		 * 账号状态:0正常,1调查,2一般屏蔽,3严重屏蔽,4很严重屏蔽,5封号,6自动屏蔽-订单错误数,7自动屏蔽-被申诉次数,8自动屏蔽-购买次数
		 */
		$lock_status = array(
				0=> '正常',
				1=> '调查',
				2=>'一般屏蔽',
				3=>'严重屏蔽',
				4=> '很严重屏蔽',
				5=> '封号',
				6=> '自动屏蔽(订单错误)',
				7=> '自动屏蔽(被申诉次数)',
				8 => '自动屏蔽(购买次数)',
		);
		$data['title'] = array(
				'用户ID',
				'用户名称',
				'用户状态',
				'抽奖时间',
				'第N次抽奖',
				'抽中奖品',
				'收货地址',
		);
		foreach ($win_list as $value) {
			$temp_data = array(
					$value['uid'],
					$value['uname'],
					@$lock_status[$value['is_lock']],
					date('Y-m-d H:i:s',$value['win_time']),
					$value['times'],
					$value['name'],
					"{$value['address']},{$value['zip']},{$value['consignee']},{$value['phone']}",
							);
							$data[] = $temp_data;
		}
		$title = "抽奖记录导出";
		$this->data_export($data, $title, $title . ".xls");
	}
	
	/**
	 * 2014双11基础数据初始化(插入基础数据以及生成配置)
	 * @author 宁天友
	 */
	function init(){
		$special_config_file = implode(DIRECTORY_SEPARATOR, array(dirname(APPPATH), 'special.ylapp.com', 'app', 'config', '20141111.php'));
		if( ! is_writeable(dirname($special_config_file))){
			$this->error('special站点配置文件20141111.php不可写');
		}
		$exec_check = $this->db->from('system_privilege_action')->where('uri', 'special/prize')->count_all_results();
		$star_pid_check = intval(config_item('shuang11_2014_star_id'));
		$star_cid_check = config_item('shuang11_2014_star_cid');
		$normal_pid_check = intval(config_item('shuang11_2014_normal_id'));
		$normal_cid_check = config_item('shuang11_2014_normal_cid');
		$seckill_pid_check = intval(config_item('seckill_pid_2014_11_11'));
		if($exec_check <= 0){//未insert过
			$call = 'CALL proc_special_data_init(\'insert\');';
			$back = $this->db->query($call)->row_array();
			$back['Code'] = intval($back['Code']);
			$back['Message'] = trim($back['Message']);
			$back_data = json_decode($back['Message'], true);
			if($back['Code'] === 1){
				if($this->_save_special_config($special_config_file, $back_data)){
					$msg = '初始化成功';
					$this->success($msg);
				}else{
					$msg = '初始化成功，但2014双11配置生成失败';
					$this->error($msg);
				}
			}else{
				$msg = $back['Message'];
				$this->error($msg);
			}
		}elseif( ! $star_pid_check || ! $normal_pid_check || ! $seckill_pid_check || empty($star_cid_check) || empty($normal_cid_check)){
			//数据表insert过，但未配置或不完整
			$call = 'CALL proc_special_data_init(\'select\');';
			$back = $this->db->query($call)->row_array();
			$back['Code'] = intval($back['Code']);
			$back['Message'] = trim($back['Message']);
			$back_data = json_decode($back['Message'], true);
			if($back['Code'] === 1){
				if($this->_save_special_config($special_config_file, $back_data)){
					$msg = '初始化成功';
					$this->success($msg);
				}else{
					$msg = '初始化成功，但2014双11配置生成失败';
					$this->error($msg);
				}
			}else{
				$msg = $back['Message'];
				$this->error($msg);
			}
		}else{
			$msg = '已初始化，无需重复执行';
			$this->success($msg);
		}
	}
	
	/**
	 * 保存双11配置
	 * @param string $config_file 配置文件 
	 * @param array $conf 双11配置
	 * @return state 是否成功，成功大于0，失败为false
	 */
	function _save_special_config($config_file, $conf){
		$state = false;
		if(empty($conf)){
			return $state;
		}
		$str[] = '<?php  if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');'.chr(13).chr(10);
		$str[] = '/*---------------2014-double11-config-start---------------*/';
		$str[] = '//2014年双11狂欢主场的明星商品的专题id';
		$str[] = '$config[\'shuang11_2014_star_id\'] = '.intval($conf['star']['id']).';';
		$str[] = '//时间都是2014-11-11  24小时制:时间=>对应的cid';
		$str[] = '$config[\'shuang11_2014_star_cid\'] = '.preg_replace("/[ ]{2}/", chr(9), var_export($conf['star']['sid'], TRUE)).';'.chr(13).chr(10);
		
		$str[] = '//2014年双11狂欢主场的普通商品场次的专题id';
		$str[] = '$config[\'shuang11_2014_normal_id\'] = '.intval($conf['normal']['id']).';';
		$str[] = '//时间都是2014-11-11  24小时制:时间=>对应的cid';
		$str[] = '$config[\'shuang11_2014_normal_cid\'] = '.preg_replace("/[ ]{2}/", chr(9), var_export($conf['normal']['sid'], TRUE)).';'.chr(13).chr(10);
		
		$str[] = '//2014双11秒杀专场，专题分类父id';
		$str[] = '$config[\'seckill_pid_2014_11_11\'] = '.intval($conf['seckill']['id']).';';
		$str[] = '/*---------------2014-double11-config-end---------------*/'.chr(13).chr(10);
		$new_config = implode(chr(13).chr(10), $str);
		$state = file_put_contents($config_file, $new_config);
		return $state;
	}
	/**
	 * 数据导出下载
	 * @param array $data 数据
	 * @param string $title 标题
	 * @param string $filename 下载文件名
	 */
	protected function data_export($data, $title, $filename)
	{
		$this->load->library('Excel_Xml', '', 'excel');
		$this->excel->addWorksheet($title, $data);
	
		ob_start();
		$this->excel->sendWorkbook($filename);
		$contents = ob_get_contents();
		$this->load->helper('download');
		force_download($filename,$contents);
	
		exit();
	}
}