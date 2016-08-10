<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用户模型
 */
class User_model extends MY_Model
{
    public static $table_name = 'user';

    /**
     * 用户注册
     * @param $mobile
     * @param $userType
     * @param $pwd
     * @return bool|int|mixed
     */
    public function reg($mobile,$userType,$pwd,$regIp){
        $data = array('phone'=>$mobile,'userType'=>$userType,'password'=>$pwd,'dateline'=>time(),'regIp'=>$regIp);
        $res = $this->insert($data);
        return $res;

    }

    /**
     * 获取用户手机号
     * @param $mobile
     * @return array
     */
    public function getUserMobile($mobile){
        $res = $this->find_by('phone',$mobile);
        return $res;
    }

    /**
     * 修改密码
     * @param $uid
     * @param $pwd
     * @return bool
     */
    public function reSettingPwd($uid,$pwd){
        $where = array('uid'=>$uid);
        $data = array('password'=>$pwd);
        $res = $this->update($where,$data);
        return $res;
    }

    /**
     * 检查用户登陆
     * @param $user  昵称或者手机号
     */
    public function getRecordByPhoneOrNickname($user){
        $where = array('nickname'=>$user,'phone'=>$user);
        $res = $this->find_by($where,'','or');
        return $res;
    }

    /**
     * 根据uid获取一条用户记录
     * @param $uid
     */
    public function getUserByUid($uid){
        $where = array('uid'=>$uid);
        $res = $this->find_by($where);
        return $res;
    }

    /**
     * 修改登录信息
     * @param $uid
     */
    public function updateLoginInfo($uid){
        $where = array('uid'=>$uid);
        $data = array('lastLoginTime'=>time(),'lastLoginIp'=>ip2long($_SERVER['REMOTE_ADDR']));
        $res = $this->update($where,$data);
        return $res;
    }

    /**
     * 获取医生列表
     * @param $officeId  科室id
     * @param $hid      医院id
     * @param $limit    获取条数
     * @param $select   查询字段
     * @return array
     */
    public function getDoctorList($limit,$select,$officeId=0,$hid=0,$keyword=''){
        $officeId = intval($officeId);
        $hid = intval($hid);
        if($officeId != 0){
            $this->where('YL_doctor_offices.id',$officeId);
        }
        if($hid != 0){
            $this->where('YL_hospital.hid',$hid);
        }
        if($keyword != ''){
            $this->like('YL_user.nickname',$keyword);
        }
        $where = array('userType'=>2);
        $this->select($select);
        $this->join('YL_doctor_info','YL_doctor_info.uid=YL_user.uid','left');
        $this->join('YL_hospital','YL_doctor_info.hid=YL_hospital.hid','left');
        $this->join('YL_doctor_offices','YL_doctor_offices.id=YL_doctor_info.officeId','left');
        $this->limit($limit);
        $res = $this->find_all_by($where);
        return $res;
    }

    /**
     * 获取医生详情
     * @param $docId
     */
    public function getDoctorDetail($docId,$select){
        $this->select($select);
        $this->where(array('YL_user.userType'=>2,'YL_user.uid'=>$docId));
        $this->join('YL_doctor_info','YL_doctor_info.uid=YL_user.uid','left');
        $this->join('YL_hospital','YL_doctor_info.hid=YL_hospital.hid','left');
        $this->join('YL_doctor_offices','YL_doctor_offices.id=YL_doctor_info.officeId','left');
        $res = $this->find_all();
        return $res;
    }



    public function getNickname($uid,$column){
        $where = array('uid'=>$uid);
        $res = $this->find_by($where);
        return $res[$column];
    }
}
