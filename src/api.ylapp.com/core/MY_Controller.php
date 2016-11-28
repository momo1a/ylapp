<?php

require COMPATH."core/YL_Controller.php";

class MY_Controller extends CI_Controller
{

    /**
     * 用户类型
     * @var int
     */
    protected static $_TYPE_USER = 1;

    /**
     * 医生类型
     * @var int
     */
    protected static $_TYPE_DOCTOR = 2;


    /**
     * 接收客户端发送的私有token解码
     * @var null
     */
    protected static $privateTokenDecode = null;

    protected  static $privateToken = null;


    /**
     * 当前用户uid
     * @var
     */
    protected static $currentUid;

    /**
     * 当前用户手机号
     * @var
     */
    protected static $currentUserMobile;
    /**
     * 当前用户类型
     * @var
     */
    protected static $currentUserType;

    /**
     * 当前用户最后登录记录时间
     * @var
     */
    protected static $currentUserLastLoginTime;


	public function __construct(){
		parent::__construct();
        session_start();
        if(!preg_match('/.?test.?/i',strtolower($this->router->class))){
            $this->checkToken();  //检测通讯token
        }
        $this->load->library('Crypt',array('key'=>KEY_APP_SERVER,'iv'=>'0x12, 0x34, 0x56, 0x78, 0x90, 0xAB, 0xCD, 0xEF'),'crypt');    // 加密类库
        $this->load->model('Money_model','money');
        $this->load->model('Take_cash_model','cash');
        $this->load->model('Common_trade_log_model','trade_log');
        $this->load->model('News_collections_model','collection');
        $this->load->model('User_model','user');
        $this->load->library("UeMsg",null,'sms');  // 加载短信类库
        $this->load->library('Cache_memcached',null,'cache');  // 加载缓存类库
        self::$privateToken = str_replace('\\','',$this->input->get_post('privateToken'));
        $cacheData = $this->cache->get(md5(self::$privateToken));
        self::$privateTokenDecode = $this->crypt->decode($cacheData);
        $userInfoArr = explode('-',self::$privateTokenDecode);
        self::$currentUid = $userInfoArr[0];
        self::$currentUserMobile = $userInfoArr[1];
        self::$currentUserLastLoginTime = $userInfoArr[2];
        self::$currentUserType = $userInfoArr[3];
	}


    /**
     * @param array $content
     *         code   响应码
     *         msg    消息描述
     *         data   数据
     * @param string $content_type 响应头
     */
    protected function response($content = array('code'=>1002,'msg'=>'ERR_PARAMETER','data'=>array()), $content_type = 'text/html;charset=utf-8'){
        $content_type = trim($content_type) != '' ? trim($content_type) : 'text/html;charset=utf-8';
        header('Content-Type: '.$content_type);
        exit(json_encode($content));
    }

    /**
     * 检查通讯token
     */
    protected function checkToken(){
        $token = trim($this->input->get_post('token'));
        if($token != strtoupper(md5(KEY_APP_SERVER))){
            exit(json_encode(array('code'=>1001,'msg'=>'ERR_TOKEN_DIFFER')));  //通信TOKEN不一致
        }
    }

    /**
     * 加密函数
     * @param $string
     */
    protected function encryption($string){
        return md5(sha1($string));
    }

    /**
     * @param $code
     * @param $msg
     * @param $data
     */
    protected function responseDataFormat($code,$msg,$data){
        $responseData['code'] = $code;
        $responseData['msg'] = $msg;
        $responseData['data'] = $data;
        return $responseData;
    }

    /**
     * 获取远程地址
     * @return int
     */
    protected function getRemoteAddr(){
        return ip2long($this->input->server('REMOTE_ADDR'));
    }

    /**
     * 获取图片服务器
     */

    protected function getImgServer(){
        $imgServers = $this->config->item('image_servers');
        $imgServer = $imgServers[0] or $this->response($this->responseDataFormat(1,'图片服务器未配置',array()));
        return $imgServer;
    }


    /**
     * 检查用户登陆
     */
    protected function checkUserLogin(){
        if(!self::$privateToken || !is_numeric(self::$currentUid) || self::$currentUid == 0 ){
            $this->response($this->responseDataFormat('500','未登录',array()));
        }
    }


    /**
     * 保存用户 医生 日志
     * @param $userId
     * @param $docId
     * @param $type
     * @param $comState
     * @param $description
     */
    protected function userDoctorLogSave($userId,$docId,$type,$comState,$description){
        $this->load->model('Common_user_doctor_log_model','udlog');
        $data = array(
            'userId'=>$userId,
            'doctorId'=>$docId,
            'comType'=>$type,
            'comState'=>$comState,
            'description'=>$description,
            'dateline'=>time()
        );
        $this->udlog->saveLog($data);
    }


    /**
     * 当前用户钱包
     */
    protected function myMoney(){
        $money = $this->money->getUserMoney(self::$currentUid);
        $money = $money ? $money : 0;
        $this->response($this->responseDataFormat(0,'请求成功',array($money)));
    }

    /**
     * 提现页面
     */

    protected function cashView(){
        $money = $this->money->getUserMoney(self::$currentUid);
        $money = $money ? $money : 0;
        $this->response($this->responseDataFormat(0,'请求成功',array($money)));
    }

    /**
     * 提现申请提交
     */

