<?php
/**
 * 订单管理控制器
 * User: momo1a@qq.com
 * Date: 2016/9/30
 * Time: 17:08
 */

class Order extends MY_Controller
{
    /**
     * 类型
     * @var null
     */
    protected  $_type = null;

    /**
     * 状态
     * @var null
     */
    protected  $_state = null;


    public function __construct(){
        parent::__construct();
        $this->load->model('Order_model','order');

        $this->_state = array(
            0=>'全部',
            1=>'待支付',
            2=>'已支付',
            3=>'待处理',
            4=>'已通知',
            5=>'完成'
        );

        $this->_type = array(
            0 => '全部',
            1 => '疫苗接种',
            2 => '基因检测'
        );
    }

    public function index(){
        $limit = 10;
        $keyword = addslashes(trim($this->input->get_post('keyword')));
        $type = intval($_GET['type']);
        $state = intval($_GET['state']);
        $total = $this->order->orderCount($keyword,$state,$type);
        $offset = intval($this->uri->segment(3));
        $list = $this->order->orderList($limit,$offset,$keyword,$state,$type,'*,YL_order.status as orderStatus');
        $page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
        $page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
        $pager = $this->pager($total, $limit,$page_conf);
        $data['pager'] = $pager;
        $data['list'] = $list;
        $data['type'] = $this->_type;
        $data['state'] = $this->_state;
        $data['get'] = $_GET;
        $this->load->view('order/index',$data);
    }


    /**
     * 设置状态
     */
    public function stateSetting(){
        $oid = intval($this->input->get_post('oid'));
        $status = intval($this->input->get_post('status'));
        ($status == 4 || $status == 5) || exit('状态异常');
        $res = $this->order->settingStatus($oid,$status);
        if($res){
            $this->ajax_json(0,'操作成功');
        }else{
            $this->ajax_json(1,'系统错误');
        }
    }
}