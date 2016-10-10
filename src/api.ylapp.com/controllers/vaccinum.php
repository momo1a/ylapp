<?php
/**
 * 疫苗接种控制器
 * User: momo1a@qq.com
 * Date: 2016/8/13 0013
 * Time: 下午 12:03
 */

class Vaccinum extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('Vaccinum_model','vaccinum');
        $this->load->model('Money_model','money');
        $this->load->model('Order_model','order');
        $this->load->model('User_model','user');
    }

    /**
     * 疫苗接种列表页面
     */

    public function vaccinumList(){
        $keyword = trim(addslashes($this->input->get_post('keyword')));
        $type = intval($this->input->get_post('vacciType'));
        $limit = intval($this->input->get_post('limit'));
        $offset = intval($this->input->get_post('offset'));
        $limit = $limit == 0 ? 10 : $limit;
        $res = $this->vaccinum->getList('id,thumbnail,name,price',$type,$keyword,$limit,$offset);
        $this->response($this->responseDataFormat(0,'请求成功',array('vaccinumList'=>$res,'imgServer'=>$this->getImgServer())));
    }

    /**
     * 疫苗接种详情
     */

    public function vaccinumDetail(){
        $vaccinumId = intval($this->input->get_post('vaccinumId'));
        $res = $this->vaccinum->getvaccinumDetail($vaccinumId,'id,name,detail,price');
        $res[0]['detail'] =  htmlspecialchars(str_replace('"','\'',$res[0]['detail']));
        $this->response($this->responseDataFormat(0,'请求成功',array($res)));
    }


    /**
     * 支付页面
     */
    public function payView(){
        $this->checkUserLogin();
        $vaccinumId = intval($this->input->get_post('vaccinumId'));
        $vaccinumPrice = $this->vaccinum->getvaccinumDetail($vaccinumId,'price');


        /*添加订单*/
        $userName =$this->user->getUserInfoByUid(self::$currentUid,'nickname');
        $userTel =$this->user->getUserInfoByUid(self::$currentUid,'phone');
        $birthday =$this->user->getUserInfoByUid(self::$currentUid,'birthday');
        $vaccinumTitle = $this->vaccinum->getVaccinumDetail($vaccinumId,'name');
        $data = array(
            'buyerId'=>self::$currentUid,
            'buyerName'=>$userName,
            'buyerTel'=>$userTel,
            'buyerBrithday'=>$birthday,
            'packageId'=>$vaccinumId,
            'packageTitle'=>$vaccinumTitle[0]['name'],
            'price'=>floatval($vaccinumPrice[0]['price']),
            'type'=>1,
            'dateline'=>time(),
            'status'=>1
        );
        $orderId = $this->order->addOrder($data);
        $this->response($this->responseDataFormat(0,'请求成功',array('price'=>$vaccinumPrice,'orderId'=>$orderId)));
    }

    /**
     * 支付页面第二步
     */
    public function payViewStepS(){
        $this->checkUserLogin();
        $oid = intval($this->input->get_post('orderId'));
        $money = $this->money->getUserMoney(self::$currentUid);
        $orderInfo = $this->order->getOrderById($oid);
        $this->response($this->responseDataFormat(0,'请求成功',array('orderInfo'=>$orderInfo,'remainAmount'=>$money)));
    }
}