    protected function takeCashAction($userType){
        $bank = addslashes(trim($this->input->get_post('bank')));
        $cardNum = addslashes(trim($this->input->get_post('cardNum')));
        $address = addslashes(trim($this->input->get_post('address')));
        $realName = addslashes(trim($this->input->get_post('realName')));
        $identity = addslashes(trim($this->input->get_post('identity')));
        $amount = floatval($this->input->get_post('amount'));
        $userType  = $userType;
        $money = $this->money->getUserMoney(self::$currentUid);
        $money = $money ? $money[0]['amount'] : 0;
        if(!is_numeric($cardNum)){
            $this->response($this->responseDataFormat(1,'请填写正确银行卡号',array()));
        }
        if(!is_numeric($identity) || strlen($identity) != 18){
            $this->response($this->responseDataFormat(2,'请填写正确身份证号',array()));
        }
        if($amount > $money){
            $this->response($this->responseDataFormat(3,'提现金额大于用户余额',array()));
        }
        $data = array(
            'uid'=>self::$currentUid,
            'bank'=>$bank,
            'cardNum'=>$cardNum,
            'address'=>$address,
            'realName'=>$realName,
            'identity'=>$identity,
            'amount'=>$amount,
            'userType'=>$userType,
            'dateline'=>time()
        );
        $tradeData = array(
            'uid'=>self::$currentUid,
            'userType'=>$userType,
            'tradeVolume'=>$amount,
            'tradeDesc'=>'提现',
            'tradeType'=>0,
            'dateline'=>time()
        );
        $this->db->trans_begin();
        $this->cash->addCash($data);
        $this->trade_log->saveLog($tradeData);
        $this->money->updateUserMoney(self::$currentUid,$amount);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->response($this->responseDataFormat(-1,'系统错误',array()));
        } else {
            $this->db->trans_commit();
            $this->response($this->responseDataFormat(0,'提交申请成功',array()));
        }
    }

    /**
     * 交易记录视图
     */
    protected function tradeLogView(){
        $res = $this->trade_log->getListByUid(self::$currentUid,'tradeDesc,FROM_UNIXTIME(dateline) AS tradeTime,tradeVolume,(case when tradeType=0 then "提现" when tradeType=1 then "充值" when tradeType=2 then "疫苗费用" when tradeType=3 then "基因费用" when tradeType=4 then "电话问诊" when tradeType=5 then "在线问答" when tradeType=6 then "预约挂号" tradeType=7 then "电话问诊退款" when tradeType=8 then "预约挂号退款" end)as tradeType');
        $this->response($this->responseDataFormat(0,'请求成功',array($res)));
    }


    /**
     * 我的收藏
     */
    protected function collectionList(){
        $limit = intval($this->input->get_post('limit'));
        $limit = $limit == 0 ? 10 : $limit;
        $offset = intval($this->input->get_post('offset'));
        $select = 'YL_news.nid,YL_news_collections.id as collId,YL_news.thumbnail,YL_news.title,YL_news.content,FROM_UNIXTIME(YL_news.createTime) as dateline';
        $res = $this->collection->myCollections(self::$currentUid,$select,$limit,$offset);
        $this->response($this->responseDataFormat(0,'请求成功',array('collections'=>$res,'imgServer'=>$this->getImgServer())));
    }


    /**
     * 密码修改
     */

    protected function updateMyPwd(){
        $oldPwd = $this->encryption(trim($this->input->get_post('oldPwd')));
        $newPwd = trim($this->input->get_post('newPwd'));
        $reNewPwd = trim($this->input->get_post('reNewPwd'));
        $password =$this->user->getUserInfoByUid(self::$currentUid,'password');
        if($oldPwd != $password){
            $this->response($this->responseDataFormat(1,'旧密码不正确',array()));
        }
        if(strlen($newPwd) < 6){
            $this->response($this->responseDataFormat(2,'密码不得小于6位',array()));
        }
        if(is_numeric($newPwd)){
            $this->response($this->responseDataFormat(3,'密码不得是纯数字',array()));
        }
        if($newPwd != $reNewPwd){
            $this->response($this->responseDataFormat(4,'第一次密码跟第二次密码不一致',array()));
        }
        $newPwd = $this->encryption($newPwd);
        if($newPwd == $password){
            $this->response($this->responseDataFormat(5,'新密码和旧密码一样未作修改',array()));
        }
        $data = array('password'=>$newPwd);
        $res = $this->user->saveUserDetail(self::$currentUid,$data);
        if($res){
            $this->response($this->responseDataFormat(0,'修改成功',array()));
        }else{
            $this->response($this->responseDataFormat(-1,'系统错误',array()));
        }
    }

    /**
     * 提交意见反馈
     */
    protected function commitFeedback(){
        $content = addslashes($this->input->get_post('content'));
        if(mb_strlen($content) > 300 || mb_strlen($content) < 6){
            $this->response($this->responseDataFormat(1,'字数不能小于6大于300',array()));
        }
        $this->load->model('Feedback_model','feedback');
        $data = array(
            'uid'=>self::$currentUid,
            'userType'=>1,
            'content'=>$content,
            'dateline'=>time()
        );
        $res = $this->feedback->addFeedback($data);
        if($res){
            $this->response($this->responseDataFormat(0,'请求成功',array()));
        }else{
            $this->response($this->responseDataFormat(-1,'系统错误',array()));
        }
    }

}