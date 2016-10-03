<?php
/**
 * 在线问诊控制器
 * User: Administrator
 * Date: 2016/10/2 0002
 * Time: 下午 10:01
 */

class TelOnline extends MY_Controller
{

    /**
     * 状态
     * @var null
     */
    protected $_state = null;

    public function __construct(){
        parent::__construct();
        $this->load->model('User_phone_diagnosis_model','online');
        $this->_state = array(
            '-1' => '全部',
            '0' => '未支付',
            '1' => '已支付(待处理)',
            '2' => '确认沟通时间',
            '3' => '完成',
            '4' => '失败',
            '5' => '用户取消'
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
        $total = $this->online->countAppoint($keyword,$state);
        $offset = intval($this->uri->segment(3));
        $list = $this->online->getAppointList($keyword,$limit,$offset,$state,'*,YL_user_phone_diagnosis.id as aid,YL_user_illness_history.sex as asex,YL_user_illness_history.age as aage,YL_user_phone_diagnosis.askTime as atime,YL_user_phone_diagnosis.state as astatus');
        $page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
        $page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
        $pager = $this->pager($total, $limit,$page_conf);
        $data['pager'] = $pager;
        $data['list'] = $list;
        $data['get'] = $_GET;
        $data['state'] = $this->_state;
        $this->load->view('online/index',$data);
    }


    /**
     * 设置状态
     */
    public function stateSetting(){
        $oid = intval($this->input->get_post('oid'));
        $status = intval($this->input->get_post('status'));
        ($status == 2 || $status == 3 || $status == 4) || exit('状态异常');
        $res = $this->online->settingStatus($oid,$status);
        if($res){
            $this->ajax_json(0,'操作成功');
        }else{
            $this->ajax_json(1,'系统错误');
        }
    }

    /**
     * 获取期望沟通时间填充表单
     */
    public function getDetail(){
        $oid = intval($this->input->get_post('oid'));
        $res = $this->online->getDetail($oid);
        $res['adate'] = date('Y-m-d',$res['hopeCalldate']);
        $res['atime'] = date('H:i:s',$res['hopeCalldate']);
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

        $res = $this->online->updateAppointTime($oid,$unixTime);

        if($res){
            $this->ajax_json(0,'操作成功');
        }else{
            $this->ajax_json(1,'系统错误');
        }

    }
}