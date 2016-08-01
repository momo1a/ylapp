<?php
/**
 * 零点定时生效控制器
 * （注：凌晨零点执行）
 * @author 杨积广 2014 3 31
 * @property system_config_model $config_model
 */
class Zero_tasktimer extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		// 添加公共类库的加载路径
		$this->load->add_package_path(COMPATH . '/');
		$this->load->model('system_config_model', 'config_model');
		$this->load->model('goods_search_keyword_model');

	}
	/**
	 * 触发生效函数
	 */
	public function index(){
        // 最新上线_设置分场定时生效函数
        $this->parvial_field();	
        // 搜索下单
        $this->search_buy();
		// 更新排名和排序变化
		$this->update_sort();		
		
		// 更新app站点配置
		$this->biuld_app_config();
	}	
	/**
	 * 更新最新排名和排名变化
	 */
	public function update_sort()
	{
		$sort_msg=$this->db->select('id,sort')->from(Goods_search_keyword_model::$table_name)->order_by('search_num desc')->get()->result_array();
		foreach ($sort_msg as $k=>$v)
		{
			$id=$v['id'];
			$old_sort=$v['sort'];
			$new_sort=$k+1;
			$sort_change=$old_sort-$new_sort;
			$data=array();
			$data[]=array(
				'id'=>$id,	
				'sort'=>$new_sort,
				'sort_change'=>$sort_change		
					);
			$this->db->update_batch('goods_search_keyword',$data,'id');
		}	
	}
	
	/**
	 * 最新上线_设置分场定时生效
	 * Enter description here ...
	 */
	public function parvial_field(){
		$old_parvial_field=$this->db->select('value')->from('system_config')->where('key','goods_new_parvial_field_not')->get()->row_array();
		if($old_parvial_field){
			$rs=$this->config_model->save('goods_new_parvial_field',$old_parvial_field['value'],'最新上线分场场次时间');
			if($rs){
				$this->_build_cache();
				$this->log('最新上线_设置分场定时生效成功！', array_merge($_GET, $_POST));
			}else{
				$this->log('最新上线_设置分场定时生效失败！', array_merge($_GET, $_POST));
			}
		}else{
			$this->log('最新上线_设置分场定时生效失败！', array_merge($_GET, $_POST));
		}
	}
	
  /**
   * 搜索下单定时生效
   */
  public function search_buy() {
      // 获取搜索下单预更新配置
    $conf_search_buy = $this->config_model->get( array( 'not_search_buy_min_price', 'not_search_buy_min_paid_guaranty', 'not_search_buy_category_pids' ) );
    $temp = array();
    foreach ($conf_search_buy as $value) {
        $temp[$value['key']] = $value['value'];
    }
    
    // 将预更新值存入配置表已更新段
        $rows = array( );
        $rows[] = array('key'=>'search_buy_min_price','value'=>$temp['not_search_buy_min_price'],'remark'=>'已生效的活动网购价（搜索下单）');
        $rows[] = array('key'=>'search_buy_min_paid_guaranty','value'=>$temp['not_search_buy_min_paid_guaranty'],'remark'=>'已生效的活动担保金（搜索下单）');
        $rows[] = array('key'=>'search_buy_category_pids','value'=>$temp['not_search_buy_category_pids'],'remark'=>'已生效的活动的类目编号（搜索下单）');
        $rs = $this->config_model->save_all($rows);
        
        if($rs){
            $this->_build_cache();
            $this->log('已生效的搜索下单配置-更新成功', array_merge($_GET, $_POST));
            echo 'true';
        }else{
            $this->log('已生效的搜索下单配置-更新失败', array_merge($_GET, $_POST));
            echo 'false';
        }
  }
	
	/**
	 * 生成配置缓存文件
	 */
	private function _build_cache()
	{
		$this->load->library('YL_setting');
		$this->YL_setting->build_cache();
	}
	/**
	 * 记录操作日志
	 * @param string $content
	 * @param string $param
	 */
	protected function log($content = '', $param = '')
	{
		$this->load->model('system_admin_log_model');
		if('' === $param){
			$param = array_merge($_GET, $_POST);
		}
		$this->system_admin_log_model->save('0', '系统自动', $content, $param);
	}
	
	/**
	 * 更新app站点的配置
	 * 
	 * @author 杜嘉杰
	 * @version 2015年5月19日 上午11:39:28
	 */
	public function biuld_app_config()
	{
		$this->load->library('YL_setting');
		$this->YL_setting->build_cache();
	}

}

?>