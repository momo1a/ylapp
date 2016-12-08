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
        if(!empty($keyword) || $state!= 0 || $type != 0){
            $offset = 0;
        }
        $list = $this->order->orderList($limit,$offset,$keyword,$state,$type,'*,YL_order.status as orderStatus');
        $page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
        $page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
        $pager = $this->pager($total, $limit,$page_conf);
        $data['pager'] = $pager;
        $data['list'] = $list;
        $data['type'] = $this->_type;
        $data['state'] = $this->_state;
        $data['get'] = $_GET;
        if($this->input->get_post('doexport') == 'yes'){
            $this->exportData();
        }
        $this->load->view('order/index',$data);
    }


    /**
     * 导出数据
     */
    protected function exportData(){
        header("Content-type:application/vnd.ms-excel");
        $filename = '订单数据('.date("Y-m-d-H:i:s", time()).').xls';
        $keyword = addslashes(trim($this->input->get_post('keyword')));
        $type = intval($_GET['type']);
        $state = intval($_GET['state']);
        /*默认可以导出最新的2000条数据*/
        $data = $this->order->orderList(2000,0,$keyword,$state,$type,'*,YL_order.status as orderStatus');
        $sex = array('1' => '男' ,'2'=>'女');
        $header = array(
            '订单编号',
            '购买人',
            '电话号码',
            '性别',
            '出生日期',
            '下单时间',
            '套餐名称',
            '价格',
            '类别',
            '当前状态',
        );
        $rows = array();
        if(!empty($data)) {
            foreach ($data as $k => $v) {
                $rows[$k]['oid'] = $v['oid'];
                $rows[$k]['buyerName'] = $v['buyerName'];
                $rows[$k]['buyerTel'] = strval($v['buyerTel']);
                $rows[$k]['sex'] = $sex[$v['sex']];
                $rows[$k]['buyerBrithday'] = date('Y-m-d',$v['buyerBrithday']);;
                $rows[$k]['dateline'] = date('Y-m-d H:i:s',$v['dateline']);;
                $rows[$k]['packageTitle'] = $v['packageTitle'];
                $rows[$k]['price'] = $v['price'];
                $rows[$k]['type'] = $this->_type[$v['type']];;
                $rows[$k]['orderStatus'] = $this->_state[$v['orderStatus']];;
            }
        }
        array_unshift($rows, $header);
        $this->data_export($rows, $filename);
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