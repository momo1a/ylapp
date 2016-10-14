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
        $this->load->model('User_phone_diagnosis_model','online_ask');
        $this->load->model('Doctor_evaluate_model','evaluate');
        $this->load->model('User_reg_num_model','appoint');
        $this->load->model('User_leaving_msg_model','leaving_msg');
        $this->load->model('Doctor_reply_model','reply');
        $this->load->model('Order_model','order');
        $this->load->model('Post_model','post');
        $this->load->model('Post_comment_model','post_comment');
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
        $sex = intval($this->input->get_post('sex'));  //性别
        $nickname = addslashes(trim($this->input->get_post('nickname')));  // 昵称
        $birthday = strtotime($this->input->get_post('birthday'));  //  出生年月
        if($sex != 1 && $sex != 2){
            $sex = 1;
        }
        $isExists = $this->user->getRecord('nickname',$nickname);
        $currentNickname = $this->user->getUserByUid(self::$currentUid);
        if($isExists && $currentNickname['nickname'] != $nickname){
            $this->response($this->responseDataFormat(1,'昵称已经存在',array()));
        }
        //$res = $this->user->saveUserDetail(self::$currentUid,$data);
        /*if($res){
            $this->response($this->responseDataFormat(0,'修改成功',array()));
        }*/
        $data = array('sex'=>$sex,'nickname'=>$nickname,'birthday'=>$birthday);
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
        $this->updateMyPwd();
    }

    /**
     * 我的钱包首页
     */

    public function myMoneyIndex(){
        $this->myMoney();
    }


    /**
     * 提现页面
     */

    public function takeCashView(){
        $this->cashView();
    }


    /**
     * 提现提交
     */
    public function takeCash(){
        $this->takeCashAction(self::$_TYPE_USER);
    }


    /**
     * 交易记录
     */
    public function tradeLog(){
        $this->tradeLogView();
    }

    /**
     * 在线问诊记录
     */
    public function onlineAskList(){
        $limit = intval($this->input->get_post('limit'));
        $limit = $limit == 0 ? 10 : $limit;
        $offset = intval($this->input->get_post('offset'));
        $res = $this->online_ask->getListByUid(self::$currentUid,'id,docName,FROM_UNIXTIME(askTime) as dateline,price,askContent,(case when state=0 then "未支付" when state=1 then "待处理" when state=2 then "已确认沟通时间" when state=3 then "完成" when state=4 then "失败" end) as state',$limit,$offset);
        $this->response($this->responseDataFormat(0,'请求成功',array($res)));
    }

    /**
     * 在线问诊详情
     */

    public function onlineAskDetail(){
        $id = intval($this->input->get_post('id'));
        $res = $this->online_ask->getDetailById(self::$currentUid,$id,'id,askNickname as name,docId,docName,askTelephone,FROM_UNIXTIME(hopeCalldate) AS hopeDate,phoneTimeLen as timeLen,price,askContent,(case when state=0 then "未支付" when state=1 then "待处理" when state=2 then "已确认沟通时间" when state=3 then "完成" when state=4 then "失败" end) as state');
        $hosAddr = $this->user->getDoctorDetail($res['docId'],'YL_hospital.address as hosAddr,YL_doctor_offices.officeName');
        $res['hosAddr'] = $hosAddr[0]['hosAddr'];
        $res['officeName'] = $hosAddr[0]['officeName'];
        $this->response($this->responseDataFormat(0,'请求成功',array($res)));
    }


    /**
     *
     * 取消在线问诊
     */

    public function cancelOnlineAsk(){
        $id = intval($this->input->get_post('id'));
        $res = $this->online_ask->askOnlineCancel(self::$currentUid,$id);
        if($res){
            $this->response($this->responseDataFormat(0,'请求成功',array($res)));
        }else{
            $this->response($this->responseDataFormat(-1,'系统错误',array()));
        }


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
        $limit = intval($this->input->get_post('limit'));
        $limit = $limit == 0 ? 10 : $limit;
        $offset = intval($this->input->get_post('offset'));
        $res = $this->appoint->appointList(self::$currentUid,'YL_user_reg_num.id,YL_user_reg_num.docName,YL_hospital.name as hosName,YL_user_reg_num.price,YL_hospital.address,FROM_UNIXTIME(YL_user_reg_num.appointTime) AS dateline,(case when YL_user_reg_num.status=0 then "未支付" when YL_user_reg_num.status=2 then "待处理" when YL_user_reg_num.status=3 then "预约成功" when YL_user_reg_num.status=4 then "预约失败" when YL_user_reg_num.status=5 then "完成" end) as status',$limit,$offset);
        $this->response($this->responseDataFormat(0,'请求成功',array($res)));
    }


    /**
     * 预约详情
     *
     */
    public function appointDetail(){
        $id = intval($this->input->get_post('id'));
        $res = $this->appoint->appointDetail(self::$currentUid,$id,'id,contacts,sex,docId,docName,FROM_UNIXTIME(appointTime) as appointTime,appointTel,price,(case when status=0 then "未支付" when status=2 then "待处理" when status=3 then "预约成功" when status=4 then "预约失败" when status=5 then "完成" end) as status');
        $hosAddr = $this->user->getDoctorDetail($res['docId'],'YL_hospital.address as hosAddr,YL_doctor_offices.officeName');
        $res['hosAddr'] = $hosAddr[0]['hosAddr'];
        $res['officeName'] = $hosAddr[0]['officeName'];
        $this->response($this->responseDataFormat(0,'请求成功',array($res)));
    }


    /**
     * 取消预约
     */
    public function appointCancel(){
        $id = intval($this->input->get_post('id'));
        $res = $this->appoint->appointCancel(self::$currentUid,$id);
        if($res){
            $this->response($this->responseDataFormat(0,'请求成功',array($res)));
        }else{
            $this->response($this->responseDataFormat(-1,'系统错误',array()));
        }
    }


    /*************问答记录****************/


    /**
     * 列表
     */
    public function askAnswerList(){
        $limit = intval($this->input->get_post('limit'));
        $limit = $limit == 0 ? 10 : $limit;
        $offset = intval($this->input->get_post('offset'));
        $res = $this->leaving_msg->getMsgList(self::$currentUid,'id,docName,FROM_UNIXTIME(askTime) AS dateline,askerContent as content,(case when state=2 then "已支付" when state=4 then "已回复" end) as state',$limit,$offset);
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
        if(!empty($res)){
            $res['img'] = json_decode($res['img'],true);
        }
        $replySelect = 'replyContent';
        $reply = $this->reply->getContentByThemeId(self::$currentUid,$id,$replySelect);
        $reply = $reply ? $reply : '';
        $this->response($this->responseDataFormat(0,'请求成功',array('askDetail'=>$res,'reply'=>$reply,'imgServer'=>$imgServer)));
    }




    /**********我的*************/


    /**
     * 购买记录
     */
    public function order(){
        $limit = intval($this->input->get_post('limit'));
        $limit = $limit == 0 ? 10 : $limit;
        $offset = intval($this->input->get_post('offset'));
        $selectVac = 'YL_order.oid,YL_order.packageId as gid,YL_vaccinum.thumbnail,YL_order.packageTitle,(CASE WHEN `YL_order`.`status`=1 THEN "未付款" WHEN `YL_order`.`status`=2 OR  `YL_order`.`status`=3 OR `YL_order`.`status`=4 THEN "已付款" WHEN `YL_order`.`status` = 5 THEN "完成" END) AS `status`,YL_order.price,(case when YL_order.type=1 then "疫苗订单" end) as orderType';
        $selectGene = 'YL_order.oid,YL_order.packageId as gid,YL_gene_check.thumbnail,YL_order.packageTitle,(CASE WHEN `YL_order`.`status`=1 THEN "未付款" WHEN `YL_order`.`status`=2 OR  `YL_order`.`status`=3 OR `YL_order`.`status`=4 THEN "已付款" WHEN `YL_order`.`status` = 5 THEN "完成" END) AS `status`,YL_order.price,(case when YL_order.type=2 then "基因订单" end) as orderType';
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
        $res = array_slice($arr,$offset,$limit,true);
        $imgServer = $this->getImgServer();
        $this->response($this->responseDataFormat(0,'请求成功',array('order'=>$res,'imgServer'=>$imgServer)));
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
     * 我的收藏列表
     */

    public function myCollections(){
        $this->collectionList();
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


    /**
     * 反馈意见
     */


    public function feedback(){
        $this->commitFeedback();
    }


    /**
     * 退出登录
     */
    public function logout(){
        $this->cache->delete(md5(self::$privateToken));
        $this->response($this->responseDataFormat(0,'请求成功',array()));
    }



    /**
     * 用户首页消息列表
     */
    public function msgList(){
        $msgF = $this->online_ask->getListByUid(self::$currentUid,'id,docName,askContent,FROM_UNIXTIME(askTime) as dateline,(case when state=0 then "未支付" when state=1 then "待处理" when state=2 then "已确认沟通时间" when state=3 then "完成" when state=4 then "失败" end) as state,(case when not isnull(docId) then "在线问诊" end) as msgType',1000);
        $msgS = $this->appoint->appointList(self::$currentUid,'YL_user_reg_num.id,docName,officeName,YL_hospital.name as hosName,address,FROM_UNIXTIME(dateline) as dateline,(case when YL_user_reg_num.status=0 then "未支付" when YL_user_reg_num.status=2 then "待处理" when YL_user_reg_num.status=3 then "预约成功" when YL_user_reg_num.status=4 then "预约失败" when YL_user_reg_num.status=5 then "完成" end) as state,(case when not isnull(docId) then "预约挂号" end) as msgType',1000);
        $msgT = $this->leaving_msg->getMsgList(self::$currentUid,'id,docName,from_unixtime(askTime) as dateline,(case when state=0 then "未支付" when state=2 then "通过" when state=3 then "不通过" when state=4 then "完成" when state=5 then "待审核" end) as state,(case when not isnull(docId) then "留言问答" end) as msgType',1000,0,'(state IN(0,2,3,4,5))');
        $msgFo = $this->order->getOrdersMsg(self::$currentUid,'oid as id,packageId as gid,packageTitle,from_unixtime(dateline) as dateline,(case when status=1 then "待支付" when status=2 then "已支付" when status=3 then "待处理" when status=4 then "已通知" when status=5 then "完成" end) as state,(case when type=1 then "疫苗接种" when type=2 then "基因检测" end) as msgType');
        $msg = array();
        $i = 0;

        $this->orderContainer($msgF,$i,$msg);
        $this->orderContainer($msgS,$i,$msg);
        $this->orderContainer($msgT,$i,$msg);
        $this->orderContainer($msgFo,$i,$msg);
        $this->sortArrByField($msg,'dateline',true);

        $this->response($this->responseDataFormat(0,'请求成功',array('msgList'=>$msg,'count'=>$i)));
    }


    /**
     * @param $order
     * @param $i
     * @param $container
     */
    protected function orderContainer($order,&$i,&$container){
        if(is_array($order)){
            if(!empty($order)){
                foreach($order as $val){
                    array_push($container,$val);
                    $i++;
                }
            }
        }
    }

    /**
     * 多维数组排序
     * @param $array
     * @param $field
     * @param bool $desc
     */
    protected function sortArrByField(&$array, $field, $desc = false){
        $fieldArr = array();
        if(!empty($array)){
            foreach ($array as $k => $v) {
                $fieldArr[$k] = $v[$field];
            }
        }
        $sort = $desc == false ? SORT_ASC : SORT_DESC;
        array_multisort($fieldArr, $sort, $array);
    }
}