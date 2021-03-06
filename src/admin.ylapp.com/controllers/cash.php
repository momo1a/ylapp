<?php
/**
 * 提现管理控制器
 * User: momo1a@qq.com
 * Date: 2016/9/29 0029
 * Time: 下午 10:24
 *
 *
 */

class Cash extends MY_Controller
{

    /**
     * 用户类型
     * @var null
     */
    protected $_userType =  null;


    /**
     * 提现状态
     * @var null
     */
    protected $_status = null;



    public function __construct(){
        parent::__construct();
        $this->load->model('Take_cash_model','cash');
        $this->_userType = array(
            0 => '全部',
            1 => '用户端',
            2 => '医生端',
        );

        $this->_status = array(
            -1 => '全部',
            0 => '待处理',
            1 => '通过',
            2 => '驳回'
        );
    }

    public function index(){
        $limit = 10;
        if(!isset($_GET['state'])){
            $_GET['state'] = -1;
        }
        $keyword = addslashes(trim($this->input->get_post('keyword')));
        $userType = intval($this->input->get_post('userType'));
        $state = intval($_GET['state']);
        $total = $this->cash->cashCount($keyword,$userType,$state);
        $offset = intval($this->uri->segment(3));
        if(!empty($keyword)){
            $offset = 0;
        }
        $list = $this->cash->cashList($keyword,$userType,$state,$limit,$offset);
        $page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
        $page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
        $pager = $this->pager($total, $limit,$page_conf);
        $data['pager'] = $pager;
        $data['list'] = $list;
        $data['userType'] = $this->_userType;
        $data['state'] = $this->_status;
        $data['get'] = $_GET;
        if($this->input->get_post('doexport') == 'yes'){
            $this->exportData();
        }
        $this->load->view('cash/index',$data);
    }


    /**
     * 设置状态
     */
    public function stateSetting(){
        $tid = intval($this->input->get_post('tid'));
        $status = intval($this->input->get_post('status'));
        $res = $this->cash->settingStatus($tid,$status);
        if($res){
            $this->ajax_json(0,'操作成功');
        }else{
            $this->ajax_json(1,'系统错误');
        }
    }


    /**
     * 导出数据
     */
    protected function exportData(){
        header("Content-type:application/vnd.ms-excel");
        $filename = '提现数据('.date("Y-m-d-H:i:s", time()).').xls';
        $keyword = $this->input->get_post('keyword');
        $userType = intval($this->input->get_post('userType'));
        $state = intval($this->input->get_post('state'));
        /*默认可以导出最新的2000条数据*/
        $data = $this->cash->cashList($keyword,$userType,$state,2000);
        $states = array('0' => '待处理' , '1' => '通过' ,'2'=>'驳回');
        $uType = array('1'=>'用户端','2'=>'医生端');
        $header = array(
            '编号',
            '姓名',
            '身份证号',
            '提现银行',
            '提现账户',
            '开户地区',
            '提现金额',
            '申请时间',
            '类型',
            '当前状态',
        );
        $rows = array();
        if(!empty($data)) {
            foreach ($data as $k => $v) {
                $rows[$k]['id'] = $v['id'];
                $rows[$k]['realName'] = $v['realName'];
                $rows[$k]['identity'] = strval($v['identity']);
                $rows[$k]['bank'] = $v['bank'];
                $rows[$k]['cardNum'] = strval($v['cardNum']);
                $rows[$k]['address'] = $v['address'];
                $rows[$k]['amount'] = $v['amount'];
                $rows[$k]['dateline'] = date('Y-m-d H:i:s', $v['dateline']);
                $rows[$k]['userType'] = $uType[$v['userType']];
                $rows[$k]['status'] = $states[$v['status']];
            }
        }
        array_unshift($rows, $header);
        $this->data_export($rows, $filename);
    }

}