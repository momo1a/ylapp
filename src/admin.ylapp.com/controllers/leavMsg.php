<?php
/**
 * 留言问诊管理控制器
 * User: Administrator
 * Date: 2016/10/3 0003
 * Time: 下午 3:51
 */

class LeavMsg extends MY_Controller
{
    /**
     * @var null
     */
    protected  $_state = null;

    /**
     * @var null
     */
    protected $_isReply = null;

    public function __construct(){
        parent::__construct();
        $this->load->model('User_leaving_msg_model','leaving');
        $this->_state = array(
            '-1' => '全部',
            '0' => '未付款',
           // '1' => '已付款(待处理)',
            '2' => '已付款',
            '3' => '不通过',
            '4' => '完成',
            '5' => '已回答'

        );

        $this->_isReply = array(
            '-1'=>'全部',
            '0' => '否',
            '1' => '是'
        );

    }



    public function index(){
        $limit = 10;
        $keyword = addslashes(trim($this->input->get_post('keyword')));
        $state = intval($this->input->get_post('state'));
        $isReply = intval($this->input->get_post('isReply'));

        if(!isset($_GET['state'])){
            $state = -1;
            $_GET['state'] = -1;
        }

        if(!isset($_GET['isReply'])){
            $isReply = -1;
            $_GET['isReply'] = -1;
        }
        $total = $this->leaving->msgCount($keyword,$isReply,$state);
        $offset = intval($this->uri->segment(3));
        $list = $this->leaving->msgList($keyword,$isReply,$state,$limit,$offset,'*,YL_user_leaving_msg.id as aid,YL_user_illness_history.sex as asex,YL_user_illness_history.age as aage,YL_user_leaving_msg.askTime as atime,YL_user_leaving_msg.state as astatus,doc.nickname as dname,doc.phone as dphone,YL_user_leaving_msg.img as msgImg');
        if(!empty($list)){
            foreach($list as $key => $value){
                $list[$key]['msgImg'] = json_decode($value['msgImg'],true);
            }
        }
        $page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
        $page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
        $pager = $this->pager($total, $limit,$page_conf);
        $data['pager'] = $pager;
        $data['list'] = $list;
        $data['get'] = $_GET;
        $data['state'] = $this->_state;
        $data['isReply'] = $this->_isReply;
        $this->load->view('leaving/index',$data);
    }


    /**
     * 设置状态
     */
    public function stateSetting(){
        $oid = intval($this->input->get_post('oid'));
        $status = intval($this->input->get_post('status'));
        ($status == 3 || $status == 4) || exit('状态异常');
        $res = $this->leaving->settingStatus($oid,$status);
        if($res){
            $this->ajax_json(0,'操作成功');
        }else{
            $this->ajax_json(1,'系统错误');
        }
    }
}