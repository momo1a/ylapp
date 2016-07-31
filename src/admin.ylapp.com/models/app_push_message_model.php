<?php
/**
 * 消息推送管理model
 * @author 张桂赏
 * @version 2014-7-25
 */
class App_push_message_model extends Zhs_app_push_message_model
{
	/**
	 * 显示搜索信息
	 * @param string $search_key 搜索关键字
	 * @param int $start_time	  开始时间
	 * @param int $end_time		  结束时间
	 * @param int $size			  搜索抓取量
	 * @param int $offset	  	  分页偏移量
	 * @return array             显示搜索结果
 	 */
	public function search($search_key='',$start_time=0,$end_time=0,$size=10,$offset=0)
	{
		$this->db->select('*');
		$this->get_message_where($search_key, $start_time, $end_time);
		$data['list']=$this->db->limit($size,$offset)->get()->result_array(); 
		//查询记录条数		
		$this->db->select('*');
		$this->get_message_where($search_key, $start_time, $end_time);
		
		$data['count']=$this->db->count_all_results();
		return $data;
	}
	
	/**
	 * 判断搜索条件
	 * @param string $search_key
	 * @param int $start_time
	 * @param int $end_time
	 */
	private function get_message_where($search_key,$start_time,$end_time)
	{
		$this->db->from(self::$table_name);
		if($search_key!=='')
		{
			$this->db->like('title',$search_key);
		}
		if($start_time > 0 ){
			$this->db->where('dateline >=',$start_time);
		}
		if($end_time>0)
		{	 
			$this->db->where('dateline <=',$end_time);
		}
	}
	
	/**
	 * 添加推送信息
	 * @param string $title 	推送标题
	 * @param string $content 	推送内容
	 * @param int $client_type			平台号
	 * @param int $dateline		创建时间
	 * @param int $push_time	推送时间
	 * @return boolean
	 */
	public function add($title,$content,$client_type,$dateline,$push_time)
	{
		$android_state = self::STATE_PUSH_STATE_DISENABLE;
		$ios_state = self::STATE_PUSH_STATE_DISENABLE;
		
		switch ($client_type){
			case self::CLIENT_TYPE_ALL:
				$android_state = self::STATE_PUSH_STATE_WAIT;
				$ios_state = self::STATE_PUSH_STATE_WAIT;
				break;
			case self::CLIENT_TYPE_ANDROID:
				$android_state = self::STATE_PUSH_STATE_WAIT;
				break;
			case self::CLIENT_TYPE_IOS:
				$ios_state = self::STATE_PUSH_STATE_WAIT;
				break;
			default:show_error('未知推送类型');
				break;
		}
		
		$this->db->set('title',$title);
		$this->db->set('content',$content);
		$this->db->set('client_type',$client_type);
		$this->db->set('dateline',$dateline);
		$this->db->set('android_state', $android_state);
		$this->db->set('ios_state', $ios_state);
		$this->db->set('push_time',$push_time);
		$this->db->set('push_state', self::STATE_PUSH_STATE_WAIT);
		return $this->db->insert(self::$table_name);
	}
	
	/**
	 * 推送全部的消息
	 * @param unknown $id
	 * @return boolean
	 * @author 杜嘉杰
	 * @version 2014-9-29
	 */
	public function push_all($id){
		$push_msg = $this->app_push_message_model->find($id);
		if(!$push_msg){
			$this->error = array('errcode' => 'PUSH_NOT_FIND', 'errtxt' =>'推送消息不存在');
		}
		if($push_msg['push_state'] <> self::STATE_PUSH_STATE_WAIT){
			$this->error = array('errcode' => 'PUSH_STATE_UNUSUAL', 'errtxt' =>'消息已经推送');
			return FALSE;
		}
		
		// 推送消息提醒
		$ret = FALSE;
		$error = '';
		
		$id = $push_msg['id'];
		$content = $push_msg['content'];
		$time = $push_msg['push_time'];
		switch ($push_msg['client_type']){
			case self::CLIENT_TYPE_ALL;
				// 全部
				$error = NULL;
				$err_content = '';
				$android_ret = $this->push_all_android($push_msg);
				if ( ! $android_ret) {
					$err_content = '推送android失败，errcode:'.$this->error['errcode'].',errtxt:'.$this->error['errtxt'].'。';
				}
				$ios_ret = $this->push_all_ios($push_msg);
				if ( ! $ios_ret) {
					$err_content .= '推送ios失败，errcode:'.$this->error['errcode'].',errtxt:'.$this->error['errtxt'].'。';
				}
				if ($android_ret && $ios_ret) {
					$ret = TRUE;
				}else{
					$this->error = array('errcode'=>'PUSH_ALL_ERROR', 'errcode'=>$err_content);
				}
				break;
			case self::CLIENT_TYPE_ANDROID:
				// android
				$ret = $this->push_all_android($push_msg);
				break;
			case self::CLIENT_TYPE_IOS:
				// ios
				$ret = $this->push_all_ios($push_msg);
				break;
			default:
				$this->error('未知推送平台类型');
				break;
		}
		if ($ret) {
			$this->db->set('push_state', self::STATE_PUSH_STATE_TIMING)->where('id',$id)->update(self::$table_name);
		}
		return $ret;
	}
	
