<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

/**
 * 用户管理控制器类
 * 
 * @author yangjiguang
 * @version 2014-06-27
 * @property admin_user_model $user_model
 */
class Source extends MY_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'admin_user_model', 'user_model' );
	}
	
	/**
	 * 所有的用户统计列表
	 */
	public function index(){
		
       //配置用户来源URL名称
		$urls= $this->get_source_data();

		$startTime = strtotime ( $this->get_post ( 'startTime' ) ) ? strtotime ( $this->get_post ( 'startTime' ) ) : strtotime ( date ( 'Y-m-d', time () ) );
		$endTime = strtotime ( $this->get_post ( 'endTime' ) ) ? strtotime ( $this->get_post ( 'endTime' ) ) : time ();
		$search_val = trim( $this->get_post ( 'search_value' ));
		$users = $this->user_model->get_reg_source ( $startTime, $endTime, $search_val );
		$other_num_buyer = $other_num_seller = 0;
		
		foreach ( $users as $val ) {
			$other_flag = true;
			if ($val ['reg_from_url']) {
				// 精确匹配
				foreach ( $urls as $k => $v ) {
					if ($other_flag == false) {
						break;
					}
					if ($v ['type'] == 2 && $other_flag) {
						if ($val ['reg_from_url'] == $v ['url']) {
							$other_flag = false;
							if ($val ['utype'] == 1) {
								$urls [$k] ['num_buyer'] += 1;
							} elseif ($val ['utype'] == 2) {
								$urls [$k] ['num_seller'] += 1;
							}
							break;
						}
					}
				}
				// 模糊匹配
				foreach ( $urls as $k => $v ) {
					if ($other_flag == false) {
						break;
					}
					if ($v ['type'] == 1 && $other_flag) {
						if (stristr ( $val ['reg_from_url'], $v ['url'] ) !== false) {
							$other_flag = false;
							if ($val ['utype'] == 1) {
								$urls [$k] ['num_buyer'] += 1;
							} elseif ($val ['utype'] == 2) {
								$urls [$k] ['num_seller'] += 1;
							}
							break;
						}
					}
				}
			}
			if ($other_flag) {
				if ($val ['utype'] == 1) {
					$other_num_buyer += 1;
				} elseif ($val ['utype'] == 2) {
					$other_num_seller += 1;
				}
			}
		}
		$this->load->view ( 'source/index', get_defined_vars () );
	}
	
	/**
	 * 导出列表
	 */
	public function export() {

		//配置用户来源URL名称
		$urls= $this->get_source_data();
		
		$startTime = strtotime ( $this->get_post ( 'startTime' ) ) ? strtotime ( $this->get_post ( 'startTime' ) ) : strtotime ( date ( 'Y-m-d', time () ) );
		$endTime = strtotime ( $this->get_post ( 'endTime' ) ) ? strtotime ( $this->get_post ( 'endTime' ) ) : time ();
		$search_val = trim( $this->get_post ( 'search_value' ));
		
		$users = $this->user_model->get_reg_source ( $startTime, $endTime, $search_val );
		$other_num_buyer = $other_num_seller = 0;
		
		foreach ( $users as $val ) {
			$other_flag = true;
			if ($val ['reg_from_url']) {
				// 精确匹配
				foreach ( $urls as $k => $v ) {
					if ($other_flag == false) {
						break;
					}
					if ($v ['type'] == 2 && $other_flag) {
						if ($val ['reg_from_url'] == $v ['url']) {
							$other_flag = false;
							if ($val ['utype'] == 1) {
								$urls [$k] ['num_buyer'] += 1;
							} elseif ($val ['utype'] == 2) {
								$urls [$k] ['num_seller'] += 1;
							}
							break;
						}
					}
				}
				// 模糊匹配
				foreach ( $urls as $k => $v ) {
					if ($other_flag == false) {
						break;
					}
					if ($v ['type'] == 1 && $other_flag) {
						if (stristr ( $val ['reg_from_url'], $v ['url'] ) !== false) {
							$other_flag = false;
							if ($val ['utype'] == 1) {
								$urls [$k] ['num_buyer'] += 1;
							} elseif ($val ['utype'] == 2) {
								$urls [$k] ['num_seller'] += 1;
							}
							break;
						}
					}
				}
			}
			if ($other_flag) {
				if ($val ['utype'] == 1) {
					$other_num_buyer += 1;
				} elseif ($val ['utype'] == 2) {
					$other_num_seller += 1;
				}
			}
		}
		// 整理数据
	$data=array();
	$data [] = array ('其它','-',$other_num_seller,$other_num_buyer);
		foreach ( $urls as $k => $v ) {
			$data [] = array (
					$v ['name'],
					$v ['url'],
					$v ['num_seller'],
					$v ['num_buyer'],
			);
		}
		$title = '用户来源URL导出-'.date('Y-m-d H:i:s',$startTime).'-'.date('Y-m-d H:i:s',$endTime);
		$filename = $title . '.xls';
		$header = array (
				'用户来源',
				'来源地址',
				'注册商家',
				'注册买家'
		);
		array_unshift ( $data, $header );
		$this->data_export ( $data, $title, $filename );
	}
	
	/**
	 * 导出url列表
	 */
	public function export_url() {

		$startTime = intval( $this->get_post ( 'startTime' ) ) ? intval ( $this->get_post ( 'startTime' ) ) : strtotime ( date ( 'Y-m-d', time () ) );
		$endTime = intval ( $this->get_post ( 'endTime' ) ) ? intval ( $this->get_post ( 'endTime' ) ) : time ();
		$search_val =urldecode( trim( $this->get_post ( 'url' )));
		$type=intval($this->get_post ( 'type' ));
			if($search_val=='other'){
				//配置用户来源URL名称
				$urls= $this->get_source_data();
				$no_like=array();
				foreach ($urls as $k=>$v){
					 $no_like[]=$v['url']; 
				}
				$this->db->close();
				$otherusers = $this->user_model->get_reg_source ( $startTime, $endTime, '');
				$other_uids=$this->get_other_uids($otherusers, $urls);
				  if(count($other_uids['otheruids'])>50000 || count($other_uids['user_uids'])>50000){
					$this->error('当前导出数据太大，可能不能正常导出。建议增加筛选条件后重新尝试导出”');
				}
				$users = $this->user_model->get_reg_source_export ( $startTime, $endTime, '',$other_uids );
			}else{
	        	$users = $this->user_model->get_reg_source_export ( $startTime, $endTime, $search_val,array(),$type );
			}
			// 整理数据
			$data=array();
			$reg_source=array('1'=>'试客联盟','2'=>'互联支付','3'=>'众划算');
			foreach ( $users as $k => $v ) {
				$data [] = array (
						$v ['uid'],
						$v ['uname'],
						$v ['reg_from_url'],
						$v ['utype']==1?'买家':'商家',
						$reg_source[$v['reg_source']]=isset($reg_source[$v['reg_source']])?$reg_source[$v['reg_source']]:'未知',
				);
			}
			$name = trim( $this->get_post ( 'name' ));
			$title =$name. 'URL导出-'.date('Y-m-d H:i:s',$startTime).'-'.date('Y-m-d H:i:s',$endTime);
			$filename = $title . '.xls';
			$header = array (
					'用户ID',
					'用户名',
					'来源地址',
                    '用户类型',
					'注册来源'
			);
			array_unshift ( $data, $header );
			$this->data_export ( $data, $title, $filename );
	}
	
	/**
	 * 获取其它用户注册来源url的uid
	 * @param unknown $users
	 * @param unknown $urls
	 * @return multitype:unknown
	 */
	private function get_other_uids($users = array(), $urls = array()) {
		$otheruids =$user_uids= array ();
		foreach ( $users as $val ) {
			$other_flag = true;
			if ($val ['reg_from_url']) {
				// 精确匹配
				foreach ( $urls as $v ) {
					if ($other_flag == false) {
						break;
					}
					if ($v ['type'] == 2 && $other_flag) {
						if ($val ['reg_from_url'] == $v ['url']) {
							$user_uids [] = $val ['uid'];
							$other_flag = false;
						}
					}
				}
				// 模糊匹配
				foreach ( $urls as $v ) {
					if ($other_flag == false) {
						break;
					}
					if ($v ['type'] == 1 && $other_flag) {
						if (stristr ( $val ['reg_from_url'], $v ['url'] ) !== false) {
							$user_uids [] = $val ['uid'];
							$other_flag = false;
							break;
						}
					}
				}
			}
			if ($other_flag) {
				$otheruids [] = $val ['uid'];
			}
		}
		return array('otheruids'=>$otheruids,'user_uids'=>$user_uids);
	}
	
	/**
	 * 获取所有的来源
	 * 
	 * @author 杜嘉杰
	 * @version 2015年10月14日  上午10:45:39
	 */
	private function get_source_data()
	{
		$this->load->model('system_config_reg_source_model');
		$data = $this->system_config_reg_source_model->find_all();
		$data = $data ? $data : array();
		
		foreach ($data as $k=>$v){
			$data[$k]['num_buyer'] = 0;
			$data[$k]['num_seller'] = 0;
		}
		return $data;
	}
}