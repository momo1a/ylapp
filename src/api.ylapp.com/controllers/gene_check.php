<?php
/**
 * 基因检测控制器
 * User: momo1a@qq.com
 * Date: 2016/8/13 0013
 * Time: 下午 12:03
 */

class Gene_check extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('Gene_check_model','gene');
        $this->load->model('Money_model','money');
        $this->load->model('Order_model','order');
        $this->load->model('User_model','user');
    }

    /**
     * 基因检测列表页面
     */

    public function geneCheckList(){
        $limit = intval($this->input->get_post('limit'));
        $offset = intval($this->input->get_post('offset'));
        $limit = $limit == 0 ? 10 : $limit;
        $res = $this->gene->getList('id,thumbnail,name,price',$limit,$offset);
        $this->response($this->responseDataFormat(0,'请求成功',array('GeneList'=>$res,'imgServer'=>$this->getImgServer())));
    }

    /**
     * 基因检测详情
     */

    public function geneCheckDetail(){
        $geneId = intval($this->input->get_post('geneId'));
        $res = $this->gene->getGeneDetail($geneId,'id,name,detail,price');
        $res[0]['detail'] =  str_replace('"','\'',$res[0]['detail']);
        $res[0]['detail'] = trim($res[0]['detail']);
        $res[0]['detail'] = ltrim($res[0]['detail'],'<p>');
        $res[0]['detail'] = rtrim($res[0]['detail'],'</p>');
        $this->response($this->responseDataFormat(0,'请求成功',array($res)));
    }


    /**
     * 支付页面
     */
    public function payView(){
        $this->checkUserLogin();
        $geneId = intval($this->input->get_post('geneId'));
        $genePrice = $this->gene->getGeneDetail($geneId,'price');

        /*添加订单*/
        $userName =$this->user->getUserInfoByUid(self::$currentUid,'nickname');
        $userTel =$this->user->getUserInfoByUid(self::$currentUid,'phone');
        $birthday =$this->user->getUserInfoByUid(self::$currentUid,'birthday');
        $geneTitle = $this->gene->getGeneDetail($geneId,'name');
        $data = array(
            'buyerId'=>self::$currentUid,
            'buyerName'=>$userName,
            'buyerTel'=>$userTel,
            'buyerBrithday'=>$birthday,
            'packageId'=>$geneId,
            'packageTitle'=>$geneTitle[0]['name'],
            'price'=>floatval($genePrice[0]['price']),
            'type'=>2,
            'dateline'=>time(),
            'status'=>1
        );
        $orderId = $this->order->addOrder($data);
        $this->response($this->responseDataFormat(0,'请求成功',array('price'=>$genePrice,'orderId'=>$orderId)));
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