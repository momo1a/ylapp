<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 用户预约挂号模型
 * Author: momo1a@qq.com
 * Date: 2016/8/12
 * Time: 10:11
 */

class User_reg_num_model extends MY_Model
{
    public static $table_name = 'user_reg_num';

    public function __construct(){
        parent::__construct();
    }


    /**
     * 挂号第一步生成记录
     */
    public function firstStep($data){
        $this->insert($data);
        return $this->db->insert_id();
    }

    /**
     * 获取用户预约列表
     * @param $uid
     */
    public function appointList($uid,$select="*",$limit=10,$offset=0,$userType=1){

        switch($userType){
            case 2 :
                $this->where(array('docId'=>$uid));
                break;
            default :
                $this->where(array('userId'=>$uid));
        }
        //$this->where('(status IN(0,2,3,4,5))');
        $this->select($select);
        $this->join('YL_doctor_info','YL_doctor_info.uid=YL_user_reg_num.docId','left');
        $this->join('YL_hospital','YL_doctor_info.hid=YL_hospital.hid','left');
        $this->order_by(array('dateline'=>'DESC'));
        $this->limit($limit);
        $this->offset($offset);
        $res = $this->find_all();
        return $res;
    }


    /**
     * @param $uid
     * @param $id
     * @param string $select
     */
    public function appointDetail($uid,$id,$select="*"){
        $this->where(array('userId'=>$uid));
        $this->select($select);
        $res = $this->find($id);
        return $res;
    }

    /**
     * 取消预约
     * @param $uid
     * @param $id
     */
    public function appointCancel($uid,$id){
        $where = array('userId'=>$uid,'id'=>$id);
        $data = array('cancelTime'=>time(),'status'=>6);
        $res = $this->update($where,$data);
        /*执行成功后查询数据返回*/
        if($res){
            $this->select('YL_hospital.name as hosName,YL_doctor_offices.officeName,YL_user_reg_num.docName,FROM_UNIXTIME(YL_user_reg_num.cancelTime) AS cancelTime');
            $this->where(array('YL_user_reg_num.id'=>$id));
            $this->join('YL_doctor_info','YL_doctor_info.uid=YL_user_reg_num.docId','left');
            $this->join('YL_doctor_offices','YL_doctor_offices.id=YL_doctor_info.officeId','left');
            $this->join('YL_hospital','YL_hospital.hid=YL_doctor_info.hid','left');
            $result = $this->find_all();
            return $result;
        }else{
            return false;
        }
    }


    /**
     * @param string $keyword
     * @param int $limit
     * @param int $offset
     */
    public function getAppointList($keyword='',$limit=10,$offset=0,$state=-1,$select='*'){
        if($keyword != ''){
            $this->like(array('contacts'=>$keyword));
        }
        if($state != -1){
            $this->where(array('YL_user_reg_num.status'=>$state));
        }
        $this->select($select);
        $this->join('YL_doctor_info','YL_doctor_info.uid=YL_user_reg_num.docId','left');
        $this->join('YL_user','YL_doctor_info.uid=YL_user.uid','left');
        $this->join('YL_doctor_offices','YL_doctor_offices.id=YL_doctor_info.officeId','left');
        $this->join('YL_hospital','YL_hospital.hid=YL_doctor_info.hid','left');
        $this->limit($limit);
        $this->offset($offset);
        return $this->find_all();
    }

    /**
     * 统计
     * @param $keyword
     */
    public function countAppoint($keyword='',$state= -1){
        if($state != -1){
            $this->where(array('status'=>$state));
        }
        if($keyword != ''){
            $this->like(array('contacts'=>$keyword));
        }
        return $this->count_all();
    }




    /**
     * 获取医生端预约列表
     * @param $docId
     * @param string $select
     * @param int $flag
     * @return array
     */
    public function getDoctorRegList($docId,$select="*",$flag=1,$limit=10,$offset=0){
        $this->where(array('docId'=>$docId));
        switch($flag){
            case 1:   //未完成预约列表
                $this->where('(YL_user_reg_num.status IN(3))');
                break;
            case 2:   //已完成预约列表
                $this->where(array('YL_user_reg_num.status'=>5));
                break;
            default:
                exit(json_encode(array('code'=>305,'msg'=>"flag参数非法",array())));
        }
        $this->join('YL_user','YL_user.uid=YL_user_reg_num.userId','left');
        $this->join('YL_user_illness_history','YL_user_illness_history.illId=YL_user_reg_num.illnessId','left');
        $this->select($select);
        $this->limit($limit);
        $this->offset($offset);
        $res = $this->find_all();
        return $res;

    }