	/**
	 * 发送全部android客户端
	 * @param unknown $push_msg
	 * @return unknown
	 * @author 杜嘉杰
	 * @version 2014-9-10
	 */
	private function push_all_android($push_msg){
		if($push_msg['android_push_id']>0){
			//如果android_push_id>0表示已经发送过
			return TRUE;
		}
		
		$this->load->driver('app_push');
		$ret = $this->app_push->push_all_devices_android($push_msg['content'], $push_msg['push_time']);
		if($ret){
			$push_id =  $this->app_push->get_push_id();
			$this->db->set(array('android_state'=>self::STATE_PUSH_STATE_TIMING, 'android_push_id'=>$push_id))
				->where('id', $push_msg['id'])->update(self::$table_name);
		}else{
			$this->error = $this->app_push->error();
		}
		return $ret;
	}
	
	/**
	 * 发送全部ios客户端
	 * @param unknown $push_msg
	 * @return unknown
	 * @author 杜嘉杰
	 * @version 2014-9-10
	 */
	private function push_all_ios($push_msg){
		if($push_msg['ios_push_id']>0){
			//如果ios_push_id>0表示已经发送过
			return TRUE;
		}
		
		$this->load->driver('app_push');
		$ret = $this->app_push->push_all_devices_ios($push_msg['content'], $push_msg['push_time']);
		if($ret){
			$push_id =  $this->app_push->get_push_id();
			$this->db->set(array('ios_state'=>self::STATE_PUSH_STATE_TIMING, 'ios_push_id'=>$push_id))
				->where('id', $push_msg['id'])->update(self::$table_name);
		}else{
			$this->error = $this->app_push->error();
		}
		return $ret;
	}
	
	/**
	 * 刷新推送状态
	 */
	public function refresh_state(){
		// 获取从未推送过的消息
		$data = $this->db->from(self::$table_name)->or_where(array('android_state'=>self::STATE_PUSH_STATE_TIMING, 'ios_state'=>self::STATE_PUSH_STATE_TIMING))
			->get()->result_array();
		
		foreach ($data as $item){
			// android
			$android_push_id = isset($item['android_push_id']) ? $item['android_push_id'] : 0;
			$android_state = self::STATE_PUSH_STATE_TIMING;
			if($android_push_id>0 && isset($item['android_state']) && $item['android_state']==self::STATE_PUSH_STATE_TIMING){
				if($this->_check_push_success($android_push_id,  self::CLIENT_TYPE_ANDROID)){
					$android_state = self::STATE_PUSH_STATE_SUCCESS;
				}
			}
			
			// ios
			$ios_push_id = isset($item['ios_push_id']) ? $item['ios_push_id'] : 0;
			$ios_state = self::STATE_PUSH_STATE_TIMING;
			if($ios_push_id && isset($item['ios_state']) && $item['ios_state']==self::STATE_PUSH_STATE_TIMING){
				if($this->_check_push_success($ios_push_id, self::CLIENT_TYPE_IOS)){
					$ios_state = self::STATE_PUSH_STATE_SUCCESS;
				}
			}
			
			// 更新数据库状态
			$update = NULL;
			if($android_state == self::STATE_PUSH_STATE_SUCCESS){
				$update['android_state'] = self::STATE_PUSH_STATE_SUCCESS;
				$update['push_state'] = self::STATE_PUSH_STATE_SUCCESS;
			}
			if($ios_state == self::STATE_PUSH_STATE_SUCCESS){
				$update['ios_state'] = self::STATE_PUSH_STATE_SUCCESS;
				$update['push_state'] = self::STATE_PUSH_STATE_SUCCESS;
			}
			if($item['client_type'] == self::CLIENT_TYPE_ALL && $android_state == self::STATE_PUSH_STATE_SUCCESS && $ios_state == self::STATE_PUSH_STATE_SUCCESS){
				$update['push_state'] = self::STATE_PUSH_STATE_SUCCESS;
			}
			if($update){
				$this->db->set($update)->where('id',$item['id'])->update(self::$table_name);
			}
		}
		
		return TRUE;
	}
	
	/**
	 * 判断单个推送是否发送成功
	 * @param int $push_id:推送id
	 * @param int $client_type:客户端类型
	 * @return boolean
	 * @author 杜嘉杰
	 * @version 2014-9-29
	 */
	private function _check_push_success($push_id, $client_type){
		$this->load->driver('app_push');
		$ret = $this->app_push->query_push_status(array($push_id), $client_type);
		
		// 判断请求状态
		if( !isset($ret['ret_code']) || $ret['ret_code']<>0){
			return FALSE;
		}
		
		// 判断推送状态返回id与本次请求的相同
		if( !isset($ret['result']['list'][0]['push_id']) || $ret['result']['list'][0]['push_id']<>$push_id){
			return FALSE;
		}
		
		// 判断推送状态
		if( !isset($ret['result']['list'][0]['status']) || $ret['result']['list'][0]['status']<> 2){
			return FALSE;
		}
		return TRUE;
	}
}