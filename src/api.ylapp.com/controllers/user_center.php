<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 用户个人中心
 * User: momo1a@qq.com
 * Date: 2016/8/15
 * Time: 16:48
 */

class User_center extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->checkUserLogin();   // 检查用户登录
        $this->load->model('User_model','user');
        $this->load->model('Money_model','money');
        $this->load->model('Take_cash_model','cash');
        $this->load->model('Common_trade_log_model','trade_log');
        $this->load->model('User_phone_diagnosis_model','online_ask');
        $this->load->model('Doctor_evaluate_model','evaluate');
        $this->load->model('User_reg_num_model','appoint');
        $this->load->model('User_leaving_msg_model','leaving_msg');
        $this->load->model('Doctor_reply_model','reply');
        $this->load->model('Order_model','order');
        $this->load->model('Post_model','post');
        $this->load->model('Post_comment_model','post_comment');
        $this->load->model('News_collections_model','collection');
    }


    /**
     * 用户个人中心首页
     */
    public function userCenterIndex(){
        $res = $this->user->getUserByUid(self::$currentUid,'avatar,nickname');
        $this->response($this->responseDataFormat(0,'请求成功',array('userInfo'=>$res,'imgServer'=>$this->getImgServer())));
    }

    /**
     * 详细信息
     */
    public function userCenterDetail(){
        $res = $this->user->getUserByUid(self::$currentUid,'nickname,sex,FROM_UNIXTIME(birthday) AS birthday');
        $this->response($this->responseDataFormat(0,'请求成功',array('userInfo'=>$res)));
    }

    /**
     * 用户详情页提交保存
     */
    public function userDetailSave(){
        $sex = intval($this->input->get_post('sex'));
        if($sex != 1 && $sex != 2){
            $sex = 1;
        }
        $data = array('sex'=>$sex);
        $res = $this->user->saveUserDetail(self::$currentUid,$data);
        if($res){
            $this->response($this->responseDataFormat(0,'保存成功',array()));
        }
    }

    /**
     * 用户上传头像
     */
    public function avatarUpload(){
        $this->load->library('Upload_image',null,'upload');
        if(!empty($_FILES['avatar'])){
            $filePath = $this->upload->save('avatar',$_FILES['avatar']['tmp_name']);
        }
        if($filePath){
            $data = array('avatar'=>$filePath);
            $this->user->saveUserDetail(self::$currentUid,$data);
            $this->response($this->responseDataFormat(0,'上传成功',array()));
        }else{
            $this->response($this->responseDataFormat(-1,'系统错误',array()));
        }
    }

    /**
     * 修改昵称
     */
    public function updateNickname(){
        $nickname = addslashes(trim($this->input->get_post('nickname')));
        $isExists = $this->user->getRecord('nickname',$nickname);
        if($isExists){
            $this->response($this->responseDataFormat(1,'昵称已经存在',array()));
        }
        $data = array('nickname'=>$nickname);
        $res = $this->user->saveUserDetail(self::$currentUid,$data);
        if($res){
            $this->response($this->responseDataFormat(0,'修改成功',array()));
        }
    }


    /**
     * 修改密码
     */

    public function updatePwd(){
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
     * 我的钱包首页
     */

    public function myMoneyIndex(){
        $money = $this->money->getUserMoney(self::$currentUid);
        $money = $money ? $money : 0;
        $this->response($this->responseDataFormat(0,'请求成功',array($money)));
    }

    /**
     * 充值待开发
     */
    public function recharge(){

    }

    /**
     * 提现页面
     */

    public function takeCashView(){
        $money = $this->money->getUserMoney(self::$currentUid);
        $money = $money ? $money : 0;
        $this->response($this->responseDataFormat(0,'请求成功',array($money)));
    }


    /**
     * 提现提交
     */
    public function takeCash(){
        $bank = addslashes(trim($this->input->get_post('bank')));
        $cardNum = addslashes(trim($this->input->get_post('cardNum')));
        $address = addslashes(trim($this->input->get_post('address')));
        $realName = addslashes(trim($this->input->get_post('realName')));
        $identity = addslashes(trim($this->input->get_post('identity')));
        $amount = floatval($this->input->get_post('amount'));
        $userType  = intval($this->input->get_post('userType'));
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
        if($userType != 1 && $userType != 2){
            $this->response($this->responseDataFormat(4,'用户类型异常',array()));
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
     * 交易记录
     */
    public function tradeLog(){
        $res = $this->trade_log->getListByUid(self::$currentUid,'tradeDesc,FROM_UNIXTIME(dateline) AS tradeTime,tradeVolume,tradeType');
        $this->response($this->responseDataFormat(0,'请求成功',array($res)));
    }

    /**
     * 在线问诊记录
     */
    public function onlineAskList(){
        $res = $this->online_ask->getListByUid(self::$currentUid,'id,docName,FROM_UNIXTIME(askTime) as dateline,askContent,state');
        $this->response($this->responseDataFormat(0,'请求成功',array($res)));
    }

    /**
     * 在线问诊详情
     */

    public function onlineAskDetail(){
        $id = intval($this->input->get_post('id'));
        $res = $this->online_ask->getDetailById(self::$currentUid,$id,'askNickname as name,docId,docName,askTelephone,FROM_UNIXTIME(hopeCalldate) AS hopeDate,phoneTimeLen as timeLen,price,askContent,state');

        $this->response($this->responseDataFormat(0,'请求成功',array($res)));
    }


    /**
     * 评价提交
     */

    public function evaluate(){
        $docId = intval($this->input->get_post('docId'));
        $content = addslashes($this->input->get_post('content'));
        $data = array(
            'docId'=>$docId,
            'docNicname'=>$this->user->getUserInfoByUid($docId,'nickname'),
            'uid'=>self::$currentUid,
            'username'=>$this->user->getUserInfoByUid(self::$currentUid,'nickname'),
            'content'=>$content,
            'dateline'=>time()
        );
        $res = $this->evaluate->addEvaluate($data);
        if($res){
            $this->response($this->responseDataFormat(0,'请求成功',array()));
        }else{
            $this->response($this->responseDataFormat(-1,'请求失败',array()));
        }
    }

    /*************我的预约****************/
    /**
     * 预约列表
     */
    public function appointList(){
        $res = $this->appoint->appointList(self::$currentUid,'YL_user_reg_num.id,YL_user_reg_num.docName,YL_hospital.name as hosName,YL_hospital.address,FROM_UNIXTIME(YL_user_reg_num.appointTime) AS dateline,YL_user_reg_num.status');
        $this->response($this->responseDataFormat(0,'请求成功',array($res)));
    }


    /**
     * 预约详情
     *
     */
    public function appointDetail(){
        $id = intval($this->input->get_post('id'));
        $res = $this->appoint->appointDetail(self::$currentUid,$id,'id,contacts,sex,docName,FROM_UNIXTIME(appointTime) as appointTime,appointTel,price,hosAddr,status');
        $this->response($this->responseDataFormat(0,'请求成功',array($res)));
    }


    /**
     * 取消预约
     */
    public function appointCancel(){
        $id = intval($this->input->get_post('id'));
        $res = $this->appoint->appointCancel(self::$currentUid,$id);
        if($res){
            $this->response($this->responseDataFormat(0,'请求成功',array()));
        }else{
            $this->response($this->responseDataFormat(-1,'系统错误',array()));
        }
    }


    /*************问答记录****************/


    /**
     * 列表
     */
    public function askAnswerList(){
        $res = $this->leaving_msg->getMsgList(self::$currentUid,'id,docName,FROM_UNIXTIME(askTime) AS dateline,askerContent as content,state');
        $this->response($this->responseDataFormat(0,'请求成功',array($res)));
    }

    /**
     * 详情
     */
    public function askAnswerDetail(){
        $id = intval($this->input->get_post('id'));
        $select = 'askerNickname as name,docName,askerPone,FROM_UNIXTIME(askTime) AS dateline,askerContent,img';
        $imgServer = $this->getImgServer();
        $res = $this->leaving_msg->getMsgDetail(self::$currentUid,$id,$select);
        $replySelect = 'replyContent';
        $reply = $this->reply-> getContentByThemeId(self::$currentUid,$id,$replySelect);
        $reply = $reply ? $reply : '';
        $this->response($this->responseDataFormat(0,'请求成功',array('askDetail'=>$res,'reply'=>$reply,'imgServer'=>$imgServer)));
    }




    /**********我的*************/


    /**
     * 购买记录
     */
    public function order(){
        $selectVac = 'YL_order.oid,YL_vaccinum.thumbnail,YL_order.packageTitle,(CASE WHEN `YL_order`.`status`=1 THEN "未付款" WHEN `YL_order`.`status`=2 OR  `YL_order`.`status`=3 OR `YL_order`.`status`=4 THEN "已付款" WHEN `YL_order`.`status` = 5 THEN "完成" END) AS `status`,YL_order.price';
        $selectGene = 'YL_order.oid,YL_gene_check.thumbnail,YL_order.packageTitle,(CASE WHEN `YL_order`.`status`=1 THEN "未付款" WHEN `YL_order`.`status`=2 OR  `YL_order`.`status`=3 OR `YL_order`.`status`=4 THEN "已付款" WHEN `YL_order`.`status` = 5 THEN "完成" END) AS `status`,YL_order.price';
        $resVac = $this->order->getOrdersByUid(self::$currentUid,$selectVac);  // 疫苗订单
        $resGene = $this->order->getOrdersByUid(self::$currentUid,$selectGene,2);  // 基因订单
        $arr = array();
        if(!empty($resVac)){
            foreach($resVac as $k=>$v){
                array_push($arr,$v);
            }
        }
        if(!empty($resGene)){
            foreach($resGene as $k=>$v){
                array_push($arr,$v);
            }
        }
        rsort($arr);
        $this->response($this->responseDataFormat(0,'请求成功',$arr));
    }

    /**
     * 我的帖子
     */

    public function postList(){
        $select = 'id,img,postTitle,postContent,from_unixtime(postTime) as dateline';
        $res = $this->post->myPostList(self::$currentUid,$select);
        $this->response($this->responseDataFormat(0,'请求成功',$res));
    }


    /**
     * 我的回复
     */

    public function myPostReply(){
        $select = 'YL_post.id as postId,YL_post_comment.id as commentId,YL_user.avatar,YL_user.nickname,from_unixtime(YL_post_comment.recmdTime) as dateline,YL_post_comment.recmdContent as commentContent,YL_post.postTitle,YL_post.postContent';
        $res = $this->post_comment->myReply(self::$currentUid,$select);
        $this->response($this->responseDataFormat(0,'请求成功',$res));
    }

    /**
     * 我的评论
     */

    public function myPostComment(){
        $select = 'YL_post.id as postId,YL_post_comment.id as commentId,YL_user.avatar,YL_user.nickname,from_unixtime(YL_post_comment.recmdTime) as dateline,YL_post_comment.recmdContent as commentContent,YL_post.postTitle,YL_post.postContent,YL_post_comment.state';
        $res = $this->post_comment->myComment(self::$currentUid,$select);
        $this->response($this->responseDataFormat(0,'请求成功',$res));
    }


    /**
     * 我的收藏
     */

    public function myCollections(){
        $select = 'YL_news_collections.id as collId,YL_news.thumbnail,YL_news.title,YL_news.content,FROM_UNIXTIME(YL_news.createTime) as dateline';
        $res = $this->collection->myCollections(self::$currentUid,$select);
        $this->response($this->responseDataFormat(0,'请求成功',$res));
    }


    /**
     * 删除收藏
     */

    public function delCollection(){
        $collId = intval($this->input->get_post('collId'));
        $res = $this->collection->delCollection($collId,self::$currentUid);
        if($res){
            $this->response($this->responseDataFormat(0,'删除成功',array()));
        }else{
            $this->response($this->responseDataFormat(1,'删除失败',array()));
        }
    }
}