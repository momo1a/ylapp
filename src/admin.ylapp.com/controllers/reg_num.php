<?php
/**
 * 预约挂号控制器
 * User: momo1a@qq.com
 * Date: 2016/10/1 0001
 * Time: 下午 8:35
 */

class Reg_num extends MY_Controller
{
    /**
     * 状态
     * @var null
     */
    protected $_state = null;

    public function __construct(){
        parent::__construct();
        $this->load->model('User_reg_num_model','reg');
        $this->_state = array(
            '-1' => '全部',
            '0' => '未支付',
            '2' => '已支付(待处理)',
            '3' => '预约成功',
            '4' => '预约失败',
            '5' => '完成',
            '6' => '用户取消'
        );
    }

    public function index(){
        $limit = 10;
        $keyword = addslashes(trim($this->input->get_post('keyword')));
        $state = intval($this->input->get_post('state'));
        if(!isset($_GET['state'])){
            $state = -1;
            $_GET['state'] = -1;
        }
        $total = $this->reg->countAppoint($keyword,$state);
        $offset = intval($this->uri->segment(3));
        if(!empty($keyword) || $state != -1){
            $offset = 0;
        }
        $list = $this->reg->getAppointList($keyword,$limit,$offset,$state,'*,YL_user_reg_num.id as aid,YL_user_reg_num.sex as asex,YL_user_reg_num.dateline as atime,YL_user_reg_num.status as astatus');
        $page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
        $page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
        $pager = $this->pager($total, $limit,$page_conf);
        $data['pager'] = $pager;
        $data['list'] = $list;
        $data['get'] = $_GET;
        $data['state'] = $this->_state;
        $this->load->view('reg/index',$data);
    }



    /**
     * 设置状态
     */
    public function stateSetting(){
        $oid = intval($this->input->get_post('oid'));
        $status = intval($this->input->get_post('status'));
        ($status == 3 || $status == 4 || $status == 5) || exit('状态异常');
        $res = $this->reg->settingStatus($oid,$status);
        if($res){
            $this->ajax_json(0,'操作成功');
        }else{
            $this->ajax_json(1,'系统错误');
        }
    }

    /**
     * 获取详情
     */

    public function getDetail(){
        $oid = intval($this->input->get_post('oid'));
        $res = $this->reg->getDetail($oid);
        $res['adate'] = date('Y-m-d',$res['appointTime']);
        $res['atime'] = date('H:i:s',$res['appointTime']);
        $this->ajax_json(0,'请求成功',$res);
    }

    /**
     * 修改预约时间
     */
    public function updateDate(){
        $oid = intval($this->input->get_post('oid'));
        $atime = $this->input->get_post('atime');
        $adate = $this->input->get_post('adate');
        if(strpos($atime,'AM') && substr($atime,0,2) == 12) {
            $atime = substr(str_replace(substr($atime,0,2),'00',$atime),0,5);
        }
        if(strpos($atime,'PM') && intval(substr($atime,0,2)) < 12){
            $pmTime = intval(substr($atime,0,2)) + 12;
            $atime = substr(str_replace(substr($atime,0,2),$pmTime,$atime),0,5);
        }
        $unixTime = strtotime($adate.' '.$atime);

        $res = $this->reg->updateAppointTime($oid,$unixTime);

        if($res){
            $this->ajax_json(0,'操作成功');
        }else{
            $this->ajax_json(1,'系统错误');
        }

    }
}