    /**
     * 获取预约详情医生端
     * @param $id
     *
     */
    public function getDoctorRegDetail($id,$select='*'){
        $this->select($select);
        $this->join('YL_user','YL_user.uid=YL_user_reg_num.userId','left');
        $this->join('YL_user_illness_history','YL_user_illness_history.illId=YL_user_reg_num.illnessId','left');
        $res = $this->find($id);
        return $res;
    }

    /**
     * 获取详情
     * @param $oid
     * @return array
     */
    public function getDetail($oid){
        $where = array('id'=>$oid);
        return $this->find_by($where);
    }



    /**
     * 修改状态
     * @param $oid
     * @param $status
     * @return bool
     */
    public function settingStatus($oid,$status){
        $where = array('id'=>$oid);
        $updateData = array('status'=>$status);
        $currentTime = time();
        /*开始事务*/
        $this->db->trans_begin();

        $this->update($where,$updateData);  // 更新状态
        $orderInfo = $this->select('*,d.nickname as docName,u.nickname as userName,YL_user_reg_num.price as AppointFee')
            ->join('YL_user as d','d.uid=YL_user_reg_num.docId','left')
            ->join('YL_user as u','u.uid=YL_user_reg_num.userId','left')
            ->join('YL_doctor_fee_seting as s','s.docId=YL_user_reg_num.docId','left')
            ->find_by($where);
        switch(intval($status)){
            case 3:
                $tradeDesc = '预约成功';
                $stat = 1;
                break;
            case 4:
                $tradeDesc = '预约失败';
                $stat = 2;
                break;
            case 5:
                // 预约费用款项分配到医生钱包
                if(!$orderInfo['regNumPer']){
                    $orderInfo['regNumPer'] = 0;
                }
                $docGetFee = bcmul($orderInfo['AppointFee'],$orderInfo['regNumPer']/100,2);  //  医生获得费用
                $this->db->query('UPDATE YL_money set `amount`=`amount`+'.$docGetFee.',`updateTime`='.$currentTime.' WHERE `uid`='.$orderInfo['docId']);
                if($this->db->affected_rows() == 0){
                    $this->db->insert('money',array('uid'=>$orderInfo['docId'],'amount'=>$docGetFee,'updateTime'=>$currentTime));
                }

                //  trade log 表
                $tradeLog = array(
                    'uid' => $orderInfo['docId'] ,
                    'userType' => 2,
                    'tradeVolume' => $docGetFee,
                    'tradeDesc'=> '预约挂号收入',
                    'tradeChannel'=> 0,
                    'dateline'=>time(),
                    'status'=>1,
                    'tradeType'=>6,
                );

                $this->db->insert('trade_log', $tradeLog);

                $tradeDesc = '预约完成';
                break;
            default:
                $tradeDesc = '未知';
        }
        /*交易记录数据*/
        $insertData = array(
            'uid'=>$orderInfo['userId'],
            'userType'=>1,
            'tradeVolume'=>$orderInfo['price'],
            'tradeDesc'=>$tradeDesc,
            'tradeChannel'=>0,
            'dateline'=>$currentTime,
            'status'=>$stat,
            'tradeType'=>7
        );


        if($status == 4 || $status == 3) {
            $this->db->insert('trade_log', $insertData); //  交易记录
        }

        /*目前需求退款*/
        if($status == 4){
            $this->db->query('UPDATE YL_money set `amount`=`amount`+'.$orderInfo['price'].',`updateTime`='.$currentTime.' WHERE `uid`='.$orderInfo['userId']);
            if($this->db->affected_rows() == 0){
                $this->db->insert('money',array('uid'=>$orderInfo['userId'],'amount'=>$orderInfo['price'],'updateTime'=>$currentTime));
            }
        }


        $docUserLog = array(
            'userId' => $orderInfo['userId'],
            'doctorId' => $orderInfo['docId'],
            'comType'=>3,
            'comState'=>$status,
            'description'=>$tradeDesc,
            'dateline'=>$currentTime,
        );

        // 用户医生日志表

        $this->db->insert('user_doctor_log',$docUserLog);



        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }
        else
        {
            $this->db->trans_commit();
            return true;
        }
    }

    /**
     * 修改预约时间
     * @param $oid
     * @param $time
     */
    public function updateAppointTime($oid,$time){
        $where = array('id'=>$oid);
        $res = $this->update($where,array('appointTime'=>$time));
        return $res;
    }

    //  获取管理员未处理的
    public function getNotDeal($select='*'){
        $this->select($select,false);
        $this->where(array('status'=>2));
        return $this->find_all();
    }

}