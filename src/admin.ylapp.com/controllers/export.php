<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 数据导出
 * @author minch <yeah@minch.me>
 * @property Excel_Xml $excel
 * @property Data_export_model $data
 */
class Export extends MY_Controller 
{
	public $check_access = TRUE;
	public $except_methods = array();
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('data_export_model', 'data');
	}
	
	/**
	 * 损耗费导出
	 */
	public function fee()
	{
		$_POST['doexport']=isset($_POST['doexport'])?$_POST['doexport']: '' ;
		if('yes' == $_POST['doexport']){
			$starttime = strtotime($this->get_post('startTime'));
			$endtime = strtotime($this->get_post('endTime'));
			if(!$starttime OR !$endtime){
				$this->error('由于数据太多请务必选择起止时间');
			}
			$data = $this->data->fee($starttime, $endtime);
			$title = '损耗费导出';
			$filename = $title.date("Y-m-d-H:i:s", $starttime).' - '.date("Y-m-d-H:i:s",$endtime).'.xls';
			$header = array(
					'活动编号',
					'商家名称',
					'活动上线时间',
					'最初上线份数',
					'后期追加分数',
					'网购价',
					'卖出交易额',
					'已返现人数',
					'已返现总人数',
					'单品损耗费',
					'区间损耗费',
					'总损耗费',
					'所属伙伴'
				);
			array_unshift($data, $header);
			$this->data_export($data, $title, $filename);
		}
		$this->load->view('export/fee');
	}
	
	/**
	 * 交易额导出
	 */
	public function trade()
	{
		// 活动新旧筛选下拉框
		$data['goods_season_types'] = Goods_model::$new_or_add_str;
		// 活动类型下拉框
		$data['goods_types'] = array(
			'1' => array('value' => Goods_model::TYPE_NORMAL, 'name' => Goods_model::TYPE_NORMAL_NAME),
			'2' => array('value' => Goods_model::TYPE_MPG, 'name' => Goods_model::TYPE_MPG_NAME),
			'3' => array('value' => Goods_model::TYPE_YZCM, 'name' => Goods_model::TYPE_YZCM_NAME),
			'4' => array('value' => Goods_model::TYPE_YZCM_QRCODE, 'name' => Goods_model::TYPE_YZCM_QRCODE_NAME),
			'5' => array('value' => Goods_model::TYPE_YZCM_SEARCH_BUY, 'name' => Goods_model::TYPE_YZCM_SEARCH_BUY_NAME),
			'6' => array('value' => Goods_model::TYPE_SEARCH_BUY, 'name' => Goods_model::TYPE_SEARCH_BUY_NAME),
			'7' => array('value' => Admin_goods_model::GOODS_IS_MOBILE_PRICE, 'name' => '手机专享价')
		);
		
		$_POST['doexport']=isset($_POST['doexport'])?$_POST['doexport']: '' ;
		if('yes' == $_POST['doexport']){
			// 活动期类
			$goods_season_type_index = intval( $this->get_post('season_type_index') );
			$goods_season_type = in_array($goods_season_type_index, array(1,2)) ?  $goods_season_type_index : null;
			// 活动类型
			$goods_type_index = intval( $this->get_post('type_index') );
			$goods_type = $data['goods_types'][$goods_type_index]['value'] >=0 ? $data['goods_types'][$goods_type_index]['value'] : null;
			
			$pid = intval($this->get_post('pid'));
			$cid = intval($this->get_post('cid'));
			$starttime = strtotime($this->get_post('startTime'));
			$endtime = strtotime($this->get_post('endTime'));
			if(!$starttime OR !$endtime){
				$this->error('由于数据太多请务必选择起止时间');
			}
			$data =$this->data->trade( $starttime, $endtime, $pid, $cid, $goods_season_type, $goods_type );
			$title = '交易额导出';
			$filename = $title.date("Y-m-d-H:i:s", $starttime).' - '.date("Y-m-d-H:i:s",$endtime).'.xls';
			$header = array(
					'所有活动',
					'活动类型',
					'活动编号',
					'主类目',
				    '子类目',
					'商家名称',
					'活动上线时间',
					'最初上线份数',
					'后期追加分数',
					'网购价',
					'已填订单号人数',
					'已填订单号交易总额',
					'单品损耗费',
					'即时损耗费',
					'所属伙伴',
				);
			array_unshift($data, $header);
			$this->data_export($data, $title, $filename);
		}


		//获取活动的所有分类
		$categorylist=$this->db->select(' id,name,pid ')->order_by('sort')->get('goods_category')->result_array();
		foreach ($categorylist as $k=>$v) {
			if($v['pid']>0){
				$data['cidlist'][$v['pid']][]=$v;
			}else{
				$data['pidlist'][]=$v;
			}
		}
		$data['cidlist']=json_encode($data['cidlist']);
		$this->load->view('export/trade',$data);
	}
	
	/**
	 * 财务数据导出
	 */
	public function finance()
	{
		$_POST['doexport']=isset($_POST['doexport'])?$_POST['doexport']: '' ;
		if('yes' == $_POST['doexport']){
			$type = $this->get_post('type');
			$pid = intval($this->get_post('pid'));
			$cid = intval($this->get_post('cid'));
			$starttime = strtotime($this->get_post('startTime'));
			$endtime = strtotime($this->get_post('endTime'));
			if(!$starttime OR !$endtime){
				$this->error('由于数据太多请务必选择起止时间');
			}
			$data = $this->data->finance($type, $starttime, $endtime,$pid,$cid);
			$title = '财务数据导出';
			$filename = '财务数据导出'.date("Y-m-d-H:i:s", $starttime).' - '.date("Y-m-d-H:i:s",$endtime).'.xls';
			$header = array(
					'上线时间',
					'商家编号',
					'商家名称',
					'活动类型',
					'活动编号',
					'主类目',
					'子类目',
					'已存担保金',
					'已存损耗费',
					'所属伙伴'
				);
			array_unshift($data, $header);
			$this->data_export($data, $title, $filename);
		}
		//获取活动的所有分类
		$categorylist=$this->db->select(' id,name,pid ')->order_by('sort')->get('goods_category')->result_array();
		foreach ($categorylist as $k=>$v) {
			if($v[pid]>0){
				$data['cidlist'][$v['pid']][]=$v;
			}else{
				$data['pidlist'][]=$v;
			}
		}
		$data['cidlist']=json_encode($data['cidlist']);
		$this->load->view('export/finance',$data);
	}
	
	/**
	 * 导出开团提醒数据
	 */
	public function goods_remind()
	{
		$starttime = strtotime($this->get_post('startTime'));
		$endtime = strtotime($this->get_post('endTime'));
		$endTime = $endTime ? $endTime+24*3600 : 0;
		$search_key = trim(strval($this->get_post('search_key')));
		$search_val = trim(strval($this->get_post('search_val')));
		$data = $this->data->goods_remind($starttime, $endtime, $search_key, $search_val);
		$title = '开团提醒';
		$filename = $title.'.xls';
		$header = array(
				'状态',
				'会员名',
				'活动编号',
				'邮箱',
				'手机',
				'开团/追加提醒',
				'时间'
			);
		array_unshift($data, $header);
		$this->data_export($data, $title, $filename);
	}
	
	/**
	 * 错误提示
	 */
	protected function error($message)
	{
		header('Content-Type: text/html; charset=utf-8');
		$js = <<<JS
		<script type="text/javascript">
			alert('{$message}');
		</script>
JS;
		exit($js);
	}
	
	/**
	 * 2周年庆参与用户数据导出
	 */
	public function zhounian(){
		if('yes' == $_POST['doexport']){
			$type = $this->get_post('type');
			$starttime = strtotime($this->get_post('startTime'));
			$endtime = strtotime($this->get_post('endTime'));
			if(!$starttime OR !$endtime){
				$this->error('由于数据太多请务必选择起止时间');
			}
			
			$user_state = array(0=>'正常',1=>'调查',2=>'一般屏蔽',3=>'严重屏蔽',4=>'很严重屏蔽',5=>'封号',6=>'自动屏蔽-订单错误数',7=>'自动屏蔽-被申诉次数',8=>'自动屏蔽-购买次数');
			$uqualification_map = array(0=>'异常状态', 1=>'符合', 2=>'违规');
			$this->db->select('activity.uid, activity.uname, activity.dateline, user.is_lock, activity.uqualification, activity.uprice, activity.ucost_price, activity.ucost_price,activity.ureal_rebate, activity.email, activity.mobile');
			$data = $this->db->from('user_activity activity')->join('user','user.uid=activity.uid')->where('activity.dateline >='.$starttime,NULL,TRUE)->where('activity.dateline <='.$endtime, NULL, TRUE)->where('activity.period',1)->get()->result_array();
			
			foreach ($data as $k=>$v){
				$data[$k]['is_lock'] = isset($user_state[$v['is_lock']]) ? $user_state[$v['is_lock']] : '/';
				$data[$k]['dateline'] = date('Y-m-d G:i:s',$v['dateline']);
				
				$data[$k]['uqualification'] = isset($uqualification_map[$v['uqualification']]) ? $uqualification_map[$v['uqualification']] : '/';
			}
			
			$title = '2周年庆参与用户数据导出';
			$filename = '2周年庆参与用户数据导出'.date("Y-m-d-H:i:s", $starttime).' - '.date("Y-m-d-H:i:s",$endtime).'.xls';
			$header = array(
					'用户编号',
					'用户名',
					'提交时间',
					'用户状态',
					'报名资格',
					'网购价',
					'活动价',
					'返现金额',
					'邮箱',
					'手机'
			);
			array_unshift($data, $header);
			$this->data_export($data, $title, $filename);
		}
		$this->load->view('export/zhounian');
	}
	
	/**
	 * 6月夏日专场参与用户数据导出
	 */
	public function june_activity(){
		if('yes' == $_POST['doexport']){
			$type = $this->get_post('type');
			$starttime = strtotime($this->get_post('startTime'));
			$endtime = strtotime($this->get_post('endTime'));
			if(!$starttime OR !$endtime){
				$this->error('由于数据太多请务必选择起止时间');
			}
			$user_state = array(0=>'正常',1=>'调查',2=>'一般屏蔽',3=>'严重屏蔽',4=>'很严重屏蔽',5=>'封号',6=>'自动屏蔽-订单错误数',7=>'自动屏蔽-被申诉次数',8=>'自动屏蔽-购买次数');
			$uqualification_map = array(0=>'异常状态', 1=>'符合', 2=>'违规');
			$this->db->select('activity.uid, activity.uname, activity.dateline, user.is_lock, activity.uqualification, activity.uprice, activity.ucost_price, activity.ucost_price,activity.ureal_rebate, activity.email, activity.mobile');
			$data = $this->db->from('user_activity activity')
			                            ->join('user','user.uid=activity.uid')
			                            ->where('activity.dateline >='.$starttime,NULL,TRUE)
			                            ->where('activity.dateline <='.$endtime, NULL, TRUE)
			                            ->where('activity.period',2)
		                            	->get()->result_array();
				
			foreach ($data as $k=>$v){
				$data[$k]['is_lock'] = isset($user_state[$v['is_lock']]) ? $user_state[$v['is_lock']] : '/';
				$data[$k]['dateline'] = date('Y-m-d G:i:s',$v['dateline']);
	
				$data[$k]['uqualification'] = isset($uqualification_map[$v['uqualification']]) ? $uqualification_map[$v['uqualification']] : '/';
			}
				
			$title = '6月夏日专场参与用户数据导出';
			$filename = '6月夏日专场参与用户数据导出'.date("Y-m-d-H:i:s", $starttime).' - '.date("Y-m-d-H:i:s",$endtime).'.xls';
			$header = array(
					'用户编号',
					'用户名',
					'提交时间',
					'用户状态',
					'报名资格',
					'网购价',
					'活动价',
					'返现金额',
					'邮箱',
					'手机'
			);
			array_unshift($data, $header);
			$this->data_export($data, $title, $filename);
		}
		$this->load->view('export/june_activity');
	}
}
// End of class Export

/* End of file export.php */
/* Location: ./application/controllers/export.php */